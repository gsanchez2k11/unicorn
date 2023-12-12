<?php
namespace unicorn\clases\funciones\magento;
require_once 'Conectar.php';
use unicorn\clases\funciones\magento\StockItem as stock_item;
require_once RAIZ . '/clases/funciones/magento/StockItem.php';
use unicorn\clases\funciones\magento\Articulos as articulos;
require_once RAIZ . '/clases/funciones/magento/Articulos.php';
use unicorn\clases\funciones\magento\Inventory as inventory;
require_once RAIZ . '/clases/funciones/magento/Inventory.php';
use unicorn\clases\funciones\unicorn_db\General as general;
require_once RAIZ . '/clases/funciones/unicorn_db/General.php';
use unicorn\clases\funciones\unicorn_db\Config as config;
require_once RAIZ . '/clases/funciones/unicorn_db/Config.php';
use unicorn\clases\objetos\Articulos\Articulo_tarifa_inforpor as articulo_inforpor;
require_once RAIZ . '/clases/objetos/Articulos/Articulo_tarifa_inforpor.php';
?>
<?php
class Cronmage extends Conectar
{
public static function actualizaMage($tarifa_inforpor, $id_tienda = 1){
    $todos_articulos = articulos::getAllProducts($id_tienda); //Pedimos el catálogo completo de magento
    //Filtramos para quitar los plotters de Epson
$todos_articulos = array_filter($todos_articulos, function ($art){
  $arr_custom_attrs = (array) $art->custom_attributes;
    $codes = array_column($arr_custom_attrs,'attribute_code');
    $values = array_column($arr_custom_attrs,'value');
    $custom = array_combine($codes,$values); //Un array asociativo con los custom attributes
  if (($art->attribute_set_id == 39 || $art->attribute_set_id == 9) && isset( $custom['manufacturer']) && $custom['manufacturer'] == 3) {
  return false;
  }
  return $art;
  });
    $low_stock_items = stock_item::getLowStockItems($id_tienda); //Pedimos todos los stocks_id
    $arts_mage = array_map(function ($a) use ($low_stock_items,$tarifa_inforpor){
        /*$arr_dev = $a; //creamos el array con los datos existentes para pruebas
        $arr = array(
          'stock_item' => array(),
          'cost' => array()
        );*/
        $arr = array();
        foreach ($low_stock_items->items as $key => $value) { //Recorremos ahora el array de articulos y stock          
        if ($a->id == $value->product_id) { //Si encontramos el articulo actual podemos proceder

       //   $arr['stock_item']['stock_item_id'] = $value->item_id; //Asignamos el stock item id para poder actualizar
        //  $arr_dev->stock_item_id = $value->item_id;
          $datos_inforpor = self::matchIfp($a,$tarifa_inforpor);
          $cost = 0;

          if (!empty($datos_inforpor)) {
            $arr = array(
              'stock_item' => array(
                'stock_item_id' => $value->item_id,
                'sku' => $a->sku
              ),
              'cost' => array(
                'sku' => $a->sku
              )
            );
        
            foreach ($a->custom_attributes as $key => $atr) {
              if ($atr->attribute_code == 'cost') {
                $cost = $atr->value;
              }
            }
           // $arr['stock_item']['sku'] = $a->sku;
           // $arr['cost']['sku'] = $a->sku;
            if ($datos_inforpor['qty'] != $value->qty) { //comparamos el stock actual con el recibido
              $arr['stock_item']['qty'] = $datos_inforpor['qty'];
            $arr['stock_item']['is_in_stock'] = $datos_inforpor['is_in_stock'];
            }
            if ($datos_inforpor['precio'] != $cost) { //comparamos el coste actual con el recibido
              $arr['cost']['cost'] = $datos_inforpor['precio'];    
            }
          }
        }
        }
        
        return $arr;
        },$todos_articulos);
//Filtramos para dejar solo los que vamos a poder actualizar
$arts_mage_qty = array_filter($arts_mage, function ($b) {
  return isset($b['stock_item']['qty']);
});
foreach ($arts_mage_qty as $a) {
//Llamamos a la función que actualiza
$actualizar_stock = stock_item::putStockItemByEntry($a);
}
//Filtramos ahora y dejamos solos los que tenemos que actualizar coste
$arts_mage_cost = array_filter($arts_mage, function ($b) {
  return isset($b['cost']['cost']);
});
foreach ($arts_mage_cost as $b) {
  $artic = array(
    'custom_attributes' => array(
      'cost' => $b['cost']['cost']
    )
  );
  $actualizar_cost = articulos::actualizarArticulo($b['cost']['sku'],$artic);
}
}

/**Función que actualiza el stock en la nueva version de magento */
public static function actualizaMage24($tarifa_inforpor){
  $stock = inventory::getSourceItems('inforpor'); //Pedimos el listado de artículos que usan como fuente de stock inforpor y los datos de inventario
  foreach ($stock as $key => $value) {
    $datos_inforpor = self::matchIfp($value,$tarifa_inforpor);

    if (!empty($datos_inforpor)) {
      $stock[$key]->quantity = $datos_inforpor['qty'];
      $stock[$key]->status = $datos_inforpor['is_in_stock'];
    }

}
$actualizaar = inventory::postSourceItems($stock); //Actualizamos el stock
}

public static function matchIfp($articulo_mage,$tarifa_inforpor){
    $array_magento = array();
    $found_key = array_search($articulo_mage->sku, array_column($tarifa_inforpor, 'referencia')); //Buscamos la referencia
    if ($found_key === false) { //Si no se encuentra el artículo devuelve false
      $found_key = array_search($articulo_mage->sku, array_column($tarifa_inforpor, 'referencia2')); //Buscamos la referencia
    }
    if ($found_key === false && isset($articulo_mage->ean)) { //Si no se encuentra el artículo devuelve false
      $found_key = array_search($articulo_mage->ean, array_column($tarifa_inforpor, 'ean')); //Buscamos la referencia
    }
    if ($found_key === false && isset($articulo_mage->cod_inforpor)) { //Como último recurso intentamos emparejar por el codigo insertado en la bbdd para esta entidad
      $found_key = array_search($articulo_mage->cod_inforpor, array_column($tarifa_inforpor, 'codigo')); //Buscamos el código
    }
    if ($found_key !== false) {
      $qty = intval($tarifa_inforpor[$found_key]['stock']) + intval($tarifa_inforpor[$found_key]['custodia']) + intval($tarifa_inforpor[$found_key]['reserva']);
      $array_magento['qty'] = $qty;
        $array_magento['is_in_stock'] = $qty > 0 ? 1 : 0;
        $array_magento['precio'] =  $tarifa_inforpor[$found_key]['precio'] + $tarifa_inforpor[$found_key]['lpi'];
    }
    return $array_magento;
  }


  public static function preciosM246($tarifa_inforpor){
  //La tarifa inforpor es un array y necesitamos un array de objetos
$arr_objetos = array();
foreach ($tarifa_inforpor as $art_inforpor) {
$arr_objetos[] = new articulo_inforpor($art_inforpor);
}

    $params = array(
      'p' => 1,
      'auto_actualiza_precio' => 1,
      'tienda' => 2
  );
  //Pedimos los artículos auto actualizables
  $listar_articulos = articulos::buscarArticulos($params);
  //echo '<pre>';
  //print_r($listar_articulos);
  //echo '</pre>';
  
  $actualizables = array();
  //recorremos el listado recibido
  foreach ($listar_articulos->items as $articulo_magento) {
   /*   $ean = '-';    
      $cost = '-';
      $product_brand = '-';
      $margen = '-';
      foreach ($articulo_magento->custom_attributes as $custom_attr) { //Buscamos los custom attributes que nos puedan interesar
  
          if ($custom_attr->attribute_code == 'ean') {
          $ean = $custom_attr->value;
          }
          if ($custom_attr->attribute_code == 'cost') {
              $cost = $custom_attr->value;
              }
              if ($custom_attr->attribute_code == 'product_brand') {
                  $product_brand = $custom_attr->value;
                  }
                  if ($custom_attr->attribute_code == 'margen') {
                      $margen = $custom_attr->value;
                      }
          } */
  $custom_attributes = articulos::getAttrs($articulo_magento, 'custom_attributes');

//  $actualizable = array_filter($tarifa_inforpor, function ($tifp) use ($articulo_magento,$ean,$cost) {
    $actualizable = array_filter($arr_objetos, function ($tifp) use ($articulo_magento, $custom_attributes) {
  return (($articulo_magento->sku == $tifp->getReferencia() || $articulo_magento->sku == $tifp->getReferencia2() || (isset($custom_attributes['ean']) && $custom_attributes['ean'] == $tifp->getEan())) && (!isset($custom_attributes['cost']) || $custom_attributes['cost'] != $tifp->getPrecio()));
  });

  //  echo '<pre>';
  //  print_r($actualizable);
  //  echo '</pre>';


  if (count($actualizable) > 0) {
    //echo '<pre>';
    //print_r($custom_attributes['cost']);
    //print_r(reset($actualizable)->getMejorPrecio());
    //echo '</pre>';


  $actualizables[] = array(
      'id' => $articulo_magento->id,
      'sku' => $articulo_magento->sku,
      'price' => $articulo_magento->price,
      'attribute_set_id' => $articulo_magento->attribute_set_id,
      'product_brand' => $custom_attributes['product_brand'],
      'margen' => $custom_attributes['margen'] ?? 0,
      'cost' =>reset($actualizable)->getMejorPrecio()
  );
  }
  }
  
  //recorremos ahora el array de artículos a actualizar
  if (count($actualizables) > 0) {
 //   echo '<pre>';
  //  print_r($actualizables);
  //  echo '</pre>';
  //Lista con todas las familias y subfamilias
  $familias_margenes = general::listar('familias_margenes');
  
  foreach ($actualizables as $art) {
   if ($art['margen'] > 0 ) { //Si ese artículo tiene un margen individual definido lo aplicamos
  $margen =     $art['margen'];
  
   }else { //En caso contrario tenemos que buscar por conjunto de atributos o fabricante
      $coincidencia = array_filter($familias_margenes, function($at) use ($art) {
  if ($at['conjunto_atributos'] == $art['attribute_set_id'] && $at['fabricante'] == $art['product_brand']) { //Le damos prioridad si coincide conjunto de atributos y fabricante
      return true;
  } else {
      if ($at['conjunto_atributos'] == $art['attribute_set_id'] && $at['fabricante'] == '') {
          return true;
      } elseif($at['fabricante'] == $art['product_brand'] && $at['conjunto_atributos'] == '') {
          return true;
      }
      
  }
  return false;
      });

   if (count($coincidencia) > 0 && is_numeric(reset($coincidencia)['margen'])) {
 
  $margen = reset($coincidencia)['margen'];
   } else { //En este caso el artículo es auto actualizable pero no tiene definido un margen, ni coinciden con ninguna familia o fabricante que lo tenga, así que le aplicamos un margen general que vamos a definir en la bbdd
  $margen = config::dameValorConfig('margen_venta_general');
   }  
   }
   
   if (isset($margen) && is_numeric($margen)) {
      $precio_venta = $art['cost'] * (1+($margen/100));
      $attrs['price'] = $precio_venta;
   }
   
  
   /*  $attrs['custom_attributes'] = array(
      'custom_attributes' => array(
          'attribute_code' => 'cost',
          'value' => $art['cost']
          )
  ); */
  $attrs['custom_attributes'] = array(
    [
        'attribute_code' => 'cost',
        'value' => $art['cost']
    ]   
    );
     $update = articulos::actualizarArticulo($art['sku'],$attrs,2);
     echo '<pre>';
     print_r($update);
     echo '</pre>';
  
  //echo '<pre>';
  //print_r($art);
  //print_r($attrs);
  //echo '</pre>';
  }
  }

  }

}
?>
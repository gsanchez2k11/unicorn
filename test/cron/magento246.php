<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . '/../../config.php.inc';
require_once(RAIZ . '/vendor/autoload.php');

use unicorn\clases\funciones\inforpor\Tarifa as tarifa;
require RAIZ . '/clases/funciones/inforpor/Tarifa.php';
use unicorn\clases\funciones\magento\Articulos as articulos;
require RAIZ . '/clases/funciones/magento/Articulos.php';
use unicorn\clases\funciones\unicorn_db\General as general;
require_once RAIZ . '/clases/funciones/unicorn_db/General.php';
use unicorn\clases\funciones\unicorn_db\Config as config;
require_once RAIZ . '/clases/funciones/unicorn_db/Config.php';

$tarifa_inforpor = tarifa::dameTarifaRemota();
$params = array(
  'p' => 1,
  'auto_actualiza_precio' => 1,
  'tienda' => 2
);
//Pedimos los artículos auto actualizables
$listar_articulos = articulos::buscarArticulos($params);

$actualizables = array();
foreach ($listar_articulos->items as $articulo_magento) {
 // echo '<pre>';
  //print_r($art);
 // print_r($articulo_magento);
 // echo '</pre>';
  if (is_object($articulo_magento)) {
  $custom_attributes = articulos::getAttrs($articulo_magento, 'custom_attributes');
  $actualizable = array_filter($tarifa_inforpor, function ($tifp) use ($articulo_magento, $custom_attributes) {
//    return ($articulo_magento->sku == $tifp->getReferencia() || $articulo_magento->sku == $tifp->getReferencia2() || (isset($custom_attributes['ean']) && $custom_attributes['ean'] == $tifp->getEan())) && (!isset($custom_attributes['cost']) || $custom_attributes['cost'] != $tifp->getPrecio());
    return (($articulo_magento->sku == $tifp->getReferencia() || $articulo_magento->sku == $tifp->getReferencia2() || (isset($custom_attributes['ean']) && $custom_attributes['ean'] == $tifp->getEan()) )&& $articulo_magento->attribute_set_id == 10 && isset($custom_attributes['product_brand']) && $custom_attributes['product_brand'] == 2);
    });
    if (count($actualizable) > 0) {
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
}



  if (count($actualizables) > 0) {
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
       $attrs['custom_attributes'] = array(
        [
            'attribute_code' => 'cost',
            'value' => $art['cost']
        ]   
        );
      //  echo '<pre>';
      //  print_r($art['sku']);
      //  print_r($attrs);
      //  echo '</pre>';

        $update = articulos::actualizarArticulo($art['sku'],$attrs,2);
        echo '<pre>';
        print_r($update);
        echo '</pre>';

    }
  }






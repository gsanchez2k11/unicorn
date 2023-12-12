<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . '/../../config.php.inc';
require_once(RAIZ . '/vendor/autoload.php');
$tiempo_inicial = microtime(true); //true es para que sea calculado en segundos

use unicorn\clases\funciones\magento\Articulos as articulos;
require_once RAIZ . '/clases/funciones/magento/Articulos.php';
use unicorn\clases\funciones\inforpor\Tarifa as tarifa_inforpor;
require_once RAIZ . '/clases/funciones/inforpor/Tarifa.php';
use unicorn\clases\funciones\magento\StockItem as stock_item;
require_once RAIZ . '/clases/funciones/magento/StockItem.php';
use unicorn\clases\funciones\magento\Cronmage as cron;
require_once RAIZ . '/clases/funciones/magento/Cronmage.php';


//var_dump($array); // print array
$tarifa_inforpor_json = tarifa_inforpor::dameJSONTarifa(); //Cargamos la última tarifa
$tarifa_inforpor = json_decode($tarifa_inforpor_json, true, 512); //la convertimos desde el JSON
$todos_articulos = articulos::getAllProducts(); //Pedimos el catálogo completo de magento
$todos_articulos = array_filter($todos_articulos, function ($art){
  $arr_custom_attrs = (array) $art->custom_attributes;
    $codes = array_column($arr_custom_attrs,'attribute_code');
    $values = array_column($arr_custom_attrs,'value');
    $custom = array_combine($codes,$values); //Un array asociativo con los custom attributes
      if (($art->attribute_set_id == 39 || $art->attribute_set_id == 9) && $custom['manufacturer'] == 3) {
      return false;
      }
      return $art;
      });
  /*$todos_articulos = array_filter($todos_articulos, function ($art){
    if ($art->sku == 'C11CD81404') {
    return $art;
    }
    //return $art;
    });*/


$low_stock_items = stock_item::getLowStockItems(); //Pedimos todos los stocks_id

$arts_mage = array_map(function ($a) use ($low_stock_items,$tarifa_inforpor){
  $arr = array();
//$arr_dev = $a; //creamos el array con los datos existentes para pruebas
foreach ($low_stock_items->items as $key => $value) { //Recorremos ahora el array de articulos y stock
if ($a->id == $value->product_id) { //Si encontramos el articulo actual podemos proceder
 // $arr['stock_item']['stock_item_id'] = $value->item_id; //Asignamos el stock item id para poder actualizar
  $datos_inforpor = cron::matchIfp($a,$tarifa_inforpor);
  echo '<pre>';
  print_r($datos_inforpor);
  //print_r($art);
  echo '<pre>';
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
//echo '<pre>';
//print_r($arts_mage);
//print_r($art);
//echo '<pre>';
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

/**
 * En la versión 2.2 no podemos hacer bulk import
 */
/*foreach ($arts_mage as $key => $value) {
  $actualizar = stock_item::putStockItemByEntry($value);
  echo '<pre>';
print_r($actualizar);
//print_r($art);
echo '<pre>';
}*/







$tiempo_final = microtime(true);
 $tiempo = $tiempo_final - $tiempo_inicial; //este resultado estará en segundos
 echo "El tiempo de ejecución del archivo ha sido de " . $tiempo . " segundos";
 ?>

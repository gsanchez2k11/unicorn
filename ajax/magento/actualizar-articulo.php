<?php
error_reporting(E_ALL);                                     //Activamos el reporte de errores
ini_set('display_errors', 'on');
require_once __DIR__ . '/../../config.php.inc';

require_once RAIZ . '/clases/funciones/magento/Articulos.php';
use unicorn\clases\funciones\magento\Articulos as articulos;

//echo '<pre>';
//print_r($_POST);
//echo '</pre>';
//$ultimos_pedidos = pedidos::dameUltimosPedidosObj();
//$items = $ultimos_pedidos->items;
$sku = isset($_POST['mage']['sku']) ? $_POST['mage']['sku'] : $_POST['sku'];

if (isset($_POST['precio_venta'])) $atributos['price'] = $_POST['precio_venta'];
if (isset($_POST['price'])) $atributos['price'] = $_POST['price'];

if (isset($_POST['status'])) $atributos['status'] = $_POST['status'];
if (isset($_POST['name'])) $atributos['name'] = $_POST['name'];
//if (isset($_POST['cost'])) $atributos['cost'] = $_POST['cost'];

//$price = $_POST['precio_venta'];
if(isset($_POST['special_to_date'])) {                                          //Si tenemos la fecha de fin de oferta
  $atributos['custom_attributes'][] = array (
    'attribute_code' => 'special_to_date',
    'value' => $_POST['special_to_date']
  );
}
if(isset($_POST['special_from_date'])) {                                          //Si tenemos la fecha de fin de oferta
  $atributos['custom_attributes'][] = array (
    'attribute_code' => 'special_from_date',
    'value' => $_POST['special_from_date']
  );
}
if(isset($_POST['special_price'])) {                                          //Si tenemos la fecha de fin de oferta
  $atributos['custom_attributes'][] = array (
    'attribute_code' => 'special_price',
    'value' => $_POST['special_price']
  );
}
if(isset($_POST['fin_oferta_descripcion'])) {                                          //Si tenemos la fecha de fin de oferta
  $atributos['custom_attributes'][] = array (
    'attribute_code' => 'fin_oferta_descripcion',
    'value' => $_POST['fin_oferta_descripcion']
  );
}
if(isset($_POST['cost'])) {                                          //Si tenemos la fecha de fin de oferta
  $atributos['custom_attributes'][] = array (
    'attribute_code' => 'cost',
    'value' => $_POST['cost']
  );
}
if(isset($_POST['auto_actualizable'])) {                                          //Si tenemos el valor de auto actualización

$code = $_POST['plataforma'] == 'mage' ? 'auto_actualizable' : 'auto_actualiza_precio';

  $atributos['custom_attributes'][] = array (
    'attribute_code' => $code,
    'value' => $_POST['auto_actualizable']
  );
}


if(isset($_POST['extension_attributes'])) $atributos['extension_attributes'] = $_POST['extension_attributes'];
if(isset($_POST['custom_attributes'])) $atributos['custom_attributes'] = $_POST['custom_attributes'];

//echo '<pre>';
//print_r($atributos);
//echo '</pre>';

$resultados = array();

if (isset($_POST['plataforma'])) {
  $id_tienda = $_POST['plataforma'] == 'magento-2-beta' || $_POST['plataforma'] == 'mage245' ? 2 : 1 ; //Pasamos la plataforma para poder actualizar en la nueva versión
  $actualizar = articulos::actualizarArticulo($sku,$atributos,$id_tienda);
  $res = is_object($actualizar) && isset($actualizar->id) &&$actualizar->id > 1 ? 'ok' : 'ko';
  $resultados[] = array(
    'id_tienda' => $id_tienda,
    'resultado' => $res
  );
} else {
$tiendas = [1,2];
foreach ($tiendas as $id_tienda) {
  $actualizar = articulos::actualizarArticulo($sku,$atributos,$id_tienda);
  $res = is_object($actualizar) && isset($actualizar->id) &&$actualizar->id > 1 ? 'ok' : 'ko';
  $resultados[] = array(
    'id_tienda' => $id_tienda,
    'resultado' => $res
  );
}
}


/*if (is_object($actualizar) && isset($actualizar->id) &&$actualizar->id > 1) {
  $resultado = 'ok';
} else {
    $resultado = $actualizar;
}*/




$variable = json_encode($resultados);
echo $variable;
 ?>

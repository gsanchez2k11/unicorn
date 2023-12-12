<?php
/**
 * Generamos una tarifa con todos los artículos
 */
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . '/../../config.php.inc';
require_once(RAIZ . '/vendor/autoload.php');
use unicorn\clases\funciones\unicorn_db\General as general;
require RAIZ . '/clases/funciones/unicorn_db/General.php';
use unicorn\clases\funciones\magento\Articulos as articulos;
require RAIZ . '/clases/funciones/magento/Articulos.php';
use unicorn\clases\funciones\inforpor\Tarifa as tarifa_inforpor;
require_once RAIZ . '/clases/funciones/inforpor/Tarifa.php';
use unicorn\clases\funciones\rocket\Ims as ims;
use unicorn\clases\funciones\rocket\Mensajes as msj;
require_once __DIR__ . '/../../clases/funciones/rocket/Ims.php';
require_once __DIR__ . '/../../clases/funciones/rocket/Mensajes.php';
//use unicorn\clases\funciones\magento\StockItem as stock_item;
//require RAIZ . '/clases/funciones/magento/StockItem.php';

$tabla = 'familias_margenes';
$datos = array(
  'campo' => 'hora',
 // 'valor' => date('H')
 'valor' => 9
);
$operaciones_hora = general::dameRegistros($tabla,$datos);
$plataformas = array_unique(array_column($operaciones_hora,'plataforma')); //Buscamos las plataformas con actualizaciones a esta hora
foreach ($plataformas as $plataforma) {
switch ($plataforma) {
  case 'mage':
$id_tienda = 1;
    break;
    case 'mage245':
      $id_tienda = 2;
          break;
  
  default:
  $id_tienda = 1;
    break;
}

if ($id_tienda == 2) {//Nos quedamos solo con el magento nuevo para depurar


$ops_esta_plataforma = array_filter($operaciones_hora, function ($a) use ($plataforma){
  return $a['plataforma'] == $plataforma;
});

foreach ($ops_esta_plataforma as $criterios) {
  $articulos_coincidentes = articulos::busquedaArticulosActualizacion($criterios,$id_tienda); //Pedimos todos, auto actualizables y no
  //  echo '<pre>';
  //print_r($articulos_coincidentes);
  //echo '</pre>';
  if (!empty($articulos_coincidentes)) {
    $actualizables = array();
    $no_actualizables = array();  
//Primero vamos a separar entre actualizables y no actualizables
foreach ($articulos_coincidentes as $articulo) {
 /* $codes = array_column($articulo->custom_attributes,'attribute_code');
  $values = array_column($articulo->custom_attributes,'value');
  $custom = array_combine($codes,$values); //Un array asociativo con los custom attributes*/ 

  $custom = articulos::getAttrs($articulo, 'custom_attributes');
  //echo '<pre>';
  //print_r($custom);
  //echo '</pre>';
  $auto = (isset($custom['auto_actualizable']) && $custom['auto_actualizable'] == 1 ) || (isset($custom['auto_actualiza_precio']) && $custom['auto_actualiza_precio'] == 1) ? true : false;
  if ($auto === true) { //Si es autoactualizable componemos el articulo para actualizar
    //Creamos un array para depuración
    $sku = $articulo->sku;
    if (isset($custom['cost'])) {
    $cost = $custom['cost'];
    $margen = $custom['margen'] ?? $criterios['margen']; //si tenemos un margen fijo lo aplicamos

$venta = number_format($custom['cost'] * (1+($margen/100)),2);

$actual = number_format($articulo->price,2);
if ($venta !== $actual) { //Articulos que van a modificar su precio
//  echo $venta - $actual . '<br>';
  $info_registro_articulo[] = array(
    'sku' => $sku,
    'cost' => $cost,
    'margen' => $margen,
    'venta' => $venta,
    'actual' => $actual,
    'modificacion' => $venta < $actual ? 'baja' : 'sube',
//'diferencia' => $venta - $actual
   );
$info_actualizacion = array(
  'price' => $venta,
  'custom_attributes' => array(
   // 'cost' => $cost
  [
    'attribute_code' => 'cost',
    'value' => $cost
  ]
  )
);

    echo '<pre>';
  print_r($sku);
  echo '<br>';
  print_r($info_actualizacion);
  echo '<br>';
  print_r($id_tienda);
  echo '</pre>';

$actualizar_precio = articulos::actualizarArticulo($sku,$info_actualizacion,$id_tienda);    //Actualizamos el precio de venta

 //   echo '<pre>';
 // print_r($actualizar_precio);
 // echo '</pre>';
} 
} else { //Estos artículos deberían actualizarse pero no tenemos el precio de compra
  $no_cost[] = $sku;
  
//echo $sku . '<br>';
}

  }


}
 // echo '<pre>';
 // print_r($info_registro_articulo);
 // echo '</pre>';
  }

}
}
if (!empty($no_cost)) {
  $mensaje = 'hay articulos no actualizables: ';
  foreach ($no_cost as $art) {
    $mensaje .= $art . '<br />';
  }
  enviaMsj($mensaje);
}


}
function enviaMsj($mensaje){
  $cliente = 'unicorn';
$usuarios = array('gabriel'); //Los usuarios son un array

$room = ims::CrearSesion($usuarios);
$mensajear = msj::PostMensaje($room,$mensaje,$cliente);
//echo '<pre>';
//print_r($mensajear);
//echo '</pre>';
}
?>
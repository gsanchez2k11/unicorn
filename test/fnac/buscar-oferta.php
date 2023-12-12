<?php
//namespace unicorn\clases\funciones\fnac;
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../../config.php.inc';
//use unicorn\clases\funciones\fnac\Conectar as conectar;
//require_once RAIZ . '/clases/funciones/fnac/Conectar.php';
//$token = conectar::getToken();

use unicorn\clases\funciones\fnac\Ofertas as ofertas;
require_once RAIZ . '/clases/funciones/fnac/Ofertas.php';
//HLL6400DW
$articulo = array(
  'ean' => '4977766753401',
  'mpn' => 'HLL6400DW',
  'stock_final' => '13',
  'precio' => '650'
);
$mpn = 'HLL6400DW2';


$add = ofertas::BuscarOferta($mpn);
//Recorremos los atributos para saber si tenemos o no el artículo
$atributos = $add->attributes();
foreach ($atributos as $key => $val) {
$status = $val;
}

echo $status;

echo '<pre>';
//print_r($add->error);
print_r($atributos);
echo '</pre>';

 ?>

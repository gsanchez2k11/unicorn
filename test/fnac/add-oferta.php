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
$articulos[] = array(
  'ean' => '4977766753401',
  'mpn' => 'HLL6400DW',
  'stock_final' => '13',
  'precio' => '650'
);

$add = ofertas::actualizaOfertas($articulos);

echo '<pre>';
print_r($add);
echo '</pre>';

 ?>

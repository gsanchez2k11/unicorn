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



$add = ofertas::dameOfertas();


echo '<pre>';
print_r($add);
//var_dump($add);
echo '</pre>';

 ?>

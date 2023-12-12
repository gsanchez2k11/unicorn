<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . '/../config.php.inc';
use unicorn\clases\funciones\mirakl\Pedidos as pedidos;
require RAIZ . '/clases/funciones/mirakl/Pedidos.php';


$pedidos = pedidos::dameUltimosPedidos();


echo "<pre>";
print_r($pedidos);
echo "</pre>";
 ?>

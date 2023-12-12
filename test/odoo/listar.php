<?php
error_reporting(E_ALL);                                     //Activamos el reporte de errores
ini_set('display_errors', 'on');
require_once __DIR__ . '/../../config.php.inc';
require_once(RAIZ . '/vendor/autoload.php');
require_once RAIZ . '/clases/funciones/odoo/Conectar.php';
use unicorn\clases\funciones\odoo\Conectar as conectar;

$resultados = array();
$offset = 0;
do {
  $listar = conectar::listar('product.pricelist.item',$offset);  
$resultados = array_merge($resultados,$listar);
  $offset = $offset + 10;
} while (count($listar) == 10);


 ?>

 <?php
 echo '<pre>';
print_r($resultados);
echo '</pre>';
  ?>

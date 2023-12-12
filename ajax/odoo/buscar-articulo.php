<?php
error_reporting(E_ALL);                                     //Activamos el reporte de errores
ini_set('display_errors', 'on');
require_once __DIR__ . '/../../config.php.inc';
require_once(RAIZ . '/vendor/autoload.php');
require_once RAIZ . '/clases/funciones/odoo/Articulos.php';
use unicorn\clases\funciones\odoo\Articulos as articulo;
$atributos = $_POST;
//print_r($atributos);

$buscar = articulo::buscarArticuloAtributos($atributos);
//print_r($buscar);
$json_cliente = json_encode($buscar);
echo $json_cliente;

 ?>

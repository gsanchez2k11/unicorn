<?php
error_reporting(E_ALL);                                     //Activamos el reporte de errores
ini_set('display_errors', 'on');
require_once __DIR__ . '/../../config.php.inc';
require_once(RAIZ . '/vendor/autoload.php');
require_once RAIZ . '/clases/funciones/odoo/Clientes.php';
use unicorn\clases\funciones\odoo\Clientes as cliente;
$cliente = $_POST;

$crear = cliente::crearCliente($cliente);

//$json_cliente = json_encode($buscar);
//echo $json_cliente;

 ?>

 <?php
//print_r($crear);
  ?>

<?php
error_reporting(E_ALL);                                     //Activamos el reporte de errores
ini_set('display_errors', 'on');
require_once __DIR__ . '/../../config.php.inc';
require_once(RAIZ . '/vendor/autoload.php');
require_once RAIZ . '/clases/funciones/odoo/Conectar.php';
use unicorn\clases\funciones\odoo\Conectar as conectar;
$modelo = $_POST['modelo'];
//$modelo = 'sale.order.type';
$limite = isset($_POST['limite']) ?  $_POST['limite'] :  50000; //Si tenemos un limite lo aplicamos, si no 50000 entradas
//$limite = 50;

$offset = 0;
$i = 0;
$total = 0;
$articulos = array();
do {
    $peticion =    conectar::listarNuevo($modelo,$offset); 
    $articulos = array_merge($peticion,$articulos);
    $offset += 10;
    $i++;
    $total += count($articulos);
    } while (count($articulos) % 10 === 0 && $total < $limite && count($peticion) !== 0);

$json_cliente = json_encode($articulos);
echo $json_cliente;
/*echo '<pre>';
print_r($articulos);
echo '</pre>';*/
 ?>

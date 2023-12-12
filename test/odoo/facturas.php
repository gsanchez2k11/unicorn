<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . '/../../config.php.inc';
require_once(RAIZ . '/vendor/autoload.php');
use Ripoo\OdooClient;

use unicorn\clases\funciones\odoo\Conectar as conectar;
require RAIZ . '/clases/funciones/odoo/Conectar.php';

$id = '6135';                                                                   //Ejemplo de factura pagada
//$id = '5816';                                                                   //Ejemplo de factura pagada
$factura = conectar::busqueda('id',$id,'account.invoice');

echo "<pre>";
print_r($factura);
echo "</pre>";

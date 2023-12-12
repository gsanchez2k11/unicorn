<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . '/../../config.php.inc';
require_once(RAIZ . '/vendor/autoload.php');
use Ripoo\OdooClient;

use unicorn\clases\funciones\odoo\Conectar as conectar;
require RAIZ . '/clases/funciones/odoo/Conectar.php';

$id = '2759';
//$id = '2700';
//$factura = conectar::listar('account.payment',1);
$factura = conectar::busqueda('id',$id,'account.payment');

echo "<pre>";
print_r($factura);
echo "</pre>";

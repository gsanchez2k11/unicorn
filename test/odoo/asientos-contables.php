<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . '/../../config.php.inc';
require_once(RAIZ . '/vendor/autoload.php');
use Ripoo\OdooClient;

use unicorn\clases\funciones\odoo\Conectar as conectar;
require RAIZ . '/clases/funciones/odoo/Conectar.php';

//$factura = conectar::busqueda('id',7273,'account.move');

//$factura = conectar::busqueda('id',$id,'account.payment');

$asiento = array(
'date' =>  '2022-04-05',
 'journal_id' => 31,
 'name' => '2022/00011',
 'state' => 'draft'
);

$crear_asiento = conectar::crear($asiento,'account.move');
echo "<pre>";
print_r($crear_asiento);
echo "</pre>";

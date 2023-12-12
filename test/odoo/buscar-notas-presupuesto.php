<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . '/../../config.php.inc';
require_once(RAIZ . '/vendor/autoload.php');
use Ripoo\OdooClient;

use unicorn\clases\funciones\odoo\Presupuestos_venta as presupuesto_venta;
require RAIZ . '/clases/funciones/odoo/Presupuestos_venta.php';

/*$campo = 'id';
$valor = 3037;
$ids = presupuesto_venta::busqueda($campo,$valor,'sale.order');*/

$campo = 'name';
$valor = 'TP000005261';
$id = presupuesto_venta::like($campo,$valor,'sale.order.line');

//echo $id;

echo "<pre>";
print_r($ids);
print_r($id);
echo "</pre>";
 ?>

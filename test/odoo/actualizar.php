<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . '/../../config.php.inc';
require_once(RAIZ . '/vendor/autoload.php');
use Ripoo\OdooClient;

use unicorn\clases\funciones\odoo\Articulos as articulos;
require RAIZ . '/clases/funciones/odoo/Articulos.php';

$campo_busqueda = 'default_code';
$valor_antiguo = '1102SG3NL0';
/*$campo_busqueda = 'id';
$valor_antiguo = 125;*/
$campo_actualizar = 'name';
$valor_nuevo = 'Equipo multifuncion laser monocromo Kyocera ECOSYS M2735dw';
$modelo = 'product.product';



$actualizar = articulos::actualizar($campo_busqueda,$valor_antiguo,$campo_actualizar,$valor_nuevo,$modelo);
print_r($actualizar);
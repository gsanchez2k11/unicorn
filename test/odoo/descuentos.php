<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . '/../../config.php.inc';
require_once(RAIZ . '/vendor/autoload.php');
$tiempo_inicial = microtime(true); //true es para que sea calculado en segundos
use Ripoo\OdooClient;

use unicorn\clases\funciones\odoo\Conectar as conectar;
require RAIZ . '/clases/funciones/odoo/Conectar.php';

$arr = [388,"All / Consumibles / Ecosolvente / Serie T713",432]; //Este artículo tiene un descuento sobre variante y no aparece correctamente


$modelo = 'product.pricelist.item';
$campo = 'pricelist_id';
$valor = $arr[2];
$offset = 0;
$resultado = conectar::busqueda($campo,'SANTAFE (EUR)',$modelo,$offset);

/*$modelo = 'product.pricelist';
$offset = 0;
$lista = conectar::listar($modelo,$offset);*/

echo '<pre>';
print_r($resultado);
echo '<pre>';
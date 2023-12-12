<?php
error_reporting(E_ALL);                                     //Activamos el reporte de errores
ini_set('display_errors', 'on');
require_once __DIR__ . '/../../config.php.inc';
require_once(RAIZ . '/vendor/autoload.php');
require_once RAIZ . '/clases/funciones/odoo/Articulos.php';
use unicorn\clases\funciones\odoo\Articulos as articulo;
$arr = $_POST;
$campo_busqueda = 'id';
$valor_antiguo = $arr['id'];
//$campo_actualizar = 'fix_price';
$valor_nuevo = $arr['pTarifa'];
$modelo = 'product.product';
$tipo = $arr['tipo'];
$campo_actualizar = $tipo == 'compra' ? 'standard_price': 'fix_price';

//print_r($arr);

$actualizar = articulo::actualizar($campo_busqueda,$valor_antiguo,$campo_actualizar,$valor_nuevo,$modelo);
//print_r($actualizar);
if ($actualizar == 1) {
  $actualizar = 'ok';
} else {
  $actualizar =  'ko';
}
//print_r($actualizar);
$json_cliente = json_encode($actualizar);
echo $json_cliente;

 ?>

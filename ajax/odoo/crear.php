<?php
error_reporting(E_ALL);                                     //Activamos el reporte de errores
ini_set('display_errors', 'on');
require_once __DIR__ . '/../../config.php.inc';
require_once(RAIZ . '/vendor/autoload.php');
require_once RAIZ . '/clases/funciones/odoo/Conectar.php';
use unicorn\clases\funciones\odoo\Conectar as conectar;
$datos = is_string($_POST['datos']) ? (array) json_decode($_POST['datos']) : $_POST['datos'] ;
$modelo = (string)$datos['modelo'];
$arr = (array)$datos['arr'];
//$arr['list_price'] = floatval($datos['arr']['list_price']);

$crear = conectar::crear(conectar::convertirArr($arr), $modelo);
$resultado['id'] = $crear;

$json_resultado = json_encode($resultado);
echo $json_resultado;

 ?>

<?php
error_reporting(E_ALL);                                     //Activamos el reporte de errores
ini_set('display_errors', 'on');
require_once __DIR__ . '/../../config.php.inc';
require_once(RAIZ . '/vendor/autoload.php');
require_once RAIZ . '/clases/funciones/odoo/Articulos.php';
use unicorn\clases\funciones\odoo\Articulos as articulo;
/*echo "<pre>";
print_r($_POST);
echo "</pre>";*/
if (is_array($_POST['datos'])) { //Si recibimos un array directamente lo asignamos
  $datos = $_POST['datos'];
} else { //En caso contrario lo construimos
  $datos = (array) json_decode($_POST['datos']);
}


$campo_busqueda = $datos['campo_busqueda'];
$valor_antiguo = $datos['valor_antiguo'];
$campo_actualizar = $datos['campo_actualizar'];
$valor_nuevo = $datos['valor_nuevo'];
$modelo = $datos['modelo'];
//print_r($campo_busqueda);
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

<?php
error_reporting(E_ALL);                                     //Activamos el reporte de errores
ini_set('display_errors', 'on');
require_once __DIR__ . '/../../config.php.inc';
require_once(RAIZ . '/vendor/autoload.php');
require_once RAIZ . '/clases/funciones/odoo/Conectar.php';
use unicorn\clases\funciones\odoo\Conectar as conectar;
$datos = $_POST;
$modelo = $datos['modelo'];
$campo = $datos['campo'];
$valor = $datos['valor'];
$offset = $datos['offset'];

$articulos = array();
//do {
$resultado = conectar::like($campo,$valor,$modelo,$offset);
/*foreach ($resultado as $articulo) {
$articulos[] = $articulo;
}
$offset += 10;
} while (count($resultado) == 10);*/

//$offset = isset($datos['offset']) ? $datos['offset'] : 0 ;
//$arr['list_price'] = floatval($datos['arr']['list_price']);

//$busqueda = conectar::busqueda($campo,$valor,$modelo,$offset);

$json_resultado = json_encode($resultado);
echo $json_resultado;

 ?>

 <?php
//var_dump($arr);
  ?>

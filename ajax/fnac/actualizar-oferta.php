<?php
error_reporting(E_ALL);                                     //Activamos el reporte de errores
ini_set('display_errors', 'on');
require_once __DIR__ . '/../../config.php.inc';
require_once(RAIZ . '/vendor/autoload.php');
use unicorn\clases\funciones\fnac\Ofertas as ofertas;
require RAIZ . '/clases/funciones/fnac/Ofertas.php';
$ofertas[] = $_POST;


$actualizar = ofertas::actualizaOfertas($ofertas);
$resultado['fnac'] = strtolower((string) $actualizar->attributes()->status);
//$actualizar_string = simplexml_load_string($actualizar);
$json_resultado = json_encode($resultado);
echo $json_resultado;

?>

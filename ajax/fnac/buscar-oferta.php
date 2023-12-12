<?php
error_reporting(E_ALL);                                     //Activamos el reporte de errores
ini_set('display_errors', 'on');
require_once __DIR__ . '/../../config.php.inc';
require_once(RAIZ . '/vendor/autoload.php');
use unicorn\clases\funciones\fnac\Ofertas as ofertas;
require RAIZ . '/clases/funciones/fnac/Ofertas.php';
$mpn = $_POST['mpn'];

$buscar = ofertas::BuscarOferta($mpn);
/*echo "<pre>";
print_r($buscar);
echo "</pre>";

$resultado = strtolower((string) $buscar->attributes()->status);*/
//$actualizar_string = simplexml_load_string($actualizar);
$json_resultado = json_encode($buscar);
echo $json_resultado;

?>

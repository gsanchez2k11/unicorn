<?php
error_reporting(E_ALL);                                     //Activamos el reporte de errores
ini_set('display_errors', 'off');
require_once __DIR__ . '/../../config.php.inc';
use unicorn\clases\funciones\google\Tarifa as tarifa;
require RAIZ . '/clases/funciones/google/Tarifa.php';
$pagina = $_POST['p'];
$rangos = tarifa::RANGOS;
$articulos_tarifa = tarifa::miTarifaCompleta([$rangos[$pagina]]);


$variable = json_encode($articulos_tarifa);
echo $variable;

/*echo '<pre>';
print_r($articulos_tarifa);
echo '</pre>';*/
?>

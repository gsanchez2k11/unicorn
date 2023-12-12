<?php
namespace unicorn\cron\inforpor;
error_reporting(E_ALL);                                     //Activamos el reporte de errores
ini_set('display_errors', 'on');
require_once __DIR__ . '/../../config.php.inc';
use unicorn\clases\funciones\inforpor\Tarifa as tarifa;
require RAIZ . '/clases/funciones/inforpor/Tarifa.php';


$tarifa = tarifa::dameTarifa();


$json_tarifa = json_encode($tarifa);


//  echo $json_tarifa;
$fp = fopen(RAIZ . '/var/import/inforpor.json', 'w');
fwrite($fp, $json_tarifa);
fclose($fp);

 ?>

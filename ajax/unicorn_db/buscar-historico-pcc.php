<?php
error_reporting(E_ALL);                                     //Activamos el reporte de errores
ini_set('display_errors', 'on');
require_once __DIR__ . '/../../config.php.inc';

use unicorn\clases\funciones\unicorn_db\Entidad as entidad;
require_once __DIR__ . '/../../clases/funciones/unicorn_db/Entidad.php';
?>
<?php

$resultado = entidad::getHistorico($_POST);
//echo "resultado<pre>";
//print_r($resultado);
//echo "</pre>";
//Ojo que devuelve un array con los posibles códigos
echo json_encode($resultado);
 ?>

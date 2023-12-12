<?php
namespace unicorn\cron\mirakl;
error_reporting(E_ALL);                                     //Activamos el reporte de errores
ini_set('display_errors', 'on');
require_once __DIR__ . '/../../config.php.inc';

require_once RAIZ . '/clases/funciones/unicorn_db/Actualizacion.php';
use unicorn\clases\funciones\unicorn_db\Actualizacion as actualizacion;
$tipo = isset($_POST['tipo']) ? $_POST['tipo'] : 'stock';
$plataforma = isset($_POST['plataforma']) ? $_POST['plataforma'] :  'pcc';
//$plataforma = 'fnac';
 ?>
<?php
//$articulos_ultima = actualizacion::fusionActualizaciones($tipo,$plataforma);
$articulos_ultima = actualizacion::dameDatosActualizacion($plataforma);
 ?>

 <?php

 $json_articulos = json_encode($articulos_ultima);
 /*echo "<pre>";
 print_r(json_last_error());
 echo "</pre>";*/
 echo $json_articulos;
  ?>

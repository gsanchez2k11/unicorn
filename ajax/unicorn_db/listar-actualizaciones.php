<?php
namespace unicorn\cron\mirakl;
error_reporting(E_ALL);                                     //Activamos el reporte de errores
ini_set('display_errors', 'on');
require_once __DIR__ . '/../../config.php.inc';

require_once RAIZ . '/clases/funciones/unicorn_db/Actualizacion.php';
use unicorn\clases\funciones\unicorn_db\Actualizacion as actualizacion;

 ?>
<?php
$articulos_ultima = actualizacion::listaActualizacionesNoVistas($_POST['tipo']);

 ?>

 <?php
 $json_articulos = json_encode($articulos_ultima);
 echo $json_articulos;
  ?>

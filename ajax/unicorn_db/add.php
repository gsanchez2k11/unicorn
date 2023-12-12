<?php
namespace unicorn\cron\mirakl;
error_reporting(E_ALL);                                     //Activamos el reporte de errores
ini_set('display_errors', 'on');
require_once __DIR__ . '/../../config.php.inc';

require_once RAIZ . '/clases/funciones/unicorn_db/General.php';
use unicorn\clases\funciones\unicorn_db\General as general;

 ?>
<?php
$lista = general::addRegistro($_POST['tabla'], $_POST['arr']);

 ?>

 <?php
 $json_lista = json_encode($lista);
 echo $json_lista;
  ?>

<?php
error_reporting(E_ALL);                                     //Activamos el reporte de errores
ini_set('display_errors', 'on');
require_once __DIR__ . '/../../config.php.inc';
require_once __DIR__ . '/../../clases/funciones/unicorn_db/Entidad.php';
use unicorn\clases\funciones\unicorn_db\Entidad as entidad;


$entidad = $_POST['entidad'];

$eliminar_comentario = entidad::eliminaArticuloEntidadVarchar($entidad,'6');


  echo 'ok';


?>

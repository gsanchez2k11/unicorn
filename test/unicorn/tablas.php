<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . '/../../config.php.inc';

use unicorn\clases\funciones\unicorn_db\Actualizacion as Actualizacion;
require RAIZ . '/clases/funciones/unicorn_db/Actualizacion.php';



$truncar = Actualizacion::dameModoListarPcc();

echo "<pre>";
print_r($truncar);
echo "</pre>";

 ?>

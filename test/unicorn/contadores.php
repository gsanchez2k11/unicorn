<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . '/../../config.php.inc';

use unicorn\clases\funciones\unicorn_db\Entidad as entidad;
require_once RAIZ . '/clases/funciones/unicorn_db/entidad.php';


$row = array(
  'id' => 1,
  'incremento' => 100
);
$incrementa_contador = contadores::incrementaContador($row);

echo "<pre>";
print_r($incrementa_contador);
echo "</pre>";

 ?>

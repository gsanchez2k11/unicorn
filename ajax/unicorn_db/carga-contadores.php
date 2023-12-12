<?php
error_reporting(E_ALL);                                     //Activamos el reporte de errores
ini_set('display_errors', 'on');
require_once __DIR__ . '/../../config.php.inc';
require_once __DIR__ . '/../../clases/funciones/unicorn_db/Contadores.php';
use unicorn\clases\funciones\unicorn_db\Contadores as contadores;

$row['id'] = 2;
$contadores = contadores::buscarContador($row);

$json_articulos = json_encode($contadores);
echo $json_articulos;
 ?>

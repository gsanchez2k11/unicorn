<?php
/**
 * Puesta a cero de contadores.
 * Está configurado en plesk para ejecutarse a cada hora en punto
 * @var [type]
 */
namespace unicorn\cron\unicorn_db;
error_reporting(E_ALL);                                     //Activamos el reporte de errores
ini_set('display_errors', 'on');
require_once __DIR__ . '/../../config.php.inc';
require_once RAIZ . '/clases/funciones/unicorn_db/Contadores.php';
use unicorn\clases\funciones\unicorn_db\Contadores as contadores;
//Ponemos contadores a 0
$contadores = array(
  '1',                                                                          //    Información sobre una oferta
  '2',                                                                          //    Lista de ofertas
  '3',                                                                          //    Crear, actualizar o eliminar ofertas
  '4',
  '5'                                                                           //    Ofertas para un artículo dado
);

foreach ($contadores as $contador) {
$row = array(
  'id' => $contador,
  'valor' => 0
);
$actualizar = contadores::actualizaContador($row);
unset($row);
}

 ?>

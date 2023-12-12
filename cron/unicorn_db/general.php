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
require_once RAIZ . '/clases/funciones/unicorn_db/Config.php';
use unicorn\clases\funciones\unicorn_db\Contadores as contadores;
use unicorn\clases\funciones\unicorn_db\Operaciones_tablas as operaciones;
use unicorn\clases\funciones\unicorn_db\Config as config;
require_once RAIZ . '/clases/funciones/unicorn_db/Operaciones_tablas.php';
use unicorn\clases\funciones\unicorn_db\Actualizacion as actualizacion;
require_once RAIZ . '/clases/funciones/unicorn_db/Actualizacion.php';
//A las 22 horas eliminamos la cache de precios y stock de pcc para regenerarla
$hora = date("H");
if ($hora == 22) {
$truncar = Actualizacion::limpiaCachePcc();
$row = array(
  'config' => 'listar_ofertas',
  'valor' => 1
);
$cambiar_modo = config::updateValorConfig($row); //Ponemos el modo en 1 para comenzar la cache
$row['config'] = 'pagina_ofertas';
$row['valor'] = 0;
$cambiar_modo = config::updateValorConfig($row); //Ponemos el modo en 1 para comenzar la cache

} elseif ($hora == 8){
  $row = array(
    'config' => 'listar_ofertas',
    'valor' => 0
  );
  $cambiar_modo = config::updateValorConfig($row); //Ponemos el modo en 1 para comenzar la cache
  $row = array(
    'config' => 'pagina_ofertas',
    'valor' => 0
  );
  $cambiar_modo = config::updateValorConfig($row); //Ponemos el modo en 1 para comenzar la cache
}


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
//$actualizar = contadores::actualizaContador($row);
unset($row);
}

//limpiamos los duplicados en la tabla historico_pcc
operaciones::limpiaDuplicados();



 ?>

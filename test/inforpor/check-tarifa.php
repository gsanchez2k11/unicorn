<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . '/../../config.php.inc';
//use unicorn\clases\objetos\Articulos\Articulo_tarifa_inforpor as articulo;
//require RAIZ . '/clases/objetos/Articulos/Articulo_tarifa_inforpor.php';
use unicorn\clases\funciones\inforpor\Tarifa as tarifa;
require RAIZ . '/clases/funciones/inforpor/Tarifa.php';
use unicorn\clases\funciones\unicorn_db\Config as config;
require_once RAIZ . '/clases/funciones/unicorn_db/Config.php';
use unicorn\clases\objetos\Articulos\Articulo_tarifa_inforpor as articulo;
require_once RAIZ . '/clases/objetos/Articulos/Articulo_tarifa_inforpor.php';
use unicorn\clases\funciones\mirakl\Cronmirakl as mirakl;
require RAIZ . '/clases/funciones/mirakl/Cronmirakl.php';
use unicorn\clases\funciones\magento\Cronmage as mage;
require RAIZ . '/clases/funciones/magento/Cronmage.php';
?>
<?php

$tar = tarifa::checkVersionTarifa();

//Mientras devuelva false tenemos la última versión de la tarifa
if ($tar !== false) {
// 1. Descargamos la tarifa nueva y cargamos los datos
$tarifa_inforpor_json = tarifa::gestionaTarifaNueva($tar);
$tarifa_inforpor = json_decode($tarifa_inforpor_json, true, 512); //la convertimos desde el JSON
/**
 * 2 Lanzamos las distintas actualizaciones
 *  2.1 Mirakl
 * 2.2 Magento versión 2.2
 * 2.4 Magento versión 2.4
 */
$mirakl = mirakl::actualizaMiraklInforpor($tarifa_inforpor);
$mage22 = mage::actualizaMage($tarifa_inforpor,1);
$mage245 = mage::actualizaMage($tarifa_inforpor,2);

} else {
  echo 'sin novedad';
}







 ?>

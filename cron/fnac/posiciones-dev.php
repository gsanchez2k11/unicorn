<?php
/**
 * Actualizamos los datos de los artículos de pc componentes, basandonos en la última actualización grabada en la BBDD.
 * Guardamos tanto el resultado de la actualización como los datos básicos para su posterior procesamiento.
 * @var [type]
 */
namespace unicorn\cron\mirakl;
error_reporting(E_ALL);                                     //Activamos el reporte de errores
ini_set('display_errors', 'on');
require_once __DIR__ . '/../../config.php.inc';
require_once(RAIZ . '/vendor/autoload.php');

require_once RAIZ . '/clases/funciones/fnac/Ofertas.php';

use unicorn\clases\funciones\fnac\Ofertas as ofertas;


$ofertas = ofertas::dameOfertas();                                              //Recuperamos las ofertas de FNAC


echo "<pre>";
print_r($ofertas);
echo "</pre>";
 ?>

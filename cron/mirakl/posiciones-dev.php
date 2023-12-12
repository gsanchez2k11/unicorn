<?php
namespace unicorn\cron\mirakl;
error_reporting(E_ALL);                                     //Activamos el reporte de errores
ini_set('display_errors', 'on');
require_once __DIR__ . '/../../config.php.inc';
require_once(RAIZ . '/vendor/autoload.php');
$tiempo_inicial = microtime(true); //true es para que sea calculado en segundos

//require_once RAIZ . '/clases/funciones/inforpor/Stock.php';
require_once RAIZ . '/clases/funciones/otras/Catalogo.php';
//require_once RAIZ . '/clases/funciones/otras/Moneda.php';
require_once RAIZ . '/clases/funciones/unicorn_db/Entidad.php';
require_once RAIZ . '/clases/funciones/unicorn_db/Config.php';
//require_once RAIZ . '/clases/funciones/mirakl/Ofertas.php';
require_once RAIZ . '/clases/funciones/unicorn_db/Actualizacion.php';

use Mirakl\MMP\Shop\Client\ShopApiClient as Client;
//use Mirakl\MMP\Shop\Request\Offer\GetOffersRequest;
use Mirakl\MMP\Shop\Request\Offer\UpdateOffersRequest;
use unicorn\clases\funciones\otras\Catalogo as catalogo;
//use unicorn\clases\funciones\inforpor\Stock as compraInforpor;
//use unicorn\clases\funciones\otras\Moneda as moneda;
use unicorn\clases\funciones\unicorn_db\Entidad as entidad;
use unicorn\clases\funciones\unicorn_db\Config as config;
use unicorn\clases\funciones\unicorn_db\Actualizacion as actualizacion;
//use unicorn\clases\funciones\mirakl\Ofertas as ofertas;
$tiempo_inicial = microtime(true); //true es para que sea calculado en segundos
 ?>

 <?php
 $plataformas = Array('pcc','mediamarkt'); //Lista de las plataformas
 foreach ($plataformas as $plataforma) { //Recorremos las distintas plataformas
  $listado_ofertas = catalogo::dameObjetosMirakl($plataforma); //Pedimos el listado de ofertas de cada plataforma 

//$arr_key_ean = actualizacion::damePosiciones($listado_ofertas); //Pedimos las posiciones para todos las ofertas, esto se hace por bloques

//Dejamos de grabar estos datos en la bbdd
/*$datos_alertas    = array(
  'plataforma' => $plataforma,
  'tipo' => 'posiciones',
  'campo_1' => base64_encode(serialize($arr_key_ean)),
  'visto' => 1,
  'num_modificados' => count($arr_key_ean)
);
$insertar = actualizacion::addActualizacion($datos_alertas);*/
//Filtramos para dejar solo en las que salimos


//echo $plataforma . '|' . count($filtradas);
echo "<pre>";
print_r($arr);
echo "</pre>";
 }





 $tiempo_final = microtime(true);
 $tiempo = $tiempo_final - $tiempo_inicial; //este resultado estará en segundos
 echo "El tiempo de ejecución del archivo ha sido de " . $tiempo . " segundos";
 ?>

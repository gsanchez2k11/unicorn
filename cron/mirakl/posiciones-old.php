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

 ?>

 <?php
 //Datos de pc componentes
 $apiUrl = config::dameValorConfig('url_pcc');
 $apiKey = config::dameValorConfig('key_pcc');
 $client = new Client($apiUrl, $apiKey);


 $refs_nuevas = catalogo::dameObjetosMirakl('pcc'); //Pedimos el listado de ofertas de pc componentes

$arr_key_ean = actualizacion::damePosiciones($refs_nuevas);

$datos_alertas    = array(
  'plataforma' => 'pcc',
  'tipo' => 'posiciones',
  'campo_1' => base64_encode(serialize($arr_key_ean)),
  'visto' => 1,
  'num_modificados' => count($arr_key_ean)
);
$insertar = actualizacion::addActualizacion($datos_alertas);

//Vamos a buscar en que posicion estamos nosotros
foreach ($arr_key_ean as $articulo) {
  $posicion = 0;
  $entidad = $articulo['entidad'];
  foreach ($articulo['ofertas'] as $key => $value) {
    if ($value['tienda'] === 'Futura Teck') { //La grabamos en la base de datos
    $posicion = $key+1;
    }
  }
$grabar_posicion = entidad::insertaArticuloEntidadInt($entidad,21,$posicion,'articulos_entidad_int');
}


echo "<pre>";
print_r($arr_key_ean);
echo "</pre>";

 ?>

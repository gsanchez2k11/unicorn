<?php
namespace unicorn\cron\mirakl;
error_reporting(E_ALL);                                     //Activamos el reporte de errores
ini_set('display_errors', 'on');
require_once __DIR__ . '/../../config.php.inc';
require_once(RAIZ . '/vendor/autoload.php');
require_once RAIZ . '/clases/funciones/mirakl/Ofertas.php';
require_once RAIZ . '/clases/funciones/unicorn_db/Actualizacion.php';

use Mirakl\MMP\Shop\Client\ShopApiClient as Client;
use Mirakl\MMP\Shop\Request\Offer\GetOffersRequest;
use unicorn\clases\funciones\mirakl\Ofertas as ofertas;
use unicorn\clases\funciones\unicorn_db\Actualizacion as actualizacion;

/******* Obtenemos el listado de ofertas de Pc Componentes *******/
$apiUrl = 'https://pccomponentes-prod.mirakl.net/api';
$apiKey = '95747f30-c781-4b96-aabc-9935b237a47d';
$client = new Client($apiUrl, $apiKey);

$ofertas_mirakl = ofertas::listarOfertas('pcc',1);

$datos_alertas    = array(
  'plataforma' => 'pcc',
  'tipo' => 'listar_ofertas',
  'campo_1' => serialize($ofertas_mirakl)
);
$insertar = actualizacion::addActualizacion($datos_alertas);
 ?>

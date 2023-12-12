<?php
namespace unicorn\clases\funciones\mirakl;

error_reporting(E_ALL);                                     //Activamos el reporte de errores
ini_set('display_errors', 'on');
//require_once __DIR__ . '/../../../config.php';
require_once(RAIZ . '/vendor/autoload.php');
//require_once RAIZ . '/clases/objetos/Articulo_mirakl.php';            // La clase compraarticulo
require_once RAIZ . '/clases/objetos/Articulos/Articulo_mirakl.php';
require_once RAIZ . '/clases/funciones/unicorn_db/Contadores.php';

use Mirakl\MMP\Shop\Client\ShopApiClient as Client;
use Mirakl\MMP\Shop\Request\Offer\GetOffersRequest;
use Mirakl\MMP\Shop\Request\Offer\GetOfferRequest;
use Mirakl\MMP\Shop\Request\Product\Offer\GetOffersOnProductsRequest;
use unicorn\clases\objetos\Articulos\Articulo_mirakl as articulo;
use unicorn\clases\funciones\unicorn_db\Contadores as contadores;


/**
 *
 */
class Documentos 
{

  function __construct(argument)
  {
    // code...
  }
}

<?php
namespace unicorn\clases\funciones\mirakl;

error_reporting(E_ALL);                                     //Activamos el reporte de errores
ini_set('display_errors', 'on');
//require_once __DIR__ . '/../../../config.php';
require_once(RAIZ . '/vendor/autoload.php');
//require_once RAIZ . '/clases/objetos/Articulo_mirakl.php';            // La clase compraarticulo
require_once RAIZ . '/clases/objetos/Articulos/Articulo_mirakl.php';
require_once RAIZ . '/clases/funciones/unicorn_db/Contadores.php';

require_once RAIZ . '/clases/funciones/unicorn_db/Actualizacion.php';
require_once RAIZ . '/clases/funciones/unicorn_db/Config.php';

use Mirakl\MMP\Shop\Client\ShopApiClient as Client;
use Mirakl\MMP\Shop\Request\Offer\OffersExportRequest;
use Mirakl\MMP\Shop\Request\Offer\GetOffersRequest;
use Mirakl\MMP\Shop\Request\Offer\GetOfferRequest;
use Mirakl\MMP\Shop\Request\Product\Offer\GetOffersOnProductsRequest;
use unicorn\clases\objetos\Articulos\Articulo_mirakl as articulo;
use unicorn\clases\funciones\unicorn_db\Contadores as contadores;
use Mirakl\MMP\Shop\Request\Offer\UpdateOffersRequest;
use unicorn\clases\funciones\unicorn_db\Actualizacion as Actualizacion;
use unicorn\clases\funciones\unicorn_db\Config as config;
//use Mirakl\MMP\Front\Client\FrontApiClient;
//use Mirakl\MMP\FrontOperator\Request\Offer\OffersExportRequest;

/**
 *
 */
class Ofertas
{
private static function sumaContadorOfertas($id){
  $row = array(
    'id' => $id,
    'incremento' => 1
  );
$incrementa_contador = contadores::incrementaContador($row);
}


  public static function obtenerOfertas($plataforma, $row = false) {
    $url = config::dameValorConfig('url_' . $plataforma);
    $key = config::dameValorConfig('key_' . $plataforma);
    $client = new Client($url, $key);
 //   $client = new Client(APIURLPCCOMPONENTES, APIKEYPCCOMPONENTES);
    $request = new GetOffersRequest('2395');
  if (isset($row['sku'])) $request->setSku($row['sku']);
   $result = $client->getOffers($request);
self::sumaContadorOfertas(2);                                                    //Incrementamos el contador de peticiones de la API
   $ofertas = $result->getItems();
   return $ofertas;
  }

  public static function listarOfertas($plataforma,$pagina,$maximo = false) {
    $url = config::dameValorConfig('url_' . $plataforma);
    $key = config::dameValorConfig('key_' . $plataforma);
    $client = new Client($url, $key);
    $tienda = config::dameValorConfig('id_' . $plataforma);
  //  $tope = isset($maximo) ? $maximo : 30;
  /*switch ($plataforma) {
    case 'pcc':
  $url = APIURLPCCOMPONENTES;
  $key = APIKEYPCCOMPONENTES;
  $tienda = '2395';
      break;
      case 'phh':
      $url = APIURLPHONEHOUSE;
      $key = APIKEYPHONEHOUSE;
      $tienda = '2514';
        break;
  }*/

  //  $client = new Client(APIURLPCCOMPONENTES, APIKEYPCCOMPONENTES);
  //  $request = new GetOffersRequest('2395');
  //$client = new Client($url, $key);
  $request = new GetOffersRequest($tienda);
    $articulo = array();
    //Obtenemos los resultados que vamos a mostrar por página
    $rpp = $request->getQueryParams()['max'];                                       //Por defecto son 10
    $ofertas = array();                                                             //Array vacío para las ofertas
    $p = $pagina;
    $cont = 1;

    do {
    /*  echo "<pre>";
      print_r($request->getQueryParams()) ;
      echo "</pre>";*/
      // Calling the API
      $result = $client->getOffers($request);
      $num_result = count($result);
      self::sumaContadorOfertas(2);                                                    //Incrementamos el contador de peticiones de la API

      //Incrementamos el contador correspondiente
      //nos quedamos con los $items
      $items = $result->getItems();
      foreach ($items as $oferta) {
        $ofertas[] = $oferta;
      }
    //Reducimos el contador de items total
//    $result->setTotalCount($result->getTotalCount()-$rpp);
    $offset = $p*$rpp;
    //$result->offsetSet($p,$offset);
    $request->setOffset($offset);
    $row = array(
      'config' => 'pagina_ofertas',
      'valor' => $p
    );
    $grabar_pagina = config::updateValorConfig($row);
    $p++;
    $cont++;
  //  echo $result->getTotalCount() . '<br />';
} while (/*$result->getTotalCount()*/ $num_result >= $rpp && $cont < 500);   //Limitamos a 500 peticiones, el límite total sería de 5000 artículos así
//Tenemos un array con todas las ofertas
foreach ($ofertas as $oferta) {

  $refs = $oferta->getData()['product']->getData()['references']->getItems();

  foreach ($refs as $ref) {
  $articulo[strtolower($ref->getData()['type'])] = $ref->getData()['value'];
  }

  $articulo = array (
    'precio' => $oferta->getData()['total_price'],
    'product_sku' => $oferta->getData()['product']->getData()['sku'],
    'shop_sku' => $oferta->getData()['sku'],
    'logistic_class' => $oferta->getData()['logistic_class']->getData()['code'],
    'ean' => $articulo['ean'],
    'mpn' => $articulo['mpn'],
    'state_code' => $oferta->getData()['state_code'],
  //  'price' => $oferta->getData()['price'],
    'category' => $oferta->getData()['product']->getData()['category']->getData()['code'],
    'stock' => $oferta->getData()['quantity'],
    'offer_id' =>  $oferta->getData()['offer_id'],
    'nombre' => $oferta->getData()['product']->getData()['title'],
    'si_custodia' => '0'
    );
  $obj_articulo = new articulo($articulo);
//    $articulos[] = $articulo;
$articulos[$articulo['ean']] = $obj_articulo;
}

    return $articulos;
  }

  /**
   * (OF21) List offers for a shop
   * This API uses pagination by default and will return 10 offers
   *
   * @method  string  getShopId()
   * @method  $this   setShopId(string $shopId)
   * @method  string  getSku()
   * @method  $this   setSku(string $sku)
   * @method  string  getProductId()
   * @method  $this   setProductId(string $productId)
   *
   * Example:
   *
   * <code>
   * use Mirakl\MMP\Shop\Client\ShopApiClient;
   * use Mirakl\MMP\Shop\Request\Offer\GetOffersRequest;
   *
   * $api = new ShopApiClient('API_URL', 'API_KEY', 'SHOP_ID');
   * $request = new GetOffersRequest('SHOP_ID');
   * $request->setSku('OFFER_SKU'); // Optional
   * $request->setProductId('PRODUCT_ID'); // Optional
   * $request->setOfferStateCodes(['OFFER_STATE']); // Optional
   * $request->setFavorite(true); // Optional
   * $result = $api->getOffers($request);
   * // $result => @see \Mirakl\MMP\Shop\Domain\Collection\Offer\ShopOfferCollection
   * </code>
   */
public static function todasLasOfertas($plataforma = 'pcc') {
  /*switch ($plataforma) {
    case 'pcc':
  $url = APIURLPCCOMPONENTES;
  $key = APIKEYPCCOMPONENTES;
  $tienda = '2395';
      break;
      case 'phh':
      $url = APIURLPHONEHOUSE;
      $key = APIKEYPHONEHOUSE;
      $tienda = '2514';
        break;
  }
  $client = new Client($url, $key);*/
  $url = config::dameValorConfig('url_' . $plataforma);
  $key = config::dameValorConfig('key_' . $plataforma);
  $client = new Client($url, $key);
  $tienda = config::dameValorConfig('id_' . $plataforma);

  $request = new GetOffersRequest($tienda);
  $result = $client->getOffers($request);
  return $result;
}



  public static function dameInfoOfertas($product_sku, $plataforma = 'pcc') {
    $oferta = array();
    $url = config::dameValorConfig('url_' . $plataforma);
    $key = config::dameValorConfig('key_' . $plataforma);
    $client = new Client($url, $key);

  //  $client = new Client(APIURLPCCOMPONENTES, APIKEYPCCOMPONENTES);
$request = new GetOffersOnProductsRequest([$product_sku]);
$request->setMax(50);
   $result = $client->getOffersOnProducts($request);
   self::sumaContadorOfertas(4);                                                    //Incrementamos el contador de peticiones de la API
if (isset($result->getItems()[0])){
 $oferta = $result->getItems()[0]->getData();
}
   return $oferta;
 }


 /**
  * Esta versión pasa los datos como array y devuelve todo, no solo las ofertas.
  * https://pccomponentes-prod.mirakl.net/help/api-doc/seller/mmp.html#P11
  * Importante, hay que pasar las peticiones en arrays de 100 referecias como mucho
  * (P11) List offers for each product given
  *
  * This API uses pagination by default and will return 10 offers
  * Result can be sort with bestPrice or bestEvaluation @see AbstractGetOffersOnProductsRequest
  *
  * @method  bool    getPremium()
  * @method  $this   setPremium(bool $flag)
  * @method  array   getShopIds()
  * @method  $this   setShopIds(array $shopIds)
  *
  * Example:
  *
  * <code>
  * use Mirakl\MMP\Front\Client\FrontApiClient;
  * use Mirakl\MMP\FrontOperator\Request\Product\Offer\GetOffersOnProductsRequest;
  * use Mirakl\MMP\FrontOperator\Domain\Product\Offer\OfferOnProduct;
  *
  * $api = new FrontApiClient('API_URL', 'API_KEY');
  *
  * $request = new GetOffersOnProductsRequest(['PRODUCT_ID_1', 'PRODUCT_ID_2'], ['OFFER_STATE_CODE_1']);
  * $request->setPricingChannelCode('CHANNEL_1');
  * $request->setShopIds(['SHOP_1', 'SHOP_2']);
  *
  * $result = $api->getOffersOnProducts($request);
  * // $result => @see \Mirakl\MMP\Shop\Domain\Collection\Product\Offer\ProductWithOffersCollection
  * </code>
  */
  public static function dameInfoOfertasArr(array $product_sku, $plataforma = 'pcc') {
    $url = config::dameValorConfig('url_' . $plataforma);
    $key = config::dameValorConfig('key_' . $plataforma);
    $client = new Client($url, $key);
  //  $client = new Client(APIURLPCCOMPONENTES, APIKEYPCCOMPONENTES);
$request = new GetOffersOnProductsRequest($product_sku);
$request->setMax(50);
   $result = $client->getOffersOnProducts($request);
   self::sumaContadorOfertas(4);                                                    //Incrementamos el contador de peticiones de la API
   return $result;
  }

/**
 * (OF24) Update offers
 * https://pccomponentes-prod.mirakl.net/help/api-doc/seller/mmp.html#OF24
 * @return [type] [description]
 *
 * $api = new ShopApiClient('API_URL', 'API_KEY', 'SHOP_ID');
 * $request = new UpdateOffersRequest('SHOP_ID');
 * $request->setOffers([
 *     'shop_sku' => 'AAPL-CHASAW7852',
 *     'description' => 'never used',
 * ]);
 * $result = $api->updateOffers($request);
 * // $result => @see \Mirakl\MMP\OperatorShop\Domain\Offer\Importer\OfferImportTracking
 */
  public static function actualizaOferta(array $articulos, $plataforma = 'pcc'){
    /*switch ($plataforma) {
      case 'pcc':
    $url = APIURLPCCOMPONENTES;
    $key = APIKEYPCCOMPONENTES;
        break;
        case 'phh':
        $url = APIURLPHONEHOUSE;
        $key = APIKEYPHONEHOUSE;
          break;
    }*/

    $url = config::dameValorConfig('url_' . $plataforma);
    $key = config::dameValorConfig('key_' . $plataforma);
    $client = new Client($url, $key);
      $request = new UpdateOffersRequest(['2395']);
      $request->setOffers($articulos);
      $result = $client->updateOffers($request);
      return $result;
  }


  /**
   * (OF51) Get a list of offers that have been updated and deleted since the last request date
   *
   * NB: This is the same API call that
   * @see     OffersExportFileRequest
   * but with the CSV file having been parsed
   *
   * Example:
   *
   * <code>
   * use Mirakl\MMP\Front\Client\FrontApiClient;
   * use Mirakl\MMP\FrontOperator\Request\Offer\OffersExportRequest;
   *
   * $api = new FrontApiClient('API_URL', 'API_KEY');
   *
   * $request = new OffersExportRequest();
   * $request->setIncludeInactiveOffers(true);
   *
   * $result = $this->api->exportOffers($request);
   * // $result => @see \Mirakl\MMP\FrontOperator\Domain\Collection\Offer\ExportOfferCollection;
   *
   * </code>
   */

  public static function dameCsv($plataforma = 'pcc'){
    /*switch ($plataforma) {
      case 'pcc':
    $url = APIURLPCCOMPONENTES;
    $key = APIKEYPCCOMPONENTES;
        break;
        case 'phh':
        $url = APIURLPHONEHOUSE;
        $key = APIKEYPHONEHOUSE;
          break;
    }*/

    $url = config::dameValorConfig('url_' . $plataforma);
    $key = config::dameValorConfig('key_' . $plataforma);

   // $client = new FrontApiClient($url, $key);
    $client = new Client($url, $key);
    $request = new OffersExportRequest();
$request->setIncludeInactiveOffers(true);


      $result = $client->exportOffers($request);
      return $result;
  }





}

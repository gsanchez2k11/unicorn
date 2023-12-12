<?php
namespace unicorn\clases\funciones\mirakl;

error_reporting(E_ALL);                                     //Activamos el reporte de errores
ini_set('display_errors', 'on');
//require_once __DIR__ . '/../../../config.php';
require_once(RAIZ . '/vendor/autoload.php');
//require_once RAIZ . '/clases/objetos/Articulo_mirakl.php';            // La clase compraarticulo
//require_once RAIZ . '/clases/objetos/Articulos/Articulo_mirakl.php';
//require_once RAIZ . '/clases/funciones/unicorn_db/Contadores.php';

use Mirakl\MMP\Shop\Client\ShopApiClient as Client;
use Mirakl\MMP\Shop\Request\Offer\GetOffersRequest;
use Mirakl\MMP\Shop\Request\Offer\GetOfferRequest;
use Mirakl\MMP\Shop\Request\Product\Offer\GetOffersOnProductsRequest;
use Mirakl\MMP\Operator\Client\OperatorApiClient;
use Mirakl\MMP\Operator\Request\Order\Document\GetOrderDocumentsRequest;
//use unicorn\clases\objetos\Articulos\Articulo_mirakl as articulo;
//use unicorn\clases\funciones\unicorn_db\Contadores as contadores;
use Mirakl\Core\Domain\Collection\DocumentCollection;
use Mirakl\Core\Domain\Document;
use Mirakl\MMP\Shop\Request\Order\Document\UploadOrdersDocumentsRequest;

/**
 *
 */
class Documentos
{

/**
 * (OR72) List order's documents
 * https://pccomponentes-prod.mirakl.net/help/api-doc/seller/mmp.html#OR72
 * @param  [type] $pedido [description]
 * @return [type]         [description]
 */
  public static function dameDocumentos($pedido){
  $pedidos[] = $pedido;
  $client = new OperatorApiClient(APIURLPCCOMPONENTES, APIKEYPCCOMPONENTES);
  $request = new GetOrderDocumentsRequest($pedidos);

  $request->setOrderIds($pedidos);
  $result = $client->getOrderDocuments($request);
  //Nos quedamos con los items
  $resultado = $result->getItems();
return $resultado;
}

public static function tieneFactura($pedido){
  $items = self::dameDocumentos($pedido);
  $tipo_doc = false;
foreach ($items as $documento) {
$tipo = $documento->getData()['type_code'];
if ($tipo == 'CUSTOMER_INVOICE') {
$tipo_doc = true;
}
}
return $tipo_doc;
}


/**
 * (OR73) Download one or multiple documents associated to one or multiple orders
 * https://pccomponentes-prod.mirakl.net/help/api-doc/seller/mmp.html#OR73
 * @return [type] [description]
 */
public static function descargaDocumentos(){
/*
  * <code>
  * use Mirakl\MMP\Shop\Client\ShopApiClient;
  * use Mirakl\MMP\Shop\Request\Order\Document\DownloadOrdersDocumentsRequest;
  *
  * $api = new ShopApiClient('API_URL', 'API_KEY', 'SHOP_ID');
  * $request = new DownloadOrdersDocumentsRequest();
  * $request->setOrderIds(['ORDER_ID_1', 'ORDER_ID_2']);
  * $result = $api->downloadOrdersDocuments($request);
  * // $result => @see \Mirakl\Core\Domain\FileWrapper
  * // Download file:
  * $result->download();
  * </code>
  * */
}


/**
 * (OR74) Upload documents to associate to an order
 * https://pccomponentes-prod.mirakl.net/help/api-doc/seller/mmp.html#OR74
 * @param  [type] $id_pedido [description]
 * @return [type]            [description]
 */
public static function subeFactura($id_pedido){
$documento = $id_pedido . '.pdf';
$client = new Client(APIURLPCCOMPONENTES, APIKEYPCCOMPONENTES);
$file = new \SplFileObject(RAIZ . '/docs/' . $id_pedido . '.pdf');
$docs = new DocumentCollection();
$docs->add(new Document($file, $documento, 'CUSTOMER_INVOICE'));
$request = new UploadOrdersDocumentsRequest($docs, $id_pedido);
$result = $client->uploadOrderDocuments($request);
return $result;
}


}

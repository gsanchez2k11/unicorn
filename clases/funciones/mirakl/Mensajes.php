<?php
namespace unicorn\clases\funciones\mirakl;

error_reporting(E_ALL);                                     //Activamos el reporte de errores
ini_set('display_errors', 'on');
//require_once __DIR__ . '/../../../config.php';
require_once(RAIZ . '/vendor/autoload.php');

//use Mirakl\MMP\Front\Client\FrontApiClient;
use Mirakl\MMP\Front\Request\Message\GetThreadDetailsRequest;
 use Mirakl\MMP\Front\Request\Message\GetThreadsRequest;
 use Mirakl\MMP\Common\Domain\Message\Thread\ThreadTopic;
 use Mirakl\MMP\Common\Domain\Order\Message\CreateOrderThread;
 use Mirakl\MMP\Common\Request\Order\Message\CreateOrderThreadRequest;
use Mirakl\MMP\Shop\Client\ShopApiClient;


/**
 *
 */
class Mensajes
{


/**
 * M10 - Retrieve a thread
 * https://pccomponentes-prod.mirakl.net/help/api-doc/seller/mmp.html#M10
 * @param  [type] $id_hilo [description]
 * @return [type]          [description]
 */
public static function recuperaHilo($id_hilo){

$api = new ShopApiClient(APIURLPCCOMPONENTES, APIKEYPCCOMPONENTES);
$request = new GetThreadDetailsRequest($id_hilo);
$request->setCustomerId('2395');
$result = $api->getThreadDetails($request);
return $result;
}

/**
 * M11 - List all threads
 * https://pccomponentes-prod.mirakl.net/help/api-doc/seller/mmp.html#M11
 * @return [type] [description]
 */
public static function listaHilos(){


$api = new ShopApiClient(APIURLPCCOMPONENTES, APIKEYPCCOMPONENTES);
 $request = new GetThreadsRequest();
 //$request->setCustomerId('CUSTOMER_ID');
 //$request->setEntityType('MMP_ORDER');
 //$request->setEntityId('ORDER_ID');
 $result = $api->getThreads($request);
return $result;
}

/**
 * M12 - Reply to a thread
 * https://pccomponentes-prod.mirakl.net/help/api-doc/seller/mmp.html#M12
 * @param  [type] $id_hilo [description]
 * @return [type]          [description]
 */
public static function respondeHilo($id_hilo){

}

/**
 * M13 - Download an attachment
 * https://pccomponentes-prod.mirakl.net/help/api-doc/seller/mmp.html#M13
 * @return [type] [description]
 */
public static function descargaAdjunto(){

}

/**
 * OR43 - Create a thread on an order
 * https://pccomponentes-prod.mirakl.net/help/api-doc/seller/mmp.html#OR43
 * @return [type] [description]
 */
public static function creaHilo($id_pedido, $asunto, $cuerpo){
  $api = new ShopApiClient(APIURLPCCOMPONENTES, APIKEYPCCOMPONENTES);

   $thread = new CreateOrderThread();
   $thread->setBody($cuerpo);
   // $thread->setTo(['SHOP', 'OPERATOR']);
   $thread->setTo(['CUSTOMER']);
   $topic = new ThreadTopic();
   $topic->setType('FREE_TEXT');
   $topic->setValue($asunto);
   $thread->setTopic($topic);

   $request = new CreateOrderThreadRequest($id_pedido, $thread);

   // Add a file
  // $file = new FileWrapper(new \SplFileObject('foobar.txt'));
  // $file->setFileName('test1.txt'); // Optional, only needed if file name different than 'foobar.txt'
  // $request->addFile($file);

   $result = $api->createOrderThread($request);
}


}


 ?>

<?php
namespace unicorn\clases\funciones\mirakl;
error_reporting(E_ALL);                                     //Activamos el reporte de errores
ini_set('display_errors', 'on');
//require_once __DIR__ . '/../../../config.php';
require_once(RAIZ . '/vendor/autoload.php');
use Mirakl\MMP\Shop\Client\ShopApiClient as Client;
use Mirakl\MMP\Common\Request\Shipping\AbstractGetShippingCarriersRequest;

/**
 *
 */
class Agencias
{
/**
 * (SH21) List all carriers (sorted by sortIndex, defined in the BO)
 * @return [type] [description]
 */
public static function listarAgencias(string $plataforma = 'pcc') {
switch ($plataforma) {
  case 'pcc':
$url = APIURLPCCOMPONENTES;
$key = APIKEYPCCOMPONENTES;
    break;
    case 'phh':
    $url = APIURLPHONEHOUSE;
    $key = APIKEYPHONEHOUSE;
      break;
}


  $client = new Client($url, $key);
  $result = $client->getShippingCarriers();
  return $result;
}

/**
 * (SH31) List all logistic classes
 * @return [type] [description]
 */
public static function listarClaseslogisticas(string $plataforma = 'pcc') {
  switch ($plataforma) {
    case 'pcc':
  $url = APIURLPCCOMPONENTES;
  $key = APIKEYPCCOMPONENTES;
      break;
      case 'phh':
      $url = APIURLPHONEHOUSE;
      $key = APIKEYPHONEHOUSE;
        break;
  }


    $client = new Client($url, $key);
  $result = $client->getLogisticClasses();
  return $result->getItems();
}

}


?>

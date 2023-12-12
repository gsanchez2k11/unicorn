<?php
namespace unicorn\clases\funciones\mirakl;
error_reporting(E_ALL);                                     //Activamos el reporte de errores
ini_set('display_errors', 'on');
//require_once __DIR__ . '/../../../config.php';
require_once(RAIZ . '/vendor/autoload.php');
use Mirakl\MMP\Front\Client\FrontApiClient;
use Mirakl\MMP\Front\Request\AdditionalField\GetAdditionalFieldRequest;
use Mirakl\MMP\Common\Domain\AdditionalField\AdditionalFieldLinkedEntity;
use Mirakl\MMP\Shop\Client\ShopApiClient as Client;

class Platformsettings
{
  /**
   * (AF01) Get the list of any additional fields
   * @return [type] [description]
   */
public static function dameCampos() {
  $url = 'https://mediamarktsaturn.mirakl.net/api';
  $key = 'b2701192-22b2-4b7e-bfbb-d593d1095bba';
  $client = new Client($url, $key);
  $request = new GetAdditionalFieldRequest();
  $request->setEntities([AdditionalFieldLinkedEntity::OFFER, AdditionalFieldLinkedEntity::SHOP]); // Optional
  $result = $client->getAdditionalFields($request);
  return $result;
}

}


?>

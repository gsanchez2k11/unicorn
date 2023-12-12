<?php
//namespace unicorn\clases\funciones\fnac;
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../../config.php.inc';
//use unicorn\clases\funciones\fnac\Conectar as conectar;
//require_once RAIZ . '/clases/funciones/fnac/Conectar.php';
//$token = conectar::getToken();

use unicorn\clases\funciones\fnac\Ofertas as ofertas;
require_once RAIZ . '/clases/funciones/fnac/Ofertas.php';

$token = ofertas::dameOfertas();
echo "<pre>";
print_r($token);
echo "</pre>";


$offers_request_xml = <<<XML
<?xml version="1.0" encoding="utf-8"?>
<offers_query results_count="2" xmlns="http://www.fnac.com/schemas/mp-dialog.xsd" shop_id="0000000-4AC1-6B59-1AB5-98462FB4B3B1" partner_id="0000000-945A-527F-3D1C-3C1D353A49D5" token="0000000-5814-B0A9-6AFB-EDDB32253631">
  <paging>1</paging>
</offers_query>
XML;

  $xmlOffersRequest               = simplexml_load_string($offers_request_xml);

  $xmlOffersRequest['partner_id'] = '28B1A6B6-2206-6248-F294-E12C728D0E04';
  $xmlOffersRequest['shop_id']    = '61690C3A-34DD-C7B4-AB38-F3136914713B';
  $xmlOffersRequest['token']      = $token;


  libxml_use_internal_errors(true);
  $dom = new DOMDocument;
  $dom->loadXML($xmlOffersRequest->asXML());




  // Send xml to webservice OffersUpdate
  $response    = conectar::do_post_request('https://vendeur.fnac.com/api.php/' . "offers_query", $xmlOffersRequest->asXML());
  $xmlResponse = simplexml_load_string(trim($response));

  // STEP 3: Handle the response
  // Offers_update gives the batch id which can be used with batch_status service to get status of your import. Here, we are simply displaying it.
  var_dump($xmlResponse);











 ?>

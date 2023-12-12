<?php
namespace unicorn\clases\funciones\fnac;
error_reporting(E_ALL);
ini_set('display_errors', 1);
//require_once __DIR__ . '/../../config.php.inc';
use unicorn\clases\funciones\fnac\Conectar as conectar;
require_once 'Conectar.php';
require_once RAIZ . '/clases/objetos/Articulos/Articulo_fnac.php';
use unicorn\clases\objetos\Articulos\Articulo_fnac as articuloFnac;


/**
 *
 */
class Ofertas extends Conectar
{

  public static function dameOfertas($developer = 'false'){
    $token = self::getToken();                                                  //Obtenemos el token
$ofertas = array();
$pagina = 1;
//Preparamos el xml
$offers_request_xml = <<<XML
<?xml version="1.0" encoding="utf-8"?>
<offers_query xmlns="http://www.fnac.com/schemas/mp-dialog.xsd" shop_id="0000000-4AC1-6B59-1AB5-98462FB4B3B1" partner_id="0000000-945A-527F-3D1C-3C1D353A49D5" token="0000000-5814-B0A9-6AFB-EDDB32253631">
</offers_query>
XML;

  $xmlOffersRequest               = simplexml_load_string($offers_request_xml);

  $xmlOffersRequest['partner_id'] = self::FNAC_PARTNER_ID;
  $xmlOffersRequest['shop_id']    = self::FNAC_SHOP_ID;
  $xmlOffersRequest['token']      = $token;
$xmlOffersRequest->addChild('paging',$pagina);
  libxml_use_internal_errors(true);


  // Send xml to webservice OffersUpdate
  $response    = self::do_post_request(self::FNAC_URL . "offers_query", $xmlOffersRequest->asXML());
  $xmlResponse = simplexml_load_string(trim($response), NULL, LIBXML_NOCDATA);
  foreach ($xmlResponse->offer as $oferta) { //Recorremos el resultado y añadimos las ofertas a un array
  $ofertas[] =   $oferta;
  }
$total_paginas = $xmlResponse->total_paging;

while ($pagina < $total_paginas) {
$pagina++;  //Sumamos una página
$xmlOffersRequest->paging = $pagina; //Modificamos el valor
//echo 'hijo ' . $pagina;
// Send xml to webservice OffersUpdate
$response    = self::do_post_request(self::FNAC_URL . "offers_query", $xmlOffersRequest->asXML());
$xmlResponse = simplexml_load_string(trim($response), NULL, LIBXML_NOCDATA);
foreach ($xmlResponse->offer as $oferta) { //Recorremos el resultado y añadimos las ofertas a un array
$ofertas[] =   $oferta;
}
}
//  echo "<pre>";
//  print_r($ofertas);
//  echo "</pre>";
//  $ofertas[] = $xmlResponse->offer;
//  echo 'pagina' . $xmlResponse->page;


$array = array();
foreach ( $ofertas as $oferta) {
  if (isset($oferta->product_fnac_id)) {
$articulo = array(
  'price' => (string) $oferta->price,
  'product_fnac_id' => (string) $oferta->product_fnac_id,
  'offer_seller_id' => (string) $oferta->offer_seller_id,
  'product_state' => (string) $oferta->product_state,
  'quantity' => (string) $oferta->quantity,
  'offer_fnac_id' => (string) $oferta->offer_fnac_id,
  'product_name' => (string) $oferta->product_name,
  'categoria' => (string) $oferta->type_label
);

$obj_articulo = new articuloFnac($articulo);
/*  echo "<pre>";
  print_r($obj_articulo);
  echo $obj_articulo->getEan() . '<br />';
  echo "</pre>";*/

$array[$obj_articulo->getEan()] = $obj_articulo;
  }
}
$salida = $developer === true ? $xmlResponse : $array;
    return $salida;
  }


  public static function BuscarOferta(string $mpn){
    $token = self::getToken();                                                  //Obtenemos el token

//Preparamos el xml
$offers_request_xml = <<<XML
<?xml version="1.0" encoding="utf-8"?>
<offers_query xmlns="http://www.fnac.com/schemas/mp-dialog.xsd" shop_id="0000000-4AC1-6B59-1AB5-98462FB4B3B1" partner_id="0000000-945A-527F-3D1C-3C1D353A49D5" token="0000000-5814-B0A9-6AFB-EDDB32253631">
<paging>1</paging>
</offers_query>
XML;

  $xmlOffersRequest               = simplexml_load_string($offers_request_xml);

  $xmlOffersRequest['partner_id'] = self::FNAC_PARTNER_ID;
  $xmlOffersRequest['shop_id']    = self::FNAC_SHOP_ID;
  $xmlOffersRequest['token']      = $token;

$xmlOffersRequest->addChild('offer_seller_id',$mpn);
  libxml_use_internal_errors(true);

  $response    = self::do_post_request(self::FNAC_URL . "offers_query", $xmlOffersRequest->asXML());
  $xmlResponse = simplexml_load_string(trim($response), NULL, LIBXML_NOCDATA);

return $xmlResponse;
  }




/**
 * Actualiza o crea una nueva oferta
 * @param  array  $ofertas               Array con los campos para actualizar o crear la oferta
 * @return SimpleXMLElement         Status y batch id
 */
  public static function actualizaOfertas(array $ofertas){
    $token = self::getToken();
    // Generate the offers_update request
$offers_update_request_xml = <<<XML
<?xml version='1.0' encoding="utf-8"?>
<offers_update partner_id="00000000-0000-0000-0000-000000000000" shop_id="00000000-0000-0000-0000-000000000000" token="00000000-0000-0000-0000-000000000000" xmlns='http://www.fnac.com/schemas/mp-dialog.xsd'>
</offers_update>
XML;

  $xmlOffersUpdate               = simplexml_load_string($offers_update_request_xml);
  // Load authentication parameters and token within request
  $xmlOffersUpdate['partner_id'] = self::FNAC_PARTNER_ID;
  $xmlOffersUpdate['shop_id']    = self::FNAC_SHOP_ID;
  $xmlOffersUpdate['token']      = $token;


  foreach ($ofertas as $oferta) {
$xmloferta = $xmlOffersUpdate->addChild('offer');
$mpn = $xmloferta->addChild('offer_reference', $oferta['mpn']);
$stock = $xmloferta->addChild('quantity',  $oferta['stock_final']);
$status = $xmloferta->addChild('product_state',  '11');
$tiempo_envio = $xmloferta->addChild('time_to_ship',  '1');
if (isset($oferta['precio'])) {
$precio = $xmloferta->addChild('price',  $oferta['precio']);
}
if (isset($oferta['ean'])) {
$ean = $xmloferta->addChild('product_reference',  $oferta['ean']);
$ean->addAttribute('type','Ean');
}
  }

  // Send xml to webservice OffersUpdate
  $response    = self::do_post_request(self::FNAC_URL . "offers_update", $xmlOffersUpdate->asXML());
  $xmlResponse = simplexml_load_string(trim($response));

  return $xmlResponse;

  }

}


 ?>

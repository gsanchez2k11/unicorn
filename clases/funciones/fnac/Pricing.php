<?php
namespace unicorn\clases\funciones\fnac;
error_reporting(E_ALL);
ini_set('display_errors', 1);
//require_once __DIR__ . '/../../config.php.inc';
use unicorn\clases\funciones\fnac\Conectar as conectar;
require_once 'Conectar.php';
//require_once RAIZ . '/clases/objetos/Articulos/Articulo_fnac.php';
//use unicorn\clases\objetos\Articulos\Articulo_fnac as articulo;


/**
 *
 */
class Pricing extends Conectar
{

  public static function damePricing(array $arr_products_sku){
    $token = self::getToken();                                                  //Obtenemos el token
    $array = array();
    foreach ($arr_products_sku as $arr_products) {

    $princing_query_xml = <<<XML
<?xml version='1.0' encoding="utf-8"?>
<pricing_query xmlns="http://www.fnac.com/schemas/mp-dialog.xsd" shop_id="BBBFA40E-3A94-2EE1-762A-2858EDE4F9BB" partner_id="C906104B-9B13-611D-6104-261780F88E38" token="6EB5F43F-33CF-5C33-D204-278907C0FD9D" sellers="all">
</pricing_query>
XML;
//<product_reference type="Ean">4977766783118</product_reference>
      $xmlPricingQuery               = simplexml_load_string($princing_query_xml);
      $xmlPricingQuery['partner_id'] = conectar::FNAC_PARTNER_ID;
      $xmlPricingQuery['shop_id']    = conectar::FNAC_SHOP_ID;
      $xmlPricingQuery['token']      = $token;
      foreach ($arr_products as $ean) {
    $xmloferta = $xmlPricingQuery->addChild('product_reference', $ean);
    $xmloferta->addAttribute('type','Ean');
      }
libxml_use_internal_errors(true);
      $response    = conectar::do_post_request(conectar::FNAC_URL . "pricing_query", $xmlPricingQuery->asXML());
    $xmlResponse = simplexml_load_string(trim($response), NULL, LIBXML_NOCDATA);


if (isset($xmlResponse->pricing_product)) {
    foreach ( $xmlResponse->pricing_product as $oferta) {
      if (!empty((string) $oferta->pricing->price)) {

      $articulo = array(
        'precio' => (float) $oferta->pricing->price,
        'tienda' => 'desconocida',
        'calif_tienda' => '-',
        'tipo_envio' => '-',
        'portes' => (float) $oferta->pricing->shipping_price
      );
      $array[$oferta->product_reference . ' '][] = $articulo;
        }
  //    $array[(string) $oferta->product_reference] = $oferta;
    }
    }
  }


return $array;

/*$array = array();
foreach ( $xmlResponse as $oferta) {
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

$obj_articulo = new Articulo($articulo);
$array[$obj_articulo->getEan()] = $obj_articulo;
  }

}
$salida = $developer === true ? $xmlResponse : $array;
    return $salida;*/
  }


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
$xmloferta->addChild('offer_reference', $oferta['mpn']);
$xmloferta->addChild('quantity',  $oferta['stock_final']);
if ($oferta['precio']) {
$xmloferta->addChild('price',  $oferta['precio']);
}
  }

  // Send xml to webservice OffersUpdate
  $response    = self::do_post_request(self::FNAC_URL . "offers_update", $xmlOffersUpdate->asXML());
  $xmlResponse = simplexml_load_string(trim($response));

  return $xmlResponse;
  }

}


 ?>

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

$token = ofertas::getToken();

$princing_query_xml = <<<XML
<?xml version='1.0' encoding="utf-8"?>
<pricing_query xmlns="http://www.fnac.com/schemas/mp-dialog.xsd" shop_id="BBBFA40E-3A94-2EE1-762A-2858EDE4F9BB" partner_id="C906104B-9B13-611D-6104-261780F88E38" token="6EB5F43F-33CF-5C33-D204-278907C0FD9D" sellers="all">
<product_reference type="Ean">4977766783118</product_reference>
</pricing_query>
XML;

  $xmlPricingQuery               = simplexml_load_string($princing_query_xml);
  $xmlPricingQuery['partner_id'] = ofertas::FNAC_PARTNER_ID;
  $xmlPricingQuery['shop_id']    = ofertas::FNAC_SHOP_ID;
  $xmlPricingQuery['token']      = $token;

  $response    = ofertas::do_post_request(ofertas::FNAC_URL . "pricing_query", $xmlPricingQuery->asXML());
  $xmlResponse = simplexml_load_string(trim($response));
echo "<pre>";
print_r($xmlResponse);


echo "</pre>";
 ?>

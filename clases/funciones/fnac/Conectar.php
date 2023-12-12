<?php
namespace unicorn\clases\funciones\fnac;

/**
 *
 */
class Conectar
{

  //Production credentials
  //partner_id: 28B1A6B6-2206-6248-F294-E12C728D0E04
  //shop_id: 61690C3A-34DD-C7B4-AB38-F3136914713B
  //key: 23DACA95-5C55-C602-036F-D2730CC3B01C
  //url: https://vendeur.fnac.com/api.php/

  //Test credentials
  //partner_id: 763256DD-A032-6E08-2E1A-76EB1DBC2F03
  //shop_id: E7478221-1188-3D5B-DE0E-0292A91087D0
  //key: 97468F56-B907-C9D5-4DB3-694A2478687A
  //url: https://partners-test.mp.fnacdarty.com/api.php/

  const FNAC_PARTNER_ID = '28B1A6B6-2206-6248-F294-E12C728D0E04';
  const FNAC_SHOP_ID = '61690C3A-34DD-C7B4-AB38-F3136914713B';
  const FNAC_KEY = '23DACA95-5C55-C602-036F-D2730CC3B01C';
  const FNAC_URL = 'https://vendeur.fnac.com/api.php/';

  const FNAC_PARTNER_ID_TEST = '763256DD-A032-6E08-2E1A-76EB1DBC2F03';
  const FNAC_SHOP_ID_TEST = 'E7478221-1188-3D5B-DE0E-0292A91087D0';
  const FNAC_KEY_TEST = '97468F56-B907-C9D5-4DB3-694A2478687A';
  const FNAC_URL_TEST = 'https://partners-test.mp.fnacdarty.com/api.php/';

public static function getToken(){
  // STEP 1: Authenticate to the API
  // Generate the authentication request
  $auth_request_xml = <<<XML
<?xml version='1.0' encoding='utf-8'?>
<auth xmlns='http://www.fnac.com/schemas/mp-dialog.xsd'>
<partner_id></partner_id>
<shop_id></shop_id>
<key></key>
</auth>
XML;

  $xmlAuthentication              = simplexml_load_string($auth_request_xml);
  // Load authentication parameters within request
  $xmlAuthentication->partner_id  = self::FNAC_PARTNER_ID;
  $xmlAuthentication->shop_id     = self::FNAC_SHOP_ID;
  $xmlAuthentication->key         = self::FNAC_KEY;

  // Send xml request to webservice auth
  $response    = self::do_post_request(self::FNAC_URL . "auth", $xmlAuthentication->asXML());
  $xmlResponse = simplexml_load_string(trim($response));
  // Get token for session authentication
  $token = $xmlResponse->token;
return $token;

}
/***
 * do_post_request
 * ===============
 * @param string $url contains the service url to call for the request.
 * @param string $data contains the request to send. In this purpose, XML data are sent.
 *
 * This function sends request by POST. Any method to send the request can be used, here we are using a cURL session.
 */

 static function do_post_request($url, $data)
 {
   $ch = curl_init();

   // Depending on your system, you may add other options or modify the following ones.
   curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
   curl_setopt($ch, CURLOPT_POST, 1);
   curl_setopt($ch, CURLOPT_URL, $url);
   curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
   curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
   curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
   curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

   $response = curl_exec($ch);
   curl_close($ch);

   return $response;
 }


}

 ?>

<?php
  // Fnac Marketplace offers_update example


//Production credentials
//partner_id: 28B1A6B6-2206-6248-F294-E12C728D0E04
//shop_id: 61690C3A-34DD-C7B4-AB38-F3136914713B
//key: 23DACA95-5C55-C602-036F-D2730CC3B01C

//Test credentials
//partner_id: 763256DD-A032-6E08-2E1A-76EB1DBC2F03
//shop_id: E7478221-1188-3D5B-DE0E-0292A91087D0
//key: 97468F56-B907-C9D5-4DB3-694A2478687A

  // Set the API authentication parameters
  $partner_id = '763256DD-A032-6E08-2E1A-76EB1DBC2F03';
  $shop_id    = 'E7478221-1188-3D5B-DE0E-0292A91087D0';
  $key        = '97468F56-B907-C9D5-4DB3-694A2478687A';

  $url        = 'https://partners-test.mp.fnacdarty.com/api.php/';

  // STEP 1: Authenticate to the API
  // Generate the authentication request
  $auth_request_xml = <<<XML
<?xml version='1.0' encoding='utf-8'?>
<auth xmlns='http://www.fnac.com/schemas/mp-dialog.xsd'>
  <partner_id>28B1A6B6-2206-6248-F294-E12C728D0E04</partner_id>
  <shop_id>61690C3A-34DD-C7B4-AB38-F3136914713B</shop_id>
  <key>23DACA95-5C55-C602-036F-D2730CC3B01C</key>
</auth>
XML;


  $xmlAuthentication              = simplexml_load_string($auth_request_xml);
  // Load authentication parameters within request
  $xmlAuthentication->partner_id  = $partner_id;
  $xmlAuthentication->shop_id     = $shop_id;
  $xmlAuthentication->key         = $key;

  // Send xml request to webservice auth
  $response    = do_post_request($url . "auth", $xmlAuthentication->asXML());
  $xmlResponse = simplexml_load_string(trim($response));

  // Display auth response
  var_dump($xmlResponse);
  echo "\n<hr/>";

  // Get token for session authentication
  $token = $xmlResponse->token;

  // STEP 2: Send an offers_update request
  // Generate the offers_update request
  $offers_update_request_xml = <<<XML
<?xml version='1.0' encoding="utf-8"?>
<offers_update partner_id="00000000-0000-0000-0000-000000000000" shop_id="00000000-0000-0000-0000-000000000000" token="00000000-0000-0000-0000-000000000000" xmlns='http://www.fnac.com/schemas/mp-dialog.xsd'>
  <offer>
    <product_reference type='Ean'>0886971942323</product_reference>
    <offer_reference type='SellerSku'>SKU_TEST_1</offer_reference>
    <price>50</price>
    <product_state>11</product_state>
    <quantity>999</quantity>
    <description_fr>Ceci est une offre de test.</description_fr>
  </offer>
</offers_update>
XML;


  $xmlOffersUpdate               = simplexml_load_string($offers_update_request_xml);
  // Load authentication parameters and token within request
  $xmlOffersUpdate['partner_id'] = $partner_id;
  $xmlOffersUpdate['shop_id']    = $shop_id;
  $xmlOffersUpdate['token']      = $token;

  libxml_use_internal_errors(true);
  $dom = new DOMDocument;
  $dom->loadXML($xmlOffersUpdate->asXML());

  // Check XSD compliance of the generated request. This step is very useful to spot possible formatting errors.
  $valid = $dom->schemaValidate('../xsd/OffersUpdateService.xsd');
  $error = libxml_get_errors();
  if(!$valid)
  {
    // Display found errors
    var_dump($error);
    die();
  }

  // Send xml to webservice OffersUpdate
  $response    = do_post_request($url . "offers_update", $xmlOffersUpdate->asXML());
  $xmlResponse = simplexml_load_string(trim($response));

  // STEP 3: Handle the response
  // Offers_update gives the batch id which can be used with batch_status service to get status of your import. Here, we are simply displaying it.
  var_dump($xmlResponse);



 /***
  * do_post_request
  * ===============
  * @param string $url contains the service url to call for the request.
  * @param string $data contains the request to send. In this purpose, XML data are sent.
  *
  * This function sends request by POST. Any method to send the request can be used, here we are using a cURL session.
  */

  function do_post_request($url, $data)
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

?>

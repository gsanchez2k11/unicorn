<?php
$string_xml = <<<XML
<?xml version='1.0' encoding="utf-8"?>
<offers_update partner_id="00000000-0000-0000-0000-000000000000" shop_id="00000000-0000-0000-0000-000000000000" token="00000000-0000-0000-0000-000000000000" xmlns='http://www.fnac.com/schemas/mp-dialog.xsd'>
</offers_update>
XML;


$xmlOffersUpdate               = simplexml_load_string($string_xml);
$xmlOffersUpdate->addChild('offers');
echo "<pre>";
  print_r($xmlOffersUpdate);
  echo "</pre>";
 ?>

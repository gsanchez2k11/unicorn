<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . '/../../config.php.inc';
use unicorn\vendor\econea\nusoap\src as nusoap;
require_once RAIZ . '/vendor/econea/nusoap/src/nusoap.php'; //Incluimos la libreria nusoap

$proxyhost     = isset($_POST['proxyhost']) ? $_POST['proxyhost'] : '';
$proxyport     = isset($_POST['proxyport']) ? $_POST['proxyport'] : '';
$proxyusername = isset($_POST['proxyusername']) ? $_POST['proxyusername'] : '';
$proxypassword = isset($_POST['proxypassword']) ? $_POST['proxypassword'] : '';
$timeout = 20;
$response_timeout = 10;


$client = new
nusoap\nusoap_client('http://www.inforpor.com/ServiciosWeb/WebInforpor.asmx?WSDL', 'wsdl',
$proxyhost, $proxyport, $proxyusername, $proxypassword, $timeout, $response_timeout);
$err = $client->getError();
if ($err) {
  echo '<h2>Constructor error</h2><pre>' . $err . '</pre>';
} 
$parametros = array (
  'ean' => '8436550236385'
);
$param  = array('Clave' => 'S159RmVer', $parametros);
$result = $client->call('VerCodeRef', array('parameters' => $param), '', '', false, true);

?>
<?php

   echo '<pre>';
print_r($result);
echo '</pre>';

/**
* Array
*(
*   [VerCodeRefResult] => ERROR
*)
* 
*/



$param  = array('CIF' => 'B30507743', 'User' => 'AL0030', 'Clave' => 'AL0031', 'Cod' => '48072', 'CodigoPromo' => '');
$result = $client->call('StockPr', array('parameters' => $param), '', '', false, true);


echo '<pre>';
print_r($result);
echo '</pre>';
/*
Array
(
    [StockPrResult] => Array
        (
            [CodErr] => 0
            [Cod] => 48072
            [Referencia] => TAL-CRAB-BLU
            [Stock] => 41
            [Precio] => 79,90
            [lpi] => 0,00
            [CodigoPromocion] => Promo Lanzamiento
            [maxUd] => 44
            [EAN] => 8436550236385
            [FechaEntrada] => 
        )

)
*/
 ?>

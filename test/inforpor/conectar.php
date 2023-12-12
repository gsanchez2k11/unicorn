<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . '/../../config.php.inc';
use unicorn\clases\funciones\inforpor\Conectar as conectar;
require RAIZ . '/clases/funciones/inforpor/Conectar.php';
use unicorn\vendor\econea\nusoap\src as nusoap;

require_once RAIZ . '/vendor/econea/nusoap/src/nusoap.php'; //Incluimos la libreria nusoap
?>
<?php
      $proxyhost     = isset($_POST['proxyhost']) ? $_POST['proxyhost'] : '';
      $proxyport     = isset($_POST['proxyport']) ? $_POST['proxyport'] : '';
      $proxyusername = isset($_POST['proxyusername']) ? $_POST['proxyusername'] : '';
      $proxypassword = isset($_POST['proxypassword']) ? $_POST['proxypassword'] : '';
      $timeout = 20;
      $response_timeout = 10;
      const CIF   = 'B30507743';
      const USER  = 'AL0030';
      const CLAVE = 'AL0031';

      $client = new
      nusoap\nusoap_client('http://www.inforpor.com/ServiciosWeb/WebInforpor.asmx?WSDL', 'wsdl',
      $proxyhost, $proxyport, $proxyusername, $proxypassword, $timeout, $response_timeout);
      $err = $client->getError();
      if ($err) {
        echo '<h2>Constructor error</h2><pre>' . $err . '</pre>';
      }     
//$client->forceEndpoint = 'http://www.inforpor.com/ServiciosWeb/webinforpor.asmx';

 // echo '<pre>';
 // print_r($client);
  //echo '</pre>';
 //     $client->checkWSDL();
      //$Cod_inforpor= '48072';
    //  $param  = array('CIF' => CIF, 'User' => USER, 'Clave' => CLAVE, 'Cod' => $Cod_inforpor, 'CodigoPromo' => '');
      $param  = array( 'Clave' => 'S159RmVer');
$result = $client->call('GetMarcasH', array('parameters' => $param), '', '', false, true);
//$result = $client->call('StockPr', array('parameters' => $param), '', '', false, true);

if ($client->fault) {
  echo 'Error: ';
  print_r($result);
} else {
  // check result
  $err_msg = $client->getError();
  if ($err_msg) {
      // Print error msg
      echo 'Errorr: '.$err_msg;
  } else {
      // Print result
      echo 'Result: ';
      print_r($result);
  }
}


echo '<pre>';
print_r($result);
//print_r(get_class_methods($client));
echo '</pre>';
 ?>

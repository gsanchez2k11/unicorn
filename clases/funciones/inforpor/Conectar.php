<?php
/**
 *
 */
namespace unicorn\clases\funciones\inforpor;
use unicorn\vendor\econea\nusoap\src as nusoap;
require_once RAIZ . '/vendor/econea/nusoap/src/nusoap.php'; //Incluimos la libreria nusoap
use unicorn\clases\funciones\unicorn_db\Config as config;
require_once RAIZ . '/clases/funciones/unicorn_db/Config.php';

class Conectar
{
  const CIF   = 'B30507743';
  const USER  = 'AL0030';
  const CLAVE = 'AL0031';

  /* Datos para pruebas solicitados por inforpor*/
  const CIF_TEST   = 'DISTINF';
  const USER_TEST  = 'BUDI12';
  const CLAVE_TEST = 'S123';

  //const CIF = config::dameValorConfig('inforpor_cif');
  //const USER  = config::dameValorConfig('inforpor_user');
  //const CLAVE = 'AL0031';

  /* Datos para pruebas solicitados por inforpor*/
  //const CIF_TEST   = 'DISTINF';
  //const USER_TEST  = 'BUDI12';
  //const CLAVE_TEST = 'S123';


  //------------------------------------------------------------------//
    //Funcion que crea el objeto nusoap_client                            //
    //------------------------------------------------------------------//
    public static function Crearcliente()
    {
    //  include RAIZ . '/vendor/econea/nusoap/src/nusoap.php'; //Incluimos la libreria nusoap

      $proxyhost     = isset($_POST['proxyhost']) ? $_POST['proxyhost'] : '';
      $proxyport     = isset($_POST['proxyport']) ? $_POST['proxyport'] : '';
      $proxyusername = isset($_POST['proxyusername']) ? $_POST['proxyusername'] : '';
      $proxypassword = isset($_POST['proxypassword']) ? $_POST['proxypassword'] : '';
      $timeout = 20;
      $response_timeout = 10;


      $client = new
      \nusoap_client('http://www.inforpor.com/ServiciosWeb/WebInforpor.asmx?WSDL', 'wsdl',
      $proxyhost, $proxyport, $proxyusername, $proxypassword, $timeout, $response_timeout);
      $err = $client->getError();
      if ($err) {
        echo '<h2>Constructor error</h2><pre>' . $err . '</pre>';
      } else {
        $client->forceEndpoint = 'http://www.inforpor.com/ServiciosWeb/webinforpor.asmx';
          $client->soap_defencoding = 'utf-8';
          $client->xml_encoding = 'utf-8';
          $client->decode_utf8 = false;
        return $client;
      }
    }

public static function dameValorC($valor){
$valor_retorno = config::dameValorConfig('inforpor_'.$valor);
return $valor_retorno;
}


protected static function formatearImporte($cadena){
  $number = floatval(str_replace(',', '.', str_replace('.', '', $cadena)));
return $number;

}



}

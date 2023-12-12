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
class Batch extends Conectar
{


//Listamos todas las operaciones actuales
public static function batchQuery(){
    $token = self::getToken();

    //Preparamos el xml
$batch_query_xml = <<<XML
<?xml version="1.0" encoding="utf-8"?>
<batch_query xmlns="http://www.fnac.com/schemas/mp-dialog.xsd" shop_id="" partner_id="" token="">
</batch_query>
XML;

$batchQueryRequest               = simplexml_load_string($batch_query_xml);

$batchQueryRequest['partner_id'] = self::FNAC_PARTNER_ID;
$batchQueryRequest['shop_id']    = self::FNAC_SHOP_ID;
$batchQueryRequest['token']      = $token;

$response    = self::do_post_request(self::FNAC_URL . "batch_query", $batchQueryRequest->asXML());
$xmlResponse = simplexml_load_string(trim($response), NULL, LIBXML_NOCDATA);

    return $xmlResponse;
}



public static function batchStatus(string $batch_id){
    $token = self::getToken();

    //Preparamos el xml
$batch_status_xml = <<<XML
<?xml version="1.0" encoding="utf-8"?>
<batch_status xmlns="http://www.fnac.com/schemas/mp-dialog.xsd" shop_id="" partner_id="" token="">
</batch_status>
XML;

$batchStatusRequest               = simplexml_load_string($batch_status_xml);

$batchStatusRequest['partner_id'] = self::FNAC_PARTNER_ID;
$batchStatusRequest['shop_id']    = self::FNAC_SHOP_ID;
$batchStatusRequest['token']      = $token;

$batchStatusRequest->addChild('batch_id',$batch_id);


$response    = self::do_post_request(self::FNAC_URL . "batch_status", $batchStatusRequest->asXML());
$xmlResponse = simplexml_load_string(trim($response), NULL, LIBXML_NOCDATA);

    return $xmlResponse;
}

}


 ?>

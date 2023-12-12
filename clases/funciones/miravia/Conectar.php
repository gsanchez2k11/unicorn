<?php
namespace unicorn\clases\funciones\miravia;
error_reporting(E_ALL);                                     //Activamos el reporte de errores
ini_set('display_errors', 'on');
require_once(RAIZ . '/vendor/autoload.php');
use unicorn\clases\funciones\unicorn_db\Config as config;
//require_once __DIR__ . '/../../config.php.inc';
require RAIZ . '/clases/funciones/unicorn_db/Config.php';


class Conectar {
//const MIRAVIA_APP_KEY = '502170';
//const MIRAVIA_APP_SECRET = '7cfvkHiL67jFBzuLXtyb7T15hEaS3Nf2';

protected static function conectar() {
    $appkey = config::dameValorConfig('miravia_app_key');
    $appsecret = config::dameValorConfig('miravia_app_secret');
    include "../../vendor/iop-sdk-php/IopSdk.php";
    $c = new \IopClient(\UrlConstants::$api_authorization_url, $appkey, $appsecret);
    return $c;
    }


public static function dameToken(){
    $cliente = self::conectar();
    $request = new \IopRequest('/auth/token/create');
    $request->addApiParam('code','0_2DL4DV3jcU1UOT7WGI1A4rY91');
$request->addApiParam('uuid','38284839234');
    //$request = new \IopRequest('/product/sellable_quantity/adjust');
   // $request->addApiParam('payload','{"Request": { "Product": {"Skus": {"Sku":[{"ItemId":"234234234","SkuId":"234","SellerSku":"Apple-SG-Glod-64G","SellableQuantity":"20"},{"ItemId":"234234234","SkuId":"234","SellerSku":"Apple-SG-Glod-64G","MultiWarehouseInventories": {"MultiWarehouseInventory":[{"WarehouseCode":"warehouseTest1","SellableQuantity":"20"},{"WarehouseCode": "warehouseTest2","SellableQuantity": "30"}]}}]}}}}');
   // $accessToken = '500001000153fWPwTpfcyuExBkD1676df9brxqtca6JXghsfRwEdEephkRoV8Vr';
    $salida = $cliente->execute($request);
    var_dump($salida);
}

}
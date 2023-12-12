<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . '/../../config.php.inc';
require_once(RAIZ . '/vendor/autoload.php');


use unicorn\clases\funciones\magento\StockItem as stock;
require_once RAIZ . '/clases/funciones/magento/StockItem.php';
use unicorn\clases\funciones\magento\Articulos as articulos;
require_once RAIZ . '/clases/funciones/magento/Articulos.php';
use unicorn\clases\funciones\magento\Conectar as conectar;
require_once RAIZ . '/clases/funciones/magento/Conectar.php';


//$stock = stock::getLowStockItems();
/*$stock = stock::getStockItem('CP04OSSECC62');

echo "<pre>";
print_r($stock);
echo "</pre>";*/
$stockItemId = 7843;
$token = conectar::getToken();
//$ch    = curl_init($token['url'] . 'index.php/rest/async/bulk/V1/products/bySku/stockItems/byEntryId');
$ch    = curl_init($token['url'] . 'index.php/rest/async/bulk/V1/products/CP04OSSECC62/stockItems/byEntryId');
$headers = array(
    'Content-Type: application/json',
    'Authorization: Bearer ' . $token['token'],
);
$data = array(
    'stock_item' => array(
        'item_id' => $stockItemId,
        'qty' => 64,
        'is_in_stock' => 1

    )/*,
    'stock_item' => array(
        'item_id' => 8010,
        'qty' => 64,
        'is_in_stock' => 1

    )*/
);
$data = json_encode($data);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$result = curl_exec($ch);

$result = json_decode($result);
echo "<pre>";
print_r($result);
echo "</pre>";
 ?>

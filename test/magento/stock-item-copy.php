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



$stock = stock::putStockItem('CP04OSSECC62');

echo "<pre>";
print_r($stock);
echo "</pre>";

 ?>

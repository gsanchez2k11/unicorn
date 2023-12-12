<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . '/../../config.php.inc';
require_once(RAIZ . '/vendor/autoload.php');


use unicorn\clases\funciones\magento\Inventory as inventory;
require_once RAIZ . '/clases/funciones/magento/Inventory.php';

$id_tienda = 1;
$fuente = 'inforpor';
//Pedimos las fuentes de stock
$stock = inventory::getSourceItems($fuente);
echo '<pre>';
print_r($stock);
echo '</pre>';
foreach ($stock as $key => $value) {
    $stock[$key]->quantity = 69;
}
$actualizaar = inventory::postSourceItems($stock);
echo '<pre>';
print_r($actualizaar);
echo '</pre>';




?>

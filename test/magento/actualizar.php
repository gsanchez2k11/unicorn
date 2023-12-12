<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . '/../../config.php.inc';
require_once(RAIZ . '/vendor/autoload.php');
$tiempo_inicial = microtime(true); //true es para que sea calculado en segundos

use unicorn\clases\funciones\magento\Articulos as articulos;
require_once RAIZ . '/clases/funciones/magento/Articulos.php';
use unicorn\clases\funciones\inforpor\Tarifa as tarifa_inforpor;
require_once RAIZ . '/clases/funciones/inforpor/Tarifa.php';
use unicorn\clases\funciones\magento\StockItem as stock_item;
require_once RAIZ . '/clases/funciones/magento/StockItem.php';

$todos_articulos = articulos::getAllProducts(1); //Pedimos el catálogo completo de magento
//Filtramos para quitar los plotters de Epson
$todos_articulos = array_filter($todos_articulos, function ($art){
//return $art->attribute_set_id == 39 && reset($art->custom_attributes)['value'] == 3;
if ($art->attribute_set_id == 39 && reset($art->custom_attributes)->value == 3) {
return false;
}
return $art;
});
echo '<pre>';
print_r($todos_articulos);
echo '</pre>';




$tiempo_final = microtime(true);
 $tiempo = $tiempo_final - $tiempo_inicial; //este resultado estará en segundos
 echo "El tiempo de ejecución del archivo ha sido de " . $tiempo . " segundos";
 ?>

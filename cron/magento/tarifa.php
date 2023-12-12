<?php
/**
 * Generamos una tarifa con todos los artículos
 */
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . '/../../config.php.inc';
require_once(RAIZ . '/vendor/autoload.php');
use unicorn\clases\funciones\magento\Articulos as articulosMage;
require RAIZ . '/clases/funciones/magento/Articulos.php';
use unicorn\clases\funciones\magento\StockItem as stock_item;
require RAIZ . '/clases/funciones/magento/StockItem.php';

//Ahora la de magento 2.2

$tiendas = [1,2];


 foreach ($tiendas as $id_t) {
  $articulos = array();
  $pagina = 1;
  $i = 0; //contador para pruebas
  do {
    $parametros = array (
      'pagina' => $pagina
    );
    $arts_mage = articulosMage::getListaPaginaArticulos($parametros,$id_t);
    foreach ($arts_mage as $articulo) {
     // $articulo = array_map('trim', $articulo);
     $articulo->item_id = stock_item::getStockItem($articulo->sku)->item_id;
      $articulos[] = $articulo;
    }
    $pagina++;
    $i++;
  } while (count($arts_mage) === 100 /*|| $i <= 10*/);
  

if (!empty($articulos)) {
  $variable = json_encode($articulos);
  /*echo $variable;





  $json_tarifa = json_encode($articulos);*/


  $fp = fopen(RAIZ . '/var/import/magento' . $id_t . '.json', 'w');
fwrite($fp, $variable);
fclose($fp);
}
 }
  



?>
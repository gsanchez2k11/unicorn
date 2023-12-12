<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . '/../../config.php.inc';
require_once(RAIZ . '/vendor/autoload.php');
$tiempo_inicial = microtime(true); //true es para que sea calculado en segundos


use unicorn\clases\funciones\magento\Articulos as articulosMage;
require RAIZ . '/clases/funciones/magento/Articulos.php';
use unicorn\clases\funciones\magento\StockItem as stock_item;
require RAIZ . '/clases/funciones/magento/StockItem.php';
//Primero hacemos la tarifa del 2.4.6
$articulosm246 = array();
$tienda = 2;
$pagina = 1;
do {
  $params = array (
    'pagina' => $pagina,
    'tienda' => $tienda
  );
  
  $listado = articulosMage::listarArticulos($params);

  foreach ($listado->items as $articulo) {
    $articulosm246[] = $articulo;
  }
  $pagina++;
} while (count($listado->items) === 1000);



echo "<pre>";
print_r($articulosm246);
echo "</pre>";

  $tiempo_final = microtime(true);
 $tiempo = $tiempo_final - $tiempo_inicial; //este resultado estará en segundos
 echo "El tiempo de ejecución del archivo ha sido de " . $tiempo . " segundos";
 ?>

<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
$tiempo_inicial = microtime(true);
require_once __DIR__ . '/../../config.php.inc';
use unicorn\clases\funciones\inforpor\Stock as stock;
require RAIZ . '/clases/funciones/inforpor/Stock.php';
?>
<?php

//$articulo= '370560'; //Con promo
//$articulo= '37056'; //Con varias promos
//$articulo= '48072'; //Con promo
//$articulo= '51062'; //Con promo
$articulo = '35981';
//SO01718
//$estado_pedido = pedido::BuscaArticuloEnPedidoObj('49337',$pedido);
$estado = stock::StockPr($articulo);

echo "<pre>";
print_r($estado);
echo "</pre>";

$tiempo_final = microtime(true);
$tiempo = $tiempo_final - $tiempo_inicial; //este resultado estará en segundos
echo "El tiempo en utilizar el método stockPr ha sido  " . $tiempo . " segundos";

 ?>

<?php
error_reporting(E_ALL);                                     //Activamos el reporte de errores
ini_set('display_errors', 'on');
require_once __DIR__ . '/../../config.php.inc';
require_once __DIR__ . '/../../clases/funciones/inforpor/Stock.php';
use unicorn\clases\funciones\inforpor\Stock as stock;
$codinfo = $_POST['codinfo'];
//Buscamos el articulo de manera normal
$info_articulo = stock::StockPr($codinfo);
//Buscamos las custodias para el artículo
$custodias = stock::SiCustodiaObj($codinfo);
//echo "<pre>";
//print_r($info_articulo);
//echo "</pre>";
//Tenemos un array multdimensional
if (!empty($custodias)) {


foreach ($custodias as $custodia) {
//Definimos el precio
/*$lpi = isset($info_articulo['lpi']) && $info_articulo['lpi'] > 0 ? $info_articulo['lpi'] : 0;
$precio_sin_iva = $custodia->getPrecio() + $lpi;
$precio_total = $precio_sin_iva * 1.21;*/

  $arr_custodia = array(
    'quedan' => $custodia->getQuedan(),
    'pedido' => $custodia->getPedido(),
    'custodia' => $custodia->getIdCustodia(),
    'precio' => $custodia->getPrecio(),
    'precio_total' => (double)$custodia->getPrecioTotal(),
  //  'lpi'         => $custodia->getLpi()
  );
  $arr_custodias[] = $arr_custodia;
}
 $json_articulos = json_encode($arr_custodias);
} else {
  $arr_custodias = json_encode('');
}
 ?>
 <?php

 echo $json_articulos;
  ?>

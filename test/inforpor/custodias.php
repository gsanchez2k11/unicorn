<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . '/../../config.php.inc';
use unicorn\clases\funciones\inforpor\Stock as stock;
use unicorn\clases\funciones\inforpor\Pedido as pedido;
require_once RAIZ . '/clases/funciones/inforpor/Stock.php';
require_once RAIZ . '/clases/funciones/inforpor/Pedido.php';

?>
<?php

$estado_pedido = stock::SiCustodiaObjDev('34185');
//$custodia_obj = stock::SiCustodiaObj($codinf);
//$custodia = stock::SiCustodia($codinf);
echo "<pre>";
print_r($estado_pedido);
echo "</pre>";
/*$stock_custodias = 0;
foreach ($estado_pedido as $custodia) {
  $stock_custodias += $custodia->getQuedan();
}*/
/*echo "<pre>";
print_r($stock_custodias);
print_r($estado_pedido);
echo "</pre>";*/
 ?>

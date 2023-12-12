<?php
error_reporting(E_ALL);                                     //Activamos el reporte de errores
ini_set('display_errors', 'on');
require_once __DIR__ . '/../../config.php.inc';
require_once(RAIZ . '/vendor/autoload.php');
require_once RAIZ . '/clases/funciones/inforpor/Pedido.php';

use unicorn\clases\funciones\inforpor\Pedido as pedido;

$pedido = $_POST;
//echo "<pre>";
//print_r($pedido);
//echo "</pre>";
$hacer_pedido = pedido::hacerPedido($pedido);
//echo "<pre>";
//print_r($hacer_pedido);
//echo "</pre>";

/*$pedidos = pedidos::dameUltimosPedidos();*/

//$json_pedidos = json_encode($buscar_pedido['EstadoPedidoResult']);
if (!empty($hacer_pedido)) {
  $json_pedidos = json_encode($hacer_pedido,JSON_HEX_QUOT | JSON_PARTIAL_OUTPUT_ON_ERROR);
  echo $json_pedidos;
} else {
  echo json_encode('');
}



/*echo "<pre>";
print_r($pedidos);
echo "</pre>";*/

?>

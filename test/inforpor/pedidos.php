<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . '/../../config.php.inc';
use unicorn\clases\funciones\inforpor\Pedido as pedido;
require RAIZ . '/clases/funciones/inforpor/Pedido.php';
?>
<?php
//49337
//$pedido["NumPedCli"] = 'PC1415505';
$pedido["NumPedInf"] = 'PV-23-124506';
//SO01718
//$estado_pedido = pedido::BuscaArticuloEnPedidoObj('49337',$pedido);
$estado = pedido::EstadoPedidoObj($pedido);

echo "<pre>";
print_r($estado);
echo "</pre>";

foreach ($estado->getLineasPedR() as $linea) {
  $info = $linea->esCustodia();
  echo "<pre>";
  print_r($info);
  echo "</pre>";
}

 ?>

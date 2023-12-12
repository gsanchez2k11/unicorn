<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . '/../../config.php.inc';
use unicorn\clases\funciones\mirakl\Pedidos as pedidos;
require RAIZ . '/clases/funciones/mirakl/Pedidos.php';
?>
<?php
$pedido = pedidos::dameDetallesPedido('1633189-A');
//$lineas = $pedido[0]->getLineasPedido();
//$linea = $lineas[0]->getAtributosBd();
echo "<pre>";

//print_r($linea);
print_r($pedido);
echo "</pre>";
 ?>

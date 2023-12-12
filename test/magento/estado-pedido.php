<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . '/../../config.php.inc';
require_once(RAIZ . '/vendor/autoload.php');


use unicorn\clases\funciones\magento\Pedidos as pedidos;
require RAIZ . '/clases/funciones/magento/Pedidos.php';


//$articulos = articulos::buscarArticulo('default_code','64V100BBF1520050');    //Referencia interna
$row = array(
  'id' => '14703',
  'comment' => 'vamos de pruebas 3',
  'is_customer_notified' => 1,
  'is_visible_on_front' => 1,
  'status' => 'processing'
);
$peticion_pedido = pedidos::updateStatusPedido($row);

echo '<pre>';
print_r($peticion_pedido);
echo '</pre>';
 ?>

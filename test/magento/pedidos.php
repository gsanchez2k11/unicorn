<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . '/../../config.php.inc';
require_once(RAIZ . '/vendor/autoload.php');


use unicorn\clases\funciones\magento\Pedidos as pedidos;
require RAIZ . '/clases/funciones/magento/Pedidos.php';


//$articulos = articulos::buscarArticulo('default_code','64V100BBF1520050');    //Referencia interna
$peticion_pedidos = pedidos::getPedidos(2);
echo "<pre>";
print_r($peticion_pedidos);
echo "</pre>";
$pedidos = $peticion_pedidos->items;
foreach ($pedidos as $pedido) {
  echo "<pre>";
  print_r($pedido);
  echo "</pre>";
}



 ?>

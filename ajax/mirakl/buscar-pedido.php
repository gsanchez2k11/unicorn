<?php
error_reporting(E_ALL);                                     //Activamos el reporte de errores
ini_set('display_errors', 'on');
require_once __DIR__ . '/../../config.php.inc';
require_once(RAIZ . '/vendor/autoload.php');
require_once RAIZ . '/clases/funciones/mirakl/Pedidos.php';

use unicorn\clases\funciones\mirakl\Pedidos as pedidos;
$id = $_POST['id'];
$pedidos = pedidos::dameDetallesPedido($id);


/*$pedidos = pedidos::dameUltimosPedidos();*/

$json_pedidos = json_encode($pedidos);
echo $json_pedidos;


/*echo "<pre>";
print_r($pedidos);
echo "</pre>";*/
?>

<?php
error_reporting(E_ALL);                                     //Activamos el reporte de errores
ini_set('display_errors', 'on');
require_once __DIR__ . '/../../config.php.inc';

require_once RAIZ . '/clases/funciones/magento/Pedidos.php';
use unicorn\clases\funciones\magento\Pedidos as pedidos;

$ultimos_pedidos = pedidos::dameUltimosPedidosObj();
//$items = $ultimos_pedidos->items;
/*echo "<pre>";
print_r($ultimos_pedidos);
echo "</pre>";*/


$variable = json_encode($ultimos_pedidos);
echo $variable;
 ?>

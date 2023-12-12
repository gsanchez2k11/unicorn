<?php
error_reporting(E_ALL);                                     //Activamos el reporte de errores
ini_set('display_errors', 'on');
require_once __DIR__ . '/../../config.php.inc';
require_once(RAIZ . '/vendor/autoload.php');
require_once RAIZ . '/clases/funciones/mirakl/Pedidos.php';

use unicorn\clases\funciones\mirakl\Pedidos as pedidos;
$plataforma = isset($_POST['plataforma']) ? $_POST['plataforma'] :  'pcc';
$pedidos = pedidos::dameUltimosPedidos($plataforma);

$json_pedidos = json_encode($pedidos);
echo $json_pedidos;


/*echo "<pre>";
print_r($pedidos);
echo "</pre>";*/
?>

<?php
error_reporting(E_ALL);                                     //Activamos el reporte de errores
ini_set('display_errors', 'on');
require_once __DIR__ . '/../../config.php.inc';
require_once(RAIZ . '/vendor/autoload.php');
require_once RAIZ . '/clases/funciones/inforpor/Veralbaran.php';

use unicorn\clases\funciones\inforpor\Veralbaran as albaran;

$buscar_albaran = albaran::verAlbaran($_POST['id']);
/*echo "<pre>";
print_r($buscar_albaran);
echo "</pre>";*/

/*$pedidos = pedidos::dameUltimosPedidos();*/

//$json_pedidos = json_encode($buscar_pedido['EstadoPedidoResult']);
if (!empty($buscar_albaran)) {
  $json_pedidos = json_encode($buscar_albaran,JSON_HEX_QUOT | JSON_PARTIAL_OUTPUT_ON_ERROR);
  echo $json_pedidos;
} else {
  echo json_encode('');
}



//echo "<pre>";
//print_r($json_pedidos);
//echo "</pre>";

?>

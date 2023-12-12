<?php
error_reporting(E_ALL);                                     //Activamos el reporte de errores
ini_set('display_errors', 'on');
require_once __DIR__ . '/../../config.php.inc';

require_once RAIZ . '/clases/funciones/magento/Articulos.php';
use unicorn\clases\funciones\magento\Articulos as articulos;

//$ultimos_pedidos = pedidos::dameUltimosPedidosObj();
//$items = $ultimos_pedidos->items;


$eliminar = articulos::eliminarArticulo($_POST);



$variable = json_encode($eliminar);
echo $variable;
 ?>

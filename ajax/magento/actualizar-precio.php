<?php
error_reporting(E_ALL);                                     //Activamos el reporte de errores
ini_set('display_errors', 'on');
require_once __DIR__ . '/../../config.php.inc';

require_once RAIZ . '/clases/funciones/magento/Articulos.php';
use unicorn\clases\funciones\magento\Articulos as articulos;

//$ultimos_pedidos = pedidos::dameUltimosPedidosObj();
//$items = $ultimos_pedidos->items;
$sku = $_POST['mage']['sku'];
$price = $_POST['precio_venta'];
$atributos['price'] = $price;


$actualizar = articulos::actualizarArticulo($sku,$atributos);

if (is_object($actualizar) && $actualizar->id > 1) {
  echo 'ok';
} else {
  echo 'ko';
}

/*echo "<pre>";
print_r($actualizar);
echo "</pre>";*/


//$variable = json_encode($ultimos_pedidos);
//echo $variable;
 ?>

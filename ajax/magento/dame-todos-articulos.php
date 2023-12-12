<?php
error_reporting(E_ALL);                                     //Activamos el reporte de errores
ini_set('display_errors', 'on');
require_once __DIR__ . '/../../config.php.inc';

use unicorn\clases\funciones\magento\Articulos as articulosMage;
require_once RAIZ . '/clases/funciones/magento/Articulos.php';

$pagina = $_POST['p'];
$status = $_POST['status'];

$parametros = array (
  'pagina' => $pagina,
  'status' => $status
);

$arts_mage = articulosMage::getListaPaginaArticulos($parametros);
//$items = $ultimos_pedidos->items;
/*echo "<pre>";
print_r($ultimos_pedidos);
echo "</pre>";*/


$variable = json_encode($arts_mage);
echo $variable;
 ?>

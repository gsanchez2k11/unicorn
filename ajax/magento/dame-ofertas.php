<?php
error_reporting(E_ALL);                                     //Activamos el reporte de errores
ini_set('display_errors', 'on');
require_once __DIR__ . '/../../config.php.inc';

use unicorn\clases\funciones\magento\Articulos as articulosMage;
require_once RAIZ . '/clases/funciones/magento/Articulos.php';
//echo "<pre>";
//print_r($_POST);
//echo "</pre>";
$fin_oferta = $_POST['finOferta'];
$operador = $_POST['operador'];
$campo = $_POST['campo'];
$id_tienda = $_POST['idTienda'];

$parametros = array (
  'campo' => $campo,
  'fin_oferta' => $fin_oferta,
  'operador' => $operador,
  'id_tienda' => $id_tienda
);

$arts_mage = articulosMage::listarOfertas($parametros);
//$items = $ultimos_pedidos->items;
/*echo "<pre>";
print_r($ultimos_pedidos);
echo "</pre>";*/


$variable = json_encode($arts_mage);
echo $variable;
 ?>

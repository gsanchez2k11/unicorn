<?php
error_reporting(E_ALL);                                     //Activamos el reporte de errores
ini_set('display_errors', 'on');
require_once __DIR__ . '/../../config.php.inc';

use unicorn\clases\funciones\magento\Articulos as articulosMage;
require_once RAIZ . '/clases/funciones/magento/Articulos.php';

$arr = array();

$arr['mpn'] = $_POST['mpn'];
$tienda = $_POST['id_tienda'] ?? 1; //Si tenemos la id de la tienda la pasamos, si no utilizamos 1 por defecto

$arts_mage = articulosMage::getinfoArticuloReferencia($arr, $tienda);



$variable = json_encode($arts_mage);
echo $variable;
 ?>

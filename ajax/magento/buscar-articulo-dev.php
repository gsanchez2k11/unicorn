<?php
error_reporting(E_ALL);                                     //Activamos el reporte de errores
ini_set('display_errors', 'on');
require_once __DIR__ . '/../../config.php.inc';

use unicorn\clases\funciones\magento\Articulos as articulosMage;
require_once RAIZ . '/clases/funciones/magento/Articulos.php';

$arr = array();

if (isset($_POST['mpn'])) $arr['mpn'] = $_POST['mpn'];
if (isset($_POST['bnombre'])) $arr['bnombre'] = $_POST['bnombre'];
if (isset($_POST['ean'])) $arr['ean'] = $_POST['ean'];
if (isset($_POST['tipoarticulo']) && $_POST['tipoarticulo'] != '-') $arr['tipoarticulo'] = $_POST['tipoarticulo'];
if (isset($_POST['bmarca']) && $_POST['bmarca'] != '-') $arr['bmarca'] = $_POST['bmarca'];
if (isset($_POST['ap']) && $_POST['ap'] != '-') $arr['ap'] = $_POST['ap'];
if (isset($_POST['p']) && $_POST['p'] != '-') $arr['p'] = $_POST['p'];


//$arr['mpn'] = '64LF120GL0107050';
$arr['mpn'] = 'PAPCAD805011';
$arts_mage = articulosMage::buscarArticulos($arr);



$variable = json_encode($arts_mage);
echo $variable;
 ?>

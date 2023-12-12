<?php
error_reporting(E_ALL);                                     //Activamos el reporte de errores
ini_set('display_errors', 'on');
require_once __DIR__ . '/../../config.php.inc';
require_once(RAIZ . '/vendor/autoload.php');
use unicorn\clases\funciones\inforpor\Stock as compraInforpor;
require_once RAIZ . '/clases/funciones/inforpor/Stock.php';
 ?>
<?php
/*$datos = array(
 'mpn' => 'MFCL2710DWZX1'
);*/
$datos = $_POST;
//echo '<pre>';
//print_r($datos);
//echo '</pre>';
$compra = compraInforpor::ObtenerCompraInforpor($datos);

echo json_encode($compra,JSON_HEX_QUOT | JSON_PARTIAL_OUTPUT_ON_ERROR);

 ?>

<?php
error_reporting(E_ALL);                                     //Activamos el reporte de errores
ini_set('display_errors', 'on');
require_once __DIR__ . '/../../config.php.inc';
require_once(RAIZ . '/vendor/autoload.php');
use unicorn\clases\funciones\fnac\Ofertas as ofertas;
require RAIZ . '/clases/funciones/fnac/Ofertas.php';
$articulo = $_POST;
$ean = $articulo['EAN'];
$mpn = $articulo['Referencia'];
$stock = $articulo['Stock'] <= 9999 ? $articulo['Stock'] : 9999 ;               //El campo stock tiene un valor máximo de 9999 para la fnac
$precio = $articulo['Precio'];
$lpi = $articulo['lpi'];

$precio_con_margen = $precio * 1.20; //Añadimos un margen del 20%
$precio_con_lpi = $precio_con_margen + $lpi; //Sumamos el canon
$precio_con_portes = $precio <= 30 ? $precio_con_lpi + 3.20 : $precio_con_lpi; //Sumamos los portes si procede
$precio_total = $precio_con_portes * 1.21;
//Construimos el array
$articulos[] = array(
  'ean' => $ean,
  'mpn' => $mpn,
  'stock_final' => $stock,
  'precio' => $precio_total
);
$add = ofertas::actualizaOfertas($articulos);

//$actualizar = ofertas::actualizaOfertas($ofertas);
//$resultado['fnac'] = strtolower((string) $actualizar->attributes()->status);
//$actualizar_string = simplexml_load_string($actualizar);
$json_resultado = json_encode($add);
echo $json_resultado;

/*echo '<pre>';
print_r($articulo);
echo '</pre>';*/

?>

<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . '/../../config.php.inc';
require_once(RAIZ . '/vendor/autoload.php');


use unicorn\clases\funciones\magento\Articulos as articulos;
require RAIZ . '/clases/funciones/magento/Articulos.php';


//$articulos = articulos::buscarArticulo('default_code','64V100BBF1520050');    //Referencia interna

$arr_articulo = array(
  'mpn' => urlencode('B11B198032'),
);
/*$arr_articulo = array(
  'mpn' => urlencode('64LF120GL0107050'),
);*/

//$busca_articulos = articulos::getinfoArticuloReferencia($arr_articulo,2);
$busca_articulos = articulos::buscarArticulos($arr_articulo);
echo "<pre>";
print_r($busca_articulos);
echo "</pre>";
 ?>

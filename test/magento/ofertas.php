<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . '/../../config.php.inc';
require_once(RAIZ . '/vendor/autoload.php');


use unicorn\clases\funciones\magento\Articulos as articulos;
require RAIZ . '/clases/funciones/magento/Articulos.php';


//$articulos = articulos::buscarArticulo('default_code','64V100BBF1520050');    //Referencia interna
$arr_articulo = array(
  'fin_oferta' => '2021-08-03',
  'operador' => 'lt'

);

$busca_articulos = articulos::listarOfertas($arr_articulo);

echo "<pre>";
print_r($busca_articulos);
echo "</pre>";
 ?>

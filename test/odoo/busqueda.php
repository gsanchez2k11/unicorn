<?php
error_reporting(E_ALL);                                     //Activamos el reporte de errores
ini_set('display_errors', 'on');
require_once __DIR__ . '/../../config.php.inc';
require_once(RAIZ . '/vendor/autoload.php');
require_once RAIZ . '/clases/funciones/odoo/Conectar.php';
use unicorn\clases\funciones\odoo\Conectar as conectar;
$arr = array(
   'modelo' => 'product.pricelist.item',
   'campo' => 'categ_id',
   'valor' => 'All / Consumibles / Ecosolvente / Serie T713',
   'offset' => 0
);

/*$arr = array(
   'modelo' => 'product.pricelist.item',
   'campo' => 'id',
   'valor' => 1980,
   'offset' => 0
);*/ 
$datos = $arr;
$modelo = $datos['modelo'];
$campo = $datos['campo'];
$valor = $datos['valor'];
$offset = $datos['offset'];
//print_r($datos);
$articulos = array();
//do {
$resultado = conectar::busqueda($campo,$valor,$modelo,$offset);
/*foreach ($resultado as $articulo) {
$articulos[] = $articulo;
}
$offset += 10;
} while (count($resultado) == 10);*/

//$offset = isset($datos['offset']) ? $datos['offset'] : 0 ;
//$arr['list_price'] = floatval($datos['arr']['list_price']);

//$busqueda = conectar::busqueda($campo,$valor,$modelo,$offset);

//$json_resultado = json_encode($resultado);
//echo $json_resultado;


echo '<pre>';
print_r($resultado);
echo '</pre>';

 ?>

 <?php
//var_dump($arr);
  ?>

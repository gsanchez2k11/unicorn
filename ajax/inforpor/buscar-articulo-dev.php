<?php
error_reporting(E_ALL);                                     //Activamos el reporte de errores
ini_set('display_errors', 'on');
require_once __DIR__ . '/../../config.php.inc';
require_once(RAIZ . '/vendor/autoload.php');
require_once RAIZ . '/clases/funciones/inforpor/Stock.php';
use unicorn\clases\funciones\inforpor\Stock as stock;
require_once RAIZ . '/clases/funciones/inforpor/Codigos.php';
use unicorn\clases\funciones\inforpor\Codigos as codigo;
/**
 * Vamos a modificar esto para aumentar la compatibilidad
 * La idea es comprobar si es un codigo de inforpor o un EAN, todo lo demás vamos a considerar que es un mpn
 *
 */



//$codinfo = $_POST['codinfo'];

$referencia = $_POST['codinfo'];
//$referencia = '4977766768870';
switch ($referencia) {
   case preg_match('/^\d{4,5}$/', $referencia) ? true : false: //Si es un código de inforpor directamente hacemos la búsqueda
  $codinfo = $referencia;
    break;
case preg_match('/^\d{6,13}$/', $referencia) ? true : false: //Si es un EAN tenemos que pedir primero el código de inforpor
$row['ean'] = $referencia;
$codinfo = codigo::DameCodigosInforpor($row);
  break;
  default:
  $row['mpn'] = $referencia;
  $codinfo = codigo::DameCodigosInforpor($row);
    break;
}

echo $codinfo;
$buscar_articulo = stock::StockPr($codinfo);


/*$pedidos = pedidos::dameUltimosPedidos();*/

//$json_pedidos = json_encode($buscar_pedido['EstadoPedidoResult']);
if (!empty($buscar_articulo)) {
  $json_articulo = json_encode($buscar_articulo,JSON_HEX_QUOT | JSON_PARTIAL_OUTPUT_ON_ERROR);
  echo $json_articulo;
} else {
  echo json_encode('');
}



/*echo "<pre>";
print_r($pedidos);
echo "</pre>";*/

?>

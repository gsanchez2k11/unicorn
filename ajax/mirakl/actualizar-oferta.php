<?php
error_reporting(E_ALL);                                     //Activamos el reporte de errores
ini_set('display_errors', 'on');
require_once __DIR__ . '/../../config.php.inc';
require_once(RAIZ . '/vendor/autoload.php');
use unicorn\clases\funciones\mirakl\Ofertas as ofertas;
require RAIZ . '/clases/funciones/mirakl/Ofertas.php';

//echo '<pre>';
//print_r($_POST);
//echo '</pre>';

$articulos[] = $_POST['datos'];
$plataforma = $_POST['plataforma'];
$resultado = array();
$actualizar_oferta = ofertas::actualizaOferta($articulos,$plataforma);                             //Lanzamos la actualización de pc componentes
if (isset($actualizar_oferta->getData()['import_id'])) {
  $resultado[$plataforma] = 'ok';
}



/*foreach ($articulos as $key => $articulo) {
$articulos[$key]['logistic_class'] = 'PA_MIDDLE';                               //Ajustamos la clase logistica para ajustarla a phone house
$articulos[$key]['leadtime_to_ship'] = 2;                               //Ajustamos la clase logistica para ajustarla a phone house
}
$actualizar_phh = ofertas::actualizaOferta($articulos,'phh');
if (isset($actualizar_phh->getData()['import_id'])) {
  $resultado['phh'] = 'ok';
}*/

$json_resultado = json_encode($resultado);
echo $json_resultado;
 ?>

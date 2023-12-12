<?php
error_reporting(E_ALL);                                     //Activamos el reporte de errores
ini_set('display_errors', 'on');
require_once __DIR__ . '/../../config.php.inc';
require_once(RAIZ . '/vendor/autoload.php');
require_once RAIZ . '/clases/funciones/inforpor/Consultaprod.php';

use unicorn\clases\funciones\inforpor\Consultaprod as cprod;
$codinfo = $_POST['codinfo'];
//$codinfo = '18011';


$buscar_articulo = cprod::consultaProd($codinfo);

/*echo "<pre>";
print_r($buscar_articulo);
echo "</pre>";*/



  $json_articulo = json_encode($buscar_articulo,JSON_HEX_QUOT | JSON_PARTIAL_OUTPUT_ON_ERROR);
  echo $json_articulo;





?>

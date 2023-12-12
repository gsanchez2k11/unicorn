<?php
error_reporting(E_ALL);                                     //Activamos el reporte de errores
ini_set('display_errors', 'on');
require_once __DIR__ . '/../../config.php.inc';

use unicorn\clases\funciones\unicorn_db\Entidad as entidad;
require_once __DIR__ . '/../../clases/funciones/unicorn_db/Entidad.php';
?>
<?php
$referencia = $_POST['referencia'];
$tipo =  $_POST['tipoRef'];
/*$referencia = 'C4810A';
$tipo = 'mpn';*/

switch ($tipo) {
  case 'mpn':
$atributo = '2';
    break;
    case 'ean':
  $atributo = '3';
      break;

  default:
$atributo = '2';
    break;
}
$entidad_atributo = array (
  'valor' => $referencia,
  'cod_atributo' => $atributo,
  'cod_atributo_buscado' => '7'
);
$resultado = entidad::getAtributoporAtributo($entidad_atributo);
//echo "resultado<pre>";
//print_r($resultado);
//echo "</pre>";
//Ojo que devuelve un array con los posibles códigos
echo json_encode($resultado);
 ?>

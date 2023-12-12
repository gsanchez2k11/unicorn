<?php
error_reporting(E_ALL);                                     //Activamos el reporte de errores
ini_set('display_errors', 'on');
require_once __DIR__ . '/../../config.php.inc';

use unicorn\clases\funciones\unicorn_db\Entidad as entidad;
require_once __DIR__ . '/../../clases/funciones/unicorn_db/Entidad.php';
?>
<?php
$datos = $_POST;
foreach ($datos as $key => $value) {
$tipo_ref = $key;
$valor = $value;
}


//Añadimos la entidad con la referencia que tengamos
$insertar = entidad::insertaArticuloEntidad($valor);

$entidad = $insertar['id']; //Capturamos la id de la entidad que acabamos de crear

switch ($tipo_ref) {
  case 'ean':
  $atributo = '3';
$add = entidad::insertaArticuloEntidadVarchar($entidad,$atributo,$valor);
    break;
}

if (get_class($add) == 'PDOStatement') {
  $respuesta = $entidad;


} else {
    $respuesta = 'ko';
}
$variable = json_encode($respuesta);
echo $variable;
//print_r($insertar);
 ?>

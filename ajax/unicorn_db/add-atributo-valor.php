<?php
error_reporting(E_ALL);                                     //Activamos el reporte de errores
ini_set('display_errors', 'on');
require_once __DIR__ . '/../../config.php.inc';

use unicorn\clases\funciones\unicorn_db\Entidad as entidad;
require_once __DIR__ . '/../../clases/funciones/unicorn_db/Entidad.php';
?>
<?php
$datos = $_POST;
if (!isset($datos['entidad'])) {//Si no tenemos la entidad la buscamos
  $entidad = entidad::gestionaEntidad($datos);
} else {
  $entidad = $datos['entidad'];
}
foreach ($datos as $atributo => $valor) {
  $cod_atributo = entidad::dameIdAtributo($atributo);
  $tabla = entidad::dameTablaAtributos($cod_atributo);
  $add = entidad::insertaArticuloEntidadInt($entidad,$cod_atributo,$valor, $tabla);
}
//Si llegamos bien a este punto damos el ok
echo json_encode('ok');


//echo '<pre>';
//print_r(get_defined_vars());
//echo '</pre>';
/*
$entidad = $_POST['entidad'];
$atributo = $_POST['atributo'];
$valor = $_POST['valor'];
$tabla = isset($_POST['tabla']) ? $_POST['tabla'] : 'articulos_entidad_int';
$add = entidad::insertaArticuloEntidadInt($entidad,$atributo,$valor, $tabla);


if (get_class($add) == 'PDOStatement') {
  $respuesta = $entidad;


} else {
    $respuesta = 'ko';
}
$variable = json_encode($respuesta);
echo $variable;*/

 ?>

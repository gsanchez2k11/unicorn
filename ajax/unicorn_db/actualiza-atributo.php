<?php
error_reporting(E_ALL);                                     //Activamos el reporte de errores
ini_set('display_errors', 'on');
require_once __DIR__ . '/../../config.php.inc';
require_once __DIR__ . '/../../clases/funciones/unicorn_db/Entidad.php';
use unicorn\clases\funciones\unicorn_db\Entidad as entidad;


$entidad = $_POST['entidad'];
$atributo = $_POST['atributo'];
$plataforma = $_POST['plataforma'];
//print_r($_POST['estado']);
switch ($_POST['valor']) {
  case 'true':
$valor = 1;
    break;
    case 'false':
  $valor = 0;
      break;
default:
    $valor = $_POST['valor'];
    break;

}


if (is_numeric($atributo)) { //Si el atributo es númerico es porque estamos pasando la id
  //Como queremos permitir sólo un comentario de este tipo por cada entidad primero hacemos una búsqueda
//$busca_comentario = entidad::dameValorArticuloEntidadVarchar($entidad,'6');
//Si tenemos un objeto PDOStatement es porque no existe aún una entrada para esta entidad y podemos hacer un insert
/*if (is_object($busca_comentario) && get_class($busca_comentario) == 'PDOStatement') {
$inserta_comentario = entidad::insertaArticuloEntidadVarchar($entidad,'6',$comentario);
} else {    //Si recibimos un string es porque ya hay un comentario y lo que tenemos que hacer es actualizarlo
$inserta_comentario =   entidad::actualizaArticuloEntidadVarchar($entidad,'6',$comentario);
}*/

//$tabla = $atributo == '14' ? 'articulos_entidad_decimal' : 'articulos_entidad_int'; //Si el atributo recibido es el 14 vamos a escribir un precio y va a la tabla de decimal
switch ($atributo) {
  case '2':
  case '3':
  case '4':
  case '15':
  case '17':
$tabla = 'articulos_entidad_varchar';
    break;
  case '14':
  case '20':
$tabla = 'articulos_entidad_decimal';
    break;

  default:
$tabla = 'articulos_entidad_int';
    break;
}

} else { //Hemos decidido que es mejor pasar el nombre del atributo para poder adjuntar la plataforma
$cadena = $atributo . '_' . $plataforma;
$atributo = entidad::dameIdAtributo($cadena);
$tabla = entidad::dameTablaAtributos($atributo); //Obtenemos la tabla en la que tenemos que escribir la informacion

}




$grabar = entidad::insertaArticuloEntidadInt($entidad,$atributo,$valor,$tabla);

//print_r($busca_comentario);

/*$inserta_comentario = entidad::insertaArticuloEntidadVarchar($entidad,'6',$comentario);*/

/*if (get_class($inserta_comentario) == 'PDOStatement') {
echo 'ok';
} else {
echo  gettype($inserta_comentario);
}*/
print_r($grabar);


 ?>

<?php
error_reporting(E_ALL);                                     //Activamos el reporte de errores
ini_set('display_errors', 'on');
require_once __DIR__ . '/../../config.php.inc';
require_once __DIR__ . '/../../clases/funciones/unicorn_db/Categorias_Marketplaces.php';
use unicorn\clases\funciones\unicorn_db\Categorias_Marketplaces as catpcc;


$categoria = $_POST['categoria'];
$comision = $_POST['comision'];

$arr = array(
  'categoria' => $categoria,
  'comision' => $comision
);

$grabar = catpcc::addComisionCategoria($arr);

//Como queremos permitir sólo un comentario de este tipo por cada entidad primero hacemos una búsqueda
//$busca_comentario = entidad::dameValorArticuloEntidadVarchar($entidad,'6');
//Si tenemos un objeto PDOStatement es porque no existe aún una entrada para esta entidad y podemos hacer un insert
/*if (is_object($busca_comentario) && get_class($busca_comentario) == 'PDOStatement') {
$inserta_comentario = entidad::insertaArticuloEntidadVarchar($entidad,'6',$comentario);
} else {    //Si recibimos un string es porque ya hay un comentario y lo que tenemos que hacer es actualizarlo
$inserta_comentario =   entidad::actualizaArticuloEntidadVarchar($entidad,'6',$comentario);
}*/

//$grabar = entidad::insertaArticuloEntidadInt($entidad,'9',$estado);

//print_r($busca_comentario);

/*$inserta_comentario = entidad::insertaArticuloEntidadVarchar($entidad,'6',$comentario);*/

/*if (get_class($inserta_comentario) == 'PDOStatement') {
echo 'ok';
} else {
echo  gettype($inserta_comentario);
}*/
print_r($grabar);


 ?>

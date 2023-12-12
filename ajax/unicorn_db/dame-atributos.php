<?php
error_reporting(E_ALL);                                     //Activamos el reporte de errores
ini_set('display_errors', 'on');
require_once __DIR__ . '/../../config.php.inc';

use unicorn\clases\funciones\unicorn_db\Entidad as entidad;
require_once __DIR__ . '/../../clases/funciones/unicorn_db/Entidad.php';
?>
<?php
//print_r($_POST);
$salida = array();
$buscar_entidad = entidad::buscarEntidad($_POST);   
if(!empty($buscar_entidad)){
  $atributos = entidad::dameAtributosEntidad($buscar_entidad['entidad']);
  if (!empty($atributos) ) {
    $salida = $atributos;
  }
}

//$resultado = entidad::getAtributoporAtributo($entidad_atributo);
//echo "<pre>";
//print_r($buscar);
//echo "</pre>";
//Ojo que devuelve un array con los posibles códigos
echo json_encode($salida);
 ?>

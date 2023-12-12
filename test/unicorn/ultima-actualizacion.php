<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . '/../../config.php.inc';

use unicorn\clases\funciones\unicorn_db\Actualizacion as actualizacion;
use unicorn\clases\funciones\unicorn_db\Entidad as entidad;
require_once RAIZ . '/clases/funciones/unicorn_db/Actualizacion.php';
require_once RAIZ . '/clases/funciones/unicorn_db/Entidad.php';
//use unicorn\clases\funciones\inforpor\Stock as stock;
//require RAIZ . '/clases/funciones/inforpor/Stock.php';


//Pedimos las entidades sin mpn
//$datos_bd = actualizacion::dameDatosBd();
//Pedimos los datos de la última actualizacion
$datos = actualizacion::dameDatosActualizacion('pcc');

/*foreach ($datos as $key => $articulo) {
  if (isset($articulo['entidad']) && in_array($articulo['entidad']['id'],$datos_bd)) {
    $grabar = entidad::insertaArticuloEntidadVarchar($articulo['entidad']['id'],17,$articulo['categoria']);
  }

}*/


/*$row = array(
  'ean' => '8436550235074'
);
$articulo = entidad::buscarEntidad($row);
$compra_ifp = stock::ObtenerCompraInforporDev($articulo);*/
echo "<pre>";
echo count($datos);
print_r($datos);
echo "</pre>";

 ?>

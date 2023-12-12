<?php
error_reporting(E_ALL);                                     //Activamos el reporte de errores
ini_set('display_errors', 'on');
require_once __DIR__ . '/../../config.php.inc';
require_once __DIR__ . '/../../clases/funciones/unicorn_db/Entidad.php';
use unicorn\clases\funciones\unicorn_db\Entidad as entidad;
$articulos = array();
$entidades_custodias = entidad::buscarArticuloEntidadInt('1','1');

if (!empty($entidades_custodias)) {
foreach ($entidades_custodias as $entidad) {
//Obtenemos los datos de cada artículo
$mpn = entidad::dameValorArticuloEntidadVarchar($entidad,'2');
$ean = entidad::dameValorArticuloEntidadVarchar($entidad,'3');
$nombre = entidad::dameValorArticuloEntidadVarchar($entidad,'4');
$cod_inforpor = entidad::dameValorArticuloEntidadInt($entidad,'5');

$articulos[] = array(
  'mpn' => $mpn,
  'ean' => $ean,
  'nombre' => $nombre,
  'codinfo' => $cod_inforpor
);
}
}

 ?>
 <?php
 $json_articulos = json_encode($articulos);
 echo $json_articulos;
  ?>

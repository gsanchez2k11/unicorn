<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . '/../../config.php.inc';

use unicorn\clases\funciones\unicorn_db\Entidad as entidad;
require_once RAIZ . '/clases/funciones/unicorn_db/Entidad.php';
use unicorn\clases\funciones\inforpor\Stock as stock;
require_once RAIZ . '/clases/funciones/inforpor/Stock.php';

$row = array(
  'ean' => '8436550235074'
);
$articulo = entidad::buscarEntidad($row);
$compra_ifp = stock::ObtenerCompraInforporDev($articulo);
echo "<pre>";
print_r($articulo);
print_r($compra_ifp);
echo "</pre>";

 ?>

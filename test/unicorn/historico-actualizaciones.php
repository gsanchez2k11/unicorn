<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . '/../../config.php.inc';

use unicorn\clases\funciones\unicorn_db\Actualizacion as actualizacion;
require RAIZ . '/clases/funciones/unicorn_db/Actualizacion.php';

$ultimas = actualizacion::dameUltimasActualizaciones('stock');
$articulos_ultima_stock = unserialize(base64_decode($ultimas[0]['campo_1']));
echo "<pre>";
print_r($articulos_ultima_stock);
echo "</pre>";

 ?>

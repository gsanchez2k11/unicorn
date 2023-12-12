<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
//$tiempo_inicial = microtime(true); //true es para que sea calculado en segundos
require_once __DIR__ . '/../../config.php.inc';
use unicorn\clases\funciones\unicorn_db\Entidad as entidad;
require_once RAIZ . '/clases/funciones/unicorn_db/Entidad.php';
use unicorn\clases\funciones\inforpor\Stock as stock;
require_once RAIZ . '/clases/funciones/inforpor/Stock.php';
?>
<?php


$row = array(
'mpn' => 'C11CH03402'
);

$compra_ifp = stock::ObtenerCompraInforpor($row, true);

echo '<pre>';
print_r($compra_ifp);
echo '</pre>';



//$tiempo_final = microtime(true);
//$tiempo = $tiempo_final - $tiempo_inicial; //este resultado estará en segundos
//echo "El tiempo de ejecución del archivo ha sido de " . $tiempo . " segundos";

 ?>

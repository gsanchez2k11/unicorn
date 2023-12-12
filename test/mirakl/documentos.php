<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . '/../../config.php.inc';
use unicorn\clases\funciones\mirakl\Documentos as documentos;
require RAIZ . '/clases/funciones/mirakl/Documentos.php';
?>


<?php
/*1551367-B
1550873-A
1552387-A
1552275-A
1551818-A
1550276-A
1552473-A
1549838-A
1549690-A
1549182-E
1549802-A
1552380-A*/
//$pedido = '1551367-B';
//$documentos = documentos::tieneFactura($pedido);
$documentos = documentos::subeFactura();


echo "<pre>";
print_r($documentos);
echo "</pre>";
 ?>

<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . '/../../config.php.inc';
use unicorn\clases\funciones\mirakl\Mensajes as mensajes;
require RAIZ . '/clases/funciones/mirakl/Mensajes.php';
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
//$hilo = mensajes::recuperaHilo('e335bb00-f782-47f8-a8b2-1077792364a2');
//$documentos = mensajes::listaHilos();
//$
$tema = mensajes::creaHilo('1562596-A', '¡¡Esperamos que estés disfrutando de tu pedido, y nos lo hagas saber dejándonos tu valoración!! Vendedor de PcComponentes Marketplace');


echo "<pre>";
print_r($tema);
echo "</pre>";
 ?>

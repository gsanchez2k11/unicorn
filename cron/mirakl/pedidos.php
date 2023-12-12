<?php
/**
 * Actualizamos los datos de los artículos de pc componentes, basandonos en la última actualización grabada en la BBDD.
 * Guardamos tanto el resultado de la actualización como los datos básicos para su posterior procesamiento.
 * @var [type]
 */
namespace unicorn\cron\mirakl;
error_reporting(E_ALL);                                     //Activamos el reporte de errores
ini_set('display_errors', 'on');
require_once __DIR__ . '/../../config.php.inc';
require_once(RAIZ . '/vendor/autoload.php');

require_once RAIZ . '/clases/funciones/mirakl/Pedidos.php';

use Mirakl\MMP\Shop\Client\ShopApiClient as Client;
use unicorn\clases\funciones\mirakl\Pedidos as pedidos;
?>

<?php
$pedidos = pedidos::dameUltimosPedidos();

//Filtramos y nos quedamos con los pedidos pendientes de aceptar
$pds_pendientes = array_filter($pedidos, function ($pedido){
//  return $pedido->getEstado() == 'WAITING_ACCEPTANCE';
return $pedido->getEstado() == 'SHIPPED';
});

$articulos_descontar = array();
//Recorremos el array resultante para obtener los articulos
foreach ($pds_pendientes as $key => $pd) {
//Recorremos ahora los articulos de cada pedido
foreach ($pd->getLineasPedido() as $keylp => $linea) {
//Generamos un array con clave codigo de pcc y una entrada por cada cantidad y pedido
  $articulos_descontar[$linea->getSku()][] = $linea->getCantidad();
}
}

$art_descontar_totales = array();

foreach ($articulos_descontar as $key => $art) {
$art_descontar_totales[$key] = array_sum($art);
}

echo "<pre>";
print_r($art_descontar_totales);
//var_dump($pds_shipping);
echo "</pre>";
 ?>

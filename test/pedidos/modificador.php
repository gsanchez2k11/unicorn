<?php
namespace unicorn\test\pedidos;
error_reporting(E_ALL);                                     //Activamos el reporte de errores
ini_set('display_errors', 'on');
require_once __DIR__ . '/../../config.php.inc';
require_once(RAIZ . '/vendor/autoload.php');

require_once RAIZ . '/clases/funciones/mirakl/Pedidos.php';
use unicorn\clases\funciones\mirakl\Pedidos as pedidos;

require_once RAIZ . '/clases/funciones/fnac/Pedidos.php';
use unicorn\clases\funciones\fnac\Pedidos as pedidos_fnac;

require_once RAIZ . '/clases/funciones/unicorn_db/Entidad.php';
use unicorn\clases\funciones\unicorn_db\Entidad as entidad;

 ?>

 <?php
 $articulos_descontar = array();
$pedidos_pcc = pedidos::dameUltimosPedidos('pcc');
$pedidos_phh = pedidos::dameUltimosPedidos('phh');
$pedidos_fnac = pedidos_fnac::damePedidos();
/*$pedidos = array_merge($pedidos_pcc,$pedidos_phh);*/



function articulosDescontar($pedidos, $plataforma = 'pcc'){
    $articulos_descontar = array();
  //Filtramos y nos quedamos con los pedidos pendientes de aceptar
  $pds_pendientes = array_filter($pedidos, function ($pedido){
    return $pedido->getEstado() == 'WAITING_ACCEPTANCE' || $pedido->getEstado() == 'WAITING_DEBIT' || $pedido->getEstado() == 'SHIPPING';
  //return $pedido->getEstado() == 'SHIPPED';
  });

  //Recorremos el array resultante para obtener los articulos
  foreach ($pds_pendientes as $key => $pd) {

  //Recorremos ahora los articulos de cada pedido
  foreach ($pd->getLineasPedido() as $keylp => $linea) {
    $row['sku_' . $plataforma] = $linea->getSku();
    $entidad = entidad::buscarEntidad($row)['entidad'];
    $atributos = entidad::dameAtributosEntidad($entidad);
    $ean = $atributos[3];
  /*  echo '<pre>';
    print_r($ean);
    echo '</pre>';*/
  //Generamos un array con clave codigo de pcc y una entrada por cada cantidad y pedido
    $articulos_descontar['EAN' . $ean][] = $linea->getCantidad();               //Añadimos el string EAN para que las claves sean tratadas como string

  }
  }
  return $articulos_descontar;
}


$articulos_descontar = array();
$art_descontar_totales = array();
$articulos_descontar_pcc = articulosDescontar($pedidos_pcc,'pcc');
$articulos_descontar_phh = articulosDescontar($pedidos_phh,'phh');
$articulos_descontar_fnac = articulosDescontar($pedidos_fnac,'fnac');
$articulos_descontar = array_merge_recursive($articulos_descontar_pcc,$articulos_descontar_phh,);




foreach ($articulos_descontar as $key => $art) {
$art_descontar_totales[substr($key,3)] = array_sum($art);                       //Quitamos la palabra EAN del comienzo
}
  ?>

  <?php
echo '<pre>';
print_r($art_descontar_totales);
echo '</pre>';

   ?>

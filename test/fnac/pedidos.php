<?php
//namespace unicorn\clases\funciones\fnac;
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../../config.php.inc';
//use unicorn\clases\funciones\fnac\Conectar as conectar;
//require_once RAIZ . '/clases/funciones/fnac/Conectar.php';
//$token = conectar::getToken();

use unicorn\clases\funciones\fnac\Pedidos as pedidos;
require_once RAIZ . '/clases/funciones/fnac/Pedidos.php';

require_once RAIZ . '/clases/funciones/unicorn_db/Entidad.php';
use unicorn\clases\funciones\unicorn_db\Entidad as entidad;

function articulosDescontarFnac($pedidos,$plataforma){
    $articulos_descontar = array();
    $pds_pendientes = array_filter($pedidos, function ($pedido){
      return $pedido->getEstado() == 'Shipped';
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

      echo '<pre>';
      print_r($ean);
      echo '</pre>';
    //Generamos un array con clave codigo de pcc y una entrada por cada cantidad y pedido
    //  $articulos_descontar['EAN' . $ean][] = $linea->getCantidad();               //Añadimos el string EAN para que las claves sean tratadas como string

    }
    }

return $pds_pendientes;
}




$pedidos = pedidos::damePedidos();

$articulos = articulosDescontarFnac($pedidos,'fnac');
//Recorremos los atributos para saber si tenemos o no el artículo
/*$atributos = $add->attributes();
foreach ($atributos as $key => $val) {
$status = $val;
}*/

//echo $status;

echo '<pre>';
//print_r($add->error);
print_r($articulos);
echo '</pre>';

 ?>

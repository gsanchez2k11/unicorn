<?php
namespace unicorn\ajax\odoo;

error_reporting(E_ALL);                                     //Activamos el reporte de errores
ini_set('display_errors', 'on');
require_once __DIR__ . '/../../config.php.inc';
require_once(RAIZ . '/vendor/autoload.php');
require_once RAIZ . '/clases/funciones/odoo/Conectar.php';
use unicorn\clases\funciones\odoo\Conectar as conectar;
use unicorn\clases\objetos\Pedidos\Pedido_odoo;
require_once RAIZ . '/clases/objetos/Pedidos/Pedido_odoo.php';
$modelo = 'sale.order';
//$modelo = 'product.category';
$limite = 100; //Si tenemos un limite lo aplicamos, si no 50000 entradas

$offset = 0;
$i = 0;
$total = 0;
$pedidos = array();
do {
$pedidos = array_merge(conectar::listarNuevo($modelo,$offset),$pedidos);
$offset += 10;
$i++;
$total += count($pedidos);
} while (count($pedidos) % 10 === 0 && count($pedidos) < $limite);

$obj_pedido = array();
foreach ($pedidos as $pedido) {
    $obj_pedido[] = new Pedido_odoo($pedido);
}

//echo '<pre>';
//print_r($obj_pedido);
//echo '</pre>';
$json_cliente = json_encode($obj_pedido);
echo $json_cliente;

 ?>

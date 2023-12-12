<?php
error_reporting(E_ALL);                                     //Activamos el reporte de errores
ini_set('display_errors', 'on');
require_once __DIR__ . '/../../config.php.inc';
require_once(RAIZ . '/vendor/autoload.php');
require_once RAIZ . '/clases/funciones/odoo/Solicitudes_compra.php';
use unicorn\clases\funciones\odoo\Solicitudes_compra as solicitudes_compra;
$compras = array();
$campo = 'other_reference';
$referencia = isset($_POST['ventaOdoo']) ? $_POST['ventaOdoo']: $_POST['id']; //Tenemos el nombre del pedido (SOXXX)
$arr_compras = solicitudes_compra::buscarSolicitud($campo,$referencia);
//echo '<pre>';
//print_r($arr_compras);
//echo '</pre>';
/*foreach ($_POST as $venta) {

    if (isset($venta['order_id'])) {
        $articulos = solicitudes_compra::buscarSolicitud($campo,$venta['order_id'][1]);
    } 
    if (isset($venta['name']) && empty($articulos)) {
        $articulos = solicitudes_compra::buscarSolicitud($campo,$venta['name']);
    } 
    if (isset($venta['valor']) && empty($articulos)) {
        $articulos = solicitudes_compra::buscarSolicitud($campo,$venta['valor']);
    }
    if (!empty($articulos)) {
        $ventas[] = $articulos;
    }
}*/

//print_r($_POST);


    


//print_r($articulos);

//$articulos = solicitudes_compra::buscarSolicitud($campo,$valor);

$json_cliente = json_encode($arr_compras);
echo $json_cliente;

 ?>

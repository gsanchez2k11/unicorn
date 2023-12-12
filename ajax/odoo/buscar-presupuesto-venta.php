<?php
error_reporting(E_ALL);                                     //Activamos el reporte de errores
ini_set('display_errors', 'on');
require_once __DIR__ . '/../../config.php.inc';
require_once(RAIZ . '/vendor/autoload.php');
require RAIZ . '/clases/funciones/odoo/Presupuestos_venta.php';
use unicorn\clases\funciones\odoo\Presupuestos_venta as presupuesto_venta;
//require RAIZ . '/clases/funciones/odoo/Conectar.php';
//use unicorn\clases\funciones\odoo\Conectar as conectar;
$ref = $_POST['name'];
$tipo = $_POST['tipo'];


/*
$campo = 'name';
$valor = $ref;
$id = presupuesto_venta::like($campo,$valor,'sale.order.line');

if (empty($id)) {                                                               //Si no encontramos ninguna linea buscamos en notas
  $campo = 'note';
  $valor = $ref;
  $id = presupuesto_venta::like($campo,$valor,'sale.order');
}*/

//Si estamos recibiendo una linea primero buscamos por ello y una vez obtenido la referencia del pedido buscamos de nuevo
if ($tipo == 'linea') {
  $linea = presupuesto_venta::busquedaPorRefCliente($ref);
  if (!empty($linea)) {
   $ref = $linea[0]['order_id'][1];
  }
} 

  $id = presupuesto_venta::busqueda('name',$ref,'sale.order');





/*$campo = 'x_studio_otra_referencia';
$valor = $ref;
$articulos = solicitudes_compra::buscarSolicitud($campo,$valor);*/

$json_cliente = json_encode($id);
echo $json_cliente;

 ?>

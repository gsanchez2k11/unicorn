<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . '/../../config.php.inc';
require_once(RAIZ . '/vendor/autoload.php');
use Ripoo\OdooClient;

use unicorn\clases\funciones\odoo\Solicitudes_compra as solicitudes_compra;
require RAIZ . '/clases/funciones/odoo/Solicitudes_compra.php';

/*$campo = 'x_studio_otra_referencia';
$valor = 'PC1410212';
$articulos = solicitudes_compra::buscarSolicitud($campo,$valor);

echo "<pre>";
print_r($articulos);
echo "</pre>";*/

/*$articulos = array(
  4665,
  5253
);*/

/*$solicitud = array(
  'partner_ref' => 'AV-PRUEBA',                                                              //Albaran del proveedor
  'partner_id' => 217,                                                          // 217 inforpor
  'x_studio_otra_referencia' =>    'PEDPRUEBA',                                             //referencia interna (ref cliente pcc)
//  'product_id' =>           $articulos                                                    //Array de ids de productos
);*/

//$insertar = solicitudes_compra::crearSolicitud($solicitud);

//echo "<pre>";
//print_r($insertar);
//echo "</pre>";

//Buscar solicitud
/*$campo = 'x_studio_otra_referencia';
$valor = 'PC1431918';
$articulos = solicitudes_compra::buscarSolicitud($campo,$valor);
echo "<pre>";
print_r($articulos);
echo "</pre>";
*/

//Buscar
/*$campo = 'active';
$valor = 1;
$modelo = 'account.tax';
$articulos = solicitudes_compra::busqueda($campo,$valor,$modelo);
echo "<pre>";
print_r($articulos);
echo "</pre>";*/


//Actualizar linea de compra
$campo_busqueda = 'id';
$valor_antiguo = 2445;
$campo_actualizar = 'taxes_id';
$valor_nuevo = array([6, 0,array(124)]);
$modelo = 'purchase.order.line';
$articulos = solicitudes_compra::actualizar($campo_busqueda,$valor_antiguo,$campo_actualizar,$valor_nuevo,$modelo);
echo "<pre>";
print_r($articulos);
echo "</pre>";




//Get the current date and time.
/*$date = new DateTime();

//Create a DateInterval object with P1D.
$interval = new DateInterval('P1D');

//Add a day onto the current date.
$date->add($interval);

$linea = array(
  'product_qty' => 5,                                                           //Cantidad
  'product_id' => 8515,                                                         //Id del artículo en odoo
  'order_id' => 965,                                                            //Solicitud de presupuesto al que añadir las lineas
  'date_planned' => $date->format("Y-m-d H:i:s"),                               //Fecha prevista
  'name' => 'Linea de pedido de prueba',                                        //Nombre del artículo
  'price_unit' => 69,                                                           //Precio por unidad
  'product_uom' => 1                                                            //Unidad de medida del artículo

);
$campo = 'id';
$valor = 1947;
$articulos = solicitudes_compra::buscarLineaCompra($campo,$valor);
$linea_salida = solicitudes_compra::crearLineaCompra($linea);
echo "<pre>";
print_r($linea_salida);
print_r($articulos);
echo "</pre>";*/

 ?>

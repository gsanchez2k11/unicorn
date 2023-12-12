<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . '/../../config.php.inc';
require_once(RAIZ . '/vendor/autoload.php');
use Ripoo\OdooClient;

use unicorn\clases\funciones\odoo\Presupuestos_venta as presupuesto_venta;
require RAIZ . '/clases/funciones/odoo/Presupuestos_venta.php';

use unicorn\clases\funciones\odoo\Articulos as articulo;
require RAIZ . '/clases/funciones/odoo/Articulos.php';

$campo = 'id';
$valor = 18776;
$ids = presupuesto_venta::busqueda($campo,$valor,'sale.order');

/*$campo = 'id';
$valor = 60757;
$id_linea_presupuesto = presupuesto_venta::busqueda($campo,$valor,'sale.order.line');*/


//$lista_presupuestos = presupuesto_venta::listar('sale.order',0);
//$campo = 'id';
//$valor = 26506;
//$id_linea_presupuesto = presupuesto_venta::busqueda($campo,$valor,'sale.order.line');


//echo $id;


//Añadimos el número de pedido tambien como linea
//$nombre = 'Linea de prueba';
/*$arr_linea = array(
  'product_qty' => 1,                                                 //Cantidad
  'product_id' => 1,                                               //Id del artículo en odoo
  'order_id' => 3137,                                              //Solicitud de presupuesto al que añadir las lineas
  'customer_lead' => 2,                                                       //Fecha prevista
  'name' => 'Linea de prueba',                                                          //Nombre del artículo
  'price_unit' => 1,                                              //Precio por unidad
  'product_uom' => 1,
  'product_uom_qty' => 1,
    'purchase_price' => 0,                                          //Coste
    'display_type' => 'line_note'
);

$id_linea_presupuesto = presupuesto_venta::crear($arr_linea,'sale.order.line');*/



echo "<pre>";
print_r($ids);
//print_r($id_linea_presupuesto);
echo "</pre>";


/*$campo_busqueda = 'id';
//$valor_antiguo = 60757;
$valor_antiguo = 60761;
$campo_actualizar = 'product_uom';
//$campo_actualizar = 'discount';
$valor_nuevo = 24;
$modelo = 'sale.order.line';
//$actualizar = articulo::actualizar($campo_busqueda,$valor_antiguo,$campo_actualizar,$valor_nuevo,$modelo);
echo "<pre>";
print_r($actualizar);
echo "</pre>";*/
 ?>

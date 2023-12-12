<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . '/../../config.php.inc';
require_once(RAIZ . '/vendor/autoload.php');
use Ripoo\OdooClient;

use unicorn\clases\funciones\odoo\Presupuestos_venta as presupuesto_venta;
require RAIZ . '/clases/funciones/odoo/Presupuestos_venta.php';
$partner_id = 209;
$dir_factura = 12893;
$dir_envio = 12893;
$blm_journal_id = 34;
$payment_mode_id = 11;
$payment_term_id = 1;
$almacen = 2;


$datos_presupuesto = array(
    'partner_id' => $partner_id,                                                           //id de cliente (PC componentes)(209)
    'partner_invoice_id' => $dir_factura,                                                   //id direccion factura
    'partner_shipping_id' => $dir_envio,                                                  //id de direccion de envío
   // 'blm_journal_id' => $blm_journal_id,                                                        //diario (PC componentes)
    'payment_mode_id' => $payment_mode_id,                                                      //Forma de pago (PC componentes)
    'currency_id'     => 1,                                                      //Moneda (Euro)
  
    'picking_policy' => 'direct',                                                       //Politica de entrega
    'pricelist_id'=> 1,                                                           //Tarifa
    'payment_term_id' => $payment_term_id,                                                        //Plazo de pago
    'fiscal_position_id' => 24,                                                   //Posicion fiscal (Los datos fijos habría que obtenerlos del cliente,pero como de momento son todos de pc componentes los tomamos como constantes)
      //  'note' => $nota                                          //La info que aparece bajo las lineas de artículos
  'warehouse_id' => $almacen,
  //'warehouse_id' => 1,
  'company_id' => 1,
 // 'date_order'      => '2022-09-01',                                                       //Fecha pedido
  //'name' => 'SO033311A'
  );
  //print_r($datos_presupuesto);
  $id_presupuesto = presupuesto_venta::crear($datos_presupuesto,'sale.order');



echo "<pre>";
print_r($id_presupuesto);
//print_r($lista_presupuestos);
echo "</pre>";
 ?>

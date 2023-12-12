<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . '/../../config.php.inc';
require_once(RAIZ . '/vendor/autoload.php');
use Ripoo\OdooClient;

use unicorn\clases\funciones\odoo\Conectar as conectar;
require RAIZ . '/clases/funciones/odoo/Conectar.php';

$criterios[] = array (                                                                  //Generamos el array de busqueda
  'campo' => 'journal_id',
  'operador' => '=',
  'valor' => 34
);
$criterios[] = array (                                                                  //Generamos el array de busqueda
  'campo' => 'state',
  'operador' => '=',
  'valor' => 'open'
);
$facturas = conectar::busquedaConOperador($criterios,'account.invoice');              //Buscamos las facturas que corresponden a ese periodo. Nos devuelve un array

foreach ($facturas as $key => $factura) {
  //echo "<pre>";
  //print_r($factura);
  //echo "</pre>";
if ($key == 0) {


$fecha = $factura['date_invoice'];
$presupuesto = $factura['origin'];
$moneda = 1;                                                                    //euro
$importe = $factura['amount_total'];
$diario = 31;                                                                   //Pc componentes
$payment_method_id = 1;                                                         //Manual, codigo de una factura pagada
$payment_type = 'inbound';                                                      //Cogido de otra factura
$invoice_ids = array(array(4, $factura['id'], false));                                         //https://www.odoo.com/documentation/12.0/reference/orm.html#odoo.fields.One2many
$estado = 'posted';
$partner_type =    'customer';                                                             //Tipo de cliente
$partner_id = $factura['partner_id'];                                                              //Id de cliente
$name = 'CUST.IN/' . $factura['move_name'];                                                                     //nombre
$communication = $factura['reference'];

$pago = array (
'amount' => $importe,
'currency_id' => $moneda,
'journal_id' => $diario,
'payment_date' => $fecha,
'payment_method_id' => $payment_method_id,
'payment_type' => $payment_type,
'invoice_ids' => $invoice_ids,
'state' => $estado,
'partner_type' => $partner_type,
'partner_id' => $partner_id[0],
'name' => $name,
'communication' => $communication
);
$crear_pago = conectar::crear($pago, 'account.payment');
echo "<pre>";
print_r($crear_pago);
//print_r($pago);
echo "</pre>";
}
//Creamos un pago asociado a esa factura

}

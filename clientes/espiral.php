<?php
namespace unicorn\clientes;
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . '/../config.php.inc';
require_once(RAIZ . '/vendor/autoload.php');
use Ripoo\OdooClient;

require_once RAIZ . '/clases/funciones/odoo/Clientes.php';
use unicorn\clases\funciones\odoo\Clientes as cliente;
require_once RAIZ . '/clases/funciones/odoo/Articulos.php';
use unicorn\clases\funciones\odoo\Articulos as articulos;
require_once RAIZ . '/clases/funciones/odoo/Presupuestos_venta.php';
use unicorn\clases\funciones\odoo\Presupuestos_venta as presupuesto_venta;
//Cargamos el cliente, en este caso Espiral
$campo = 'id';
$valor = 24;
$busqueda_clientes = cliente::busqueda($campo,$valor,'res.partner');
$cliente = $busqueda_clientes[0];

//Definimos las variables que nos interesan
$id_cliente_odo = $valor;
$partner_id = $valor;
$dir_envio = $valor;
//$dir_envio = isset($cliente['child_ids']) && !empty($cliente['child_ids']) ? $cliente['child_ids'][0] : $dir_factura;   //Si tiene direcciones hijas las cogemos, si no cogemos la de factura
$blm_journal_id = 27;  //Facturas Central
$payment_mode_id = 1; //Recibo Domiciliado
$payment_term_id = 3; //30 dias

$nombre_cliente = $cliente['name'];
$id_tarifa = $cliente['property_product_pricelist'][0]; //Id de su tarifa

$datos_presupuesto = array(
  'partner_id' => $partner_id,                                                           //id de cliente (PC componentes)(209)
  'partner_invoice_id' => $partner_id,                                                   //id direccion factura
  'partner_shipping_id' => $dir_envio,                                                  //id de direccion de envío
  'blm_journal_id' => $blm_journal_id,                                                        //diario (PC componentes)
  'payment_mode_id' => $payment_mode_id,                                                      //Forma de pago (PC componentes)
  'currency_id'     => 1,                                                      //Moneda (Euro)
  //'date_order'      => $fecha_debito,                                                       //Fecha pedido
  'picking_policy' => 'direct',                                                       //Politica de entrega
  'pricelist_id'=> $id_tarifa,                                                           //Tarifa
  'payment_term_id' => $payment_term_id,                                                        //Plazo de pago
  'fiscal_position_id' => 24,                                                   //Posicion fiscal (Los datos fijos habría que obtenerlos del cliente,pero como de momento son todos de pc componentes los tomamos como constantes)
//  'note' => $nota                                          //La info que aparece bajo las lineas de artículos
'warehouse_id' => 1
);
$id_presupuesto = presupuesto_venta::crear($datos_presupuesto,'sale.order');




//Buscamos algún artículo
$atributos['default_code'] = 'C13T890100';
$articulo = articulos::buscarArticuloAtributos($atributos);

echo "<pre>";
print_r($id_presupuesto);
echo "</pre>";

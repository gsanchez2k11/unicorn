<?php
error_reporting(E_ALL);                                     //Activamos el reporte de errores
ini_set('display_errors', 'on');
require_once __DIR__ . '/../../config.php.inc';
require_once(RAIZ . '/vendor/autoload.php');
require_once RAIZ . '/clases/funciones/odoo/Presupuestos_venta.php';
use unicorn\clases\funciones\odoo\Presupuestos_venta as presupuesto_venta;
$obj_pedido = $_POST;//Recuperamos los datos globales del pedido
//echo '<pre>';
//print_r($obj_pedido);
//echo '</pre>';
//exit('comprobamos');
$plataforma = $obj_pedido['plataforma'];
$cliente = $obj_pedido['cliente'];
if(isset($obj_pedido['pedido_venta']) && !empty($obj_pedido['pedido_venta']['id'])) {
  $pedido_venta = $obj_pedido['pedido_venta'];
  $id_pedido_plataforma_venta = 'Pedido nº ' . $pedido_venta['id'];               //Generamos el string con la nota
  $nota = $id_pedido_plataforma_venta;      //Asignamos la variable nota
}
if(isset($obj_pedido['pedido_compra']) && !empty($obj_pedido['pedido_compra']) ) $pedido_compra = $obj_pedido['pedido_compra'];
$articulos_venta = $obj_pedido['articulos_venta'];

$buscar = isset($pedido_venta) ? presupuesto_venta::busquedaPorRefCliente($pedido_venta['id']) : '';               //Buscamos el presupuesto para asegurarnos que no lo estamos duplicando
/*echo '<pre>';
print_r($buscar);
echo '</pre>';*/
////////////////////////////////////////////////////////////////////////////////
/////////    CREAMOS EL PRESUPUESTO                                      ///////
////////////////////////////////////////////////////////////////////////////////

if (empty($buscar)){               //Buscamos el presupuesto para asegurarnos que no lo estamos duplicando))
  $id_cliente_odoo = $cliente['id'];                                                                                      //Id del cliente en ODOO
  $dir_factura = $id_cliente_odoo;
$dir_envio =  dameDireccionEnvio($obj_pedido);

$partner_id = $plataforma == 'pcc' ? 209 : $id_cliente_odoo;                        //Si la plataforma  es pc componentes usamos su id, si no la del cliente
$payment_term_id = $cliente['property_payment_term_id'][0];                         //La forma de pago la cogemos del cliente
//El diario no va relacionado con el cliente, si no que se define para cada presupuesto
if ($plataforma == 'clientes') { //Si la plataforma es 'clientes' no podemos capturar ni deducir el diario
switch ($id_cliente_odoo) {
case '24':
case '78':
case '260':
$blm_journal_id = 27;
break;
default:
$blm_journal_id = 34;

}
$payment_mode_id = $cliente['customer_payment_mode_id'][0];
} else { //Si viene de otra plataforma deducimos los datos
/*$id_linea_negocio =   presupuesto_venta::identificarLineaNegocio($pedido_venta);
switch ($id_linea_negocio) {
  case '0':
  $blm_journal_id = 23;
//  $payment_term_id = 1;
  break;
  case '1':
  $blm_journal_id = 21;
//  $payment_term_id = 1;
  break;
  case '2':
  $blm_journal_id = 24;
//  $payment_term_id = 1;
  break;
  case '3':
  $blm_journal_id = 28;
//  $payment_term_id = 1;
  break;
  default:
  $blm_journal_id = 34;
//  $payment_term_id = 2;
}*/
//Generamos la forma de pago
if (isset($pedido_venta['forma_pago'])) {
switch ($pedido_venta['forma_pago']) {
  case 'Pagar con tarjeta':
  case 'Pago con tarjeta':
$payment_mode_id = 9;
    break;
    case 'Transferencia / Ingreso bancario':
    case "Transferencia ' ingreso bancario":
  $payment_mode_id = 3;
      break;
      case 'PayPal':
    $payment_mode_id = 12;
        break;

}
} else {                                                                        //Si no tenemos forma de pago de momento seguro que es de Pc componentes
$payment_mode_id = 11;
}


}

$almacen = isset($obj_pedido['almacen']) && is_numeric($obj_pedido['almacen']) ? $obj_pedido['almacen'] : 1;
$tarifa = isset($cliente['property_product_pricelist']) ? $cliente['property_product_pricelist'][0] : 1;
$datos_presupuesto = array(
  'partner_id' => $partner_id,                                                           //id de cliente (PC componentes)(209)
  'partner_invoice_id' => $dir_factura,                                                   //id direccion factura
  'partner_shipping_id' => $dir_envio,                                                  //id de direccion de envío //No funciona en versión 14
//  'blm_journal_id' => $blm_journal_id,                                                        //diario (PC componentes)
  'payment_mode_id' => $payment_mode_id,                                                      //Forma de pago (PC componentes)
  'currency_id'     => 1,                                                      //Moneda (Euro)
  //'date_order'      => $fecha_debito,                                                       //Fecha pedido
  'picking_policy' => 'direct',                                                       //Politica de entrega
  'pricelist_id'=> $tarifa,                                                           //Tarifa
  'payment_term_id' => $payment_term_id,                                                        //Plazo de pago
  'fiscal_position_id' => 24,                                                   //Posicion fiscal (Los datos fijos habría que obtenerlos del cliente,pero como de momento son todos de pc componentes los tomamos como constantes)
//  'note' => $nota                                          //La info que aparece bajo las lineas de artículos
'warehouse_id' => $almacen,
'x_studio_estado_operacin' => '1',
'type_id' => 2 //Tipo de pedido Facturas central
);
/*echo '<pre>';
print_r($datos_presupuesto);
echo '</pre>';*/
$id_presupuesto = presupuesto_venta::crear($datos_presupuesto,'sale.order');
/*echo '<pre>';
print_r($id_presupuesto);
echo '</pre>';*/
////////////////////////////////////////////////////////////////////////////////
/////////    AÑADIMOS ARTICULOS                                          ///////
////////////////////////////////////////////////////////////////////////////////

foreach ($articulos_venta as $linea) {
  $cantidad = intval($linea['cantidad']);
  //  $id_articulo = intval($linea['id']);
    $id_articulo = intval($linea['idProducto']);
    $nombre = $linea['nombre'];
    $precio_venta = $linea['precio'];
    $precio_compra = isset($linea['precio_compra']) ?  $linea['precio_compra'] : 0 ;
    $descuento = isset($linea['descuento']) ?  $linea['descuento'] : 0 ;
    $product_uom = isset($linea['uomId']) ?  intval($linea['uomId']) : 1 ;
    $arr_linea = array(
      'product_qty' => $cantidad,                                                 //Cantidad
      'product_id' => $id_articulo,                                               //Id del artículo en odoo
      'order_id' => $id_presupuesto,                                              //Solicitud de presupuesto al que añadir las lineas
      'customer_lead' => 2,                                                       //Fecha prevista
      'name' => $nombre,                                                          //Nombre del artículo
      'price_unit' => $precio_venta,                                              //Precio total
      'product_uom' => $product_uom,
      'product_uom_qty' => $cantidad,
      'purchase_price' => $precio_compra,                                          //Coste
      'discount' => $descuento
    );
    $id_linea_presupuesto = presupuesto_venta::crear($arr_linea,'sale.order.line');

    //Si es un pedido de pc componentes configuramos la comisión
    if ($plataforma == 'pcc') {
      $valor = $cod_categoria;
      $campo = 'name';
    $busqueda = presupuesto_venta::like($campo,$valor,'sale.commission');
$importe_comision = floatval($linea['comision'] / 1.21);


    $id_comision = !empty($busqueda) ? $busqueda[0]['id'] : 1;

  $cambiar_comision = presupuesto_venta::actualizar('object_id',$id_linea_presupuesto,'commission',$id_comision,'sale.order.line.agent');
  $cambiar_comision = presupuesto_venta::actualizar('object_id',$id_linea_presupuesto,'amount',$importe_comision,'sale.order.line.agent');

    }

}

//Comprobamos los portes
if (isset($pedido_venta['portes_pedido'])) {
  //Añadimos el número de pedido tambien como linea
  $nombre = 'Portes web';
  $arr_linea = array(
    'product_qty' => 1,                                                 //Cantidad
    'product_id' => 2,                                               //Id del artículo en odoo
    'order_id' => $id_presupuesto,                                              //Solicitud de presupuesto al que añadir las lineas
    'customer_lead' => 0,                                                       //Fecha prevista
    'name' => $nombre,                                                          //Nombre del artículo
    'price_unit' => $pedido_venta['portes_pedido'],                                              //Precio por unidad
    'product_uom' => 1,
    'product_uom_qty' => 1,
    //  'purchase_price' => 0,                                          //Coste
  //  'display_type' => 'line_note'
  );
  $id_linea_presupuesto = presupuesto_venta::crear($arr_linea,'sale.order.line');
}

if (isset($nota)) {
  //Añadimos el número de pedido tambien como linea
  $nombre = $nota;
  $arr_linea = array(
  //  'product_qty' => 0,                                                 //Cantidad
    'product_id' => 1,                                               //Id del artículo en odoo
    'order_id' => $id_presupuesto,                                              //Solicitud de presupuesto al que añadir las lineas
    'customer_lead' => 0,                                                       //Fecha prevista
    'name' => $nombre,                                                          //Nombre del artículo
    'price_unit' => 0,                                              //Precio por unidad
  //  'product_uom' => 1,
    'product_uom_qty' => 0,
    //  'purchase_price' => 0,                                          //Coste
    'display_type' => 'line_note'
  );

  $id_linea_presupuesto = presupuesto_venta::crear($arr_linea,'sale.order.line');
}
//Buscamos el presupuesto para obtener la ref
$dat_pre = presupuesto_venta::busqueda('id',$id_presupuesto,'sale.order');
$nombre = $dat_pre[0]['name'];
/*echo '<pre>';
print_r($dat_pre);
echo '</pre>';*/
$json_cliente = json_encode($dat_pre); //Devolvemos el presupuesto creado
//$json_cliente = json_encode($nombre); //Devolvemos la referencia del presupuesto

//$id_linea_negocio = $plataforma == 'clientes' ? $cliente['x_studio_linea_de_negocio'] : presupuesto_venta::identificarLineaNegocio($pedido_venta);
/*echo '<pre>';
print_r($id_presupuesto);
echo '</pre>';*/

} else {

  $json_cliente = json_encode($buscar[0]['id']);

}

echo $json_cliente;

//funciones
function dameDireccionEnvio($obj_pedido){
  if (isset($obj_pedido['dir_envio'])) {
  $dir_envio = $obj_pedido['dir_envio'];
} else {
  $dir_envio = isset($cliente['child_ids']) && !empty($cliente['child_ids'])? $cliente['child_ids'][0] : $id_cliente_odoo;   //Si tiene direcciones hijas las cogemos, si no cogemos la de factura

}
return $dir_envio;
}

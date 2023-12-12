<?php
error_reporting(E_ALL);                                     //Activamos el reporte de errores
ini_set('display_errors', 'on');
require_once __DIR__ . '/../../config.php.inc';
require_once(RAIZ . '/vendor/autoload.php');
require_once RAIZ . '/clases/funciones/odoo/Presupuestos_venta.php';
use unicorn\clases\funciones\odoo\Presupuestos_venta as presupuesto_venta;
$obj_pedido = $_POST;                                                           //Recuperamos los datos globales del pedido
//echo '<pre>';
//print_r($obj_pedido);
//echo '</pre>';
$plataforma = $obj_pedido['plataforma'];
//$cliente = $obj_pedido['cliente'];
$cliente = $obj_pedido['direccionesPedido']['factura'];
if(isset($obj_pedido['pedido_venta']))  $pedido_venta = $obj_pedido['pedido_venta'];
if(isset($obj_pedido['pedido_compra'])) $pedido_compra = $obj_pedido['pedido_compra'];
if(isset($obj_pedido['direccionesPedido']['envio'])) $envio = $obj_pedido['direccionesPedido']['envio'];
$articulos_venta = $obj_pedido['articulosVenta'];

$buscar = isset($pedido_venta) ? presupuesto_venta::busquedaPorRefCliente($pedido_venta['id']) : '';               //Buscamos el presupuesto para asegurarnos que no lo estamos duplicando

if (empty($buscar)){               //Buscamos el presupuesto para asegurarnos que no lo estamos duplicando))
$id_cliente_odoo = $cliente['id'];                                                                                      //Id del cliente en ODOO
$dir_factura = $id_cliente_odoo;                                                                                        //La direccion se indica mediante la ID del contacto

//$dir_envio = isset($cliente['child_ids']) && !empty($cliente['child_ids']) ? $cliente['child_ids'][0] : $dir_factura;   //Si tiene direcciones hijas las cogemos, si no cogemos la de factura
$dir_envio = isset($envio) ? $envio['id'] : $dir_factura;
$payment_term_id = $cliente['property_payment_term_id'][0];                         //La forma de pago la cogemos del cliente
//echo '<pre>';
//print_r($cliente);
//echo '</pre>';
$id_pedido_plataforma_venta = 'Pedido nº ' . $pedido_venta['id'];               //Generamos el string con la nota
$nota = $id_pedido_plataforma_venta;                                            //Asignamos la variable nota

$partner_id = $plataforma == 'pcc' ? 209 : $dir_factura;                        //Si la plataforma  es pc componentes usamos su id, si no la del cliente

$tipo_pedido = isset($obj_pedido['tipoPedido']) ? $obj_pedido['tipoPedido'] : 0 ; //CApturamos el tipo de pedido que hemos seleccionado, si no lo tenemos asignamos 0 (normal order)



//$id_linea_negocio = presupuesto_venta::identificarLineaNegocio($pedido_venta);


/*switch ($id_linea_negocio) {
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

//Generamos el diario
/*  $primeros_caracteres =substr($pedido_venta['id'],0,3);
  switch ($primeros_caracteres) {
    case 'TP0':
  $blm_journal_id = 23;
  $payment_term_id = 1;
      break;
      case 'TSO':
    $blm_journal_id = 21;
      $payment_term_id = 1;
        break;
        case 'TSU':
      $blm_journal_id = 24;
        $payment_term_id = 1;
          break;
          case 'FUT':
        $blm_journal_id = 28;
          $payment_term_id = 1;
        break;
        default:
        $blm_journal_id = 34;
          $payment_term_id = 2;
                                          //Por defecto usamos el de pc componentes
  }*/
//  echo "forma pago";
//print_r($pedido_venta['forma_pago']);
//Generamos la forma de pago
if (isset($pedido_venta['forma_pago'])) {
switch ($pedido_venta['forma_pago']) {
  case 'Pago con tarjeta':
$payment_mode_id = 9;
    break;
    case 'Transferencia / Ingreso bancario':
      case 'Transferencia / ingreso bancario':
    case "Transferencia ' ingreso bancario":
      case "Transferencia &apos; ingreso bancario":
  $payment_mode_id = 3;
      break;
      case 'PayPal':
    $payment_mode_id = 12;
        break;
case 'Aplazame':
  $payment_mode_id = 23;
  break; 
}
} else {                                                                        //Si no tenemos forma de pago de momento seguro que es de Pc componentes
$payment_mode_id = 11;
}

$almacen = isset($obj_pedido['almacen']) && is_numeric($obj_pedido['almacen']) ? $obj_pedido['almacen'] : 1;

$datos_presupuesto = array(
  'partner_id' => $partner_id,                                                           //id de cliente (PC componentes)(209)
  'partner_invoice_id' => $dir_factura,                                                   //id direccion factura
  'partner_shipping_id' => $dir_envio,                                                  //id de direccion de envío
  //'blm_journal_id' => $blm_journal_id,                                                        //diario (PC componentes)
  'payment_mode_id' => $payment_mode_id,                                                      //Forma de pago (PC componentes)
  'currency_id'     => 1,                                                      //Moneda (Euro)
  'picking_policy' => 'direct',                                                       //Politica de entrega
  'pricelist_id'=> 1,                                                           //Tarifa
  'payment_term_id' => $payment_term_id,                                                        //Plazo de pago
  'fiscal_position_id' => 24,                                                   //Posicion fiscal (Los datos fijos habría que obtenerlos del cliente,pero como de momento son todos de pc componentes los tomamos como constantes)
    //  'note' => $nota                                          //La info que aparece bajo las lineas de artículos
'warehouse_id' => $almacen,
'company_id' => 1,
'type_id' => intval($tipo_pedido),
//'x_studio_estado_operacin' => 1           //Le ponemos estado operación pendiente
/*'warehouse_id' => 1,
'company_id' => 1,
'date_order'      => '2022-08-20 11:02:50',                                                       //Fecha pedido
'name' => 'SO033311A'*/
);
//echo '<pre>';
//print_r($datos_presupuesto);
//echo '</pre>';
$id_presupuesto = presupuesto_venta::crear($datos_presupuesto,'sale.order');
//print_r($articulos_venta);

foreach ($articulos_venta as $linea) {
 // echo '<pre>';
//print_r($linea);
//echo '</pre>';
  $cantidad = intval($linea['cantidad']);
  $id_articulo = intval($linea['id']);
  $nombre = $linea['nombre'];
  $precio_venta = $linea['precio'];
  $precio_compra = isset($linea['precio_compra']) ?  $linea['precio_compra'] : 0 ;
  $cod_categoria = isset($linea['codCat']) ? $linea['codCat'] : '';


  $arr_linea = array(
    'product_qty' => $cantidad,                                                 //Cantidad
    'product_id' => $id_articulo,                                               //Id del artículo en odoo
    'order_id' => $id_presupuesto,                                              //Solicitud de presupuesto al que añadir las lineas
    'customer_lead' => 2,                                                       //Fecha prevista
    'name' => $nombre,                                                          //Nombre del artículo
    'price_unit' => $precio_venta,                                              //Precio total
    'product_uom' => 1,
    'product_uom_qty' => $cantidad,
    'purchase_price' => $precio_compra                                          //Coste
  );



    $id_linea_presupuesto = presupuesto_venta::crear($arr_linea,'sale.order.line');

    //Si es un pedido de pc componentes configuramos la comisión
    if ($plataforma == 'pcc') {
      $valor = $cod_categoria;
      $campo = 'name';
    $busqueda = presupuesto_venta::like($campo,$valor,'sale.commission');
$importe_comision = floatval($linea['comision'] / 1.21);


    $id_comision = !empty($busqueda) ? $busqueda[0]['id'] : 1;
  $cambiar_comision = presupuesto_venta::actualizar('object_id',$id_linea_presupuesto,'commission_id',$id_comision,'sale.order.line.agent');
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





$json_cliente = json_encode($id_presupuesto);

} else {

  $json_cliente = json_encode($buscar[0]['id']);

}

echo $json_cliente;
 ?>

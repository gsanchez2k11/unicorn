<?php
error_reporting(E_ALL);                                     //Activamos el reporte de errores
ini_set('display_errors', 'on');

/**
 * Las lineas de custodia no las vamos a crear, por lo que puede ser que no tengamos que crear el presupuesto
 * @var [type]
 */
require_once __DIR__ . '/../../config.php.inc';
require_once(RAIZ . '/vendor/autoload.php');
require_once RAIZ . '/clases/funciones/odoo/Solicitudes_compra.php';
require_once RAIZ . '/clases/funciones/odoo/Articulos.php';
require_once RAIZ . '/clases/funciones/inforpor/Pedido.php';

use unicorn\clases\funciones\odoo\Solicitudes_compra as solicitud_compra;
use unicorn\clases\funciones\odoo\Articulos as articulos;
use unicorn\clases\funciones\inforpor\Pedido as pedido;

$obj_pedido = $_POST;

//Get the current date and time.
$date = new DateTime();

//Create a DateInterval object with P1D.
$interval = new DateInterval('P1D');

//Add a day onto the current date.
$date->add($interval);
if (isset($obj_pedido['pedido_compra'])) { //Si tenemos el indice pedido_compra estamos intentando grabarlo desde un pedido de venta y el botón sonreir

$ped_compra = $obj_pedido['pedido_compra'];

$ref_pedido['NumPedCli'] = $ped_compra['numpedCli'];
$obj_ped_infor = pedido::EstadoPedidoObj($ref_pedido);

$ls_compras = $obj_ped_infor->getLineasPedR();

//Filtramos las lineas del pedido para comprobar si hay lineas que no sean de custodia
$ls_no_custo = array_filter($ls_compras, function($linea){
  return $linea->esCustodia() == false;
});

if (!empty($ls_no_custo)) {

$buscar = solicitud_compra::busqueda('partner_ref',$obj_ped_infor->getNumero(),'purchase.order');
if (empty($buscar)) {                                                           //Si recibimos un array vacio creamos el presupuesto

//Preparamos el presupuesto de compra a falta de analizar las lineas
$solicitud = array(
  'partner_ref' => $obj_ped_infor->getNumero(),                                 //Albaran del proveedor
  'partner_id' => 217,                                                          // 217 inforpor
  'x_studio_otra_referencia' => $obj_ped_infor->getNumpedCli(),                 //referencia interna (ref cliente pcc),
  'fiscal_position_id' => 24,                                                   //Tipo de IVA
  'payment_term_id' => 13,                                                      //Plazo de pago
  'payment_mode_id' => 14                                                       //Forma de pago
);
$insertar = solicitud_compra::crearSolicitud($solicitud);

$id_compra = $insertar;


foreach ($ls_no_custo as $l_compra) {
  $articulo_odoo = articulos::buscarArticuloAtributos($l_compra->getAtributosBd());
  $id_odoo = $articulo_odoo[0]['product_variant_ids'][0];
  $nombre = $articulo_odoo[0]['name'];
  $importe_iva = $l_compra->getPrecio() * 0.21;
  $linea = array(
    'product_qty' => $l_compra->getCant(),                                      //Cantidad
    'product_id' => $id_odoo,                                                   //Id del artículo en odoo
    'order_id' => $id_compra,                                                          //Solicitud de presupuesto al que añadir las lineas
    'date_planned' => $date->format("Y-m-d H:i:s"),                             //Fecha prevista
    'name' => $nombre,                                      //Nombre del artículo
    'price_unit' => $l_compra->getPrecio(),                                   //Precio por unidad (sin iva)
    'product_uom' => 1,                                                          //Unidad de medida del artículo
    'taxes_id' => array([6, 0,array(124)]),                                                             //Impuestos
  //  'price_tax' => $importe_iva,
    //'price_total' => $l_compra->getPrecio() + $importe_iva

  );
  $linea_salida = solicitud_compra::crearLineaCompra($linea);

  /*echo "<pre>";
  var_dump($linea_salida);
  //print_r($lineas);
  echo "</pre>";*/
  $json_cliente = json_encode($linea_salida);

}
} else {
  $json_cliente = json_encode($buscar[0]['id']);
}
//echo "<pre>";
//print_r($id_compra);
//print_r($lineas);
//echo "</pre>";

} else {
$json_cliente = json_encode('Todos los articulos son de custodias');
}
  echo $json_cliente;

} else { //Si no tenemos el índice pedido_compra estamos accediendo directamente desde la info de inforpor
  //Preparamos el presupuesto de compra a falta de analizar las lineas
$solicitud = array(
  'partner_ref' => $obj_pedido['refProveedor'],                                 //Albaran del proveedor
  'partner_id' => 217,                                                          // 217 inforpor, 863 mi ficha
  'x_studio_otra_referencia' => $obj_pedido['numpedCli'],                 //referencia interna (ref cliente pcc),
  'fiscal_position_id' => 24,                                                   //Tipo de IVA
  'payment_term_id' => 13,                                                      //Plazo de pago
  'payment_mode_id' => 14                                                       //Forma de pago
);
if (isset($obj_pedido['fechaAlbaran'])) {
  $str = strtotime(str_replace('/','-',$obj_pedido['fechaAlbaran']));  
  $newDate = date("Y-m-d H:i:s", $str);  
  $solicitud['date_planned'] = $newDate;
}
//echo "<pre>";                                                           //Recuperamos los datos globales del pedido
//print_r($obj_pedido);
//echo "</pre>";
$insertar = solicitud_compra::crearSolicitud($solicitud);

$id_compra = $insertar;
//Recorremos las líneas del pedido para grabarlas
foreach ($obj_pedido['lineas_pedido'] as $l_compra) {
    $attrs['2'] = $l_compra['mpn'];
  $articulo_odoo = articulos::buscarArticuloAtributos($attrs);

  $id_odoo = $articulo_odoo[0]['product_variant_ids'][0];
  $nombre = $articulo_odoo[0]['name'];
  $importe_iva = $l_compra['importe'] * 0.21;
  $linea = array(
    'product_qty' => $l_compra['cantidad'],                                      //Cantidad
    'product_id' => $id_odoo,                                                   //Id del artículo en odoo
    'order_id' => $id_compra,                                                          //Solicitud de presupuesto al que añadir las lineas
    'date_planned' => $newDate,                             //Fecha prevista
    'name' => $nombre,                                      //Nombre del artículo
    'price_unit' => $l_compra['importe'],                                   //Precio por unidad (sin iva)
    'product_uom' => 1,                                                          //Unidad de medida del artículo
    'taxes_id' => array([6, 0,array(124)]),                                                             //Impuestos
  //  'price_tax' => $importe_iva,
    //'price_total' => $l_compra->getPrecio() + $importe_iva

  );
  $linea_salida = solicitud_compra::crearLineaCompra($linea);
}
//Si hay linea de portes tenemos que grabarla también
if (isset($obj_pedido['portes']) && $obj_pedido['portes'] > 0) {
  $attrs['2'] = 'Delivery_009'; //Esta es la referencia que usamos para los portes
  $articulo_odoo = articulos::buscarArticuloAtributos($attrs);

  $id_odoo = $articulo_odoo[0]['product_variant_ids'][0];
  $nombre = $articulo_odoo[0]['name'];
  $importe_iva = $obj_pedido['portes'] * 0.21;
  $linea = array(
    'product_qty' => 1,                                      //Cantidad
    'product_id' => $id_odoo,                                                   //Id del artículo en odoo
    'order_id' => $id_compra,                                                          //Solicitud de presupuesto al que añadir las lineas
    'date_planned' => $newDate,                             //Fecha prevista
    'name' => $nombre,                                      //Nombre del artículo
    'price_unit' => $obj_pedido['portes'],                                   //Precio por unidad (sin iva)
    'product_uom' => 1,                                                          //Unidad de medida del artículo
    'taxes_id' => array([6, 0,array(124)]),                                                             //Impuestos
  //  'price_tax' => $importe_iva,
    //'price_total' => $l_compra->getPrecio() + $importe_iva

  );
  $linea_salida = solicitud_compra::crearLineaCompra($linea);
}

$json_cliente = json_encode($id_compra);
echo $json_cliente;
}



//echo "<pre>";                                                           //Recuperamos los datos globales del pedido
//print_r($obj_pedido);
//echo "</pre>";
 ?>

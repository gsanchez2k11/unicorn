<?php
namespace unicorn\clases\objetos\Pedidos;
require_once('Linea_Pedido_mage.php');
require_once('Pedido_venta.php');
require_once(RAIZ . '/clases/funciones/otras/Clientes.php');
use unicorn\clases\objetos\Pedidos\Pedido_venta as pedido_venta;
use unicorn\clases\objetos\Pedidos\Linea_Pedido_mage as LineaPedidomage;
use unicorn\clases\objetos\Direccion\Direccion as direccion;
use unicorn\clases\funciones\otras\Clientes as clientes;
use JsonSerializable;

/**
 *
 */
class Pedido_mage extends Pedido_venta implements JsonSerializable
{
  private $applied_rule_ids;                                                    //Ids de los descuentos aplicados
  private $base_currency_code;                                                  //Moneda (EUR)
  private $base_discount_amount;                                                //Importe de descuento
  private $base_discount_tax_compensation_amount;
  private $base_grand_total;                                                    //Total del pedido
  private $base_shipping_amount;                                                //Portes
  private $base_shipping_discount_amount;                                       //Descuento en los Portes
  private $base_shipping_discount_tax_compensation_amnt;
  private $base_shipping_incl_tax;                                              //Portes con IVA
  private $base_shipping_tax_amount;                                            //Importe del IVA de los portes
  private $base_subtotal;                                                       //Subtotal sin iva
  private $base_subtotal_incl_tax;                                              //Subtotal con IVA
  private $base_tax_amount;                                                     //Importe del IVA
  private $base_total_due;                                                      //Importe a pagar
  private $billing_address;                                                     //Dirección de factura
  private $billing_address_id;                                                  //Id de la dirección de factura
  private $coupon_code;                                                         //Código descuento
  private $customer_email;                                                      //Email del cliente
  private $customer_firstname;                                                  //Nombre del cliente
  private $customer_group_id;                                                   //Id del grupo de clientes
  private $customer_id;                                                         //Id del cliente
  private $customer_is_guest;                                                   //Es invitado
  private $customer_lastname;                                                   //Apellidos del cliente
//  private $customer_taxvat;                                                     //NIF / CIF del cliente
  private $discount_amount;                                                     //Descuento
  private $discount_description;                                                //Descripción del descuento
  private $email_sent;                                                          //Se ha enviado correo al cliente
  private $entity_id;                                                           //Entidad del pedido
  private $extension_attributes;                                                //Extension attributes
  private $global_currency_code;                                                //Moneda (EUR)
  private $grand_total;                                                         //Total
  private $increment_id;                                                        //Referencia del pedido
  private $is_virtual;                                                          //El pedido es virtual
  private $items;                                                               //Articulos del pedido
  private $order_currency_code;                                                 //Código de moneda
  private $payment;                                                             //Información del pago
  private $remote_ip;                                                           //IP del pedido
//  private $shipping_amount;                                                     //Importe de los Portes
  private $shipping_description;                                                //Método de envío
  private $shipping_discount_amount;                                            //Descuento sobre los portes
  private $shipping_incl_tax;                                                   //Portes sin IVA
  private $shipping_tax_amount;                                                 //IVA de los portes
  private $state;
  private $status;
  private $status_histories;                                                    //Historial del pedido
  private $store_id;                                                            //Id de la tienda
  private $store_name;                                                          //Nombre de la tienda
  private $subtotal;                                                            //Subtotal
  private $subtotal_incl_tax;                                                   //Subtotal con iva
  private $tax_amount;                                                          //Importe del IVA
  private $total_due;                                                           //Total a pagar
  private $total_item_count;                                                    //Número total de artículos
  private $total_qty_ordered;                                                   //Número total de artículos pedidos
  private $updated_at;                                                          //Fecha actualizacion
  private $weight;                                                              //Peso
  private $forma_pago;                                                          //Forma de pago


public function __construct($row) {
//    echo "<pre>";
//    print_r($row);
//    echo "</pre>";
//Generamos la forma de Pago
$bloque_pago = $row->extension_attributes->payment_additional_info;
$forma_pago = 'desconocida';
foreach ($bloque_pago as $entrada) {
if ($entrada->key == 'method_title') {
$forma_pago = $entrada->value;
}
}


  $this->fecha_creado = $row->created_at;
  $this->id = $row->increment_id;
  $this->tienda = $row->store_name;
  $this->estado = $row->status;
  $this->total_pedido = $row->grand_total;
  $this->forma_pago = $forma_pago;
  $this->portes_pedido = $row->shipping_amount;
//  $this->nif = isset($row->customer_taxvat) ? $row->customer_taxvat : '';
  //$this->direccion_factura = $row->billing_address;
  //$this->direccion_envio = isset($row->extension_attributes->shipping_assignments[0]->shipping->address) ? $row->extension_attributes->shipping_assignments[0]->shipping->address : '';

  //$this->lineas_pedido = $row->items;
$lineas = array_filter($row->items, function($a){
  return $a->product_type == 'simple'; //Filtramos y dejamos solo los productos simples
});
$obj_lin_ped = array(); //Declaramos el array donde van los artículos
  foreach ($lineas as $lin_ped) {
 //Necesitamos ver la manera de fusionar la información para las lineas de configurables 
$articulo = property_exists($lin_ped,'parent_item') ? $lin_ped->parent_item : $lin_ped;

  $arr_lin_ped = array(
    //    'cancelaciones' => '',
     //   'importe' => $articulo->row_total,
        'importe' => $articulo->price, //El precio por unidad
    //    'promociones' => '',
        'cantidad' => $articulo->qty_ordered,
    //    'abonos' => '',
    //    'portes' => '',
        'impuestos' => $articulo->tax_amount,
    //    'total_linea' => '',
    //    'historico' => '',
    //    'cod_categoria' => '',
    //    'etiqueta_categoria' => '',
        'sku' => $lin_ped->sku,
        'nombre' => $lin_ped->name,
    //    'id' => '',
    //    'sku_oferta' => '',
      //  'estado' => '',
    //    'comision' => '',
    'product_type' => $lin_ped->product_type
      );
      //echo "<pre>";
//print_r($lin_ped);
//echo "</pre>";

  $obj_lin_ped[] = new Linea_Pedido_mage($arr_lin_ped);




  }
  $this->lineas_pedido = $obj_lin_ped;


//Preparamos los campos para la direccion de facturacion
$empresa_fra = isset($row->billing_address->company) ? $row->billing_address->company : '';
$nif_fra = isset($row->billing_address->vat_id) ? $row->billing_address->vat_id : '';
$region = isset($row->billing_address->region) ? $row->billing_address->region : '';
  $obj_dir_fra = array(
    'nombre_completo' => $row->billing_address->firstname . ' ' . $row->billing_address->lastname,
    'nif' => $nif_fra,
    'empresa' => $empresa_fra,
    'direccion' => join($row->billing_address->street),
    'ciudad' => $row->billing_address->city,
    'codigo_postal' => $row->billing_address->postcode,
    'telefono' => $row->billing_address->telephone,
    'provincia' => $region,
    'email' => $row->billing_address->email
  );
  if (isset($row->billing_address->company)) {                                  //Si tenemos el campo "empresa" lo añadimos al array
    $obj_dir_fra['empresa'] = $row->billing_address->company;
  }
  $this->direccion_factura = new direccion($obj_dir_fra);
//CAmbiamos esto para priorizar el CIF/NIF de la dirección de facturación
//$this->nif = $this->direccion_factura->getNif() == '' ? clientes::formatearNIF($this->direccion_factura->getNif()) : clientes::formatearNIF($row->customer_taxvat);                                //Sacamos el NIF fuera de la direccion para estandarizar con pcc
$this->nif = isset($row->customer_taxvat) ? clientes::formatearNIF($row->customer_taxvat) : clientes::formatearNIF($this->direccion_factura->getNif());                                //Sacamos el NIF fuera de la direccion para estandarizar con pcc
$this->nombre_apellidos = $this->direccion_factura->getNombreCompleto();                                //Sacamos el nombre completo fuera de la direccion para estandarizar con pcc



$dir_envio = isset($row->extension_attributes->shipping_assignments[0]->shipping->address) ? $row->extension_attributes->shipping_assignments[0]->shipping->address : '';
if ($dir_envio != '') {


//  $this->direccion_envio = isset($row->extension_attributes->shipping_assignments[0]->shipping->address) ? $row->extension_attributes->shipping_assignments[0]->shipping->address : '';
$empresa_envio = isset($dir_envio->company) ? $dir_envio->company : '';
$nif_envio = isset($dir_envio->vat_id) ? $dir_envio->vat_id : '';
  //Preparamos ahora la direccion de envío
  $obj_dir_env = array(
    'nombre_completo' => $dir_envio->firstname . ' ' . $dir_envio->lastname,
    'nif' => $nif_envio,
    'empresa' => $empresa_envio,
    'direccion' => join($dir_envio->street),
    'ciudad' => $dir_envio->city,
    'codigo_postal' => $dir_envio->postcode,
    'telefono' => $dir_envio->telephone,
    'provincia' => $dir_envio->region,
    'email' => $dir_envio->email
  );
  if (isset($dir_envio->company)) {                                  //Si tenemos el campo "empresa" lo añadimos al array
    $obj_dir_env['empresa'] = $dir_envio->company;
  }
  $this->direccion_envio = new direccion($obj_dir_env);
} else {
  $this->direccion_envio = $this->direccion_factura;
}

$this->DirEnvioIgualFactura = self::getDirEnvioIgualFactura();
}
public function getFormaPago()
{
    return $this->forma_pago;
}






public function jsonSerialize():mixed {

  return [
'fecha_creado' => self::getFechaCreado(),
'id' => self::getId(),
'estado' => self::getEstado(),
  'total_pedido' => self::getTotalPedido(),
  'lineas_pedido' => self::getLineasPedido(),
  'nif' => self::getNif(),
  'direccion_factura' => self::getDireccionFactura(),
  'direccion_envio' => self::getDireccionEnvio(),
  'dir_envio_igual_factura' => self::getDirEnvioIgualFactura(),
  'nombre_apellidos' => self::getNombreApellidos(),
  'forma_pago' => self::getFormaPago(),
  'portes_pedido' => self::getPortesPedido(),
  'tienda' => self::getTienda()
  ];
}


}

 ?>

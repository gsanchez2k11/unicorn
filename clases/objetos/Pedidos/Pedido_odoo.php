<?php
namespace unicorn\clases\objetos\Pedidos;

require_once('Pedido_venta.php');
require_once RAIZ . '/clases/funciones/odoo/Conectar.php';

use unicorn\clases\objetos\Pedidos\Pedido_venta as pedido_venta;
use unicorn\clases\funciones\odoo\Conectar;
use JsonSerializable;

/**
 *
 */
class Pedido_odoo extends Pedido_venta implements JsonSerializable
{
//private int $id;                    //id del pedido 
private string $name;               //referencia del pedido
private bool $origin;               //
private bool $client_order_ref;     //referencia proporcionada por el cliente
private bool $reference;            //
private string $state;              //estado (draft,etc)
private string $date_order;         //hora y fecha del pedido
private string $validity_date;      //fecha de caducidad
private bool $is_expired;           //caducado (si/no)
private bool $require_signature;    //firma obligatoria
private bool $require_payment;      //pago obligatorio
private string $create_date;        //hora y Fecha de creación (creo que es la fecha en la que se guarda)
private array $user_id;             //Usuario que ha creado el pedido (id,nombre)
private array $partner_id;          //Cliente del pedido (id, nombre)
private array $partner_invoice_id;  //Contacto de facturación del pedido  (id, nombre)
private array $partner_shipping_id; //Contacto para enviar el pedido (id, nombre)
private array $pricelist_id;        //Tarifa del cliente (id, nombre)
private array $currency_id;         //Moneda (id, nombre)
private bool $analytic_account_id;  //
private array $order_line;          //Lineas del pedido (ids)
private int $invoice_count;         //Intuyo que número de facturas de este pedido
private array $invoice_ids;         //Intuyo que id de las facturas de este pedido
private string $invoice_status;     //Facturado ("no"/"yes")
private string $note;               //Notas del pedido
private float $amount_untaxed;      //Total sin impuestos
private array $amount_by_group;     //Importe por grupos 
private float $amount_tax;          //Importe de los impuestos
private float $amount_total;        //Total con impuestos
private int $currency_rate;         //
private array $payment_term_id;     //Plazo de pago (id, nombre)
private array $fiscal_position_id;  //Regimen (id, nombre) Por defecto regimén nacional
private array $company_id;          //Compañia (id, nombre) (Futura)
private array $team_id;             //Equipo de ventas (id, nombre)
private bool $signature;            //Firma
private bool $signed_by;            //Firmado por
private bool $signed_on;            //Fecha de firma
private bool $commitment_date;      //
private float $amount_undiscounted; //Importe sin descuentos
private string $type_name;          //Tipo de documento (Quotation, etc)
private array $transaction_ids;     //
private array $authorized_transaction_ids; //
private bool $show_update_pricelist;
private array $tag_ids;             //Etiquetas del pedido
private array $payment_mode_id;     //Forma de pago (id, nombre)
private bool $force_invoiced;       //
private bool $sale_order_template_id; //
private array $sale_order_option_ids; //
private int $purchase_order_count;    //
private bool $incoterm;             //
private string $picking_policy;     //(direct,etc)
private array $warehouse_id;        //Almacén (id, nombre)
private array $picking_ids;         //Recogidas?
private int $delivery_count;        //Número de envíos
private bool $procurement_group_id; 
private bool $effective_date;
private string $expected_date;
private string $json_popover;       
private bool $show_json_popover;
private bool $carrier_id;
private bool $delivery_message;
private bool $delivery_rating_success;
private bool $delivery_set;
private bool $recompute_delivery_price;
private bool $is_all_service;
private bool $intrastat_transport_id;
private string $intrastat;
private int $commission_total;
private array $partner_agent_ids;
private bool $opportunity_id;
private int $early_payment_discount;
private float $margin;              //Beneficio del pedido
private float $margin_percent;      //Porcentaje de margen
private int $mrp_production_count;
private array $type_id;             //Tipo de pedido (linea de negocio)
private array $tasks_ids;           
private int $tasks_count;           
private bool $visible_project;
private array $project_ids;
private float $net_margin;
private float $net_margin_percent;
private array $timesheet_ids;
private int $timesheet_count;
private bool $project_id;
private array $timesheet_encode_uom_id;
private int $timesheet_total_duration;
private bool $campaign_id;
private bool $source_id;
private bool $medium_id;
private array $activity_ids;
private bool $activity_state;
private bool $activity_user_id;
private bool $activity_type_id;
private bool $activity_type_icon;
private bool $activity_date_deadline;
private bool $my_activity_date_deadline;
private bool $activity_summary;
private bool $activity_exception_decoration;
private bool $activity_exception_icon;
private bool $message_is_follower;
private array $message_follower_ids;
private array $message_partner_ids;
private array $message_channel_ids;
private array $message_ids;
private bool $message_unread;
private int $message_unread_counter;
private bool $message_needaction;
private int $message_needaction_counter;
private bool $message_has_error;
private int $message_has_error_counter;
private bool $message_attachment_count;
private bool $message_main_attachment_id;
private array $website_message_ids;
private bool $message_has_sms_error;
private string $access_url;                 //Url del pedido ("/my/orders/id)
private bool $access_token;
private string $access_warning;
private string $display_name;           //Nombre del pedido (igual que la referencia)
private array  $create_uid;
private array $write_uid;
private string $write_date;
private string $__last_update;
private string $x_studio_estado_operacin;



  function __construct($row)
  {
$this->id = $row['name'];
$this->fecha_creado = $row['create_date'];
$this->estado = $row['state'];
$this->direccion_factura = $row['partner_invoice_id'];
$this->direccion_envio = $row['partner_shipping_id'];
//$this->nif = $row['partner_shipping_id'];
$this->nombre_apellidos = $row['partner_id'][1];
$this->total_pedido = $row['amount_total'];
$this->lineas_pedido = $row['order_line'];
$this->tienda = $row['type_id'];
  }



    public function jsonSerialize():mixed {

      return [
        'id' => self::getId(),
        'fecha_creado' => self::getFechaCreado(),
'estado' => self::getEstado(),
'direccion_factura' => self::getDireccionFactura(),
'direccion_envio' => self::getDireccionEnvio(),
//'nif' => self::getNif(),
'nombre_apellidos' => self::getNombreApellidos(),
'total_pedido' => self::getTotalPedido(),
'lineas_pedido' => self::getLineasPedido(),
'tienda' => self::getTienda(),
      ];
    }



    public function getLineasPedido()
    {
      $salida = array();
    foreach ($this->lineas_pedido as $linea) {
      //Para evitar hacer tantas llamadas devolvemos un objeto con los datos en blanco
      $salida[] = array(
        'cantidad' => '-',
        'nombre' => '-',
        'mpn' => '-',
        'id' => $linea
              );
    }
        return $salida;
    }

    public function getDireccionEnvio(){
      //Como ya tenemos el nombre no pedimos los datos para ahorrar llamadas
       /* $datos_cliente = Conectar::busqueda('id',$this->direccion_factura[0],'res.partner');
        echo '<pre>';
        print_r($datos_cliente);
        echo '</pre>';*/
        $salida = array(
          'id' => $this->direccion_envio[0],
          'nombre_completo' => $this->direccion_envio[1]
        );
        return $salida;
      }


public function getDireccionFactura(){
//Como ya tenemos el nombre no pedimos los datos para ahorrar llamadas
 /* $datos_cliente = Conectar::busqueda('id',$this->direccion_factura[0],'res.partner');
  echo '<pre>';
  print_r($datos_cliente);
  echo '</pre>';*/
  $salida = array(
    'id' => $this->direccion_factura[0],
    'nombre_completo' => $this->direccion_factura[1]
  );
  return $salida;
}

/**
 * Get the value of name
 */ 
public function getName()
{
return $this->name;
}

/**
 * Get the value of date_order
 */ 
public function getDate_order()
{
return $this->date_order;
}

/**
 * Get the value of partner_id
 */ 
public function getPartner_id()
{
return $this->partner_id;
}

/**
 * Get the value of partner_invoice_id
 */ 
public function getPartner_invoice_id()
{
return $this->partner_invoice_id;
}

/**
 * Get the value of partner_shipping_id
 */ 
public function getPartner_shipping_id()
{
return $this->partner_shipping_id;
}

/**
 * Get the value of pricelist_id
 */ 
public function getPricelist_id()
{
return $this->pricelist_id;
}

/**
 * Get the value of order_line
 */ 
public function getOrder_line()
{
return $this->order_line;
}


}


 ?>

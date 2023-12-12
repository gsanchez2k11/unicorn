<?php
namespace unicorn\clases\objetos\Pedidos;
/**
 *
 */
 use JsonSerializable;
  require_once 'Linea_Pedido_venta.php';
 //use unicorn\clases\funciones\unicorn_db\Entidad as entidad;

 //require_once RAIZ . '/clases/funciones/unicorn_db/Entidad.php';

class Linea_Pedido_mage extends Linea_Pedido_venta implements JsonSerializable
{
private $base_original_price;
private $base_price;
private $base_price_incl_tax;
private $base_row_invoiced;
private $base_row_total;
private $base_row_total_incl_tax;
private $base_tax_amount;
private $created_at;
private $discount_amount;
private $discount_invoiced;
private $discount_percent;
private $free_shipping;
private $discount_tax_compensation_amount;
private $is_qty_decimal;
private $is_virtual;
private $item_id;
private $name;
private $no_discount;
private $order_id;
private $original_price;
private $price;
private $price_incl_tax;
private $product_id;
private $product_type;
private $qty_canceled;
private $qty_invoiced;
private $qty_ordered;
private $qty_refunded;
private $qty_shipped;
private $quote_item_id;
private $row_invoiced;
private $row_total;
private $row_total_incl_tax;
private $row_weight;
//private $sku;
private $store_id;
private $tax_amount;
private $tax_invoiced;
private $tax_percent;
private $updated_at;
private $weight;

  function __construct($row)
  {
$this->cantidad = $row['cantidad'];
$this->nombre = $row['nombre'];
$this->sku = $row['sku'];
$this->mpn = $row['sku'];
$this->product_type = $row['product_type'];
$this->importe = $row['importe'];
$this->impuestos = $row['impuestos'];
  }

public function getMpn() {
    return $this->mpn;
}

public function getProductType() {
    return $this->product_type;
}


    public function jsonSerialize():mixed {
      return [
        'sku' => self::getSku(),
        'nombre' => self::getNombre(),
        'cantidad' => self::getCantidad(),
        'atributos_bd'  => self::getAtributosBd(),
        'mpn' => self::getMpn(),
        'product_type' => self::getProductType(),
        'importe' => self::getImporte(),
        'impuestos' => self::getImpuestos()
      ];
    }



}


 ?>

<?php
namespace unicorn\clases\objetos\Pedidos;
/**
 *
 */
 use JsonSerializable;
  require_once 'Linea_Pedido_venta.php';
 //use unicorn\clases\funciones\unicorn_db\Entidad as entidad;

 //require_once RAIZ . '/clases/funciones/unicorn_db/Entidad.php';

class Linea_Pedido_odoo extends Linea_Pedido_venta implements JsonSerializable
{
private $base_original_price;


  function __construct($row)
  {
    $this->cantidad = $row['cantidad'];
    $this->nombre = $row['nombre'];
    $this->sku = $row['sku'];
    $this->importe = $row['importe'];
    $this->impuestos = $row['impuestos'];
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

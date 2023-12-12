<?php
namespace unicorn\clases\objetos\Pedidos;
/**
 *
 */
 use JsonSerializable;

 require_once 'Linea_Pedido_venta.php';

class Linea_Pedido_fnac extends Linea_Pedido_venta implements JsonSerializable
{

  function __construct($row)
  {
$this->cantidad = $row['cantidad'];
$this->sku = $row['product_fnac_id'];
$this->nombre = $row['nombre'];
$this->importe = $row['importe'];
$this->impuestos = $row['impuestos'];
  }






    public function jsonSerialize():mixed {
      return [
      ];
    }



}


 ?>

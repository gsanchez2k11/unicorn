<?php
namespace unicorn\clases\objetos\Pedidos;
require_once('Linea_Pedido_fnac.php');
require_once('Pedido_venta.php');
use unicorn\clases\objetos\Pedidos\Linea_Pedido_fnac as linea_pedido_fnac;
use unicorn\clases\objetos\Pedidos\Pedido_venta as pedido_venta;
//use unicorn\clases\objetos\Direccion\Direccion as direccion;
use JsonSerializable;
/**
 *
 */
class Pedido_fnac extends Pedido_venta implements JsonSerializable
{
//private $estado;


public function __construct($row) {
$lineas = $row['lineas_pedido'];
foreach ($lineas as $key => $value) {
$arr = array(
  'estado' => (string)$value->state,
  'nombre' => (string)$value->product_name,
  'cantidad' => (string)$value->quantity,
  'importe' => (string)$value->price,
  'impuestos' => (string)$value->fees,
  'product_fnac_id' => (string) $value->product_fnac_id,
  'mpn' => (string) $value->offer_seller_id,
  'portes' => (string) $value->shipping_price,
  'creado' => (string) $value->created_at
);
$obj_articulo[] = new linea_pedido_fnac($arr);
}

    $this->fecha_creado = $row['creado'];
$this->id = $row['id_pedido'];
$this->lineas_pedido = $obj_articulo;
$this->estado = $row['estado'];
}



    public function jsonSerialize():mixed {

      return [
      ];
    }




}

 ?>

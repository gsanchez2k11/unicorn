<?php
namespace unicorn\clases\funciones\odoo;
error_reporting(E_ALL);                                     //Activamos el reporte de errores
ini_set('display_errors', 'on');
require_once 'Conectar.php';
//use unicorn\clases\funciones\odoo\Conectar as conectar;
/**
 *
 */
class Presupuestos_venta extends Conectar
{

/**
 * Buscamos un presupuesto a partir de la referencia del pedido en la plataforma de venta
 * @param  [type] $ref [description]
 * @return [type]      [description]
 */
public static function busquedaPorRefCliente($ref){
  $campo = 'name';
  $valor = $ref;
  $id = Conectar::like($campo,$valor,'sale.order.line');

  if (empty($id)) {                                                               //Si no encontramos ninguna linea buscamos en notas
    $campo = 'note';
    $valor = $ref;
    $id = Conectar::like($campo,$valor,'sale.order');
  }

return $id;
}


}


 ?>

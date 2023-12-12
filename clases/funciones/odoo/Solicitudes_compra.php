<?php
namespace unicorn\clases\funciones\odoo;
error_reporting(E_ALL);                                     //Activamos el reporte de errores
ini_set('display_errors', 'on');
include 'Conectar.php';
/**
 *
 */
class Solicitudes_compra extends Conectar
{

  public static function buscarSolicitud($campo,$valor){

    $cliente = self::conectar();                                                //Conectamos con el servidor
$criterios[] = array(
  $campo,
  '=',
  $valor
);
$ids = $cliente->search('purchase.order', $criterios, 0, 10);         //Buscamos el correo
//    $fields = ['name', 'email', 'customer','mobile','vat'];
$articulos = $cliente->read('purchase.order', $ids/*, $fields*/);
    return $articulos;
  }

  public static function crearSolicitud($solicitud){
  $cliente = self::conectar();
  $id = $cliente->create('purchase.order', $solicitud);
return $id;
  }

  public static function buscarLineaCompra($campo,$valor){
    $cliente = self::conectar();                                                //Conectamos con el servidor
$criterios[] = array(
  $campo,
  '=',
  $valor
);
$ids = $cliente->search('purchase.order.line', $criterios, 0, 10);         //Buscamos el correo
//    $fields = ['name', 'email', 'customer','mobile','vat'];
$articulos = $cliente->read('purchase.order.line', $ids/*, $fields*/);
    return $articulos;
  }

  public static function crearLineaCompra($linea){
  $cliente = self::conectar();
  $id = $cliente->create('purchase.order.line', $linea);
return $id;
  }




}






 ?>

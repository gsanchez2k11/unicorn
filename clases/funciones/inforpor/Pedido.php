<?php
namespace unicorn\clases\funciones\inforpor;
require_once 'Conectar.php';
require_once 'Otros.php';
require_once RAIZ . '/clases/objetos/Pedidos/Pedido_inforpor.php';
use unicorn\clases\funciones\inforpor\Otros as otros;
use unicorn\clases\objetos\pedidos\Pedido_inforpor as pedido_inforpor;
 ?>
<?php

/**
 *
 */
class Pedido extends Conectar
{

public static function EstadoPedido($row) {
    $cliente = self::Crearcliente();

switch (true) {
  case (isset($row['NumPedCli'])):
    $param                = array('CIF' => self::dameValorC('cif'), 'User' => self::dameValorC('user'), 'Clave' => self::dameValorC('pass'), 'NumPedCli' => $row['NumPedCli']);
    break;
    case (isset($row['NumPedInf'])):
  //  $param                = array('CIF' => self::CIF, 'User' => self::USER, 'Clave' => self::CLAVE, 'NumPedInf' => $row['NumPedInf']);
  $param                = array('CIF' =>self::dameValorC('cif'), 'User' => self::dameValorC('user'), 'Clave' => self::dameValorC('pass'), 'NumPedInf' => $row['NumPedInf']);
      break;
}
  $result               = $cliente->call('EstadoPedido', array('parameters' => $param), '', '', false, true);
  return $result;

}

/**
 * devuelve un Pedido_inforpor object
 * @param [type] $row [description]
 */
public static function EstadoPedidoObj($row) {
 
    $cliente = self::Crearcliente();


switch (true) {
  case (isset($row['NumPedCli'])):
 //   $param                = array('CIF' => self::CIF, 'User' => self::USER, 'Clave' => self::CLAVE, 'NumPedCli' => $row['NumPedCli']);
    $param                = array('CIF' =>self::dameValorC('cif'), 'User' => self::dameValorC('user'), 'Clave' => self::dameValorC('pass'), 'NumPedCli' => $row['NumPedCli']);

    break;
    case (isset($row['NumPedInf'])):
   // $param                = array('CIF' => self::CIF, 'User' => self::USER, 'Clave' => self::CLAVE, 'NumPedInf' => $row['NumPedInf']);
    $param                = array('CIF' =>self::dameValorC('cif'), 'User' => self::dameValorC('user'), 'Clave' => self::dameValorC('pass'), 'NumPedInf' => $row['NumPedInf']);

      break;
}
  $result               = $cliente->call('EstadoPedido', array('parameters' => $param), '', '', false, true);
  // echo "<pre>";
  //print_r($result);
  //echo "</pre>";
  $obj = new Pedido_inforpor($result['EstadoPedidoResult']);
  //echo "<pre>";
  //print_r($obj);
  //echo "</pre>";
  return $obj;

}

/**
 * devuelve los pedidos de inforpor filtrados por fecha, hay que pasarle el parametro fechaIni o fechaFin obligatoriamente, por defecto le vamos a pasar los del último mes
 * @param [type] $row [description]
 */
public static function ListaPedidos(array $row = []) {
  if ($row == []) { //Si recibimos el array vacío o no lo recibimos
  $fecha = new \DateTime('now');
  $fecha->sub(new \DateInterval('P5D'));
  $fecha_inicio = $fecha->format('Y-m-d'); //Stringo con la fecha actual menos un mes
  }
  $cliente = self::Crearcliente();
$param                = array('CIF' =>self::dameValorC('cif'), 'User' => self::dameValorC('user'), 'Clave' => self::dameValorC('pass'), 'fechaini' => $fecha_inicio);
if (isset($row['fechaIni'])) {
  $param['fechaini'] = $row['fechaIni']; //Inforpor jugando con mayúsculas y minúsculas otra vez
}
if (isset($row['fechaFin'])) {
  $param['fechafin'] = $row['fechaFin']; //Inforpor jugando con mayúsculas y minúsculas otra vez
}
$result               = $cliente->call('ListaPedidos', array('parameters' => $param), '', '', false, true);
return $result;

}

public static function ultimosPedidos(int $dias) {
  $fecha = new \DateTime('now');
  $cliente = self::Crearcliente();
  $pedidos = array();
  do {
    $fecha->sub(new \DateInterval('P1D'));
    $fecha_inicio = $fecha->format('Y-m-d'); //Stringo con la fecha actual menos un mes
    $param = array('CIF' =>self::dameValorC('cif'), 'User' => self::dameValorC('user'), 'Clave' => self::dameValorC('pass'), 'fechaini' => $fecha_inicio);
    $result               = $cliente->call('ListaPedidos', array('parameters' => $param), '', '', false, true);
    $pedidos_li = $result['ListaPedidosResult']['PedidosLi'];
    if (count($pedidos_li) > 1|| (count($pedidos_li) === 1 && $pedidos_li['CodErr'] == '0')) {
      foreach ($pedidos_li as $pedido) {
        $pedidos[] = new pedido_inforpor($pedido);
      }
    }
   $dias--;
  } while ($dias > 0);
  /*echo "<pre>";
  print_r($pedidos);
  echo "</pre>";*/
  return $pedidos;
}


/**
 * Busca una referencia de inforpor en un pedido dado
 * @param [type] $codinf [description]
 * @param [type] $pedido [description]
 */
/*public static function BuscaArticuloEnPedido($codinf,$row){
  $custodias = array();
  $ped_inforpor = self::EstadoPedido($row);
  $lineas_ped = otros::ConvertirArrayUniMulti($ped_inforpor['EstadoPedidoResult']['lineasPedR']['LinPedR']);

  foreach ($lineas_ped as $linea) {
if ($codinf == $linea['codinf']) {
$custodias = $linea;
}
  }

$custodias = otros::ConvertirArrayUniMulti($custodias);
  return $custodias;

}*/


public static function BuscaArticuloEnPedidoObj($codinf,$row){
  $custodias = array();
  $ped_inforpor = self::EstadoPedidoObj($row);
  $lineas_ped = $ped_inforpor->getLineasPedR();
/*echo "<pre>";
print_r($lineas_ped);
echo "</pre>";*/

  foreach ($lineas_ped as $linea) {
if ($codinf == $linea->getCodinf()) {
$custodias[] = $linea;
}
  }
  return $custodias;

}




public static function hacerPedido(array $pedido) {
//echo "<pre>";
//print_r($pedido);
//echo "</pre>";

  $cliente = self::Crearcliente();
$param                = array('CIF' =>self::dameValorC('cif'), 'User' => self::dameValorC('user'), 'Clave' => self::dameValorC('pass'), 'Pedido' => $pedido);
//$param                = array('CIF' =>'DISTINF', 'User' => 'BUDI12', 'Clave' => 'S123', 'Pedido' => $pedido);

$result               = $cliente->call('RealizaPedido', array('parameters' => $param), '', '', false, true);
return $result;

}


}


 ?>

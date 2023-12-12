<?php
namespace unicorn\clases\funciones\mirakl;

error_reporting(E_ALL);                                     //Activamos el reporte de errores
ini_set('display_errors', 'on');
//require_once __DIR__ . '/../../../config.php';
require_once(RAIZ . '/vendor/autoload.php');
require_once RAIZ . '/clases/objetos/Pedidos/Pedido_mirakl.php';
require_once RAIZ . '/clases/funciones/unicorn_db/Contadores.php';
require_once RAIZ . '/clases/funciones/unicorn_db/Config.php';
use Mirakl\MMP\Shop\Client\ShopApiClient as Client;
use Mirakl\MMP\Shop\Request\Order\Get\GetOrdersRequest;

use unicorn\clases\objetos\Pedidos\Pedido_mirakl as pedidomirakl;
use unicorn\clases\funciones\unicorn_db\Contadores as contadores;
use unicorn\clases\funciones\unicorn_db\Config as config;
 ?>

 <?php

/**
 *
 */
class Pedidos
{
  private static function sumaContadorPedidos($id){
    $row = array(
      'id' => $id,
      'incremento' => 1
    );
  $incrementa_contador = contadores::incrementaContador($row);
  }
public static function dameUltimosPedidos($plataforma = 'pcc') {

  $url = config::dameValorConfig('url_' . $plataforma);
  $key = config::dameValorConfig('key_' . $plataforma);

 // $client = new FrontApiClient($url, $key);
  $client = new Client($url, $key);


 /* switch ($plataforma) {
    case 'pcc':
  $api_url = APIURLPCCOMPONENTES;
  $api_key = APIKEYPCCOMPONENTES;
      break;
      case 'phh':
    $api_url = APIURLPHONEHOUSE;
    $api_key = APIKEYPHONEHOUSE;
        break;

    default:
    $api_url = APIURLPCCOMPONENTES;
    $api_key = APIKEYPCCOMPONENTES;
      break;
  }
  $client = new Client($api_url, $api_key);*/

  $pedidos_mirakl = array();

  $request = new GetOrdersRequest();
  //$request->setMax(100)->sortDesc();
  $request->setMax(50)->sortDesc(); //Lo bajo a 50 para evitar el timeout
  $result = $client->getOrders($request);
self::sumaContadorPedidos(5);
$pedidos = $result->getItems();
foreach ($pedidos as $pedido) {
$fecha_aceptado = isset($pedido->getData()['acceptance_decision_date']) ? $pedido->getData()['acceptance_decision_date']->format('d-m-Y H:i') : '-';
$fecha_debito =  isset($pedido->getData()['customer_debited_date']) ? $pedido->getData()['customer_debited_date']->format('d-m-Y H:i') : '-';
$dir_factura = isset($pedido->getData()['customer']->getData()['billing_address']) ? $pedido->getData()['customer']->getData()['billing_address']->getData() : '-';
$dir_envio = isset($pedido->getData()['customer']->getData()['shipping_address']) ? $pedido->getData()['customer']->getData()['shipping_address']->getData() : '-';
$nif = isset($pedido->getData()['customer']->getData()['shipping_address']) && isset($pedido->getData()['customer']->getData()['shipping_address']->getData()['additional_info']) ? substr($pedido->getData()['customer']->getData()['shipping_address']->getData()['additional_info'],5) : '-' ;

$arr_pedido = array(
  'fecha_creado' =>  $pedido->getData()['created_date']->format('Y-m-d H:i'),
  'fecha_aceptado' => $fecha_aceptado,
  'fecha_debito' => $fecha_debito,
  'direccion_factura' => $dir_factura,
  'direccion_envio' => $dir_envio,
  'nif' => $nif,
  'nombre_apellidos' => $pedido->getData()['customer']->getData()['firstname'] . ' ' . $pedido->getData()['customer']->getData()['lastname'],
  'lineas_pedido' => $pedido->getData()['order_lines']->getItems(),
  'total_pedido' => $pedido->getData()['total_price'],
  'limite_envio' => $pedido->getData()['shipping_deadline']->format('d-m-Y H:i'),
  'comision' => $pedido->getData()['total_commission'],
  'id' => $pedido->getData()['id'],
  'estado' => $pedido->getData()['status']->getData()['state'],
  'envio' => $pedido->getData()['shipping']->getData()
);
$pedidos_mirakl[] = new pedidomirakl($arr_pedido);
}

return $pedidos_mirakl;

}

public static function dameDetallesPedido($id, $plataforma = 'pcc'){
//Saneamos la id
$id =strtoupper(trim($id));                                                     //Eliminamos los espacios y pasamos a mayusculas
  $pedidos_mirakl = array();
  $url = config::dameValorConfig('url_' . $plataforma);
  $key = config::dameValorConfig('key_' . $plataforma);

 // $client = new FrontApiClient($url, $key);
  $client = new Client($url, $key);

  //$client = new Client(APIURLPCCOMPONENTES, APIKEYPCCOMPONENTES);
  $request = new GetOrdersRequest();
  $request->setOrderIds($id);
  $result = $client->getOrders($request);
self::sumaContadorPedidos(5);
$pedidos = $result->getItems();
foreach ($pedidos as $pedido) {
$fecha_aceptado = isset($pedido->getData()['acceptance_decision_date']) ? $pedido->getData()['acceptance_decision_date']->format('d-m-Y H:i') : '-';
$fecha_debito =  isset($pedido->getData()['customer_debited_date']) ? $pedido->getData()['customer_debited_date']->format('d-m-Y H:i') : '-';
$dir_factura = isset($pedido->getData()['customer']->getData()['billing_address']) ? $pedido->getData()['customer']->getData()['billing_address']->getData() : '-';
$dir_envio = isset($pedido->getData()['customer']->getData()['shipping_address']) ? $pedido->getData()['customer']->getData()['shipping_address']->getData() : '-';
$nif = isset($pedido->getData()['customer']->getData()['shipping_address']) ? substr($pedido->getData()['customer']->getData()['shipping_address']->getData()['additional_info'],5) : '-' ;

$arr_pedido = array(
  'fecha_creado' =>  $pedido->getData()['created_date']->format('d-m-Y H:i'),
  'fecha_aceptado' => $pedido->getData()['acceptance_decision_date']->format('d-m-Y H:i'),
  'fecha_debito' => $fecha_debito,
  'direccion_factura' => $dir_factura,
  'direccion_envio' => $dir_envio,
  'nif' => $nif,
  'nombre_apellidos' => $pedido->getData()['customer']->getData()['firstname'] . ' ' . $pedido->getData()['customer']->getData()['lastname'],
  'lineas_pedido' => $pedido->getData()['order_lines']->getItems(),
  'total_pedido' => $pedido->getData()['total_price'],
  'limite_envio' => $pedido->getData()['shipping_deadline']->format('d-m-Y H:i'),
  'comision' => $pedido->getData()['total_commission'],
  'id' => $pedido->getData()['id'],
  'estado' => $pedido->getData()['status']->getData()['state'],
  'envio' => $pedido->getData()['shipping']->getData()
);
$pedidos_mirakl[] = new pedidomirakl($arr_pedido);
}
return $pedidos_mirakl;
}



public static function aceptarPedido($id_pedido) {

}

public static function preparaTracking($pedido, $estado_pedido) {
  /**
   *
   * Recibimos un Array['EstadoPedidoResult'] con 2 posibles valores para el indice ['CodErr']
   * Mensaje de error o 0. Si es 0 es que estamos recibiendo datos
   * Los pedidos en inforpor entran como "procesando" y con número de expedicion ficticio
   * hay que esperar al siguiente estado que debe ser "enviado" o similar
   */
  $refunds = false;
  $lineas_pedido = $pedido->getData()['order_lines'];
  foreach ($lineas_pedido as $linea_pedido) {
    if (!empty($linea_pedido->getData()['refunds']->getItems())) {
      $refunds = true;
    }
  }

if ($refunds == false /*&& $estado_pedido['EstadoPedidoResult']['estado'] == 'SERVIDO' && isset($estado_pedido['EstadoPedidoResult']['Agencia']) */) {

    $agencia = $estado_pedido['EstadoPedidoResult']['Agencia'];
    switch ($agencia) {
      case 'CHRONOEXPRESS':
      case 'CHRONOEXPRES':
    $code = 'correosexpress-api';
    $label = 'Correos Express';
    $url = 'https://track.pccomponentes.com/{trackingId}';
        break;
        case 'DHL':
      $code = 'dhlparcel-es';
      $label = 'DHL';
      $url = 'https://track.pccomponentes.com/{trackingId}';
          break;
          case 'SEUR':
        $code = 'spanish-seur';
        $label = 'Seur';
        $url = 'https://track.pccomponentes.com/{trackingId}';
            break;

      default:
    $code = $agencia;
    $label = '';
    $url = '';
        break;
    }
  //  $expedicion = !empty($estado_pedido['EstadoPedidoResult']['expedicion']) && $estado_pedido['EstadoPedidoResult']['expedicion'] != ' ' ? '|' . $estado_pedido['EstadoPedidoResult']['expedicion'] . '|': '000000';
//Comprobamos si tenemos expedición
if (!empty($estado_pedido['EstadoPedidoResult']['expedicion']) && $estado_pedido['EstadoPedidoResult']['expedicion'] != ' ') {
  $expedicion = $estado_pedido['EstadoPedidoResult']['expedicion'];
  if ($code == 'spanish-seur' && preg_match('/^01\s+AV-\d{2}-\d{6}$/',$expedicion)) {
    $expedicion = trim(substr($expedicion,2));
  }
  $datos_pedido = array (
    'referencia' => $pedido->getData()['id'],
    'code' => $code,
    'label' => $label,
    'tracking_url' => $url,
    'expedicion' => $expedicion,

  );
  echo "<pre>";
  print_r($datos_pedido);
  echo "</pre>";
  $add_tracking = self::addTracking($datos_pedido);
  $resultado = $add_tracking;
} else {
  $resultado = 'no hay expedicion';
}


    return $resultado;
  }


}

}


  ?>

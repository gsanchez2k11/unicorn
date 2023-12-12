<?php

namespace unicorn\clases\funciones\magento;

error_reporting(E_ALL);                                     //Activamos el reporte de errores
ini_set('display_errors', 'on');
//require_once __DIR__ . '/../../../config.php';
require_once(RAIZ . '/vendor/autoload.php');
require_once 'Conectar.php';
require_once RAIZ . '/clases/objetos/Pedidos/Pedido_mage.php';

use unicorn\clases\objetos\Pedidos\Pedido_mage as pedido_mage;


/**
 *
 */
class Pedidos extends Conectar
{

  //-------------------------------------------------------//
  // Obtenemos el listado de pedidos                 //
  //------------------------------------------------------//
  public static function getPedidos(int $id_tienda, $row = false)
  {
    $token = self::getToken($id_tienda);

    if ($row === false) {
      //  $ch = curl_init(self::URL . 'index.php/rest/all/V1/orders?searchCriteria[currentPage]=1&searchCriteria[pageSize]=25&searchCriteria[sortOrders][0][field]=created_at&searchCriteria[sortOrders][0][direction]=DESC');
      $cadena = 'index.php/rest/all/V1/orders?searchCriteria[currentPage]=1&searchCriteria[pageSize]=50&searchCriteria[sortOrders][0][field]=created_at&searchCriteria[sortOrders][0][direction]=DESC';
    } else {
      if (isset($row['entidad_pedido'])) {
        //      $ch = curl_init(self::URL . 'index.php/rest/V1/orders/' . $row['entidad_pedido']);
        $cadena = 'index.php/rest/V1/orders/' . $row['entidad_pedido'];
      } elseif (isset($row['termino'])) {
        //      $ch = curl_init(self::URL . 'index.php/rest/all/V1/orders?searchCriteria[filter_groups][0][filters][0][field]=increment_id&searchCriteria[filter_groups][0][filters][0][value]=' . $row['termino'] . '&searchCriteria[filter_groups][0][filters][0][condition_type]=eq');
        $cadena = 'index.php/rest/all/V1/orders?searchCriteria[filter_groups][0][filters][0][field]=increment_id&searchCriteria[filter_groups][0][filters][0][value]=' . $row['termino'] . '&searchCriteria[filter_groups][0][filters][0][condition_type]=eq';
      } elseif (isset($row['estado'])) {
        //              $ch = curl_init(self::URL . 'index.php/rest/all/V1/orders?searchCriteria[filter_groups][0][filters][0][field]=status&searchCriteria[filter_groups][0][filters][0][value]=' . $row['estado'] . '&searchCriteria[filter_groups][0][filters][0][condition_type]=eq&searchCriteria[pageSize]=100&searchCriteria[sortOrders][0][field]=created_at&searchCriteria[sortOrders][0][direction]=DESC&fields=items[entity_id,grand_total,increment_id,billing_address[firstname,lastname],created_at,status,payment[method],store_name,customer_email,items[item_id,qty_ordered,qty_ordered,qty_invoiced,qty_shipped]]');
        $cadena = 'index.php/rest/all/V1/orders?searchCriteria[filter_groups][0][filters][0][field]=status&searchCriteria[filter_groups][0][filters][0][value]=' . $row['estado'] . '&searchCriteria[filter_groups][0][filters][0][condition_type]=eq&searchCriteria[pageSize]=100&searchCriteria[sortOrders][0][field]=created_at&searchCriteria[sortOrders][0][direction]=DESC&fields=items[entity_id,grand_total,increment_id,billing_address[firstname,lastname],created_at,status,payment[method],store_name,customer_email,items[item_id,qty_ordered,qty_ordered,qty_invoiced,qty_shipped,sku]]';
      }
      if (isset($row['created_at'])) { //Si tenemos una fecha definida la añadimos a la cadena
        $cadena .= '&searchCriteria[filter_groups][1][filters][0][field]=created_at&searchCriteria[filter_groups][1][filters][0][value]=' . $row['created_at'] . '&searchCriteria[filter_groups][1][filters][0][condition_type]=lt';
      }
    }


    $ch = curl_init($token['url'] . $cadena);

    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "Authorization: Bearer " . $token['token']));

    $result = curl_exec($ch);
    // Se cierra el recurso CURL y se liberan los recursos del sistema
    curl_close($ch);
    $result = json_decode($result);


    //Si estamos recibiendo el método de pago filtramos el listado desde este punto
    if (isset($row['payment']['method'])) {
      $metodo =   $row['payment']['method'];
      $result->items = array_filter($result->items, function ($arr) use ($metodo) {
        return $arr->payment->method == $metodo;
      });
    }

    /*Si tenemos items deolvemos la lista de pedidos, en caso contrario devolvemos el resultado tal cual-*/
    /*if(isset($result->items)) {
      return $result->items;
  } else {*/
    return $result;
    /*}*/
  }

  public static function dameUltimosPedidosObj()
  {
    $tiendas = [1, 2]; //Array con las ids de las tiendas
    $ped_obj = array();
    foreach ($tiendas as $tienda) {
      $pedir_pedidos = self::getPedidos($tienda);                                                //Pedimos los últimos pedidos para cada tienda
      //     echo "<pre>";
      //     print_r($pedir_pedidos);
      //    echo "</pre>";
      if (is_object($pedir_pedidos) && property_exists($pedir_pedidos, 'items')) {
        $pedidos = $pedir_pedidos->items;

        foreach ($pedidos as $pedido) {

          $ped_obj[] = new pedido_mage($pedido);
        }
      }
    }

    return $ped_obj;
  }

  /**
   * Devuelve el detalle de un pedido por la id
   */
  public static function infoPedido($id)
  {
    $token = self::getToken();
    $cadena = 'index.php/rest/all/V1/orders/' . $id;

    $ch = curl_init($token['url'] . $cadena);

    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "Authorization: Bearer " . $token['token']));
    $result = curl_exec($ch);
    $result = json_decode($result);
    return $result;
  }

  /**
   * Devuelve el estado de un pedido por la id
   */
  public static function getStatusPedido($id)
  {
    $token = self::getToken();
    $cadena = 'index.php/rest/all/V1/orders/' . $id . '/statuses';

    $ch = curl_init($token['url'] . $cadena);

    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "Authorization: Bearer " . $token['token']));
    $result = curl_exec($ch);
    $result = json_decode($result);
    return $result;
  }


  /**
   * Actualiza el estado de un pedido
   */
  public static function updateStatusPedido($row)
  {
    $token = self::getToken();
    $cadena = 'index.php/rest/all/V1/orders/' . $row['id'] . '/comments';

    $ch = curl_init($token['url'] . $cadena);

    $headers = array(
      'Content-Type: application/json',
      'Authorization: Bearer ' . $token['token'],
    );
    /*
{
"statusHistory": {
"comment": "string",
"created_at": "string",
"entity_id": 0,
"entity_name": "string",
"is_customer_notified": 0,
"is_visible_on_front": 0,
"parent_id": 0,
"status": "string",
"extension_attributes": { }
}
}
*/
    $data = array(
      'statusHistory' => array(
        //  'comment' => 'comentario de prueba'
      )
    );
    $data['statusHistory']['comment'] = $row['comment'] ?? '';
    $data['statusHistory']['is_customer_notified'] = $row['is_customer_notified'] ?? 0;
    $data['statusHistory']['is_visible_on_front'] = $row['is_visible_on_front'] ?? 0;
    $data['statusHistory']['status'] = $row['status'] ?? self::getStatusPedido($row['id']);
    $data = json_encode($data);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "Authorization: Bearer " . $token['token']));
    $result = curl_exec($ch);
    $result = json_decode($result);
    return $result;
  }


  public static function buscarPedidos(array $row)
  {
    $pedidos = array();
    $cadenaB = '';
    $pagesize = $row['pageSize'] ?? '10';
    $currentPage = $row['currentPage'] ?? '1';
    foreach ($row['fields'] as $clave => $criterio) {
      $cadenaB .= '&searchCriteria[filter_groups][' . $clave . '][filters][0][field]=' . $criterio['field'] . '&searchCriteria[filter_groups][' . $clave . '][filters][0][value]=' . $criterio['value'] . '&searchCriteria[filter_groups][' . $clave . '][filters][0][condition_type]=' . $criterio['condition_type'];
    }

    $tiendas = isset($row['idTienda']) ? [$row['idTienda']] : [1, 2];
    foreach ($tiendas as $idTienda) {
      $token = self::getToken($idTienda);
      $cadena = $token['url'] . 'index.php/rest/V1/orders/?';
      $cadena .= '&searchCriteria[pageSize]=' . $pagesize; // añadimos el tamaño de página también
      $cadena .= '&searchCriteria[currentPage]=' . $currentPage; //añadimos el número de página
      $cadena .= $cadenaB;

      $ch = curl_init($cadena);
      curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "Authorization: Bearer " . $token['token']));

      $result = curl_exec($ch);
      $result = json_decode($result);
      if (property_exists($result, 'items')) {
        foreach ($result->items as $pedido) {
          $pedidos[] = new pedido_mage($pedido);
        }
      }
    }
    return $pedidos;
  }
}

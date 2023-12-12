<?php
namespace unicorn\clases\funciones\magento;
require_once 'Conectar.php';

?>
<?php
class StockItem extends Conectar
{
/**
 * Devuelve las entradas de stock para un sku determinado
 *  
 * */    
public static function getStockItem(string $sku,$id_tienda = 1){
    $token = self::getToken($id_tienda);
    $ch    = curl_init($token['url'] . 'index.php/rest/V1/stockItems/' . $sku);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "Authorization: Bearer " . $token['token']));

    $result = curl_exec($ch);
    $result = json_decode($result);

    return $result;

}

/**
 * 
 */
public static function putStockItem(string $sku,$id_tienda = 1){
    $getStockItem = self::getStockItem($sku,$id_tienda);
    $stockItemId = $getStockItem->item_id; //Pedimos el item_id
    $token = self::getToken($id_tienda);
 $ch    = curl_init($token['url'] . 'index.php/rest/V1/products/' . urlencode($sku) . '/stockItems/' . $stockItemId);
    $headers = array(
        'Content-Type: application/json',
        'Authorization: Bearer ' . $token['token'],
    );
    $arr = array(
        'stock_item' => array(
            'item_id' => $stockItemId,
            'qty' => 69,
            'is_in_stock' => 1

        )
    );
    $data = json_encode($arr);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($ch);

    $result = json_decode($result);

    return $result;

}

public static function putStockItemByEntry(array $articulo,$id_tienda = 1){
   // $getStockItem = self::getStockItem($sku,$id_tienda);
  //  $stockItemId = $getStockItem->item_id; //Pedimos el item_id
    $token = self::getToken($id_tienda);
 $ch    = curl_init($token['url'] . 'index.php/rest/V1/products/' . urlencode($articulo['stock_item']['sku']) . '/stockItems/' . $articulo['stock_item']['stock_item_id']);
    $headers = array(
        'Content-Type: application/json',
        'Authorization: Bearer ' . $token['token'],
    );
    $arr = array(
        'stock_item' => array(
            'item_id' => $articulo['stock_item']['stock_item_id'],
            'qty' => $articulo['stock_item']['qty'],
            'is_in_stock' => $articulo['stock_item']['is_in_stock']

        )
    );
    $data = json_encode($arr);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($ch);

    $result = json_decode($result);

    return $result;

}

public static function putBulkStockItem(array $arr,$id_tienda = 1){
 //   $getStockItem = self::getStockItem($sku,$id_tienda);
  //  $stockItemId = $getStockItem->item_id; //Pedimos el item_id
    $token = self::getToken($id_tienda);
   // $ch    = curl_init($token['url'] . 'index.php/rest/V1/products/' . urlencode($sku) . '/stockItems/' . $stockItemId);
   $ch    = curl_init($token['url'] . 'index.php/rest/async/bulk/V1/products/bySku/stockItems/byEntryId2');
    $headers = array(
        'Content-Type: application/json',
        'Authorization: Bearer ' . $token['token'],
    );
  /*  $arr = array(
        'stock_item' => array(
            'item_id' => $stockItemId,
            'qty' => 64,
            'is_in_stock' => 1

        )
    );*/
    $data = json_encode(array_values($arr));
   // echo $data;
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($ch);

    $result = json_decode($result);

    return $result;

}


/**
 * Actualizamos los stocks a partir de un array de sku y cantidades
 */
public static function putBulkStockItemfromSku(array $articulos,$id_tienda = 1){
    foreach ($articulos as $key => $articulo) {
        $getStockItem = self::getStockItem($articulo['sku'],$id_tienda);
        $articulos[$key]['item_id'] = $getStockItem;
        unset($articulos[$key]['sku']);
    }
    


    return $articulos;

}

public static function getLowStockItems($id_tienda = 1){
    $token = self::getToken($id_tienda);
    $ch    = curl_init($token['url'] . 'index.php/rest/V1/stockItems/lowStock?scopeId=0&qty=100000&pageSize=100000');
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "Authorization: Bearer " . $token['token']));

    $result = curl_exec($ch);
    $result = json_decode($result);

    return $result;
}


}
?>
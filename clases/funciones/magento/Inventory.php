<?php
namespace unicorn\clases\funciones\magento;
require_once 'Conectar.php';

?>
<?php
class Inventory extends Conectar
{
/**
 * Devuelve el listado de inventarios disponibles
 *  
 * */    
public static function getInventoryStocks($id_tienda = 1){
    $token = self::getToken($id_tienda);
    $ch    = curl_init($token['url'] . 'index.php/rest/V1/inventory/stocks');
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "Authorization: Bearer " . $token['token']));

    $result = curl_exec($ch);
    $result = json_decode($result);
$items = $result->items;
    return $items;

}

/**
 *  Devuelve los datos para un inventario determinado por su id
 */
public static function getStockIdData($stock_id,$id_tienda = 1){
    $token = self::getToken($id_tienda);
    $ch    = curl_init($token['url'] . 'index.php/rest/V1/inventory/stocks/' . $stock_id);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "Authorization: Bearer " . $token['token']));

    $result = curl_exec($ch);
    $result = json_decode($result);
//$items = $result->items;
    return $result;

}

/**
 * Devuelve las fuentes de stocks 
 */
public static function getSources($id_tienda = 1){
    $token = self::getToken($id_tienda);
    $ch    = curl_init($token['url'] . 'index.php/rest/V1/inventory/sources');
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "Authorization: Bearer " . $token['token']));

    $result = curl_exec($ch);
    $result = json_decode($result);
$items = $result->items;
    return $items;

}

/**
 * Devuelve los datos para una fuente de stock dada
 */
public static function getSourceData($source_code,$id_tienda = 1){
    $token = self::getToken($id_tienda);
    $ch    = curl_init($token['url'] . 'index.php/rest/V1/inventory/sources/' . $source_code);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "Authorization: Bearer " . $token['token']));

    $result = curl_exec($ch);
    $result = json_decode($result);
//$items = $result->items;
    return $result;

}

/**
 * Sólo magento 2.4
 * Devuelve un listado de artículos y stock para la fuente dada, si no se le pasa una fuente devuelve todos
 */
public static function getSourceItems($source_code = false){
    $token = self::getToken(2); //Sólo funciona con magento 2.4
    $url = $token['url'] . 'index.php/rest/V1/inventory/source-items/?searchCriteria';
if ($source_code !== false) {
   $url .= '[filter_groups][0][filters][0][field]=source_code&searchCriteria[filter_groups][0][filters][0][value]=' . $source_code . '&searchCriteria[filter_groups][0][filters][0][condition_type]=eq';
}
    $ch    = curl_init($url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "Authorization: Bearer " . $token['token']));

    $result = curl_exec($ch);
    $result = json_decode($result);
$items = $result->items;
    return $items;

}

/**
 * Actualiza el stock en magento
 */
public static function postSourceItems(array $stock){
    $token = self::getToken(2); //Sólo funciona con magento 2.4
    $url = $token['url'] . 'index.php/rest/V1/inventory/source-items';
    $ch    = curl_init($url);
    $headers = array(
        'Content-Type: application/json',
        'Authorization: Bearer ' . $token['token'],
    );
    $data = array('sourceItems' => $stock);
    $data = json_encode($data);

    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($ch);

    $result = json_decode($result);

    return $result;  

}

/**
 * Actualiza el stock en magento
 */
public static function deleteSourceItems(array $stock){
    $token = self::getToken(2); //Sólo funciona con magento 2.4
    $url = $token['url'] . 'index.php/rest/V1/inventory/source-items-delete';
    $ch    = curl_init($url);
    $headers = array(
        'Content-Type: application/json',
        'Authorization: Bearer ' . $token['token'],
    );
    $data = array('sourceItems' => $stock);
    $data = json_encode($data);

    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($ch);

    $result = json_decode($result);

    return $result;  

}

}
?>
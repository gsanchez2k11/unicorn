<?php
error_reporting(E_ALL);
ini_set('display_errors', 'on');
require_once __DIR__ . '/../../config.php.inc';
require_once(RAIZ . '/vendor/autoload.php');
require_once RAIZ . '/clases/funciones/mirakl/Ofertas.php';
use unicorn\clases\funciones\mirakl\Ofertas as ofertas;
?>
<?php
$articulo = array();
/*echo "<pre>";
print_r($_POST);
echo "</pre>";*/
$id_articulo = $_POST['product_sku'];
$plataforma = isset($_POST['plataforma']) ? $_POST['plataforma'] : 'pcc'; //Si recicibimos la plataforma la configuramos, si no usamos pc componentes
//$id_articulo = '148005';
$ofertas = array();
$result = ofertas::dameInfoOfertas($id_articulo, $plataforma);
if (isset($result['offers'])) {
 $ofertas_items =  $result['offers']->getItems();
foreach ($ofertas_items as $oferta_data) {
$oferta = $oferta_data->getData();
//All prices
foreach ($oferta['all_prices']->getItems() as $all_price) {
  $all_prices[] = array(
    'channel_code' => '',
    'discount_end_date' => '',
    'discount_start_date' => '',
    'price' => $all_price->getData()['price'],
    'unit_discount_price' => '',
    'unit_origin_price' => $all_price->getData()['unit_origin_price'],
    'volume_prices' => ''
  );
}
//Aplicable applicable_pricing
  $applicable_pricing = array(
    'channel_code' => '',
    'discount_end_date' => '',
    'discount_start_date' => '',
    'price' => $oferta['applicable_pricing']->getData()['price'],
    'unit_discount_price' => '',
    'unit_origin_price' => $oferta['applicable_pricing']->getData()['unit_origin_price'],
    'volume_prices' => ''
  );
$oferta_description = isset($oferta['description']) ? $oferta['description'] : '';
$shop_grade = isset($oferta['shop_grade']) ? $oferta['shop_grade'] : '';

$ofertas[] = array(
  'active' => $oferta['active'],
  'allow_quote_requests' => $oferta['allow_quote_requests'],
  'all_prices' => $all_prices,
  'applicable_pricing' => $applicable_pricing,
  'channels' => '',
  'currency_iso_code' => $oferta['currency_iso_code'],
  'description' => $oferta_description,
  'discount' => '',
  'is_professional' => $oferta['professional'],
  'logistic_class' => '',
  'min_quantity_alert' => '',
  'min_shipping_price' => $oferta['min_shipping']->getData()['price'],
  'min_shipping_price_additional' => $oferta['min_shipping']->getData()['price_additional'],
  'min_shipping_type' => $oferta['min_shipping']->getData()['type_code'],
  'min_shipping_zone' => $oferta['min_shipping']->getData()['zone_code'],
  'nb_evaluation' => $oferta['nb_evaluation'],
  'offer_additional_fields' => '',
  'offer_id' => $oferta['offer_id'],
  'premium' => $oferta['premium'],
  'price' => $oferta['price'],
  'price_additional_info' => '',
  'quantity' => $oferta['quantity'],
  'shop_grade' => $shop_grade,
  'shop_id' => '',
  'shop_name' => $oferta['shop_name'],
  'state_code' => $oferta['state_code'],
  'total_price' => $oferta['total_price']
);
}
//Products product_references
//$result['product']->getData()
foreach ($result['product']->getData()['references']->getItems() as $_referencia) {
$product_references[] =  array (
  'reference' => $_referencia->getData()['value'],
  'reference_type' =>$_referencia->getData()['type']
);
}

$product_media = isset($result['product_media']) ? $result['product_media'] : '';

$product_description = isset($result['product_description']) ? $result['product_description'] : '';

$articulo['products'][] = array (
  'category_code' => $result['product']->getData()['category']->getData()['code'],
  'category_label' => $result['product']->getData()['category']->getData()['label'],
  'offers' =>$ofertas,
  'product_brand' =>  $result['product_brand'],
  'product_description' => $product_description, 
  'product_media' =>$product_media,
  'product_references' =>$product_references,
  'product_sku' =>$result['product']->getData()['sku'],
  'product_title' =>$result['product']->getData()['title'],
  'total_count'=>''
);
};
 ?>
<?php //echo '<pre>' ?>
   <?php // print_r($articulo) ?>
   <?php //  print_r($result) ?>
<?php  // echo '</pre>' ?>
<?php
echo json_encode($articulo);
 ?>

<?php
$tiempo_inicial = microtime(true); //true es para que sea calculado en segundos
error_reporting(E_ALL);
ini_set('display_errors', 'on');
require_once __DIR__ . '/../../config.php.inc';
require_once(RAIZ . '/vendor/autoload.php');
//require_once RAIZ . '/clases/funciones/mirakl/Ofertas.php';
//use unicorn\clases\funciones\mirakl\Ofertas as ofertas;
//use unicorn\clases\funciones\unicorn_db\Entidad as entidad;
//require_once RAIZ . '/clases/funciones/unicorn_db/Entidad.php';
require_once RAIZ . '/clases/funciones/unicorn_db/Actualizacion.php';
use unicorn\clases\funciones\unicorn_db\Actualizacion as actualizacion;
require_once RAIZ . '/clases/funciones/otras/Catalogo.php';
use unicorn\clases\funciones\otras\Catalogo as catalogo;
require_once RAIZ . '/clases/funciones/inforpor/Tarifa.php';
use unicorn\clases\funciones\inforpor\Tarifa as tarifa;
?>
<?php
$plataforma = $_GET['plataforma']; //Obtenemos la plataforma sobre la que queremos recuperar los artículos
//Cargamos la última tarifa de inforpor
//$tarifa_inforpor_json = tarifa::dameJSONTarifa(); //Cargamos la última tarifa
//$tarifa_inforpor = json_decode($tarifa_inforpor_json, true, 512); //la convertimos desde el JSON
//$mis_items = catalogo::dameObjetosMirakl($plataforma); //Pedimos el listado de ofertas de pc componentes

$tarifa = file_get_contents(RAIZ . '/var/import/inforpor/tarifa.000');
$tarifa_inforpor = json_decode($tarifa, true, 512); //la convertimos desde el JSON
$mis_items = catalogo::procesaListado($plataforma, $tarifa_inforpor);
//$listado = actualizacion::addEntidadPosicion($mis_items); //Añadimos las posiciones en tiempo real, no es viable, timeout
/*echo '<pre>';
print_r($listado);
echo '</pre>';*/
foreach ($mis_items as $key => $mi_item) {
$mis_items[$key]['DT_RowAttr'] = array(
  'precio' => $mi_item['precio'],
  'stock' => $mi_item['stock'],
  'shop_sku' => $mi_item['shop_sku'],
  'sku_plataforma' => $mi_item['sku_plataforma'],
  'clase_logistica' => $mi_item['clase_logistica'],
  'offer_id' => $mi_item['offer_id'],
  'product_sku' => $mi_item['sku_plataforma'],
  'margen' => $mi_item['margen'],
  'nombre' => $mi_item['nombre'],
  'cat_plataforma' => $mi_item['cat_plataforma'],
  'comision' => $mi_item['comision'],
  'ean' => $mi_item['ean'],
  'posicion' => $mi_item['posicion'],
  'favorito' => $mi_item['info']['favorito'],
  'custodia' => $mi_item['info']['custodia'],
  'comentario' => $mi_item['info']['comentario'],
  'actualizable' => $mi_item['info']['actualizable'],
  'entidad' => $mi_item['entidad'],
  'stock_local' => $mi_item['stock_local'],
  'modificador' => $mi_item['modificador'],
  'en_inforpor' => $mi_item['enIfp'],
  'cod_inforpor' => $mi_item['cod_inforpor']

);
$mis_items[$key]['DT_RowId'] = 'entidad-' . $mi_item['entidad'];
}
/*echo "<pre>";
print_r($mis_items);
echo "</pre>";*/
 ?>
<?php //echo '<pre>' ?>
   <?php // print_r($articulo) ?>
   <?php //print_r($items) ?>
<?php   //echo '</pre>' ?>

<?php
/*$panes = array(
'options' => array(
  'entidad' => array([
    "label"=> "Alexa",
          "total" => "1",
          "value" => "Alexa",
          "count"=> "1"],[
            "label"=> "Alexb",
                  "total" => "1",
                  "value" => "Alexb",
                  "count"=> "1"]
  )
)
);*/
 ?>

<?php
$salida_datatable = array(
  'draw' => '1',
  'recordsTotal' => count($mis_items),
  'recordsFiltered' => count($mis_items),
  'data' => $mis_items,
//  'searchPanes' => $panes
);

//+echo json_encode($salida_datatable);


echo json_encode($salida_datatable);
 ?>
 <?php
 $tiempo_final = microtime(true);
 $tiempo = $tiempo_final - $tiempo_inicial; //este resultado estará en segundos
 //echo "El tiempo de ejecución del archivo ha sido de " . $tiempo . " segundos";


  ?>

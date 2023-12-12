<?php
namespace unicorn\cron\mirakl;
error_reporting(E_ALL);                                     //Activamos el reporte de errores
ini_set('display_errors', 'on');
require_once __DIR__ . '/../../config.php.inc';
require_once(RAIZ . '/vendor/autoload.php');
$tiempo_inicial = microtime(true); //true es para que sea calculado en segundos

require_once RAIZ . '/clases/funciones/inforpor/Stock.php';
require_once RAIZ . '/clases/funciones/otras/Catalogo.php';
require_once RAIZ . '/clases/funciones/otras/Moneda.php';
require_once RAIZ . '/clases/funciones/unicorn_db/Entidad.php';
require_once RAIZ . '/clases/funciones/unicorn_db/Config.php';
require_once RAIZ . '/clases/funciones/mirakl/Ofertas.php';
require_once RAIZ . '/clases/funciones/unicorn_db/Actualizacion.php';

use Mirakl\MMP\Shop\Client\ShopApiClient as Client;
//use Mirakl\MMP\Shop\Request\Offer\GetOffersRequest;
use Mirakl\MMP\Shop\Request\Offer\UpdateOffersRequest;
use unicorn\clases\funciones\otras\Catalogo as catalogo;
use unicorn\clases\funciones\inforpor\Stock as compraInforpor;
use unicorn\clases\funciones\otras\Moneda as moneda;
use unicorn\clases\funciones\unicorn_db\Entidad as entidad;
use unicorn\clases\funciones\unicorn_db\Config as config;
use unicorn\clases\funciones\unicorn_db\Actualizacion as actualizacion;
use unicorn\clases\funciones\mirakl\Ofertas as ofertas;

 ?>

 <?php
 $plataformas = Array('pcc','mediamarkt'); //Lista de las plataformas
 $art_descontar_totales = catalogo::articulosADescontar(); //Pedimos los artículos a los que tenemos que descontar stock

 foreach ($plataformas as $plataforma) { //Recorremos las distintas plataformas
  $listado_ofertas = catalogo::dameObjetosMirakl($plataforma); //Pedimos el listado de ofertas de pc componentes 
  //$listado_ofertas = array_chunk($listado_ofertas,20)[0]; //limitamos a 50 el array para las pruebas
  //echo '<pre>';
  //print_r($listado_ofertas);
  //echo '</pre>';
//Recorremos ahora el listado de ofertas recibidas
array_walk($listado_ofertas,function (&$oferta, $key) use ($plataforma){
  $stock_margen = comprainforpor::generaStockMargen($oferta);
  $oferta = array_merge($oferta,$stock_margen);
  actualizaDatosBd($stock_margen,$oferta,$plataforma); //Actualizamos los datos en la bbdd
  });
 /******************* PREPARAMOS EL ARRAY PARA ACTUALIZAR***********************/

 $array_actualizacion = array_map(function($articulo) {
  $nuevo = array (
  //  'product_id' => $articulo['mpn'],
   // 'product_id_type' => 'MPN',
    'product_id' => $articulo['sku_plataforma'],
    'product_id_type' => 'SKU',
    'shop_sku' => $articulo['shop_sku'],
    'logistic_class' => $articulo['clase_logistica'],
    'quantity' => $articulo['cantidad'],
    'state_code' => $articulo['state_code'],
    'price' => $articulo['precio'],
    'update_delete' => 'update',
  //  'hora-corte' => '18:00'
  );
  if (isset($articulo['oferta'])) {
   $nuevo['discount']['origin_price'] = $articulo['oferta']['precio_inicial'];
   $nuevo['discount']['discount_price'] = $articulo['oferta']['precio_final'];
   $nuevo['discount']['origin_price'] = $articulo['oferta']['start_date'];
   $nuevo['discount']['origin_price'] = $articulo['oferta']['end_date'];
  }
  return $nuevo;
} ,$listado_ofertas);

//Para cada plataforma añadimos los campos personalizados que necesitemos
if ($plataforma == 'pcc') {
foreach ($array_actualizacion as $k => $elemento) {
  $array_actualizacion[$k]['hora-corte'] = '18:00';
  
}
}
/*if ($plataforma == 'mediamarkt') {
  foreach ($array_actualizacion as $k => $elemento) {
    $array_actualizacion[$k]['product_id_type'] = 'SKU';
  }
  }*/
actualizaOfertasMP($array_actualizacion, $listado_ofertas, $plataforma);

  //echo '<pre>';
  //print_r($array_actualizacion);
  //echo '</pre>';
 } //Fin Foreach plataforma

function actualizaOfertasMP($array_actualizacion,$listado_ofertas,$plataforma) {
  $apiUrl = config::dameValorConfig('url_' . $plataforma);
  $apiKey = config::dameValorConfig('key_' . $plataforma);
  $idTienda = config::dameValorConfig('id_' . $plataforma);
  $cliente = new Client($apiUrl, $apiKey);
  $request = new UpdateOffersRequest(array($idTienda));
  $request->setOffers($array_actualizacion);
$result = $cliente->updateOffers($request);

$datos_alertas    = array(
  'plataforma' => $plataforma,
  'tipo' => 'stock',
  'campo_1' => base64_encode(serialize($listado_ofertas)),
  'visto' => 1,
  'num_modificados' => count($listado_ofertas)
);
$insertar = actualizacion::addActualizacion($datos_alertas);
}

function actualizaDatosBd($stock,$oferta,$plataforma){
  //Atributo no hay compra
  $hay_compra = $stock['fuente'] == 'no hay compra' ? 1 : 0;
  entidad::insertaArticuloEntidadInt($oferta['entidad'],29,$hay_compra,'articulos_entidad_int'); //Grabamos en la bbdd  si tiene o nofuente de compras
  //SKU para esa plataforma
  $id_attr = entidad::dameIdAtributo('sku_' . $plataforma);
  $row = array(
    'entidad' => $oferta['entidad'],
    'atributo' => $id_attr,
    'valor' => $oferta['sku_plataforma']
  );
  entidad::actualizarEntidad($row);
  //Stock para esa plataforma
  $id_attr = entidad::dameIdAtributo('stock_' . $plataforma);
$row['atributo'] = $id_attr;
$row['valor'] = $stock['cantidad'];
entidad::actualizarEntidad($row);
//Precio para esa plataforma
$id_attr = entidad::dameIdAtributo('precio_' . $plataforma);
$row['atributo'] = $id_attr;
$row['valor'] = $oferta['precio'];
entidad::actualizarEntidad($row);
//Clase logistica
$id_attr = entidad::dameIdAtributo('logistic_class_' . $plataforma);
$row['atributo'] = $id_attr;
$row['valor'] = $oferta['clase_logistica'];
entidad::actualizarEntidad($row);
//State code
$id_attr = entidad::dameIdAtributo('state_code_' . $plataforma);
$row['atributo'] = $id_attr;
$row['valor'] = $oferta['state_code'];
entidad::actualizarEntidad($row);

//Margen
$id_attr = entidad::dameIdAtributo('margen_' . $plataforma);
$row['atributo'] = $id_attr;
$row['valor'] = $oferta['margen'] != '-' ? $oferta['margen'] : 0;
entidad::actualizarEntidad($row);
}


 $tiempo_final = microtime(true);
 $tiempo = $tiempo_final - $tiempo_inicial; //este resultado estará en segundos
 echo "El tiempo de ejecución del archivo ha sido de " . $tiempo . " segundos";
  ?>

<?php
namespace unicorn\cron\mirakl;
error_reporting(E_ALL);                                     //Activamos el reporte de errores
ini_set('display_errors', 'on');
require_once __DIR__ . '/../../config.php.inc';
require_once(RAIZ . '/vendor/autoload.php');
$tiempo_inicial = microtime(true); //true es para que sea calculado en segundos

require_once RAIZ . '/clases/funciones/inforpor/Tarifa.php';
require_once RAIZ . '/clases/objetos/Articulos/Articulo_tarifa_inforpor.php';
require_once RAIZ . '/clases/funciones/inforpor/Stock.php';
require_once RAIZ . '/clases/funciones/otras/Catalogo.php';
//require_once RAIZ . '/clases/funciones/otras/Moneda.php';
require_once RAIZ . '/clases/funciones/unicorn_db/Entidad.php';
require_once RAIZ . '/clases/funciones/unicorn_db/Config.php';
//require_once RAIZ . '/clases/funciones/mirakl/Ofertas.php';
require_once RAIZ . '/clases/funciones/unicorn_db/Actualizacion.php';

use unicorn\clases\funciones\inforpor\Tarifa as tarifa;
use Mirakl\MMP\Shop\Client\ShopApiClient as Client;
//use Mirakl\MMP\Shop\Request\Offer\GetOffersRequest;
use Mirakl\MMP\Shop\Request\Offer\UpdateOffersRequest;
use unicorn\clases\funciones\otras\Catalogo as catalogo;
use unicorn\clases\funciones\inforpor\Stock as compraInforpor;
//use unicorn\clases\funciones\otras\Moneda as moneda;
use unicorn\clases\funciones\unicorn_db\Entidad as entidad;
use unicorn\clases\funciones\unicorn_db\Config as config;
use unicorn\clases\funciones\unicorn_db\Actualizacion as actualizacion;
//use unicorn\clases\funciones\mirakl\Ofertas as ofertas;
use unicorn\clases\objetos\Articulos\Articulo_tarifa_inforpor as articuloIfp;

 ?>

 <?php
//Cargamos la última tarifa de inforpor
//$tarifa_inforpor_json = tarifa::dameJSONTarifa(); //Cargamos la última tarifa
//$tarifa_inforpor = json_decode($tarifa_inforpor_json, true, 512); //la convertimos desde el JSON
//Vamos a filtrar para mejorar el rendimiento
/*$tarifa_objetos_inforpor = array(); //Declaramos un array para meter los objetos 
foreach ($tarifa_inforpor as $articulo) { //Recorremos la tarifa para crear un array de objetos inforpor y trabajar con eso
$tarifa_objetos_inforpor[] = new articuloIfp($articulo);
}*/
//$arr_no_inforpor = array();
$plataformas = Array('pcc','mediamarkt'); //Lista de las plataformas
$art_descontar_totales = catalogo::articulosADescontar(); //Pedimos los artículos a los que tenemos que descontar stock
foreach ($plataformas as $plataforma) { //Recorremos las distintas plataformas
/*  $listado_ofertas = catalogo::dameObjetosMirakl($plataforma); //Pedimos el listado de ofertas de pc componentes 
 // $listado_ofertas = array_chunk($listado_ofertas,200)[0]; //limitamos a 50 el array para las pruebas
  //Recorremos ahora el listado de ofertas recibidas
foreach ($listado_ofertas as $key => $oferta) { 

  $found_key = array_search($oferta['mpn'], array_column($tarifa_inforpor, 'referencia')); //Buscamos la referencia
  if ($found_key === false) { //Si no se encuentra el artículo devuelve false
    $found_key = array_search($oferta['mpn'], array_column($tarifa_inforpor, 'referencia2')); //Buscamos la referencia
  }
  if ($found_key === false) { //Si no se encuentra el artículo devuelve false
    $found_key = array_search($oferta['ean'], array_column($tarifa_inforpor, 'ean')); //Buscamos la referencia
  }
  if ($found_key === false) { //Si al final de todas las comparaciones no lo encontramos lo añadimos a un array
 $arr_no_inforpor[$plataforma][] = $oferta;
 $listado_ofertas[$key]['cantidad'] = 0; //Si no está en inforpor le ponemos un 0 al stock
  } else {
    $art_ifp = $tarifa_inforpor[$found_key];
$listado_ofertas[$key]['ifp'] = $art_ifp; //Añadimos a la entrada del array la info completa de inforpor
//Stock
$stock_normal = intval($art_ifp['stock']) - config::dameValorConfig('descontar_stock')  >= 0 ? intval($art_ifp['stock']) - config::dameValorConfig('descontar_stock') : 0; //Si el resultado es menor que 0 ponemos 0
$stock_reserva = intval($art_ifp['reserva']);
$stock_custodias = intval($art_ifp['custodia']) == 0 ? intval($art_ifp['custodia']) : intval($art_ifp['custodia']); //falta recuperar las custodias con una llamada
$listado_ofertas[$key]['cantidad'] = $stock_normal + $stock_reserva + $stock_custodias;
//Margen
$margen_beneficio = compraInforpor::calculaMargenNueva($art_ifp, $oferta, $plataforma);
$listado_ofertas[$key]['margen'] = $margen_beneficio['margen']; //Sobreescribimos el margen que hemos recuperado de la bbdd con el calculado
$listado_ofertas[$key]['beneficio'] = $margen_beneficio['beneficio'];
//Como medida de seguridad ponemos stock 0 a cualquier artículo actualizable en el que se pierda dinero
if ($listado_ofertas[$key]['info']['actualizable'] == 0 && ($margen_beneficio['margen'] < 0 || $margen_beneficio['beneficio'] < 0)) {
  $listado_ofertas[$key]['cantidad'] = 0; //Le ponemos un 0 al stock
  $listado_ofertas[$key]['precio'] = $listado_ofertas[$key]['precio'] * 1.20; //Y le subimos un 20%
}


//$listado_ofertas[$key]['calculos_margen'] = $margen_beneficio;
  }
 // actualizaDatosBd($stock_margen,$oferta,$plataforma); //Actualizamos los datos en la bbdd
}*/
$listado_ofertas = catalogo::procesaListado($plataforma); //Procesamos el listado, pero necesitamos encapsular las modificaciones que vayamos a hacer para actualizar

/*echo '<pre>';
print_r($listado_ofertas);
echo '</pre>';*/
$array_actualizacion = array_map(function($articulo) {
//Aprovechamos la generación del array nuevo para realizar las modificaciones
if ($articulo['info']['actualizable'] == 0 && ($articulo['margen'] < 0 || $articulo['beneficio'] < 0)) {
  $articulo['cantidad'] = 0; //Le ponemos un 0 al stock
  $articulo['precio'] = $articulo['precio'] * 1.20; //Y le subimos un 20%
} elseif ($articulo['info']['actualizable'] == 0 && (($articulo['margen'] >= 0 && $articulo['margen'] < 10)|| ($articulo['beneficio'] > 0 && $articulo['beneficio'] < 2))) { //Si tiene margen bajo o beneficio subimos un poco el precio también
  $articulo['precio'] = $articulo['precio'] * 1.05; //Y le subimos un 5%
} elseif($articulo['info']['actualizable'] == 0 && $articulo['margen'] >= 20){
  $articulo['precio'] = $articulo['precio'] / 1.05; //Y le bajamos un 5%
}


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
  actualizaOfertasMP($array_actualizacion, $listado_ofertas, $plataforma);

echo '<pre>';
print_r($array_actualizacion);
echo '</pre>';
}

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
actualizacion::addActualizacion($datos_alertas);
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

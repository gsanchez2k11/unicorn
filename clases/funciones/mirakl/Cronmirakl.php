<?php
namespace unicorn\clases\funciones\mirakl;
error_reporting(E_ALL);                                     //Activamos el reporte de errores
ini_set('display_errors', 'on');
//require_once __DIR__ . '/../../../config.php';
require_once(RAIZ . '/vendor/autoload.php');
use Mirakl\MMP\Shop\Client\ShopApiClient as Client;
use Mirakl\MMP\Common\Request\Shipping\AbstractGetShippingCarriersRequest;
use unicorn\clases\funciones\otras\Catalogo as catalogo;
use unicorn\clases\funciones\unicorn_db\Config as config;
use Mirakl\MMP\Shop\Request\Offer\UpdateOffersRequest;
require_once RAIZ . '/clases/funciones/otras/Catalogo.php';
require_once RAIZ . '/clases/funciones/unicorn_db/Config.php';
/**
 *
 */
class Cronmirakl
{
public static function actualizaMiraklInforpor($tarifa_inforpor){
  $plataformas = Array('pcc','mediamarkt'); //Lista de las plataformas
  $art_descontar_totales = catalogo::articulosADescontar(); //Pedimos los artículos a los que tenemos que descontar stock
  foreach ($plataformas as $plataforma) {
    $listado_ofertas = catalogo::procesaListado($plataforma, $tarifa_inforpor) ; //Procesamos el listado, pero necesitamos encapsular las modificaciones que vayamos a hacer para actualizar

    $array_actualizacion = array_map(function($articulo) {
      //Aprovechamos la generación del array nuevo para realizar las modificaciones
      if ($articulo['info']['actualizable'] == 0 && ($articulo['margen'] < 0 || $articulo['beneficio'] < 0)) {
        $articulo['cantidad'] = 0; //Le ponemos un 0 al stock
        $articulo['precio'] = $articulo['precio'] * 1.20; //Y le subimos un 20%
      } elseif ($articulo['info']['actualizable'] == 0 && (($articulo['margen'] >= 0 && $articulo['margen'] < 10)|| ($articulo['beneficio'] > 0 && $articulo['beneficio'] < 2))) { //Si tiene margen bajo o beneficio subimos un poco el precio también
        $articulo['precio'] = $articulo['precio'] * 1.05; //Y le subimos un 5%
      } elseif($articulo['info']['actualizable'] == 0 && $articulo['margen'] >= 15){
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
  self::actualizaOfertasMP($array_actualizacion, $listado_ofertas, $plataforma);
  }
}

public static function actualizaOfertasMP($array_actualizacion,$listado_ofertas,$plataforma) {
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
//actualizacion::addActualizacion($datos_alertas);
}

}


?>

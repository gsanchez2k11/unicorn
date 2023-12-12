<?php
namespace unicorn\clases\funciones\otras;
error_reporting(E_ALL);                                     //Activamos el reporte de errores
ini_set('display_errors', 'on');
require_once(RAIZ . '/vendor/autoload.php');
//require_once __DIR__ . '/../../config.php.inc';
require_once RAIZ . '/clases/funciones/unicorn_db/Entidad.php';
require_once RAIZ . '/clases/funciones/inforpor/Stock.php';
require_once RAIZ . '/clases/funciones/inforpor/Tarifa.php';
require_once RAIZ . '/clases/funciones/google/Tarifa.php';
require_once RAIZ . '/clases/funciones/mirakl/Pedidos.php';
require_once RAIZ . '/clases/funciones/mirakl/Ofertas.php';
require_once RAIZ . '/clases/funciones/fnac/Pedidos.php';
require_once RAIZ . '/clases/funciones/unicorn_db/Categorias_Marketplaces.php';
require_once RAIZ . '/clases/funciones/unicorn_db/Config.php';

use unicorn\clases\funciones\mirakl\Pedidos as pedidos;
use unicorn\clases\funciones\unicorn_db\Entidad as entidad;
use unicorn\clases\funciones\inforpor\Stock as compraInforpor;
//use unicorn\clases\funciones\google\Tarifa as tarifa;
use unicorn\clases\funciones\fnac\Pedidos as pedidos_fnac;
use unicorn\clases\funciones\mirakl\Ofertas as ofertas;
use unicorn\clases\funciones\unicorn_db as unicorndb;
use unicorn\clases\funciones\inforpor\Tarifa as tarifa_inforpor;
use unicorn\clases\funciones\unicorn_db\Config as config;
?>
<?php
/**
 *
 */
class Catalogo
{
/**
 * Recorremos el array con el catálogo de ofertas, macheamos con la tarifa interna y añadimos las compras en inforpor
 * @param  array   $refs_nuevas [description]
 * @param  boolean $developer   [description]
 * @return [type]               [description]
 */
public static function recorreRefsNuevas(array $refs_nuevas, string $plataforma, $developer = false) {
  $valores_tarifa = tarifa::dameTarifaFiltrada('1zVn9BKoljNXLaj4wQVhvsPN0edio0wLFYHjVeMJ-amA','Futura!A1:Z');         //Pedimos la tarifa nuestra y filtramos los artículos
  switch ($plataforma) {
    case 'pcc':
      $id_atributo = '7';
      break;
      case 'phh':
        $id_atributo = '11';
        break;
        case 'fnac':
          $id_atributo = '10';
          break;

  }

  foreach ($refs_nuevas as $key => $ref) {
  if ($developer === false /*|| $key <= 10 */) {

    //Buscamos la referencia en la bbdd y la insertamos si es necesario
    $entidad = entidad::gestionaEntidad($ref);
    $ref['entidad'] = $entidad;                                                     //Añadimos la entidad al artículo actual
    $refs_nuevas[$key]['entidad']['id'] = $entidad;                                 //Y tambien a su entrada en el array
  $gestiona_cod_pcc = entidad::insertaArticuloEntidadInt($entidad,$id_atributo,$ref['product_sku']);  //Añadimos o actualizamos la referencia de pcc en la bd
  //Mapeamos con inforpor
  $inforpor = compraInforpor::ObtenerCompraInforpor($ref);
  $ref['inforpor'] = $inforpor;                                                   //Añadimos la info de inforpor al artículo actual
  $refs_nuevas[$key]['inforpor'] = $inforpor;                                     //Actualizamos tambien el array general



  //Comprobamos si tiene custodias
  /*if (!empty($refs_nuevas[$key]['inforpor']['custodias']) && get_class($refs_nuevas[$key]['inforpor']['custodias']) == 'unicorn\clases\objetos\Articulos\Custodia') {
    echo 'tiene custodias';
  }*/

  //Mapeamos con google
  foreach ($valores_tarifa as $linea_tarifa) {
  if ((!empty($linea_tarifa['mpn']) && $linea_tarifa['mpn'] == $ref['mpn'] )||( !empty($linea_tarifa['ean']) && $linea_tarifa['ean'] == $ref['ean'])) {     //Comparamos por mpn y ean
  $refs_nuevas[$key]['tarifa'] = $linea_tarifa;
  }
  }
  }
  }

$refs_nuevas = $developer !== false ? array_chunk($refs_nuevas,9,true)[0] : $refs_nuevas ;

  return $refs_nuevas;
}



public static function preparaStock($refs_nuevas,$art_descontar_totales) {

foreach ($refs_nuevas as $key => $articulo) {
  //Vamos a definir el stock
  $modificador = isset($art_descontar_totales[$articulo['product_sku']]) ? $art_descontar_totales[$articulo['product_sku']] : 0;
  if (isset($articulo['tarifa']) && is_numeric($articulo['tarifa']['stock_tarifa'])) {  //Si está en tarifa no hay más que rascar
  $articulo['stock_final'] = $articulo['tarifa']['stock_tarifa'];
  $stock_final = $articulo['stock_final'];
  } else { //Si no vamos a aplicar la formula stock indforpor = (stock normal - 3)  + reservas + custodias
  //stock normal

  //$stock_normal_inforpor = $articulo['inforpor']['normal_inforpor']['StockPrResult']['Stock'];
  $stock_normal_inforpor = $articulo['inforpor']['normal_inforpor']['Stock'];

  $stock_normal = $stock_normal_inforpor > 1 ? $stock_normal_inforpor - 1 : 0;
  //reservas
  $stock_reserva = !empty($articulo['inforpor']['reserva_inforpor']) && $articulo['inforpor']['reserva_inforpor'] > 0 ? $articulo['inforpor']['reserva_inforpor'] : 0;
  //custodias
  $stock_custodias = 0;
  if (!empty($articulo['inforpor']['custodias'])) {
    foreach ($articulo['inforpor']['custodias'] as $custodia) {
      $stock_custodias += $custodia->getQuedan();
    }
  }
  //echo $articulo['mpn'] . '|' . $stock_normal . '|' . $stock_reserva . '|' . $stock_custodias . '<br>';
  $stock_final = ($stock_normal + $stock_reserva + $stock_custodias) > 0 ?  $stock_normal + $stock_reserva + $stock_custodias : 0;
  $articulo['stock_custodias'] = $stock_custodias;                                //Añadimos el total de custodias a los datos
  $articulo['stock_final'] = $stock_final;                                        //Añadimos el stock final, resultado de la suma de stocks
  }
  //En cualquier caso reducimos el stock final con el modificador
  $stock_final -= $modificador;
  $stock_final = $stock_final >= 0 ? $stock_final : 0;                            //Limitamos la cantidad a 0
  $stock_final = $stock_final <= 9999 ? $stock_final : 9999;                            //Limitamos la cantidad por arriba a 9999
  //Pasamos el stock al array general
  $refs_nuevas[$key]['stock_final'] = $stock_final;
  $refs_nuevas[$key]['modificador'] = $modificador;
}

return $refs_nuevas;
}

protected static function articulosDescontar($pedidos, $plataforma = 'pcc'){
    $articulos_descontar = array();
  //Filtramos y nos quedamos con los pedidos pendientes de aceptar
  $pds_pendientes = array_filter($pedidos, function ($pedido){
    return $pedido->getEstado() == 'WAITING_ACCEPTANCE' || $pedido->getEstado() == 'WAITING_DEBIT' || $pedido->getEstado() == 'SHIPPING';
//  return $pedido->getEstado() == 'SHIPPED';
  });

  //Recorremos el array resultante para obtener los articulos
  foreach ($pds_pendientes as $key => $pd) {

  //Recorremos ahora los articulos de cada pedido
  foreach ($pd->getLineasPedido() as $keylp => $linea) {
    $row['sku_' . $plataforma] = $linea->getSku();
    $entidad = entidad::buscarEntidad($row)['entidad'];
    $atributos = entidad::dameAtributosEntidad($entidad);
    $ean = $atributos[3];
  /*  echo '<pre>';
    print_r($ean);
    echo '</pre>';*/
  //Generamos un array con clave codigo de pcc y una entrada por cada cantidad y pedido
    $articulos_descontar['EAN' . $ean][] = $linea->getCantidad();               //Añadimos el string EAN para que las claves sean tratadas como string

  }
  }
  return $articulos_descontar;
}

/**
 * Devuelve todos los artículos pendientes de servir en las distintas plataformas
 */
public static function articulosADescontar(){
  /**
   * Tenemos que obtener el listado de plataformas y recorrerlas para obtener los artículos
   */
  $pedidos_pcc = pedidos::dameUltimosPedidos('pcc');
  //$pedidos_phh = pedidos::dameUltimosPedidos('phh');
  //$pedidos_fnac = pedidos_fnac::damePedidos();

  $articulos_descontar = array();
  $art_descontar_totales = array();
  $articulos_descontar_pcc = self::articulosDescontar($pedidos_pcc,'pcc');
  //$articulos_descontar_phh = self::articulosDescontar($pedidos_phh,'phh');
  //$articulos_descontar_fnac = self::articulosDescontar($pedidos_fnac,'fnac');
$articulos_descontar = array_merge_recursive($articulos_descontar_pcc/*,$articulos_descontar_phh, $articulos_descontar_fnac*/);

  foreach ($articulos_descontar as $key => $art) {
  $art_descontar_totales[substr($key,3)] = array_sum($art);                       //Quitamos la palabra EAN del comienzo
  }
return $art_descontar_totales;

}

/**
 * Pide todas las ofertas y completa la info con los datos que tenemos en la bd
 * @return [type] [description]
 */
public static function dameObjetosMirakl($plataforma){
  $csv = ofertas::dameCsv($plataforma); //Pedimos el listado de ofertas de la plataforma correspondiente
 // $tarifa_inforpor = tarifa_inforpor::dameTarifa();    
  $items = $csv->getItems(); //Nos quedamos con los items
  $mis_items = array(); //Declaramos un array vacío
  $articulos_descontar = self::articulosADescontar(); //Descontamos los artículos pendientes de servir en las distintas plataformas


  foreach ($items as $key => $value) {
    $datos = $value->getData();
    $precio = $datos['price'];
    $stock = $datos['quantity'];
    $shop_sku = $datos['shop-sku'];
    $sku_plataforma = $datos['product']->getData()['sku'];
    $logistic_class_code = $datos['logistic_class_code'];
    $offer_id = $datos['offer_id'];
    $state_code = $datos['state_code'];


    $cadena = 'sku_' . $plataforma;  //Definimos el atributo sku que necesitamos
    $id_atributo = entidad::dameIdAtributo($cadena); //Buscamos la id de la referencia del artículo para esa plataforma
    //Si tenemos oferta capturamos los datos
    if (isset($datos['discount'])) {
   //   print_r($datos['discount']);
    $descuento = $datos['discount']->getData();
    $precio_origen = $descuento['origin_price'];
    $fecha_inicio = isset($descuento['start_date']) ? $descuento['start_date']->format('Y-m-d H:i:s') : '';
    $fecha_fin = isset($descuento['end_date']) ? $descuento['end_date']->format('Y-m-d H:i:s') : '';
    $precio_oferta = $descuento['discount_price'];
    }

  $entidad = entidad::buscarArticuloEntidadInt($id_atributo,$sku_plataforma); //Pedimos la entidad en la base de datos para cada artículo
  if (empty($entidad)) { //Si la entidad no existe lo buscamos por mpn
  $row_buscar = array (
    'mpn' => $shop_sku
  );
  $buscar = entidad::buscarEntidad($row_buscar);


  if (empty($buscar)) {//Si no tenemos resultados es que no tenemos el artículo registrado

  $nueva_entidad = array (
    'mpn' => $shop_sku
  );
  $nueva_entidad[$cadena] = $sku_plataforma;
  $insertar = entidad::crearEntidad($nueva_entidad); //Creamos una nueva entidad con el mpn y el sku de pcc
  $entidad[] = $insertar; //Creamos un array con la entidad que es como retorna la consulta sql

  } else { //Si tenemos resultados el problema es que no tenemos la información bien y vamos a añadir el sku pcc

    $inserta_aev = entidad::insertaArticuloEntidadInt($buscar['entidad'],$id_atributo,$sku_plataforma);
  $entidad[] = $buscar['entidad']; //Creamos un array con la entidad que es como retorna la consulta sql
  }

  }


//Pedimos los atributos del artículo en la base de datos
  $attrs_bd = entidad::dameAtributosEntidad($entidad[0]);
  //$en_custodia = isset($attrs_bd[1]) ? $attrs_bd[1] : '0';
  $mpn = isset($attrs_bd[2]) ? $attrs_bd[2] : '-' ; //Este campo lo mantenemos en la bbdd
  $nombre = isset($attrs_bd[4]) ? $attrs_bd[4] : '-' ; //El nombre también lo mantenemos en la bbdd
  $cod_inforpor = isset($attrs_bd[5]) ? $attrs_bd[5] : '-' ;
  $comentario = isset($attrs_bd[6]) ? $attrs_bd[6] : '-' ;
  //$cat_plataforma = isset($attrs_bd[17]) ? $attrs_bd[17] : '' ;
  //$cat_plataforma = isset($attrs_bd[$id_atributo_categoria]) ? $attrs_bd[$id_atributo_categoria] : '' ;
  $cat_plataforma = self::dameCategoria($attrs_bd,$plataforma,$datos,$entidad[0]);
  $ean = isset($attrs_bd[3]) ? $attrs_bd[3] : '' ;
  $favorito = isset($attrs_bd[12]) ? $attrs_bd[12] : "0" ;
  //Margen
//$id_attr = entidad::dameIdAtributo('margen_' . $plataforma);
//$margen = isset($attrs_bd[$id_attr]) ? $attrs_bd[$id_attr] : "-" ;
//Posicion
//$id_attr = entidad::dameIdAtributo('posicion_' . $plataforma);
 // $posicion = isset($attrs_bd[$id_attr]) ? $attrs_bd[$id_attr] : "-" ;
  
  $stock_local = isset($attrs_bd[18]) ? $attrs_bd[18] : "-" ;
  $actualizable = isset($attrs_bd[9]) ? $attrs_bd[9] : "0" ;

$comision = $cat_plataforma != '' ? unicorndb\Categorias_Marketplaces::buscarCategoriaMarketplace($cat_plataforma, $plataforma)['comision'] : 12; //Por defecto ponemos un 12% de comision y así no nos pillamos los dedos
//echo 'comision' . $comision;
//Buscamos los modificadores
$modif = array_filter($articulos_descontar, function($ref) use ($ean){
return $ref == $ean;
}, ARRAY_FILTER_USE_KEY);

$modificador = count($modif) > 0 ? reset($modif) : 0;

  //echo "<pre>";
  //print_r($attrs_bd);
  //echo "</pre>";
  $mi_item = array(
    'entidad' => $entidad[0],
    'precio' => $precio,
    'stock' => $stock,
    'stock_local' => $stock_local,
    'shop_sku' => $shop_sku,
    'sku_plataforma' => $sku_plataforma,
    'clase_logistica' => $logistic_class_code,
    'offer_id' => $offer_id,
  //  'custodia' => $en_custodia,
    'nombre' => $nombre,
    'cat_plataforma' => $cat_plataforma,
    'ean' => $ean,
   // 'posicion' => $posicion,
   'posicion' => 0,
   // 'margen' => $margen,
    'mpn' => $mpn,
    'state_code' => $state_code,
    'cod_inforpor' => $cod_inforpor,
    'comision' => $comision,
    'modificador' => $modificador,
    'info' => array(
        'favorito' => $favorito,
    //    'custodia' => $en_custodia,
        'actualizable' => $actualizable,
        'comentario' => $comentario
    )
  );

  if (isset($datos['discount'])) {
    $mi_item['oferta'] = array(
      'precio_inicial' => $precio_origen,
      'precio_final' => $precio_oferta,
      'fecha_inicio' => $fecha_inicio,
      'fecha_fin' => $fecha_fin
    );
  }
  $mis_items[] = $mi_item;
}

return $mis_items;
}



private static function dameCategoria($attrs_bd,$plataforma,$datos, $entidad) {
  $cadena = 'categoria_' . $plataforma;
  $id_atributo_categoria = entidad::dameIdAtributo($cadena); //Buscamos la id de la referencia de esa plataforma
  $categoria = '';
//  $cat_plataforma = isset($attrs_bd[$id_atributo_categoria]) ? $attrs_bd[$id_atributo_categoria] : '' ;
if (isset($attrs_bd[$id_atributo_categoria])) { //Si tenemos el valor lo devolvemos
  $categoria = $attrs_bd[$id_atributo_categoria];
} else {
  $info_articulo = ofertas::dameInfoOfertas($datos['product']->getData()['sku'], $plataforma); //Pedimos el listado de ofertas para ese artículo
  $categoria = $info_articulo['product']->getData()['category']->getData()['code']; //Obtenemos la categoría
//Grabamos la informacion
$attrs_bd[$id_atributo_categoria] = $categoria;
$row = array(
  'entidad' => $entidad,
  'atributo' => $id_atributo_categoria,
  'valor' => $categoria
);
entidad::actualizarEntidad($row);
}
return $categoria;

}



public static function procesaListado($plataforma, $tarifa_inforpor) {
  //Cargamos la última tarifa de inforpor
//$tarifa_inforpor_json = tarifa_inforpor::dameJSONTarifa(); //Cargamos la última tarifa
//$tarifa_inforpor = json_decode($tarifa_inforpor_json, true, 512); //la convertimos desde el JSON
  $listado_ofertas = self::dameObjetosMirakl($plataforma); //Pedimos el listado de ofertas para esa plataforma 
  $arr_no_inforpor = array(); //Array para tener controlados los artículos que no existen en inforpor
  foreach ($listado_ofertas as $key => $oferta) { 

    $found_key = array_search($oferta['mpn'], array_column($tarifa_inforpor, 'referencia')); //Buscamos la referencia
    if ($found_key === false) { //Si no se encuentra el artículo devuelve false
      $found_key = array_search($oferta['mpn'], array_column($tarifa_inforpor, 'referencia2')); //Buscamos la referencia
    }
    if ($found_key === false) { //Si no se encuentra el artículo devuelve false
      $found_key = array_search($oferta['ean'], array_column($tarifa_inforpor, 'ean')); //Buscamos la referencia
    }
    if ($found_key === false) { //Como último recurso intentamos emparejar por el codigo insertado en la bbdd para esta entidad
      $found_key = array_search($oferta['cod_inforpor'], array_column($tarifa_inforpor, 'codigo')); //Buscamos el código
    }
    if ($found_key === false) { //Si al final de todas las comparaciones no lo encontramos lo añadimos a un array
//echo '<pre>';
//print_r($oferta);
//echo '</pre>';
   $arr_no_inforpor[$plataforma][] = $oferta;
   $listado_ofertas[$key]['cantidad'] = 0; //Si no está en inforpor le ponemos un 0 al stock
   $listado_ofertas[$key]['margen'] = 0; //Sobreescribimos el margen que hemos recuperado de la bbdd con el calculado
   $listado_ofertas[$key]['beneficio'] = 0;
   $listado_ofertas[$key]['info']['custodia'] = 0;
   $listado_ofertas[$key]['enIfp'] = 0;
   $listado_ofertas[$key]['cod_inforpor'] = 0;
    } else {
  $art_ifp = $tarifa_inforpor[$found_key];
  $listado_ofertas[$key]['ifp'] = $art_ifp; //Añadimos a la entrada del array la info completa de inforpor
  //Stock
  $stock_normal = intval($art_ifp['stock']) - config::dameValorConfig('descontar_stock')  >= 0 ? intval($art_ifp['stock']) - config::dameValorConfig('descontar_stock') : 0; //Si el resultado es menor que 0 ponemos 0
  $stock_reserva = intval($art_ifp['reserva']);
  $stock_custodias = intval($art_ifp['custodia']) == 0 ? intval($art_ifp['custodia']) : intval($art_ifp['custodia']); //falta recuperar las custodias con una llamada
  $listado_ofertas[$key]['cantidad'] = $stock_normal + $stock_reserva + $stock_custodias;
  $listado_ofertas[$key]['info']['custodia'] = intval($art_ifp['custodia']) > 0 ? 1 : 0; //Definimos el atributo custodia para el artículo
  //Margen
  $margen_beneficio = compraInforpor::calculaMargenNueva($art_ifp, $oferta, $plataforma);
  $listado_ofertas[$key]['margen'] = $margen_beneficio['margen']; //Sobreescribimos el margen que hemos recuperado de la bbdd con el calculado
  $listado_ofertas[$key]['beneficio'] = $margen_beneficio['beneficio'];
  $listado_ofertas[$key]['enIfp'] = 1; //Para controlar lo que hay en inforpor y lo que no
  $listado_ofertas[$key]['cod_inforpor'] = $art_ifp['codigo']; //Para tener fácil acceso al artículo en inforpor
  //Como medida de seguridad ponemos stock 0 a cualquier artículo actualizable en el que se pierda dinero
  /*if ($listado_ofertas[$key]['info']['actualizable'] == 0 && ($margen_beneficio['margen'] < 0 || $margen_beneficio['beneficio'] < 0)) {
    $listado_ofertas[$key]['cantidad'] = 0; //Le ponemos un 0 al stock
    $listado_ofertas[$key]['precio'] = $listado_ofertas[$key]['precio'] * 1.20; //Y le subimos un 20%
  }*/
  
  
  //$listado_ofertas[$key]['calculos_margen'] = $margen_beneficio;
    }
   // actualizaDatosBd($stock_margen,$oferta,$plataforma); //Actualizamos los datos en la bbdd
  }

  return $listado_ofertas;
}


}





 ?>

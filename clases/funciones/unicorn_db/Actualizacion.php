<?php
namespace unicorn\clases\funciones\unicorn_db;
require_once 'General.php';
require_once 'Entidad.php';
require_once 'Config.php';
require_once '/var/www/vhosts/grupodercont.es/httpdocs/unicorn/config.php.inc';
require_once RAIZ . '/clases/funciones/otras/Moneda.php';
require_once RAIZ . '/clases/objetos/Stock/Custodia.php';
require_once RAIZ . '/clases/funciones/mirakl/Ofertas.php';
require_once RAIZ . '/clases/funciones/fnac/Pricing.php';
require_once RAIZ . '/clases/funciones/otras/Catalogo.php';
use unicorn\clases\funciones\otras\Catalogo as catalogo;
use unicorn\clases\funciones\otras\Moneda as moneda;
use unicorn\clases\funciones\unicorn_db\Entidad as entidad;
use unicorn\clases\funciones\unicorn_db\Config as config;
use unicorn\clases\objetos\Stock\Custodia as custodia;
use unicorn\clases\funciones\mirakl\Ofertas as ofertas;
use unicorn\clases\funciones\fnac\Pricing as pricing;


/**
 *
 */
class Actualizacion extends General
{
  public static function addActualizacion($datos)
  {
    $visto = isset($datos['visto']) ? $datos['visto'] : 1;                      //Campo opcional
    $num_modificados = isset($datos['num_modificados']) ? $datos['num_modificados'] : 1;                      //Campo opcional
      $sql = "INSERT INTO log_actualizacion(plataforma,tipo,campo_1,visto)";
      $sql .= " VALUES('" . $datos['plataforma'] . "','" . $datos['tipo'] . "','" . $datos['campo_1'] . "','" . $visto . "')";
      $resultado = self::ejecutaConsulta($sql);
      return $resultado;
  }


/*devuelve la última actualización de un tipo dado
Valores para tipo:
- stock
- posiciones
- listar_ofertas
*/

  public static function dameUltimaActualizacion(string $tipo, string $plataforma = 'pcc') {
    $sql = "SELECT * FROM log_actualizacion ";
    $sql .= " where tipo = '" . $tipo ."' and plataforma = '" . $plataforma ."' order by fecha desc limit 1;";
    $resultado       = self::ejecutaConsulta($sql);

      if ($resultado) {
          $row = $resultado->fetch(\PDO::FETCH_ASSOC); //Usando PDO::FETCH_ASSOC devuelve el resultado como array asociativo
      }
      return $row;
  }

  public static function dameUltimasActualizaciones($tipo) {
    $sql = "SELECT * FROM log_actualizacion ";
    $sql .= " where tipo = '" . $tipo ."' order by fecha desc limit 100;";
    $resultado       = self::ejecutaConsulta($sql);

      if ($resultado) {
          $row = $resultado->fetch(\PDO::FETCH_ASSOC); //Usando PDO::FETCH_ASSOC devuelve el resultado como array asociativo
          while ($row != null) {
              $actualizaciones[] = $row;
              $row               = $resultado->fetch(\PDO::FETCH_ASSOC);
          }
      }
      return $actualizaciones;
  }




  public static function listaActualizacionesNoVistas($tipo) {
    $sql = "SELECT * FROM log_actualizacion ";
    $sql .= " where tipo = '" . $tipo ."' and visto = '0' order by fecha desc;";
    $resultado       = self::ejecutaConsulta($sql);
    $actualizaciones = array();
        if ($resultado) {
          $row = $resultado->fetch(\PDO::FETCH_ASSOC); //Usando PDO::FETCH_ASSOC devuelve el resultado como array asociativo
          while ($row != null) {
              $actualizaciones[] = $row;
              $row               = $resultado->fetch(\PDO::FETCH_ASSOC);
          }
      }
      return $actualizaciones;
  }

/**
 * NECESITAMOS FILTRAR PARA QUITAR REACONDICIONADOS
 * @param  [type] $ofertas_con_stock               [description]
 * @param  [type] $plataforma                      [description]
 * @return [type]                    [description]
 */
public static function dameArrPosiciones($ofertas_con_stock, $plataforma) {
  $posiciones = array();
  switch ($plataforma) {
    case 'pcc':
    //Si tenemos el campo product_sku es el que pasamos, si no probamos con el sku pcc
    if (isset(reset($ofertas_con_stock)['product_sku'])) {
      $arr_products_sku = array_chunk(array_column($ofertas_con_stock, 'product_sku'),100);         //generamos subarrays con las id de los productos
    } else {
      $arr_products_sku = array_chunk(array_column($ofertas_con_stock, 'sku_plataforma'),100);         //generamos subarrays con las id de los productos

    }
  //  echo "<pre>";
  //print_r($arr_products_sku);
   //echo "</pre>";
    foreach ($arr_products_sku as $porcion) {                                       //Recorremos los subarrays para obtener las ofertas
      //echo count($porcion);
      $items = ofertas::dameInfoOfertasArr($porcion)->getItems();
      foreach ($items as $bloque) {
        $ofertas[] = $bloque;
      }
    }

if (!empty($ofertas)) {
  foreach ($ofertas as $of) {                                                     //Gereramos el array de posiciones por EAN
    $refs = $of->getData()['product']->getData()['references']->getItems();
    $ean = false;

    foreach ($refs as $ref) {
    if ($ref->getData()['type'] == 'EAN') {
      $ean = $ref->getData()['value'];
    }
    }

  if ($ean !== false) {
   //   echo "<pre>";
//print_r($of->getData()['offers']->getItems());
 //   echo "</pre>";
    $posiciones[$ean] = array_map(function($oferta){
      $calif_tienda = isset($oferta->getData()['shop_grade']) ? $oferta->getData()['shop_grade'] : '-';
      $pos = array(
        'precio' => $oferta->getData()['total_price'],
        'tienda' => $oferta->getData()['shop_name'],
        'calif_tienda' => $calif_tienda,
        'tipo_envio' => $oferta->getData()['min_shipping']->getData()['type_code'],
        'portes'    => $oferta->getData()['min_shipping']->getData()['price'],
        'estado' => $oferta->getData()['state_code']
      );
      return $pos;
    },$of->getData()['offers']->getItems());
  }
  }
  }
      break;
    case 'fnac':
          $arr_products_sku = array_chunk(array_column($ofertas_con_stock, 'ean'),10);         //generamos subarrays con las id de los productos

          $posiciones = pricing::damePricing($arr_products_sku);
          //Generamos un array con nuestras propias Ofertas
      $mapeado = array_map(function($oferta){
        $nuestras[] = array(
          'precio' => $oferta['precio_inicial'],
          'tienda' => 'Futura teck',
          'calif_tienda' => '-',
          'tipo_envio' => '-',
          'portes' => '0'
        );
        return $nuestras;
      },$ofertas_con_stock);
$remap = array();
foreach ($mapeado as $key => $value) {
$remap[$key . ' '] = $value;
}

$juntar = array_merge_recursive($remap,$posiciones);

$salida = array();
foreach ($juntar as $key => $value) {
  $salida[trim($key)] = $value;
}

  /*echo "<pre>";
    print_r($salida);
print_r($posiciones);
    echo "</pre>";*/
    break;

  }
/*   echo "<pre>";
  print_r($posiciones);
    echo "</pre>";*/
  return $posiciones;
}

/**
 * Obtenemos las posiciones a partir de la actualizacion de stock
 * @param  [type] $articulos_ultima_stock [description]
 * @return [type]                         [description]
 */
public static function damePosiciones($listado_ofertas,$plataforma = 'pcc'){
  $arr_oferta = array();
 // if (is_array($articulos_ultima_stock)) {
 // $ofertas = array();
 /* $ofertas_con_stock = array_filter($articulos_ultima_stock, function ($k){
if (isset($k['stock_final'])) {
  $stock = $k['stock_final'];
} else {
    $stock = $k['stock'];
}

    return $stock > 0;
  });*/
//Solo vamos a recuperar la posicion de las ofertas con stock
$listado_ofertas = array_filter($listado_ofertas, function ($ofe) {
  return intval($ofe['stock']) > 0 /*&& isset($ofe['ean'])*/;
});
  //echo count($listado_ofertas);

$posiciones = self::dameArrPosiciones($listado_ofertas, $plataforma);

  //echo "<pre>";
  //print_r($ofertas_con_stock);
  //echo "</pre>";



$arr_oferta = array_map( function ($of_arr) use ($posiciones){                                   //Preparamos ahora el array de productos con stock
 //   echo "<pre>";
 //print_r($of_arr);
 //echo "</pre>";
  $ofertas = array();
  foreach ($posiciones as $ean => $of) {
if ($ean == $of_arr['ean']) {
  $ofertas = $of;
}
  }
$sku = isset($of_arr['product_sku']) ? $of_arr['product_sku'] : $of_arr['sku_plataforma'];
$salida = array(
  'product_sku' => $sku,
  'mpn'         => $of_arr['mpn'],
  'entidad' => $of_arr['entidad'],
  'ean'         => $of_arr['ean'],
  'nombre'      => $of_arr['nombre'],
  'precio' => $of_arr['precio'],
//  'margen' => $of_arr['margen'],
 // 'actualizable' => $of_arr['info']['actualizable'],
  'shop_sku' => $of_arr['shop_sku'],
  'stock' => $of_arr['stock'],
  'estado' => $of_arr['state_code'],
  'comision' => $of_arr['comision'],
  'ofertas' => $ofertas
);
  return $salida;
},$listado_ofertas);
 // }
return $arr_oferta;
}
/**
 * Procesa el listado de ofertas y añade la posicion actual
 */
 public static function addEntidadPosicion($listado_ofertas){
  $arr_key_ean = self::damePosiciones($listado_ofertas); //Pedimos las posiciones para todos las ofertas, esto se hace por bloques
  $filtradas = array_filter($arr_key_ean, function ($of) {
    $ofertas = $of['ofertas'];
    //$encontrado = array_search('Futura Teck',array_column($ofertas,'tienda'));
    $encontrado = array_search('Futura Teck',array_column($ofertas,'tienda'));
    if ($encontrado !== false) {
      return $of;
    }
  });
  //Recorremos las filtradas para encontrar nuestra posicion
  $arr = array();
  foreach ($filtradas as $oferta) {
  $ofertas = $oferta['ofertas'];
  
  foreach ($ofertas as $key => $o) {
  if ($o['tienda'] == 'Futura Teck') {
   /* $arr[] = array(
      'entidad' => $oferta['entidad'],
      'posicion' => $key+1
    );*/
    $arr[$oferta['entidad']] =  $key+1;
  }
  }
  }

$listado_ofertas = array_map(function ($of) use ($arr){
  if (isset($arr[$of['entidad']])) {
    $of['posicion'] = $arr[$of['entidad']];
  } else {
    $of['posicion'] = 0; 
  }
return $of;
},$listado_ofertas);
  return $listado_ofertas;
 }


/**
 * Versión mínima para optimizar
 */
public static function dameActualizacionBasica(string $plataforma = 'pcc'){
  $articulos = array();                                                         //Declaramos un array vacío para los artículos
  $salida = array();
$ultima_stock = self::dameUltimaActualizacion('stock',$plataforma);             //Recuperamos la ultima actualizacion de tipo stock
$articulos_ultima_stock = unserialize(base64_decode($ultima_stock['campo_1'])); //Deserializamos los artículos
return $articulos_ultima_stock;
}

/**
 * [dameDatosActualizacion description]
 * @param  string $plataforma               [description]
 * @return [type]             [description]
 */
public static function dameDatosActualizacion(string $plataforma = 'pcc'){
  $articulos = array();                                                         //Declaramos un array vacío para los artículos
  $salida = array();
$ultima_stock = self::dameUltimaActualizacion('stock',$plataforma);             //Recuperamos la ultima actualizacion de tipo stock
$articulos_ultima_stock = unserialize(base64_decode($ultima_stock['campo_1'])); //Deserializamos los artículos
$articulos_ultima_posiciones = self::damePosiciones($articulos_ultima_stock, $plataforma);
$fecha_ultima_stock = $ultima_stock['fecha'];                                   //Definioms las fechas que vamos a mostrar
 // echo "<pre>";
 // print_r($articulos_ultima_stock);
 // echo "</pre>";
/*
echo "</pre>";*/
if (!empty($articulos_ultima_posiciones)) {                                     //Comprobamos si tenemos las posiciones
$articulos_ultima = array_map(function ($a) use ($articulos_ultima_posiciones){       //Mapeamos los artículos de los 2 arrays
  $ean_a = $a['ean'];
  foreach ($articulos_ultima_posiciones as $ean_b => $value) {
    if ($ean_a == $ean_b) {                                                           //Si coinciden los EAN añadimos las ofertas
      $a['ofertas'] = $value['ofertas'];
    }
  }
  return $a;
}, $articulos_ultima_stock);
} else {                                                                        //Si no tenemos las posiciones logicamente no las añadimos
  $articulos_ultima = $articulos_ultima_stock;
}
if (!empty($articulos_ultima)) {
//Recorremos los artículos
foreach ($articulos_ultima as $ean => $articulo) {
  //echo "<pre>";
  //print_r($articulo);
  //echo "</pre>";
  //Comprobamos la posicion de nuestra oferta
if (isset($articulo['ofertas'])) {
  $posicion_oferta = 69;                                                        //69 para articulos con oferta en los que no estamos
  $mejor_precio = 0;
  $precio_futura = 0;
  foreach ($articulo['ofertas'] as $key => $oferta) {
$of_precio = isset($oferta['precio']) ? $oferta['precio'] : 0;
$of_tienda = isset($oferta['tienda']) ? $oferta['tienda'] : '-';
    if ($key == 0) {
$mejor_precio = $of_precio;
    }
    if ($of_tienda == 'Futura Teck') { //Si encontramos nuestra tienda
      $posicion_oferta = $key + 1;
      $precio_futura = $of_precio;                                   //Asignamos nuestra posición
      $articulos_ultima[$ean]['precio_inicial'] = $precio_futura;   //Modificamos el array para coger el precio obtenido de mirakl
      }
  }
  $articulos_ultima[$ean]['ofertas']['posicion'] = $posicion_oferta;
  $articulos_ultima[$ean]['ofertas']['mejor_precio'] = $mejor_precio;
  $articulos_ultima[$ean]['ofertas']['precio_futura'] = $precio_futura;
} else {
  $articulos_ultima[$ean]['ofertas']['posicion'] = 0;                           //0 Para artículos SIN oferta
}

//Vamos a comprobar el margen de venta
$datos_inforpor = isset($articulo['inforpor']) ? $articulo['inforpor'] : [];                                        //Aislamos los datos de inforpor
//Si no está definido el siguiente indice es que el artículo no existe en inforpor, habrá que hacer depuración de esto
//$lpi = isset($datos_inforpor['normal_inforpor']['StockPrResult']['lpi']) ? $datos_inforpor['normal_inforpor']['StockPrResult']['lpi'] : 0;              //Aislamos el lpi
$lpi = isset($datos_inforpor['normal_inforpor']['lpi']) ? $datos_inforpor['normal_inforpor']['lpi'] : 0;              //Aislamos el lpi

//$precios_inforpor = array();                                                    //Declaramos un array para los precios de inforpor y añadimos el precio normal

//$precio_normal = isset($datos_inforpor['normal_inforpor']['StockPrResult']['Precio']) && $datos_inforpor['normal_inforpor']['StockPrResult']['Precio'] > 0 ? $datos_inforpor['normal_inforpor']['StockPrResult']['Precio'] : 0;
$precio_normal = isset($datos_inforpor['normal_inforpor']['Precio']) && $datos_inforpor['normal_inforpor']['Precio'] > 0 ? $datos_inforpor['normal_inforpor']['Precio'] : 0;

$precio_inforpor = moneda::cadenaAnumero($precio_normal);
$precio_inforpor_portes = $precio_inforpor > 60 ? $precio_inforpor : $precio_inforpor + 3.2; //Le sumamos los portes si no llegamos a 60
$precio_inforpor_canon = $precio_inforpor_portes + $lpi;                        //Le sumamos el canon
$calculo_margen = $precio_inforpor_canon;
$factor_comision = 1; //iniciamos con factor comision 1
if (!empty($datos_inforpor['custodias'])) {
  foreach ($datos_inforpor['custodias'] as $key => $custodia) {
  //    if ($custodia->getQuedan() >  0 && $custodia->getPrecio() < $precio_inforpor) {
  //    $precio_inforpor = moneda::cadenaAnumero($custodia->getPrecio());
  //  }
      if ($custodia->getQuedan() >  0 && ($key === 0 || $custodia->getTotalCompra() > $precio_inforpor_canon)) {

      $precio_inforpor_canon = moneda::cadenaAnumero($custodia->getTotalCompra()/1.21);
    }
  }
}
$compra_neto = $precio_inforpor_canon;
$portes = $precio_inforpor + $lpi > 60 ? 0 : 3.2;
////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////
if (isset($articulo['comision'])) {
$porciento_comision = $articulo['comision'] / 100;                                        //Preparamos el factor para calcular el margen
$factor_comision = 1 - $porciento_comision;
$total_sin_comision = $articulo['precio']*$factor_comision;
$total_neto = $total_sin_comision / 1.21;
$margen_neto = $compra_neto > 3.21 ? (($total_neto  / $compra_neto)-1)*100 : 0;
$articulos_ultima[$ean]['margen'] = number_format($margen_neto,2);
$articulos_ultima[$ean]['beneficio_neto'] = number_format(($total_neto - $compra_neto)*1.21,2);
}
//Vamos a calcular las condiciones para ser primeros
if (isset($mejor_precio) && $mejor_precio > 0) {
$pp_total_sin_comision = (($mejor_precio - 0.05)*$factor_comision) / 1.21;               //Le quitamos 5 cents al mejor precio
$pp_margen_neto = $compra_neto > 3.21 ? (($pp_total_sin_comision  / $compra_neto)-1)*100 : 0;
$articulos_ultima[$ean]['margen_pp'] = number_format($pp_margen_neto,2);
}

$articulos_ultima[$ean]['compra_IVA'] = number_format($compra_neto * 1.21,2);

//echo $articulo['mpn'] . '|' . $precio_total_portes . '|' . $precio_sin_comision . '|' . $margen_neto. '<br>';
$entidad = isset($articulo['entidad']['id']) ? $articulo['entidad']['id'] : $articulo['entidad'];
//Buscamos posibles comentarios del articulo
//$comentario = !is_object(entidad::dameValorArticuloEntidadVarchar($articulo['entidad']['id'],'6')) ? entidad::dameValorArticuloEntidadVarchar($articulo['entidad']['id'],'6') : '';
$comentario = !is_object(entidad::dameValorArticuloEntidadVarchar($entidad,'6')) ? entidad::dameValorArticuloEntidadVarchar($entidad,'6') : '';
$articulos_ultima[$ean]['atributos']['comentario'] = $comentario;
//Buscamos el campo de auto actualizar precio
$precio_auto = !is_object(entidad::dameValorArticuloEntidadInt($entidad,'9')) ? entidad::dameValorArticuloEntidadInt($entidad,'9') : '';
$articulos_ultima[$ean]['atributos']['auto_precio'] = $precio_auto;
//Buscamos si es un articulo favorito
$precio_auto = !is_object(entidad::dameValorArticuloEntidadInt($entidad,'12')) ? entidad::dameValorArticuloEntidadInt($entidad,'12') : '0';
$articulos_ultima[$ean]['atributos']['favorito'] = $precio_auto;
}
}
$articulos_ultima['fecha_margenes'] = $fecha_ultima_stock;
//$articulos_ultima['fecha_posiciones'] = $fecha_ultima_posiciones;
$articulos_ultima['fecha_posiciones'] = 'ahora';
$salida = self::utf8formateo($articulos_ultima);
return $salida;
}



//public static function fusionActualizaciones(string $tipo = 'stock'){
public static function fusionActualizaciones(string $tipo, string $plataforma = 'pcc'){
$articulos = array();
//Pedimos las ultimas actualizaciones
$ultima_stock = self::dameUltimaActualizacion($tipo,$plataforma);
$ultima_posiciones = self::dameUltimaActualizacion('posiciones',$plataforma);
//Asignamos las variables
$fecha_ultima_stock = $ultima_stock['fecha'];
$fecha_ultima_posiciones = $ultima_posiciones['fecha'];
$articulos_ultima_stock = unserialize(base64_decode($ultima_stock['campo_1']));
$articulos_ultima_posiciones = unserialize(base64_decode($ultima_posiciones['campo_1']));

if (!empty($articulos_ultima_posiciones)) {                                     //Comprobamos si tenemos las posiciones
$articulos_ultima = array_map(function ($a) use ($articulos_ultima_posiciones){       //Mapeamos los artículos de los 2 arrays
  $ean_a = $a['ean'];
  foreach ($articulos_ultima_posiciones as $ean_b => $value) {
    if ($ean_a == $ean_b || $ean_a == $value['ean']) {    
                                                   //Si coinciden los EAN añadimos las ofertas
      $a['ofertas'] = $value['ofertas'];
    }
  }
  return $a;
}, $articulos_ultima_stock);
} else {                                                                        //Si no tenemos las posiciones logicamente no las añadimos
  $articulos_ultima = $articulos_ultima_stock;
}
//echo '<pre>';
//print_r($articulos_ultima);
//echo '</pre>';    
//Recorremos los artículos
foreach ($articulos_ultima as $ean => $articulo) {
  //Comprobamos la posicion de nuestra oferta
if (isset($articulo['ofertas'])) {
  $posicion_oferta = 69;                                                        //69 para articulos con oferta en los que no estamos
  $mejor_precio = 0;
  $precio_futura = 0;
  $arr_ofertas = $articulo['ofertas'];
  $arr_ofertas = array_filter($arr_ofertas, function ($v) { //Filtramos para dejar sólo los articulos nuevos (state_code 11)
return $v['estado'] == 11;
  });
  foreach ($arr_ofertas as $key => $oferta) {
    if ($key == 0) {
$mejor_precio = $oferta['precio'];
    }
    if ($oferta['tienda'] == 'Futura Teck') {
      $posicion_oferta = $key + 1;
      $precio_futura = $oferta['precio'];                                   //Asignamos nuestra posición
      }
  }
  $articulos_ultima[$ean]['ofertas']['posicion'] = $posicion_oferta;
  $articulos_ultima[$ean]['ofertas']['mejor_precio'] = $mejor_precio;
  $articulos_ultima[$ean]['ofertas']['precio_futura'] = $precio_futura;
} else {
  $articulos_ultima[$ean]['ofertas']['posicion'] = 0;                           //0 Para artículos SIN oferta
}

//echo '<pre>';
//print_r($articulo);
//echo '</pre>';   
//Vamos a comprobar el margen de venta
$datos_inforpor = isset($articulo['inforpor']) ? $articulo['inforpor'] : '' ;                                        //Aislamos los datos de inforpor
//Si no está definido el siguiente indice es que el artículo no existe en inforpor, habrá que hacer depuración de esto
//$lpi = isset($datos_inforpor['normal_inforpor']['StockPrResult']['lpi']) ? $datos_inforpor['normal_inforpor']['StockPrResult']['lpi'] : 0;              //Aislamos el lpi
$lpi = isset($datos_inforpor['normal_inforpor']['lpi']) ? $datos_inforpor['normal_inforpor']['lpi'] : 0;              //Aislamos el lpi

//$precios_inforpor = array();                                                    //Declaramos un array para los precios de inforpor y añadimos el precio normal

//$precio_normal = isset($datos_inforpor['normal_inforpor']['StockPrResult']['Precio']) && $datos_inforpor['normal_inforpor']['StockPrResult']['Precio'] > 0 ? $datos_inforpor['normal_inforpor']['StockPrResult']['Precio'] : 0;
$precio_normal = isset($datos_inforpor['normal_inforpor']['Precio']) && $datos_inforpor['normal_inforpor']['Precio'] > 0 ? $datos_inforpor['normal_inforpor']['Precio'] : 0;

$precio_inforpor = moneda::cadenaAnumero($precio_normal);
$precio_inforpor_portes = $precio_inforpor > 60 ? $precio_inforpor : $precio_inforpor + 3.2; //Le sumamos los portes si no llegamos a 60
$precio_inforpor_canon = $precio_inforpor_portes + $lpi;                        //Le sumamos el canon
$calculo_margen = $precio_inforpor_canon;
if (!empty($datos_inforpor['custodias'])) {
  foreach ($datos_inforpor['custodias'] as $key => $custodia) {
  //    if ($custodia->getQuedan() >  0 && $custodia->getPrecio() < $precio_inforpor) {
  //    $precio_inforpor = moneda::cadenaAnumero($custodia->getPrecio());
  //  }
      if ($custodia->getQuedan() >  0 && ($key === 0 || $custodia->getTotalCompra() > $precio_inforpor_canon)) {

      $precio_inforpor_canon = moneda::cadenaAnumero($custodia->getTotalCompra()/1.21);
    }
  }
}
$compra_neto = $precio_inforpor_canon;
$portes = $precio_inforpor + $lpi > 60 ? 0 : 3.2;

////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////
if (isset($articulo['comision'])) {
$porciento_comision = $articulo['comision'] / 100;                                        //Preparamos el factor para calcular el margen
$factor_comision = 1 - $porciento_comision;
//$total_sin_comision = $articulo['precio_inicial']*$factor_comision;
$total_sin_comision = $articulo['precio']*$factor_comision;
$total_neto = $total_sin_comision / 1.21;
$margen_neto = $compra_neto > 3.21 ? (($total_neto  / $compra_neto)-1)*100 : 0;
$articulos_ultima[$ean]['margen'] = number_format($margen_neto,2);
$articulos_ultima[$ean]['beneficio_neto'] = number_format(($total_neto - $compra_neto)*1.21,2);
}
//Vamos a calcular las condiciones para ser primeros
if (isset($mejor_precio) && $mejor_precio > 0) {
$pp_total_sin_comision = (($mejor_precio - 0.05)*$factor_comision) / 1.21;               //Le quitamos 5 cents al mejor precio
$pp_margen_neto = $compra_neto > 3.21 ? (($pp_total_sin_comision  / $compra_neto)-1)*100 : 0;
$articulos_ultima[$ean]['margen_pp'] = number_format($pp_margen_neto,2);
}

$articulos_ultima[$ean]['compra_IVA'] = number_format($compra_neto * 1.21,2);

//echo $articulo['mpn'] . '|' . $precio_total_portes . '|' . $precio_sin_comision . '|' . $margen_neto. '<br>';

$entidad = isset($articulo['entidad']['id']) ? $articulo['entidad']['id'] : $articulo['entidad'];
//Buscamos posibles comentarios del articulo
$comentario = !is_object(entidad::dameValorArticuloEntidadVarchar($entidad,'6')) ? entidad::dameValorArticuloEntidadVarchar($entidad,'6') : '';
$articulos_ultima[$ean]['atributos']['comentario'] = $comentario;
//Buscamos el campo de auto actualizar precio
$precio_auto = !is_object(entidad::dameValorArticuloEntidadInt($entidad,'9')) ? entidad::dameValorArticuloEntidadInt($entidad,'9') : '';
$articulos_ultima[$ean]['atributos']['auto_precio'] = $precio_auto;
}
$articulos_ultima['fecha_margenes'] = $fecha_ultima_stock;
$articulos_ultima['fecha_posiciones'] = $fecha_ultima_posiciones;
$salida = self::utf8formateo($articulos_ultima);
return $salida;


}


public static function limpiaLogActualizaciones($plataforma,$tipo) {
  $sql = "SELECT distinct id FROM log_actualizacion ";
  $sql .= " where tipo = '" . $tipo ."' and plataforma = '" . $plataforma ."' order by fecha asc limit 10;";
  $resultado       = self::ejecutaConsulta($sql);

    if ($resultado) {
        $row = $resultado->fetch(\PDO::FETCH_ASSOC); //Usando PDO::FETCH_ASSOC devuelve el resultado como array asociativo
    }
    return $row;
}



/*
Codificamos a UTF para resolver errores
 */
public static function utf8formateo($d) {
    if (is_array($d)) {
        foreach ($d as $k => $v) {
            $d[$k] = self::utf8formateo($v);
        }
    } else if (is_string ($d)) {
        return utf8_encode($d);
    }
    return $d;
}

public static function limpiaCachePcc() {
  //Limpiamos los precios
  $sql = "DELETE FROM articulos_entidad_decimal ";
  $sql .= " where atributo = '14';";
  $resultado       = self::ejecutaConsulta($sql);
//Limpiamos ahora los stocks y el state_code
$sql = "DELETE FROM articulos_entidad_int ";
$sql .= " where atributo = '13' or atributo = '16';";
$resultado       = self::ejecutaConsulta($sql);
//Limpiamos ahora la clase logistica
$sql = "DELETE FROM articulos_entidad_varchar ";
$sql .= " where atributo = '15';";
$resultado       = self::ejecutaConsulta($sql);


}



public static function switchModoListarPcc() {
  $actual = config::dameValorConfig();
switch ($actual) {
  case 0:
$modo = 1;
    break;
    case 1:
  $modo = 0;
      break;
}
$row = array(
  'config' => 'listar_ofertas',
  'valor' => $modo
);
$actualiza = config::updateValorConfig($row);
}

public static function dameDatosBd(){
  $sql = "select id FROM articulos_entidad ";

  $sql .= " where id in (select entidad from articulos_entidad_int where atributo = '7')";

  $sql .= " and id in (select entidad from articulos_entidad_varchar where atributo = '2')";

  $sql .= " and id in (select entidad from articulos_entidad_varchar where atributo = '3')";
  $sql .= " and id in (select entidad from articulos_entidad_varchar where atributo = '4')";
  $sql .= " and id not in (select entidad from articulos_entidad_varchar where atributo = '17')";
  $sql .= " and id in (select entidad from articulos_entidad_decimal where atributo = '14')";
  $sql .= " and id in (select entidad from articulos_entidad_int where atributo = '16')";

  $sql .= " and id in (select entidad from articulos_entidad_int where atributo = '13');";

  $resultado       = self::ejecutaConsulta($sql);
  $entidades = array();
  if ($resultado) {
      $row = $resultado->fetch(\PDO::FETCH_ASSOC); //Usando PDO::FETCH_ASSOC devuelve el resultado como array asociativo
      while ($row != null) {
          $entidades[] = $row['id'];
          $row               = $resultado->fetch(\PDO::FETCH_ASSOC);
      }
  }
    return $entidades;

}

public static function dameListadoPcc(){
  $sql = "select id FROM articulos_entidad";
  $sql .= " where id in (select entidad from articulos_entidad_varchar where atributo = '2')";
  $sql .= " and id in (select entidad from articulos_entidad_varchar where atributo = '3')";
  $sql .= " and id in (select entidad from articulos_entidad_int where atributo = '7')";
  $sql .= " and id in (select entidad from articulos_entidad_int where atributo = '13')";
  $sql .= " and id in (select entidad from articulos_entidad_decimal where atributo = '14')";
  $sql .= " and id in (select entidad from articulos_entidad_varchar where atributo = '15')";
  $sql .= " and id in (select entidad from articulos_entidad_int where atributo = '16');";

  $resultado       = self::ejecutaConsulta($sql);
  $entidades = array();
  if ($resultado) {
      $row = $resultado->fetch(\PDO::FETCH_ASSOC); //Usando PDO::FETCH_ASSOC devuelve el resultado como array asociativo
      while ($row != null) {
          $entidades[] = $row['id'];
          $row               = $resultado->fetch(\PDO::FETCH_ASSOC);
      }
  }
    return $entidades;
}

/**
 * Obtenemos un listado de las que no tienen stock ni precio
 * @return [type] [description]
 */
public static function dameListadoPccNoStockNoPrecio(){
  $sql = "select id FROM articulos_entidad";
  $sql .= " where id in (select entidad from articulos_entidad_varchar where atributo = '2')";
  $sql .= " and id in (select entidad from articulos_entidad_varchar where atributo = '3')";
  $sql .= " and id in (select entidad from articulos_entidad_int where atributo = '7')";
  $sql .= " and id not in (select entidad from articulos_entidad_int where atributo = '13')";
  $sql .= " and id not in (select entidad from articulos_entidad_decimal where atributo = '14')";
  $sql .= " and id in (select entidad from articulos_entidad_varchar where atributo = '15')";
  $sql .= " and id in (select entidad from articulos_entidad_int where atributo = '16');";

  $resultado       = self::ejecutaConsulta($sql);
  $entidades = array();
  if ($resultado) {
      $row = $resultado->fetch(\PDO::FETCH_ASSOC); //Usando PDO::FETCH_ASSOC devuelve el resultado como array asociativo
      while ($row != null) {
          $entidades[] = $row['id'];
          $row               = $resultado->fetch(\PDO::FETCH_ASSOC);
      }
  }
    return $entidades;
}



}

 ?>

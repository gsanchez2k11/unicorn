<?php
namespace unicorn\clases\funciones\magento;
require_once 'Conectar.php';
//use unicorn\clases\funciones\unicorn_db\Config as config;
//use unicorn\clases\funciones\unicorn_db\Config as config;
//require RAIZ . '/clases/funciones/unicorn_db/Config.php';
/**
 *
 */
class Articulos extends Conectar
{

protected static function EjecutaBusqueda(string $cadena_url, int $id_tienda = 1) {
$token = self::getToken($id_tienda);  
  $ch    = curl_init($cadena_url); //No podemos obtener el stock mediante la busqueda, para ello hay que pedir la info de cada artículo suelto
  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "Authorization: Bearer " . $token['token']));

  $result = curl_exec($ch);
  $result = json_decode($result);

  return $result;
}


public static function listarOfertas(array $array) {
 //   echo 'id tienda ' . $id_tienda; 
//use unicorn\clases\funciones\unicorn_db\Config as config;
//require RAIZ . '/clases/funciones/unicorn_db/Config.php';
$fecha = $array['fin_oferta'];
$operador = $array['operador'];
$campo = $array['campo'];
$id_tienda = $array['id_tienda'];


$cadena = '&searchCriteria[filter_groups][0][filters][0][field]='. $campo . '&searchCriteria[filter_groups][0][filters][0][value]=' . $fecha . '&searchCriteria[filter_groups][0][filters][0][condition_type]=' . $operador;

$url = \unicorn\clases\funciones\unicorn_db\Config::dameValorConfig('url_mage_2_' . $id_tienda);
$cadena_url = $url. 'index.php/rest/V1/products/?' . $cadena;
$resultado = self::EjecutaBusqueda($cadena_url, $id_tienda);
return $resultado;
}

/**
 * Es un método rápido para recibir todo el catálogo, pero tiene el problema que no recibimos campos como el sku o el coste
 * https://adobe-commerce.redoc.ly/2.4.6-admin/tag/products-render-info/#operation/GetV1Productsrenderinfo
 */
public static function listarArticulos(array $array){
$pagina = $array['pagina'];
$tienda = $array['tienda'];
$store_id = $tienda; //casualmente coincide

$token = self::getToken($tienda); 

    $ch    = curl_init($token['url'] . 'index.php/rest/V1/products-render-info?searchCriteria[currentPage]=' . $pagina .'&searchCriteria[pageSize]=1000&storeId='. $store_id . '&currencyCode=EUR');
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "Authorization: Bearer " . $token['token']));

    $result = curl_exec($ch);
    $result = json_decode($result);

    return $result;
}


public static function getAllProducts(int $id_tienda = 1){
   
    $token = self::getToken($id_tienda);  
    echo '<pre>';
    print_r($token);
    echo '</pre>';
    $cadena = '&searchCriteria[filter_groups][0][filters][0][field]=status&searchCriteria[filter_groups][0][filters][0][value]=1&searchCriteria[filter_groups][0][filters][0][condition_type]=eq'; //Solo los habilitados
$p = 1; //Página inicial
$pagesize = 1000; //Resultados por página
$articulos = array();
do {
    $ch    = curl_init($token['url'] . 'index.php/rest/V1/products/?fields=items[id,sku,name,price,attribute_set_id,custom_attributes[manufacturer,cost]]&searchCriteria[currentPage]=' . $p . '&searchCriteria[pageSize] = '. $pagesize . $cadena);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "Authorization: Bearer " . $token['token']));

    $result = curl_exec($ch);
    $result = json_decode($result);
    $items = $result->items;
$articulos = array_merge($articulos,$items);
$p++;
} while ($p <= 50 && count($items) == $pagesize);
//} while ($p <= 5 && count($items) == $pagesize);

    
        return $articulos;
    }

  //---------------------------------------------------//
      // Listado con todos los artículos  //
      //---------------------------------------------------//
      public static function getListaPaginaArticulos(array $parametros, int $id_tienda = 1)
      {
        $cadena          = ''; //Iniciamos la cadena de búsqueda
        $cont = 0;
        $url = \unicorn\clases\funciones\unicorn_db\Config::dameValorConfig('url_mage_2_' . $id_tienda);

if (isset($parametros['pagina'])) {
  $cadena .= '&searchCriteria[currentPage]=' . $parametros['pagina'] . '&searchCriteria[pageSize]=100';
}
if (isset($parametros['status'])) {
  $cadena .= '&searchCriteria[filter_groups]['. $cont .'][filters][0][field]=status&searchCriteria[filter_groups][' . $cont  . '][filters][0][value]=' . $parametros['status'] . '&searchCriteria[filter_groups][' . $cont . '][filters][0][condition_type]=eq';
  $cont++;
}
if (isset($parametros['fin_oferta'])) {
$cadena .= '&searchCriteria[filter_groups]['. $cont .'][filters][0][field]=special_to_date&searchCriteria[filter_groups][' . $cont  . '][filters][0][value]=' . $parametros['fin_oferta'] . '&searchCriteria[filter_groups][' . $cont . '][filters][0][condition_type]=lt';
  $cont++;
}
//        $fin_oferta = isset($parametros['fin_oferta']) ? '&searchCriteria[filter_groups]['. $cont .'][filters][0][field]=special_to_date&searchCriteria[filter_groups][' . $cont  . '][filters][0][value]=' . $parametros['fin_oferta'] . '&searchCriteria[filter_groups][' . $cont . '][filters][0][condition_type]=gt' && $cont++ :'';

$cadena_url = $url. 'index.php/rest/V1/products/?fields=items[sku,name,price]' . $cadena;


//echo $cadena_url;
$resultado = self::EjecutaBusqueda($cadena_url,$id_tienda);
/*echo "<pre>";
print_r($resultado);
echo "</pre>";*/
return $resultado->items;
      }

public static function getListaTodosArticulos(){
$p = 1;
//$off = 0;
$items = array();
do {
$pag_arts =   self::getListaPaginaArticulos($p);
$items = array_merge($items,$pag_arts);

$p++;
} while(count($pag_arts) === 100 /*&& $p <= 5*/);
return $items;
}


public static function actualizarArticulo(string $sku, array $atributos,int $id_tienda = 1)
    {
        $token = self::getToken($id_tienda);  
    //    print_r($token); 
    if (is_string($token['token'])) {


        $ch    = curl_init($token['url'] . 'index.php/rest/all/V1/products/' . urlencode($sku));
//$productData = json_encode($estado);
        $headers = array(
            'Content-Type: application/json',
            'Authorization: Bearer ' . $token['token'],
        );

        /* $data = array(
        'product' => array(
        'status' => $atributos['status'],
        )
        ); */
//$atributos['price'] = 995.00;

        $data = array('product' => $atributos);

        $data = json_encode($data);

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);


        $result = json_decode($result);

        return $result;

//print_r($result);
}
    }


    /**
     * nueva función de búsqueda usando la api de magento 
     * https://developer.adobe.com/commerce/webapi/rest/use-rest/performing-searches/
     * 
     */
public static function searchCriteria(array $criterios){
    $token = self::getToken($criterios['idTienda']);
    $cadena = $token['url'] . 'index.php/rest/V1/products/?';
    $cadena .= '&searchCriteria[pageSize]=' . $criterios['pageSize']; // añadimos el tamaño de página también
    $cadena .= '&searchCriteria[currentPage]=' . $criterios['currentPage']; //añadimos el número de página

    //Añadimos los distintos campos de búsqueda
    foreach ($criterios['fields'] as $clave => $criterio) {
        $cadena .= '&searchCriteria[filter_groups][' . $clave . '][filters][0][field]=' . $criterio['field'] . '&searchCriteria[filter_groups][' . $clave . '][filters][0][value]=' . $criterio['value'] . '&searchCriteria[filter_groups][' . $clave . '][filters][0][condition_type]=' . $criterio['condition_type'];
    }
    $ch = curl_init($token['url'] . 'index.php/rest/V1/products/?' . $cadena);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "Authorization: Bearer " . $token['token']));

    $result = curl_exec($ch);
   //echo $cadena;
 //echo '<pre>';
//print_r($result);
//echo '</pre>';
    $result = json_decode($result);
    return $result;
}    

    //---------------------------------------------------//
        // Obtenemos la info de un articulo por el sku       //
        //---------------------------------------------------//
        //---------------------------------------------------//
    /**
     * [Obtenemos la info de un articulo por el sku]
     * @param  [Array] $criterios_busqueda [Criterios con los que vamos a filtrar la búsqueda]
     * @return [Array?]                     [Devuelve un array con los resultados de la búsqueda]
     */
        public static function buscarArticulos($criterios_busqueda, $developer = false)
        {
            $tienda = $criterios_busqueda['tienda'] ?? 1;

            $token = self::getToken($tienda);

            if (isset($criterios_busqueda['p'])) {
                $pagina = $criterios_busqueda['p'];
            }

            if (isset($criterios_busqueda['ap'])) {
                $articulos_pagina = $criterios_busqueda['ap'];
            }

            if (isset($criterios_busqueda['bsku'])) {
                $sku = $criterios_busqueda['bsku'];
            }
            if (isset($criterios_busqueda['mpn'])) {
                $sku = $criterios_busqueda['mpn'];
            }

            if (isset($criterios_busqueda['bnombre'])) {
                $nombre = $criterios_busqueda['bnombre'];
            }

            if (isset($criterios_busqueda['fin_oferta'])) {
                $fin_oferta = $criterios_busqueda['fin_oferta'];
            }

            if (isset($criterios_busqueda['bmarca'])) {
                $marca = $criterios_busqueda['bmarca'];
            }

            if (isset($criterios_busqueda['tipoarticulo'])) {
                $tipoarticulo = $criterios_busqueda['tipoarticulo'];
            }
            if (isset($criterios_busqueda['auto_actualizable'])) {
                $auto_actualizable = $criterios_busqueda['auto_actualizable'];
            }
            if (isset($criterios_busqueda['auto_actualiza_precio'])) {
                $auto_actualiza_precio = $criterios_busqueda['auto_actualiza_precio'];
            }
            if (isset($criterios_busqueda['ean'])) {
                $ean = $criterios_busqueda['ean'];
            }
            if (isset($criterios_busqueda['stock_local'])) {
                $stock_local = $criterios_busqueda['stock_local'];
            }

            $tipo_producto = 'simple'; //Por defecto vamos a dejar sólo los artículos simples

    //$fin_oferta = '2018-01-29 00:00:00';
            //Contamos el número de filtros que vamos a aplicar
            //Menos  el número de página y los articulos por página que siempre se pasan
            $contador_filtro = 0; //Iniciamos el contador de filtros a 0
            $cadena          = ''; //Iniciamos la cadena de búsqueda
            if (isset($pagina) && $pagina != '') {
                $cadena = '&searchCriteria[currentPage]=' . $pagina;
            }
            if (isset($articulos_pagina) && $articulos_pagina != '') {
                $cadena .= '&searchCriteria[pageSize]=' . $articulos_pagina;
            }

            if (isset($sku) && $sku != '') {
               // $cadena .= '&searchCriteria[filter_groups][' . $contador_filtro . '][filters][0][field]=sku&searchCriteria[filter_groups][' . $contador_filtro . '][filters][0][value]=%25' . $sku . '%25&searchCriteria[filter_groups][' . $contador_filtro . '][filters][0][condition_type]=like';
                $cadena .= '&searchCriteria[filter_groups][' . $contador_filtro . '][filters][0][field]=sku&searchCriteria[filter_groups][' . $contador_filtro . '][filters][0][value]=' . $sku . '&searchCriteria[filter_groups][' . $contador_filtro . '][filters][0][condition_type]=like';

                $contador_filtro++;
            }
            if (isset($nombre) && $nombre != '') {
                $cadena .= '&searchCriteria[filter_groups][' . $contador_filtro . '][filters][0][field]=name&searchCriteria[filter_groups][' . $contador_filtro . '][filters][0][value]=%25' . $nombre . '%25&searchCriteria[filter_groups][' . $contador_filtro . '][filters][0][condition_type]=like';
                $contador_filtro++;
            }

            if (isset($criterios_busqueda['special_to_date']) && $criterios_busqueda['special_to_date'] != '') {
                $hoy               = new \DateTime("now");
                $hoy_formateado    = $hoy->format('Y-m-d H:i:s');
                $cadena .= '&searchCriteria[filter_groups][' . $contador_filtro . '][filters][0][field]=special_to_date&searchCriteria[filter_groups][' . $contador_filtro . '][filters][0][value]=' . $criterios_busqueda['special_to_date']  . '&searchCriteria[filter_groups][' . $contador_filtro . '][filters][0][condition_type]=lt&searchCriteria[filter_groups][' . ($contador_filtro + 1) . '][filters][0][field]=special_to_date&searchCriteria[filter_groups][' . ($contador_filtro + 1) . '][filters][0][value]=' . $hoy_formateado . '&searchCriteria[filter_groups][' . ($contador_filtro + 1) . '][filters][0][condition_type]=gt';
            }
            if (isset($fin_oferta) && $fin_oferta != '') {
                $hoy               = new DateTime("now");
                $hoy_formateado    = $hoy->format('Y-m-d H:i:s');
                $hoy_dia           = $hoy->format('Y-m-d');
                $fin_oferta_objeto = new DateTime($fin_oferta);
                $fin_oferta_dia    = $fin_oferta_objeto->format('Y-m-d');
                if ($hoy_dia == $fin_oferta_dia) {
                    $cadena .= '&searchCriteria[filter_groups][' . $contador_filtro . '][filters][0][field]=special_to_date&searchCriteria[filter_groups][' . $contador_filtro . '][filters][0][value]=' . $fin_oferta . '&searchCriteria[filter_groups][' . $contador_filtro . '][filters][0][condition_type]=lt';
                } else {

              /*  $cadena .= '&searchCriteria[filter_groups][' . $contador_filtro . '][filters][0][field]=special_to_date&searchCriteria[filter_groups][' . $contador_filtro . '][filters][0][value]=' . $hoy_formateado . '&searchCriteria[filter_groups][' . $contador_filtro . '][filters][0][condition_type]=gt&searchCriteria[filter_groups][' . ($contador_filtro + 1) . '][filters][0][field]=special_to_date&searchCriteria[filter_groups][' . ($contador_filtro + 1) . '][filters][0][value]=' . $fin_oferta . '&searchCriteria[filter_groups][' . ($contador_filtro + 1) . '][filters][0][condition_type]=lt';*/
                            $cadena .= '&searchCriteria[filter_groups][' . $contador_filtro . '][filters][0][field]=special_to_date&searchCriteria[filter_groups][' . $contador_filtro . '][filters][0][value]=' . $fin_oferta . '&searchCriteria[filter_groups][' . $contador_filtro . '][filters][0][condition_type]=lt&searchCriteria[filter_groups][' . ($contador_filtro + 1) . '][filters][0][field]=special_to_date&searchCriteria[filter_groups][' . ($contador_filtro + 1) . '][filters][0][value]=' . $hoy_formateado . '&searchCriteria[filter_groups][' . ($contador_filtro + 1) . '][filters][0][condition_type]=gt';
                }
                $contador_filtro++;
            }
            if (isset($marca) && $marca != '') {
                $nombre_attr = $tienda == 1 ? 'manufacturer' : 'product_brand';
                $cadena .= '&searchCriteria[filter_groups][' . $contador_filtro . '][filters][0][field]='.  $nombre_attr . '&searchCriteria[filter_groups][' . $contador_filtro . '][filters][0][value]=' . $marca . '&searchCriteria[filter_groups][' . $contador_filtro . '][filters][0][condition_type]=eq';
                $contador_filtro++;
            }
            if (isset($ean) && $ean != '') {
                $cadena .= '&searchCriteria[filter_groups][' . $contador_filtro . '][filters][0][field]=ean&searchCriteria[filter_groups][' . $contador_filtro . '][filters][0][value]=' . $ean . '&searchCriteria[filter_groups][' . $contador_filtro . '][filters][0][condition_type]=eq';
                $contador_filtro++;
            }
            if (isset($tipoarticulo) && $tipoarticulo != '') {
                $cadena .= '&searchCriteria[filter_groups][' . $contador_filtro . '][filters][0][field]=attribute_set_id&searchCriteria[filter_groups][' . $contador_filtro . '][filters][0][value]=' . $tipoarticulo . '&searchCriteria[filter_groups][' . $contador_filtro . '][filters][0][condition_type]=eq';
                $contador_filtro++;
            }
            if (isset($auto_actualizable) && $auto_actualizable != '') {
                $cadena .= '&searchCriteria[filter_groups][' . $contador_filtro . '][filters][0][field]=auto_actualizable&searchCriteria[filter_groups][' . $contador_filtro . '][filters][0][value]=' . $auto_actualizable . '&searchCriteria[filter_groups][' . $contador_filtro . '][filters][0][condition_type]=eq';
                $contador_filtro++;
            }
            if (isset($auto_actualiza_precio) && $auto_actualiza_precio != '') {
                $cadena .= '&searchCriteria[filter_groups][' . $contador_filtro . '][filters][0][field]=auto_actualiza_precio&searchCriteria[filter_groups][' . $contador_filtro . '][filters][0][value]=' . $auto_actualiza_precio . '&searchCriteria[filter_groups][' . $contador_filtro . '][filters][0][condition_type]=eq';
                $contador_filtro++;
            }
            if (isset($stock_local) && $stock_local != '') {
                $cadena .= '&searchCriteria[filter_groups][' . $contador_filtro . '][filters][0][field]=stock_local&searchCriteria[filter_groups][' . $contador_filtro . '][filters][0][value]=' . $stock_local . '&searchCriteria[filter_groups][' . $contador_filtro . '][filters][0][condition_type]=eq';
                $contador_filtro++;
            }
                    if (isset($tipo_producto) && $tipo_producto != '') {
                $cadena .= '&searchCriteria[filter_groups][' . $contador_filtro . '][filters][0][field]=type_id&searchCriteria[filter_groups][' . $contador_filtro . '][filters][0][value]=' . $tipo_producto . '&searchCriteria[filter_groups][' . $contador_filtro . '][filters][0][condition_type]=eq';
                $contador_filtro++;
            }
         //   echo $cadena;
            $ch = curl_init($token['url'] . 'index.php/rest/V1/products/?' . $cadena);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "Authorization: Bearer " . $token['token']));

            $result = curl_exec($ch);
            $result = json_decode($result);
          //  echo '<pre>';
           // print_r($result);
           // echo '</pre>';
         /*   if (isset($result->items)) {
                $resultado = $result->items;
            } else {
                $resultado = $result;
            }*/
            $resultado = $result;
          //  return $resultado;
          if ($developer === false) {
            return $resultado;
          } else {
            return $token;
          }

        }

/**
 * Busqueda de articulos para la actualizacion de precios
 */
     public static function busquedaArticulosActualizacion($criterios, $id_tienda) {
 
        $token = self::getToken($id_tienda);   
        //Definimos las variables para la búsqueda
      //  $arr['auto_actualizable'] = 1; //Solo vamos a coger los autoactualizables
       if (isset($criterios['conjunto_atributos'])) $arr['attribute_set_id'] =  $criterios['conjunto_atributos'];
       if (isset($criterios['min_precio'])) $min_precio =  $criterios['min_precio'];
       if (isset($criterios['max_precio'])) $max_precio =  $criterios['max_precio'];
       if (isset($criterios['fabricante'])) {
switch ($id_tienda) {
    case 2:
        $arr['product_brand'] =  $criterios['fabricante'];
        break;
         case 1:
        default:
        $arr['manufacturer'] =  $criterios['fabricante'];
        break;

    
}
       }
$criterios['status'] = '1'; //Solo vamos a recuperar los activos
       
       


       $arr['type_id'] = 'simple'; //Por defecto vamos a dejar sólo los artículos simples

       //Componemos la cadena
       $contador_filtro = 0; //Iniciamos el contador de filtros a 0
       $cadena          = ''; //Iniciamos la cadena de búsqueda
       //Filtros eq
       foreach ($arr as $campo => $valor) {
        $cadena .= '&searchCriteria[filter_groups][' . $contador_filtro . '][filters][0][field]=' . $campo . '&searchCriteria[filter_groups][' . $contador_filtro . '][filters][0][value]=' . $valor . '&searchCriteria[filter_groups][' . $contador_filtro . '][filters][0][condition_type]=eq';
        $contador_filtro++;
       }
       //Otros campos
       if (isset($min_precio) && $min_precio != '' && $min_precio > 0) {      
        $cadena .= '&searchCriteria[filter_groups][' . $contador_filtro . '][filters][0][field]=price&searchCriteria[filter_groups][' . $contador_filtro . '][filters][0][value]=' . $min_precio . '&searchCriteria[filter_groups][' . $contador_filtro . '][filters][0][condition_type]=gt';
        $contador_filtro++;
       }
       if (isset($max_precio) && $max_precio != '' && $max_precio > 0) {      
        $cadena .= '&searchCriteria[filter_groups][' . $contador_filtro . '][filters][0][field]=price&searchCriteria[filter_groups][' . $contador_filtro . '][filters][0][value]=' . $min_precio . '&searchCriteria[filter_groups][' . $max_precio . '][filters][0][condition_type]=lt';
        $contador_filtro++;
       }
       $pagesize = 50; 
       $p = 1;
       $articulos = array();
       do {
        $url = $token['url'] . 'index.php/rest/V1/products/?&searchCriteria[currentPage]=' . $p . '&searchCriteria[pageSize]='. $pagesize .$cadena;
       // echo $url;
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "Authorization: Bearer " . $token['token']));
        $result = curl_exec($ch);
        $result = json_decode($result);
     //   echo '<pre>';
      //  print_r($result);
      //  echo '</pre>';
        $articulos = array_merge($articulos, $result->items);
        $p++;
       } while (count($result->items) >= $pagesize);




        return $articulos;
     }

        //---------------------------------------------------//
            // Obtenemos la info de un articulo por el sku       //
            //---------------------------------------------------//
            public static function getinfoArticulo($sku,$id_tienda = 1)
            {
 
                $token = self::getToken($id_tienda);  
                $ch    = curl_init($token['url'] . 'index.php/rest/V1/products/' . $sku);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "Authorization: Bearer " . $token['token']));

                $result = curl_exec($ch);
                $result = json_decode($result);

                return $result;
            }


    /**
     * Función que busca un artículo por referencia, ean o cualquier otra referencia que definamos.
     * La idea es que reemplace getinfoArticulo al ser esta más potente, pasando la otra a ser privada
     * Para obtener la mayor información posible una vez localizamos el artículo llamamos al metodo getinfoArticulo
     * @param  array $row Array asociativo con clave mpn o ean
     * @return stdClass Object      Articulo de magento
     */
                public static function getinfoArticuloReferencia($row,$id_tienda = 1)
                {
                    $token = self::getToken($id_tienda);                                      //Obtenemos el token para trabajar
    
      if (isset($row['mpn']) && !empty($row['mpn'])) {                              //Damos prioridad al mpn
      $mpn = urlencode($row['mpn']);
      $result = self::getinfoArticulo($mpn,$id_tienda);
    }
    /*
    Si no se encuentra el artículo tenemos el siguiente valor en este momento
    stdClass Object
    (
        [message] => Requested product doesn't exist
    )
    En ese caso vamos a buscar por el ean
     */
    if ((isset($row['ean']) && !empty($row['ean'])) && (!isset($row['mpn']) || !empty($result->message))) {
      $result = self::buscarArticulos($row);
      if (!empty($result)) {
        $mpn = urlencode($result[0]->sku);
    $result = self::getinfoArticulo($mpn);
      }
    }

                    return $result;
                }

/**
 * https://adobe-commerce.redoc.ly/2.4.6-admin/tag/productsspecial-price-delete
 */
public static function eliminarOferta($datos){
$id_tienda = $datos['plataforma'] == 'mage245' ? 2 : 1;

$sku = $datos['sku'];

$arr = array(
   'prices' => array([
    'price' => 0,
    'store_id' => 0,
    'sku' => $sku,
    'price_from' =>'1970-01-01 00:00:00',
    'price_to' => '1970-01-01 00:00:00'
   ]) 
);

    $token = self::getToken($id_tienda);
    $ch    = curl_init($token['url'] . 'index.php/rest/V1/products/special-price-delete');
    $headers = array(
        'Content-Type: application/json',
        'Authorization: Bearer ' . $token['token'],
    );

    $data = json_encode($arr);
   curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($ch);
    $result    = json_decode($result);
    $resultado = $result;
    return $resultado;
}

public static function eliminarArticulo($articulo)
            {
        //Comprobamos si estamos recibiendo el sku, sin este dato no podemos hacer nada
                //Tambien contamos el número de campos que estamos recibiendo, porque con sólo el sku tampoco hacemos mucho
                if (isset($articulo['sku'])) {
                    $sku = $articulo['sku'];

                    $token = self::getToken();
              //      $ch    = curl_init(self::URL . 'index.php/rest/all/V1/products/' . $sku);
                    $ch    = curl_init($token['url'] . 'index.php/rest/V1/products/' . $sku);
                    $headers = array(
                        'Content-Type: application/json',
                        'Authorization: Bearer ' . $token['token'],
                    );
                    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
                    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
              //      curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    $result = curl_exec($ch);

                    $result    = json_decode($result);
                    $resultado = $result;
                    //return $result;
                    //return $data;
                } else {
                    //Si no tenemos el sku la operaciṕon no tiene sentido
                    $resultado->mensaje = 'error';
                }
                //Salida de la funcion
                return $resultado;

            }


public static function updateCost($arr_costs){
    $token = self::getToken(2);    //Trabajamos directamente sobre la tienda nueva
    $ch    = curl_init($token['url'] . 'index.php/rest/V1/products/cost');
    $headers = array(
        'Content-Type: application/json',
        'Authorization: Bearer ' . $token['token'],
    );

    $data = json_encode($arr_costs);
   curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($ch);
        echo '<pre>';
print_r($result);
echo '</pre>';
    $result    = json_decode($result);
    $resultado = $result;
    return $resultado;
}

/**
 * Devuelve un array asociativo con los custom o extension attrs de un artículo dado
 * 
 */
public static function getAttrs($articulo,$tipo_attrs){
    $codes = array_column($articulo->$tipo_attrs,'attribute_code');
    $values = array_column($articulo->$tipo_attrs,'value');
    $custom = array_combine($codes,$values); //Un array asociativo con los custom attributes
    return $custom;
}


}


 ?>

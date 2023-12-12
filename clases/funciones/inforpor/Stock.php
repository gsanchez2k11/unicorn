<?php

namespace unicorn\clases\funciones\inforpor;
require_once 'Conectar.php';
require_once 'Codigos.php';
require_once 'Pedido.php';
require_once 'Otros.php';
require_once 'Tarifa.php';
require_once RAIZ . '/clases/funciones/unicorn_db/Entidad.php';
require_once RAIZ . '/clases/objetos/Stock/Custodia.php';
require_once RAIZ . '/clases/funciones/otras/Moneda.php';
use unicorn\clases\funciones\inforpor\Codigos as codigos;
use unicorn\clases\funciones\inforpor\Pedido as pedido;
use unicorn\clases\funciones\inforpor\Otros as otros;
use unicorn\clases\objetos\Articulos\Custodia as custodia;
use unicorn\clases\funciones\unicorn_db\Entidad as entidad;
use unicorn\clases\funciones\unicorn_db\Config as config;
use unicorn\clases\funciones\otras\Moneda as moneda;
use unicorn\clases\funciones\inforpor\Tarifa as tarifa_inforpor;
 ?>
 <?php
/**
 *
 */
class Stock extends Conectar
{

/**
 * Método ListaCodigosProm para conocer los códigos promocionales aplicables a un artículo
 * @param int $Cod_inforpor Código de inforpor del artículo.
 * @return Array $codigos_promo Array vacío o con los distintos códigos promocionales
 */
  public static function ListaCodigosProm($Cod_inforpor){
    $cliente = self::Crearcliente();
    $param  = array('CIF' => self::CIF, 'User' => self::USER, 'Clave' => self::CLAVE, 'Cod' => $Cod_inforpor);
    $result = $cliente->call('ListaCodigosProm', array('parameters' => $param), '', '', false, true);

  $codigos =  $result['ListaCodigosPromResult'] == 'Error Codigo' ? []: otros::stringToArray($result['ListaCodigosPromResult']); //Si da error el código devolvemos un array vacío

    return $codigos;
  }


/**
 * Método stockPr para devolver el stock normal de un articulo
 * @param [int] $Cod_inforpor Código de inforpor del artículo.
 * Devuelve un array
 * [StockPrResult] => Array
*        (
*            [CodErr] => 0
*            [Cod] => 41241
*            [Referencia] => WT42F-001
*            [Stock] => 8
*            [Precio] => 5,68
*            [lpi] => 0,0000
*            [CodigoPromocion] =>
*            [maxUd] =>
*            [EAN] => 8436574703238
*            [FechaEntrada] =>
*        )
**/
public static function StockPr($Cod_inforpor){
  $cliente = self::Crearcliente();
//Pedimos los códigos promocionales para ese artículo
$codigos_promo = self::ListaCodigosProm($Cod_inforpor);

if (count($codigos_promo) < 2) { //Si tenemos menos de 2 códigos lo podemos arreglar con una sola petición
 $cod_promo = isset($codigos_promo[0]) ? $codigos_promo[0] : '';

  //Ajustamos los parametros para usar el método StockPr
    $param  = array('CIF' => self::CIF, 'User' => self::USER, 'Clave' => self::CLAVE, 'Cod' => $Cod_inforpor, 'CodigoPromo' => $cod_promo);
    $result = $cliente->call('StockPr', array('parameters' => $param), '', '', false, true);
} else { //Si tenemos 2 o más códigos de promoción hacemos todas las peticiones y nos quedamos con la más favorable
  foreach ($codigos_promo as $key => $cod_promo) {
    $param  = array('CIF' => self::CIF, 'User' => self::USER, 'Clave' => self::CLAVE, 'Cod' => $Cod_inforpor, 'CodigoPromo' => $cod_promo);
    $resultado = $cliente->call('StockPr', array('parameters' => $param), '', '', false, true);
    $result = !isset($result['StockPrResult']['Precio']) || $resultado['StockPrResult']['Precio'] < $result['StockPrResult']['Precio'] ? $resultado : $result;
  }
}
//$cod_promo = '';
//$param  = array('CIF' => self::CIF, 'User' => self::USER, 'Clave' => self::CLAVE, 'Cod' => $Cod_inforpor/*, 'CodigoPromo' => $cod_promo*/);
//$result = $cliente->call('StockPr', array('parameters' => $param), '', '', false, true);
$ean_recibido = isset($result['StockPrResult']['EAN']) ? otros::standarizarEan($result['StockPrResult']['EAN']) : 0000000000000; //Queremos un EAN con 13 dígitos
$fecha_entrada = isset($result['StockPrResult']['FechaEntrada']) ? $result['StockPrResult']['FechaEntrada']: '';
$lpi = isset($result['StockPrResult']['lpi']) ? self::formatearImporte($result['StockPrResult']['lpi']): 0;
$codigo = isset($result['StockPrResult']['Cod']) ? $result['StockPrResult']['Cod']: 0000;
$cod_promo = isset($result['StockPrResult']['CodigoPromocion']) ? $result['StockPrResult']['CodigoPromocion']: '';
$precio = isset($result['StockPrResult']['Precio']) ? self::formatearImporte($result['StockPrResult']['Precio']) : 0;
$referencia = isset($result['StockPrResult']['Referencia']) ? $result['StockPrResult']['Referencia']: '-';
$stock = isset($result['StockPrResult']['Stock']) ? $result['StockPrResult']['Stock']: 0;
$max_uds = isset($result['StockPrResult']['maxUd']) ? $result['StockPrResult']['maxUd'] : 0;
//Preparamos un array con los datos, habría que utilizar un objeto mejor
$resultado = array(
  'Cod' =>  $codigo,
  'CodErr' =>  $result['StockPrResult']['CodErr'],
    'CodigoPromocion' =>  $cod_promo,
      'EAN' =>  $ean_recibido,
        'FechaEntrada' =>  $fecha_entrada,
          'Precio' =>  $precio,
          'Referencia' =>  $referencia,
          'Stock' =>  $stock,
          'lpi' =>  $lpi,
          'maxUd' =>  $max_uds
);
return $resultado;
//return $result;
}


public static function SiReserva($Cod_inforpor)
{
  $reservas = 0;
  $cliente = self::Crearcliente();
  //Ajustamos los parametros para usar el método SiReserva
    $param  = array('CIF' => self::CIF, 'User' => self::USER, 'Clave' => self::CLAVE, 'CodInf' => $Cod_inforpor);
    $result = $cliente->call('SiReserva', array('parameters' => $param), '', '', false, true);
//Tenemos un array SiReservaResult
//[SiReservaResult] => Array
//        (
//            [LinReserva] => Array
//                (
//                    [cerr] => 0
//                    [codinf] => 31351
//                    [cant] => 1
//                    [idreserva] => 587007
//                    [stock] => 0
//                )
//
//        )
//if (isset($result['SiReservaResult']['LinReserva']['cant']) && $result['SiReservaResult']['LinReserva']['cant'] > 0) {
//  $reservas = $result['SiReservaResult']['LinReserva']['cant'];
//}
return $result;
}


/*public static function SiCustodia($Cod_inforpor) {
  $obj_cust = false;
  //Creamos un array vacio, que va a ser lo minimo que vamos a devolver
  $custodias = array();
  $cliente = self::Crearcliente();
  //Ajustamos los parametros para usar el método StockPr
$param  = array('CIF' => self::CIF, 'User' => self::USER, 'Clave' => self::CLAVE, 'CodInf' => $Cod_inforpor);
$result = $cliente->call('SiCustodia', array('parameters' => $param), '', '', false, true);
if (isset($result['SiCustodiaResult']['LinCustodia'])) {
$lineas_custodias = otros::ConvertirArrayUniMulti($result['SiCustodiaResult']['LinCustodia']);   //Nos aseguramos que el array sea multidimensional
foreach ($lineas_custodias as $cust) {
  if (isset($cust['codinf'])) {
$custodias[] = $cust;
  }
}
}

//Tenemos un array vacio o con custodias
if(!empty($custodias)) {
  foreach ($custodias as $custodia) {                                           //Recorremos las custodias para pedir el pedido de cada una
    if (!empty($custodia['Npedido'])) {
$row['NumPedInf'] = $custodia['Npedido'];
//$ped_inforpor = pedido::EstadoPedido($row);                                     //Obtenemos el pedido
$ped_ifp = pedido::BuscaArticuloEnPedido($custodia['codinf'],$row);
//echo "<pre>";
//print_r($ped_ifp);
//echo "</pre>";
$art_ifp = self::StockPr($custodia['codinf']);                                  //Buscamos la info del artículo suelto
//Comprobamos que tengamos los datos de manera correcta
if (count($ped_ifp) ==1 && isset($ped_ifp[0]['codinf'])) {
//Creamos un objeto custodia
$arr_obj_cust = array(
  'codinf' => $custodia['codinf'],
  'quedan' => $custodia['cant'],
  'pedido' => $custodia['Npedido'],
  'id_custodia' => $custodia['idcustodia'],
  'stock_original' => $ped_ifp[0]['cant'],
  'lpi'    =>  $art_ifp['lpi'],
  'precio' => self::formatearImporte($ped_ifp[0]['precio'])
);

$obj_cust[] = new Custodia($arr_obj_cust);
}

}

  }
}
return $obj_cust;
}*/


public static function SiCustodiaObj($Cod_inforpor) {

  $obj_cust = false;
  //Creamos un array vacio, que va a ser lo minimo que vamos a devolver
  $custodias = array();
  $cliente = self::Crearcliente();
  //Ajustamos los parametros para usar el método StockPr
//$param  = array('CIF' => self::CIF, 'User' => self::USER, 'Clave' => self::CLAVE, 'CodInf' => $Cod_inforpor);
$param  = array('CIF' =>self::dameValorC('cif'), 'User' => self::dameValorC('user'), 'Clave' => self::dameValorC('pass'), 'CodInf' => $Cod_inforpor);
$result = $cliente->call('SiCustodia', array('parameters' => $param), '', '', false, true);
//echo '<pre>';
//print_r($result);
//echo '</pre>';
if (isset($result['SiCustodiaResult']['LinCustodia'])  || isset($result['SiCustodiaResult']['LinCustudia'])) {
  $lin_custo = isset($result['SiCustodiaResult']['LinCustodia']) ? $result['SiCustodiaResult']['LinCustodia'] : $result['SiCustodiaResult']['LinCustudia'];
$lineas_custodias = otros::ConvertirArrayUniMulti($lin_custo);   //Nos aseguramos que el array sea multidimensional
  foreach ($lineas_custodias as $cust) {
  if (isset($cust['codinf'])) {
$custodias[] = $cust;
  }
}
}
//Tenemos un array vacio o con custodias
if(!empty($custodias)) {
  foreach ($custodias as $custodia) {                                           //Recorremos las custodias para pedir el pedido de cada una
    if (!empty($custodia['Npedido'])) {
$row['NumPedInf'] = $custodia['Npedido'];                                   //Obtenemos el pedido
$ped_ifp = pedido::BuscaArticuloEnPedidoObj($custodia['codinf'],$row);
$art_ifp = self::StockPr($custodia['codinf']);                                  //Buscamos la info del artículo suelto
if (count($ped_ifp) ==1) {
  $arr_obj_cust = array(
    'codinf' => $custodia['codinf'],
    'quedan' => $custodia['cant'],
    'pedido' => $custodia['Npedido'],
    'id_custodia' => $custodia['idcustodia'],
    'stock_original' => $ped_ifp[0]->getCant(),
    'lpi'    =>  $art_ifp['lpi'],
  //  'precio' => self::formatearImporte($ped_ifp[0]->getPrecio())
   'precio' => $ped_ifp[0]->getPrecio()
  );
  $obj_cust[] = new Custodia($arr_obj_cust);
}

}

  }
}
return $obj_cust;
}

public static function SiCustodiaObjDev($Cod_inforpor) {

  $obj_cust = false;
  //Creamos un array vacio, que va a ser lo minimo que vamos a devolver
  $custodias = array();
  $cliente = self::Crearcliente();
  //Ajustamos los parametros para usar el método StockPr
$param  = array('CIF' =>self::dameValorC('cif'), 'User' => self::dameValorC('user'), 'Clave' => self::dameValorC('pass'), 'CodInf' => $Cod_inforpor);
$result = $cliente->call('SiCustodia', array('parameters' => $param), '', '', false, true);
if (isset($result['SiCustodiaResult']['LinCustudia']) || isset($result['SiCustodiaResult']['LinCustodia'])) {
  $lin_custo = isset($result['SiCustodiaResult']['LinCustodia']) ? $result['SiCustodiaResult']['LinCustodia'] : $result['SiCustodiaResult']['LinCustudia'];
$lineas_custodias = otros::ConvertirArrayUniMulti($lin_custo);   //Nos aseguramos que el array sea multidimensional
foreach ($lineas_custodias as $cust) {
  if (isset($cust['codinf'])) {
$custodias[] = $cust;
  }
}
}

//Tenemos un array vacio o con custodias
if(!empty($custodias)) {
  foreach ($custodias as $custodia) {                                           //Recorremos las custodias para pedir el pedido de cada una
    if (!empty($custodia['Npedido'])) {
$row['NumPedInf'] = $custodia['Npedido'];                                   //Obtenemos el pedido
$ped_ifp = pedido::BuscaArticuloEnPedidoObj($custodia['codinf'],$row);
$art_ifp = self::StockPr($custodia['codinf']);                                  //Buscamos la info del artículo suelto
if (count($ped_ifp) ==1) {
  $arr_obj_cust = array(
    'codinf' => $custodia['codinf'],
    'quedan' => $custodia['cant'],
    'pedido' => $custodia['Npedido'],
    'id_custodia' => $custodia['idcustodia'],
    'stock_original' => $ped_ifp[0]->getCant(),
    'lpi'    =>  $art_ifp['lpi'],
  //  'precio' => self::formatearImporte($ped_ifp[0]->getPrecio())
   'precio' => $ped_ifp[0]->getPrecio()
  );
  $obj_cust[] = new Custodia($arr_obj_cust);
}

}

  }
}
return $obj_cust;
}



/*
public static function ObtenerCompraInforpor($articulo, $developer = false)
  {
//HAcemos la llamada que nos devuelve el código de inforpor de la referencia
$Cod_inforpor = codigos::DameCodigosInforpor($articulo);
//Añadimos o actualizamos el codigo asociado a esa Referencia
if (!empty($Cod_inforpor) && $Cod_inforpor > 0) {
$actualiza_cod = entidad::insertaArticuloEntidadInt($articulo['entidad'],'5',$Cod_inforpor);
}
//Buscamos el stock normal de esa Referencia
$stock_pr = self::StockPr($Cod_inforpor);
//Buscamos las reservas
$datos_reserva = self::SiReserva($Cod_inforpor);
//Buscamos las custodias
$custodias = self::SiCustodiaObj($Cod_inforpor);

$compra_inforpor = array(
  'normal_inforpor' => $stock_pr,
  'reserva_inforpor' => $datos_reserva,
  'custodias' => $custodias
);
//Comprobamos si el artículo tiene o no custodia y actualizamos la info en la base de datos
if (isset($articulo['entidad'])) {
if (!empty($compra_inforpor['custodias'])) {
$add_cust = entidad::actualizaArticuloEntidadInt($articulo['entidad'],'1','1');
} else {
$add_cust = entidad::actualizaArticuloEntidadInt($articulo['entidad'],'1','0');
}
}


return $compra_inforpor;
}*/

  public static function ObtenerCompraInforpor($articulo, $developer = false)
    {
  if ($developer === true) $tiempo_inicial = microtime(true); //true es para que sea calculado en segundos;
  //Primero comprobamos si estamos recibiendo directamente el código de inforpor
  if (isset($articulo['cod_inforpor']) && $articulo['cod_inforpor'] != 0) {
    $Cod_inforpor_bd = $articulo['cod_inforpor'];
  } elseif(isset($articulo['entidad'])) {
  $Cod_inforpor_bd = entidad::dameValorArticuloEntidadInt($articulo['entidad'],'5');
  } elseif (isset($articulo['ean'])) {
    $arr =  array(
      'valor' => $articulo['ean'],
      'cod_atributo' => 3,
      'cod_atributo_buscado' => 5
    );
    $Cod_inforpor_bd  = entidad::getAtributoporAtributo(isset($articulo['mpn']));
  }

  //echo '<pre>';
  //print_r($Cod_inforpor_bd);
  //echo '</pre>';

  if (!isset($Cod_inforpor_bd) || empty($Cod_inforpor_bd)) {
    //HAcemos la llamada que nos devuelve el código de inforpor de la referencia
    $Cod_inforpor = codigos::DameCodigosInforpor($articulo);
  } else {
    $Cod_inforpor = $Cod_inforpor_bd;
  }
  //Añadimos o actualizamos el codigo asociado a esa Referencia
  if (!empty($Cod_inforpor) && $Cod_inforpor > 0 && isset($articulo['entidad'])) {
  $actualiza_cod = entidad::insertaArticuloEntidadInt($articulo['entidad'],'5',$Cod_inforpor);
  }

if ($Cod_inforpor !== 'ERROR') { //Si la clave que estamos utilizando no es válida, devuelve error
  //Buscamos el stock normal de esa Referencia
  $stock_pr = self::StockPr($Cod_inforpor);
  if ($developer === true) {
    print_r($stock_pr);
    $tiempo_final = microtime(true);
    $tiempo = $tiempo_final - $tiempo_inicial; //este resultado estará en segundos
    echo "El tiempo en utilizar el método stockPr ha sido  " . $tiempo . " segundos";
  }
  //Buscamos las reservas
  $datos_reserva = self::SiReserva($Cod_inforpor);
  //Buscamos las custodias
  $custodias = self::SiCustodiaObj($Cod_inforpor);

  $compra_inforpor = array(
    'normal_inforpor' => $stock_pr,
    'reserva_inforpor' => $datos_reserva,
    'custodias' => $custodias
  );


  //Comprobamos si el artículo tiene o no custodia y actualizamos la info en la base de datos
  if (isset($articulo['entidad'])) {
  if (!empty($compra_inforpor['custodias'])) {
  $add_cust = entidad::actualizaArticuloEntidadInt($articulo['entidad'],'1','1');
  } else {
  $add_cust = entidad::actualizaArticuloEntidadInt($articulo['entidad'],'1','0');
  }
  }
} else {
  tarifa_inforpor::buscaEnTarifa($articulo);
  $compra_inforpor = array();
}

  return $compra_inforpor;
    }

/**
 * Devuelve el precio de compra para un articulo ¿Sin iva? con LPI
 */
    public static function damePrecioCompraInforpor($articulo, $developer = false)
    {
$compra = self::ObtenerCompraInforpor($articulo);
$lpi = isset($compra['normal_inforpor']['lpi']) ? $compra['normal_inforpor']['lpi'] : 0;              //Aislamos el lpi
$precio_normal = isset($compra['normal_inforpor']['Precio']) && $compra['normal_inforpor']['Precio'] > 0 ? $compra['normal_inforpor']['Precio'] : 0;
$precio_inforpor = moneda::cadenaAnumero($precio_normal);
$precio_inforpor_portes = $precio_inforpor > config::dameValorConfig('inforpor_minimo_portes_gratis') ? $precio_inforpor : $precio_inforpor + (double) config::dameValorConfig('inforpor_precio_tarifa_plana_portes'); //Le sumamos los portes si no llegamos a 60
$precio_inforpor_canon = $precio_inforpor_portes + $lpi;                        //Le sumamos el canon
//custodias
$stock_custodias = 0;
if (!empty($inforpor['custodias'])) {
  foreach ($inforpor['custodias'] as $keyc => $custodia) {
    $stock_custodias += $custodia->getQuedan();
    if ($custodia->getQuedan() >  0 && ($keyc === 0 || $custodia->getTotalCompra() > $precio_inforpor_canon)) {
    $precio_inforpor_canon = moneda::cadenaAnumero($custodia->getTotalCompra()/1.21);
  }
  }
}
return $precio_inforpor_canon;
    }


    public static function calculaMargenNueva($articulo, $oferta, $plataforma){        
      $lpi = $articulo['lpi'];          //Aislamos el lpi
      $precio_normal = $articulo['precio'];
      $portes = $precio_normal > config::dameValorConfig('inforpor_minimo_portes_gratis') ? 0 : (double) config::dameValorConfig('inforpor_precio_tarifa_plana_portes'); 
      $precio_compra = $precio_normal + $portes;

      $precio_venta = $plataforma == 'mage' ? $oferta['precio'] * 1.21 : $oferta['precio']; //Si la plataforma es magento estamos recibiendo el precio sin IVA, en caso contrario lo hacemos con

      $porciento_comision = isset($oferta['comision']) ? $oferta['comision'] / 100 : 0;                                        //Preparamos el factor para calcular el margen
      $importe_comision = $precio_venta * floatval($porciento_comision); //Calculamos el importe de la comisión
      //Muy importante, PC componentes aplica el IVA a la comisión, MediaMarkt no, por tanto si es PCC lo tenemos que incrementar
      if ($plataforma == 'pcc') {
        $importe_comision = $importe_comision * 1.21;
      }
      $total_menos_comision = $precio_venta - $importe_comision; //Le restamos el importe de la comisión al total, porque no computa para el margen
      $total_neto = $total_menos_comision / 1.21; //Precio de venta sin IVA
      $total_neto_sin_lpi = $total_neto - $lpi; //Restamos tambien el LPI para dejarlo fuera del cálculo del margen
      $margen =number_format(($total_neto_sin_lpi / $precio_compra - 1) * 100,2,'.',''); //Hacemos el cálculo con los portes como gasto
      $beneficio = number_format($total_menos_comision - $precio_compra*1.21,2,'.','');
      $resultado = array(
        'margen' => $margen,
        'beneficio' => $beneficio,
       /* 'datos' => array (
          'lpi' => $lpi,
          'precio_normal' => $precio_normal,
          'portes' => $portes,
          'precio_compra' => $precio_compra,
          'precio_venta' => $precio_venta,
          'porciento_comision' => $porciento_comision,
          'importe_comision' => $importe_comision,
          'total_sin_comision' => $total_menos_comision,
          'total_neto' => $total_neto,
          'total_neto_sin_lpi' => $total_neto_sin_lpi
        )*/
      );

      return $resultado;

    }
    public static function calculaMargen($articulo, $ref){

     $lpi = isset($articulo['normal_inforpor']['lpi']) ? $articulo['normal_inforpor']['lpi'] : 0;              //Aislamos el lpi
      $precio_normal = isset($articulo['normal_inforpor']['Precio']) && $articulo['normal_inforpor']['Precio'] > 0 ? moneda::cadenaAnumero($articulo['normal_inforpor']['Precio']) : 0;
$portes = $precio_normal > 60 ? 0 : 3.2;
//$precio_compra = $lpi + $precio_normal + $portes;
$precio_compra = $precio_normal + $portes;

$precio_venta = $ref['precio'];
$porciento_comision = $ref['comision'] / 100;                                        //Preparamos el factor para calcular el margen
$importe_comision = $precio_venta * $porciento_comision; //Calculamos el importe de la comisión
$importe_comision = $importe_comision * 1.21; //Al importe de la comisión le sumamos el IVA, según la última actualización de condiciones
$total_sin = $precio_venta - $importe_comision; //Se lo restamos al precio de venta

$total_neto = $total_sin / 1.21; //Precio de venta sin IVA
$total_neto_sin_lpi = $total_neto - $lpi;
$margen =number_format(($total_neto_sin_lpi / $precio_compra - 1) * 100,2,'.',''); //Hacemos el cálculo con los portes como gasto
$beneficio = number_format($total_neto_sin_lpi - $total_neto_sin_lpi,2,'.','');
$resultado = array(
  'margen' => $margen,
  'beneficio' => $beneficio,
  'compra' => $precio_normal
);

/*echo '<pre>';
print_r($ref);
echo $factor_comision. '<br>';
echo $precio_venta . ' | ' . $total_sin_comision . '<br>';
print_r($total_neto_sin_lpi_portes);
echo '<br>';
print_r($resultado);
echo '</pre>';*/

      return $resultado;

    }

  public static  function generaStockMargen($oferta) {
      $stock =  array(
        'margen' => 0,
        //'beneficio_neto' => 0,
        'compra_neto' => 0
      );
      //Calculamos primero el stock
      if (is_numeric($oferta['stock_local'])) { //Si el stock local es un número directamente lo asignamos como stock
        $stock['cantidad'] = $oferta['stock_local'];
        $stock['fuente']  = 'stock local'; //Añadimos la entrada al array
        //Si queremos disponer del precio de compra tendremos que añadir también un atributo en la bd
        } else { //Si no tenemos stock local buscamos en inforpor
          $inforpor = self::ObtenerCompraInforpor($oferta);
          if (!is_numeric($inforpor['normal_inforpor']['Cod']) || $inforpor['normal_inforpor']['CodErr'] == 'Producto vacio' || $inforpor['normal_inforpor']['Precio'] == 0) {//Si no hay compra en inforpor significa que no tenemos datos de compra para este artículo, por tanto no podemos añadirlos
            $stock['cantidad'] = 0;
            $stock['fuente'] = 'no hay compra'; //Añadimos la entrada al array
          } else {
            $stock['cantidad'] = self::numeroStockInforpor($inforpor, $oferta); //Añadimos la entrada al array
            $stock['fuente'] = 'inforpor'; //Añadimos la entrada al array
      /**
       * Por ahora lo dejamos así, pero si queremos calcular márgenes con stock local tenemos que modificar esto
       */
      if (isset($oferta['comision'])) {
        $margen = self::calculaMargen($inforpor, $oferta);
      
        $stock['margen'] = $margen['margen'];
      //  $stock['beneficio_neto'] = $margen['beneficio'];
        $stock['compra_neto'] = $margen['compra'];
      }
      
          }
        }
      
       return $stock; 
       }
    

       public static function numeroStockInforpor($inforpor,$oferta){
        $descontar_stock_inforpor = config::dameValorConfig('descontar_stock'); //Unidades a descontar del stock normal de inforpor
      
      //Stock normal de inforpor
      $stock_normal_inforpor = $inforpor['normal_inforpor']['Stock'];
      $stock_normal = $stock_normal_inforpor > $descontar_stock_inforpor ? $stock_normal_inforpor - $descontar_stock_inforpor : 0;    //Restamos una unidad al stock normal para minimizar el riesgo de que pidan algo y no tengamos
      //reservas
      $stock_reserva = !empty($inforpor['reserva_inforpor']) && $inforpor['reserva_inforpor'] > 0 ? $inforpor['reserva_inforpor'] : 0;
      //custodias
      $stock_custodias = 0;
      if (!empty($inforpor['custodias'])) {
        foreach ($inforpor['custodias'] as $keyc => $custodia) {
          $stock_custodias += $custodia->getQuedan();
          //Deshabilito este bloque por incoherente
       /*   if ($custodia->getQuedan() >  0 && ($keyc === 0 || $custodia->getTotalCompra() > $precio_inforpor_canon)) {
      
          $precio_inforpor_canon = moneda::cadenaAnumero($custodia->getTotalCompra()/1.21);
        }*/ 
        }
      }
      //Calculamos el stock final de inforpor
      $stock = ($stock_normal + $stock_reserva + $stock_custodias) > 0 ?  $stock_normal + $stock_reserva + $stock_custodias : 0;
      //Calculamos el modificador para cada artículo
      $modificador = isset($art_descontar_totales[$oferta['ean']]) ? $art_descontar_totales[$oferta['ean']] : 0;
      $stock -= $modificador;//En cualquier caso reducimos el stock final con el modificador
      return $stock;
       }


}

  ?>

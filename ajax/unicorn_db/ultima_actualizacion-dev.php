<?php
error_reporting(E_ALL);                                     //Activamos el reporte de errores
ini_set('display_errors', 'on');
require_once __DIR__ . '/../../config.php.inc';
require_once __DIR__ . '/../../clases/funciones/unicorn_db/Actualizacion.php';
require_once __DIR__ . '/../../clases/funciones/unicorn_db/Entidad.php';
require_once __DIR__ . '/../../clases/funciones/otras/Moneda.php';
require_once __DIR__ . '/../../clases/objetos/Stock/Custodia.php';

use unicorn\clases\funciones\unicorn_db\Actualizacion as actualizacion;
use unicorn\clases\funciones\unicorn_db\Entidad as entidad;

use unicorn\clases\funciones\otras\Moneda as moneda;
use unicorn\clases\objetos\Stock\Custodia as custodia;
$articulos = array();
//Pedimos las ultimas actualizaciones
$ultima_stock = actualizacion::dameUltimaActualizacion('stock');
$ultima_posiciones = actualizacion::dameUltimaActualizacion('posiciones');
//Asignamos las variables
$fecha_ultima_stock = $ultima_stock['fecha'];
$fecha_ultima_posiciones = $ultima_posiciones['fecha'];
$articulos_ultima_stock = unserialize(base64_decode($ultima_stock['campo_1']));
$articulos_ultima_posiciones = unserialize(base64_decode($ultima_posiciones['campo_1']));

$articulos_ultima = array_map(function ($a) use ($articulos_ultima_posiciones){       //Mapeamos los artículos de los 2 arrays
  $ean_a = $a['ean'];
  foreach ($articulos_ultima_posiciones as $ean_b => $value) {
    if ($ean_a == $ean_b) {                                                           //Si coinciden los EAN añadimos las ofertas
      $a['ofertas'] = $value['ofertas'];
    }
  }
  return $a;
}, $articulos_ultima_stock);

//Recorremos los artículos
foreach ($articulos_ultima as $ean => $articulo) {
  //Comprobamos la posicion de nuestra oferta
if (isset($articulo['ofertas'])) {
  $posicion_oferta = 69;                                                        //69 para articulos con oferta en los que no estamos
  $mejor_precio = 0;
  $precio_futura = 0;
  foreach ($articulo['ofertas'] as $key => $oferta) {
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

//Vamos a comprobar el margen de venta
$datos_inforpor = $articulo['inforpor'];                                        //Aislamos los datos de inforpor
//Si no está definido el siguiente indice es que el artículo no existe en inforpor, habrá que hacer depuración de esto
//$lpi = isset($datos_inforpor['normal_inforpor']['StockPrResult']['lpi']) ? $datos_inforpor['normal_inforpor']['StockPrResult']['lpi'] : 0;              //Aislamos el lpi
$lpi = isset($datos_inforpor['normal_inforpor']['lpi']) ? $datos_inforpor['normal_inforpor']['lpi'] : 0;              //Aislamos el lpi

//$precios_inforpor = array();                                                    //Declaramos un array para los precios de inforpor y añadimos el precio normal

//$precio_normal = isset($datos_inforpor['normal_inforpor']['StockPrResult']['Precio']) && $datos_inforpor['normal_inforpor']['StockPrResult']['Precio'] > 0 ? $datos_inforpor['normal_inforpor']['StockPrResult']['Precio'] : 0;
$precio_normal = isset($datos_inforpor['normal_inforpor']['Precio']) && $datos_inforpor['normal_inforpor']['Precio'] > 0 ? $datos_inforpor['normal_inforpor']['Precio'] : 0;

$precio_inforpor = moneda::cadenaAnumero($precio_normal);
if (!empty($datos_inforpor['custodias'])) {
  foreach ($datos_inforpor['custodias'] as $custodia) {
    if ($custodia->getQuedan() >  0 && $custodia->getPrecio() < $precio_inforpor) {
      $precio_inforpor = moneda::cadenaAnumero($custodia->getPrecio());
    }
  }
}
////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////
$porciento_comision = $articulo['comision'] / 100;                                        //Preparamos el factor para calcular el margen
$factor_comision = 1 - $porciento_comision;
$total_sin_comision = $articulo['precio_inicial']*$factor_comision;
$total_neto = $total_sin_comision / 1.21;
$portes = $precio_inforpor + $lpi > 60 ? 0 : 3.2;
$compra_neto = ($precio_inforpor + $lpi + $portes);
$margen_neto = $compra_neto > 3.21 ? (($total_neto  / $compra_neto)-1)*100 : 0;




//$precio_inforpor_iva = $precio_inforpor * 1.21;
//$lpi_iva = moneda::cadenaAnumero($lpi) * 1.21;
//$lpi_iva = 0;

//$precio_total = $precio_inforpor_iva + $lpi_iva;                                          //Calculamos el precio de compra en las mejores condiciones
//$precio_total_portes = $precio_total > 0 && $precio_total < 72.6 ?  $precio_total + 3.872 : $precio_total;     //Incrementamos la compra con los portes si el artículo es menor de 60€


//$precio_sin_comision = $articulo['precio_inicial'] / $factor_comision;                    //Le descontamos la comisión al precio de venta

//$margen_bruto = $precio_total_portes > 0 ? $precio_sin_comision / $precio_total_portes : 0;
//$margen_neto = ($margen_bruto - 1)*100;
$articulos_ultima[$ean]['compra_IVA'] = number_format($compra_neto * 1.21,2);
$articulos_ultima[$ean]['margen'] = number_format($margen_neto,2);
$articulos_ultima[$ean]['beneficio_neto'] = number_format(($total_neto - $compra_neto)*1.21,2);
//echo $articulo['mpn'] . '|' . $precio_total_portes . '|' . $precio_sin_comision . '|' . $margen_neto. '<br>';
//Buscamos posibles comentarios del articulo
$comentario = !is_object(entidad::dameValorArticuloEntidadVarchar($articulo['entidad']['id'],'6')) ? entidad::dameValorArticuloEntidadVarchar($articulo['entidad']['id'],'6') : '';
$articulos_ultima[$ean]['atributos']['comentario'] = $comentario;

}
$articulos_ultima['fecha_margenes'] = $fecha_ultima_stock;
$articulos_ultima['fecha_posiciones'] = $fecha_ultima_posiciones;
//Añadimos las fechas y horas de las actualizaciones

//echo "<pre>";
//print_r($articulos_ultima);
//print_r($articulos_ultima_posiciones);
//echo "</pre>";
 ?>
 <?php
 $json_articulos = json_encode($articulos_ultima);
 echo $json_articulos;
  ?>

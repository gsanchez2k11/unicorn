<?php

namespace unicorn\cron\mirakl;

error_reporting(E_ALL);                                     //Activamos el reporte de errores
ini_set('display_errors', 'on');
require_once __DIR__ . '/../../config.php.inc';

require_once RAIZ . '/clases/funciones/unicorn_db/Config.php';
require_once RAIZ . '/clases/funciones/unicorn_db/Actualizacion.php';
use unicorn\clases\funciones\unicorn_db\Config as config;
use unicorn\clases\funciones\unicorn_db\Actualizacion as actualizacion;

require_once RAIZ . '/clases/funciones/mirakl/Ofertas.php';

use unicorn\clases\funciones\mirakl\Ofertas as ofertas;

require_once RAIZ . '/clases/funciones/unicorn_db/Entidad.php';

use unicorn\clases\funciones\unicorn_db\Entidad as entidad;

require_once RAIZ . '/clases/funciones/otras/Catalogo.php';

use unicorn\clases\funciones\otras\Catalogo as catalogo;
use unicorn\clases\funciones\inforpor\Stock as compraInforpor;
require_once RAIZ . '/clases/funciones/inforpor/Stock.php';
?>
<?php
//$articulos_ultima = actualizacion::fusionActualizaciones('stock', 'pcc'); //Recuperamos la ultima actualización
$refs_nuevas = catalogo::dameObjetosMirakl('pcc'); //Pedimos el listado de ofertas de pc componentes
$articulos_con_posiciones = actualizacion::damePosiciones($refs_nuevas); //Añadimos las posiciones en tiempo real

$margen_minimo = config::dameValorConfig('margen_minimo_pcc');
$margen_minimo_factor = 1 + ($margen_minimo / 100);

 /*  echo '<pre>';
 print_r($articulos_con_posiciones);
 echo '</pre>';*/

//Recorremos el array
foreach ($articulos_con_posiciones as $articulo) {
  if (isset($articulo['actualizable']) && $articulo['actualizable'] != 1) {
  $entidad = $articulo['entidad'];
  $ofertas = $articulo['ofertas'];
  $precio = $articulo['precio'];
  $mpn = $articulo['mpn'];
  $clase_logistica = isset($articulo['clase_logistica']) ? $articulo['clase_logistica'] :  'medio';
  $shop_sku = $articulo['shop_sku'];
  $stock = $articulo['stock'];
  $estado = $articulo['estado'];
  $margen = $articulo['margen'] > 0 ? $articulo['margen'] : 1;
  $comision = $articulo['comision'];


  //Eliminamos las ofertas de reacondicionados
  $ofertas_nuevos = array_filter($ofertas, function ($v){
return $v['estado'] == 11;
  });
if (count($ofertas_nuevos) > 0) {
  $posicion_futura = 0;
  $mejor_precio = reset($ofertas_nuevos)['precio'];
  $precio_futura = 0;
  foreach ($ofertas_nuevos as $key => $oferta) { //Recorremos las ofertas
if ($oferta['tienda'] == 'Futura Teck') { //Si la oferta es nuestra grabamos la posición en la bbdd
  $posicion_futura = intval($key) + 1;
  $precio_futura = $oferta['precio'];
  $grabar = entidad::insertaArticuloEntidadInt($entidad, 21, $posicion_futura, 'articulos_entidad_int'); //Actualizamos la posicion en la base de datos

if ($key !== 0 && $margen >= $margen_minimo && $margen <= 20) { //Si no somos primeros y los márgenes están entre lo razonable

$precio_necesario = $mejor_precio - 0.05; //Este es el precio que necesitariamos para ser primeros

$porciento_comision = $comision / 100;                                        //Preparamos el factor para calcular el margen
$importe_comision = $precio * $porciento_comision; //Calculamos el importe de la comisión
$total_sin = $precio - $importe_comision; //Se lo restamos al precio de venta
$precio_actual_bruto_compra = $total_sin / (1+($margen/100)); //Le quitamos ahora el margen
$precio_al_tres = $precio_actual_bruto_compra * $margen_minimo_factor *(1+$porciento_comision);

if ($precio_al_tres <= $precio_necesario){ //Estos si merece la pena buscarlos en inforpor
 // echo '<pre>';
 // echo $shop_sku . '<br >';
 // echo '-------------------------------';
  $importe_comision_precio_necesario = $precio_necesario * $porciento_comision;
  $p_necesario_sin_comision = $precio_necesario - $importe_comision_precio_necesario;
  $p_necesario_sin_comision_sin_iva = $p_necesario_sin_comision / 1.21;
 //Comprobamos ahora que el precio que necesitamos sea igual o superior al precio de inforpor al 3%
  $compra_inforpor = compraInforpor::damePrecioCompraInforpor($articulo); //Pedimos el precio de compra de inforpor
  if($p_necesario_sin_comision_sin_iva > $compra_inforpor*$margen_minimo_factor){
    $nuevo_precio = $precio_necesario;
//    echo 'nuevo' . $nuevo_precio . '<br >';
//En este caso ya podriamos escribirlo
  }

 // echo '</pre>';
}

}

}
  }
}
  /*echo '<pre>';
  print_r($articulo);
  echo '</pre>';*/
  //Operaciones sólo con los artículos auto actualizables

 //Operamos según el margen que tengamos guardado en la base de datos
 //Lo suyo es tener una función que recalcule el margen y grabe el nuevo, pero de momento vamos a prescindir de ella
switch (true) {
  case $margen < $margen_minimo: //Si el margen es menor que 1% incrementamos el precio en un 2%
    $nuevo_precio = $precio * 1.03;
    break;

    case $margen >= 20: //Si el margen es excesivo reducimos el precio en un 10%
      $nuevo_precio = $precio / 1.10;
      break;  

}

//Si hemos definido un nuevo precio actualizamos en pcc
if (isset($nuevo_precio)) {
$articulos_para_actualizar[] = array(
  'product_id' => $mpn,
  'product_id_type' => 'MPN',
  'shop_sku' => $shop_sku,
  'logistic_class' => $clase_logistica,
  'quantity' => $stock,
  'state_code' => $estado,
  'price' => number_format($nuevo_precio,2,'.',''),
  'update_delete' => 'update'
);


}

  }

  unset($nuevo_precio);
}

//Si tenemos articulos pendientes de actualizar lo hacemos
if (isset($articulos_para_actualizar)) {
  $actualizar = ofertas::actualizaOferta($articulos_para_actualizar, 'pcc'); 
  if (gettype($actualizar) == 'object') {
    foreach ($articulos_para_actualizar as $art) {
//Buscamos en la bbdd para obtener la entidad
$ent = entidad::buscarEntidad($art);
//Grabamos el nuevo precio en la base de datos
$grabar = entidad::insertaArticuloEntidadInt($ent, 14, $art['precio'], 'articulos_entidad_decimal'); //Actualizamos el precio en la base de datos
echo '<pre>';
print_r($actualizar);
print_r($grabar);
echo '</pre>';
    }

  }

}


?>

<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . '/../../config.php.inc';

use unicorn\clases\funciones\unicorn_db\Entidad as entidad;
require_once RAIZ . '/clases/funciones/unicorn_db/Entidad.php';
use unicorn\clases\funciones\inforpor\Stock as stock;
require_once RAIZ . '/clases/funciones/inforpor/Stock.php';
use unicorn\clases\funciones\unicorn_db\Actualizacion as actualizacion;
require_once RAIZ . '/clases/funciones/unicorn_db/Actualizacion.php';
use unicorn\clases\funciones\mirakl\Ofertas as ofertas;
require_once RAIZ . '/clases/funciones/mirakl/Ofertas.php';


$entidades = actualizacion::dameListadoPccNoStockNoPrecio();

foreach ($entidades as $entidad) {
  $atributos = entidad::dameAtributosEntidad($entidad); //Pedimos los artibutos para cada entidad
  $row['sku'] = $atributos[2];
  //789766
  //$row['sku'] = 'C11CJ67417';
  $oferta_pcc = ofertas::obtenerOfertas('pcc', $row);
  if (isset($oferta_pcc[0])) {

  $precio = $oferta_pcc[0]->getData()['price'];
  $stock = $oferta_pcc[0]->getData()['quantity'];

  $grabar_stock = entidad::insertaArticuloEntidadInt($entidad,13,$stock,'articulos_entidad_int');
  $grabar_precio = entidad::insertaArticuloEntidadInt($entidad,14,$precio,'articulos_entidad_decimal');
} else {
  echo "<pre>";
  print_r($atributos);
  echo "</pre>";
}
}





//echo "<pre>";
//print_r($precio);
//echo "</pre>";
 ?>

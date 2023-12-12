<?php
/**
 * Actualizamos los datos de los artículos de pc componentes, basandonos en la última actualización grabada en la BBDD.
 * Guardamos tanto el resultado de la actualización como los datos básicos para su posterior procesamiento.
 * @var [type]
 */
namespace unicorn\cron\mirakl;
error_reporting(E_ALL);                                     //Activamos el reporte de errores
ini_set('display_errors', 'on');
require_once __DIR__ . '/../../config.php.inc';
require_once(RAIZ . '/vendor/autoload.php');

require_once RAIZ . '/clases/funciones/fnac/Ofertas.php';

require_once RAIZ . '/clases/funciones/google/Tarifa.php';

require_once RAIZ . '/clases/funciones/otras/Catalogo.php';

require_once RAIZ . '/clases/funciones/unicorn_db/Actualizacion.php';
//require_once RAIZ . '/clases/funciones/unicorn_db/Entidad.php';

use unicorn\clases\funciones\fnac\Ofertas as ofertas;

use unicorn\clases\funciones\google\Tarifa as tarifa;

use unicorn\clases\funciones\otras\Catalogo as catalogo;

use unicorn\clases\funciones\unicorn_db\Actualizacion as actualizacion;
//use unicorn\clases\funciones\unicorn_db\Entidad as entidad;
//$valores_tarifa = tarifa::dameTarifaFiltrada('1zVn9BKoljNXLaj4wQVhvsPN0edio0wLFYHjVeMJ-amA','Futura!A1:Z');           //Obtenemos la tarifa


$ofertas = ofertas::dameOfertas();                                              //Recuperamos las ofertas de FNAC
/*$datos_alertas    = array(
  'plataforma' => 'fnac',
  'tipo' => 'listar_ofertas',
  'campo_1' => base64_encode(serialize($ofertas)),
  'visto' => 1,
  'num_modificados' => count($ofertas)
);

$insertar = actualizacion::addActualizacion($datos_alertas);*/

//Creamos una copia del array dejando los campos que nos interesan
$refs_nuevas =  array_map( function($articulo){
  $referencia = array(
    'stock_inicial' => $articulo->getStock(),
    'precio_inicial' => $articulo->getPrecio(),
    'mpn' => $articulo->getMpn(),
    'ean'=> $articulo->getEan(),
    'product_sku' => $articulo->getProductSku(),
  //  'shop_sku' => $articulo->getShopSku(),
    //'logistic_class' => $articulo->getLogisticClass(),
    'state_code' =>  $articulo->getStateCode(),
    'comision'  => $articulo->getComision(),
    'nombre'    => $articulo->getNombre(),
    //'categoria' => $articulo->getCategory()
  );
  return $referencia;
},$ofertas);

echo "<pre>";
print_r($refs_nuevas);
echo "</pre>";

 ?>

<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . '/../../config.php.inc';
use unicorn\clases\funciones\google\Tarifa as tarifa;
require RAIZ . '/clases/funciones/google/Tarifa.php';
use unicorn\clases\funciones\magento\Articulos as articulosMage;
require_once RAIZ . '/clases/funciones/magento/Articulos.php';
?>
<?php

$articulos_tarifa = tarifa::miTarifaCompleta();



//[8329] => unicorn\clases\objetos\Articulos\Articulo_unicornio Object
//        (
//            [referencia] => MTEO305020503
//            [entidad] => entidad
//            [precio_venta] => 474.85
//            [compras] => Array
//                (
//                    [0] => Array
//                        (
//                            [referencia] => MTEO305020503
//                            [nombre] => Afford
//                            [medio] => tarifa 2019
//                            [precio_venta] => 474.85
//                            [precio] => 312.37
//                        )
//
//                )
//
//        )
//


//recuperamos TODOS los artículos de magento
$arts_mage = articulosMage::getListaTodosArticulos();

//Vamos a hacer un subarray con las referencias;
//$refs = array_column($articulos_tarifa,'referencia');
//Cambiamos el campo referencia para llamarlo sku

//$precio_distinto = array();
array_map(function($articulo) use ($arts_mage){                                 //Mapeamos el array de articulos de la tarifa
foreach ($arts_mage as $key => $art) {                                          //Recorremos el array de artículos de magento cada vez (esto es lento)
if ($articulo->getReferencia() == $art->sku) {                                  //Buscamos el código
  $articulo->setMage($art);                                                     //Si coinciden añadimos los campos obtenidos de magento al artículo
}
}
},$articulos_tarifa);


//Filtramos primero aquellos articulos que existen en tarifa pero no en magento
$si_tarifa_no_mage = array_filter($articulos_tarifa, function ($articulo) {
  return !empty($articulo->getMage());
});


//$comunes = array_intersect_assoc($articulos_tarifa,$arts_mage);

/*function comparar($a,$b){
if ($a->getReferencia() === $b['sku']) {
return 0;
}
return 1;

}*/

//$comunes = array_uintersect($articulos_tarifa,$arts_mage,function($a, $b) {
//  $obj = (object) $b;
//  if ($a->sku !== $obj->sku) {
//  return 0;
//  }
//  return 1;
//    });

echo "<pre>";
//echo count($articulos_tarifa);
print_r($si_tarifa_no_mage);
//print_r($comunes);
echo "</pre>";
 ?>

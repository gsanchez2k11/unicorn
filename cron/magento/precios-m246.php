<?php 
/**
 * Configuramos los precios según las distintas reglas de márgenes
 */
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . '/../../config.php.inc';
require_once(RAIZ . '/vendor/autoload.php');
?>
<?php
use unicorn\clases\funciones\magento\Articulos as articulos;
require_once RAIZ . '/clases/funciones/magento/Articulos.php';
use unicorn\clases\funciones\inforpor\Tarifa as tarifa;
require_once RAIZ . '/clases/funciones/inforpor/Tarifa.php';
use unicorn\clases\funciones\unicorn_db\General as general;
require_once RAIZ . '/clases/funciones/unicorn_db/General.php';
use unicorn\clases\funciones\unicorn_db\Config as config;
require_once RAIZ . '/clases/funciones/unicorn_db/Config.php';
use unicorn\clases\funciones\magento\Cronmage as mage;

require RAIZ . '/clases/funciones/magento/Cronmage.php';

//Pedimos la última tarifa de inforpor
$tarifa_inforpor = tarifa::dameTarifaRemota();

$mage_precios = mage::preciosM246($tarifa_inforpor); //Lanzamos el proceso que actualiza los artículos que han cambiado de coste


/*$params = array(
    'p' => 1,
    'auto_actualiza_precio' => 1,
    'tienda' => 2
);
//Pedimos los artículos auto actualizables
$listar_articulos = articulos::buscarArticulos($params);
//echo '<pre>';
//print_r($listar_articulos);
//echo '</pre>';

$actualizables = array();
//recorremos el listado recibido
foreach ($listar_articulos as $articulo_magento) {
    $ean = '-';    
    $cost = '-';
    $product_brand = '-';
    $margen = '-';
    foreach ($articulo_magento->custom_attributes as $custom_attr) { //Buscamos los custom attributes que nos puedan interesar

        if ($custom_attr->attribute_code == 'ean') {
        $ean = $custom_attr->value;
        }
        if ($custom_attr->attribute_code == 'cost') {
            $cost = $custom_attr->value;
            }
            if ($custom_attr->attribute_code == 'product_brand') {
                $product_brand = $custom_attr->value;
                }
                if ($custom_attr->attribute_code == 'margen') {
                    $margen = $custom_attr->value;
                    }
        } 

$actualizable = array_filter($tarifa_inforpor, function ($tifp) use ($articulo_magento,$ean,$cost) {
return ($articulo_magento->sku == $tifp->getReferencia() || $articulo_magento->sku == $tifp->getReferencia2() || $ean == $tifp->getEan()) && $cost != $tifp->getPrecio();
});
if (count($actualizable) > 0) {
$actualizables[] = array(
    'id' => $articulo_magento->id,
    'sku' => $articulo_magento->sku,
    'price' => $articulo_magento->price,
    'attribute_set_id' => $articulo_magento->attribute_set_id,
    'product_brand' => $product_brand,
    'margen' => $margen,
    'cost' =>reset($actualizable)->getMejorPrecio()
);
}
}

//recorremos ahora el array de artículos a actualizar
if (count($actualizables) > 0) {
//Lista con todas las familias y subfamilias
$familias_margenes = general::listar('familias_margenes');

foreach ($actualizables as $art) {
 if ($art['margen'] !== '-' && is_numeric($art['margen'])) { //Si ese artículo tiene un margen individual definido lo aplicamos
$margen =     $art['margen'];

 }else { //En caso contrario tenemos que buscar por conjunto de atributos o fabricante
    $coincidencia = array_filter($familias_margenes, function($at) use ($art) {
if ($at['conjunto_atributos'] == $art['attribute_set_id'] && $at['fabricante'] == $art['product_brand']) { //Le damos prioridad si coincide conjunto de atributos y fabricante
    return true;
} else {
    if ($at['conjunto_atributos'] == $art['attribute_set_id']) {
        return true;
    } elseif($at['fabricante'] == $art['product_brand']) {
        return true;
    }
    
}
return false;
    });

 if (count($coincidencia) > 0 && is_numeric(reset($coincidencia)['margen'])) {
$margen = reset($coincidencia)['margen'];
 } else { //En este caso el artículo es auto actualizable pero no tiene definido un margen, ni coinciden con ninguna familia o fabricante que lo tenga, así que le aplicamos un margen general que vamos a definir en la bbdd
$margen = config::dameValorConfig('margen_venta_general');
 }  
 }
 
 if (isset($margen) && is_numeric($margen)) {
   // echo is_numeric($margen);
    $precio_venta = $art['cost'] * (1+($margen/100));
    $attrs['price'] = $precio_venta;
 }
 

   $attrs['custom_attributes'] = array(
    'custom_attributes' => array(
        'attribute_code' => 'cost',
        'value' => $art['cost']
        )
); 
   $update = articulos::actualizarArticulo($art['sku'],$attrs,2);

echo '<pre>';
print_r($art);
echo '</pre>';
}
}*/
//echo '<pre>';
//print_r($actualizables);
//echo '</pre>';
/*echo '<pre>';
print_r($listar_articulos);
echo '</pre>';*/
?>
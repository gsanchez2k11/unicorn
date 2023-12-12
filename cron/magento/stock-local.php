<?php
/**
 * Script que sincroniza el stock de odoo con Magento, lo vamos a lanzar diariamente
 * @var [type]
 */
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . '/../../config.php.inc';
require_once(RAIZ . '/vendor/autoload.php');
use Ripoo\OdooClient;
use unicorn\clases\funciones\odoo\Conectar as conectar;
require_once RAIZ . '/clases/funciones/odoo/Conectar.php';
use unicorn\clases\funciones\magento\Articulos as articulos;
require_once RAIZ . '/clases/funciones/magento/Articulos.php';
use unicorn\clases\funciones\magento\Inventory as inventory;
require_once RAIZ . '/clases/funciones/magento/Inventory.php';

use unicorn\clases\funciones\rocket\Ims as ims;
use unicorn\clases\funciones\rocket\Mensajes as msj;


 ?>
<?php
//Magento 2.2
$arr['stock_local'] = 1;
$buscamos = articulos::buscarArticulos($arr); //Pedimos a Magento todos los artículos que se gestionan con stock local

//Vamos a recorrer el array buscando cada artículo en Odoo

foreach ($buscamos->items as $articulo) {
$campo = 'default_code';
$valor = $articulo->sku;
$modelo = 'product.product';
$offset = 0;
  $resultado = conectar::busqueda($campo,$valor,$modelo,$offset);
  if (isset($resultado[0])) { //Si tenemos resultados nos quedamos con el stock
      $stock_odoo = $resultado[0]['qty_available']; //stock que vamos a grabar
      $is_in_stock = $stock_odoo > 0 ? 1 : 0 ;
    $atributos['extension_attributes']['stock_item'] = array(
      'qty' => $resultado[0]['qty_available'],
      'is_in_stock' => $is_in_stock
    );
//$datos = array(
//  'sku' => $articulo->sku,
//  'extension_attributes' =>$extension_attrs
//);



$actualizar = articulos::actualizarArticulo($articulo->sku,$atributos);


echo '<pre>';
print_r($actualizar);
echo '</pre>';


}


}

//Magento 2.4
$fuente = 'futura';
//Pedimos las fuentes de stock
$stock = inventory::getSourceItems($fuente);
$arr_delete = array(); //Array vacio para los artículos que vamos a eliminar
$arr_update = array(); //Array vacio para la actualización
foreach ($stock as $key => $value) {
  $campo = 'default_code';
  $valor = $value->sku;
  $modelo = 'product.product';
  $offset = 0;
    $resultado = conectar::busqueda($campo,$valor,$modelo,$offset);
    if (empty($resultado)) { //Si el resultado está vacío tenemos configurada como fuente futura para un artículo que no encontramos en odoo
      $arr_delete[] = $value;
    } else {
      if (isset($resultado[0]) && $stock[$key]->quantity != $resultado[0]['qty_available'] ) { //Si tenemos resultados y el stock ha cambiado 
        //Modificamos la entrada correspondiente a este indice
      $stock[$key]->quantity = $resultado[0]['qty_available'];
      $stock[$key]->status = $resultado[0]['qty_available'] > 0 ? 1 : 0;
      $arr_update[] = $stock[$key]; //La añadimos al array para actualizar
      }

    }

}
//Actualizamos con los datos nuevos
inventory::postSourceItems($arr_update);
//Eliminamos las entradas obsoletas
inventory::deleteSourceItems($arr_delete);
echo '<pre>';
print_r($stock);
echo '</pre>';


 ?>

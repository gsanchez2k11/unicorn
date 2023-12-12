<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . '/../../config.php.inc';
require_once(RAIZ . '/vendor/autoload.php');
use Ripoo\OdooClient;
use unicorn\clases\funciones\odoo\Conectar as conectar;
require_once RAIZ . '/clases/funciones/odoo/Conectar.php';

use unicorn\clases\funciones\magento\Articulos as articulos;
require RAIZ . '/clases/funciones/magento/Articulos.php';

$arr['stock_local'] = 1;
$buscamos = articulos::buscarArticulos($arr); //Pedimos a Magento todos los artículos que se gestionan con stock local
foreach ($buscamos->items as $articulo) {
    $campo = 'default_code';
    $valor = $articulo->sku;
    $modelo = 'product.product';
    $offset = 0;

    if ($valor == 'BPLAN170') {
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
      echo '<pre>';
      print_r($articulo->sku);
      print_r($atributos);
      echo '</pre>';
      
      
      $actualizar = articulos::actualizarArticulo($articulo->sku,$atributos);
      
      
      //echo '<pre>';
      //print_r($actualizar);
      //echo '</pre>';
      
      
      }
    //    echo '<pre>';
  //print_r($resultado);
  //echo '</pre>';
    }

}



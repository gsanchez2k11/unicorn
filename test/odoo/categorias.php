<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . '/../../config.php.inc';
require_once(RAIZ . '/vendor/autoload.php');
use Ripoo\OdooClient;

use unicorn\clases\funciones\odoo\Conectar as conectar;
require RAIZ . '/clases/funciones/odoo/Conectar.php';


//$articulos = articulos::buscarArticuloAtributos('default_code','F1509','product.product');    //Referencia interna
/*$row = array(
'2' => "FB1519"
);*/



$categorias = conectar::listar('product.category',0);



echo "<pre>";
print_r($categorias);
echo "</pre>";
 ?>

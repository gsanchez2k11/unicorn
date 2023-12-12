<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . '/../../config.php.inc';
require_once(RAIZ . '/vendor/autoload.php');
use Ripoo\OdooClient;

use unicorn\clases\funciones\odoo\Conectar as conectar;
require RAIZ . '/clases/funciones/odoo/Conectar.php';


$modelos = conectar::busqueda('id','8352','ir.model.fields');    //Referencia interna
$seleccion = $modelos[0]['selection'];
$json_seleccion = json_decode($seleccion);

echo "<pre>";
print_r($modelos);
echo "</pre>";
 ?>

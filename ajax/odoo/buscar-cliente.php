<?php
error_reporting(E_ALL);                                     //Activamos el reporte de errores
ini_set('display_errors', 'on');
require_once __DIR__ . '/../../config.php.inc';
require_once(RAIZ . '/vendor/autoload.php');
require_once RAIZ . '/clases/funciones/odoo/Clientes.php';
use unicorn\clases\funciones\odoo\Clientes as cliente;
$clientes = $_POST;
$buscar = array();
//echo "<pre>";
//print_r($_POST);
//echo "</pre>";
foreach ($clientes as $id => $cliente) {
switch ($id) {
    case 'nif':
$campo = 'vat';
    break;
    case 'phone':
    case 'tlfo':
      case 'telefono':
  $campo = 'mobile';
    break;
    case 'email':
  $campo = 'email';
    break;
  default:
//$campo = 'name';
$campo = 'vat';
    break;
}

if ($cliente !== '') {                                                            //Evitamos buscar campos vacios
$buscar = cliente::buscarCLiente($campo,trim($cliente));
if ($campo == 'mobile') {                                                       //Si el campo es el telefono, hacemos una búsqueda tambien por el fijo
$campo = 'phone';
$buscar = cliente::buscarCLiente($campo,trim($cliente));
}

} else {
  $buscar = array('');
}
//$buscar = !empty($busqueda) ? $busqueda : '';

}
$json_cliente = json_encode($buscar);
echo $json_cliente;

 ?>

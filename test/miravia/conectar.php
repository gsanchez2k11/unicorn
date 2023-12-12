<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . '/../../config.php.inc';
require_once(RAIZ . '/vendor/autoload.php');
//use Ripoo\OdooClient;
use unicorn\clases\funciones\miravia\Conectar as conectar;
require RAIZ . '/clases/funciones/miravia/Conectar.php';
//Datos de conexión
$conectar = conectar::dameToken();




//$conecion = clientes::buscarCLiente('id','2852');
//$ids = $client->search('res.country.state', [['country_id', '=', 68],['name', '=', 'Huesca']], 0, 100);
//$conecion = $client->read('res.country.state',$ids);
//$conecion = clientes::listar('product.product',1);
echo "funciona??";
echo "<pre>";
print_r($conectar);
//print_r(get_class_methods($client));
echo "</pre>";

 ?>

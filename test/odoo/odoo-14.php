<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . '/../../config.php.inc';
require_once(RAIZ . '/vendor/autoload.php');
use Ripoo\OdooClient;
use unicorn\clases\funciones\odoo\Clientes as clientes;
require RAIZ . '/clases/funciones/odoo/Clientes.php';
//Datos de conexión
//$host = 'http://146.59.237.96:8069';
//$host = 'https://futura.studio73.dev:8069';
//14ee2ae80def612c900486119328cfef4bd856c4
$host = 'https://futura.studio73.es';
//$host = 'https://demo3.odoo.com/';
$db = 'futura_147ac041';
$user = 'manuel@futura.es';
//$password = 'Ou0Aoth7';
$password = '14ee2ae80def612c900486119328cfef4bd856c4';

//Creamos el objeto cliente y hacemos la conexión
$client = new OdooClient($host, $db, $user, $password);
$client->version();
$modelo = 'product.product';
$campo = 'id';
$valor = 1237;
$offset = 0;

$criterios[] = array(
    $campo,
    '>=',
    $valor
    );

    $ids = $client->search($modelo, $criterios, $offset, 10);         //Buscamos el correo (modelo,criterios,offset,limit)

    $articulos = $client->read($modelo, $ids/*, $fields*/);



//$conecion = clientes::buscarCLiente('id','2852');
//$ids = $client->search('res.country.state', [['country_id', '=', 68],['name', '=', 'Huesca']], 0, 100);
//$conecion = $client->read('res.country.state',$ids);
//$conecion = clientes::listar('product.product',1);
echo "funciona??";
echo "<pre>";
print_r($articulos);
print_r($client->version());
print_r(get_class_methods($client));
//print_r(get_class_methods($client));
echo "</pre>";

 ?>
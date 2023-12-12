<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . '/../../config.php.inc';
require_once(RAIZ . '/vendor/autoload.php');
use Ripoo\OdooClient;

use unicorn\clases\funciones\odoo\Articulos as articulos;
require RAIZ . '/clases/funciones/odoo/Articulos.php';

//Datos de conexión
//$host = 'http://146.59.237.96:8069';
$host = 'https://futura.studio73.dev:8069';
$db = 'futura';
$user = 'manuel@futura.es';
$password = 'Ou0Aoth7';

//Creamos el objeto cliente y hacemos la conexión
$client = new OdooClient($host, $db, $user, $password);

$modelo = 'product.product';
$campo = 'id';
$valor = 0;
//$arr['list_price'] = floatval($datos['arr']['list_price']);

$criterios[] = array(
    $campo,
    '>=',
    $valor
    );
    $ids = $client->search($modelo, $criterios, $offset, 10);         //Buscamos el correo (modelo,criterios,offset,limit)
    //    $fields = ['name', 'email', 'customer','mobile','vat'];
    $articulos = $client->read($modelo, $ids/*, $fields*/);

echo "<pre>";
print_r($articulos);
echo "</pre>";
 ?>

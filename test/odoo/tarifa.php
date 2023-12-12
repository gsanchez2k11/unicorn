<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . '/../../config.php.inc';
require_once(RAIZ . '/vendor/autoload.php');
$tiempo_inicial = microtime(true); //true es para que sea calculado en segundos
use Ripoo\OdooClient;

use unicorn\clases\funciones\odoo\Conectar as conectar;
require RAIZ . '/clases/funciones/odoo/Conectar.php';
/*$salida = array();
$offset = 1;

do {
$items = conectar::listarNuevo('product.pricelist.item',$offset);
$salida = array_merge($items,$salida);
$offset += 10;
} while (count($items) == 10);


$tiempo_final = microtime(true);
$tiempo = $tiempo_final - $tiempo_inicial; //este resultado estará en segundos
echo "El tiempo de ejecución del archivo ha sido de " . $tiempo . " segundos";
echo "<pre>";
print_r($salida);
echo "</pre>";*/

/*
$ids = [
    1272,
    1253,
    1252,
    1212,
    1208,
    1207,
    1206,
    1205,
    1204,
    1079,
    1076,
    1060,
    1059,
    1053,
    939,
    936,
    564,
    432,
    431,
    430,
    429,
    428,
    427,
    426,
    425,
    424,
    423,
    422,
    421,
    420,
    419,
    418,
    417,
    416,
    415,
    414,
    413,
    412,
    411,
    410,
    409,
    408,
    407,
    406,
    405,
    404,
    403,
    402,
    401,
    400,
    399,
    398,
    397,
    396,
    395,
    394,
    393,
    392,
    391,
    390,
    389,
    388,
    387,
    386,
    385,
    379,
    378,
    377,
    376,
    375,
    374,
    373,
    372,
    371,
    370,
    369,
    368,
    367,
    366,
    365,
    364,
    363,
    362,
    361,
    360,
    359,
    358,
    357,
    356,
    355,
    354,
    353,
    352,
    351,
    350,
    285,
    276,
    275,
    274,
    273,
    272,
    271,
    270,
    269,
    268,
    267,
    266,
    265,
    264,
    263,
    262,
    261,
    260,
    258,
    257,
    256,
    255,
    254,
    253,
    252,
    251,
    250,
    249,
    248,
    247,
    246,
    245,
    244,
    243,
    242,
    241,
    240,
    239,
    147,
    146,
    145,
    144,
    143,
    142,
    141,
    140,
    139,
    138,
    137,
    136,
    135,
    134,
    133,
    19,
    18,
    17,
    16,
    15,
    14,
    11
];
 foreach ($ids as $id) {
//$buscar = conectar::listarNuevo('product.pricelist.item',$offset);
$buscar = conectar::busqueda('id',$id,'product.pricelist.item');
 }
 $tiempo_final = microtime(true);
 $tiempo = $tiempo_final - $tiempo_inicial; //este resultado estará en segundos
 echo "El tiempo de ejecución del archivo ha sido de " . $tiempo . " segundos";
*/

$modelo = 'product.pricelist.item';
$offset = 0;
//$lista = conectar::listar($modelo,$offset);

//echo '<pre>';
//print_r($lista);
//echo '<pre>';


$campo = 'pricelist_id';
$valor = 10;
$busqueda = conectar::busqueda($campo,$valor,$modelo,$offset);

/*echo '<pre>';
var_dump($busqueda);
echo '<pre>';*/

//$modelo = 'product.pricelist';
$modelo = 'product.pricelist.item';
$offset = 2000;
$lista = conectar::listar($modelo,$offset);

echo '<pre>';
print_r($lista);
echo '<pre>';
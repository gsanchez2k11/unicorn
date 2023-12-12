<?php
$tiempo_inicial = microtime(true); //true es para que sea calculado en segundos

error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . '/../../config.php.inc';
use unicorn\clases\funciones\google\Conectar as conectar;
require RAIZ . '/clases/funciones/google/Conectar.php';

use unicorn\clases\funciones\otras\Moneda as moneda;
require RAIZ . '/clases/funciones/otras/Moneda.php';

?>
<?php

$cliente = conectar::Conectar();    //Creamos la conexión
$service = new \Google_Service_Sheets($cliente); //Creamos el servicio
$rangos = array( //Definimos los rangos que vamos a utilizar
'Hoja 1!A1:Z'
);

$estructura = array();
$valores = array(); //declaramos el array de salida
$salida = array();

foreach ($rangos as $rango) { //Recorremos el array con los rangos
$resultado = $service->spreadsheets_values->get(conectar::TARIFAEPSONID, $rango);     //Pedimos a google los datos para ese rango
$arr_rango = explode("!",$rango); //Separamos el nombre de la hoja del rango
$estructura[$arr_rango[0]] = array_filter($resultado->values); //filtramos para quitar las filas completamente vacias y creamos una entrada en el array para cada rango

}

foreach ($estructura as $hoja => $valoress) {
foreach ($valoress as $key => $fila) {
//ejemplo

//MPG                   [0] => K2
//Estado                [1] => No Price Available
//Tipo                  [2] => Consumibles
//Categoria             [3] => Papel para equipo comercial
//Nombre                [4] => SureLab Cleaning Sheet
//MPN                   [5] => C13S042497
//EAN                   [6] => 8715946524009
//Multiple order qty    [7] =>
//Precio                [8] => Previa petición
//   [9] =>
//   [10] =>
//Recomendado           [11] => 1.884,00 EUR
//   [12] =>
$mpg = $fila[0];
$estado = $fila[1];
$tipo = $fila[2];
$categoria = $fila[3];
$nombre = $fila[4];
$mpn = $fila[5];
$ean = $fila[6];
$pvp = $fila[8];

$pvp_tunning = moneda::cadenaAnumero(trim(substr($pvp,0,strlen($pvp)-3))); //Convertimos el precio a double


$objeto = array(
  'mpn' => $mpn,
  'pvp' => $pvp_tunning
);

//$precio_recomendado = $fila[11];
$salida[] = $objeto;

}
}

//Creamos los objetos
/*echo "<pre>";
print_r($salida);
echo "</pre>";*/

//Filtramos el array
/*echo "<pre>";
print_r($salida);
echo "</pre>";*/

$json_resultado = json_encode($salida);
echo $json_resultado;





/*$tiempo_final = microtime(true);
$tiempo = $tiempo_final - $tiempo_inicial; //este resultado estará en segundos
echo "El tiempo de ejecución del archivo ha sido de " . $tiempo . " segundos";*/


 ?>

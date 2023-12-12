<?php
$tiempo_inicial = microtime(true); //true es para que sea calculado en segundos

error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . '/../../config.php.inc';
use unicorn\clases\funciones\google\Conectar as conectar;
require RAIZ . '/clases/funciones/google/Conectar.php';

use unicorn\clases\objetos\Articulos\Articulo_tarifa as articulo;
require RAIZ . '/clases/objetos/Articulos/Articulo_tarifa.php';

?>
<?php

$cliente = conectar::Conectar();    //Creamos la conexión
$service = new \Google_Service_Sheets($cliente); //Creamos el servicio
$rangos = array( //Definimos los rangos que vamos a utilizar
  'Inkjet!A1:AL',
  'Solvente!A1:AL',
  'Comunes!A1:AL',
  'Sublimación!A1:AL',
  'Futura!A1:AL',
//  'Metacrilato!A1:AL'
);

$estructura = array();
$valores = array(); //declaramos el array de salida
$salida = array();

foreach ($rangos as $rango) { //Recorremos el array con los rangos
$resultado = $service->spreadsheets_values->get(conectar::NUEVAID, $rango);     //Pedimos a google los datos para ese rango
$arr_rango = explode("!",$rango); //Separamos el nombre de la hoja del rango
$estructura[$arr_rango[0]] = array_filter($resultado->values); //filtramos para quitar las filas completamente vacias y creamos una entrada en el array para cada rango
$valores = array_merge_recursive($valores,$resultado->values); //añadimos los valores al array DEPRECATED
}
//  echo "<pre>";
//  print_r($estructura);
//  echo "</pre>";


$tiempo_final = microtime(true);
$tiempo = $tiempo_final - $tiempo_inicial; //este resultado estará en segundos
echo "El tiempo de ejecución del archivo ha sido de " . $tiempo . " segundos";


 ?>

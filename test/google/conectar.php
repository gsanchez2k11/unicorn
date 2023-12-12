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

foreach ($estructura as $hoja => $valoress) {
//  $material = array();
foreach ($valoress as $key => $fila) {

if(count($fila) === 1) {   //Buscamos el primer nivel en la jerarquia
  $nombre = ucfirst(strtolower($fila[0])); //Transformamos el nombre a minúsculas y lo "capitalizamos"
} else {
  if(count($fila) === 26) {   //Buscamos el segundo nivel, que va a ser el nombre de los materiales
  $material =  trim(ucfirst(strtolower($fila[0]))); //Transformamos el nombre a minúsculas y lo "capitalizamos"
} elseif (isset($fila[0]) && strlen($fila[0]) > 0 && isset($fila[1]) && ($fila[1] == 'TRUE' || $fila[1] == 'FALSE')) { //Nos quedamos solos con los que tienen la caracteristica "activo web"


  $largo = isset($art[31]) ? $art[31]: 0;
  $ancho = isset($art[30]) ? $art[30]: 0;

  $art = array (
    'referencia' => $fila[0],
    'activo_web' => $fila[1],
    'descripcion' => $fila[2],
    'precio' => $fila[6],
    'precio_m2' => $fila[7],
    'ancho' => $ancho,
    'largo' => $largo
  );
  if (!isset($salida[$hoja][$nombre][$material][$fila[0]])) {
  $salida[$hoja][$nombre][$material][$fila[0]] = new articulo($art);
  }
  $venta_unidad = isset($fila[24]) ? $fila[24]: 0;
  $base_venta = isset($fila[25]) ? $fila[25]: 0;
  $portes_venta = isset($fila[26]) ? $fila[26]: 0;
  $otros_gastos_venta = isset($fila[27]) ? $fila[27]: 0;
  $precio_venta = isset($fila[28]) ? $fila[28]: 0;
  $venta_m2 = isset($fila[29]) ? $fila[29]: 0;
$compra = array(
  'fecha_actualizado' => $fila[10],
  'proveedor' => $fila[11],
  'unidades' => $fila[13],
  'pvp_m2' => $fila[14],
  'dto_compra' => $fila[15],
  'pvp' => $fila[16],
  'compra_m2' => $fila[17],
  'compra_unidad' => $fila[18],
  'compra_base' => $fila[19],
  'portes_compra' => $fila[20],
  'otros_gastos_compra' => $fila[21],
  'total_compra' => $fila[22],
  'margen' => $fila[23],
  'venta_unidad' => $venta_unidad,
'base_venta' => $base_venta,
'portes_venta' => $portes_venta,
'otros_gastos_venta' => $otros_gastos_venta,
'precio_venta' => $precio_venta,
'venta_m2' => $venta_m2,
);


//    $salida[$hoja][$nombre][$material][$fila[0]]['compras'][] = $compra;
$salida[$hoja][$nombre][$material][$fila[0]]->addCompra($compra);
}
//  $salida[$hoja][$nombre] = '';    //Lo usamos para mostrar el nombre de las categorias

}
//  echo "<pre>";
//  print_r($fila);
//  echo "</pre>";
}

//$salida[$hoja] =   $arr_filtrado;
}

//Creamos los objetos


//Filtramos el array


$json_resultado = json_encode($salida);
echo $json_resultado;


/*echo "<pre>";
print_r($salida);
echo "</pre>";*/


/*$tiempo_final = microtime(true);
$tiempo = $tiempo_final - $tiempo_inicial; //este resultado estará en segundos
echo "El tiempo de ejecución del archivo ha sido de " . $tiempo . " segundos";*/


 ?>

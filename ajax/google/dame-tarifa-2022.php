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
foreach ($estructura as $hoja => $valoress) {
//  $material = array();
foreach ($valoress as $key => $fila) {
if(count($fila) === 1) {   //Buscamos el primer nivel en la jerarquia
  $nombre = ucfirst(strtolower($fila[0])); //Transformamos el nombre a minúsculas y lo "capitalizamos"
} else {
  if(count($fila) === 26) {   //Buscamos el segundo nivel, que va a ser el nombre de los materiales
  $material =  trim(ucfirst(strtolower($fila[0]))); //Transformamos el nombre a minúsculas y lo "capitalizamos"
//} elseif (isset($fila[0]) && strlen($fila[0]) > 0 && isset($fila[1]) && ($fila[1] == 'TRUE' || $fila[1] == 'FALSE')) { //Nos quedamos solos con los que tienen la caracteristica "activo web"
} elseif ( ((isset($fila[0]) && strlen($fila[0]) > 0) || isset($fila[11])) && isset($fila[1]) && ($fila[1] == 'TRUE' || $fila[1] == 'FALSE')) { //Nos quedamos solos con los que tienen la caracteristica "activo web"

  //Si no tiene el indice 0 pero si es activo web es una compra para el artículo principal
  if (!isset($fila[0]) || strlen($fila[0]) === 0) {
    $hay_referencia = false;
    $i = $key; //Tenemos que recorrer las filas hacia atrás hasta llegar a la anterior con referencia
do {
  if(isset($valoress[$i][0]) && strlen($valoress[$i][0]) > 0) {
    $fila[0] = $valoress[$i][0];
    $hay_referencia = true;
  } 
  $i--;  # code...
} while ($hay_referencia === false);
  } 

  $largo = isset($art[31]) ? $art[31]: 0;
  $ancho = isset($art[30]) ? $art[30]: 0;
  $precio = isset($fila[6]) ? $fila[6] : 0;
  $precio_m2 = isset($fila[7]) ? $fila[7] : 0;
  $fecha_actualizado = isset($fila[10]) ? $fila[10] : '';
  $proveedor = isset($fila[11]) ? $fila[11] : '';

  $art = array (
    'referencia' => $fila[0],
    'activo_web' => $fila[1],
    'descripcion' => $fila[2],
    'precio' => $precio,
    'precio_m2' => $precio_m2,
    'ancho' => $ancho,
    'largo' => $largo
  );

  //echo "<pre>";
  //print_r($art);
 //echo "</pre>";

  if (!isset($salida[$hoja][$nombre][$material][$fila[0]])) {
  $salida[$hoja][$nombre][$material][$fila[0]] = new articulo($art);
  } 
  
  $ref_proveedor = isset($fila[12]) ? $fila[12]: '';
  $unidades = isset($fila[13]) ? $fila[13]: 1;
  $pvp_m2 = isset($fila[14]) ? $fila[14]: 0;
  $dto_compra = isset($fila[15]) ? $fila[15]: 0;
  $pvp = isset($fila[16]) ? $fila[16]: 0;
  $compra_m2 = isset($fila[17]) ? $fila[17]: 0;
  $compra_unidad = isset($fila[18]) ? $fila[18]: 0;
  $compra_base = isset($fila[19]) ? $fila[19]: 0;
  $portes_compra = isset($fila[20]) ? $fila[20]: 0;
  $otros_gastos_compra = isset($fila[21]) ? $fila[21]: 0;
  $total_compra = isset($fila[22]) ? $fila[22]: 0;
  $margen = isset($fila[23]) ? $fila[23]: 0;
  $venta_unidad = isset($fila[24]) ? $fila[24]: 0;
  $base_venta = isset($fila[25]) ? $fila[25]: 0;
  $portes_venta = isset($fila[26]) ? $fila[26]: 0;
  $otros_gastos_venta = isset($fila[27]) ? $fila[27]: 0;
  $precio_venta = isset($fila[28]) ? $fila[28]: 0;
  $venta_m2 = isset($fila[29]) ? $fila[29]: 0;
$compras = array(
  'fecha_actualizado' => $fecha_actualizado,
  'proveedor' => $proveedor,
  'ref_proveedor' => $ref_proveedor,
  'unidades' => $unidades,
  'pvp_m2' => $pvp_m2,
  'dto_compra' => $dto_compra,
  'pvp' => $pvp,
  'compra_m2' => $compra_m2,
  'compra_unidad' => $compra_unidad,
  'compra_base' => $compra_base,
  'portes_compra' => $portes_compra,
  'otros_gastos_compra' => $otros_gastos_compra,
  'total_compra' => $total_compra,
  'margen' => $margen,
  'venta_unidad' => $venta_unidad,
'base_venta' => $base_venta,
'portes_venta' => $portes_venta,
'otros_gastos_venta' => $otros_gastos_venta,
'precio_venta' => $precio_venta,
'venta_m2' => $venta_m2,
);
//echo "<pre>";
//print_r($fila[0] . '<br>');//
//print_r($compras);
//echo "</pre>";

//    $salida[$hoja][$nombre][$material][$fila[0]]['compras'][] = $compra;
$salida[$hoja][$nombre][$material][$fila[0]]->addCompra($compras);
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
/*echo "<pre>";
print_r($salida);
echo "</pre>";*/


$json_resultado = json_encode($salida);
echo $json_resultado;





/*$tiempo_final = microtime(true);
$tiempo = $tiempo_final - $tiempo_inicial; //este resultado estará en segundos
echo "El tiempo de ejecución del archivo ha sido de " . $tiempo . " segundos";*/


 ?>

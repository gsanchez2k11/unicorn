<?php
/**
 * Listamos todo el catálogo de magento
 * da error 504
 */
?>
<?php
error_reporting(E_ALL);                                     //Activamos el reporte de errores
ini_set('display_errors', 'on');
require_once __DIR__ . '/../../config.php.inc';
$tiempo_inicial = microtime(true); //true es para que sea calculado en segundos
use unicorn\clases\funciones\magento\Articulos as articulosMage;
require_once RAIZ . '/clases/funciones/magento/Articulos.php';

$articulos = array();
$pagina = 1;
//$tiendas = range(1,4);
$i = 0; //contador para pruebas

do {
  $parametros = array (
    'pagina' => $pagina
  );
  $arts_mage = articulosMage::getListaPaginaArticulos($parametros);
  foreach ($arts_mage as $articulo) {
    $articulos[] = $articulo;
  }
  $pagina++;
  $i++;
} while (count($arts_mage) === 100 /*|| $i <= 10*/);

$variable = json_encode($articulos);
echo $variable;
 


/*foreach ($tiendas as $tienda) {
  $pagina = 1;
  do {
    $parametros = array (
      'pagina' => $pagina,
      'tienda' => $tienda
    );
    
    $listado = articulosMage::listarArticulos($parametros);
    foreach ($listado->items as $articulo) {
      $articulos[] = $articulo;
    }
    $pagina++;
  } while (count($listado->items) === 1000);
}



echo '<pre>';
echo count($articulos);
//print_r($articulos);
echo '</pre>';
$tiempo_final = microtime(true);
$tiempo = $tiempo_final - $tiempo_inicial; //este resultado estará en segundos
echo "El tiempo de ejecución del archivo ha sido de " . $tiempo . " segundos";*/
 
 ?>

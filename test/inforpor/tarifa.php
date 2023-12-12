<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . '/../../config.php.inc';
//use unicorn\clases\objetos\Articulos\Articulo_tarifa_inforpor as articulo;
//require RAIZ . '/clases/objetos/Articulos/Articulo_tarifa_inforpor.php';
use unicorn\clases\funciones\inforpor\Tarifa as tarifa;
require RAIZ . '/clases/funciones/inforpor/Tarifa.php';
use unicorn\clases\funciones\unicorn_db\Config as config;
require_once RAIZ . '/clases/funciones/unicorn_db/Config.php';
use unicorn\clases\objetos\Articulos\Articulo_tarifa_inforpor as articulo;
require_once RAIZ . '/clases/objetos/Articulos/Articulo_tarifa_inforpor.php';
use unicorn\clases\funciones\magento\Articulos as articulos;
require_once RAIZ . '/clases/funciones/magento/Articulos.php';
?>
<?php
/*$filetime_almacenado = config::dameValorConfig('inforpor_ftp_filetime');

$tar = tarifa::checkVersionTarifa($filetime_almacenado);
$tarifa_inforpor_json = tarifa::gestionaTarifaNueva($tar);
$tarifa_inforpor = json_decode($tarifa_inforpor_json, true, 512); //la convertimos desde el JSON
$todos_articulos = articulos::getAllProducts(1); //Pedimos el catálogo completo de magento*/



 ?>

 <?php
$remota = tarifa::dameTarifaRemota();
$arts = array_map(function($art){
  $salida = (object)[
    'codigo' => $art->getCodigo(),
    'referencia' => $art->getReferencia(),
    'referencia2' => $art->getReferencia2(),
    'lpi' => $art->getLpi(),
    'precio' => $art->getPrecio(),
    'stock' => $art->getStock(),
    'ean' => $art->getEan(),
    'reserva' => $art->getReserva(),
    'custodia' => $art->getCustodia()
  ];
  return $salida;
},$remota);

$json_tarifa = json_encode($arts,JSON_HEX_QUOT | JSON_PARTIAL_OUTPUT_ON_ERROR); //La convertimos a JSON
//La guardamos
$fp = fopen(RAIZ . '/var/import/inforpor/tarifa.lite', 'w'); //Abrimos un archivo temporal para trabajar con él
fwrite($fp, $json_tarifa);
fclose($fp);
echo '<pre>';
print_r($arts);
echo '</pre>';

 ?>

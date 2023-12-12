<?php 
/**
 * Configuramos los precios según las distintas reglas de márgenes
 */
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . '/../../config.php.inc';
require_once(RAIZ . '/vendor/autoload.php');
?>
<?php
use unicorn\clases\funciones\magento\Articulos as articulos;
require RAIZ . '/clases/funciones/magento/Articulos.php';

$params = array(
    'pagina' => 1,
    'tienda' => 2
);
$listar_articulos = articulos::listarArticulos($params);

echo '<pre>';
print_r($listar_articulos);
echo '</pre>';
?>
<?php
error_reporting(E_ALL);                                     //Activamos el reporte de errores
ini_set('display_errors', 'on');
require_once __DIR__ . '/../../config.php.inc';
require_once RAIZ . '/clases/funciones/magento/Articulos.php';
use unicorn\clases\funciones\magento\Articulos as articulos;
?>
<?php
$sku = $_POST['id'];
$atributos['name'] = $_POST['value'];
$id_tienda = isset($_POST['plataforma']) && $_POST['plataforma'] == 'magento-2-beta' ? 2 : 1 ; //Pasamos la plataforma para poder actualizar en la nueva versión

$actualizar = articulos::actualizarArticulo($sku,$atributos,$id_tienda);


/*echo '<pre>';
print_r($actualizar);
echo '</pre>';*/
echo $actualizar->name;

 ?>

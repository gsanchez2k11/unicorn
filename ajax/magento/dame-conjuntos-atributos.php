<?php
error_reporting(E_ALL);                                     //Activamos el reporte de errores
ini_set('display_errors', 'on');
require_once __DIR__ . '/../../config.php.inc';

use unicorn\clases\funciones\magento\Conjuntoatributos as conjuntos;
require_once RAIZ . '/clases/funciones/magento/Conjuntoatributos.php';
$id_tienda = isset($_POST['idTienda']) ? $_POST['idTienda'] : 1;


$arts_mage = conjuntos::tipoarticulo($id_tienda);
//$items = $ultimos_pedidos->items;
/*echo "<pre>";
print_r($id_tienda);
echo "</pre>";*/


$variable = json_encode($arts_mage);
echo $variable;
 ?>

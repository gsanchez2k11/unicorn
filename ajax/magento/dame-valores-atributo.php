<?php
error_reporting(E_ALL);                                     //Activamos el reporte de errores
ini_set('display_errors', 'on');
require_once __DIR__ . '/../../config.php.inc';

use unicorn\clases\funciones\magento\Conjuntoatributos as atributos;
require_once RAIZ . '/clases/funciones/magento/Conjuntoatributos.php';
$id_tienda = isset($_POST['idTienda']) ? $_POST['idTienda'] : 1;

if (isset($_POST['idAttr'])) {
    $id_attr = $_POST['idAttr'];
} else {
    $id_attr = $id_tienda == 1 ? 81 : 137;
}

//echo $id_tienda . ' ' . $id_attr;
//La podemos utilizar para listar los valores de cualquier atributo en magento
$arts_mage = atributos::infoatributo($id_tienda,$id_attr);
//$items = $ultimos_pedidos->items;
/*echo "<pre>";
print_r($ultimos_pedidos);
echo "</pre>";*/


$variable = json_encode($arts_mage);
echo $variable;
 ?>

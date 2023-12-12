<?php
error_reporting(E_ALL);                                     //Activamos el reporte de errores
ini_set('display_errors', 'on');
require_once __DIR__ . '/../../config.php.inc';

use unicorn\clases\funciones\magento\Pedidos as pedidos;
require_once RAIZ . '/clases/funciones/magento/Pedidos.php';

$arr = array();
if (isset($_POST['termino'])) {
   $arr['fields'][] = array(
    'field' => 'increment_id',
    'value' => $_POST['termino'],
    'condition_type' => 'like'
   );
}

if (isset($_POST['idTienda'])) {
  $arr['idTienda'] = $_POST['idTienda'];
}

$pedidos = pedidos::buscarPedidos($arr);







$variable = json_encode($pedidos);
echo $variable;
 ?>

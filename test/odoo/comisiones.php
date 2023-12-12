<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . '/../../config.php.inc';
require_once(RAIZ . '/vendor/autoload.php');
use Ripoo\OdooClient;

use unicorn\clases\funciones\odoo\Articulos as articulos;
require RAIZ . '/clases/funciones/odoo/Articulos.php';


/*$comision = array (
  'agent' => 209,                                                                //Agente (209 PC componentes)
  'commission' => 8,                                                            //Vamos a usar como ejemplo la de impresoras
  'object_id' =>  9981,                                                              //Linea del pedido
'otro' => 555
);

$articulos = articulos::crear($comision, 'sale.order.line.agent');*/
//$cambiar_comision = articulos::actualizar('object_id',9981,'amount',0,'sale.order.line.agent');
/*$campo = 'id';
$valor = 2375;
$ids = articulos::busqueda($campo,$valor,'sale.order.line.agent');*/



/*$valor = 'general';
$campo = 'name';
$busqueda = articulos::like($campo,$valor,'sale.commission');*/


$ids = array(
  'id' => 2375
);
$articulos = articulos::eliminar($ids, 'sale.order.line.agent');

echo "<pre>";
print_r($ids);
print_r($articulos);
print_r($cambiar_comision);
echo "</pre>";
 ?>

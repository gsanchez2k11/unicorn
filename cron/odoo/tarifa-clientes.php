<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . '/../../config.php.inc';
require_once(RAIZ . '/vendor/autoload.php');
use Ripoo\OdooClient;
use unicorn\clases\funciones\odoo\Conectar as conectar;
require RAIZ . '/clases/funciones/odoo/Conectar.php';
use unicorn\clases\funciones\unicorn_db\Usuarios as usuarios;
require RAIZ . '/clases/funciones/unicorn_db/Usuarios.php';
require_once RAIZ . '/clases/funciones/odoo/Clientes.php';

use unicorn\clases\funciones\odoo\Clientes as cliente;
?>

<?php
$usuarios_activos = usuarios::listarUsuarios(); //Pedimos el listado de usuarios activos
$usuarios_con_odoo = array_filter($usuarios_activos, function ($u){ //Filtramos para dejar solo aquellos que tiene id de odoo
    return !empty($u['id_odoo']);
});

foreach ($usuarios_con_odoo as $key => $cliente) {
    $campo = 'id';
    $valor = $cliente['id_odoo'];
    $busqueda_clientes = cliente::busqueda($campo, $valor, 'res.partner')[0]; //Buscamos al cliente
    $id_tarifa = $busqueda_clientes['property_product_pricelist'][0]; //Id de su tarifa
    $busqueda_tarifa = cliente::busqueda('id',$id_tarifa,'product.pricelist')[0]; //Datos e items de esa tarifa
    $items = $busqueda_tarifa['item_ids'];
//Recorremos los items para obtener las condiciones
foreach ($items as $item) {
    $campo = 'id';
    $valor = $item;
    $busqueda_items = cliente::busqueda($campo, $valor, 'product.pricelist.item');
}

    echo '<pre>';
print_r($busqueda_items);
echo '</pre>';

}
?>

<?php

?>
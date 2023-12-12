<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . '/../../config.php.inc';
require_once(RAIZ . '/vendor/autoload.php');
use Ripoo\OdooClient;

//use unicorn\clases\funciones\odoo\Conectar as conectar;
//require RAIZ . '/clases/funciones/odoo/Conectar.php';
require_once RAIZ . '/clases/funciones/odoo/Clientes.php';
use unicorn\clases\funciones\odoo\Clientes as cliente;
$campo = 'id';
$valor = 217;
$id = cliente::busqueda($campo,$valor,'res.partner');

echo "<pre>";
print_r($id);
echo "</pre>";



$datos_cliente = [
  'name' => strtoupper('cliente de prueba'),
  'street' => 'calle falsa 123',
  'vat' => strtoupper('12345678A'),                          //Ponemos el NIF en mayúsculas
//  'name' => 'Cleinte raro',
//  'street' => 'Calle melancolia 13',
//  'vat' => '48402997R',
 'email' => 'batman@gotham.com',
//  'mobile' => cliente::formatear_tlfo('666999111'),
//  'zip' => '01234',
  'city' => 'Murcia',
  //'city' => 'Malaga',
//  'state_id' => cliente::dameStateId('Murcia'),
//  'state_id' => 451,                                                            //Fijamos Murcia para depuración
  'tz'   => 'Europe/Madrid',                                                   //Zona horaria
  'country_id' => 68,                                                        //Pais España
//  'category_id' => '16',                                                       //Cogido de un cliente de PCC, no tengo claro que es
  //'x_studio_linea_de_negocio' => 'Tiendaplotter',
  'property_product_pricelist' => 1,                                          //Tarifa
  'property_account_position_id' => 24,                                      //Regimen nacional
  'property_payment_term_id'     => 1,                                       //Pago a la vista
  'customer_payment_mode_id' => 16,                                          //Forma de pago (11 PCC, 16 Contado)
//  'category_id' => array(0, 0, [['id',16]])
];






//$crear = cliente::crear($datos_cliente,'res.partner');

echo "<pre>";
print_r($crear);
echo "</pre>";


 ?>

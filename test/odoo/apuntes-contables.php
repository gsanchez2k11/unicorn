<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . '/../../config.php.inc';
require_once(RAIZ . '/vendor/autoload.php');
use Ripoo\OdooClient;

use unicorn\clases\funciones\odoo\Conectar as conectar;
require RAIZ . '/clases/funciones/odoo/Conectar.php';

$factura = conectar::listar('account.move.line',1);

$apunte = array(
  'account_id' => 1388,
  'date_maturity' => '2022-04-05',

);


echo "<pre>";
print_r($factura);
echo "</pre>";

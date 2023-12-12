<?php
error_reporting(E_ALL);                                     //Activamos el reporte de errores
ini_set('display_errors', 'on');
require_once __DIR__ . '/../../config.php.inc';
require_once(RAIZ . '/vendor/autoload.php');
require_once RAIZ . '/clases/funciones/otras/Mail.php';
use unicorn\clases\funciones\otras\Mail as email;

$enviar = email::enviaCorreo($_POST);
$json_cliente = json_encode($enviar);
echo $json_cliente;
 ?>

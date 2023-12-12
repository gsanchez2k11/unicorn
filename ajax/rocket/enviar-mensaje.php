<?php
error_reporting(E_ALL);                                     //Activamos el reporte de errores
ini_set('display_errors', 'on');
require_once __DIR__ . '/../../config.php.inc';
require_once __DIR__ . '/../../clases/funciones/rocket/Ims.php';
require_once __DIR__ . '/../../clases/funciones/rocket/Mensajes.php';
use unicorn\clases\funciones\rocket\Ims as ims;
use unicorn\clases\funciones\rocket\Mensajes as msj;

$cliente = $_POST['cliente'];
$mensaje = $_POST['mensaje'];
$usuarios = array($_POST['usuario']); //Los usuarios son un array

$room = ims::CrearSesion($usuarios);
$mensajear = msj::PostMensaje($room,$mensaje,$cliente['name']);

if (get_class($mensajear) == 'ATDev\RocketChat\Messages\Message') {
echo 'ok';
} else {
    echo 'error';
}


?>
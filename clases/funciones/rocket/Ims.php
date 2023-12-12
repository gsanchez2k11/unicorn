<?php
namespace unicorn\clases\funciones\rocket;
error_reporting(E_ALL);                                     //Activamos el reporte de errores
ini_set('display_errors', 'on');
//require_once(RAIZ . '/vendor/autoload.php');
require_once 'Conectar.php';
//use \ATDev\RocketChat\Chat as chat;


class Ims extends Conectar
{

/**
 * Crea una sesión de mensaje directo con uno o varios usuarios
 * @param $usuarios Array 
 * @return String la id de la conversacion
 */
public static function CrearSesion($usuarios){
self::conectar(); //HAcemos el login
$im = new \ATDev\RocketChat\Ims\Im();
if (count($usuarios) == 1) {
    $im->setUsername($usuarios[0]);
} else {
    $im->setUsernames(implode(",", $usuarios));
}


$result = $im->create();
if (!$result) {

	// Log the error
	$error = $im->getError();
}
return $im->getRoomId();

}    

   
}

?>
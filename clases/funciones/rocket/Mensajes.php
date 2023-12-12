<?php
namespace unicorn\clases\funciones\rocket;
error_reporting(E_ALL);                                     //Activamos el reporte de errores
ini_set('display_errors', 'on');
//require_once(RAIZ . '/vendor/autoload.php');
require_once 'Conectar.php';
use \ATDev\RocketChat\Chat as chat;


class Mensajes extends Conectar
{

/**
 * Publicamos un mensaje en un canal
 * @param $canal String 
 * @param $mensaje String
 * @return ATDev\RocketChat\Messages\Message Object 
 */
public static function PostMensaje(string $canal, string $mensaje, string $alias = '', string $avatar = ''){
self::conectar();
    $message = new \ATDev\RocketChat\Messages\Message();
    $message->setRoomId($canal);
    $message->setText($mensaje);
    if($alias != '') $message->setAlias($alias);
    if($avatar != '') $message->setAvatar($avatar);
    $result = $message->postMessage();
    
    if (!$result) {
        // Log the error
        $error = $message->getError();
    }
return $result;

}    

   
}

?>
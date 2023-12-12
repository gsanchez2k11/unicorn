<?php
namespace unicorn\clases\funciones\rocket;
error_reporting(E_ALL);                                     //Activamos el reporte de errores
ini_set('display_errors', 'on');
require_once(RAIZ . '/vendor/autoload.php');

use \ATDev\RocketChat\Chat as chat;


class Conectar
{

public static function conectar(){
 // Firstly, init
 chat::setUrl("https://slack.futura.es"); // No trailing /
 // Now, login
$result = chat::login("unicorn@futura.es", "Unicornio28.");

if (!$result) {

	// Log the error
	$error = \ATDev\RocketChat\Chat::getError();
}

return $result;

}    

   
}

?>
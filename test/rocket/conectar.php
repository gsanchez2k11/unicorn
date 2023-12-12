<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . '/../../config.php.inc';
require_once(RAIZ . '/vendor/autoload.php');

// Firstly, init
/*\ATDev\RocketChat\Chat::setUrl("https://slack.futura.es"); // No trailing /

// Now, login
$result = \ATDev\RocketChat\Chat::login("unicorn@futura.es", "Unicornio28.");

if (!$result) {

	// Log the error
	$error = \ATDev\RocketChat\Chat::getError();
}*/

//Enviar mensaje a canal

/*$message = new \ATDev\RocketChat\Messages\Message();
$message->setRoomId("offtopic");
$message->setText("¿Que tasaciendo?");
$result = $message->postMessage();

if (!$result) {
	// Log the error
	$error = $message->getError();
}*/


//Enviar mensaje privado
/*$im = new \ATDev\RocketChat\Ims\Im();

$im->setUsername("miriam");
// or
//$im->setUsernames("username_first, username_second, username_third");
echo '<pre>';
print_r(get_class_methods($im));
echo '</pre>';
$result = $im->create();

if (!$result) {

	// Log the error
	$error = $im->getError();
}*/

/*use unicorn\clases\funciones\rocket\Conectar;
require RAIZ . '/clases/funciones/rocket/Conectar.php';
$prueba = Conectar::conectar();*/

use unicorn\clases\funciones\rocket\Mensajes;
require RAIZ . '/clases/funciones/rocket/Mensajes.php';
//$prueba = Mensajes::PostMensaje('offtopic','¿Que tasasiendo?');

use unicorn\clases\funciones\rocket\Ims;
require RAIZ . '/clases/funciones/rocket/Ims.php';
$room = Ims::CrearSesion(['gabriel','miriam']);
//$room = $prueba->getRoomId();
$mensaje = Mensajes::PostMensaje($room,'taza');



echo '<pre>';
print_r($mensaje);
print_r($prueba);
print_r(get_class_methods($prueba));
echo '</pre>';

?>
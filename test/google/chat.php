<?php
//namespace unicorn\clases\funciones\google;
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . '/../../config.php.inc';
require_once(RAIZ . '/vendor/autoload.php');


?>
<?php

$client = new \Google_Client();
$client->setApplicationName('prueba chat');
$client->setAuthConfig(__DIR__ . '/../../credentials.json');
$client->setScopes(['https://www.googleapis.com/auth/chat.bot']);
$chat = new \Google\Service\HangoutsChat($client);
$message = new \Google\Service\HangoutsChat\Message();
$message->setText("This is example message");
$createMessage = $chat->spaces_messages->create('spaces/AAAAqFtzdps',$message);

echo '<pre>';
print_r($createMessage);
echo '</pre>';

?>

<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . '/../../config.php.inc';
require_once(RAIZ . '/vendor/autoload.php');

$data        = array("username" => 'apiAdmin', "password" => 'chonoPa1');
$data_string = json_encode($data);
$url = 'https://tiendaplotter.com/';
$headers = array(
    'Content-Type: application/json',
    'Content-Length: ' . strlen($data_string),
);

$ch = curl_init($url . 'index.php/rest/V1/integration/admin/token');
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
$token = curl_exec($ch);

echo '<pre>';
print_r($token);
echo '</pre>';
$token = json_decode($token);

echo $token;
?>
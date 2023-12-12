<?php
//namespace unicorn\clases\funciones\fnac;
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../../config.php.inc';
//use unicorn\clases\funciones\fnac\Conectar as conectar;
//require_once RAIZ . '/clases/funciones/fnac/Conectar.php';
//$token = conectar::getToken();

use unicorn\clases\funciones\fnac\Batch as batch;
require_once RAIZ . '/clases/funciones/fnac/Batch.php';
//ABC9CFB3-3538-5762-34A6-3B7AC7D5557F

$batch_id = 'CDF069B0-B398-547A-55C1-1929F1155248';


$add = batch::batchStatus($batch_id);
//Recorremos los atributos para saber si tenemos o no el artículo

echo '<pre>';
//print_r($add->error);
print_r($add);
echo '</pre>';

 ?>

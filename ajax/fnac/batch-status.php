<?php
error_reporting(E_ALL);                                     //Activamos el reporte de errores
ini_set('display_errors', 'on');
require_once __DIR__ . '/../../config.php.inc';
require_once(RAIZ . '/vendor/autoload.php');
use unicorn\clases\funciones\fnac\Batch as batch;
require_once RAIZ . '/clases/funciones/fnac/Batch.php';
$batch_id = $_POST['batch_id'];
//echo $batch_id;
//Hacemos peticiones hasta que tengamos el ok
do {
$buscar = batch::batchStatus($batch_id);
} while (strtolower((string) $buscar->attributes()->status) != 'ok');


$oferta = $buscar->offer; //Nos quedamos con la oferta

//$resultado = strtolower((string) $buscar->attributes()->status);

$json_resultado = json_encode($oferta);
echo $json_resultado;
//

//echo '<pre>';
//print_r($oferta);
//print_r($resultado);
//echo '</pre>';
?>
<?php
/**
*SimpleXMLElement Object
*(
*    [@attributes] => Array
*        (
*            [status] => ERROR
*        )
*
*    [offer_seller_id] => A133-0174
*    [error] => Product not found
*)
*
*(
*    [@attributes] => Array
*        (
*            [status] => OK
*            [action] => Deleted
*        )
*
*    [product_fnac_id] => 5840866
*    [offer_fnac_id] => 3A99F5A8-1A05-AA36-A514-A29C05D5F052
*    [offer_seller_id] => MFCL9570CDWRE1
*)
*
*
*
*
*
 */

 ?>

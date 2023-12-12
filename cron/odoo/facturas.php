<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . '/../../config.php.inc';
require_once(RAIZ . '/vendor/autoload.php');
use Ripoo\OdooClient;

use unicorn\clases\funciones\odoo\Conectar as conectar;
use unicorn\clases\funciones\mirakl\Documentos as docs;
use unicorn\clases\funciones\mirakl\Mensajes as mensajes;

require RAIZ . '/clases/funciones/odoo/Conectar.php';
require RAIZ . '/clases/funciones/mirakl/Documentos.php';
require RAIZ . '/clases/funciones/mirakl/Mensajes.php';


$minutos = 150;                                                                  //Antiguedad máxima de la factura (hay que tener en cuenta que la hora del servidor puede no coincidir con la hora de odoo)
$objDateTime = new DateTime('NOW');                                             //Objeto fecha y hora
$objDateTime->sub(new DateInterval('PT'. $minutos . 'M'));                      //Restamos el tiempo que queremos
$string_hora = $objDateTime->format('Y-m-d H:i:s');                             //Creamos un string para buscar en odoo

$criterios[] = array (                                                                  //Generamos el array de busqueda
  'campo' => 'create_date',
  'operador' => '>=',
  'valor' => $string_hora
);
$criterios[] = array (                                                                  //Generamos el array de busqueda
  'campo' => 'journal_id',
  'operador' => '=',
  'valor' => 34
);

$facturas = conectar::busquedaConOperador($criterios,'account.invoice');              //Buscamos las facturas que corresponden a ese periodo. Nos devuelve un array




  function dameArchivo($url,$path){
  /*$url  = 'http://www.example.com/a-large-file.zip';
 $path = '/path/to/a-large-file.zip';*/

 $fp = fopen($path, 'w+');

 $ch = curl_init($url);
 curl_setopt($ch, CURLOPT_FILE, $fp);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

 $data = curl_exec($ch);

 curl_close($ch);
 fclose($fp);
  }


foreach ($facturas as $key => $factura) {                                               //Recorremos el array de facturas
//Hacemos la busqueda individual para cada factura
$url_fra = 'https://erp.futura.es' .  $factura['access_url'] . '?report_type=pdf&download=true';

$arr_nombre = explode(' ',$factura['vendor_display_name']);
$nombre_cliente = $arr_nombre[0];

//echo "<pre>";
//print_r($nombre_cliente);
//echo "</pre>";
/**
 * Para acceder a la factura necesitamos que exista el access_token, este se genera al previsualizar la factura
 * @var [type]
 */
if (!empty($factura['access_token'])) {
$url_fra .= '&access_token=' . $factura['access_token'];

//$temp_file_contents = collect_file($url_fra);
//  write_to_file($temp_file_contents,'factura_' . $key . '.pdf');
//dameArchivo($url_fra,RAIZ . '/docs/factura_' . $key . '.pdf');

$lineas = $factura['invoice_line_ids'];                                         //Nos quedamos con las lineas de la factura
$num_lineas = count($lineas) - 1;
$id_pedido = false;
$i = 0;
while ($i <= $num_lineas && $id_pedido === false) {
  $info_linea = conectar::busqueda('id',$lineas[$i],'account.invoice.line');
//  echo "<pre>";
//  print_r($info_linea);
//  echo "</pre>";
  if (!empty($info_linea[0]['display_type']) && $info_linea[0]['display_type'] == 'line_note') {                                   //Nos quedamos solo con las lineas de comentarios
    $nota = $info_linea[0]['name'];                                                  //El campo nombre es la nota
    $id_pedido = substr($nota,strlen($nota)-9);                                   //los últimos 9 caracteres son la referencia del pedido
    $tiene_factura = docs::tieneFactura($id_pedido);                              //1 -> el cliente tiene factura subida, 0 -> no tiene fac
  //  $tiene_factura = 0;                                                          //Para las pruebas lo fijamos en 0
  }
  $i++;
}
if ($id_pedido !== false && $tiene_factura == 0) {                                                     //Si tenemos la id del pedido generamos el pdf
dameArchivo($url_fra,RAIZ . '/docs/' . $id_pedido . '.pdf');                    //Generamos el pdf
docs::subeFactura($id_pedido);                                                  //Lo subimos
//Y Generamos también el mensaje
$asunto = 'Muchas gracias por tu confianza';
//$cuerpo = 'Hola, ' . $nombre_cliente . '.¡¡Esperamos que estés disfrutando de tu pedido, y nos lo hagas saber dejándonos tu valoración!! Vendedor de PcComponentes Marketplace.';
$cuerpo = 'Hola, ' . $nombre_cliente . '.¡¡Esperamos que estés disfrutando de tu pedido, y nos lo hagas saber dejándonos 5 estrellas, que nos ayudaran a seguir creciendo y mejorando par ti!! Vendedor de PcComponentes Marketplace.';
$tema = mensajes::creaHilo($id_pedido, $asunto, $cuerpo);
}
unset($id_pedido);
unset($i);

//




}
}

<?php
namespace unicorn\clases\funciones\inforpor;
use unicorn\clases\objetos\Articulos\Articulo_tarifa_inforpor as articulo;
require RAIZ . '/clases/objetos/Articulos/Articulo_tarifa_inforpor.php';
require_once RAIZ . '/clases/funciones/unicorn_db/Entidad.php';
use unicorn\clases\funciones\unicorn_db\Entidad as entidad;
use unicorn\clases\funciones\unicorn_db\Config as config;
require_once RAIZ . '/clases/funciones/unicorn_db/Config.php';
 ?>
<?php

/**
 *
 */
class Tarifa
{


/**
 * Abre la conexion curl
 */
public static function conexionCurl() {
  $ftp_username = config::dameValorConfig('inforpor_ftp_user');
  $ftp_userpass = config::dameValorConfig('inforpor_ftp_pass');
  $ftp_server = config::dameValorConfig('inforpor_ftp_url');
  $remote_file = 'Inforpor_1259.csv';
  
 // $f = fopen('php://temp', 'w+');
  
  $curl = curl_init();
  curl_setopt($curl, CURLOPT_URL, $ftp_server . '/' . $remote_file);
  curl_setopt($curl, CURLOPT_ENCODING, 'ISO-8859-15'); // Doesn't seem to work
  curl_setopt($curl, CURLOPT_VERBOSE, TRUE);
  curl_setopt($curl, CURLOPT_HEADER, 0); 
  curl_setopt($curl, CURLOPT_FILETIME, 1); 
  curl_setopt($curl, CURLOPT_USERPWD, $ftp_username . ":" . $ftp_userpass);
  curl_setopt($curl, CURLOPT_TIMEOUT, 30);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
  // curl_setopt($curl, CURLOPT_FILE, $f);
 //curl_exec($curl);
 // $info = curl_getinfo($curl);
 // curl_close($curl);
  return $curl;
}

/**
 * Devuelve el nuevo filetime si hay una nueva versión de la tarifa de inforpor o false si no es así
 * 
 */
public static function checkVersionTarifa($filetime_almacenado){
//Obtenemos la info del archivo remoto
$info_remota = self::dameFileInfo();
$filetime_remoto = $info_remota['filetime'];
$tamano_fichero = $info_remota['download_content_length'];
//Si son distintos significa que tenemos un fichero remoto más reciente que el local
if ($filetime_remoto != $filetime_almacenado && $tamano_fichero > 0) {
  return $filetime_remoto;
}else {
return false;
}
}


public static function gestionaTarifaNueva($filetime_remoto){
 //Tenemos que activar la rotación de archivos
 $ruta =  RAIZ . '/var/import/inforpor/'; //ruta donde guardamos los ficheros 
 
 $ficheros = scandir($ruta); //Cargamos los ficheros de la ruta

//Nos quedamos solo con los que son formato tarifa.NNN
$tarifas = array_filter($ficheros, function ($arr) {
  return preg_match('/^tarifa\.\d{3}$/', $arr);
});
//Ordenamos el array
sort($tarifas);
$reverso = array_reverse($tarifas); //Le damos la vuelta para poder incrementar el número sin sobrescribir lo existente
foreach ($reverso as $fichero) {
 $max_tarifas = config::dameValorConfig('inforpor_ftp_maximo_archivos'); //El máximo de ficheros de tarifa que queremos conservar
  $nombre_actual = $fichero;
  $extension_actual = intval(substr($nombre_actual,strlen($nombre_actual)-3,strlen($nombre_actual)));
  if ($extension_actual >=  $max_tarifas) { //Si el archivo es superior al máximo a almacenar lo eliminamos
   unlink($ruta . $nombre_actual);
  } else {   //Si el fichero entra en rando de lo que queremos almacenar
  $extension = $extension_actual+1; //Añadimos uno a la extensión actual
  $str = substr("000{$extension}", -3); //Añadimos 0 hasta tener una extension de 3 cifras (001,002, etc)
  $nombre_nuevo = substr($nombre_actual,0,strlen($nombre_actual)-3) . $str;
rename($ruta . $nombre_actual, $ruta . $nombre_nuevo); //Cambiamos el nombre de los archivos
 }
}

//Cargamos la tarifa remota
$remota = self::dameTarifaRemota();
$json_tarifa = json_encode($remota,JSON_HEX_QUOT | JSON_PARTIAL_OUTPUT_ON_ERROR); //La convertimos a JSON
//La guardamos
$fp = fopen(RAIZ . '/var/import/inforpor/tarifa.000', 'w+'); //Abrimos un archivo temporal para trabajar con él
fwrite($fp, $json_tarifa);
fclose($fp);
$retorno = $json_tarifa;
//Actualizamos el filetime en la bbdd
$row = array(
  'config' => 'inforpor_ftp_filetime',
  'valor' => $filetime_remoto
);
config::updateValorConfig($row);

//vamos a generar la versión lite para mayor rendimiento
$arts = array_map(function($art){
  $salida = (object)[
    'codigo' => $art->getCodigo(),
    'referencia' => $art->getReferencia(),
    'referencia2' => $art->getReferencia2(),
    'lpi' => $art->getLpi(),
    'precio' => $art->getPrecio(),
    'stock' => $art->getStock(),
    'ean' => $art->getEan(),
    'reserva' => $art->getReserva(),
    'custodia' => $art->getCustodia()
  ];
  return $salida;
},$remota);

$json_tarifa = json_encode($arts,JSON_HEX_QUOT | JSON_PARTIAL_OUTPUT_ON_ERROR); //La convertimos a JSON
//La guardamos
$fp = fopen(RAIZ . '/var/import/inforpor/tarifa.lite', 'w'); //Abrimos un archivo temporal para trabajar con él
fwrite($fp, $json_tarifa);
fclose($fp);

return $retorno;
}
/**
 * Gestiona los ficheros con la tarifa y devuelve un JSON con la última tarifa
 */
public static function dameJSONTarifa(){
//Cargamos el filetime de la última tarifa
$filetime_almacenado = config::dameValorConfig('inforpor_ftp_filetime');
//Obtenemos la info del archivo remoto
//$info_remota = self::dameFileInfo();
//$filetime_remoto = $info_remota['filetime'];
//$tamano_fichero = $info_remota['download_content_length'];
//Si son distintos significa que tenemos un fichero remoto más reciente que el local
//if ($filetime_remoto != $filetime_almacenado && $tamano_fichero > 0) {
 $chkTarifa = self::checkVersionTarifa();
 if ($chkTarifa !== false) {
 $retorno = self::gestionaTarifaNueva($chkTarifa); //Si hay tarifa nueva estamos recibiendo el filetime remoto
  //Prueba
  /* echo '<pre>';
   print_r($json_tarifa);
   echo '</pre>';*/
 } else { //Si el filetime coincide
  //$local = file_get_contents(RAIZ . '/var/import/inforpor/tarifa.000');
  //$json_tarifa = json_encode($local,JSON_HEX_QUOT | JSON_PARTIAL_OUTPUT_ON_ERROR); //La convertimos a JSON
  //$retorno = $json_tarifa;
  $retorno = file_get_contents(RAIZ . '/var/import/inforpor/tarifa.000');
 }
return $retorno;
}

/**
 * Obtiene el último array de objetos artículo inforpor desde el fichero remoto
 */
public static function dameTarifaRemota(){
  $f = fopen('php://temp', 'w+'); //Abrimos un archivo temporal para trabajar con él
  $curl = self::conexionCurl();
  curl_setopt($curl, CURLOPT_FILE, $f);
  curl_exec($curl);
  curl_close($curl);
  rewind($f);
  $articulos = array();
  while (($row = fgetcsv($f, 100000, ';')) !== FALSE)
{
$row = array_map("utf8_decode", $row); //added
if (count($row) >= 23 && $row[3] > 0) {
  $articulos[] = new articulo($row);
}
}

fclose($f);
return $articulos;
}




/**
 * Devuelve el filetime del archivo
 */
public static function dameFileInfo() {
$curl = self::conexionCurl();
curl_exec($curl);
$info = curl_getinfo($curl);
curl_close($curl);
//echo '<pre>';
//print_r($info);
//echo '</pre>';
//$filetime = $info['filetime'];
return $info;
}

public static function dameTarifa($archivo = RAIZ . '/var/import/Inforpor_1259.csv'){
  $resultado = self::csv_to_array($archivo);
  return $resultado;
}

public static function csv_to_array($filename='', $delimiter=';')
{
	if(!file_exists($filename) || !is_readable($filename))
	//	return FALSE;
switch (true) {
	case (!file_exists($filename)):
return 'no se encuentra el archivo';
		break;
		case (!is_readable($filename)):
	return 'archivo ilegible';
			break;
}

	$header = NULL;
	$data = array();
	if (($handle = fopen($filename, 'r')) !== FALSE)
	{
		$fabricante = '';
		$familia = '';
    $n_linea = 0;

/*
[0] => GAMA
   [1] =>  FABRICANTE
   [2] =>  FAMILIA
   [3] =>  CODIGO
   [4] =>  REFERENCIA
   [5] =>  REFERENCIA2
   [6] =>  DESCRIPCION
   [7] =>  COMPATIBILIDADES
   [8] =>  CARACTERISTICAS
   [9] =>  LPI
   [10] =>  PRECIO
   [11] =>  SIOFERTA
   [12] =>  STOCK
   [13] =>  CODIGO DE BARRAS
   [14] =>  EMBALAJE
   [15] =>  PALET
   [16] =>  PESO
   [17] =>  FECHA ENTRADA
   [18] =>  IMAGEN
   [19] =>  PROMO
   [20] => FECHAFINPROMO
   [21] =>
*/
$articulos = array();
//Para hacer pruebas limitamos con el segundo parámetro
		while (($row = fgetcsv($handle, 100000, $delimiter)) !== FALSE)
		{
	$row = array_map("utf8_decode", $row); //added
  if (count($row) >= 20 && $row[3] > 0) {
      $articulos[] = new articulo($row);
  }

		}

		fclose($handle);
	}
//	return $data;
return $articulos;
}

public static function buscaEnTarifa($row){
$tarifa = isset($row['tarifa']) ?  $row['tarifa']: self::dameTarifa();
$mpn = isset($row['mpn']) ? $row['mpn'] : 00000 ;
$cod_info_encontrado = array_filter($tarifa, function ($i) use ($mpn) { 
  return $i->getReferencia() == $mpn || $i->getReferencia2() == $mpn; //Buscamos en cualquiera de los campos de referencia
    });
$codigo = 00000;    
if (count($cod_info_encontrado) === 1) { //Si tenemos un resultado lo procesamos
$articulo = reset($cod_info_encontrado); //Lo sacamos del array
$mpn = $articulo->getReferencia();
$mpn2 = $articulo->getReferencia2(); //Por si tuviera doble referencia
$ean = $articulo->getEan(); //el Ean del artículo
$codigo = $articulo->getCodigo();

if (isset($row['entidad'])) { //Puede ser que estemos recibiendo la entidad, y nos ahorramos buscarla
$entidad = $row['entidad'];
} else {
  //Preparamos el array para intentar encontrar la entidad
 $arr = array(
  'mpn' => $mpn ,
  'codinf' => $codigo,
  'cod_inforpor' => $codigo //Duplicamos este campo porque no coincide la función con el nombre del atributo en la base de datos
 ); 
if($mpn2 != '') $arr['mpn2'] = $mpn2;
if($ean != '') $arr['ean'] = $ean;
}
$resultado_busqueda = entidad::buscarEntidad($arr); //Tenemos un array con los valores o una cadena vacia
//echo '<pre>';
//print_r($resultado_busqueda);
//echo '</pre>';
unset($arr['codinf']);
//unset($arr['mpn2']);
if (empty($resultado_busqueda)) { //Si tenemos una cadena vacia creamos la entidad nueva

  $entidad = entidad::crearEntidad($arr); //Creamos una nueva entidad con el mpn y el sku de pcc

} else {
  $entidad = $resultado_busqueda['entidad'];
}
//unset($arr['mpn2']);
  //Actualizamos la entidad con el resto de atributos
  foreach ($arr as $key => $value) {
    $datos = array(
      'entidad' => $entidad,
      'atributo' => entidad::dameIdAtributo($key),
      'valor' => $value
    );
    entidad::actualizarEntidad($datos);
    # code...
  }



   } else { //Si no encontramos la referencia en la tarifa
//echo '<pre>';
//print_r($mpn);
//echo '</pre>';
   }
   return $codigo;
}


public static function matchIfp($articulo_mage,$tarifa_inforpor){
  $array_magento = array();
  $found_key = array_search($articulo_mage->sku, array_column($tarifa_inforpor, 'referencia')); //Buscamos la referencia
  if ($found_key === false) { //Si no se encuentra el artículo devuelve false
    $found_key = array_search($articulo_mage->sku, array_column($tarifa_inforpor, 'referencia2')); //Buscamos la referencia
  }
  if ($found_key === false && isset($articulo_mage->ean)) { //Si no se encuentra el artículo devuelve false
    $found_key = array_search($articulo_mage->ean, array_column($tarifa_inforpor, 'ean')); //Buscamos la referencia
  }
  if ($found_key === false && isset($articulo_mage->cod_inforpor)) { //Como último recurso intentamos emparejar por el codigo insertado en la bbdd para esta entidad
    $found_key = array_search($articulo_mage->cod_inforpor, array_column($tarifa_inforpor, 'codigo')); //Buscamos el código
  }
  if ($found_key !== false) {
      $array_magento['qty'] =  $tarifa_inforpor[$found_key]['stock'] + $tarifa_inforpor[$found_key]['custodia'] + $tarifa_inforpor[$found_key]['stock'];
      $array_magento['is_in_stock'] = $tarifa_inforpor[$found_key]['stock'] > 0 ? 1 : 0;
      $array_magento['precio'] =  $tarifa_inforpor[$found_key]['precio'] + $tarifa_inforpor[$found_key]['lpi'];
  }
  return $array_magento;
}


}

 ?>

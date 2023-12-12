<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . '/../../config.php.inc';
//use unicorn\clases\objetos\Articulos\Articulo_tarifa_inforpor as articulo;
//require RAIZ . '/clases/objetos/Articulos/Articulo_tarifa_inforpor.php';
use unicorn\clases\funciones\inforpor\Tarifa as tarifa;

require RAIZ . '/clases/funciones/inforpor/Tarifa.php';

use unicorn\clases\funciones\unicorn_db\Config as config;

require_once RAIZ . '/clases/funciones/unicorn_db/Config.php';

//use unicorn\clases\objetos\Articulos\Articulo_tarifa_inforpor as articulo;

//require_once RAIZ . '/clases/objetos/Articulos/Articulo_tarifa_inforpor.php';

use unicorn\clases\funciones\mirakl\Cronmirakl as mirakl;

require RAIZ . '/clases/funciones/mirakl/Cronmirakl.php';

use unicorn\clases\funciones\magento\Cronmage as mage;

require RAIZ . '/clases/funciones/magento/Cronmage.php';

use unicorn\clases\funciones\rocket\Ims as ims;
use unicorn\clases\funciones\rocket\Mensajes as msj;

require_once __DIR__ . '/../../clases/funciones/rocket/Ims.php';
require_once __DIR__ . '/../../clases/funciones/rocket/Mensajes.php';
?>
<?php
//Cargamos el filetime de la última tarifa
$filetime_almacenado = config::dameValorConfig('inforpor_ftp_filetime');
$tar = tarifa::checkVersionTarifa($filetime_almacenado);

//Mientras devuelva false tenemos la última versión de la tarifa
if ($tar !== false) {
  // 1. Descargamos la tarifa nueva y cargamos los datos
  $tarifa_inforpor_json = tarifa::gestionaTarifaNueva($tar);
  $tarifa_inforpor = json_decode($tarifa_inforpor_json, true, 512); //la convertimos desde el JSON
  /**
   * 2 Lanzamos las distintas actualizaciones
   *  2.1 Mirakl
   * 2.2 Magento versión 2.2
   * 2.4 Magento versión 2.4
   */
  //$mirakl = mirakl::actualizaMiraklInforpor($tarifa_inforpor);
  $mage22 = mage::actualizaMage($tarifa_inforpor, 1);
  $mage245 = mage::actualizaMage24($tarifa_inforpor);
  $mage_precios = mage::preciosM246($tarifa_inforpor);
} else {
  /*$Now = new DateTime('now', new DateTimeZone('+00:00'));
  $servidor = new DateTime("@$filetime_almacenado");
  $interval = $Now->diff($servidor);
  $horas = $interval->h;
  if ($horas > 24) { //Si han pasado más de 4 horas desde el último archivo me mando un mensaje de aviso
    $cliente = 'unicorn';
    $usuarios = array('gabriel'); //Los usuarios son un array
    $mensaje = 'Han pasado ' . $horas . ' horas desde la última actualización';
    $room = ims::CrearSesion($usuarios);
    $mensajear = msj::PostMensaje($room, $mensaje, $cliente);
  }*/

  //$date = new DateTimeImmutable();
  //$ahora = $date->getTimestamp();
  //$resta = $filetime_almacenado - $ahora; //Calculamos la diferencia entre la hora actual y la almacenada
  // echo date("H",$resta);

}







?>

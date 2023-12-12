<?php
$tiempo_inicial = microtime(true); //true es para que sea calculado en segundos
error_reporting(E_ALL);
ini_set('display_errors', 'on');
require_once __DIR__ . '/../../config.php.inc';
require_once(RAIZ . '/vendor/autoload.php');
//require_once RAIZ . '/clases/funciones/mirakl/Ofertas.php';
//use unicorn\clases\funciones\mirakl\Ofertas as ofertas;
//use unicorn\clases\funciones\unicorn_db\Entidad as entidad;
//require_once RAIZ . '/clases/funciones/unicorn_db/Entidad.php';
require_once RAIZ . '/clases/funciones/unicorn_db/Actualizacion.php';
use unicorn\clases\funciones\unicorn_db\Actualizacion as actualizacion;
require_once RAIZ . '/clases/funciones/otras/Catalogo.php';
use unicorn\clases\funciones\otras\Catalogo as catalogo;
require_once RAIZ . '/clases/funciones/inforpor/Tarifa.php';
use unicorn\clases\funciones\inforpor\Tarifa as tarifa;
?>
<?php
//$plataforma = $_GET['plataforma']; //Obtenemos la plataforma sobre la que queremos recuperar los artículos
$plataforma = $_POST['plataforma'];
//$listado_articulos = $_POST['listado'];
$listado_ofertas = catalogo::dameObjetosMirakl($plataforma); //Pedimos el listado de ofertas para esa plataforma 
$posiciones = actualizacion::addEntidadPosicion($listado_ofertas);

/*echo "<pre>";
print_r($posiciones);
echo "</pre>";*/


echo json_encode($posiciones);
 ?>
 <?php
 //$tiempo_final = microtime(true);
 //$tiempo = $tiempo_final - $tiempo_inicial; //este resultado estará en segundos
 //echo "El tiempo de ejecución del archivo ha sido de " . $tiempo . " segundos";


  ?>

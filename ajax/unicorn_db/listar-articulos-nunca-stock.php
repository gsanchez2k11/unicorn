<?php
namespace unicorn\cron\mirakl;
error_reporting(E_ALL);                                     //Activamos el reporte de errores
ini_set('display_errors', 'on');
require_once __DIR__ . '/../../config.php.inc';

require_once RAIZ . '/clases/funciones/unicorn_db/Operaciones_tablas.php';
use unicorn\clases\funciones\unicorn_db\Operaciones_tablas as operaciones;
require_once RAIZ . '/clases/funciones/unicorn_db/Entidad.php';
use unicorn\clases\funciones\unicorn_db\Entidad as entidad;

 ?>
<?php
$atributo = 13; //trabajamos por defecto con pc componentes y su atributo stock
$entidades = operaciones::nuncaEnStock($atributo); //Pedimos las entidades que nunca han modificado su stock
//print_r($entidades);
$lista_entidades = array();
foreach ($entidades as $entidad) { //Recorremos el listado
  $atributos = entidad::dameAtributosEntidad($entidad['entidad']); //Pedimos los atributos para cada entidad
  $lista_entidades[] = $atributos; //Añadimos una entrada al array
}

 ?>

 <?php
 $json_articulos = json_encode($lista_entidades);
 echo $json_articulos;
  ?>

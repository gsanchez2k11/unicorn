<?php
namespace unicorn\clases\funciones\odoo;
error_reporting(E_ALL);                                     //Activamos el reporte de errores
ini_set('display_errors', 'on');
require_once(RAIZ . '/vendor/autoload.php');
use Ripoo\OdooClient;
use unicorn\clases\funciones\unicorn_db\Config as config;
//require_once __DIR__ . '/../../config.php.inc';
require_once RAIZ . '/clases/funciones/unicorn_db/Config.php';
/**
 *
 */
class Conectar
{

 // const ODOO_URL        = 'https://futura.studio73.es';
  //const ODOO_DB       = 'futura_147ac041';
  //const ODOO_USER     = 'manuel@futura.es';                                    //Cuenta con permisos de administrador
  //const ODOO_PASS     = 'v6WdakLF';                                            //Contraseña de la cuenta


protected static function conectar(){
  $url = config::dameValorConfig('odoo_url');
  $db = config::dameValorConfig('odoo_db');
$user = config::dameValorConfig('odoo_user');
$pass = config::dameValorConfig('odoo_pass');
  //Creamos el objeto cliente y hacemos la conexión
  $client = new OdooClient($url, $db, $user, $pass);
  return $client;
}

/**
 * Funcion de búsqueda genérica en odoo
 * @param  [type] $campo  [description]
 * @param  [type] $valor  [description]
 * @param  [type] $modelo [description]
 * @return [type]         [description]
 */
public static function busqueda(string $campo, string $valor,string $modelo, $offset = 0){

  $cliente = self::conectar();                                                //Conectamos con el servidor
$criterios[] = array(
$campo,
'=',
$valor
);
$ids = $cliente->search($modelo, $criterios, $offset, 50);         //Buscamos el correo (modelo,criterios,offset,limit)
//    $fields = ['name', 'email', 'customer','mobile','vat'];
$articulos = $cliente->read($modelo, $ids/*, $fields*/);
  return $articulos;
}

public static function like($campo,$valor,$modelo, $offset = 0){

  $cliente = self::conectar();                                                //Conectamos con el servidor
$criterios[] = array(
$campo,
'ilike',
$valor
);
$ids = $cliente->search($modelo, $criterios, intval($offset), 10);         //Buscamos el correo
//    $fields = ['name', 'email', 'customer','mobile','vat'];
$articulos = $cliente->read($modelo, $ids/*, $fields*/);
  return $articulos;
}


public static function listar($modelo,$offset){

  $cliente = self::conectar();                                                //Conectamos con el servidor
$criterios[] = array(
'id',
'>=',
$offset
);
$ids = $cliente->search($modelo, $criterios, 0, 10);         //Buscamos el correo
//    $fields = ['name', 'email', 'customer','mobile','vat'];
$articulos = $cliente->read($modelo, $ids/*, $fields*/);
  return $articulos;
}
public static function listarNuevo($modelo,$offset){

  $cliente = self::conectar();                                                //Conectamos con el servidor
$criterios[] = array(
'id',
'>=',
1
);
$ids = $cliente->search($modelo, $criterios, $offset, 10);         //Buscamos el correo
//    $fields = ['name', 'email', 'customer','mobile','vat'];
$articulos = $cliente->read($modelo, $ids/*, $fields*/);
  return $articulos;
}


public static function busquedaConOperador($row,$modelo){

  $cliente = self::conectar();                                                //Conectamos con el servidor
  foreach ($row as $criterio) {
    $criterios[] = array(
    $criterio['campo'],
    $criterio['operador'],
    $criterio['valor']
    );
  }

  if (count($criterios) == 2) {                                                 //Si tenemos 2 criterios añadimos el operador
  array_splice($criterios, 0, 0, ["&"]);
  }

$ids = $cliente->search($modelo, $criterios, 0, 100);         //Buscamos el correo (modelo, criterio,pagina,offset)
//    $fields = ['name', 'email', 'customer','mobile','vat'];
$articulos = $cliente->read($modelo, $ids/*, $fields*/);
  return $articulos;
}


/**
 * Función genérica para crear entradas en odoo
 * @param  [type] $array  [description]
 * @param  [type] $modelo [description]
 * @return [type]         [description]
 */
public static function crear($array,$modelo){
$cliente = self::conectar();
$id = $cliente->create($modelo, $array);
return $id;
}

public static function actualizar($campo_busqueda,$valor_antiguo,$campo_actualizar,$valor_nuevo,$modelo){
  $cliente = self::conectar();                                                //Conectamos con el servidor
$criterios[] = array(
$campo_busqueda,
'=',
$valor_antiguo
);
$ids = $cliente->search($modelo, $criterios, 0, 10);         //Buscamos el correo
//    $fields = ['name', 'email', 'customer','mobile','vat'];
$articulos = $cliente->write($modelo, $ids, [$campo_actualizar => $valor_nuevo]);
  return $articulos;
}

/**
 * Función genérica para crear entradas en odoo
 * @param  [type] $array  [description]
 * @param  [type] $modelo [description]
 * @return [type]         [description]
 */
public static function eliminar($array,$modelo){
$cliente = self::conectar();
$id = $cliente->unlink($modelo, $array);
return $id;
}

/**
 * Identificamos la linea de negocio a partir del pedido
 * @param  Array $pedido Pedido de venta
 * @return Integer       Id de la linea de negocio (
 * 0 -> tiendaplotter
 * 1 -> tiendasolvente
 * 2 -> tiendasublimacion
 * 3 -> futura
 * 4 -> pc componentes
 * )
 */
/*public static function identificarLineaNegocio($pedido){
$id = $pedido['id'];
$primeros_caracteres =substr($id,0,3);
switch ($primeros_caracteres) {
  case 'TP0':
$id_linea_negocio = 0;
    break;
    case 'TSO':
$id_linea_negocio = 1;
      break;
      case 'TSU':
$id_linea_negocio = 2;
        break;
        case 'FUT':
$id_linea_negocio = 3;
      break;
      default:
$id_linea_negocio = 4;                                                          //Por defecto usamos el de pc componentes

}

return $id_linea_negocio;
}*/

public static function convertirArr($arr) {
  $salida = array();
  foreach ($arr as $key => $value) {
  $salida[$key] = $value;
if (is_numeric($value) && $key != 'barcode') { //No lo aplicamos para el EAN
  $salida[$key] = floatval($value);
}
  }
  return $salida;
}


}

 ?>

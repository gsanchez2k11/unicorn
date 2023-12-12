<?php
namespace unicorn\clases\funciones\odoo;
error_reporting(E_ALL);                                     //Activamos el reporte de errores
ini_set('display_errors', 'on');
require_once 'Conectar.php';
/**
 *
 */
class Articulos extends Conectar
{

  public static function buscarArticulo($campo,$valor){

    $cliente = self::conectar();                                                //Conectamos con el servidor
$criterios[] = array(
  $campo,
  '=',
  $valor
);
//$ids = $cliente->search('product.template', $criterios, 0, 10);         //Buscamos el correo
//$articulos = $cliente->read('product.template', $ids/*, $fields*/);

$ids = $cliente->search('product.product', $criterios, 0, 10);         //Buscamos el correo
$articulos = $cliente->read('product.product', $ids/*, $fields*/);
    return $articulos;
  }

/**
 * Busca un artículo en odoo a partir de una array asociativo de la bd tipo atributo -> valor
 * @param  [array] $atributos Array atributo -> valor
 * @return [type]            [description]
 */
public static function buscarArticuloAtributos($atributos) {
//sustituimos los valores númericos por su campo en odoo
$nuevo = array();
foreach ($atributos as $key => $value) {
  switch ($key) {
    case '2':
    $campo = 'default_code';
      break;
      case '3':
      $campo = 'barcode';
        break;
      /*  case '5':
        $campo = 'cod_inforpor';
          break;
          case '7':
          $campo = 'sku_pcc';
            break;*/
    default:
    $campo = $key;
  }
//$nuevo[$campo] =   strtoupper($value);
$nuevo[$campo] =   $value;
}
//print_r($nuevo);
//Eliminamos los indices que sean digitos, para dejar solo los campos de odoo
$solo_campos = array_filter($nuevo, function ($campo){
  return !is_numeric($campo);
},ARRAY_FILTER_USE_KEY);
    $cliente = self::conectar();                                                //Conectamos con el servidor

foreach ($solo_campos as $key => $value) {
  $criterios[] = array(
    $key,
    '=',
    $value
  );
}

//$chunk = array_chunk($solo_campos, 1, true);                                    //Convertimos cada indice en un array nuevo

  if (count($criterios) == 2) {                                                 //Si tenemos 2 criterios añadimos el operador
  array_splice($criterios, 0, 0, ["|"]);
  }



//$ids = $cliente->search('product.template', $criterios, 0, 10);         //Buscamos el correo
//$articulos = $cliente->read('product.template', $ids);
$ids = $cliente->search('product.product', $criterios, 0, 10);         //Buscamos el correo
$articulos = $cliente->read('product.product', $ids);

if (empty($articulos)) { //Hacemos una nueva búsqueda con todo en mayúsculas
 $criterios =  array_map(function ($value){
    //sustituimos los posibles espacios por guiones
  if (isset($value[2])) {
    $sin_espacios = str_replace(" ","-",$value[2]);
    $mayus = strtoupper($sin_espacios);
    $value[2] = $mayus;
  }
    return $value;
      }, $criterios);
 
  $ids = $cliente->search('product.product', $criterios, 0, 10);         //Buscamos el correo
$articulos = $cliente->read('product.product', $ids);
}



return $articulos;
}



}






 ?>

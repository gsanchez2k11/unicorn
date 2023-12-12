<?php
namespace unicorn\clases\funciones\inforpor;

/**
 *
 */
class Otros
{

public static function ConvertirArrayUniMulti($arr) {
  //Podemos recibir un array con los datos del artículo si es solo uno o un array de articulos
  if (isset($arr['codinf'])) {                                           //Si tenemos un sólo artículo
    $nueva_arr[] = $arr;                                               //Convertimos el array a multidimensional
    unset($arr);
    $arr = $nueva_arr;
  }
  return $arr;
}

/**
 * Convertimos un string con varios campos de inforpor separados por punto y coma a un array
 */
public static function stringToArray($string) {
$arr = explode(';',$string);
$arr = array_filter($arr);
return $arr;

}

/**
 * Cogemos un EAN de inforpor y nos aseguramos de que tenga 13 dígitos
 */
public static function standarizarEan($ean){

  $longitud_ean = strlen($ean);
  while ($longitud_ean < 13) {
    $ean = '0' . $ean;
    $longitud_ean++;
  }
  return $ean;
}

public static function toDouble($string) {
 // $number = floatval(str_replace(',', '.', str_replace('.', '', $string)));
 $number = floatval(str_replace(',', '.', $string));
  return $number;
}
}


 ?>

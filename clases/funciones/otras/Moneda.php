<?php
namespace unicorn\clases\funciones\otras;
error_reporting(E_ALL);                                     //Activamos el reporte de errores
ini_set('display_errors', 'on');
?>
<?php
/**
 *
 */
class Moneda
{
  //Formateamos una cadena númerica tipo "XXX,XX" a double
  public static function cadenaAnumero($cadena)
  {
//$cadena = str_replace('.','',$cadena);
      /*$cad_formateada = str_replace(',','.',$cadena);
      return (double)$cad_formateada;*/

//Comprobamos si estamos recibiendo una cadena en formato moneda (acabada en € o %)
      if (preg_match('/€$/', $cadena)) {
          $cadena = trim(str_replace('€', '', $cadena)); //Limpiamos el campo eliminando el símbolo € del final
      }
      if (preg_match('/\%$/', $cadena)) {
          $cadena = trim(str_replace('%', '', $cadena)); //Limpiamos el campo eliminando el símbolo % del final
      }
      if (preg_match('/\d{1,3}\,(\d{0,2}|\d{4})$/', $cadena)) {
        $number = floatval(str_replace(',', '.', str_replace('.', '', $cadena)));
      } else {
          $number = $cadena;
      }

      return $number;
  }

}


 ?>

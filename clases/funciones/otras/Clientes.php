<?php
namespace unicorn\clases\funciones\otras;
error_reporting(E_ALL);                                     //Activamos el reporte de errores
ini_set('display_errors', 'on');
?>
<?php
/**
 *
 */
class Clientes
{
  //Limpiamos el nif o cif eliminando espacios, puntos, guiones, etc
  public static function formatearNIF($nif)
  {
  $caracteres = array('-','.',' ');
$nif_limpio = str_replace($caracteres,'',$nif);
$nif_mayusculo = strtoupper($nif_limpio);

      return $nif_mayusculo;
  }

}


 ?>

<?php

namespace unicorn\clases\funciones\inforpor;

require_once 'Conectar.php';
 ?>
 <?php
/**
 *
 */
class Codigos extends Conectar
{
  /**
   * Busca un código EAN y devuelve su codigo de inforpor. Prueba todas las variantes posibles con 0 delane
   * @param array $result $result['VerCodeRefResult'] => xxxxx o $result['VerCodeRefResult'] => No Datos
   */
  public static function BuscaEan($row) {
    $ean = $row['ean'];
    //quitamos todos los posibles ceros
      $sin_ceros = ltrim($ean, '0');
      $codinf = '';
      $cliente = self::Crearcliente();
      $result = '';
      do {
    //    $param  = array('Clave' => 'S159RmVer', 'ean' => $sin_ceros);
    $param  = array('Clave' => self::dameValorC('pass_generic'), 'ean' => $sin_ceros);
        $result = $cliente->call('VerCodeRef', array('parameters' => $param), '', '', false, true);
        $sin_ceros = '0' . $sin_ceros;
        if (preg_match('/^\d{4,5}$/i', $result['VerCodeRefResult'])) {          //Comprobamos si estamos recibiendo un código de inforpor
          $codinf = $result;
        }
      } while (strlen($sin_ceros) < 14 && $codinf != '');
      return $codinf;
  }


public static function verCodeRef($parametros){
  $cliente = self::Crearcliente();
  $param  = array('Clave' => self::dameValorC('pass_generic'), $parametros);
  $result = $cliente->call('VerCodeRef', array('parameters' => $param), '', '', false, true);
return $result;
}


  /**
   * Implementación de la función para trabajar con arrays
   * @param [type] $row [description]
   */
    public static function DameCodigosInforpor($row){
      $cliente = self::Crearcliente();
      switch (true) {
        case (isset($row['ean']) && isset($row['mpn'])):                          //Si tenemos los 2 campos
      /*  $param  = array('Clave' => 'S159RmVer', 'ean' => $row['ean']);
        $result = $cliente->call('VerCodeRef', array('parameters' => $param), '', '', false, true);*/
        $result = self::BuscaEan($row);

        //Probamos por ean, si no devuelve datos probamos con el MPN
        if ((isset($result['VerCodeRefResult']) && $result['VerCodeRefResult'] == 'No Datos') || empty($result['VerCodeRefResult'])) {
    /*      $param  = array('Clave' => 'S159RmVer', 'Referencia' => $row['mpn']);
          $result = $cliente->call('VerCodeRef', array('parameters' => $param), '', '', false, true);*/
          $parametros = array(
            'Referencia' => $row['mpn']
          );
                $result =  self::verCodeRef($parametros);
        }
        break;

        case (isset($row['ean'])):                                                //Si solo tenemos el ean
      //  $param  = array('Clave' => 'S159RmVer', 'ean' => $row['ean']);
        //$result = $cliente->call('VerCodeRef', array('parameters' => $param), '', '', false, true);
        $result = self::BuscaEan($row);
        break;
        case (isset($row['mpn'])):                                                //Si solo tenemos el mpn
       // $param  = array('Clave' => 'S159RmVer', 'Referencia' => $row['mpn']);
       // $result = $cliente->call('VerCodeRef', array('parameters' => $param), '', '', false, true);
$parametros = array(
  'Referencia' => $row['mpn']
);
      $result =  self::verCodeRef($parametros);
        break;
      }

$resultado = isset($result['VerCodeRefResult']) ? $result['VerCodeRefResult'] : 0;
   

      return $resultado;
    }


}

  ?>

<?php

/**
 *
 */
namespace unicorn\clases\funciones\unicorn_db;
use \PDO;
class General
{
  protected static function ejecutaConsulta($sql)
  {
  //    $opc        = array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8");
      $opc        = array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION);
      $dsn        = "mysql:host=" . DBHOST . ";dbname=" . DBNOMBRE;
      $usuario    = DBUSUARIO;
      $contrasena = DBPASSWORD;

      $dwes      = new PDO($dsn, $usuario, $contrasena, $opc);
      $resultado = null;
      if (isset($dwes)) {
          $resultado = $dwes->query($sql);
      }

      return $resultado;
  }


  public static function listar(string $tabla){
    //Comprueba si existe y devuelve verdadero o falso
    $sql = "SELECT * FROM ". $tabla;
    $resultado = self::ejecutaConsulta($sql);
    $arr = array();

    if ($resultado) {
        // Añadimos un elemento por cada proveedor
        $row = $resultado->fetch(\PDO::FETCH_ASSOC);

        while ($row != null) {
            $arr[] = $row;
            $row           = $resultado->fetch(\PDO::FETCH_ASSOC);

        }
    }
    return $arr;
}

public static function addRegistro(string $tabla, array $arr) {
    $indices = array_keys($arr);
    $valores = array_values($arr);
    $str_indices = '';
    $str_valores = '';
    foreach ($arr as $key => $value) {
        if ($value != '') {
        $str_indices .= $key . ',';
        $str_valores .= is_numeric($value) ? $value . ',' : '\'' . $value . '\',' ;
} 
    }
    $str_indices = substr($str_indices,0,-1);
    $str_valores = substr($str_valores,0,-1);
    $sql = "INSERT INTO " . $tabla . "(" . $str_indices . ")";
    $sql .= " VALUES(" . $str_valores . " ) ";
 //   echo '<pre>';
//print_r($sql);
 //   echo '</pre>';

    $resultado = self::ejecutaConsulta($sql);
  
    return $resultado;
  }

  public static function actualizaRegistro(string $tabla, array $arr) {
  //Actualiza un registro en la tabla
  $sql = "UPDATE " . $tabla . " SET " . $arr['campo'] . " = '" . $arr['valor'] . "'";
  $sql .= " where id = '" . $arr['id'] . "';";
  
  $resultado = self::ejecutaConsulta($sql);
  return $resultado;

  }
  public static function eliminarRegistro(string $tabla, int $id) {
    $sql = "DELETE FROM " . $tabla . " ";
    $sql .= " where id = '" .  $id . "';";
    $resultado = self::ejecutaConsulta($sql);
  
    return $resultado;
  
    }

    public static function dameRegistros(string $tabla, array $arr){
        //Comprueba si existe y devuelve verdadero o falso
        $sql = "SELECT * FROM ". $tabla;
        $sql .= " where " . $arr['campo'] . " = '" .  $arr['valor'] . "';";
        $resultado = self::ejecutaConsulta($sql);
        $arr = array();
    
        if ($resultado) {
            // Añadimos un elemento por cada proveedor
            $row = $resultado->fetch(\PDO::FETCH_ASSOC);
    
            while ($row != null) {
                $arr[] = $row;
                $row           = $resultado->fetch(\PDO::FETCH_ASSOC);
    
            }
        }
        return $arr;
    }

}

 ?>

<?php
namespace unicorn\clases\funciones\unicorn_db;
require_once 'General.php';

/**
 *
 */
class Config extends General
{
  public static function dameValorConfig($config) {
    $sql = "SELECT valor FROM config ";
    $sql .= " where configuracion = '" . $config . "';";
    $resultado       = self::ejecutaConsulta($sql);
    $row = $resultado->fetch(\PDO::FETCH_ASSOC); //Usando PDO::FETCH_ASSOC devuelve el resultado como array asociativo
    return $row['valor'];
  }


  public static function updateValorConfig($row) {
    $config = $row['config'];
    $valor = $row['valor'];

    $sql = "UPDATE config ";
    $sql .= " SET valor = '" .  $valor . "' ";
    $sql .= " where configuracion = '" . $config . "';";
    $resultado       = self::ejecutaConsulta($sql);
  }

}

 ?>

<?php


namespace unicorn\clases\funciones\unicorn_db;
require_once 'General.php';
/**
 *
 */
class Categorias_Marketplaces extends General
{
  public static function buscarCategoriaMarketplace(string $codigo,string $marketplace = 'pcc'){
  //Borramos las promos con estado = 2
  $sql = "SELECT * FROM categorias_marketplaces ";
  $sql .= " where plataforma = '" . $marketplace . "' and codigo = '" . $codigo ."';";
  $resultado       = self::ejecutaConsulta($sql);

    if ($resultado) {
        $row = $resultado->fetch(\PDO::FETCH_ASSOC); //Usando PDO::FETCH_ASSOC devuelve el resultado como array asociativo
    }
    return $row;
}


public static function addComisionCategoria(array $array, string $marketplace = 'pcc'){
  $comision = $array['comision'];
  $codigo = $array['categoria'];
  $sql = "INSERT INTO categorias_marketplaces (plataforma, codigo, comision)";
  $sql .= " VALUES('". $marketplace . "','" . $codigo . "','" . $comision . "');";
  $resultado = self::ejecutaConsulta($sql);
  if ($resultado) {
    $sql = "SELECT id from categorias_marketplaces ";
    $sql .= " order by id desc limit 1;";
    $resultado = self::ejecutaConsulta($sql);
    $resultado = $resultado->fetch(\PDO::FETCH_ASSOC);
  }
  if ($resultado['id'] > 0) {
    $resultado = 'ok';
  }
  return $resultado;
}

}

 ?>

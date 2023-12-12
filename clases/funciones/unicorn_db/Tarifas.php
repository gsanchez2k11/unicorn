<?php


namespace unicorn\clases\funciones\unicorn_db;
require_once 'General.php';
/**
 *
 */
class Tarifas extends General
{
  /*public static function buscarCategoriaMarketplace(string $codigo,string $marketplace = 'pcc'){
  //Borramos las promos con estado = 2
  $sql = "SELECT * FROM categorias_marketplaces ";
  $sql .= " where plataforma = '" . $marketplace . "' and codigo = '" . $codigo ."';";
  $resultado       = self::ejecutaConsulta($sql);

    if ($resultado) {
        $row = $resultado->fetch(\PDO::FETCH_ASSOC); //Usando PDO::FETCH_ASSOC devuelve el resultado como array asociativo
    }
    return $row;
}*/


public static function addTarifa(int $id_proveedor, string $tarifa){
  $sql = "INSERT INTO tarifas (proveedor, tarifa)";
  $sql .= " VALUES('". $id_proveedor . "','" . $tarifa . "')";
  $sql .= " ON DUPLICATE KEY UPDATE tarifa = ' . $tarifa . ';";

  $resultado = self::ejecutaConsulta($sql);
  return $resultado;
}

}

 ?>

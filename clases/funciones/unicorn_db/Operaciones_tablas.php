<?php
namespace unicorn\clases\funciones\unicorn_db;
require_once 'General.php';

/**
 *
 */
class Operaciones_tablas extends General
{
  public static function truncar($tabla){
  //Borramos las promos con estado = 2
  $sql = "truncate " . $tabla;
  $resultado       = self::ejecutaConsulta($sql);
    return $resultado;
}
/**
 * Pedimos una lista de entradas duplicadas en el historico PCC
 * @return [type] [description]
 */
public static function listarDuplicados($atributo){
  $resultado = '';
  $sql = "SELECT id,entidad,atributo,valor,count(valor) as NumDuplicates";

  $sql .= " FROM `historico_pcc`";

      $sql .= " where atributo = '" . $atributo ."'";

      $sql .=  " group by entidad, valor";

   $sql .=  " having count(valor) > 1;";

  $resultado       = self::ejecutaConsulta($sql);

  $valor = array();
  if ($resultado) {
      $row = $resultado->fetch(\PDO::FETCH_ASSOC); //Usando PDO::FETCH_ASSOC devuelve el resultado como array asociativo
      while ($row != null) {
          $valor[] = $row;
          $row               = $resultado->fetch(\PDO::FETCH_ASSOC);
      }
  }
  /*if (isset($valor[0])) {
    $resultado = $valor[0];
  }*/
  return $valor;
}

public static function eliminarDuplicados($entrada){
  $sql = "DELETE FROM historico_pcc";
  $sql .= " where id !='". $entrada['id'] . "'";
  $sql .= " and entidad = '". $entrada['entidad'] . "' and atributo = '". $entrada['atributo'] . "' and valor='". $entrada['valor'] . "';";
$resultado       = self::ejecutaConsulta($sql);
return $resultado;
}

public static function limpiaDuplicados(){
  //Limpieza de la tabla historico pcc
  $lista = self::listarDuplicados(13); //Pedimos los duplicados
  foreach ($lista as $entrada) {
  $eliminar = self::eliminarDuplicados($entrada);
  }
  $lista = self::listarDuplicados(14); //Pedimos los duplicados
  foreach ($lista as $entrada) {
  $eliminar = self::eliminarDuplicados($entrada);
  }
}

/**
 * Lista aquellos artículos que nunca han tenido stock, o cualquier otro atributo que le pasemos
 * Devuelve las entidades
 */
public static function nuncaEnStock($atributo){
  $resultado = '';
  $sql = "SELECT entidad FROM `historico_pcc`";

      $sql .= " where atributo = '" . $atributo ."'";

      $sql .=  " group by entidad";

   $sql .=  " having count(*) = 1;";

  $resultado       = self::ejecutaConsulta($sql);

  $valor = array();
  if ($resultado) {
      $row = $resultado->fetch(\PDO::FETCH_ASSOC); //Usando PDO::FETCH_ASSOC devuelve el resultado como array asociativo
      while ($row != null) {
          $valor[] = $row;
          $row               = $resultado->fetch(\PDO::FETCH_ASSOC);
      }
  }
  /*if (isset($valor[0])) {
    $resultado = $valor[0];
  }*/
  return $valor;
}


}


 ?>

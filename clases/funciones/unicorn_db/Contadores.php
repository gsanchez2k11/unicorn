<?php
namespace unicorn\clases\funciones\unicorn_db;
require_once 'General.php';

/**
 *
 */
class Contadores extends General
{
  public static function buscarContador($row){
    $id = $row['id'];
  //Borramos las promos con estado = 2
  $sql = "SELECT * FROM contadores ";
  $sql .= " where id = '" . $id ."';";
  $resultado       = self::ejecutaConsulta($sql);

    if ($resultado) {
        $row = $resultado->fetch(\PDO::FETCH_ASSOC); //Usando PDO::FETCH_ASSOC devuelve el resultado como array asociativo
    }
    return $row;
}

public static function valorActualContador($row){
$datos_actuales = self::buscarContador($row);
$valor = $datos_actuales['valor'];
return $valor;
}

public static function actualizaContador($row) {
  $id = $row['id'];
  $valor = $row['valor'];

$sql = "UPDATE contadores ";
$sql .= " set valor = '" . $valor  . "' ";
$sql .= " where id = '" . $id  . "';";
$resultado       = self::ejecutaConsulta($sql);
return $resultado;
}


/**
 * Actualizamos el valor de un contador a partir de un array con la id y el incremento que queremos
 * @param  [type] $row [description]
 * @return [type]      [description]
 */
public static function incrementaContador($row) {
  $id = $row['id'];
  $incremento = $row['incremento'];

//Buscamos el valor actual
$valor_actual = self::valorActualContador($row);
//Generamos el nuevo valor
$nuevo_valor = $valor_actual + $incremento;

$row['valor'] = $nuevo_valor;
$actualizar = self::actualizaContador($row);

return $actualizar;
}


}
 ?>

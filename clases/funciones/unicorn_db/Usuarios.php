<?php

/**
 *
 *
 */
namespace unicorn\clases\funciones\unicorn_db;
//use clases\funciones\unicorn_db\General;
require_once 'General.php';
class Usuarios extends General
{
  public static function compruebaUsuario($usuario)
{
    //Comprueba sólo si existe un usuario
    $sql = "SELECT nombre_usuario, avatar, nombre_completo, email, bio, ultima_conexion, gu.nombre as grupo_usuario, gu.nivel_acceso  as nivel_acceso, id_odoo FROM usuarios u join grupos_usuarios gu on (u.grupo = gu.id)";
    $sql .= " WHERE u.nombre_usuario='" . $usuario . "'";
    $resultado = self::ejecutaConsulta($sql);
    $usuario   = $resultado->fetch();

    return $usuario;

}


//Comprueba los datos que le pasamos al hacer login con los de la base de datps
    public static function consultaUsuarios($usuario, $password)
    {
        //Comprueba si existe y devuelve verdadero o falso
        $sql = "SELECT id_usuario FROM usuarios";
        $sql .= " WHERE nombre_usuario='" . $usuario . "' and password ='" . md5($password) . "' and activo = 1 ";
        $resultado = self::ejecutaConsulta($sql);

        $admins = $resultado->fetch();

        if ($admins) {
            return true;
        } else {
            return false;
        }

    }

  public static function perfilUsuarios($usuario) {

  }

  public static function listarUsuarios(){
            //Comprueba si existe y devuelve verdadero o falso
            $sql = "SELECT * FROM usuarios";
            $sql .= " WHERE activo = 1 ";
            $resultado = self::ejecutaConsulta($sql);
    
            $usuarios = array();

            if ($resultado) {
                // Añadimos un elemento por cada proveedor
                $row = $resultado->fetch(\PDO::FETCH_ASSOC);
   
                while ($row != null) {
                    $usuarios[] = $row;
                    $row           = $resultado->fetch(\PDO::FETCH_ASSOC);
   
                }
            }
            return $usuarios;
  }


}

 ?>

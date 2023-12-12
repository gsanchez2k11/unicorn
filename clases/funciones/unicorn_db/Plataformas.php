<?php

namespace unicorn\clases\funciones\unicorn_db;
//use clases\funciones\unicorn_db\General;
require_once 'General.php';

/**
 *
 */
class Plataformas extends General
{

  public static function listaPlataformasBd()
     {

       //    $sql = "SELECT id, codigo, nombre FROM proveedores ";
           $sql = "SELECT * FROM proveedores "; //Modificamos para obtener todos los datos de cada proveedor
 //Recibimos un variable e intentamos identificarla
 if ($datoproveedor !== false && !empty($datoproveedor)) {
 $sql .= " WHERE ";                              // añadimos la clausula where a la sentencia
 if (is_numeric($datoproveedor)) {               //Usamos is_numeric() en lugar de is_int() porque por defecto trata las varaibles recibidas del formulario como strings
      $sql .= " id='" . $datoproveedor . "' ";
 } else {
 $sql .= " nombre='" . $datoproveedor . "' ";
 }
         }

         $sql .= " order by nombre";
         $resultado   = self::ejecutaConsulta($sql);
         $proveedores = array();

         if ($resultado) {
             // Añadimos un elemento por cada proveedor
             $row = $resultado->fetch(\PDO::FETCH_ASSOC);

             while ($row != null) {
                 $proveedores[] = new \Proveedor($row);
                 $row           = $resultado->fetch(\PDO::FETCH_ASSOC);

             }
         }
         return $proveedores;
     }
}

 ?>

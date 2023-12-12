<?php
namespace unicorn\clases\funciones\magento;
include 'Conectar.php';
/**
 *
 */
class Conjuntoatributos extends Conectar
{

  //-------------------------------------------------------//
      // Obtenemos el listado de conjuntos de atributos                   //
      //------------------------------------------------------//
      public static function tipoarticulo($id_tienda, $id = false)
      {
          $token = self::getToken($id_tienda);
          if ($id === false) {
              $ch = curl_init($token['url'] . 'index.php/rest/V1/eav/attribute-sets/list?searchCriteria=[]');
          } else {
              $ch = curl_init($token['url'] . 'index.php/rest/V1/eav/attribute-sets/' . $id . '?searchCriteria=[]');
          }
          curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
          curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "Accept: application/json", "Authorization: Bearer " . $token['token']));

          $result = curl_exec($ch);
          $result = json_decode($result);
          if (isset($result->items)) {
              $resultado = $result->items;



      usort($resultado,function ($a, $b)
        {
                return strcmp($a->attribute_set_name, $b->attribute_set_name);
            });

          } else {
              $resultado = $result;
          }

          return $resultado;
      }
/*
      * Función que recupera todos los valores para un atributo a través de su id
      * Por defecto hemos puesto la id del atributo fabricante
      */

         public static function infoatributo($id_tienda,$id = 81)
         {
            $token = self::getToken($id_tienda);          
          //  echo '<pre>';
          //  print_r($id_tienda);
          //  echo '</pre>';

      //      $ch    = curl_init($token['url'] . 'index.php/rest/V1/products/attributes/' . $id . '?searchCriteria');
      $ch    = curl_init($token['url'] . 'index.php/rest/V1/products/attributes/' . $id . '/options');
             curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
             curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
             curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "Authorization: Bearer " . $token['token']));

             $result = curl_exec($ch);
       //     echo '<pre>';
         //   print_r($result);
          // echo '</pre>';
             $result = json_decode($result);
             if (isset($result->options)) {
                 $resultado = $result->options;
             } else {
                 $resultado = $result;
             }

             return $resultado;
         }


}


 ?>

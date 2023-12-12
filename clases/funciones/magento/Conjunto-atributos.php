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
      public static function tipoarticulo($id = false)
      {
          $token = self::getToken();
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

      function cmp($a, $b)
  {
          return strcmp($a->attribute_set_name, $b->attribute_set_name);
      }

      usort($resultado, "cmp");

          } else {
              $resultado = $result;
          }

          return $resultado;
      }


}


 ?>

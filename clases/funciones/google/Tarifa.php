<?php
namespace unicorn\clases\funciones\google;
include 'Conectar.php';
require_once RAIZ . '/clases/funciones/unicorn_db/Proveedores.php';
use unicorn\clases\funciones\unicorn_db\Proveedores as proveedores;
require_once RAIZ . '/clases/funciones/otras/Moneda.php';
use unicorn\clases\funciones\otras\Moneda as moneda;
require_once RAIZ . '/clases/objetos/Articulos/Compra_articulo.php';
use unicorn\clases\objetos\Articulos\Compra_articulo as compraarticulo;
require_once RAIZ . '/clases/objetos/Articulos/Articulo_unicornio.php';
use unicorn\clases\objetos\Articulos\Articulo_unicornio as Articulo_unicornio;
/**
 *
 */
class Tarifa extends Conectar
{
  /**
   * Devuelve todos los valores de un rango seleccionado en una hoja
   * @param  [string] $spreadsheetId    [Identificador de la hoja en la que vamos a escribir]
   * @param  [string] $range         [Rango que queremos ver]
   * @return [Google_Service_Sheets_ValueRange]                [Objeto con una propiedad values, que es un array con los datos]
   */
   static function getValues($spreadsheetId, $range)
      {
         $client = self::getClient(); //Obtenemos el cliente de google
  $service = new \Google_Service_Sheets($client);
          // [START sheets_get_values]
          $result = $service->spreadsheets_values->get($spreadsheetId, $range);
          $numRows = $result->getValues() != null ? count($result->getValues()) : 0;
          printf("%d rows retrieved.", $numRows);
          // [END sheets_get_values]
          return $result;
      }

      public static function dameTarifaFiltrada($spreadsheetId, $range){
        $values = self::getValues($spreadsheetId, $range);
        $valores_tarifa = array_map(function($articulo){
          $ean = !empty($articulo[1]) ? $articulo[1] : '';
          $precio = isset($articulo[12]) && !empty($articulo[12])  && preg_match('/\d+,\d{2}€$/', $articulo[12]) ? $articulo[12] : '';                //Nos quedamos con los campos con formato precio
          $stock = isset($articulo[25]) && (!empty($articulo[25]) || strlen($articulo[25]) > 0 ) && is_numeric($articulo[25]) ? $articulo[25] : ''; //Nos quedamos con los campos con formato númerico
          if (isset($articulo[0]) && (!empty($articulo[0]) || strlen($articulo[0]) > 3) && ($precio != '' || $stock != '')) {
            $valor = array(
              'mpn' => trim($articulo[0]),
              'ean'=> $ean,                                                     //Cogemos la columna B a falta de definir esto
              'precio_tarifa' => str_replace('.','',$precio),                      //Quitamos los puntos como separador de miles
              'stock_tarifa' => $stock
            );
            return $valor;
          } else {
            return false;
          }
        },$values['values']);
$valores_filtrados = array_filter($valores_tarifa);
return $valores_filtrados;
      }


      static function miTarifaCompleta($rangos = self::RANGOS) {
      $client = self::getClient();                        //Obtenemos el cliente de google
      $service = new \Google_Service_Sheets($client);      //Definimos el servicio a
      //$tarifa = array();                                  //Creamos un array para guardar la salida

      foreach ($rangos as $key => $rango) {
      $response = $service->spreadsheets_values->get(self::SPREADSHEETID, $rango);        //Obtenemos los datos
     $valores[] = self::limpiarValores($response->getValues());                            //Los limpiamos
  //    foreach ($valores as $valor) {                                                      //Creamos un array de datos limpios por cada hoja
  //    $tarifa[] = $valor;
  //    }
      //error_log(date('d/m/o H:i') . ' : ' . print_r($tarifa,true). '\n', 3, RAIZ . '/var/log/google/mitarifacompleta.txt'); //Añadir una entrada en el log
    //  $archivo = 'dev_' . $key . '.txt';
      //error_log(date('d/m/o H:i') . ' : ' . print_r($tarifa,true). '\n', 3, RAIZ . '/var/log/google/' . $archivo); //Añadir una entrada en el log
      }
      foreach ($valores as $hoja) {
        foreach ($hoja as $item) {
          $salida[] = $item;
        }

      }
      return $salida;

      }


static function limpiarValores(array $valores_sucios) {

  $valores = array_filter($valores_sucios, function ($item) {
  $proveedores = proveedores::obtieneProveedoresBd(); //Obtenemos el listado de proveedores
  /* Primero filtramos las filas que tienen campo referencia, campo proveedor y al menos uno entre precio de venta, de compra o stock */
      if (!empty($item[0]) && !empty($item[19]) && (!empty($item[12]) || !empty($item[23]) || !empty($item[24]))) {
  /* Si el campo referencia empieza por ref lo quitamos*/
        if (preg_match("/^(REF|WEB)/i",$item[0])) {
           return false;
          }
  /*Si el campo proveedor no coincide con uno de los la lista lo quitamos*/
         if (in_array($item[19],$proveedores)) {
              return false;
          }
          if (!empty($item[12])) {
              if (preg_match("/^\d*\.?\d+,?\d{0,2}(\s€)?/i",$item[12])) {
                 } else {
                   return false;
                 }
          }
          if (!empty($item[23])) {
              if (preg_match("/^\d*\.?\d+,?\d{0,2}(\s€)?/i",$item[23])) {
                 } else {
                   return false;
                 }
          }
        /*  if (!empty($item[24])) {
              if (preg_match("/^\d*\.?\d+,?\d{0,2}\s?€/i",$item[24])) {
                 } else {
                   return false;
                 }
          }*/

          return true;
      }
      return false;
  });
foreach ($valores as $valor) {
  $referencia = trim($valor[0]);
  $valor_limpio['referencia'] = trim($valor[0]);
$valor_limpio['nombre']     = $valor[19]; //Cogemos el nombre del proveedor
$valor_limpio['medio']     = 'tarifa 2019'; //Cogemos el nombre del proveedor
/*Valores opcionales*/
if (!empty($valor[11]) && preg_match('/€$/', $valor[11])) {
    // Celda que debe contener el PVP
    $number            = moneda::cadenaAnumero($valor[11]);
    $valor_limpio['pvp'] = $number; //PVP
}
if (!empty($valor[12]) && preg_match('/€$/', $valor[12])) {
    // Celda que debe contener el precio de venta
    $number            = moneda::cadenaAnumero($valor[12]);
    $valor_limpio['precio_venta'] = $number; //Precio de venta
}
if (!empty($valor[14]) && (preg_match('/€$/', $valor[14]) || is_float($valor[14]))) {
    // Celda que debe contener el nuevo precio de venta sugerido
    $number            = moneda::cadenaAnumero($valor[14]);
    $valor_limpio['nuevo_precio'] = $number; //Precio sugerido para cambiar
}
    if (!empty($valor[23]) && preg_match('/€$/', $valor[23])) {
    // Celda que debe contener el precio de compra
    $number               = moneda::cadenaAnumero($valor[23]);
    $valor_limpio['precio'] = $number; //Precio de compra
}
    if (isset($valor[25]) && preg_match('/^\d+$/i', $valor[25])) {
    // Celda que puede contener el stock
    $valor_limpio['stock'] = $valor[25]; //stock del artículo
}

/* Alias */
//Comprobamos si hubiera alias y que sea distinto de la referencia
if (!empty($valor[1]) && $valor[1] != $valor[0]) {
    // Celda que puede contener un alias
    $alias[] = $valor[1]; //Posible alias
}
    if (!empty($valor[2]) && $valor[2] != $valor[0]) {
    // Celda que puede contener un alias
    $alias[] = $valor[2]; //Posible alias
}
//Directamente escribimos los alias nuevos desde aquí
//if (isset($alias) && !empty($alias)) {
//foreach ($alias as $alia) {
//$add_alias = Bdfav3::addAtributoTexto($valor_limpio['referencia'],$alia,10);
//}
//}
unset($alias);
//echo "<pre>";
//print_r($valor_limpio);
//echo "</pre>";
//$objetos_valor = new compraarticulo($valor_limpio);
//$valores_limpios[] = $objetos_valor;
if (!isset($valores_limpios[$referencia])) {                                    //Si no tenemos el articulo unicornio creado lo hacemos
  $precio_venta = isset($valor_limpio['precio_venta']) ? $valor_limpio['precio_venta']:0;
  $valores_limpios[$referencia] = new Articulo_unicornio(array(
    'referencia' => $referencia,
    'entidad' => 'entidad',
    'precio_venta' => $precio_venta
  ));
} else {                                                                        //Si ya tenemos el articulo no lo creamos, pero comprobamos si podemos añadir el precio de venta en caso de no tenerlo
  if ($valores_limpios[$referencia]->getPrecioVenta() === 0 && isset($valor_limpio['precio_venta'])) {
    $valores_limpios[$referencia]->setPrecioVenta($valor_limpio['precio_venta']);
  }
}

$valores_limpios[$referencia]->AddCompra($valor_limpio);                         //Añadimos la compra si o si

    unset($valor_limpio);
//compraarticulo
}



return $valores_limpios;

}

}


 ?>

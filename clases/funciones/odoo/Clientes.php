<?php
namespace unicorn\clases\funciones\odoo;
error_reporting(E_ALL);                                     //Activamos el reporte de errores
ini_set('display_errors', 'on');
require_once 'Conectar.php';

/**
 *
 */
class Clientes extends Conectar
{
public static function quitar_tildes($cadena) {
$no_permitidas= array ("á","é","í","ó","ú","Á","É","Í","Ó","Ú","ñ","À","Ã","Ì","Ò","Ù","Ã™","Ã ","Ã¨","Ã¬","Ã²","Ã¹","ç","Ç","Ã¢","ê","Ã®","Ã´","Ã»","Ã‚","ÃŠ","ÃŽ","Ã”","Ã›","ü","Ã¶","Ã–","Ã¯","Ã¤","«","Ò","Ã","Ã„","Ã‹");
$permitidas= array ("a","e","i","o","u","A","E","I","O","U","n","N","A","E","I","O","U","a","e","i","o","u","c","C","a","e","i","o","u","A","E","I","O","U","u","o","O","i","a","e","U","I","A","E");
$texto = str_replace($no_permitidas, $permitidas ,$cadena);
return $texto;
}

public static function formatear_tlfo($telefono){
  if (preg_match('/^\d{9}$/',$telefono)) {
        $resultado =  '+34 ' . substr($telefono,0,3) . ' ' . substr($telefono,3,2) . ' ' . substr($telefono,5,2) . ' ' . substr($telefono,7,2);
  } else {
    $resultado = $telefono;
  }
  return $resultado;
}

/**
 * Buscamos la provincia a partir del nombre
 * @param  [type] $provincia [description]
 * @return [type]            [description]
 */
public static function dameStateId($provincia){
/*Primero buscamos directamente en el modelo en odoo*/
$cliente = self::conectar();                                                //Conectamos con el servidor
//Ajustamos aquellas provincias con nombre especial
switch ($provincia) {
  case 'Alicante':
  $nombre_provincia = 'Alacant (Alicante)';
    break;
    case 'A Coruña':
    $nombre_provincia = 'A Coruña (La Coruña)';
      break;
    case 'Vizcaya':
    $nombre_provincia = 'Bizkaia (Vizcaya)';
      break;

  default:
$nombre_provincia = $provincia;
    break;
}
$ids = $cliente->search('res.country.state', [['country_id', '=', 68],['name', '=', $nombre_provincia]], 0, 100);
$fields = ['id'];
$id_provincia = $cliente->read('res.country.state',$ids,$fields);
$provincia = isset($id_provincia[0]) ? $id_provincia[0]['id'] : '';

  return $provincia;
}

  public static function buscarCLiente($campo,$valor){

  /*  if ($campo == 'mobile' && preg_match('/^\d{9}$/',$valor)) {
          $valor =  '+34 ' . substr($valor,0,3) . ' ' . substr($valor,3,2) . ' ' . substr($valor,5,2) . ' ' . substr($valor,7,2);
    }*/
    if ($campo == 'mobile' || $campo == 'phone') {
      $valor = self::formatear_tlfo($valor);
    }
  if ($campo == 'name') {
    $valor = self::quitar_tildes($valor);
  }


    $cliente = self::conectar();                                                //Conectamos con el servidor
$criterios[] = array(
  $campo,
  'ilike',
  $valor
);


$ids = $cliente->search('res.partner', $criterios, 0, 10);         //Buscamos el correo
//    $fields = ['name', 'email', 'customer','mobile','vat'];
$customers = $cliente->read('res.partner', $ids/*, $fields*/);
    return $customers;
  }

public static function crearCliente($cliente_nuevo) {
    $cliente = self::conectar();
$name =   strtoupper(self::quitar_tildes($cliente_nuevo['name']));  
$email = $cliente_nuevo['email'];
$street = $cliente_nuevo['street'];
$vat = $cliente_nuevo['vat'];
$mobile = self::formatear_tlfo($cliente_nuevo['mobile']);
$zip = $cliente_nuevo['zip'];
$city = $cliente_nuevo['city'];
$state_id = self::dameStateId($cliente_nuevo['state']);
$tz = 'Europe/Madrid'; //TimeZone (fijo)
$country_id = 68; //Pais (fijo)
$property_product_pricelist = 1; //Tarifa (fijo)
$property_account_position_id = 24; //Regimen nacional (fijo)
$property_payment_term_id = 1; //Pago a la vista (fijo)
$customer_payment_mode_id = 16; //Forma de pago contado (fijo)
$parent_id = $cliente_nuevo['parent_id'];
//Ayuda campos one2many
//https://www.odoo.com/documentation/12.0/reference/orm.html#odoo.fields.One2many
$datos_cliente = array(
  'name' => $name,
  'street' => $street,
 // 'vat' => $vat,                          //Ponemos el NIF en mayúsculas
 'email' => $email,
  'mobile' => $mobile,
  'zip' => $zip,
  'city' => $city,
  'state_id' => $state_id,                                                           //Fijamos Murcia para depuración
  'tz'   => 'Europe/Madrid',                                                   //Zona horaria
  'country_id' => 68,                                                        //Pais España
 // 'x_studio_linea_de_negocio' => $x_studio_linea_de_negocio,
  'property_product_pricelist' => 1,                                          //Tarifa
  'property_account_position_id' => 24,                                      //Regimen nacional
  'property_payment_term_id'     => 1,                                       //Pago a la vista
  'customer_payment_mode_id' => 16,      
);

//print_r($parent_id);
if ($parent_id != 'false') { //Campos únicos para dirección de envío
$datos_cliente['parent_id'] = intval($parent_id);
$datos_cliente['type'] = 'delivery';
} else { //Campos únicos para dirección de factura
  $datos_cliente['vat'] = $vat;
}
//echo '<pre>';
//print_r($datos_cliente);
//echo '</pre>';
$id = $cliente->create('res.partner', $datos_cliente);




/*$email = $cliente_nuevo['fra']['email'] !== '-' ? $cliente_nuevo['fra']['email'] : ''; //Creamos el campo email, si no lo tenemos hemos pasado un guión solamente


  $datos_cliente = [
    'name' => strtoupper(self::quitar_tildes($cliente_nuevo['fra']['name'])),
    'street' => $cliente_nuevo['fra']['street'],
    'vat' => strtoupper($cliente_nuevo['fra']['vat']),                          //Ponemos el NIF en mayúsculas
   'email' => $email,
    'mobile' => self::formatear_tlfo($cliente_nuevo['fra']['mobile']),
    'zip' => $cliente_nuevo['fra']['zip'],
    'city' => $cliente_nuevo['fra']['city'],
    'state_id' => self::dameStateId($cliente_nuevo['fra']['state']),                                                           //Fijamos Murcia para depuración
    'tz'   => 'Europe/Madrid',                                                   //Zona horaria
    'country_id' => 68,                                                        //Pais España
   // 'x_studio_linea_de_negocio' => $x_studio_linea_de_negocio,
    'property_product_pricelist' => 1,                                          //Tarifa
    'property_account_position_id' => 24,                                      //Regimen nacional
    'property_payment_term_id'     => 1,                                       //Pago a la vista
    'customer_payment_mode_id' => 16,                                          //Forma de pago (11 PCC, 16 Contado)
  //  'category_id' => $etiqueta
  ];*/

//print_r($datos_cliente);

 // $id = $cliente->create('res.partner', $datos_cliente); //Lo importante

  //Eliminamos la parte que genera el segundo contacto hasta que depuremos
//var_dump($id);
/*if (!empty($cliente_nuevo['dir']['name'])) {
  $datos_cliente_envio = [
    'parent_id' => $id ,                                                        //la id del contacto padre
    'type' => 'delivery',                                                       //Especificamos que es una direccion de envio
    'name' => $cliente_nuevo['dir']['name'],
    'street' => $cliente_nuevo['dir']['street'],
    'mobile' => self::formatear_tlfo($cliente_nuevo['dir']['mobile']),
    'zip' => $cliente_nuevo['dir']['zip'],
    'city' => $cliente_nuevo['dir']['city'],
    'state' => $cliente_nuevo['dir']['state']
  ];
  $id_envio = $cliente->create('res.partner', $datos_cliente_envio);
}*/

  return $id;
}



}


 ?>

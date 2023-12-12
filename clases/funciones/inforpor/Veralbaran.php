<?php

namespace unicorn\clases\funciones\inforpor;
require_once 'Conectar.php';
require_once 'Codigos.php';
require_once 'Pedido.php';
require_once 'Otros.php';
require_once 'Tarifa.php';
/*require_once RAIZ . '/clases/funciones/unicorn_db/Entidad.php';
require_once RAIZ . '/clases/objetos/Stock/Custodia.php';
require_once RAIZ . '/clases/funciones/otras/Moneda.php';
use unicorn\clases\funciones\inforpor\Codigos as codigos;
use unicorn\clases\funciones\inforpor\Pedido as pedido;
use unicorn\clases\funciones\inforpor\Otros as otros;
use unicorn\clases\objetos\Articulos\Custodia as custodia;
use unicorn\clases\funciones\unicorn_db\Entidad as entidad;
use unicorn\clases\funciones\unicorn_db\Config as config;
use unicorn\clases\funciones\otras\Moneda as moneda;
use unicorn\clases\funciones\inforpor\Tarifa as tarifa_inforpor;*/
 ?>
 <?php
/**
 *
 */
class VerAlbaran extends Conectar
{

/**
 * Para consultar los datos del albarán y obtener una dirección donde poder
*descargarla en pdf a partir de un número de pedido del cliente o de Inforpor, o de
*unas fechas
 * 
 * @param int $Cod_inforpor Código de inforpor del artículo.
 * @return Array $codigos_promo Array vacío o con los distintos códigos promocionales
 * 
 * Nuevo Servicio Web  ConsultaProd
	
 * 
 * 
 */

  public static function verAlbaran($NumPedInf){
    $cliente = self::Crearcliente();
    $param  = array('CIF' => self::CIF, 'User' => self::USER, 'Clave' => self::CLAVE, 'NumPedInf' => $NumPedInf);
    $result = $cliente->call('VerAlbaran', array('parameters' => $param), '', '', false, true);
   $resultado = $result['VerAlbaranResult']['Albaran']['CodErr'] == 0 ? $result['VerAlbaranResult']['Albaran'] : $result; //Devolvemos los datos del artículo o un array vacío en su defecto
    
    return $resultado;
  }



}

  ?>

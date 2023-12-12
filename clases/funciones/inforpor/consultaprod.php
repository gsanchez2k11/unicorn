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
class ConsultaProd extends Conectar
{

/**
 * Método ListaCodigosProm para conocer los códigos promocionales aplicables a un artículo
 * @param int $Cod_inforpor Código de inforpor del artículo.
 * @return Array $codigos_promo Array vacío o con los distintos códigos promocionales
 */
  public static function ListaCodigosProm($Cod_inforpor){
    $cliente = self::Crearcliente();
    $param  = array('CIF' => self::CIF, 'User' => self::USER, 'Clave' => self::CLAVE, 'Cod' => $Cod_inforpor);
    $result = $cliente->call('ListaCodigosProm', array('parameters' => $param), '', '', false, true);

  $codigos =  $result['ListaCodigosPromResult'] == 'Error Codigo' ? []: otros::stringToArray($result['ListaCodigosPromResult']); //Si da error el código devolvemos un array vacío

    return $codigos;
  }



}

  ?>

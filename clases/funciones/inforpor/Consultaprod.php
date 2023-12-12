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
 * Método consultaProd que nos han generado para nosotros
 * 
 * @param int $Cod_inforpor Código de inforpor del artículo.
 * @return Array $codigos_promo Array vacío o con los distintos códigos promocionales
 * 
 * Nuevo Servicio Web  ConsultaProd
	

Entrada:
CIF
User
Clave
Cod

Ejemplo:
<Envelope xmlns="http://schemas.xmlsoap.org/soap/envelope/">
    <Body>
        <ConsultaProd xmlns="http://www.inforpor.com/ServiciosWeb/">
            <CIF>B30507743</CIF>
            <User>AL0030</User>
            <Clave></Clave>
            <Cod>38825</Cod>
        </ConsultaProd >
    </Body>
</Envelope>

Salida:
Coderr – 0 si no hay error o ”Producto vacío” 
Cod – Mismo que entrada
Referencia – Referencia de fabricante
Stock – Stock disponible en ese momento
Precio – Precio de tarifa, sin promos
Lpi – Canon digital del producto
EAN – Código ean del producto
FechaEntrada – Próxima fecha de entrada de mercancía
CantReserva – Cantidad que dispone en reserva
Promocion – Todas las promociones de ese producto
	CodigoProm – Código de promoción
PrecioProm – Precio de promoción
FechaIni – Fecha de inicio de la promoción
FechaFin – Fecha de fin de la promoción
Udmax – Unidades disponibles de esa promoción
Udmin – Unidades mínimas que puede comprar
Custodia – Todas las custodias que tenga
	Npedido – Numero de pedido donde fue puesta en custodia
	cant – Cantidad en custodia de ese pedido
	idcustodia – Id de custodia para poner en pedido
 * 
 * 
 */

  public static function consultaProd($Cod_inforpor){
    $cliente = self::Crearcliente();
    $param  = array('CIF' => self::CIF, 'User' => self::USER, 'Clave' => self::CLAVE, 'Cod' => $Cod_inforpor);
    $result = $cliente->call('ConsultaProd', array('parameters' => $param), '', '', false, true);
   $resultado = $result['ConsultaProdResult']['CodErr'] == 0 ? $result['ConsultaProdResult'] : $result; //Devolvemos los datos del artículo o un array vacío en su defecto
    
    return $resultado;
  }



}

  ?>

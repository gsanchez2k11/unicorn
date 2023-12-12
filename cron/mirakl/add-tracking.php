<?php
namespace unicorn\cron\mirakl;
error_reporting(E_ALL);                                     //Activamos el reporte de errores
ini_set('display_errors', 'on');
require_once __DIR__ . '/../../config.php.inc';
require_once(RAIZ . '/vendor/autoload.php');
$tiempo_inicial = microtime(true); //true es para que sea calculado en segundos
use unicorn\clases\funciones\mirakl\Pedidos as pedidos;
use unicorn\clases\funciones\mirakl\Agencias as agencias;
use unicorn\clases\funciones\inforpor\Pedido as pedido;
require_once RAIZ . '/clases/funciones/mirakl/Pedidos.php';
require_once RAIZ . '/clases/funciones/mirakl/Agencias.php';
require_once RAIZ . '/clases/funciones/inforpor/Pedido.php';


//Pedimos los últimos pedidos
$pedidos = pedidos::dameUltimosPedidos();
//Obtenemos el listado de agencias
$agencias = agencias::listarAgencias();
//Creamos subarrays según el estado de cada pedido
foreach ($pedidos as $key => $pedido) {
    $pds[$pedido->getEstado()][] = $pedido;
    }

    $pedidos_shipping = $pds['SHIPPING'];
    foreach ($pedidos_shipping as $pedido) {
        //Formateamos la referencia para conseguir la que estamos utilizando en Inforpor
        $referencia_explode =   explode('-',$pedido->getId());
        $referencia = 'PC' . $referencia_explode[0];
        $ped['NumPedCli'] = $referencia;
        $estado_pedido = pedido::EstadoPedido($ped,'mirakl');
        //Mientras esté pendiente no hacemos nada, cogemos los que tienen estado procesando o servido
        if ($estado_pedido['EstadoPedidoResult']['CodErr'] == '0' && isset($estado_pedido['EstadoPedidoResult']['estado']) && ($estado_pedido['EstadoPedidoResult']['estado'] == 'PROCESANDO' || $estado_pedido['EstadoPedidoResult']['estado'] == 'SERVIDO')) {
        $add_tracking = pedidos::preparaTracking($pedido, $estado_pedido);

        }
      }


$tiempo_final = microtime(true);
$tiempo = $tiempo_final - $tiempo_inicial; //este resultado estará en segundos
echo "El tiempo de ejecución del archivo ha sido de " . $tiempo . " segundos";

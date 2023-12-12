<?php
namespace unicorn\clases\objetos\Pedidos;
require_once('Linea_Pedido_mirakl.php');
require_once('Pedido_venta.php');
use unicorn\clases\objetos\Pedidos\Linea_Pedido_mirakl as linea_pedido_mirakl;
use unicorn\clases\objetos\Pedidos\Pedido_venta as pedido_venta;
use unicorn\clases\objetos\Direccion\Direccion as direccion;
use JsonSerializable;
/**
 *
 */
class Pedido_mirakl extends Pedido_venta implements JsonSerializable
{

  private $fecha_aceptado;
  private $fecha_debito;
  private $limite_envio;
  private $comision;


public function __construct($row) {
    $this->fecha_creado = $row['fecha_creado'];
    $this->fecha_aceptado = $row['fecha_aceptado'];
    $this->fecha_debito = $row['fecha_debito'];
    $this->nif = $row['nif'];
    $this->nombre_apellidos = $row['nombre_apellidos'];
    $this->total_pedido = $row['total_pedido'];
    $this->limite_envio = $row['limite_envio'];
    $this->comision = $row['comision'];
    $this->id = $row['id'];
    $this->estado = $row['estado'];
    $this->envio = $row['envio'];
    $this->tienda = 'mirakl';
    $this->DirEnvioIgualFactura = self::getDirEnvioIgualFactura();

//    $this->lineas_pedido = $row['lineas_pedido'];
foreach ($row['lineas_pedido'] as $lin_ped) {

/*echo "<pre>";
 print_r($lin_ped->getData()['taxes']);
echo "</pre>";*/
$impuestos = !empty($lin_ped->getData()['taxes']->getItems()) ? $lin_ped->getData()['taxes']->getItems()[0]->getData()['amount'] : 0;
$arr_lin_ped = array(
  'cancelaciones' => $lin_ped->getData()['cancelations']->getItems(),
  'importe' => $lin_ped->getData()['total_price'],
  'promociones' => $lin_ped->getData()['promotions']->getItems(),
  'cantidad' => $lin_ped->getData()['quantity'],
  'abonos' => $lin_ped->getData()['refunds']->getItems(),
  'portes' => $lin_ped->getData()['shipping_price'],
  'impuestos' => $impuestos,        //Damos por hecho que cada linea tiene sólo un impuesto, ya que el canon LPI no se desglosa
  'total_linea' => $lin_ped->getData()['total_price'],
  'historico' => $lin_ped->getData()['history']->getData(),
  'cod_categoria' => $lin_ped->getData()['offer']->getData()['product']->getData()['category']->getData()['code'],
  'etiqueta_categoria' => $lin_ped->getData()['offer']->getData()['product']->getData()['category']->getData()['label'],
  'sku' => $lin_ped->getData()['offer']->getData()['product']->getData()['sku'],
  'nombre' => $lin_ped->getData()['offer']->getData()['product']->getData()['title'],
  'id' => $lin_ped->getData()['offer']->getData()['id'],
  'sku_oferta' => $lin_ped->getData()['offer']->getData()['sku'],
  'estado' => $lin_ped->getData()['status']->getData()['state'],
  'comision' => $lin_ped->getData()['commission']->getData()['total'],
);

$obj_lin_ped[] = new linea_pedido_mirakl($arr_lin_ped);
}
//echo "<pre>";
//print_r($row);
//echo "</pre>";
$this->lineas_pedido = $obj_lin_ped;
//  $this->lineas_pedido = $row['lineas_pedido'];
//
//
//
//Preparamos los campos para la direccion de facturacion
if ($row['direccion_factura'] !== '-') {
  $obj_dir_fra = array(
    'nombre_completo' => $row['direccion_factura']['firstname'] . ' ' . $row['direccion_factura']['lastname'],
    'nif' => $this->nif,
    'empresa' => '',
    'direccion' => $row['direccion_factura']['street_1'],
    'ciudad' => $row['direccion_factura']['city'],
    'codigo_postal' => $row['direccion_factura']['zip_code'],
    'telefono' => $row['direccion_factura']['phone'],
    'provincia' => $row['direccion_factura']['state']
  );
  $this->direccion_factura = new direccion($obj_dir_fra);

  //Preparamos ahora la direccion de envío
  $obj_dir_env = array(
    'nombre_completo' => $row['direccion_envio']['firstname'] . ' ' . $row['direccion_envio']['lastname'],
    'nif' => $this->nif,
    'empresa' => '',
    'direccion' => $row['direccion_envio']['street_1'],
    'ciudad' => $row['direccion_envio']['city'],
    'codigo_postal' => $row['direccion_envio']['zip_code'],
    'telefono' => $row['direccion_envio']['phone'],
    'provincia' => $row['direccion_envio']['state']
  );
  $this->direccion_envio = new direccion($obj_dir_env);
}



//Utilizamos los arrays para dar funcionalidad mientras depuramos
//$this->direccion_factura = $row['direccion_factura'];
//$this->direccion_envio = $row['direccion_envio'];
}




    /**
     * Get the value of Fecha Aceptado
     *
     * @return mixed
     */
    public function getFechaAceptado()
    {
        return $this->fecha_aceptado;
    }

    /**
     * Set the value of Fecha Aceptado
     *
     * @param mixed $fecha_aceptado
     *
     * @return self
     */
    public function setFechaAceptado($fecha_aceptado)
    {
        $this->fecha_aceptado = $fecha_aceptado;

        return $this;
    }

    /**
     * Get the value of Fecha Debito
     *
     * @return mixed
     */
    public function getFechaDebito()
    {
        return $this->fecha_debito;
    }

    /**
     * Set the value of Fecha Debito
     *
     * @param mixed $fecha_debito
     *
     * @return self
     */
    public function setFechaDebito($fecha_debito)
    {
        $this->fecha_debito = $fecha_debito;

        return $this;
    }

    /**
     * Get the value of Limite Envio
     *
     * @return mixed
     */
    public function getLimiteEnvio()
    {
        return $this->limite_envio;
    }

    /**
     * Set the value of Limite Envio
     *
     * @param mixed $limite_envio
     *
     * @return self
     */
    public function setLimiteEnvio($limite_envio)
    {
        $this->limite_envio = $limite_envio;

        return $this;
    }

    /**
     * Get the value of Comision
     *
     * @return mixed
     */
    public function getComision()
    {
        return $this->comision;
    }

    /**
     * Set the value of Comision
     *
     * @param mixed $comision
     *
     * @return self
     */
    public function setComision($comision)
    {
        $this->comision = $comision;

        return $this;
    }

    public function jsonSerialize():mixed {

      return [
        'fecha_creado' => self::getFechaCreado(),
        'fecha_aceptado' => self::getFechaAceptado(),
        'fecha_debito' => self::getFechaDebito(),
        'direccion_factura' => self::getDireccionFactura(),
        'direccion_envio' => self::getDireccionEnvio(),
        'dir_envio_igual_factura' => self::getDirEnvioIgualFactura(),
        'nif' => self::getNif(),
        'nombre_apellidos' => self::getNombreApellidos(),
        'lineas_pedido' => self::getLineasPedido(),
        'total_pedido' => self::getTotalPedido(),
        'comision' => self::getComision(),
        'id' => self::getId(),
        'estado' => self::getEstado(),
        'envio' => self::getEnvio(),
        'tienda' => self::getTienda()
      ];
    }


}

 ?>

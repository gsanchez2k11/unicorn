<?php
namespace unicorn\clases\objetos\Pedidos;
/**
 *
 */
require_once 'Linea_Pedido.php';
use unicorn\clases\funciones\unicorn_db\Entidad as entidad;

require_once RAIZ . '/clases/funciones/unicorn_db/Entidad.php';

abstract class Linea_Pedido_venta extends Linea_Pedido
{
protected $cantidad;
protected $sku;
protected $nombre;
protected $importe;
protected $impuestos;
protected $mpn;



/**
 * Get the value of Cantidad
 *
 * @return mixed
 */
public function getCantidad()
{
    return $this->cantidad;
}

/**
 * Set the value of Cantidad
 *
 * @param mixed $cantidad
 *
 * @return self
 */
public function setCantidad($cantidad)
{
    $this->cantidad = $cantidad;

    return $this;
}

/**
 * Get the value of Sku
 *
 * @return mixed
 */
public function getSku()
{
    return $this->sku;
}

/**
 * Set the value of Sku
 *
 * @param mixed $sku
 *
 * @return self
 */
public function setSku($sku)
{
    $this->sku = $sku;

    return $this;
}

/**
 * Get the value of Nombre
 *
 * @return mixed
 */
public function getNombre()
{
    return $this->nombre;
}

/**
 * Set the value of Nombre
 *
 * @param mixed $nombre
 *
 * @return self
 */
public function setNombre($nombre)
{
    $this->nombre = $nombre;
    return $this;
}

/**
 * Get the value of Importe
 *
 * @return mixed
 */
public function getImporte()
{
    return $this->importe;
}

/**
 * Set the value of Importe
 *
 * @param mixed $importe
 *
 * @return self
 */
public function setImporte($importe)
{
    $this->importe = $importe;

    return $this;
}




/**
 * Get the value of Impuestos
 *
 * @return mixed
 */
public function getImpuestos()
{
    return $this->impuestos;
}

/**
 * Set the value of Impuestos
 *
 * @param mixed $impuestos
 *
 * @return self
 */
public function setImpuestos($impuestos)
{
    $this->impuestos = $impuestos;

    return $this;
}

public function getAtributosBd(){
  $resultado = array();
  if (isset($this->mpn)) $row['mpn'] = $this->mpn;                              //Si tenemos el mpn lo pasamos al array
 $row['sku_pcc'] = $this->sku;                                                  //Si tenemos el sku lo pasamos como código de pc componentes
$buscar = entidad::buscarEntidad($row);                                         //Buscamos la entidad en la base de datos
//print_r($buscar);
if (!empty($buscar)) {
$atributos = entidad::dameAtributosEntidad($buscar['entidad']);
$resultado = $atributos;
}
return $resultado;
}



}

 ?>

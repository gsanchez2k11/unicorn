<?php
namespace unicorn\clases\objetos\Articulos;
require_once 'Articulo.php';
require_once 'Compra_articulo.php';
use unicorn\clases\objetos\Compra_articulo as compraarticulo;

class Articulo_unicornio extends Articulo {

public $referencia;
public $entidad;
public $precio_venta;
public $compras;
public $mage;


public function __construct($row)
{
$this->referencia = $row['referencia'];
$this->entidad = $row['entidad'];
$this->precio_venta = $row['precio_venta'];
//$this->compras = $row['compras'];
if (isset($row['compras'])) {
  foreach ($row['compras'] as $compra) {
    $compras[] = new Compra_articulo($compra);
  }
  $this->compras = $compras;
}

}



    /**
     * Get the value of Referencia
     *
     * @return mixed
     */
    public function getReferencia()
    {
        return $this->referencia;
    }

    /**
     * Set the value of Referencia
     *
     * @param mixed $referencia
     *
     * @return self
     */
    public function setReferencia($referencia)
    {
        $this->referencia = $referencia;

        return $this;
    }


    public function getMage()
    {
        return $this->mage;
    }

    public function setMage($datos_mage)
    {
        $this->mage = $datos_mage;

        return $this;
    }

    /**
     * Get the value of Entidad
     *
     * @return mixed
     */
    public function getEntidad()
    {
        return $this->entidad;
    }

    /**
     * Set the value of Entidad
     *
     * @param mixed $entidad
     *
     * @return self
     */
    public function setEntidad($entidad)
    {
        $this->entidad = $entidad;

        return $this;
    }

    public function setSku()
    {
        $this->sku = $this->referencia;

        return $this;
    }


    /**
     * Get the value of Precio Venta
     *
     * @return mixed
     */
    public function getPrecioVenta()
    {
        return $this->precio_venta;
    }

    /**
     * Set the value of Precio Venta
     *
     * @param mixed $precio_venta
     *
     * @return self
     */
    public function setPrecioVenta($precio_venta)
    {
        $this->precio_venta = $precio_venta;

        return $this;
    }

    /**
     * Get the value of Compras
     *
     * @return mixed
     */
    public function getCompras()
    {
        return $this->compras;
    }

    /**
     * Set the value of Compras
     *
     * @param mixed $compras
     *
     * @return self
     */
    public function setCompras($compras)
    {
        $this->compras = $compras;

        return $this;
    }

    public function AddCompra(array $compra)
    {
        $this->compras[] = $compra;
        return $this;
    }

}

 ?>

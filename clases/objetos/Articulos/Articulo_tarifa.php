<?php
namespace unicorn\clases\objetos\Articulos;
require_once 'Articulo.php';
require_once RAIZ . '/clases/funciones/otras/Moneda.php';
use unicorn\clases\funciones\otras\Moneda as moneda;
require_once 'Compra_articulo_tarifa.php';
use unicorn\clases\objetos\Articulos\Compra_articulo_tarifa as compra;

use JsonSerializable;

/**
 *
 */
class Articulo_tarifa extends Articulo implements JsonSerializable
{

  private $referencia;
  private $activo_web;
  private $descripcion;
  private $precio;
  private $precio_m2;
  private $fecha_actualizado;
  private $proveedor;
  private $unidades;
  private $pvp_m2;
 private $compras; //Compras del artículo


private $ancho;
private $largo;


public function __construct($row)
{
$this->referencia = $row['referencia'];
$this->activo_web = $row['activo_web'];
$this->descripcion = $row['descripcion'];
$this->precio = $row['precio'];
$this->precio_m2 = $row['precio_m2'];

if (isset($row['compras'])) $this->compras = $row['compras'];

$this->ancho = $row['ancho'];
$this->largo = $row['largo'];
}



    public function jsonSerialize():mixed {

      return [
         'referencia' => self::getReferencia(),
         'activo_web' => self::getActivoWeb(),
         'descripcion' => self::getDescripcion(),
         'precio' => self::getPrecio(),
         'precio_m2' => self::getPrecioM2(),
'compras' => self::getCompras(),
    //     'compra_m2' => self::getCompraM2(),
    //     'compra_unidad' => self::getCompraUnidad(),
    //     'compra_base' => self::getCompraBase(),
    //     'portes_compra' => self::getPortesCompra(),
    //     'otros_gastos_compra' => self::getOtrosGastosCompra(),
    //     'total_compra' => self::getTotalCompra(),
    //     'margen' => self::getMargen(),
    //   'venta_unidad' => self::getVentaUnidad(),
    //   'base_venta' => self::getBaseVenta(),
    //   'portes_venta' => self::getPortesVenta(),
    //   'otros_gastos_venta' => self::getOtrosGastosVenta(),
    //   'precio_venta' => self::getPrecioVenta(),
    //   'venta_m2' => self::getVentaM2(),
       'ancho' => self::getAncho(),
       'largo' => self::getLargo()
      ];
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

    /**
     * Get the value of Activo Web
     *
     * @return mixed
     */
    public function getActivoWeb()
    {
        return $this->activo_web;
    }

    /**
     * Set the value of Activo Web
     *
     * @param mixed $activo_web
     *
     * @return self
     */
    public function setActivoWeb($activo_web)
    {
        $this->activo_web = $activo_web;

        return $this;
    }

    /**
     * Get the value of Descripcion
     *
     * @return mixed
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * Set the value of Descripcion
     *
     * @param mixed $descripcion
     *
     * @return self
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    /**
     * Get the value of Precio
     *
     * @return mixed
     */
    public function getPrecio()
    {
        return moneda::cadenaAnumero($this->precio);
      //  return $this->precio;
    }

    /**
     * Set the value of Precio
     *
     * @param mixed $precio
     *
     * @return self
     */
    public function setPrecio($precio)
    {
        $this->precio = $precio;

        return $this;
    }

    /**
     * Get the value of Precio m2
     *
     * @return mixed
     */
    public function getPrecioM2()
    {
        return moneda::cadenaAnumero($this->precio_m2);
    }

    /**
     * Set the value of Precio m2
     *
     * @param mixed $precio_m2
     *
     * @return self
     */
    public function setPrecioM2($precio_m2)
    {
        $this->precio_m2 = $precio_m2;

        return $this;
    }





    /**
     * Get the value of Ancho
     *
     * @return mixed
     */
    public function getAncho()
    {
        return $this->ancho;
    }

    /**
     * Set the value of Ancho
     *
     * @param mixed $ancho
     *
     * @return self
     */
    public function setAncho($ancho)
    {
        $this->ancho = $ancho;

        return $this;
    }

    /**
     * Get the value of Largo
     *
     * @return mixed
     */
    public function getLargo()
    {
        return $this->largo;
    }

    /**
     * Set the value of Largo
     *
     * @param mixed $largo
     *
     * @return self
     */
    public function setLargo($largo)
    {
        $this->largo = $largo;

        return $this;
    }

    public function addCompra(array $compra){
$obj_compra = new compra($compra);
      $this->compras[] = $obj_compra;
      return $this;
    }

    public function getCompras()
    {
        return $this->compras;
    }

}


 ?>

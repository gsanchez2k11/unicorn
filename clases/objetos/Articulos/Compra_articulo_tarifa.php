<?php
namespace unicorn\clases\objetos\Articulos;
require_once RAIZ . '/clases/funciones/otras/Moneda.php';
use unicorn\clases\funciones\otras\Moneda as moneda;
use JsonSerializable;
/**
 *
 */
class Compra_articulo_tarifa implements JsonSerializable
{
  private $fecha_actualizado;
  private $proveedor;
  private $ref_proveedor;
  private $unidades;
  private $pvp_m2;
    private $dto_compra;
    private $pvp;
    private $compra_m2;
    private $compra_unidad;
    private $compra_base;
    private $portes_compra;
    private $otros_gastos_compra;
    private $total_compra;
    private $margen;
  private $venta_unidad;
  private $base_venta;
  private $portes_venta;
  private $otros_gastos_venta;
  private $precio_venta;
  private $venta_m2;

  function __construct($row)
  {
    $this->fecha_actualizado = $row['fecha_actualizado'];
    $this->proveedor = $row['proveedor'];
      $this->ref_proveedor = $row['ref_proveedor'];
    $this->unidades = $row['unidades'];
    $this->pvp_m2 = $row['pvp_m2'];
    $this->dto_compra = $row['dto_compra'];
    $this->pvp = $row['pvp'];
    $this->compra_m2 = $row['compra_m2'];
    $this->compra_unidad = $row['compra_unidad'];
    $this->compra_base = $row['compra_base'];
    $this->portes_compra = $row['portes_compra'];
    $this->otros_gastos_compra = $row['otros_gastos_compra'];
    $this->total_compra = $row['total_compra'];
    $this->margen = $row['margen'];
    $this->venta_unidad = $row['venta_unidad'];
    $this->base_venta = $row['base_venta'];
    $this->portes_venta = $row['portes_venta'];
    $this->otros_gastos_venta = $row['otros_gastos_venta'];
    $this->precio_venta = $row['precio_venta'];
    $this->venta_m2 = $row['venta_m2'];
    }

    /**
     * Get the value of Fecha Actualizado
     *
     * @return mixed
     */
    public function getFechaActualizado()
    {
        return $this->fecha_actualizado;
    }

    /**
     * Set the value of Fecha Actualizado
     *
     * @param mixed $fecha_actualizado
     *
     * @return self
     */
    public function setFechaActualizado($fecha_actualizado)
    {
        $this->fecha_actualizado = $fecha_actualizado;

        return $this;
    }


        /**
         * Get the value of Proveedor
         *
         * @return mixed
         */
        public function getProveedor()
        {
            return $this->proveedor;
        }

        /**
         * Set the value of Proveedor
         *
         * @param mixed $proveedor
         *
         * @return self
         */
        public function setProveedor($proveedor)
        {
            $this->proveedor = $proveedor;

            return $this;
        }



            /**
             * Get the value of Unidades
             *
             * @return mixed
             */
            public function getUnidades()
            {
                return $this->unidades;
            }

            /**
             * Set the value of Unidades
             *
             * @param mixed $unidades
             *
             * @return self
             */
            public function setUnidades($unidades)
            {
                $this->unidades = $unidades;

                return $this;
            }


                /**
                 * Get the value of Pvp m2
                 *
                 * @return mixed
                 */
                public function getPvpM2()
                {
                    return moneda::cadenaAnumero($this->pvp_m2);
                }

                /**
                 * Set the value of Pvp m2
                 *
                 * @param mixed $pvp_m2
                 *
                 * @return self
                 */
                public function setPvpM2($pvp_m2)
                {
                    $this->pvp_m2 = $pvp_m2;

                    return $this;
                }


                    /**
                     * Get the value of Dto Compra
                     *
                     * @return mixed
                     */
                    public function getDtoCompra()
                    {
                        return $this->dto_compra;
                    }

                    /**
                     * Set the value of Dto Compra
                     *
                     * @param mixed $dto_compra
                     *
                     * @return self
                     */
                    public function setDtoCompra($dto_compra)
                    {
                        $this->dto_compra = $dto_compra;

                        return $this;
                    }

                    /**
                     * Get the value of Pvp
                     *
                     * @return mixed
                     */
                    public function getPvp()
                    {
                        return moneda::cadenaAnumero($this->pvp);
                    }

                    /**
                     * Set the value of Pvp
                     *
                     * @param mixed $pvp
                     *
                     * @return self
                     */
                    public function setPvp($pvp)
                    {
                        $this->pvp = $pvp;

                        return $this;
                    }

                    /**
                     * Get the value of Compra m2
                     *
                     * @return mixed
                     */
                    public function getCompraM2()
                    {
                        return moneda::cadenaAnumero($this->compra_m2);
                    }

                    /**
                     * Set the value of Compra m2
                     *
                     * @param mixed $compra_m2
                     *
                     * @return self
                     */
                    public function setCompraM2($compra_m2)
                    {
                        $this->compra_m2 = $compra_m2;

                        return $this;
                    }

                    /**
                     * Get the value of Compra Unidad
                     *
                     * @return mixed
                     */
                    public function getCompraUnidad()
                    {
                        return moneda::cadenaAnumero($this->compra_unidad);
                    }

                    /**
                     * Set the value of Compra Unidad
                     *
                     * @param mixed $compra_unidad
                     *
                     * @return self
                     */
                    public function setCompraUnidad($compra_unidad)
                    {
                        $this->compra_unidad = $compra_unidad;

                        return $this;
                    }

                    /**
                     * Get the value of Compra Base
                     *
                     * @return mixed
                     */
                    public function getCompraBase()
                    {
                        return moneda::cadenaAnumero($this->compra_base);
                    }

                    /**
                     * Set the value of Compra Base
                     *
                     * @param mixed $compra_base
                     *
                     * @return self
                     */
                    public function setCompraBase($compra_base)
                    {
                        $this->compra_base = $compra_base;

                        return $this;
                    }

                    /**
                     * Get the value of Portes Compra
                     *
                     * @return mixed
                     */
                    public function getPortesCompra()
                    {
                        return moneda::cadenaAnumero($this->portes_compra);
                    }

                    /**
                     * Set the value of Portes Compra
                     *
                     * @param mixed $portes_compra
                     *
                     * @return self
                     */
                    public function setPortesCompra($portes_compra)
                    {
                        $this->portes_compra = $portes_compra;

                        return $this;
                    }

                    /**
                     * Get the value of Otros Gastos Compra
                     *
                     * @return mixed
                     */
                    public function getOtrosGastosCompra()
                    {
                        return moneda::cadenaAnumero($this->otros_gastos_compra);
                    }

                    /**
                     * Set the value of Otros Gastos Compra
                     *
                     * @param mixed $otros_gastos_compra
                     *
                     * @return self
                     */
                    public function setOtrosGastosCompra($otros_gastos_compra)
                    {
                        $this->otros_gastos_compra = $otros_gastos_compra;

                        return $this;
                    }

                    /**
                     * Get the value of Total Compra
                     *
                     * @return mixed
                     */
                    public function getTotalCompra()
                    {
                        return moneda::cadenaAnumero($this->total_compra);
                    }

                    /**
                     * Set the value of Total Compra
                     *
                     * @param mixed $total_compra
                     *
                     * @return self
                     */
                    public function setTotalCompra($total_compra)
                    {
                        $this->total_compra = $total_compra;

                        return $this;
                    }

                    /**
                     * Get the value of Margen
                     *
                     * @return mixed
                     */
                    public function getMargen()
                    {
                        return $this->margen;
                    }

                    /**
                     * Set the value of Margen
                     *
                     * @param mixed $margen
                     *
                     * @return self
                     */
                    public function setMargen($margen)
                    {
                        $this->margen = $margen;

                        return $this;
                    }
                    /**
                     * Get the value of Margen
                     *
                     * @return mixed
                     */
                    public function getRefProveedor()
                    {
                        return $this->ref_proveedor;
                    }

                    /**
                     * Set the value of Margen
                     *
                     * @param mixed $margen
                     *
                     * @return self
                     */
                    public function setRefProveedor($ref_proveedor)
                    {
                        $this->ref_proveedor = $ref_proveedor;

                        return $ref_proveedor;
                    }

                    /**
                     * Get the value of Venta Unidad
                     *
                     * @return mixed
                     */
                    public function getVentaUnidad()
                    {
                        return moneda::cadenaAnumero($this->venta_unidad);
                    }

                    /**
                     * Set the value of Venta Unidad
                     *
                     * @param mixed $venta_unidad
                     *
                     * @return self
                     */
                    public function setVentaUnidad($venta_unidad)
                    {
                        $this->venta_unidad = $venta_unidad;

                        return $this;
                    }

                    /**
                     * Get the value of Base Venta
                     *
                     * @return mixed
                     */
                    public function getBaseVenta()
                    {
                        return moneda::cadenaAnumero($this->base_venta);
                    }

                    /**
                     * Set the value of Base Venta
                     *
                     * @param mixed $base_venta
                     *
                     * @return self
                     */
                    public function setBaseVenta($base_venta)
                    {
                        $this->base_venta = $base_venta;

                        return $this;
                    }

                    /**
                     * Get the value of Portes Venta
                     *
                     * @return mixed
                     */
                    public function getPortesVenta()
                    {
                        return moneda::cadenaAnumero($this->portes_venta);
                    }

                    /**
                     * Set the value of Portes Venta
                     *
                     * @param mixed $portes_venta
                     *
                     * @return self
                     */
                    public function setPortesVenta($portes_venta)
                    {
                        $this->portes_venta = $portes_venta;

                        return $this;
                    }

                    /**
                     * Get the value of Otros Gastos Venta
                     *
                     * @return mixed
                     */
                    public function getOtrosGastosVenta()
                    {
                        return moneda::cadenaAnumero($this->otros_gastos_venta);
                    }

                    /**
                     * Set the value of Otros Gastos Venta
                     *
                     * @param mixed $otros_gastos_venta
                     *
                     * @return self
                     */
                    public function setOtrosGastosVenta($otros_gastos_venta)
                    {
                        $this->otros_gastos_venta = $otros_gastos_venta;

                        return $this;
                    }

                    /**
                     * Get the value of Precio Venta
                     *
                     * @return mixed
                     */
                    public function getPrecioVenta()
                    {
                        return moneda::cadenaAnumero($this->precio_venta);
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
                     * Get the value of Venta m2
                     *
                     * @return mixed
                     */
                    public function getVentaM2()
                    {
                        return moneda::cadenaAnumero($this->venta_m2);
                    }

                    /**
                     * Set the value of Venta m2
                     *
                     * @param mixed $venta_m2
                     *
                     * @return self
                     */
                    public function setVentaM2($venta_m2)
                    {
                        $this->venta_m2 = $venta_m2;

                        return $this;
                    }


  public function jsonSerialize():mixed {
                      return [
                        'fecha_actualizado' => self::getFechaActualizado(),
                        'proveedor' => self::getProveedor(),
                        'ref_proveedor' =>self::getRefProveedor(),
                        'unidades' => self::getUnidades(),
                        'pvp_m2' => self::getPvpM2(),
                        'dto_compra' => self::getDtoCompra(),
                        'pvp' => self::getPvp(),
                         'compra_m2' => self::getCompraM2(),
                         'compra_unidad' => self::getCompraUnidad(),
                         'compra_base' => self::getCompraBase(),
                         'portes_compra' => self::getPortesCompra(),
                         'otros_gastos_compra' => self::getOtrosGastosCompra(),
                         'total_compra' => self::getTotalCompra(),
                         'margen' => self::getMargen(),
                       'venta_unidad' => self::getVentaUnidad(),
                       'base_venta' => self::getBaseVenta(),
                       'portes_venta' => self::getPortesVenta(),
                       'otros_gastos_venta' => self::getOtrosGastosVenta(),
                       'precio_venta' => self::getPrecioVenta(),
                       'venta_m2' => self::getVentaM2(),

                      ];
                    }
}

 ?>

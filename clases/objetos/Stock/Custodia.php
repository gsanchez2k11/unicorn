<?php
namespace unicorn\clases\objetos\Articulos;
use JsonSerializable;
/**
 *
 */
class Custodia implements JsonSerializable
{
private $codinf;
private $quedan;
private $pedido;
private $id_custodia;
private $stock_original;
private $precio;
private $lpi;

public function __construct($row) {
  $this->codinf = $row['codinf'];
  $this->quedan = $row['quedan'];
  $this->pedido = $row['pedido'];
  $this->id_custodia = $row['id_custodia'];
  $this->stock_original = $row['stock_original'];
  $this->precio = $row['precio'];
  $this->lpi = $row['lpi'];
  $this->total_compra = self::getTotalCompra();
}

public function getTotalCompra() {
  $total_con_portes = $this->precio > 60 ? $this->precio : $this->precio + 3.20;
  $total_iva = $total_con_portes * 1.21;
  return $total_iva;
}


    /**
     * Get the value of Codinf
     *
     * @return mixed
     */
    public function getCodinf()
    {
        return $this->codinf;
    }

    /**
     * Set the value of Codinf
     *
     * @param mixed $codinf
     *
     * @return self
     */
    public function setCodinf($codinf)
    {
        $this->codinf = $codinf;

        return $this;
    }

    /**
     * Get the value of Quedan
     *
     * @return mixed
     */
    public function getQuedan()
    {
        return $this->quedan;
    }

    /**
     * Set the value of Quedan
     *
     * @param mixed $quedan
     *
     * @return self
     */
    public function setQuedan($quedan)
    {
        $this->quedan = $quedan;

        return $this;
    }

    /**
     * Get the value of Pedido
     *
     * @return mixed
     */
    public function getPedido()
    {
        return $this->pedido;
    }

    /**
     * Set the value of Pedido
     *
     * @param mixed $pedido
     *
     * @return self
     */
    public function setPedido($pedido)
    {
        $this->pedido = $pedido;

        return $this;
    }

    /**
     * Get the value of Id Custodia
     *
     * @return mixed
     */
    public function getIdCustodia()
    {
        return $this->id_custodia;
    }

    /**
     * Set the value of Id Custodia
     *
     * @param mixed $id_custodia
     *
     * @return self
     */
    public function setIdCustodia($id_custodia)
    {
        $this->id_custodia = $id_custodia;

        return $this;
    }

    /**
     * Get the value of Stock Original
     *
     * @return mixed
     */
    public function getStockOriginal()
    {
        return $this->stock_original;
    }

    /**
     * Set the value of Stock Original
     *
     * @param mixed $stock_original
     *
     * @return self
     */
    public function setStockOriginal($stock_original)
    {
        $this->stock_original = $stock_original;

        return $this;
    }

    /**
     * Get the value of Precio
     *
     * @return mixed
     */
    public function getPrecio()
    {
        return $this->precio;
    }

    public function getPrecioTotal()
    {
      //  return number_format($this->precio * 1.21,2);
    //  $total_neto = self::getPrecio() + self::getLpi();
      $total_neto = self::getPrecio();
      $total_iva = number_format($total_neto * 1.21,2);
      return $total_iva;
    }

    /*public function getLpi()
    {
        return $this->lpi;
    }

    public function setLpi($lpi)
    {
        $this->lpi = $lpi;

        return $this;
    }*/
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

    public function jsonSerialize():mixed {

      return [
        'codinf' => self::getcodinf(),
        'quedan' => self::getquedan(),
        'pedido'   => self::getpedido(),
        'id_custodia'   => self::getIdCustodia(),
        'stock_original'   => self::getStockOriginal(),
        'precio'   => self::getprecio(),
      //  'lpi'   => self::getLpi(),
      'total_compra' => self::getTotalCompra()
      ];
    }

}



 ?>

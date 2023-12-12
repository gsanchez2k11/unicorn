<?php
/**
 *
 */
namespace unicorn\clases\objetos\Articulos;
require_once 'Articulo.php';
require_once RAIZ . '/clases/funciones/unicorn_db/Categorias_Marketplaces.php';

use unicorn\clases\funciones\unicorn_db as unicorndb;

class Articulo_mirakl extends Articulo {

public $precio;
public $product_sku;
public $shop_sku;
public $logistic_class;
public $state_code;
public $category;
public $mpn;
public $ean;
public $compras;
public $stock;
public $offer_id;
public $nombre;
public $si_custodia;
public $ofertas;
public $entidad;

    public function __construct($row)
    {
  $this->precio = $row['precio'];
  $this->product_sku = $row['product_sku'];
  $this->shop_sku = $row['shop_sku'];
  $this->logistic_class = $row['logistic_class'];
  $this->ean = $row['ean'];
  $this->mpn = $row['mpn'];
  $this->state_code = $row['state_code'];
  //$this->$precio_compra = $row['price'];
  $this->category = $row['category'];
  $this->stock = $row['stock'];
  $this->offer_id = $row['offer_id'];
  $this->nombre = $row['nombre'];
  $this->si_custodia = $row['si_custodia'];
  if(isset($row['entidad'])) $this->entidad = $row['entidad'];
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

    /**
     * Get the value of nombre
     *
     * @return mixed
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Get the value of Product Sku
     *
     * @return mixed
     */
    public function getProductSku()
    {
        return $this->product_sku;
    }

    /**
     * Get the value of Shop Sku
     *
     * @return mixed
     */
    public function getShopSku()
    {
        return $this->shop_sku;
    }

    /**
     * Get the value of Logistic Class
     *
     * @return mixed
     */
    public function getLogisticClass()
    {
        return $this->logistic_class;
    }

    /**
     * Get the value of State Code
     *
     * @return mixed
     */
    public function getStateCode()
    {
        return $this->state_code;
    }

    /**
     * Get the value of Category
     *
     * @return mixed
     */
    public function getCategory()
    {
        return $this->category;
    }

    public function getComision()
    {
    $categoria =   $this->category;
    //Buscamos la categoria
$categoria_resultado = unicorndb\Categorias_Marketplaces::buscarCategoriaMarketplace($categoria);
 $comision = $categoria_resultado['comision'];
 return $comision;
    }

    /**
     * Get the value of Mpn
     *
     * @return mixed
     */
    public function getMpn()
    {
        return $this->mpn;
    }


    /**
     * Get the value of Ean
     *
     * @return mixed
     */
    public function getEan()
    {
        return $this->ean;
    }

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

    public function getStock()
    {
        return $this->stock;
    }
    public function getOfferId()
    {
        return $this->offer_id;
    }
    public function getSi_custodia()
    {
        return $this->si_custodia;
    }
    public function setSi_custodia($valor)
    {
        $this->Si_custodia = $Si_custodia;

        return $this;
    }

    public function getOfertas()
    {
        return $this->ofertas;
    }
    public function setOfertas($valor)
    {
        $this->ofertas = $valor;

        return $this;
    }

    public function getEntidad()
    {
        return $this->entidad;
    }
    public function setEntidad($valor)
    {
        $this->entidad = $valor;

        return $this;
    }

}

 ?>

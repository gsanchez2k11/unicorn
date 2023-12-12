<?php
/**
 *
 */
namespace unicorn\clases\objetos\Articulos;
require_once 'Articulo.php';
require_once RAIZ . '/clases/funciones/unicorn_db/Entidad.php';
require_once RAIZ . '/clases/funciones/unicorn_db/Categorias_Marketplaces.php';

use unicorn\clases\funciones\unicorn_db as unicorndb;
//use unicorn\clases\objetos\Articulos\Articulo as articulo;

class Articulo_fnac extends Articulo {

public $precio;
public $product_sku;
//public $shop_sku;
//public $logistic_class;
public $state_code;
public $categoria;
public $mpn;
//public $ean;
public $compras;
public $stock;
public $offer_id;
public $nombre;
//public $si_custodia;

    public function __construct($row)
    {
  $this->precio = $row['price'];
  $this->product_sku = $row['product_fnac_id'];
  //$this->shop_sku = $row['shop_sku'];
  //$this->logistic_class = $row['logistic_class'];
  //$this->ean = $row['ean'];
  $this->mpn = $row['offer_seller_id'];
  $this->state_code = $row['product_state'];
  //$this->$precio_compra = $row['price'];
  $this->categoria = $row['categoria'];
  $this->stock = $row['quantity'];
  $this->offer_id = $row['offer_fnac_id'];
  $this->nombre = $row['product_name'];
  //$this->si_custodia = $row['si_custodia'];
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
      $categoria = isset($this->category) ? $this->category : '';
        return $categoria;
    }

    public function getComision()
    {
    //Buscamos la categoria
$categoria_resultado = unicorndb\Categorias_Marketplaces::buscarCategoriaMarketplace($this->categoria, 'fnac');
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
    //Buscamos la entidad por el sku fnac
      $entidades = unicorndb\Entidad::buscarArticuloEntidadInt('10',$this->product_sku);

      if (empty($entidades)) { //Si no tenemos el sku fnac buscamos por el mpn
      $entidades = unicorndb\Entidad::dameValorArticuloEntidadVarchar('2',$this->mpn);
    }
if (is_array($entidades) && isset($entidades[0])) { //Si tenemos resultados asignamos la entidad
  $entidad = $entidades[0];
} else {                    //En caso contrario vamos a crearla
  $row = array(
    'mpn' => $this->mpn,
    'sku_fnac' => $this->product_sku
  );
  $entidad = unicorndb\Entidad::crearEntidad($row);
}
$atributos = unicorndb\Entidad::dameAtributosEntidad($entidad);
$ean = isset($atributos[3]) ? $atributos[3] : '';
return $ean;
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

}

 ?>

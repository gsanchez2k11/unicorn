<?php
namespace unicorn\clases\objetos\Articulos;
require_once 'Articulo.php';
require_once RAIZ . '/clases/funciones/inforpor/Otros.php';
use unicorn\clases\funciones\inforpor\Otros as otros;

use JsonSerializable;

/**
 *
 */
class Articulo_tarifa_inforpor extends Articulo implements JsonSerializable
{

private $gama;
private $fabricante;
private $familia;
private $codigo;
private $referencia;
private $referencia2;
private $descripcion;
private $compatibilidades;
private $caracteristicas;
private $lpi;
private $precio;
private $sioferta;
private $stock;
private $ean; //codigo de barras
private $embalaje;
private $palet;
private $peso;
private $fecha_entrada;
private $imagen;
private $promo;
private $fecha_fin_promo;
private $precio_promo;
private $reserva;
private $custodia;


public function __construct($row)
{
$this->gama = isset($row['gama']) ? $row['gama'] : $row[0];
$this->fabricante =  isset($row['fabricante']) ? $row['fabricante'] : $row[1];
$this->familia = isset($row['familia']) ? $row['familia'] : $row[2];
$this->codigo = isset($row['codigo']) ? $row['codigo'] : $row[3];
$this->referencia = isset($row['referencia']) ? $row['referencia'] : $row[4];
$this->referencia2 = isset($row['referencia2']) ? $row['referencia2'] : $row[5];
$this->descripcion = isset($row['descripcion']) ? $row['descripcion'] : $row[6];
$this->compatibilidades = isset($row['compatibilidades']) ? $row['compatibilidades'] : $row[7];
$this->caracteristicas = isset($row['caracteristicas']) ? $row['caracteristicas'] : strip_tags($row[8]);
$this->lpi = isset($row['lpi']) ? otros::toDouble($row['lpi']) : otros::toDouble($row[9]);
$this->precio = isset($row['precio']) ? otros::toDouble($row['precio']) : otros::toDouble($row[10]);
$this->sioferta = isset($row['sioferta']) ? $row['sioferta'] : $row[11];
$this->stock = isset($row['stock']) ? $row['stock'] : $row[12];
$this->ean = isset($row['ean']) ? $row['ean'] : $row[13];
$this->embalaje = isset($row['embalaje']) ? $row['embalaje'] : $row[14];
$this->palet = isset($row['palet']) ? $row['palet'] : $row[15];
$this->peso = isset($row['peso']) ? $row['peso'] : $row[16];
$this->fecha_entrada = isset($row['fecha_entrada']) ? $row['fecha_entrada'] : $row[17];
$this->imagen = isset($row['imagen']) ? $row['imagen'] : $row[18];
$this->promo = isset($row['promo']) ? $row['promo'] : $row[19];
$this->fecha_fin_promo = isset($row['fecha_fin_promo']) ? $row['fecha_fin_promo'] : $row[20];
if (isset($row['precio_promo']) || isset($row[21])) {
    $this->precio_promo = isset($row['precio_promo']) ? otros::toDouble($row['precio_promo']) : otros::toDouble($row[21]);
}
if (isset($row['reserva']) || isset($row[23])) {
$this->reserva = isset($row['reserva']) ? $row['reserva'] : $row[23];
}
if (isset($row['custodia']) || isset($row[22])) {
$this->custodia = isset($row['custodia']) ? $row['custodia'] : $row[22];
}
}


public function getMejorPrecio(){
    $mejor_precio = self::getPrecioPromo() > 0 ? min(self::getPrecioPromo(), self::getPrecio()) : self::getPrecio();
    return $mejor_precio;
}
 /**
     * Get the value of Precio promo
     *
     * @return mixed
     */
    public function getPrecioPromo()
    {
        return $this->precio_promo;
    }

    /**
     * Set the value of Precio promo
     *
     * @param mixed $gama
     *
     * @return self
     */
    public function setPrecioPromo($precio_promo)
    {
        $this->precio_promo = $precio_promo;

        return $this;
    }


 /**
     * Get the value of Reserva
     *
     * @return mixed
     */
    public function getCustodia()
    {
        return $this->custodia;
    }

    /**
     * Set the value of Reserva
     *
     * @param mixed $gama
     *
     * @return self
     */
    public function setCustodia($custodia)
    {
        $this->custodia = $custodia;

        return $this;
    }

  /**
     * Get the value of Reserva
     *
     * @return mixed
     */
    public function getReserva()
    {
        return $this->reserva;
    }

    /**
     * Set the value of Reserva
     *
     * @param mixed $gama
     *
     * @return self
     */
    public function setReserva($reserva)
    {
        $this->reserva = $reserva;

        return $this;
    }



    /**
     * Get the value of Gama
     *
     * @return mixed
     */
    public function getGama()
    {
        return $this->gama;
    }

    /**
     * Set the value of Gama
     *
     * @param mixed $gama
     *
     * @return self
     */
    public function setGama($gama)
    {
        $this->gama = $gama;

        return $this;
    }

    /**
     * Get the value of Fabricante
     *
     * @return mixed
     */
    public function getFabricante()
    {
        return $this->fabricante;
    }

    /**
     * Set the value of Fabricante
     *
     * @param mixed $fabricante
     *
     * @return self
     */
    public function setFabricante($fabricante)
    {
        $this->fabricante = $fabricante;

        return $this;
    }

    /**
     * Get the value of Familia
     *
     * @return mixed
     */
    public function getFamilia()
    {
        return $this->familia;
    }

    /**
     * Set the value of Familia
     *
     * @param mixed $familia
     *
     * @return self
     */
    public function setFamilia($familia)
    {
        $this->familia = $familia;

        return $this;
    }

    /**
     * Get the value of Codigo
     *
     * @return mixed
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * Set the value of Codigo
     *
     * @param mixed $codigo
     *
     * @return self
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;

        return $this;
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
     * Get the value of Referencia
     *
     * @return mixed
     */
    public function getReferencia2()
    {
        return $this->referencia2;
    }

    /**
     * Set the value of Referencia
     *
     * @param mixed $referencia2
     *
     * @return self
     */
    public function setReferencia2($referencia2)
    {
        $this->referencia2 = $referencia2;

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
     * Get the value of Compatibilidades
     *
     * @return mixed
     */
    public function getCompatibilidades()
    {
        return $this->compatibilidades;
    }

    /**
     * Set the value of Compatibilidades
     *
     * @param mixed $compatibilidades
     *
     * @return self
     */
    public function setCompatibilidades($compatibilidades)
    {
        $this->compatibilidades = $compatibilidades;

        return $this;
    }

    /**
     * Get the value of Caracteristicas
     *
     * @return mixed
     */
    public function getCaracteristicas()
    {
        return $this->caracteristicas;
    }

    /**
     * Set the value of Caracteristicas
     *
     * @param mixed $caracteristicas
     *
     * @return self
     */
    public function setCaracteristicas($caracteristicas)
    {
        $this->caracteristicas = $caracteristicas;

        return $this;
    }

    /**
     * Get the value of Lpi
     *
     * @return mixed
     */
    public function getLpi()
    {
        return $this->lpi;
    }

    /**
     * Set the value of Lpi
     *
     * @param mixed $lpi
     *
     * @return self
     */
    public function setLpi($lpi)
    {
        $this->lpi = $lpi;

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
     * Get the value of Sioferta
     *
     * @return mixed
     */
    public function getSioferta()
    {
        return $this->sioferta;
    }

    /**
     * Set the value of Sioferta
     *
     * @param mixed $sioferta
     *
     * @return self
     */
    public function setSioferta($sioferta)
    {
        $this->sioferta = $sioferta;

        return $this;
    }

    /**
     * Get the value of Stock
     *
     * @return mixed
     */
    public function getStock()
    {
        return $this->stock;
    }

    /**
     * Set the value of Stock
     *
     * @param mixed $stock
     *
     * @return self
     */
    public function setStock($stock)
    {
        $this->stock = $stock;

        return $this;
    }

    /**
     * Get the value of Ean
     *
     * @return mixed
     */
    public function getEan()
    {
      $recibido = trim($this->ean);
      $longitud = strlen($recibido);
      while ($longitud < 13) {
        $recibido = '0' . $recibido;
        $longitud++;
      }
        return $recibido;
    //    return $this->ean;
    }

    /**
     * Set the value of Ean
     *
     * @param mixed $ean
     *
     * @return self
     */
    public function setEan($ean)
    {
        $this->ean = $ean;

        return $this;
    }

    /**
     * Get the value of Embalaje
     *
     * @return mixed
     */
    public function getEmbalaje()
    {
        return $this->embalaje;
    }

    /**
     * Set the value of Embalaje
     *
     * @param mixed $embalaje
     *
     * @return self
     */
    public function setEmbalaje($embalaje)
    {
        $this->embalaje = $embalaje;

        return $this;
    }

    /**
     * Get the value of Palet
     *
     * @return mixed
     */
    public function getPalet()
    {
        return $this->palet;
    }

    /**
     * Set the value of Palet
     *
     * @param mixed $palet
     *
     * @return self
     */
    public function setPalet($palet)
    {
        $this->palet = $palet;

        return $this;
    }

    /**
     * Get the value of Peso
     *
     * @return mixed
     */
    public function getPeso()
    {
        return $this->peso;
    }

    /**
     * Set the value of Peso
     *
     * @param mixed $peso
     *
     * @return self
     */
    public function setPeso($peso)
    {
        $this->peso = $peso;

        return $this;
    }

    /**
     * Get the value of Fecha Entrada
     *
     * @return mixed
     */
    public function getFechaEntrada()
    {
        return $this->fecha_entrada;
    }

    /**
     * Set the value of Fecha Entrada
     *
     * @param mixed $fecha_entrada
     *
     * @return self
     */
    public function setFechaEntrada($fecha_entrada)
    {
        $this->fecha_entrada = $fecha_entrada;

        return $this;
    }

    /**
     * Get the value of Imagen
     *
     * @return mixed
     */
    public function getImagen()
    {
        return $this->imagen;
    }

    /**
     * Set the value of Imagen
     *
     * @param mixed $imagen
     *
     * @return self
     */
    public function setImagen($imagen)
    {
        $this->imagen = $imagen;

        return $this;
    }

    /**
     * Get the value of Promo
     *
     * @return mixed
     */
    public function getPromo()
    {
        return $this->promo;
    }

    /**
     * Set the value of Promo
     *
     * @param mixed $promo
     *
     * @return self
     */
    public function setPromo($promo)
    {
        $this->promo = $promo;

        return $this;
    }

    /**
     * Get the value of Fecha Fin Promo
     *
     * @return mixed
     */
    public function getFechaFinPromo()
    {
        return $this->fecha_fin_promo;
    }

    /**
     * Set the value of Fecha Fin Promo
     *
     * @param mixed $fecha_fin_promo
     *
     * @return self
     */
    public function setFechaFinPromo($fecha_fin_promo)
    {
        $this->fecha_fin_promo = $fecha_fin_promo;

        return $this;
    }


    public function jsonSerialize():mixed {

      return [
        'gama' => self::getGama(),
        'fabricante' => self::getFabricante(),
        'familia' => self::getFamilia(),
        'codigo' => self::getCodigo(),
        'referencia' => self::getReferencia(),
        'referencia2' => self::getReferencia2(),
        'descripcion' => self::getDescripcion(),
        'compatibilidades'  => self::getCompatibilidades(),
        'caracteristicas' => self::getCaracteristicas(),
        'lpi' => self::getLpi(),
        'precio' => self::getPrecio(),
        'sioferta' => self::getSioferta(),
        'stock' => self::getStock(),
        'ean' => self::getEan(),
        'embalaje' => self::getEmbalaje(),
        'palet'  => self::getPalet(),
        'peso' => self::getPeso(),
        'fecha_entrada' => self::getFechaEntrada(),
        'imagen' => self::getImagen(),
        'promo' => self::getPromo(),
        'fecha_fin_promo'  => self::getFechaFinPromo(),
        'precio_promo' => self::getPrecioPromo(),
        'reserva' => self::getReserva(),
        'custodia' => self::getCustodia()
      ];
    }



}


 ?>

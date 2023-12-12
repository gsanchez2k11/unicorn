<?php
namespace unicorn\clases\objetos\Pedidos;
use JsonSerializable;
use unicorn\clases\funciones\unicorn_db\Entidad as entidad;
use unicorn\clases\funciones\inforpor\Pedido as pedido;
use unicorn\clases\funciones\inforpor\Stock as stock;
use unicorn\clases\funciones\otras\Moneda as moneda;
require_once RAIZ . '/clases/funciones/unicorn_db/Entidad.php';
require_once RAIZ . '/clases/funciones/inforpor/Pedido.php';
require_once RAIZ . '/clases/funciones/inforpor/Stock.php';
require_once RAIZ . '/clases/funciones/otras/Moneda.php';

/**
 *
 */
class Linea_Pedido_inforpor implements JsonSerializable
{
private $cant;
private $cerr;
private $codinf;
private $notas;
private $num;
private $precio;
private $stock;


  function __construct($row)
  {
$this->cant = $row['cant'];
$this->cerr = $row['cerr'];
$this->codinf = $row['codinf'];
$this->notas = $row['notas'];
$this->num = $row['num'];
$this->precio = $row['precio'];
$this->stock = $row['stock'];
  }



    /**
     * Get the value of Cant
     *
     * @return mixed
     */
    public function getCant()
    {
        return $this->cant;
    }

    /**
     * Set the value of Cant
     *
     * @param mixed $cant
     *
     * @return self
     */
    public function setCant($cant)
    {
        $this->cant = $cant;

        return $this;
    }

    /**
     * Get the value of Cerr
     *
     * @return mixed
     */
    public function getCerr()
    {
        return $this->cerr;
    }

    /**
     * Set the value of Cerr
     *
     * @param mixed $cerr
     *
     * @return self
     */
    public function setCerr($cerr)
    {
        $this->cerr = $cerr;

        return $this;
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
     * Get the value of Notas
     *
     * @return mixed
     */
    public function getNotas()
    {
        return $this->notas;
    }

    /**
     * Set the value of Notas
     *
     * @param mixed $notas
     *
     * @return self
     */
    public function setNotas($notas)
    {
        $this->notas = $notas;

        return $this;
    }

    /**
     * Get the value of Num
     *
     * @return mixed
     */
    public function getNum()
    {
        return $this->num;
    }

    /**
     * Set the value of Num
     *
     * @param mixed $num
     *
     * @return self
     */
    public function setNum($num)
    {
        $this->num = $num;

        return $this;
    }

    /**
     * Get the value of Precio
     *
     * @return mixed
     */
    public function getPrecio()
    {
    //    echo $this->precio . '<br>';
      $precio = '0';                                                            //Lo fijamos de inicio a 0
      if ($this->precio != '0,0000') {                                          //Si estamos recibiendo un precio lo dejamos tal cual
        $precio = $this->precio;
        $lpi = self::getLpi();                                                  //El canon
        $precio_float = floatval(moneda::cadenaAnumero($precio));
        $precio_total = $precio_float + $lpi;
      } else {
        if (preg_match('/^CUSTODIA\sN\s\d{6}/',self::getNotas())) {             //Comprobamos si tenemos nota como CUSTODIA N XXXXXX
        $matches = array();                                                     //Extraemos el numero de custodia
        preg_match('/\d{6}/', self::getNotas(), $matches);
        $ped_custodia['NumPedInf'] = $matches[0];                                         //Obtenemos el pedido del que se ha sacado el artículo
$info_custodia = pedido::BuscaArticuloEnPedidoObj(self::getCodinf(),$ped_custodia);       //Recibimos una array que solo debe tener un elemento
$precio = $info_custodia[0]->getPrecio(); 
}
$precio_float = floatval(moneda::cadenaAnumero($precio));
$precio_total = $precio_float;
      }



        return  $precio_total;
    }


    public function esCustodia() {
      $es = false;
      if (preg_match('/^CUSTODIA\sN\s\d{6}/',self::getNotas())) {             //Comprobamos si tenemos nota como CUSTODIA N XXXXXX
$es = true;
      }
return $es;
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
 * Conseguir el LPI del articulo
 * @return [type] [description]
 */
    public function getLpi(){
//      $lpi = '0';
$info_articulo = stock::StockPr(self::getCodinf());
$lpi = isset($info_articulo['lpi']) && $info_articulo['lpi'] != '0' ?  $info_articulo['lpi'] : '0';
return moneda::cadenaAnumero($lpi);
    }
    public function getAtributosBd(){
     $row['codinf'] = $this->codinf;
    $buscar = entidad::buscarEntidad($row);
    $atributos = entidad::dameAtributosEntidad($buscar['entidad']);
    //return $entidad;*/
    return $atributos;
    }
    public function jsonSerialize():mixed {
      return [
        'cant' => self::getCant(),
        'cerr' => self::getCerr(),
        'codinf' => self::getCodinf(),
        'notas' => self::getNotas(),
        'num' => self::getNum(),
        'precio' => self::getPrecio(),
        'stock' => self::getStock(),
      //  'atributos_bd'  => self::getAtributosBd()
      ];
    }

}


 ?>

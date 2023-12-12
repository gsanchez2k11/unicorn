<?php
namespace unicorn\clases\objetos\Pedidos;
/**
 *
 */
 use JsonSerializable;

 require_once 'Linea_Pedido_venta.php';

class Linea_Pedido_mirakl extends Linea_Pedido_venta implements JsonSerializable
{
  private $cancelaciones;
  //private $importe;
  private $promociones;
  //private $cantidad;
  private $abonos;
  private $portes;
  //private $impuestos;
  private $total_linea;
  private $historico;
  private $cod_categoria;
  private $etiqueta_categoria;
  //private $sku;
  //private $nombre;
  private $id;
  private $sku_oferta;
  private $estado;
  private $comision;

  function __construct($row)
  {
    $this->cancelaciones = $row['cancelaciones'];
    $this->importe = $row['importe'];
    $this->promociones = $row['promociones'];
    $this->cantidad = $row['cantidad'];
    $this->abonos = $row['abonos'];
    $this->portes = $row['portes'];
    $this->impuestos = $row['impuestos'];
    $this->total_linea = $row['total_linea'];
    $this->historico = $row['historico'];
    $this->cod_categoria = $row['cod_categoria'];
    $this->etiqueta_categoria = $row['etiqueta_categoria'];
    $this->sku = $row['sku'];
    $this->nombre = $row['nombre'];
    $this->id = $row['id'];
    $this->sku_oferta = $row['sku_oferta'];
    $this->estado = $row['estado'];
    $this->comision = $row['comision'];
  }


    /**
     * Get the value of Cancelaciones
     *
     * @return mixed
     */
    public function getCancelaciones()
    {
        return $this->cancelaciones;
    }

    /**
     * Set the value of Cancelaciones
     *
     * @param mixed $cancelaciones
     *
     * @return self
     */
    public function setCancelaciones($cancelaciones)
    {
        $this->cancelaciones = $cancelaciones;

        return $this;
    }



    /**
     * Get the value of Promociones
     *
     * @return mixed
     */
    public function getPromociones()
    {
        return $this->promociones;
    }

    /**
     * Set the value of Promociones
     *
     * @param mixed $promociones
     *
     * @return self
     */
    public function setPromociones($promociones)
    {
        $this->promociones = $promociones;

        return $this;
    }



    /**
     * Get the value of Abonos
     *
     * @return mixed
     */
    public function getAbonos()
    {
        return $this->abonos;
    }

    /**
     * Set the value of Abonos
     *
     * @param mixed $abonos
     *
     * @return self
     */
    public function setAbonos($abonos)
    {
        $this->abonos = $abonos;

        return $this;
    }

    /**
     * Get the value of Portes
     *
     * @return mixed
     */
    public function getPortes()
    {
        return $this->portes;
    }

    /**
     * Set the value of Portes
     *
     * @param mixed $portes
     *
     * @return self
     */
    public function setPortes($portes)
    {
        $this->portes = $portes;

        return $this;
    }



    /**
     * Get the value of Total Linea
     *
     * @return mixed
     */
    public function getTotalLinea()
    {
        return $this->total_linea;
    }

    /**
     * Set the value of Total Linea
     *
     * @param mixed $total_linea
     *
     * @return self
     */
    public function setTotalLinea($total_linea)
    {
        $this->total_linea = $total_linea;

        return $this;
    }

    /**
     * Get the value of Historico
     *
     * @return mixed
     */
    public function getHistorico()
    {
        return $this->historico;
    }

    /**
     * Set the value of Historico
     *
     * @param mixed $historico
     *
     * @return self
     */
    public function setHistorico($historico)
    {
        $this->historico = $historico;

        return $this;
    }

    /**
     * Get the value of Cod Categoria
     *
     * @return mixed
     */
    public function getCodCategoria()
    {
        return $this->cod_categoria;
    }

    /**
     * Set the value of Cod Categoria
     *
     * @param mixed $cod_categoria
     *
     * @return self
     */
    public function setCodCategoria($cod_categoria)
    {
        $this->cod_categoria = $cod_categoria;

        return $this;
    }

    /**
     * Get the value of Etiqueta Categoria
     *
     * @return mixed
     */
    public function getEtiquetaCategoria()
    {
        return $this->etiqueta_categoria;
    }

    /**
     * Set the value of Etiqueta Categoria
     *
     * @param mixed $etiqueta_categoria
     *
     * @return self
     */
    public function setEtiquetaCategoria($etiqueta_categoria)
    {
        $this->etiqueta_categoria = $etiqueta_categoria;

        return $this;
    }





    /**
     * Get the value of Id
     *
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of Id
     *
     * @param mixed $id
     *
     * @return self
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }


    /**
     * Get the value of Estado
     *
     * @return mixed
     */
    public function getEstado()
    {
      switch ($this->estado) {
        case 'INCIDENT_OPEN':
          $estado = 'Incidencia abierta';
          break;
          case 'RECEIVED':
            $estado = 'Recibido';
            break;
            case 'REFUNDED':
              $estado = 'Reembolsado';
              break;
              case 'SHIPPED':
                $estado = 'Enviado';
                break;

        default:
      $estado = $this->estado;
          break;
      }
        return $estado;
    }

    /**
     * Set the value of Estado
     *
     * @param mixed $estado
     *
     * @return self
     */
    public function setEstado($estado)
    {
        $this->estado = $estado;

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

    /**
     * Get the value of Comision
     *
     * @return mixed
     */
    public function getSkuoferta()
    {
        return $this->sku_oferta;
    }

    /**
     * Set the value of Comision
     *
     * @param mixed $comision
     *
     * @return self
     */
    public function setSkuoferta($sku_oferta)
    {
        $this->mpn = $sku_oferta;

        return $this;
    }




    public function jsonSerialize():mixed {
      return [
        'cancelaciones' => self::getCancelaciones(),
        'importe' => self::getImporte(),
        'promociones' => self::getPromociones(),
        'cantidad' => self::getCantidad(),
        'abonos' => self::getAbonos(),
        'portes' => self::getPortes(),
        'impuestos' => self::getImpuestos(),
        'total_linea' => self::getTotalLinea(),
        'historico' => self::getHistorico(),
        'cod_categoria' => self::getCodCategoria(),
        'etiqueta_categoria' => self::getEtiquetaCategoria(),
        'sku' => self::getSku(),
        'mpn' => self::getSkuoferta(),                                                //Duplicamos para tener el mpn de cualqueir fuente
        'nombre' => self::getNombre(),
        'id' => self::getId(),
        'sku_oferta' => self::getSkuoferta(),
        'estado' => self::getEstado(),
        'comision' => self::getComision(),
        'atributos_bd'  => self::getAtributosBd()
      ];
    }



}


 ?>

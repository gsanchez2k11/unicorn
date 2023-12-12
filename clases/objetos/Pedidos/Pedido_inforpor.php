<?php
namespace unicorn\clases\objetos\Pedidos;
require_once('Linea_Pedido_inforpor.php');
require_once RAIZ . '/clases/funciones/inforpor/Otros.php';
use unicorn\clases\objetos\Pedidos\Linea_Pedido_inforpor as linea_pedido_inforpor;
use unicorn\clases\funciones\inforpor\Otros as otros;
use JsonSerializable;

/**
 *
 */
class Pedido_inforpor implements JsonSerializable
{
private $Agencia;
private $CodErr;
private $DirEnvio;
private $env;
private $estado;
private $expedicion;
private $lineasPedR;
private $numpedInf;
private $numpedCli;
private $observaciones;
private $portes;
private $total;
private $fecha_creado;

  function __construct($row)
  {
$this->CodErr = $row['CodErr'];
$this->Agencia = isset($row['Agencia']) ? $row['Agencia'] : '';
$this->DirEnvio = isset($row['DirEnvio']) ? $row['DirEnvio'] : '';
$this->env = isset($row['env']) ? $row['env'] : '';
$this->estado = isset($row['estado']) ? $row['estado'] : '';
$this->expedicion = isset($row['expedicion']) ?  $row['expedicion'] :'';
//$this->numero = isset($row['numero']) ? $row['numero'] :'';
$this->numpedInf = isset($row['numpedInf']) ? $row['numpedInf'] : '';
$this->numpedCli = isset($row['numpedCli']) ? $row['numpedCli'] :'';
$this->observaciones = isset($row['observaciones']) ? $row['observaciones'] :'';
$this->portes = isset($row['portes']) ? $row['portes'] :'';
$this->total = isset($row['total']) ? $row['total'] :'';
$this->fecha_creado = isset($row['fecha']) ? $row['fecha'] : '' ;

if (isset($row['lineasPedR']['LinPedR'])) {
$lineas_pedido = otros::ConvertirArrayUniMulti($row['lineasPedR']['LinPedR']);  //standarizamos el array de lineas de pedido
foreach ($lineas_pedido as $linea) {
$obj_linea[] = new linea_pedido_inforpor($linea);
}
} else {
  $obj_linea = array();
}
$this->lineasPedR = $obj_linea;

  }




    /**
     * Get the value of Agencia
     *
     * @return mixed
     */
    public function getAgencia()
    {
        return $this->Agencia;
    }

    /**
     * Set the value of Agencia
     *
     * @param mixed $Agencia
     *
     * @return self
     */
    public function setAgencia($Agencia)
    {
        $this->Agencia = $Agencia;

        return $this;
    }

    /**
     * Get the value of Cod Err
     *
     * @return mixed
     */
    public function getCodErr()
    {
        return $this->CodErr;
    }

    /**
     * Set the value of Cod Err
     *
     * @param mixed $CodErr
     *
     * @return self
     */
    public function setCodErr($CodErr)
    {
        $this->CodErr = $CodErr;

        return $this;
    }

    /**
     * Get the value of Dir Envio
     *
     * @return mixed
     */
    public function getDirEnvio()
    {
        return $this->DirEnvio;
    }

    /**
     * Set the value of Dir Envio
     *
     * @param mixed $DirEnvio
     *
     * @return self
     */
    public function setDirEnvio($DirEnvio)
    {
        $this->DirEnvio = $DirEnvio;

        return $this;
    }

    /**
     * Get the value of Env
     *
     * @return mixed
     */
    public function getEnv()
    {
        return $this->env;
    }

    /**
     * Set the value of Env
     *
     * @param mixed $env
     *
     * @return self
     */
    public function setEnv($env)
    {
        $this->env = $env;

        return $this;
    }

    /**
     * Get the value of Estado
     *
     * @return mixed
     */
    public function getEstado()
    {
        return $this->estado;
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
     * Get the value of Expedicion
     *
     * @return mixed
     */
    public function getExpedicion()
    {
        return $this->expedicion;
    }

    /**
     * Set the value of Expedicion
     *
     * @param mixed $expedicion
     *
     * @return self
     */
    public function setExpedicion($expedicion)
    {
        $this->expedicion = $expedicion;

        return $this;
    }

    /**
     * Get the value of Lineas Ped
     *
     * @return mixed
     */
    public function getLineasPedR()
    {
        return $this->lineasPedR;
    }

    /**
     * Set the value of Lineas Ped
     *
     * @param mixed $lineasPedR
     *
     * @return self
     */
    public function setLineasPedR($lineasPedR)
    {
        $this->lineasPedR = $lineasPedR;

        return $this;
    }

    /**
     * Get the value of Numero
     *
     * @return mixed
     */
    public function getNumero()
    {
        return $this->numpedInf;
    }

    /**
     * Set the value of Numero
     *
     * @param mixed $numero
     *
     * @return self
     */
    public function setNumero($numpedInf)
    {
        $this->numpedInf = $numpedInf;

        return $this;
    }

    /**
     * Get the value of Numped Cli
     *
     * @return mixed
     */
    public function getNumpedCli()
    {
        return $this->numpedCli;
    }

    public function getRefCliente()
    {

        return array(
            'nombre_completo' => $this->numpedCli
        );
    }

    /**
     * Set the value of Numped Cli
     *
     * @param mixed $numpedCli
     *
     * @return self
     */
    public function setNumpedCli($numpedCli)
    {
        $this->numpedCli = $numpedCli;

        return $this;
    }

    /**
     * Get the value of Observaciones
     *
     * @return mixed
     */
    public function getObservaciones()
    {
        return $this->observaciones;
    }

    /**
     * Set the value of Observaciones
     *
     * @param mixed $observaciones
     *
     * @return self
     */
    public function setObservaciones($observaciones)
    {
        $this->observaciones = $observaciones;

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
     * Get the value of Total
     *
     * @return mixed
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * Set the value of Total
     *
     * @param mixed $total
     *
     * @return self
     */
    public function setTotal($total)
    {
        $this->total = $total;

        return $this;
    }
        /**
     * Get the value of Total
     *
     * @return mixed
     */
    public function getFecha_Creado()
    {
        return $this->fecha_creado;
    }

    public function jsonSerialize():mixed {

      return [
        'Agencia' => self::getAgencia(),
        'CodErr' => self::getCodErr(),
        'DirEnvio' => self::getDirEnvio(),
        'env' => self::getEnv(),
        'estado' => self::getEstado(),
        'expedicion' => self::getExpedicion(),
        'lineas_pedido' => self::getLineasPedR(),
        'id' => self::getNumero(),
        'numpedCli' => self::getNumpedCli(),
        'direccion_factura' => self::getRefCliente(),//Adaptamos esto para mostrar la referencia que hemos puesto a pedido en los listados como si fuera el nombre del cliente
        'observaciones' => self::getObservaciones(),
        'portes' => self::getPortes(),
        'total' => self::getTotal(),
        'fecha_creado' => self::getFecha_Creado()
      ];
    }


}


 ?>

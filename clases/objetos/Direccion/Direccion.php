<?php
namespace unicorn\clases\objetos\Direccion;
use JsonSerializable;
/**
 *
 */
class Direccion implements JsonSerializable
{
private $nombre_completo;
private $nif;
private $empresa;
private $direccion;
private $ciudad;
private $codigo_postal;
private $telefono;
private $provincia;
private $email;


  function __construct($row)
  {
$this->nombre_completo = $row['nombre_completo'];
$this->nif = $row['nif'];
$this->empresa = $row['empresa'];
$this->direccion = $row['direccion'];
$this->ciudad = $row['ciudad'];
$this->codigo_postal = $row['codigo_postal'];
$this->telefono = $row['telefono'];
$this->provincia = $row['provincia'];
$this->email = isset($row['email']) ? $row['email'] : '';
$this->empresa = isset($row['empresa']) ? $row['empresa'] : '';
  }

  public function jsonSerialize():mixed {

    return [
      'nombre_completo' => self::getNombreCompleto(),
      'nif' => self::getNif(),
      'empresa' => self::getEmpresa(),
      'direccion' => self::getDireccion(),
      'ciudad' => self::getCiudad(),
      'codigo_postal' => self::getCodigoPostal(),
      'telefono' => self::getTelefono(),
      'provincia' => self::getProvincia(),
      'email' => self::getEmail()
    ];
  }



    /**
     * Get the value of Nombre Completo
     *
     * @return mixed
     */
    public function getNombreCompleto()
    {
        return $this->nombre_completo;
    }

    /**
     * Set the value of Nombre Completo
     *
     * @param mixed $nombre_completo
     *
     * @return self
     */
    public function setNombreCompleto($nombre_completo)
    {
        $this->nombre_completo = $nombre_completo;

        return $this;
    }

    /**
     * Get the value of Nif
     *
     * @return mixed
     */
    public function getNif()
    {
      $nif_mayuscula = strtoupper($this->nif);
        return $nif_mayuscula;
    }

    /**
     * Set the value of Nif
     *
     * @param mixed $nif
     *
     * @return self
     */
    public function setNif($nif)
    {
        $this->nif = $nif;

        return $this;
    }

    /**
     * Get the value of Empresa
     *
     * @return mixed
     */
    public function getEmpresa()
    {
        return $this->empresa;
    }

    /**
     * Set the value of Empresa
     *
     * @param mixed $empresa
     *
     * @return self
     */
    public function setEmpresa($empresa)
    {
        $this->empresa = $empresa;

        return $this;
    }
    /**
     * Get the value of Empresa
     *
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set the value of Empresa
     *
     * @param mixed $empresa
     *
     * @return self
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get the value of Direccion
     *
     * @return mixed
     */
    public function getDireccion()
    {
        return $this->direccion;
    }

    /**
     * Set the value of Direccion
     *
     * @param mixed $direccion
     *
     * @return self
     */
    public function setDireccion($direccion)
    {
        $this->direccion = $direccion;

        return $this;
    }

    /**
     * Get the value of Ciudad
     *
     * @return mixed
     */
    public function getCiudad()
    {
        return $this->ciudad;
    }

    /**
     * Set the value of Ciudad
     *
     * @param mixed $ciudad
     *
     * @return self
     */
    public function setCiudad($ciudad)
    {
        $this->ciudad = $ciudad;

        return $this;
    }

    /**
     * Get the value of Codigo Postal
     *
     * @return mixed
     */
    public function getCodigoPostal()
    {
        return $this->codigo_postal;
    }

    /**
     * Set the value of Codigo Postal
     *
     * @param mixed $codigo_postal
     *
     * @return self
     */
    public function setCodigoPostal($codigo_postal)
    {
        $this->codigo_postal = $codigo_postal;

        return $this;
    }

    /**
     * Get the value of Telefono
     *
     * @return mixed
     */
    public function getTelefono()
    {
        return $this->telefono;
    }

    /**
     * Set the value of Telefono
     *
     * @param mixed $telefono
     *
     * @return self
     */
    public function setTelefono($telefono)
    {
        $this->telefono = $telefono;

        return $this;
    }

    /**
     * Get the value of Provincia
     *
     * @return mixed
     */
    public function getProvincia()
    {
        return $this->provincia;
    }

    /**
     * Set the value of Provincia
     *
     * @param mixed $provincia
     *
     * @return self
     */
    public function setProvincia($provincia)
    {
        $this->provincia = $provincia;

        return $this;
    }

}

 ?>

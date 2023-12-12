<?php
/**
 * Clase proveedores
 */
class Proveedor
{
    public $id;
    public $codigo;
    public $nombre;
    public $imagen;
    public $descripcion;
    public $observaciones;
    public $portes;
    public $envio_directo;
    public $contacto;
    public $web;
    public $drive;
    public $responsable;
    public $fuente_datos;
    public $hora_limite_pedidos;
    public $vacaciones;
    public $perioricidad_tarifa;
    public $minimo_portes_gratis;

    public function getid()
    {return $this->id;}
    public function getcodigo()
    {return $this->codigo;}
    public function getnombre()
    {return $this->nombre;}
    public function getimagen()
    {return $this->imagen;}
    public function getdescripcion()
    {return $this->descripcion;}
    public function getobservaciones()
    {return $this->observaciones;}
    public function getportes()
    {return $this->portes;}
    public function getenvio_directo()
    {return $this->envio_directo;}
    public function getcontacto()
    {return $this->contacto;}
    public function getweb()
    {return $this->web;}
    public function getdrive()
    {return $this->drive;}
    public function getresponsable()
    {return $this->responsable;}
    public function getfuente_datos()
    {return $this->fuente_datos;}
    public function gethora_limite_pedidos()
    {return $this->hora_limite_pedidos;}
    public function getvacaciones()
    {return $this->vacaciones;}
    public function getperioricidad_tarifa()
    {return $this->perioricidad_tarifa;}
    public function getminimo_portes_gratis()
    {return $this->minimo_portes_gratis;}

    public function __construct($row)
    {
        $this->id     = $row['id'];
        $this->codigo = $row['codigo'];
        $this->nombre = $row['nombre'];
        $this->imagen = $row['codigo'] . '.jpg';
        if (isset($row['descripcion'])) $this->descripcion = $row['descripcion'];
        if (isset($row['observaciones'])) $this->observaciones  = $row['observaciones'];
        if (isset($row['portes'])) $this->portes  = $row['portes'];
      $this->envio_directo = $row['envio_directo'];
        if (isset($row['contacto'])) $this->contacto = $row['contacto'];
        if (isset($row['web'])) $this->web = $row['web'];
        if (isset($row['drive'])) $this->drive = $row['drive'];
        if (isset($row['responsable'])) $this->responsable = $row['responsable'];
        if (isset($row['fuente_datos'])) $this->fuente_datos = $row['fuente_datos'];
        if (isset($row['hora_limite_pedidos'])) $this->hora_limite_pedidos = $row['hora_limite_pedidos'];
        if (isset($row['vacaciones'])) $this->vacaciones = $row['vacaciones'];
        if (isset($row['perioricidad_tarifa'])) $this->perioricidad_tarifa = $row['perioricidad_tarifa'];
        if (isset($row['minimo_portes_gratis'])) $this->minimo_portes_gratis = $row['minimo_portes_gratis'];
    }
}

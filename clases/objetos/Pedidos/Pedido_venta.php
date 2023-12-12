<?php

namespace unicorn\clases\objetos\Pedidos;

require_once('Pedido.php');

use unicorn\clases\objetos\Pedidos\Pedido as pedido;

/**
 *
 */
abstract class Pedido_venta extends Pedido
{
    protected $fecha_creado;
    protected $direccion_factura;
    protected $direccion_envio;
    protected $nif;
    protected $nombre_apellidos;
    protected $total_pedido;
    protected $id;
    protected $estado;
    protected $envio;
    protected $lineas_pedido;
    protected $portes_pedido;
    protected $tienda;
    protected $DirEnvioIgualFactura;

    /**
     * Get the value of Fecha Creado
     *
     * @return mixed
     */
    public function getDirEnvioIgualFactura()
    {

        return $this->direccion_factura == $this->direccion_envio ? true : false;
    }

    /**
     * Set the value of Fecha Creado
     *
     * @param mixed $fecha_creado
     *
     * @return self
     */
    public function setDirEnvioIgualFactura($DirEnvioIgualFactura)
    {
        $this->DirEnvioIgualFactura = $DirEnvioIgualFactura;
    }


    /**
     * Get the value of Fecha Creado
     *
     * @return mixed
     */
    public function getFechaCreado()
    {
        return $this->fecha_creado;
    }

    /**
     * Set the value of Fecha Creado
     *
     * @param mixed $fecha_creado
     *
     * @return self
     */
    public function setFechaCreado($fecha_creado)
    {
        $this->fecha_creado = $fecha_creado;

        return $this;
    }

    /**
     * Get the value of Direccion Factura
     *
     * @return mixed
     */
    public function getDireccionFactura()
    {
        return $this->direccion_factura;
    }

    /**
     * Set the value of Direccion Factura
     *
     * @param mixed $direccion_factura
     *
     * @return self
     */
    public function setDireccionFactura($direccion_factura)
    {
        $this->direccion_factura = $direccion_factura;

        return $this;
    }

    /**
     * Get the value of Direccion Envio
     *
     * @return mixed
     */
    public function getDireccionEnvio()
    {
        return $this->direccion_envio;
    }

    /**
     * Set the value of Direccion Envio
     *
     * @param mixed $direccion_envio
     *
     * @return self
     */
    public function setDireccionEnvio($direccion_envio)
    {
        $this->direccion_envio = $direccion_envio;

        return $this;
    }

    /**
     * Get the value of Direccion Envio
     *
     * @return mixed
     */
    public function getNif()
    {
        return $this->nif;
    }

    /**
     * Set the value of Direccion Envio
     *
     * @param mixed $direccion_envio
     *
     * @return self
     */
    public function setNif($nif)
    {
        $this->nif = $nif;

        return $this;
    }


    /**
     * Get the value of Nombre Apellidos
     *
     * @return mixed
     */
    public function getNombreApellidos()
    {
        return $this->nombre_apellidos;
    }

    /**
     * Set the value of Nombre Apellidos
     *
     * @param mixed $nombre_apellidos
     *
     * @return self
     */
    public function setNombreApellidos($nombre_apellidos)
    {
        $this->nombre_apellidos = $nombre_apellidos;

        return $this;
    }

    /**
     * Get the value of Total Pedido
     *
     * @return mixed
     */
    public function getTotalPedido()
    {
        return $this->total_pedido;
    }

    /**
     * Set the value of Total Pedido
     *
     * @param mixed $total_pedido
     *
     * @return self
     */
    public function setTotalPedido($total_pedido)
    {
        $this->total_pedido = $total_pedido;

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
            case 'canceled':
                $estado = 'cancelado';
                break;
            case 'payment_review':
                $estado = 'Pendiente de confirmación';
                break;
            case 'pending_payment':
                $estado = 'Pendiente de pago';
                break;
            case 'processing':
                $estado = 'En proceso';
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
     * Get the value of Envio
     *
     * @return mixed
     */
    public function getEnvio()
    {
        return $this->envio;
    }

    /**
     * Set the value of Envio
     *
     * @param mixed $envio
     *
     * @return self
     */
    public function setEnvio($envio)
    {
        $this->envio = $envio;

        return $this;
    }
    /**
     * Get the value of Lineas Pedido
     *
     * @return mixed
     */
    public function getLineasPedido()
    {
        return $this->lineas_pedido;
    }

    /**
     * Set the value of Lineas Pedido
     *
     * @param mixed $lineas_pedido
     *
     * @return self
     */
    public function setLineasPedido($lineas_pedido)
    {
        $this->lineas_pedido = $lineas_pedido;

        return $this;
    }



    /**
     * Get the value of Portes Pedido
     *
     * @return mixed
     */
    public function getPortesPedido()
    {
        return $this->portes_pedido;
    }

    /**
     * Set the value of Portes Pedido
     *
     * @param mixed $portes_pedido
     *
     * @return self
     */
    public function setPortesPedido($portes_pedido)
    {
        $this->portes_pedido = $portes_pedido;

        return $this;
    }

    /**
     * Get the value of Tienda
     *
     * @return mixed
     */
    public function getTienda()
    {
        return $this->tienda;
    }

    /**
     * Set the value of Tienda
     *
     * @param mixed $tienda
     *
     * @return self
     */
    public function setTienda($tienda)
    {
        $this->tienda = $tienda;

        return $this;
    }
}

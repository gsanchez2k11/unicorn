<?php
namespace unicorn\clases\objetos\Articulos;
/**
* Compras de articulos a proveedor
*/
error_reporting(E_ALL);
ini_set('display_errors', 'on');
//require_once __DIR__ . '/../../config.php';
//require_once RAIZ . '/clases/objetos/Articulo.php'; // La clase compraarticulo
//require_once RAIZ . '/clases/funciones/Bdfav3.php'; // La clase compraarticulo
require_once RAIZ . '/clases/funciones/unicorn_db/Proveedores.php';
use unicorn\clases\funciones\unicorn_db\Proveedores as proveedores;
require_once RAIZ . '/clases/funciones/unicorn_db/Entidad.php';
use unicorn\clases\funciones\unicorn_db\Entidad as entidad;
class Compra_articulo
{

  private $id; // Id de la compra
  public $codigo; // Código del proveedor
  public $nombre; // Nombre del proveedor
  public $referencia_proveedor; //Referencia del artículo en el proveedor
  // private $referencia;
  public $precio;
  private $nuevo_precio;
  public $lpi;
  public $stock;
  public $stock_reserva;
  public $stock_custodia;
  public $fecha_actualizado;
  private $fin_cotizacion;
  private $urldoc;
  public $pvp;
  public $medio;
  public $codigo_promocion;
  // private $entidad; //Entidad de la tabla articulos_entidad a la que hace referencia la compra
  private $alias;
  public $precio_venta;
  public $portes;
public $custodias;
  public function getid()
  {return $this->id;}
  public function getPortes()
  {return $this->portes;}
  public function getcodigo()
  {
    //Obternemos el código del proveedor a partir del nombre
    if (!isset($this->codigo) && isset($this->nombre)) {
      $proveedor = proveedores::obtieneProveedoresBd($this->nombre);
      if (!empty($proveedor)) {
        $cod_proveedor = $proveedor[0]->getid();
      } else {
        $cod_proveedor = 18;
      }

    } else {
      $cod_proveedor = $this->codigo;
    }
    return $cod_proveedor;
  }
  public function getnombre()
  {return $this->nombre;}
  public function getreferenciaproveedor()
  {return $this->referencia_proveedor;}
  /*public function getreferencia()
  {return $this->referencia;}*/
  public function getprecio()
  {return $this->precio;}
  public function getnuevo_precio()
  {return $this->nuevo_precio;}
  public function getnuevo_precio_moneda()
  {return money_format('%.2n€',  $this->nuevo_precio);}
  public function getlpi()
  {return $this->lpi;}
  public function getTotalCompra() {
    if (isset($this->lpi)) {
      $precio = $this->lpi + $this->precio;
    } else {
      $precio = $this->precio;
    }
    return $precio;

  }

  public function getstock()
  {
    return $this->stock;
  }
  public function getstockReserva()
  {
    return $this->stock_reserva;
  }
  public function getstockCustodia()
  {
    return $this->stock_custodia;
  }
  public function getprecio_venta()
  {return $this->precio_venta;}

  public function getfecha_actualizado()
  {
    if (isset($this->fecha_actualizado)) {
      //Formateamos la fecha para el estilo español
      $fecha = explode("-", $this->fecha_actualizado);
      //Reordenamos los campos
      $fecha = $fecha[2] . '-' . $fecha[1] . '-' . $fecha[0];
    } else {
      $fecha = false;
    }
    return $fecha;
  }

  public function getfin_cotizacion()
  {
    if (isset($this->fin_cotizacion) && !is_null($this->fin_cotizacion)) {
      //Formateamos la fecha para el estilo español
      $fechafin = explode("-", $this->fin_cotizacion);
      //Reordenamos los campos
      $fechafin = $fechafin[2] . '-' . $fechafin[1] . '-' . $fechafin[0];
    } else {
      $fechafin = false;
    }
    return $fechafin;
  }
  public function geturldoc()
  {return $this->urldoc;}
  public function getmedio()
  {return $this->medio;}
  public function getcodigo_promocion()
  {return $this->codigo_promocion;}

  public function getentidad()
  {
    $entidad = entidad::getEntidadReferencia($this->referencia);
    return $entidad;
  }
  public function getalias()
  {return $this->alias;}

  /**
  * [__construct description]
  * @param [type] $row [description]
  */
  public function __construct($row)
  {
    $this->referencia = $row['referencia'];                                         //Referencia del artículo
    $this->entidad = entidad::getEntidadReferencia($this->referencia);               //Obtenemos la entidad a partir de la referencia
    if (empty($this->entidad)) {                                                        //Si no hay entidad para la referencia añadimos una
      $this->entidad = entidad::addEntidad($this->referencia);
    }
    if (isset($row['referencia_proveedor'])) {
      $this->referencia_proveedor = $row['referencia_proveedor'];
    } elseif(isset($row['alias'])) {
      $this->referencia_proveedor = $row['alias'][0];
    }


    if (isset($row['id'])) {
      $this->id = $row['id'];
    }
    if (isset($row['codigo'])) {
      $this->codigo = $row['codigo'];
    }
    if (isset($row['nombre'])) {
      $this->nombre = $row['nombre'];
    }
    if (isset($row['precio'])) {
      $this->precio = $row['precio'];
    }
    if (isset($row['nuevo_precio'])) {
      $this->nuevo_precio = $row['nuevo_precio'];
    } else {
      $this->nuevo_precio = NULL;
    }

    if (isset($row['lpi'])) {
      $this->lpi = $row['lpi'];
    }
    if (isset($row['stock'])) {
      $this->stock = $row['stock'];
    } else {
      $this->stock = NULL;
    }
    $this->stock_reserva = isset($row['stock_reserva']) ? $row['stock_reserva'] : NULL;
    $this->stock_custodia = isset($row['stock_custodia']) ? $row['stock_custodia'] : NULL;

    if (isset($row['fecha_actualizado'])) {
      $this->fecha_actualizado = $row['fecha_actualizado'];
    }
    if (isset($row['fin_cotizacion'])) {
      $this->fin_cotizacion = $row['fin_cotizacion'];
    }
    if (isset($row['urldoc'])) {
      $this->urldoc = $row['urldoc'];
    }
    if (isset($row['pvp'])) {
      $this->pvp = $row['pvp'];
    }
    if (isset($row['precio_venta'])) {
      $this->precio_venta = $row['precio_venta'];
    }
    if (isset($row['medio'])) {
      $this->medio = $row['medio'];
    }
    if (isset($row['codigo_promocion'])) {
      $this->codigo_promocion = $row['codigo_promocion'];
    }
    if (isset($row['portes'])) {
      $this->portes = $row['portes'];
    }
    /*                if (isset($row['entidad'])) {
    $this->entidad = $row['entidad'];
  }*/


  if (isset($row['alias'])) {
    $this->alias = $row['alias'];
  }
    if (isset($row['custodias'])) $this->custodias = $row['custodias'];

}
}

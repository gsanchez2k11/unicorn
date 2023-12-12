<?php
namespace unicorn\clases\funciones\unicorn_db;
require_once 'General.php';
require_once 'Operaciones_tablas.php';
use unicorn\clases\funciones\unicorn_db\Operaciones_tablas as operaciones;

/**
 *
 */
class Entidad extends General
{
public static function buscarEntidad($row) {
if (isset($row['ean']) || isset($row['mpn'])) {

  $sql = "SELECT * FROM articulos_entidad_varchar ";
  $sql .= " where ";
  if (isset($row['ean'])) {
  $sql .= " (atributo = '3' and valor = '" . $row['ean'] ."')";
  }
if (isset($row['ean']) && isset($row['mpn'])) {
  $sql .= " or ";
}
  if (isset($row['mpn'])) {
  $sql .= " (atributo = '2' and valor = '" . $row['mpn'] ."')";
  }
  if (isset($row['mpn2'])) {
    $sql .= " or (atributo = '2' and valor = '" . $row['mpn2'] ."')";
    }

} elseif(isset($row['sku_pcc']) || isset($row['codinf']) || isset($row['sku_phh']) || isset($row['sku_fnac'])){
  if (isset($row['sku_pcc'])) {
  $sql = "SELECT * FROM articulos_entidad_int ";
  $sql .= " where ";
  $sql .= " (atributo = '7' and valor = '" . $row['sku_pcc'] ."')";
} elseif (isset($row['codinf'])) {
  $sql = "SELECT * FROM articulos_entidad_int ";
  $sql .= " where ";
  $sql .= " (atributo = '5' and valor = '" . $row['codinf'] ."')";
} elseif (isset($row['sku_phh'])) {
  $sql = "SELECT * FROM articulos_entidad_int ";
  $sql .= " where ";
  $sql .= " (atributo = '23' and valor = '" . $row['sku_mediamarkt'] ."')";
} elseif (isset($row['sku_phh'])) {
  $sql = "SELECT * FROM articulos_entidad_int ";
  $sql .= " where ";
  $sql .= " (atributo = '11' and valor = '" . $row['sku_phh'] ."')";
} elseif (isset($row['sku_fnac'])) {
  $sql = "SELECT * FROM articulos_entidad_int ";
  $sql .= " where ";
  $sql .= " (atributo = '10' and valor = '" . $row['sku_fnac'] ."')";
}
}

  $sql .= ";";
  $resultado       = self::ejecutaConsulta($sql);

    if ($resultado) {
        $row = $resultado->fetch(\PDO::FETCH_ASSOC); //Usando PDO::FETCH_ASSOC devuelve el resultado como array asociativo
    }
    return $row;
    //return $sql;
}
//Devuelve
public static function listarEntidadesConAtributos($cod_atributo){
$tabla = self::dameTablaAtributos($cod_atributo);
$sql = "SELECT aev.valor as mpn, aei.valor as stock from " .  $tabla . " aei" ;
$sql .= " left join articulos_entidad_varchar aev ";
$sql .= " using (entidad) ";
$sql .= " where aei.atributo = '" . $cod_atributo . "'; ";
$sql .= " and aev.atributo = '2';";
$resultado       = self::ejecutaConsulta($sql);
$entidades = array();
if ($resultado) {
    $row = $resultado->fetch(\PDO::FETCH_ASSOC); //Usando PDO::FETCH_ASSOC devuelve el resultado como array asociativo
    while ($row != null) {
        $entidades[] = $row;
        $row               = $resultado->fetch(\PDO::FETCH_ASSOC);
    }
}
return $entidades;
}
public static function insertaArticuloEntidad($referencia) {
  $sql = "INSERT IGNORE INTO articulos_entidad (referencia)";
  $sql .= " VALUES('" . $referencia . "');";
  $resultado = self::ejecutaConsulta($sql);
  if ($resultado) {
    $sql = "SELECT id from articulos_entidad ";
    $sql .= " where referencia = '" . $referencia . "'";
    $sql .= " order by id desc limit 1;";
    $resultado = self::ejecutaConsulta($sql);
    $resultado = $resultado->fetch(\PDO::FETCH_ASSOC);
  }
  return $resultado;
}

public static function buscarArticuloEntidadInt($atributo,$valor) {
$sql = "SELECT entidad FROM articulos_entidad_int ";
$sql .= " where atributo = '" . $atributo ."' and valor = '" .  $valor ."';";
$resultado       = self::ejecutaConsulta($sql);
$entidades = array();
if ($resultado) {
    $row = $resultado->fetch(\PDO::FETCH_ASSOC); //Usando PDO::FETCH_ASSOC devuelve el resultado como array asociativo
    while ($row != null) {
        $entidades[] = $row['entidad'];
        $row               = $resultado->fetch(\PDO::FETCH_ASSOC);
    }
}
return $entidades;
}

public static function insertaArticuloEntidadInt($entidad,$atributo,$valor,$tabla = 'articulos_entidad_int'){
$sql = "INSERT INTO " . $tabla . "(entidad,atributo,valor)";
$sql .= " VALUES('" . $entidad . "','" . $atributo . "','" . $valor . "') ";
//$sql .= " on duplicate key update atributo = '". $atributo ."' and valor = '" . $valor . "';";
$sql .= " on duplicate key update valor = '" . $valor . "';";
//print_r($sql);
$resultado = self::ejecutaConsulta($sql);
return $resultado;
}

public static function actualizaArticuloEntidadInt($entidad,$atributo,$valor){
  //Actualiza un registro en la tabla
 $sql = "UPDATE articulos_entidad_int SET valor = '" . $valor . "'";
$sql .= " where entidad = '" . $entidad . "' and atributo ='" . $atributo  . "';";

$resultado = self::ejecutaConsulta($sql);
return $resultado;
}

public static function dameValorArticuloEntidadInt($entidad, $atributo) {
  $sql = "SELECT valor FROM articulos_entidad_int ";
  $sql .= " where entidad = '" . $entidad  . "' and atributo = '" . $atributo ."' ;";
  $resultado       = self::ejecutaConsulta($sql);
  $valor = array();
  if ($resultado) {
      $row = $resultado->fetch(\PDO::FETCH_ASSOC); //Usando PDO::FETCH_ASSOC devuelve el resultado como array asociativo
      while ($row != null) {
          $valor[] = $row['valor'];
          $row               = $resultado->fetch(\PDO::FETCH_ASSOC);
      }
  }
  if (isset($valor[0])) {
    $resultado = $valor[0];
  } else {
    $resultado = $valor;
  }
  return $resultado;
}

/**
 * Busca entradas en la tabla para la entidad y el atributo dados
 * @param  [int] $entidad  entidad del artículo en la bbdd
 * @param  [int] $atributo atributo que estamos buscando, de la tabla lista_atributos
 * @return [type]           Devuelve un string o un objeto PDOStatement si no encuentra nada
 * Tal y como está ahora sólo devuelve la primera ocurrencia para la búsqueda, ojo con esto
 */
public static function dameValorArticuloEntidadVarchar($entidad, $atributo) {
  $resultado = '';
  $sql = "SELECT valor FROM articulos_entidad_varchar ";
  $sql .= " where entidad = '" . $entidad  . "' and atributo = '" . $atributo ."' ;";
  $resultado       = self::ejecutaConsulta($sql);
  $valor = array();
  if ($resultado) {
      $row = $resultado->fetch(\PDO::FETCH_ASSOC); //Usando PDO::FETCH_ASSOC devuelve el resultado como array asociativo
      while ($row != null) {
          $valor[] = $row['valor'];
          $row               = $resultado->fetch(\PDO::FETCH_ASSOC);
      }
  }
  if (isset($valor[0])) {
    $resultado = $valor[0];
  }
  return $resultado;
}

public static function insertaArticuloEntidadVarchar($entidad,$atributo,$valor) {
  $sql = "INSERT INTO articulos_entidad_varchar (entidad,atributo,valor)";
  $sql .= " VALUES('" . $entidad  . "','" . $atributo . "','" . $valor . "') ";
  $sql .= " on duplicate key update valor = '" . $valor . "';";
  $resultado = self::ejecutaConsulta($sql);

  return $resultado;
}
public static function actualizaArticuloEntidadVarchar($entidad,$atributo,$valor) {
  $sql = "UPDATE articulos_entidad_varchar ";
  $sql .= " SET valor = '" . $valor . "' where entidad = '" .  $entidad . "' and atributo = '" . $atributo . "' ;";
  $resultado = self::ejecutaConsulta($sql);

  return $resultado;
}
public static function eliminaArticuloEntidadVarchar($entidad,$atributo) {
  $sql = "DELETE FROM articulos_entidad_varchar ";
  $sql .= " where entidad = '" .  $entidad . "' and atributo = '" . $atributo . "' ;";
  $resultado = self::ejecutaConsulta($sql);

  return $resultado;
}


public static function crearEntidad($row) {
$referencia = isset($row['ean']) ? $row['ean'] : $row['mpn'];

$inserta_entidad = self::insertaArticuloEntidad($referencia);
$entidad = $inserta_entidad['id'];

if (isset($row['ean'])) {
$inserta_aev = self::insertaArticuloEntidadVarchar($entidad,'3',$row['ean']);
}
if (isset($row['mpn'])) {
$inserta_aev = self::insertaArticuloEntidadVarchar($entidad,'2',$row['mpn']);
}
if (isset($row['cod_inforpor'])) {
  $inserta_aev = self::insertaArticuloEntidadVarchar($entidad,'5',$row['cod_inforpor']);
  }
if (isset($row['nombre'])) {
$inserta_aev = self::insertaArticuloEntidadVarchar($entidad,'4',$row['nombre']);
}
if (isset($row['sku_pcc'])) {
$inserta_aev = self::insertaArticuloEntidadInt($entidad,'7',$row['sku_pcc']);
}
if (isset($row['sku_fnac'])) {
$inserta_aev = self::insertaArticuloEntidadInt($entidad,'10',$row['sku_fnac']);
}

return $entidad;
}

public static function actualizarEntidad($row) {
  $entidad = $row['entidad'];
  $atributo = $row['atributo'];
  $valor = $row['valor'];

  //Primero buscamos el tipo de atributo que vamos a grabar
$tipo = self::dameTablaAtributos($atributo);
$sql = "INSERT INTO " . $tipo . "(entidad,atributo,valor)";
$sql .= " VALUES('" . $entidad  . "','" . $atributo . "','" . $valor . "') ";
$sql .= " on duplicate key update valor = '" . $valor . "';";
$resultado = self::ejecutaConsulta($sql);

return $resultado;

}


public static function gestionaEntidad($row){
  //Buscamos la entidad
  $buscar = self::buscarEntidad($row);
  $resultado = $buscar['entidad'];
//Si no existe la creamos
  if (empty($buscar)) {
$insertar = self::crearEntidad($row);
$resultado = $insertar;
  }
  return $resultado;
}

public static function dameAtributosEntidad($entidad) {

//  $sql = "SET @entity_id = '" . $entidad . "';";
$sql = "(SELECT aei.atributo as atributo, aei.valor as valor from articulos_entidad ae
left JOIN articulos_entidad_int aei on (ae.id = aei.entidad)
where ae.id = '" . $entidad . "')
UNION
(select aed.atributo as atributo, aed.valor as valor from articulos_entidad ae
 LEFT JOIN articulos_entidad_decimal aed on (ae.id = aed.entidad)
where ae.id = '" . $entidad . "')
UNION
(select aev.atributo as atributo, aev.valor as valor from articulos_entidad ae
 LEFT JOIN articulos_entidad_varchar aev on (ae.id = aev.entidad)
where ae.id = '" . $entidad . "');";

  $resultado       = self::ejecutaConsulta($sql);
  $valor = array();
  if ($resultado) {
      $row = $resultado->fetch(\PDO::FETCH_ASSOC); //Usando PDO::FETCH_ASSOC devuelve el resultado como array asociativo
      while ($row != null) {
          $valor[$row['atributo']] = $row['valor'];
          $row               = $resultado->fetch(\PDO::FETCH_ASSOC);
      }
  }
  return $valor;
  //return  $resultado;
}


/**
 * [getEntidadReferencia description]
 * @param  [type] $referencia [description]
 * @return [type]             [description]
 */
    public static function getEntidadReferencia($referencia)
    {
        //Devuelve la entidad correspondiente a una
        $sql = "SELECT id FROM articulos_entidad"; //Referencia dada
        $sql .= " WHERE referencia ='" . $referencia . "'";
        $resultado = self::ejecutaConsulta($sql);
        $id        = $resultado->fetch();
        return $id[0];
    }


    /**
 * Añade una entidad con una referencia dada
 * @param [string] $referencia Referencia que queremos dar de alta
 */
    public static function addEntidad($referencia) {
            //Insertamos la entidad nueva
            $sql = "INSERT INTO articulos_entidad (referencia)";
            $sql .= " VALUES('" . $referencia . "')";
            $resultado = self::ejecutaConsulta($sql);
            //Volvemos a realizar la llamada despues de insertar
            $id = self::getEntidadReferencia($referencia);
            return $id;
    }


    private static function getAtributoporEntidad($array)
        {
         $entidad = $array['entidad'];
    $cod_atributo = $array['cod_atributo'];
            //Devuelve el part number (sku) de una referencia de
            // fabricante o alias dado
            $sql = "SELECT valor FROM articulos_entidad_int";                       //En este caso vamos a usar la tabla int
            $sql .= " WHERE entidad ='" . $entidad . "' and atributo ='". $cod_atributo ."'";
            $resultado   = self::ejecutaConsulta($sql);
            $valor   = $resultado->fetchAll(\PDO::FETCH_ASSOC);
        /*    $referencias = array();
            foreach ($entidades as $entidad) {
                $entidad       = self::getReferencia($entidad['entidad']);
                $referencias[] = $entidad['referencia'];
            }*/

            return $valor;
            //  Cambiamos para devolver un string con la referencia en lugar de un array
            //  return $referencia['referencia'];
        }



    /**
       * [getEntidadporAtributo description]
       * @param  [type] $valor [description]
       * @return [type]        [description]
       */
          public static function getAtributoporAtributo($array)
          {
           $valor = $array['valor'];
      $cod_atributo = $array['cod_atributo'];
      $atributo_buscado = $array['cod_atributo_buscado'];
              //Devuelve el part number (sku) de una referencia de
              // fabricante o alias dado
              $sql = "SELECT * FROM articulos_entidad_varchar";
              $sql .= " WHERE entidad = ANY (";
              $sql  .= " SELECT entidad FROM articulos_entidad_varchar";
              $sql .= " WHERE valor ='" . $valor . "' and atributo ='". $cod_atributo ."')";
              $sql .= " order by id;";
              $resultado   = self::ejecutaConsulta($sql);
              $entidades   = $resultado->fetchAll(\PDO::FETCH_ASSOC);
              $referencias = array();
              $_entidades = array();


  //Recorremos las entidades recibidas
              foreach ($entidades as $key => $fila) {
    //Si no tenemos creada la entrada en el array creamos la que debe contener el código de PcComponentes
                if (!isset($_entidades[$fila['entidad']])) {
                  $art = array(
                    'entidad' => $fila['entidad'],
                    'cod_atributo' => $atributo_buscado
                  );
                  $valor       = self::getAtributoporEntidad($art);
  if (!empty($valor)) {
                $_entidades[$fila['entidad']]['referencias'] = array(
  //La idea es escalar con todas las referencias que necesitemos
                    'pccomp' => $valor[0]['valor']
                );
              }
                }
  //Para cada par atributo/valor generamos una entrada en el array
                $_entidades[$fila['entidad']][] = array(
                  'atributo' => $fila['atributo'],
                  'valor' => $fila['valor']
                );
              }

              return $_entidades;

              //  Cambiamos para devolver un string con la referencia en lugar de un array
              //  return $referencia['referencia'];
          }

public static function buscarValorBruto(string $termino) {
  $sql = "SELECT entidad FROM articulos_entidad_varchar";
  $sql .= " WHERE valor LIKE '%" . $termino . "%' ";
$sql .= " UNION ";
$sql .= "SELECT entidad FROM articulos_entidad_int";
$sql .= " WHERE valor LIKE '%" . $termino . "%'; ";
  $resultado   = self::ejecutaConsulta($sql);
  $entidades   = array_column($resultado->fetchAll(\PDO::FETCH_ASSOC),'entidad');

  return $entidades;
}

/**
 * Buscamos en la tabla historico_pcc
 * @param  [type] $array               [description]
 * @return [type]        [description]
 */
public static function getHistorico($array)
    {
//Antes de cada consulta limpiamos la tabla para no mostrar los duplicados
operaciones::limpiaDuplicados();
     $entidad = $array['entidad'];
$cod_atributo = $array['cod_atributo'];
/*switch ($cod_atributo) {
  case '13':
$tabla = 'articulos_entidad_int';
    break;
    case '14':
  $tabla = 'articulos_entidad_decimal';
      break;
      default:
      $tabla = 'articulos_entidad_varchar';
      break;
}*/
$tabla = self::dameTablaAtributos($cod_atributo);
        //Devuelve el part number (sku) de una referencia de
        // fabricante o alias dado
        $sql = "SELECT DATE_FORMAT(fecha, '%Y-%m-%dT%TZ') as x,valor as y FROM historico_pcc";                       //En este caso vamos a usar la tabla int
        $sql .= " WHERE entidad ='" . $entidad . "' and atributo ='". $cod_atributo ."'";
        $sql .= " UNION ";
        $sql .= "SELECT now() as x,valor as y FROM " . $tabla;
        $sql .= " WHERE entidad ='" . $entidad . "' and atributo ='". $cod_atributo ."';";
        $resultado   = self::ejecutaConsulta($sql);
        $valor = array();
        if ($resultado) {
            $row = $resultado->fetch(\PDO::FETCH_ASSOC); //Usando PDO::FETCH_ASSOC devuelve el resultado como array asociativo
            while ($row != null) {
                $valor[] = $row;
                $row               = $resultado->fetch(\PDO::FETCH_ASSOC);
            }
        }
        return $valor;
    }
//Devuelve la tabla correspondiente al atributo que buscamos
public static function dameTablaAtributos($cod_atributo) {

  $sql = "SELECT tipo FROM lista_atributos ";
  $sql .= "where id = '" . $cod_atributo . "'";
$resultado   = self::ejecutaConsulta($sql);

if ($resultado) {
    $valor = $resultado->fetch(\PDO::FETCH_ASSOC); //Usando PDO::FETCH_ASSOC devuelve el resultado como array asociativo
    $tabla = 'articulos_entidad_' . $valor['tipo'];
}

return $tabla;
}

public static function dameIdAtributo($nombre_atributo) {
  $sql = "SELECT id FROM lista_atributos ";
  $sql .= "where nombre = '" . $nombre_atributo . "'";
$resultado   = self::ejecutaConsulta($sql);

if ($resultado) {
    $valor = $resultado->fetch(\PDO::FETCH_ASSOC); //Usando PDO::FETCH_ASSOC devuelve el resultado como array asociativo
}

//print_r($valor);
return $valor['id'];
}


}


 ?>

<?php
//Configuramos las opciones de errores
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once 'config.php.inc';
include 'clases/funciones/unicorn_db/Entidad.php';
 ?>
<?php
use unicorn\clases\funciones\inforpor\Pedido as pedido;
use unicorn\clases\funciones\mirakl\Ofertas as ofertas;
use unicorn\clases\funciones\magento\Articulos as articulo;
use unicorn\clases\funciones\google\Conectar as conectar;
use unicorn\clases\funciones\unicorn_db\Entidad as entidad;
$row['NumPedInf'] = '678869';
//$row['mpn'] = 'W2G50A';
//$codinfo = ofertas::listarOfertas();

$pedido = entidad::buscarArticuloEntidadInt('1','1');

echo "<pre>";
print_r($pedido);
echo "</pre>";
 ?>

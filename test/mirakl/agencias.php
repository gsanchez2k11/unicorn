<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . '/../../config.php.inc';
use unicorn\clases\funciones\mirakl\Agencias as agencias;
require RAIZ . '/clases/funciones/mirakl/Agencias.php';
?>
<?php
echo "aaaaa";
$lista_agencias = agencias::listarClaseslogisticas('phh');
echo "<pre>";
print_r($lista_agencias);
echo "</pre>";
 ?>

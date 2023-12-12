<?php
error_reporting(E_ALL);                                     //Activamos el reporte de errores
ini_set('display_errors', 'on');
require_once __DIR__ . '/../../config.php.inc';
require_once RAIZ . '/clases/funciones/unicorn_db/General.php';
use unicorn\clases\funciones\unicorn_db\General as general;
?>
<?php
$tabla = 'familias_margenes';
$arr = array(
    'id' => $_POST['id'],
    'campo' => 'margen',
    'valor' => rtrim($_POST['value'],'%')
);
$actualizar = general::actualizaRegistro($tabla, $arr);
if (gettype($actualizar) === 'object') {
    echo $_POST['value'];
}

/*echo '<pre>';
print_r($actualizar);
echo '</pre>';*/

//echo $valor;

 ?>
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . '/../../config.php.inc';
use unicorn\clases\funciones\mirakl\Platformsettings as pl;
require RAIZ . '/clases/funciones/mirakl/Platformsettings.php';
?>


<?php

$pl = pl::dameCampos();


echo "<pre>";
print_r($pl);
echo "</pre>";
 ?>

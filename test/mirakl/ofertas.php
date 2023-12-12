<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . '/../../config.php.inc';
use unicorn\clases\funciones\mirakl\Ofertas as ofertas;
require RAIZ . '/clases/funciones/mirakl/Ofertas.php';

require_once RAIZ . '/clases/funciones/unicorn_db/Entidad.php';
use unicorn\clases\funciones\unicorn_db\Entidad as entidad;
?>
<?php
$articulos[] = array(
'product_id' => "8715946689548",
'product_id_type' => "EAN",
'shop_sku' => "1069029",
'quantity' => 0,
'state_code' => 11,
'price' =>  "633.24",
'offer_additional_fields' => array(
  ['code' => "canon", 'type' => "NUMERIC", 'value' => '21'],
  ['code' => "tipo-iva", 'type' => "NUMERIC", 'value' => '0']
),
'logistic_class' => 'PA_MIDDLE',
'update_delete' => 'update'
);

 ?>
<?php

//$actualizar = ofertas::actualizaOferta($articulos, 'phh');
//$ofertas = ofertas::todasLasOfertas('phh');
//$info = ofertas::listarOfertas('pcc',1);
/*foreach ($info as $oferta) {
$row['ean'] = $oferta->getEan();
$atributos = entidad::buscarEntidad($row);
$entidad = $atributos['entidad'];
$atributo = '11';
$valor = $oferta->getProductSku();
//Insertamos el código de phone house
entidad::insertaArticuloEntidadInt($entidad,$atributo,$valor);
}*/


$csv = ofertas::dameCsv('mediamarkt');
echo "<pre>";
print_r($csv);
//print_r($oferta);
//print_r($ofertas);
//print_r($actualizar);
echo "</pre>";
 ?>

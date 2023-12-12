<?php
error_reporting(E_ALL);                                     //Activamos el reporte de errores
ini_set('display_errors', 'on');
require_once __DIR__ . '/../../config.php.inc';
require_once(RAIZ . '/vendor/autoload.php');
use unicorn\clases\funciones\inforpor\Tarifa as tarifa;
require RAIZ . '/clases/funciones/inforpor/Tarifa.php';
require_once RAIZ . '/clases/funciones/unicorn_db/Actualizacion.php';
use unicorn\clases\funciones\unicorn_db\Actualizacion as actualizacion;
$lista_articulos = array();
$articulos_ultima = array();
$plataformas = array(                                                           //Creamos un array con todas las plataformas donde tenemos artículos
  //'fnac',
  'pcc',
  //'phh',
  'mediamarkt',
  //'miravia',
  //'correos'
);

//$tarifa = tarifa::dameTarifa();                                                 //Cargamos la tarifa completa de inforpor
$tarifa_inforpor_json = tarifa::dameJSONTarifa(); //Cargamos la última tarifa
$tarifa = json_decode($tarifa_inforpor_json, true, 512); //la convertimos desde el JSON

/*echo '<pre>';
print_r($tarifa);
echo '</pre>';*/
/*foreach ($plataformas as  $plataforma) {
  $articulos_ultima[$plataforma] = actualizacion::dameDatosActualizacion($plataforma);
}*/


foreach ($tarifa as $articulo) {
  $esta_plataforma = array();
//Buscamos el articulo en cada plataforma
foreach ($articulos_ultima as $plataforma => $articulos_p) {
//$eans = array_column($articulos_p,'ean');
//$sku_tienda = isset(reset($articulos_p)['shop_sku']) ? array_column($articulos_p,'shop_sku') : array_column($articulos_p,'product_sku');

//$esta_plataforma[$plataforma] = in_array($articulo->getEan(),$eans) || in_array($articulo->getReferencia(),$sku_tienda) || in_array($articulo->getReferencia2(),$sku_tienda ) || in_array($articulo->getReferencia(),array_column($articulos_p,'mpn'))  ? 'si':'no';
$esta_plataforma[$plataforma] = 'no';
}


/*$lista_articulos[] = array(
//  'DT_RowId' => $articulo->getReferencia(),
  'DT_RowAttr' => $articulo,
    'imagen' => $articulo->getImagen(),
      'gama' => $articulo->getGama(),
  'fabricante' => $articulo->getFabricante(),
    'referencia' => $articulo->getReferencia(),
    'descripcion' =>   $articulo->getDescripcion(),
      'stock' =>  $articulo->getStock(),
        'precio' => $articulo->getPrecio(),
        'stickers' => array(
          'sioferta' => $articulo->getSioferta(),
          'plataformas' => $esta_plataforma
        )

);*/
$lista_articulos[] = array(
  //  'DT_RowId' => $articulo->getReferencia(),
    'DT_RowAttr' => $articulo,
      'imagen' => $articulo['imagen'],
        'gama' => $articulo['gama'],
    'fabricante' => $articulo['fabricante'],
      'referencia' => $articulo['referencia'],
      'descripcion' =>   $articulo['descripcion'],
        'stock' =>  $articulo['stock'],
          'precio' => $articulo['precio'],
          'stickers' => array(
            'sioferta' => $articulo['sioferta'],
            'plataformas' => $esta_plataforma
          )
  
  );
}

$salida_datatable = array(
  'draw' => '1',
  'recordsTotal' => count($tarifa),
  'recordsFiltered' => count($tarifa),
  'data' => $lista_articulos
);

echo json_encode($salida_datatable);
/*echo "<pre>";
  print_r($salida_datatable);
  echo "</pre>";*/


/*
$json_tarifa = json_encode($tarifa);


  echo $json_tarifa;

echo "<pre>";
  print_r($tarifa);
  echo "</pre>";*/


?>

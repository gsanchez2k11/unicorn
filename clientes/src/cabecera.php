<?php
/**
 *
 *    Cabecera común a todas las páginas del sitio
 *
 */
 $start_time = microtime(TRUE);
 error_reporting(E_ALL);
 ini_set('display_errors', 1);
require_once __DIR__ . '/session.php'; //Gestionamos las sesiones
 ?>
 <!DOCTYPE html>
 <html lang="en">

 <head>

     <meta charset="utf-8">
     <meta http-equiv="X-UA-Compatible" content="IE=edge">
     <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
     <meta name="description" content="">
     <meta name="author" content="">
     <link rel="icon" type="image/png" href="img/favicon.ico" sizes="any">
     <link href="../css/nouislider.css" rel="stylesheet">

<?php
//Comprobamos si se ha definido un título para la página, si no le ponemos el título que hayamos configurado por defecto
if (isset($titulo_pagina)) {
    echo '<title>' . $titulo_pagina . '</title>';
} else {
    echo '<title>Unicorn time</title>';
}
 ?>
 <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

     <!-- Custom fonts for this template-->
     <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
     <link
         href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
         rel="stylesheet">
         <link href="https://fonts.cdnfonts.com/css/pixel-art" rel="stylesheet">
                

     <!-- Custom styles for this template-->
     <link href="../../unicorn/css/cliente.css" rel="stylesheet">
     <link href="../../unicorn/css/sb-admin-2.min.css" rel="stylesheet">
     <!-- Custom styles for this page -->
<link href="../../unicorn/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
<script src="../../unicorn/vendor/jquery/jquery.min.js"></script>
<script src="../../unicorn/js/mijs.js"></script>
<script src="../../unicorn//js/accounting.min.js"></script>
<link rel="stylesheet" href="https://futura.studio73.es/im_livechat/external_lib.css"/>
            
            <script type="text/javascript" src="https://futura.studio73.es/im_livechat/external_lib.js"></script>
            
            <script type="text/javascript" src="https://futura.studio73.es/im_livechat/loader/2"></script>
 </head>

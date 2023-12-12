<?php
/**
 *
 *    Cabecera común a todas las páginas del sitio
 *
 */
 $start_time = microtime(TRUE);
 error_reporting(E_ALL);
 ini_set('display_errors', 1);
require_once __DIR__ . '/session.php';
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
     <link href="css/nouislider.css" rel="stylesheet">


<?php
//Comprobamos si se ha definido un título para la página, si no le ponemos el título que hayamos configurado por defecto
if (isset($titulo_pagina)) {
    echo '<title>' . $titulo_pagina . '</title>';
} else {
    echo '<title>Unicorn time</title>';
}
 ?>
 <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

 <!--    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">

 <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">-->

     <!-- Custom fonts for this template-->
  <!--   <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">-->
     <link
         href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
         rel="stylesheet">

     <!-- Custom styles for this template-->
     <link href="css/micss.css" rel="stylesheet">
     <link href="css/sb-admin-2.min.css" rel="stylesheet">
     <!-- Custom styles for this page -->
     <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jeditable.js/2.0.19/jquery.jeditable.min.js"></script>
<!--<script src="https://kit.fontawesome.com/4a266fede9.js" crossorigin="anonymous"></script>-->
<script src="https://kit.fontawesome.com/58771c730b.js" crossorigin="anonymous"></script>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<!--<link href="vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">-->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs5/dt-1.12.1/sp-2.0.1/sl-1.4.0/datatables.min.css"/>


<script src="vendor/jquery/jquery.min.js"></script>
<script src="js/mijs.js"></script>
<!--<script src="js/alertas.js"></script>-->
<script src="js/accounting.min.js"></script>

 </head>

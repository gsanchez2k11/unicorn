<?php
//Configuramos las opciones de errores
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once 'config.php.inc';              //Incluimos el archivo de configuración ?>


<?php
//Definimos el título de la página
$titulo_pagina = '';
include 'src/cabecera.php';                 //Incluimos la cabecera
 ?>
 <body id="page-top">
   <?php
   include 'src/loader.php';                 //Incluimos la cabecera
    ?>
     <!-- Page Wrapper -->
     <div id="wrapper">
<?php include 'src/sidebar.php';                 //Incluimos el panel lateral ?>
<!-- Content Wrapper -->
<div id="content-wrapper" class="d-flex flex-column">

    <!-- Main Content -->
    <div id="content">
      <?php include 'src/topbar.php';                 //Incluimos el panel superior ?>

      <!-- Begin Page Content -->
      <div class="container-fluid">
        <?php
        /*
        * Comprobamos si NO estamos recibiendo la variable referencia mediante método GET
        * en ese caso mostramos la caja de búsqueda grande
        */
        if (filter_input(INPUT_GET, "pl", FILTER_SANITIZE_FULL_SPECIAL_CHARS)): ?>
        <?php
          $plataforma = filter_input(INPUT_GET, "pl", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
          print_r($plataforma);
         ?>
         <script>
         $('.loader').show();                                                   //Activamos el loader
           $(document).ready(function(){
           plataforma = '<?= $plataforma ?>';
           //Comprobamos si la plataforma es pccomponentes
           if (plataforma == 'pcc') {
gestionContadores();
             var custodias = dameListadoJson('articulos con custodias');                                  //Cargamos los artículos que tienen custodia
          for (i in custodias) {
            $('#acordeonCustodias').append('<div class="accordion-item"><h2 class="accordion-header" id="heading'+i+'"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse'+i+'" aria-expanded="false" aria-controls="collapse'+i+'" >['+custodias[i].mpn+'] '+custodias[i].nombre+'</button></h2><div id="collapse'+i+'" class="accordion-collapse collapse" aria-labelledby="heading'+i+'" data-bs-parent="#acordeonCustodias" data-codinfo="'+custodias[i].codinfo+'"><div class="accordion-body"><div class="row tarjetas"><div class="text-center"><img style="width:498px; height: 373px" src="https://media1.tenor.com/images/a469232213d4a0f23d9653f297d7cbe8/tenor.gif?itemid=16119071"></div></div><div class="row acciones"><a target="_blank" href="http://grupodercont.es/fav3/panel-articulo.php?referencia='+custodias[i].ean+'" class="btn btn-info btn-icon-split ver-articulo-enlace" style="display: none"><span class="icon text-white-50"><i class="fas fa-arrow-right"></i></span><span class="text">Ver el artículo</span></a></div></div></div></div>');
                    }

           }

           var acordeonCustodias = document.getElementById('acordeonCustodias');            //Capturamos el acordeon
           acordeonCustodias.addEventListener('shown.bs.collapse', function (e) {            //Evento que captura cuando abrimos una de las opciones del acordeon
             let codinfo = $(e.target.attributes[4]).val();                                 //Capturamos el codigo de inforpor del articulo
            let esteBloque =   $('.accordion-collapse.collapse').filter(function(){         //Asignamos el acordion que hemos clickado a una variable
                return $(this).attr('data-codinfo') == codinfo;
              }).find('.accordion-body .row.tarjetas');
              $(esteBloque).find('.text-center').remove();
              if ($(esteBloque).is(':empty')) {                                             //Comprobamos si está vacio antes de poner la información
let custodiasArt = cargaCustodiasArticulo(codinfo);
for (i in custodiasArt) {
  console.log(custodiasArt[i]);
  $(esteBloque).append('  <div class="col-xl-3 col-md-6 mb-4"><div class="card border-left-info shadow h-100 py-2"><div class="card-body"><div class="row align-items-center text-lg"><div class="col"><i class="fas fa-boxes fa-2x text-gray-300" style="padding-right: 0.7em"></i>'+custodiasArt[i].quedan +'</div><div class="col text-end">'+accounting.unformat(custodiasArt[i].precio_total,",") +'€<i class="fas fa-dollar-sign fa-2x text-gray-300" style="padding-left: 0.7em"></i></div></div><div class="row align-items-center">pedido '+custodiasArt[i].pedido+'</div></div></div></div>');
}
}
$(esteBloque).next().find('.ver-articulo-enlace').show();
$('.btn-secondary').tooltip();
});
       });
         </script>

        <?php endif ?>
          <!-- Page Heading -->
          <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <img src="img/unicornio-copa-icono.png" alt="">
            <strong>¡Espérate!</strong> Los precios ahora se muestran <strong>IVA y Canon incluidos</strong>.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
          <div class="row">
            <div class="col-xl-3 col-md-6 mb-4">
                                        <div class="card border-left-info shadow h-100 py-2">
                                            <div class="card-body">
                                                <div class="row no-gutters align-items-center">
                                                    <div class="col mr-2">
                                                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">OF21 (Info oferta)
                                                        </div>
                                                        <div class="row no-gutters align-items-center">
                                                            <div class="col-auto">
                                                                <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800 cifra-contador"><!--Cifras contador --></div>
                                                            </div>
                                                            <div class="col">
                                                                <div class="progress progress-sm mr-2">
                                                                    <div class="progress-bar bg-info barra-contador" role="progressbar" style="width: 50%" aria-valuenow="" aria-valuemin="0" aria-valuemax="500"></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-auto icono-contador">
                                                        <!-- icono -->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
          </div>
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
              <h1 class="h3 mb-0 text-gray-800">Custodias</h1>
          <!--    <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                      class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>-->
          </div>




          <div class="accordion" id="acordeonCustodias">
<!-- Custodias -->
  </div>

      </div>
      <!-- /.container-fluid -->

  </div>
  <!-- End of Main Content -->
  <?php
include 'src/footer.php';

   ?>

    </div>
            <!-- End of Content Wrapper -->
  </div>
      <!-- End of Page Wrapper -->
      <!-- Scroll to Top Button-->
<a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
</a>
<!-- Logout Modal-->
<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                <a class="btn btn-primary" href="login.html">Logout</a>
            </div>
        </div>
    </div>
</div>
<!-- Bootstrap core JavaScript-->
<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous"></script>
<!-- Core plugin JavaScript-->
<script src="vendor/jquery-easing/jquery.easing.min.js"></script>

<!-- Custom scripts for all pages-->
<script src="js/sb-admin-2.min.js"></script>

<!-- Page level plugins -->
<script src="vendor/chart.js/Chart.min.js"></script>

<!-- Page level custom scripts -->
<script src="js/demo/chart-area-demo.js"></script>
<script src="js/demo/chart-pie-demo.js"></script>

</body>
</html>

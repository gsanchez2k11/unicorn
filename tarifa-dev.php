<?php
//Configuramos las opciones de errores
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once 'config.php.inc';              //Incluimos el archivo de configuración 
?>


<?php
//Definimos el título de la página
$titulo_pagina = '';
include 'src/cabecera.php';                 //Incluimos la cabecera
?>

<body id="page-top">
  <script src="js/magento.js"></script>
  <script src="js/tarifa.js"></script>
  <?php
  include 'src/loader.php';                 //Incluimos la cabecera
  ?>
  <!-- Page Wrapper -->
  <div id="wrapper">
    <?php include 'src/sidebar.php';                 //Incluimos el panel lateral 
    ?>
    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">
        <?php include 'src/topbar.php';                 //Incluimos el panel superior 
        ?>

        <!-- Begin Page Content -->
        <div class="container-fluid">
          <?php
          /*
        * Comprobamos si NO estamos recibiendo la variable referencia mediante método GET
        */
          if (filter_input(INPUT_GET, "pl", FILTER_SANITIZE_FULL_SPECIAL_CHARS)) : ?>
            <?php
            $plataforma = filter_input(INPUT_GET, "pl", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            ?>
            <!-- Contenido de la página-->
            <script>
              $('.loader-text').html('Haciendo cosas raras');
              $('.loader').show(); //Activamos el loader


              $(document).ready(function() {
                plataforma = '<?= $plataforma ?>'; //Creamos la variable plataforma

                //Guión de la aplicación

                tarifaGoogle = dameTarifa().then(miTarifa => colocaT(miTarifa));
                
                let btnIdentificaCambios = document.getElementById('collapse-precios');
                btnIdentificaCambios.addEventListener('click', buscarArticuloWorker);

                comparaMageMage.addEventListener('click',comparaMages);
/*******
 * ACCIONES
 */

              });
              $(document).on('click', '.btn-cambia-precio', (event) => {
                event.preventDefault();
                let datos = $(event.currentTarget).data('atributos');
                cambiaPrecio(datos);
              });
              $(document).on('click', '.btn-mage-mage', (event) => {
                event.preventDefault();
                let sku = $(event.currentTarget).data('sku');
                let precio = $(event.currentTarget).data('precio');
                let datos = {
                  sku: sku,
                  precio_venta: precio,
                  plataforma: 'magento-2-beta'
                }
                cambiaPrecio(datos);
              });



            </script>
<div class="row">
<div class="col-xl-2 col-md-6 mb-4">
<a class="card text-center shadow h-100 py-2 fs-1" data-bs-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample" id="collapse-precios">
<div class="card-body">
<i class="fa-brands fa-google text-gray-700"></i>
<i class="fa-solid fa-right-long text-info"></i>
<i class="fa-brands fa-magento text-danger"></i>
</div>
</a>
</div>
<div class="col-xl-2 col-md-6 mb-4">
<a class="card text-center shadow h-100 py-2 fs-1" data-bs-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample" id="comparaMageMage">
<div class="card-body">
<i class="fa-brands fa-magento text-danger"></i>
<i class="fa-solid fa-right-long text-info"></i>
<i class="fa-brands fa-magento text-danger"></i>
</div>
</a>
</div>
<!--<div class="col-xl-2 col-md-6 mb-4">
  columna
</div>
<div class="col-xl-2 col-md-6 mb-4">
  columna
</div>
<div class="col-xl-2 col-md-6 mb-4">
  columna
</div>
<div class="col-xl-2 col-md-6 mb-4">
  columna
</div>
<div class="col-xl-2 col-md-6 mb-4">
  columna
</div>-->
</div>
<div class="row" id="rGoogle">
<div class="col-xl-2 col-md-6 mb-4">
<div class="card">
            <div class="card-body">
         <div class="d-flex align-items-start justify-content-center">


  <div class="nav flex-column nav-pills me-3" id="v-pills-tab" role="tablist" aria-orientation="vertical">
   </div>
         </div>

         </div>
          </div>
</div>
         <div class="col-xl-10 col-md-6 mb-4">
  <div class="tab-content" id="v-pills-tabContent">

  </div>
</div>
</div>
<div class="row visually-hidden" id="rMage">
<div class="col-xl-12 col-md-12 mb-4">
<ul class="list-group">

</ul>
</div>
</div>


        </div>
        <!-- /.container-fluid -->

   <!-- Modal para actualizar precio -->
   <div class="modal" id="actualizar-modal" tabindex="-1">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title">Looking for la fiesta</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
              <ul class="list-group list-group-flush">
  <li class="list-group-item plataforma" data-plataforma="magento-2">Magento 2 <!--<i class="fa-solid fa-check ml-3 bg-success p-1 text-white"></i>--><i class="fa-sharp fa-solid fa-question ml-3 text-warning"></i></li>
  <li class="list-group-item plataforma" data-plataforma="odoo">Odoo <i class="fa-sharp fa-solid fa-question ml-3 text-warning"></i></li>
  <li class="list-group-item plataforma" data-plataforma="magento-2-beta"> Otro magento 2 súper secreto en pruebas <i class="fa-sharp fa-solid fa-question ml-3 text-warning"></i></li>
</ul>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
              </div>
            </div>
          </div>
        </div>
        <!-- Fin del Modal de ejemplo -->


      <?php else : //Si no tenemos la variable get
      ?>

      <?php endif; ?>

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
  <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
  <!--<script src="vendor/chart.js/Chart.min.js"></script>-->

  <!-- Page level custom scripts -->
  <!--<script src="js/demo/chart-area-demo.js"></script>
<script src="js/demo/chart-pie-demo.js"></script>-->

</body>

</html>
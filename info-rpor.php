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
<!-- Contenido de la página-->

         <script>
         $('.loader').show();                                                   //Activamos el loader
           $(document).ready(function(){
            var textoBuscar = document.getElementById('input-buscar');
            let terminoBusqueda = document.getElementById('button-buscar'); //Capturamos el click en el botón buscar
            terminoBusqueda.addEventListener("click", clickBuscar); //Llamamos a la funcion clickBuscar
            textoBuscar.addEventListener("keyup", function(e) {
              if (e.key === 'Enter') {
                clickBuscar();
              }
            }); //Llamamos a la funcion clickBuscar
            function clickBuscar(){
    let termino = textoBuscar.value;
              if (termino.length < 3) {                                                       //Comprobamos si al menos hemos introducido 3 caracteres
                alert('por favor, introduce 3 o más caracteres para buscar'); //mostramos un mensaje si no
              } else {
                datos = {codinfo: termino};
let buscaIfp = llamadaJson('ajax/inforpor/buscar-articulo.php',datos);             //Buscamos en la base de datos
console.log(buscaIfp);

              }
}

});




         </script>
        <div class="row justify-content-center">
          <div class="col-xl-10 col-lg-12 col-md-9">
            <div class="card shadow mb-4">
              <div class="row g-0">
                <div class="col-md-4">
                  <img src="https://cdn.dribbble.com/users/1787323/screenshots/5671605/attachments/1225171/dribbble_chug_bottle_2-05.png" class="img-fluid rounded-start" alt="llama">
                </div>
                <div class="col-md-8">
                  <div class="card-body">
                    <h5 class="card-title">LLama a la llama</h5>
                    <p class="card-text">Haz cosas y pasarán cosas.</p>
                    <p class="card-text"><div class="input-group mb-3">
                      <input type="text" class="form-control" placeholder="Busquemos cosas" aria-label="Busquemos cosas" aria-describedby="button-buscar" id="input-buscar">
                      <button class="btn btn-outline-secondary" type="button" id="button-buscar">Buscar</button>
                    </div></p>
                    <h5 class="card-title grupo-llama" style="display: none">Buscando en:</h5>
                    <ul class="list-group list-group-flush grupo-llama" style="display: none">
                      <li class="list-group-item" id="base-datos" style="display: none">La base de datos <span class="float-end"><i class="fas fa-check fa-2x" style="display: none"></i><i class="fas fa-times fa-2x" style="display: none"></i></span></li>
                      <li class="list-group-item" id="tarifa" style="display: none">La tarifa 2022 <span class="float-end"><i class="fas fa-check fa-2x" style="display: none"></i><i class="fas fa-times fa-2x" style="display: none"></i></span></li>
                      <li class="list-group-item" id="inforpor" style="display: none">Inforpor <span class="float-end"><i class="fas fa-check fa-2x" style="display: none"></i><i class="fas fa-times fa-2x" style="display: none"></i></span></li>
                      <li class="list-group-item" id="magento" style="display: none">Magento <span class="float-end"><i class="fas fa-check fa-2x" style="display: none"></i><i class="fas fa-times fa-2x" style="display: none"></i></span></li>
                      <li class="list-group-item" id="pc-componentes" style="display: none">Pc componentes <span class="float-end"><i class="fas fa-check fa-2x" style="display: none"></i><i class="fas fa-times fa-2x" style="display: none"></i></span></li>
                      <li class="list-group-item" id="bares" style="display: none">Hospitales, balnearios y discotecas <span class="float-end"><i class="fas fa-check fa-2x" style="display: none"></i><i class="fas fa-times fa-2x" style="display: none"></i></span></li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
          </div>
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

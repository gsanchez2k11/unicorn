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
<script src="js/peds.js"></script>
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
        <script>
              $(document).ready(function(){

});
          </script>

        <!-- Begin Page Content -->
        <div class="container-fluid">
          <!-- Contenido de la página-->
          <?php
          /*
          * Comprobamos si NO estamos recibiendo la variable referencia mediante método GET
          * en ese caso mostramos la caja de búsqueda grande
          */
          if (filter_input(INPUT_GET, "q", FILTER_SANITIZE_FULL_SPECIAL_CHARS)): ?>

          <script>
          $('.loader').show();                                                   //Activamos el loader
          $(document).ready(function(){
            //const querystring = window.location.search;
            //console.log(querystring)
            //const params = new URLSearchParams(querystring);
          });

          </script>
        <?php else:    //Si no recibimos variable get
          ?>
          <script>
          $('.loader').show();                                                   //Activamos el loader
          $(document).ready(function(){
            let datos;
         //   let tarifaCompleta = llamadaJson('ajax/google/dame-tarifa-2022.php',datos);             //Buscamos en la base de datos
            var textoBuscar = document.getElementById('input-buscar');
            let terminoBusqueda = document.getElementById('button-buscar'); //Capturamos el click en el botón buscar
            let listaResultados = document.getElementById('resultados-busqueda');
            let rowResultadosBusqueda = document.getElementById('row-resultados-busqueda');
            terminoBusqueda.addEventListener("click", clickBuscar); //Llamamos a la funcion clickBuscar
            textoBuscar.addEventListener("keyup", function(e) {
              if (e.key === 'Enter') {
                clickBuscar();
              }
            }); //Llamamos a la funcion clickBuscar


function colocaListado(arr){
  listaResultados.style.display = 'block';
  rowResultadosBusqueda.style.display = 'flex';

  arr.forEach((item, i) => {
    let li = document.createElement("li");
    li.classList.add('list-group-item');
    li.append('[' + item[0] + '] ' + item[2]);
    listaResultados.append(li);

  });
}


 
        });



        </script>
        <div class="row justify-content-center">
          <div class="col-xl-10 col-lg-12 col-md-9">
            <div class="card shadow mb-4">
              <div class="row g-0">
                <div class="col-md-4">
                  <img src="https://cdn.dribbble.com/users/1787323/screenshots/5591006/dribbble_llama_fortnite-02.png" class="img-fluid rounded-start" alt="llama">
                </div>
                <div class="col-md-8">
                  <div class="card-body">
                    <h5 class="card-title">LLama a la llama</h5>
                    <p class="card-text">Haz cosas y pasarán cosas.</p>
                    <p class="card-text"><div class="input-group mb-3">
                      <input type="text" class="form-control" placeholder="Busquemos cosas" aria-label="Busquemos cosas" aria-describedby="btnBuscar" id="inputBuscar">
                      <button class="btn btn-outline-secondary" type="button" id="btnBuscar">Buscar</button>
                    </div></p>
                    <h5 class="card-title grupo-llama" >Buscando en:</h5>
                    <ul class="list-group list-group-flush grupo-llama" >
                    <li class="list-group-item" id="magento"><img src="https://icons-for-free.com/iconfiles/png/512/development+logo+magento+icon-1320184807335224584.png" alt="Logo" width="30" height="30" class="d-inline-block"> Magento</li> 
                    <li class="list-group-item" id="odoo"><img src="https://cu1.uicdn.net/b99/d811c420cd31b6c6cd8b920905906/webapp/icon1752x_odoo.png" alt="Logo" width="30" height="30" class="d-inline-block"> Odoo</li> 
                    <li class="list-group-item" id="inforpor"><img src="./img/logos/icono-inforpor.jpg" alt="Logo" width="30" height="30" class="d-inline-block"> Inforpor</li> 
                    <li class="list-group-item" id="bares"> <img src="https://icons-for-free.com/iconfiles/png/256/newyears+party+patern+icon-1320186213357319466.png" alt="Logo" width="30" height="30" class="d-inline-block"> Hospitales, balnearios y discotecas <span class="float-end"><i class="fas fa-check fa-2x" style="display: none"></i><i class="fas fa-times fa-2x" style="display: none"></i></span></li>
                    

                     </ul>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="row justify-content-center" id="row-resultados-busqueda">
          <div class="col-xl-10 col-lg-12 col-md-9">
            <div class="card shadow mb-4 p-3">
              <div class="row">
                <h5>Resultados de la LLAMAda</h5>
                <p>Hemos encontrado varias cosas que podrían coincidir con lo que buscas, así que haz click para saber más</p>
                <ul class="list-group list-group-flush" id="resultados-busqueda">
                </ul>
              </div>
            </div>
          </div>
        </div>


        <div class="row justify-content-center" id="row-informacion-pedido">
          <div class="col-xl-10 col-lg-12 col-md-9">
            <div class="card shadow mb-4 p-3">
              <div class="row">
              <div class="col-3" id="plataformaPedido">
                  <!-- plataforma -->
                </div>
                <div class="col-3">
                <div class="row">
                <div class="col-1"> <span class="material-icons align-bottom">
                          numbers
                        </span></div>
                      <div class="col"> <span class="dato-pedido" id="refPedido"><!-- referencia del pedido --></span></div>
                </div>
                </div>
                <div class="col-3">
                <div class="row">
                      <div class="col-1"> <span class="material-icons align-bottom">
                          calendar_month
                        </span></div>
                      <div class="col"> <span class="dato-pedido" id="fechaPedido"><!-- fecha del pedido --></span></div>
                    </div>
                </div>
                <div class="col-3">
                <div class="row">
                      <div class="col-1"> <span class="material-icons align-bottom">
                          calendar_month
                        </span></div>
                      <div class="col"> <span class="dato-pedido" id="estadoPedido"><!-- fecha del pedido --></span></div>
                    </div>
                </div>

              </div>
              <div class="row">
              <div class="col-6">
                  Facturación
                </div>
                <div class="col-6">
                  Envío
                </div>
              </div>
              <div class="row">
<p>Row articulos</p>
              </div>
              <div class="row">
<p>Row documentos relacionados</p>
              </div>
            </div>
          </div>
        </div>
      <?php endif; //Fin de la comprobación la variable GET?>

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

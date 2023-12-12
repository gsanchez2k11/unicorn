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
  <script src="js/ofertas.js"></script>
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
          <!-- Contenido de la página-->

          <script>
            $('.loader-text').html('Haciendo cosas raras');
            $('.loader').show();
            const querystring = window.location.search;
            console.log(querystring)
            const params = new URLSearchParams(querystring);
            //       params.set(variable, valor);
            const plataforma = params.get('pl');
            const rp = params.get('rp');
            const p = params.get('p');
            var idTienda;

            $(document).ready(function() {
              switch (plataforma) {
              case 'mage245':
                idTienda = 2;
                switchPlataforma.checked = true;
                break;
              case 'mage':
              default:
                idTienda = 1;
                break;
            }
              /// VENTANAS MODALES
              ResultadoActualizarModal = new bootstrap.Modal(document.getElementById('resultado-actualizar-modal'), { //Modal de ejemplo
                keyboard: false
              });

              dameOfertas(-1, 'special_to_date', idTienda).then(function(response) { //Hacemos una primera llamada con parámetro -1 para obtener las caducadas
                $('#num-of-caducadas').html(response.total_count); //Colocamos el número
                if (response.total_count > 0) {
                  let articul = response.items;
                  //procesaOfertas(articul,'collapseExample');
                  let articulos = procesa(articul);
                  let colocar = coloca(articulos, 'collapseExample');
                }

              }).then(function() {
                dameOfertas(90, 'special_to_date',idTienda).then(function(ofFinalizando) { //Buscamos ahora las ofertas que acaban en un máximo de 7 dias
                  $('#num-of-finalizando').html(ofFinalizando.total_count); //Colocamos el número
                  if (ofFinalizando.total_count > 0) {
                    let articul = ofFinalizando.items;
                    //  procesaOfertas(articul,'collapseFinalizando');
                    let articulos = procesa(articul);
                    console.log(articulos);
                    let colocar = coloca(articulos, 'collapseFinalizando');

                  }

                });

              }).then(function() {
                dameOfertas(-1, 'fin_oferta_descripcion',idTienda).then(function(ofs) { //Buscamos ahora las ofertas que acaban en un máximo de 7 dias
                  $('#num-of-descripcion-finalizada').html(ofs.total_count); //Colocamos el número
                  if (ofs.total_count > 0) {
                    let articul = ofs.items;
                    //    procesaOfertasDescripcion(articul,'collapseDescripcionFinalizada');
                    let articulos = procesa(articul);
                    let colocar = coloca(articulos, 'collapseDescripcionFinalizada');

                  }
                  //console.log(ofs);
                });
              });
            });





            $(document).on('click', '.btn-add-time', (event) => {

              let resultado = daleTiempo(event);

            });
            $(document).on('click', '.boton-eliminar', (event) => {
              let resultado = borraOferta(event);
            });
            function cambiaPlataforma() {
              let nPl;
              if (plataforma == 'mage') {
                nPl = 'mage245';
              } else {
                nPl = 'mage';
              }
              location.href = "?pl=" + nPl;
            }
          </script>
<!--<div class="row">
<div class="col-xl-3 col-md-6 mb-4">
<a class="card text-center shadow h-100 py-2 fs-1" data-bs-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample" id="collapse-precios">
<div class="card-body">
<div class="row">
<div class="col-xl-6 col-md-6 mb-4">
<i class="fa-sharp fa-solid fa-dollar-sign text-danger" ></i>
</div><div class="col-xl-6 col-md-6 mb-4">
  0
  </div>
</div>
<div class="row">
<div class="col-xl-6 col-md-6 mb-4">finalizadas</div>
</div>
</div>
</a>
</div>
</div>-->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800"><i class="fab fa-magento"></i> Ofertas Magento</h1>
            <div class="row justify-content-center align-items-center g-2" style="width:25%">
              <div class="col mage"><i class="fab fa-magento"></i> <label class="form-check-label" for="switchPlataforma">Magento 2.2</label></div>
              <div class="col text-center">
                <div class="form-check form-switch"><input class="form-check-input" type="checkbox" role="switch" id="switchPlataforma" onclick="cambiaPlataforma()"></div>
              </div>
              <div class="col mage245"><label class="form-check-label" for="switchPlataforma">Magento 2.4</label> <i class="fab fa-magento"></i></div>
            </div>
            <!--    <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
           class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>-->
          </div>
          <div class="row">
            <div class="col-xl-12 col-md-6 mb-4">
              <a class="card bg-danger shadow h-100 py-2 text-white" data-bs-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col-auto icono-contador"><img src="img/unicornio/llorando.png" style="width: 64px"></div>
                    <div class="col ml-3">
                      <div class="row no-gutters align-items-center">
                        <div class="col-auto">
                          <div class="h5 mb-0 mr-3 font-weight-bold cifra-contador text-white">Ofertas caducadas</div>
                        </div>

                      </div>
                      <div class="text-xs text-white text-uppercase mt-1" style="color: gray">Ojocuidao a esto, las ofertas que ya han caducado. Urgentísimo de mirarlas.
                      </div>
                    </div>
                    <div class="col-auto  fs-1" id="num-of-caducadas">

                    </div>
                  </div>
                </div>
              </a>

            </div>
            <div class="collapse" id="collapseExample">
              <div class="card card-body">
                <div class="row">
                  <img src="https://i0.wp.com/novocom.top/image/aS5naci5jbWZlci5jb20=/QhW.gif" alt="Nada que mostrar" style="width: 450px" />
                </div>
              </div>
            </div>
          </div>
          <div class="row mt-3">
            <div class="col-xl-12 col-md-6 mb-4">
              <a class="card border-left-info shadow h-100 py-2" data-bs-toggle="collapse" href="#collapseFinalizando" role="button" aria-expanded="false" aria-controls="collapseFinalizando">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col-auto icono-contador"><img src="img/unicornio-angustia.png"></div>
                    <div class="col ml-3">
                      <div class="row no-gutters align-items-center">
                        <div class="col-auto">
                          <div class="h5 mb-0 mr-3 font-weight-bold text-info cifra-contador">Ofertas finalizando</div>
                        </div>

                      </div>
                      <div class="text-xs text-gray text-uppercase mt-1" style="color: gray">Ojocuidao a esto, las ofertas que ya han caducado. Urgentísimo de mirarlas.
                      </div>
                    </div>
                    <div class="col-auto  fs-1" id="num-of-finalizando">

                    </div>
                  </div>
                </div>
              </a>

            </div>
            <div class="collapse" id="collapseFinalizando">
              <div class="card card-body">
                <div class="row">
                  <img src="https://i0.wp.com/novocom.top/image/aS5naci5jbWZlci5jb20=/QhW.gif" alt="Nada que mostrar" style="width: 450px" />
                </div>
              </div>
            </div>
          </div>

          <div class="row mt-3">
            <div class="col-xl-12 col-md-6 mb-4">
              <a class="card border-left-info shadow h-100 py-2" data-bs-toggle="collapse" href="#collapseDescripcionFinalizada" role="button" aria-expanded="false" aria-controls="collapseDescripcionFinalizada">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col-auto icono-contador"><img src="img/unicornio-angustia.png"></div>
                    <div class="col ml-3">
                      <div class="row no-gutters align-items-center">
                        <div class="col-auto">
                          <div class="h5 mb-0 mr-3 font-weight-bold text-info cifra-contador">Fecha descripcion finalizada</div>
                        </div>

                      </div>
                      <div class="text-xs text-gray text-uppercase mt-1" style="color: gray">Ofertas con fecha descripción finalizada.
                      </div>
                    </div>
                    <div class="col-auto  fs-1" id="num-of-descripcion-finalizada">

                    </div>
                  </div>
                </div>
              </a>

            </div>
            <div class="collapse" id="collapseDescripcionFinalizada">
              <div class="card card-body">
                <div class="row">
                  <img src="https://i0.wp.com/novocom.top/image/aS5naci5jbWZlci5jb20=/QhW.gif" alt="Nada que mostrar" style="width: 450px" />
                </div>
              </div>
            </div>
          </div>







        </div>
        <!-- /.container-fluid -->
           <!-- Modal para actualizar fecha oferta -->
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
        <!-- Fin del Modal de fecha oferta -->
        <!-- Modal para mostrar el resultado -->
        <div class="modal" id="resultado-actualizar-modal" tabindex="-1">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title">Looking for the fiesta</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <img src="https://cdn.dribbble.com/users/2172479/screenshots/6014482/unicorn_dribble1.gif" class="img-fluid" alt="Haber elegido muerte">
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
              </div>
            </div>
          </div>
        </div>
        <!-- Fin del Modal de ejemplo -->


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
  <script src="vendor/chart.js/Chart.min.js"></script>

  <!-- Page level custom scripts -->
  <script src="js/demo/chart-area-demo.js"></script>
  <script src="js/demo/chart-pie-demo.js"></script>

</body>

</html>
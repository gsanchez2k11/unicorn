

<?php
require_once 'config.php.inc';              //Incluimos el archivo de configuración?>
<?php
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
        <script>
        $('.loader').show();
         $(document).ready(function(){
//gestionContadores();
         });
        $('.loader').hide();
        </script>

          <!-- Page Heading -->
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
              <h1 class="h3 mb-0 text-gray-800"><i class="fab fa-magento"></i> Magento</h1>
          <!--    <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                      class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>-->
          </div>
          <div class="row">

            <div class="col-lg-2 mb-4">

                <div class="card shadow mb-4">
                    <div class="card-header py-3">

                        <h6 class="m-0 font-weight-bold text-info">Tarifa</h6>
                    </div>
                    <div class="card-body">
                        <div class="text-center">
                            <img class="img-fluid px-3 px-sm-4 mt-3 mb-4" style="width: 25rem;"
                                src="img/tarifas.jpg" alt="">
                        </div>
                        <p>Sincronización de datos entre nuestra tarifa y las tiendas online.</p>
                        <a target="_self" rel="nofollow" href="tarifa.php?pl=mage">Ver muchos datos &rarr;</a>
                    </div>
                </div>

            </div>
            <div class="col-lg-2 mb-4">

                <div class="card shadow mb-4">
                    <div class="card-header py-3">

                        <h6 class="m-0 font-weight-bold text-info">Ofertas</h6>
                    </div>
                    <div class="card-body">
                        <div class="text-center">
                            <img class="img-fluid px-3 px-sm-4 mt-3 mb-4" style="width: 25rem;"
                                src="img/cerda.jpg" alt="">
                        </div>
                        <p>¡Que no se te pase nada! Desde aquí podrás grestionar los plazos y precios de todas las ofertas.</p>
                        <a target="_self" rel="nofollow" href="ofertas.php">A ofertear &rarr;</a>
                    </div>
                </div>

            </div>
              <div class="col-lg-2 mb-4">

                  <div class="card shadow mb-4">
                      <div class="card-header py-3">

                          <h6 class="m-0 font-weight-bold text-info">Pedidos</h6>
                      </div>
                      <div class="card-body">
                          <div class="text-center">
                              <img class="img-fluid px-3 px-sm-4 mt-3 mb-4" style="width: 25rem;"
                                  src="img/billetera.jpg" alt="">
                          </div>
                          <p>Todo lo demás no vale de mucho si no se traduce en pedidos.</p>
                          <a target="_self" rel="nofollow" href="pedidos.php?pl=mage">Ver pedidos Magento &rarr;</a>
                      </div>
                  </div>

                  <!-- Approach -->
                <!--  <div class="card shadow mb-4">
                      <div class="card-header py-3">
                          <h6 class="m-0 font-weight-bold text-primary">Development Approach</h6>
                      </div>
                      <div class="card-body">
                          <p>SB Admin 2 makes extensive use of Bootstrap 4 utility classes in order to reduce
                              CSS bloat and poor page performance. Custom CSS classes are used to create
                              custom components and custom utility classes.</p>
                          <p class="mb-0">Before working with this theme, you should become familiar with the
                              Bootstrap framework, especially the utility classes.</p>
                      </div>
                  </div>-->

              </div>
              <div class="col-lg-2 mb-4">

<div class="card shadow mb-4">
    <div class="card-header py-3">

        <h6 class="m-0 font-weight-bold text-info">Catálogo</h6>
    </div>
    <div class="card-body">
        <div class="text-center">
            <img class="img-fluid px-3 px-sm-4 mt-3 mb-4" style="width: 25rem;"
                src="img/cuchillo.jpg" alt="">
        </div>
        <p>Hagamos cosas filtrando el catálogo de magento.</p>
        <a target="_self" rel="nofollow" href="catalogo.php?pl=mage">Ojear el catálogo en Magento &rarr;</a>
    </div>
</div>

<!-- Approach -->
<!--  <div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Development Approach</h6>
    </div>
    <div class="card-body">
        <p>SB Admin 2 makes extensive use of Bootstrap 4 utility classes in order to reduce
            CSS bloat and poor page performance. Custom CSS classes are used to create
            custom components and custom utility classes.</p>
        <p class="mb-0">Before working with this theme, you should become familiar with the
            Bootstrap framework, especially the utility classes.</p>
    </div>
</div>-->

</div>
<div class="col-lg-2 mb-4">

<div class="card shadow mb-4">
    <div class="card-header py-3">

        <h6 class="m-0 font-weight-bold text-info">Márgenes</h6>
    </div>
    <div class="card-body">
        <div class="text-center">
            <img class="img-fluid px-3 px-sm-4 mt-3 mb-4" style="width: 25rem;"
                src="img/margenes.jpg" alt="">
        </div>
        <p>Consulta los márgenes aplicables a cada familia de producto y su configuración.</p>
        <a target="_self" rel="nofollow" href="margenes.php">Ver familias y márgenes &rarr;</a>
    </div>
</div>

<!-- Approach -->
<!--  <div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Development Approach</h6>
    </div>
    <div class="card-body">
        <p>SB Admin 2 makes extensive use of Bootstrap 4 utility classes in order to reduce
            CSS bloat and poor page performance. Custom CSS classes are used to create
            custom components and custom utility classes.</p>
        <p class="mb-0">Before working with this theme, you should become familiar with the
            Bootstrap framework, especially the utility classes.</p>
    </div>
</div>-->

</div>
          </div>
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
              <h1 class="h3 mb-0 text-gray-800"><img src="https://gdm-catalog-fmapi-prod.imgix.net/ProductLogo/c833968b-d85a-4407-bb1c-43308117987e.png?w=90&h=90&fit=max&dpr=3&auto=format&q=50" style="width: 32px"> Mirakl</h1>
          <!--    <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                      class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>-->
          </div>
<!--<div class="row">
  <div class="col-xl-3 col-md-6 mb-4">
                              <div class="card border-left-info shadow h-100 py-2">
                                  <div class="card-body">
                                      <div class="row no-gutters align-items-center">
                                          <div class="col mr-2">
                                              <div class="text-xs font-weight-bold text-info text-uppercase mb-1">OF21 (Info oferta)
                                              </div>
                                              <div class="row no-gutters align-items-center">
                                                  <div class="col-auto">
                                                      <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800 cifra-contador"></div>
                                                  </div>
                                                  <div class="col">
                                                      <div class="progress progress-sm mr-2">
                                                          <div class="progress-bar bg-info barra-contador" role="progressbar" style="width: 50%" aria-valuenow="" aria-valuemin="0" aria-valuemax="500"></div>
                                                      </div>
                                                  </div>
                                              </div>
                                          </div>
                                          <div class="col-auto icono-contador">
                                        
                                          </div>
                                      </div>
                                  </div>
                              </div>
                          </div>
</div> -->


          <!-- Content Row -->
          <div class="row">

              <!-- Earnings (Monthly) Card Example -->
    <!--          <div class="col-xl-3 col-md-6 mb-4">
                  <div class="card border-left-primary shadow h-100 py-2">
                      <div class="card-body">
                          <div class="row no-gutters align-items-center">
                              <div class="col mr-2">
                                  <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                      Earnings (Monthly)</div>
                                  <div class="h5 mb-0 font-weight-bold text-gray-800">$40,000</div>
                              </div>
                              <div class="col-auto">
                                  <i class="fas fa-calendar fa-2x text-gray-300"></i>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>-->

              <!-- Earnings (Monthly) Card Example -->
          <!--    <div class="col-xl-3 col-md-6 mb-4">
                  <div class="card border-left-success shadow h-100 py-2">
                      <div class="card-body">
                          <div class="row no-gutters align-items-center">
                              <div class="col mr-2">
                                  <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                      Earnings (Annual)</div>
                                  <div class="h5 mb-0 font-weight-bold text-gray-800">$215,000</div>
                              </div>
                              <div class="col-auto">
                                  <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>-->

              <!-- Earnings (Monthly) Card Example -->
          <!--    <div class="col-xl-3 col-md-6 mb-4">
                  <div class="card border-left-info shadow h-100 py-2">
                      <div class="card-body">
                          <div class="row no-gutters align-items-center">
                              <div class="col mr-2">
                                  <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Tasks
                                  </div>
                                  <div class="row no-gutters align-items-center">
                                      <div class="col-auto">
                                          <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">50%</div>
                                      </div>
                                      <div class="col">
                                          <div class="progress progress-sm mr-2">
                                              <div class="progress-bar bg-info" role="progressbar"
                                                  style="width: 50%" aria-valuenow="50" aria-valuemin="0"
                                                  aria-valuemax="100"></div>
                                          </div>
                                      </div>
                                  </div>
                              </div>
                              <div class="col-auto">
                                  <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>-->

              <!-- Pending Requests Card Example -->
            <!--  <div class="col-xl-3 col-md-6 mb-4">
                  <div class="card border-left-warning shadow h-100 py-2">
                      <div class="card-body">
                          <div class="row no-gutters align-items-center">
                              <div class="col mr-2">
                                  <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                      Pending Requests</div>
                                  <div class="h5 mb-0 font-weight-bold text-gray-800">18</div>
                              </div>
                              <div class="col-auto">
                                  <i class="fas fa-comments fa-2x text-gray-300"></i>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>-->
          </div>

          <!-- Content Row -->

          <div class="row">

              <!-- Area Chart -->
          <!--    <div class="col-xl-8 col-lg-7">
                  <div class="card shadow mb-4">-->
                      <!-- Card Header - Dropdown -->
                  <!--    <div
                          class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                          <h6 class="m-0 font-weight-bold text-primary">Earnings Overview</h6>
                          <div class="dropdown no-arrow">
                              <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                                  data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                  <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                              </a>
                              <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                                  aria-labelledby="dropdownMenuLink">
                                  <div class="dropdown-header">Dropdown Header:</div>
                                  <a class="dropdown-item" href="#">Action</a>
                                  <a class="dropdown-item" href="#">Another action</a>
                                  <div class="dropdown-divider"></div>
                                  <a class="dropdown-item" href="#">Something else here</a>
                              </div>
                          </div>
                      </div>-->
                      <!-- Card Body -->
                  <!--    <div class="card-body">
                          <div class="chart-area">
                              <canvas id="myAreaChart"></canvas>
                          </div>
                      </div>
                  </div>
              </div>-->

              <!-- Pie Chart -->
              <!--<div class="col-xl-4 col-lg-5">
                  <div class="card shadow mb-4">-->
                      <!-- Card Header - Dropdown -->
                  <!--    <div
                          class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                          <h6 class="m-0 font-weight-bold text-primary">Revenue Sources</h6>
                          <div class="dropdown no-arrow">
                              <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                                  data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                  <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                              </a>
                              <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                                  aria-labelledby="dropdownMenuLink">
                                  <div class="dropdown-header">Dropdown Header:</div>
                                  <a class="dropdown-item" href="#">Action</a>
                                  <a class="dropdown-item" href="#">Another action</a>
                                  <div class="dropdown-divider"></div>
                                  <a class="dropdown-item" href="#">Something else here</a>
                              </div>
                          </div>
                      </div>-->
                      <!-- Card Body -->
                  <!--    <div class="card-body">
                          <div class="chart-pie pt-4 pb-2">
                              <canvas id="myPieChart"></canvas>
                          </div>
                          <div class="mt-4 text-center small">
                              <span class="mr-2">
                                  <i class="fas fa-circle text-primary"></i> Direct
                              </span>
                              <span class="mr-2">
                                  <i class="fas fa-circle text-success"></i> Social
                              </span>
                              <span class="mr-2">
                                  <i class="fas fa-circle text-info"></i> Referral
                              </span>
                          </div>
                      </div>
                  </div>
              </div>-->
          </div>

          <!-- Content Row -->
          <div class="row">
            <div class="col-lg-2 mb-4">

                              <div class="card shadow mb-4">
                                  <div class="card-header py-3">
                                      <h6 class="m-0 font-weight-bold text-info">Lista de artículos</h6>
                                  </div>
                                  <div class="card-body">
                                      <div class="text-center">
                                          <img class="img-fluid px-3 px-sm-4 mt-3 mb-4" style="width: 25rem;"
                                              src="img/2152.jpg" alt="">
                                      </div>
                                      <p>Ojalá ver todas nuestras ofertas en Pc Componentes a golpe de click.</p>
                                      <a target="_self" rel="nofollow" href="lista-articulos.php?pl=pcc">Ver todos los artículos de pc componentes &rarr;</a>
                                  </div>
                              </div>


                          </div>
                          <div class="col-lg-2 mb-4">
                                            <div class="card shadow mb-4">
                                                <div class="card-header py-3">
                                                    <h6 class="m-0 font-weight-bold text-info">Pedidos</h6>
                                                </div>
                                                <div class="card-body">
                                                    <div class="text-center">
                                                        <img class="img-fluid px-3 px-sm-4 mt-3 mb-4" style="width: 25rem;"
                                                            src="img/billetera.jpg" alt="">
                                                    </div>
                                                    <p>Todo lo demás no vale de mucho si no se traduce en pedidos.</p>
                                                    <a target="_self" rel="nofollow" href="pedidos.php?pl=pcc">Ver pedidos de Pc componentes &rarr;</a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-2 mb-4">
                                            <div class="card shadow mb-4">
                                                <div class="card-header py-3">
                                                    <h6 class="m-0 font-weight-bold text-info">Utilidades</h6>
                                                </div>
                                                <div class="card-body">
                                                    <div class="text-center">
                                                        <img class="img-fluid px-3 px-sm-4 mt-3 mb-4" style="width: 25rem;"
                                                            src="img/tools.png" alt="">
                                                    </div>
                                                    <p>Pequeñas utilidades para mejorar la gestión de los marketplaces.</p>
                                                    <a target="_self" rel="nofollow" href="tools-mirakl.php">Bricomaniacos &rarr;</a>
                                                </div>
                                            </div>
                                        </div>
              <!-- Content Column -->
           <!--   <div class="col-lg-3 mb-4">
                  <div class="card shadow mb-4">
                      <div class="card-header py-3">
                          <h6 class="m-0 font-weight-bold text-info">Custodias</h6>
                      </div>
                      <div class="card-body">
                          <div class="text-center">
                              <img class="img-fluid px-3 px-sm-4 mt-3 mb-4" style="width: 25rem;"
                                  src="img/18354583.jpg" alt="">
                          </div>
                          <p>Todas esas cosas que tenemos en otros almacenes a la espera de un click salvador que las rescate.</p>
                          <a target="_self" rel="nofollow" href="custodias.php?pl=pcc">Ver todas las custodias &rarr;</a>
                      </div>
                  </div>
</div>-->


<!--              <div class="col-lg-3 mb-4">


                  <div class="card shadow mb-4">
                      <div class="card-header py-3">
                          <h6 class="m-0 font-weight-bold text-info">No primeros</h6>
                      </div>
                      <div class="card-body">
                          <div class="text-center">
                              <img class="img-fluid px-3 px-sm-4 mt-3 mb-4" style="width: 25rem;"
                                  src="img/2273.jpg" alt="">
                          </div>
                          <p>No tener el mejor precio hace llorar a los unicornios. ¡Evitémoslo!</p>
                          <a target="_self" rel="nofollow" href="no-primeros.php?pl=pcc">Ver en que artículos no tenemos el mejor precio &rarr;</a>
                      </div>
                  </div>
</div>-->

          </div>
  

          <div class="d-sm-flex align-items-center justify-content-between mb-4">
              <h1 class="h3 mb-0 text-gray-800"><i class="far fa-frown-open"></i> Inforpor</h1>
          <!--    <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                      class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>-->
          </div>
          <div class="row">

            <div class="col-lg-2 mb-4">

                <div class="card shadow mb-4">
                    <div class="card-header py-3">

                        <h6 class="m-0 font-weight-bold text-info">Catalogo</h6>
                    </div>
                    <div class="card-body">
                        <div class="text-center">
                            <img class="img-fluid px-3 px-sm-4 mt-3 mb-4" style="width: 25rem;"
                                src="img/logo-inforpor.jpg" alt="">
                        </div>
                        <p>Recién traido hace unos días directamente de los más profundo del fichero que nos mandan periodicamente o no.</p>
                        <a target="_self" rel="nofollow" href="inforpor.php">Ver no sé, cosas &rarr;</a>
                    </div>
                </div>

            </div>


          </div>
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
              <h1 class="h3 mb-0 text-gray-800"><i class="far fa-frown-open"></i> Odio</h1>
          <!--    <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                      class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>-->
          </div>
          <div class="row">
            <div class="col-lg-2 mb-4">

                <div class="card shadow mb-4">
                    <div class="card-header py-3">

                        <h6 class="m-0 font-weight-bold text-info">Tarifa</h6>
                    </div>
                    <div class="card-body">
                        <div class="text-center">
                            <img class="img-fluid px-3 px-sm-4 mt-3 mb-4" style="width: 25rem;"
                                src="img/tarifas.jpg" alt="">
                        </div>
                        <p>Sincronización de datos entre nuestra tarifa y el ERP.</p>
                        <a target="_self" rel="nofollow" href="tarifa.php?pl=odoo">Ver muchos datos &rarr;</a>
                    </div>
                </div>

            </div>

            <div class="col-lg-2 mb-4">

                <div class="card shadow mb-4">
                    <div class="card-header py-3">

                        <h6 class="m-0 font-weight-bold text-info">Categorias</h6>
                    </div>
                    <div class="card-body">
                        <div class="text-center">
                            <img class="img-fluid px-3 px-sm-4 mt-3 mb-4" style="width: 25rem;"
                                src="img/categorias.jpg" alt="">
                        </div>
                        <p>Navegar entre las categorias de Odoo para hacer cosas muy interesantes.</p>
                        <a target="_self" rel="nofollow" href="categorias.php?pl=odoo">Yo vivo navegando &rarr;</a>
                    </div>
                </div>

            </div>
            <div class="col-lg-2 mb-4">

<div class="card shadow mb-4">
    <div class="card-header py-3">

        <h6 class="m-0 font-weight-bold text-info">Utilidades</h6>
    </div>
    <div class="card-body">
        <div class="text-center">
            <img class="img-fluid px-3 px-sm-4 mt-3 mb-4" style="width: 25rem;"
                src="img/categorias.jpg" alt="">
        </div>
        <p>Utilidades y pequeñas aplicaciones que ayudan en el día a día.</p>
        <a target="_self" rel="nofollow" href="tools-odoo.php">Trasteando &rarr;</a>
    </div>
</div>

</div>
          </div>

      </div>
      <!-- /.container-fluid -->

  </div>
  <!-- End of Main Content -->
  <!-- Footer -->
  <?php
include 'src/footer.php';

   ?>

  <!-- End of Footer -->

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
<!--<script src="vendor/jquery/jquery.min.js"></script>-->
<!--<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>-->

<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
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

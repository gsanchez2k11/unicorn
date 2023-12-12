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
  <script src="js/margenes.js"></script>
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
            $('.loader').show(); //Activamos el loader
            $(document).ready(function() {
              const varModalExito = new bootstrap.Modal(modalExito, '');
              const varModalAdd = new bootstrap.Modal(modalAdd, '');
              const varModalConfirmar = new bootstrap.Modal(modalConfirmar, '');
              //CArgamos las plataformas
              var plataformas = JSON.parse($.ajax({
                type: "POST",
                url: 'ajax/unicorn_db/listar.php',
                data: {
                  tabla: 'plataformas'
                },
                dataType: 'json',
                global: false,
                async: false,
                success: function(data, textStatus, jqXHR) {
                  //  console.log('cliente: ' + resp);
                  //    $('.clientecrm').append(resp);
                  return data;
                },
                error: function(data, textStatus, jqXHR) {
                  alert('Error: ' + JSON.stringify(data));
                  //    $(bloque).find('.dimmer').toggleClass('active');
                }
              }).responseText);

              //Recuperamos todas las entradas de la tabla familia_margenes
              var formData = new FormData();
              formData.append('tabla', 'familias_margenes');
              fetch('ajax/unicorn_db/listar.php', {
                  method: 'POST', // or 'PUT'
                  body: formData // data can be `string` or {object}!

                }).then(res => res.json())
                .catch(error => console.error('Error:', error)) //Si recibimos mensajes de error
                .then(response => colocaActualizacionesTabla(response, plataformas)); //Procedemos si tenemos exito
              /**
               * Acciones cuando abrimos el modal para añadir actualización
               *  */
              modalAdd.addEventListener('shown.bs.modal', () => {

                plataformas.forEach((plataforma, i) => { //Recorremos el listado de categorias
                  let option = document.createElement("option"); //Creamos un elemento option
                  option.text = plataforma.nombre; //Asignamos como texto el nombre completo de la categoria
                  option.value = plataforma.codigo; //Asignamos como valor la id
                  document.getElementById('selectPlataformas').add(option);
                });


                //Capturamos la opción seleccionada en el select de plataformas
                document.getElementById('selectPlataformas').addEventListener('change', e => {
                  let valorSeleccion = e.target.value;
                  if (valorSeleccion == 'mage' || valorSeleccion == 'mage245') { //Instalaciones de magento
                    let conjuntoAtributos = dameConjuntosAtributos(valorSeleccion);
                    //Filtramos para dejar solo los de tipo 4
                    let conjuntosActivos = conjuntoAtributos.filter(i => i.entity_type_id === 4);
                    //Colocamos las opciones //selectConjuntoAtributos
                    conjuntosActivos.forEach((conjunto, i) => { //Recorremos el listado de categorias
                      let option = document.createElement("option"); //Creamos un elemento option
                      option.text = conjunto.attribute_set_name; //Asignamos como texto el nombre completo de la categoria
                      option.value = conjunto.attribute_set_id; //Asignamos como valor la id
                      document.getElementById('selectConjuntoAtributos').add(option);
                    });

                    //Hacemos ahora otra llamada para lsitar los fabricantes
                    //En la versión 2.4.5 se utiliza otro atributo
                    let listaFabricantes = dameFabricantes(valorSeleccion);

                    listaFabricantes.forEach((fabricante, i) => { //Recorremos el listado de categorias
                      let option = document.createElement("option"); //Creamos un elemento option
                      option.text = fabricante.label; //Asignamos como texto el nombre completo de la categoria
                      option.value = fabricante.value; //Asignamos como valor la id
                      document.getElementById('selectFabricantes').add(option);
                    });

                    console.info(listaFabricantes);



                  }

                })

              })

              //Capturamos el click en el botón de añadir
              document.getElementById('btn-add-actualizacion').addEventListener('click', function(e) {
                // capturamos el valor de cada campo
                let obj = {
                  hora: selectHora.value,
                  plataforma: selectPlataformas.value,
                  conjunto_atributos: selectConjuntoAtributos.value,
                  fabricante: selectFabricantes.value,
                  min_precio: inputPrecioDesde.value,
                  max_precio: inputPrecioHasta.value,
                  notas: notasPedido.value,
                  margen: inputMargen.value
                }
                let datos = {
                  tabla: 'familias_margenes',
                  arr: obj
                }
                $.ajax({
                  type: "POST",
                  url: 'ajax/unicorn_db/add.php',
                  data: datos,
                  // dataType: 'json',
                  global: false,
                  async: false,
                  success: function(data, textStatus, jqXHR) {
                    //    console.log(data);
                    varModalAdd.hide();
                    varModalExito.show();
                  //  colocaActualizaciones(actualizaciones,plataformas);
                //  location.reload();
                  },
                  error: function(data, textStatus, jqXHR) {
                    alert('Error: ' + JSON.stringify(data));
                    //    $(bloque).find('.dimmer').toggleClass('active');
                  }
                })
                console.log(obj);
              });
           //Hacemos los campos editables
            });
          </script>
          <div class="card shadow mb-4">
            <div class="card-header py-3">
              <h6 class="m-0 font-weight-bold text-primary">Márgenes y actualizaciones</h6>
            </div>
            <div class="card-body">
              <button class="btn btn-info btn-icon-split btn-sm float-end btn-add-actualizacion mb-4" data-bs-toggle="modal" data-bs-target="#modalAdd"><span class="icon text-white-50"><i class="fa-solid fa-plus add-actualizacion" aria-hidden="true"></i></span><span class="text">Añadir nueva</span></button>
              <table class="table display" id="dataTable" style="width:100%">
                <thead>
                  <tr>
                    <th scope="col"><i class="fa-regular fa-clock"></i></th>
                    <th scope="col">Plataforma</th>
                    <th scope="col">Familia</th>
                    <th scope="col">Fabricante</th>
                    <th scope="col">€ min</th>
                    <th scope="col">€ max</th>
                    <th scope="col">Margen</th>
                    <th scope="col">Notas</th>
                    <th scope="col"></th>
                  </tr>
                </thead>
                <tbody id="tablaActualizaciones">

                </tbody>
              </table>
              <button class="btn btn-info btn-icon-split btn-sm float-end btn-add-actualizacion mt-4" data-bs-toggle="modal" data-bs-target="#modalAdd"><span class="icon text-white-50"><i class="fa-solid fa-plus add-actualizacion" aria-hidden="true"></i></span><span class="text">Añadir nueva</span></button>

            </div>
          </div>
          <!-- Modal para añadir actualizaciones-->
          <div class="modal" id="modalAdd" tabindex="-1">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title">Añadir nueva actualización</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <select class="form-select form-select-sm mb-3" id="selectPlataformas" aria-label=".form-select-sm example">
                    <option selected>Plataforma</option>
                  </select>
                  <select class="form-select form-select-sm mb-3" id="selectConjuntoAtributos" aria-label=".form-select-sm example">
                    <option value="" selected>Conjunto de atributos</option>
                  </select>
                  <select class="form-select form-select-sm mb-3" id="selectFabricantes" aria-label=".form-select-sm example">
                    <option value="" selected>Fabricante</option>
                  </select>
                  <div class="row g-3 align-items-center mb-3">
                    <div class="col-3">
                      <label for="inputPrecioDesde" class="col-form-label">Precio desde</label>
                    </div>
                    <div class="col-3">
                      <input type="number" id="inputPrecioDesde" class="form-control">
                    </div>
                    <div class="col-3">
                      <label for="inputPrecioHasta" class="col-form-label">hasta</label>
                    </div>
                    <div class="col-3">
                      <input type="number" id="inputPrecioHasta" class="form-control">
                    </div>

                  </div>
                  <select class="form-select form-select-sm mb-3" id="selectHora" aria-label=".form-select-sm example">
                    <option selected>Hora</option>
                    <option value="0">00:00</option>
                    <option value="1">01:00</option>
                    <option value="2">02:00</option>
                    <option value="3">03:00</option>
                    <option value="4">04:00</option>
                    <option value="5">05:00</option>
                    <option value="6">06:00</option>
                    <option value="7">07:00</option>
                    <option value="8">08:00</option>
                    <option value="9">09:00</option>
                    <option value="10">10:00</option>
                    <option value="11">11:00</option>
                    <option value="12">12:00</option>
                    <option value="13">13:00</option>
                    <option value="14">14:00</option>
                    <option value="15">15:00</option>
                    <option value="16">16:00</option>
                    <option value="17">17:00</option>
                    <option value="18">18:00</option>
                    <option value="19">19:00</option>
                    <option value="20">20:00</option>
                    <option value="21">21:00</option>
                    <option value="22">22:00</option>
                    <option value="23">23:00</option>
                  </select>
                  <div class="row g-3 align-items-center mb-3">
                    <div class="col-3">
                      <label for="inputMargen" class="col-form-label">Margen</label>
                    </div>
                    <div class="col-3">
                      <input type="number" id="inputMargen" class="form-control">
                    </div>


                  </div>
                  <div class="row g-3 align-items-center">
                    <div class="mb-3">
                      <div class="form-floating">
                        <textarea class="form-control" placeholder="Leave a comment here" id="notasPedido" style="height: 100px"></textarea>
                        <label for="floatingTextarea2"><svg fill="none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24">
                            <path d="M22 2H2v14h2V4h16v12h-8v2h-2v2H8v-4H2v2h4v4h4v-2h2v-2h10V2z" fill="currentColor"></path>
                          </svg> Notas</label>
                      </div>
                    </div>
                  </div>

                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                  <button type="button" class="btn btn-primary" id="btn-add-actualizacion">Añadir</button>
                </div>
              </div>
            </div>
          </div>
          <!-- Fin Modal para añadir actualizaciones-->

          <!-- modal éxito -->
          <div class="modal" tabindex="-1" id="modalExito">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="tenor-gif-embed" data-postid="25005258" data-share-method="host" data-aspect-ratio="0.953125" data-width="100%"><a href="https://tenor.com/view/solar-highway-great-success-cryptid-friendclub-gif-25005258">Solar Highway Great Success GIF</a>from <a href="https://tenor.com/search/solar+highway-gifs">Solar Highway GIFs</a></div>
                <script type="text/javascript" async src="https://tenor.com/embed.js"></script>
              </div>
            </div>
          </div>
          <!-- fin modal éxito -->

                    <!-- modal confirmar -->
                    <div class="modal" tabindex="-1" id="modalConfirmar">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Eliminar entrada</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p>Vamos a eliminar esta actualización de la base de datos. ¿Lo hacemos?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary" id="eliminaActualizacion" data-bs-dismiss="modal">Dale</button>
      </div>
    </div>
  </div>
</div>
          <!-- fin modal éxito -->



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
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
  <!-- Core plugin JavaScript-->
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="js/sb-admin-2.min.js"></script>

  <!-- Page level plugins -->
  <script src="vendor/chart.js/Chart.min.js"></script>

  <!-- Page level custom scripts -->
  <script src="js/demo/chart-area-demo.js"></script>
  <script src="js/demo/chart-pie-demo.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jeditable.js/2.0.17/jquery.jeditable.min.js"></script>
  <script type="text/javascript" src="https://cdn.datatables.net/v/bs5/dt-1.12.1/sp-2.0.1/sl-1.4.0/datatables.min.js"></script>
</body>

</html>
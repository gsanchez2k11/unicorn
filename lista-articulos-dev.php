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
  <script src="js/nouislider.js"></script>
  <script src="js/lista-articulos.js"></script>
  <!--<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.12.1/datatables.min.js"></script>-->

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
          * en ese caso mostramos la caja de búsqueda grande
          */
          if (filter_input(INPUT_GET, "pl", FILTER_SANITIZE_FULL_SPECIAL_CHARS)) : ?>
            <?php
            $plataforma = filter_input(INPUT_GET, "pl", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            //  print_r($plataforma);
            ?>
            <script>
              $('.loader').show();
              var slider = [];
              var rangeSliderValueElement = [];
              var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
              var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
              })


              $(document).ready(function() {
                /// VENTANAS MODALES
                exampleModal = new bootstrap.Modal(document.getElementById('sorpresa-modal'), { //Modal de ejemplo
                  keyboard: false
                });
                exitoModal = new bootstrap.Modal(document.getElementById('exito-modal'), { //Modal de exito
                  keyboard: false
                })
                /*  exitoActualizacionModal = new bootstrap.Modal(document.getElementById('exito-actualizacion'), {    //Modal de exito
                    keyboard: false
                  })*/

                comisionModal = new bootstrap.Modal(document.getElementById('modificar-cantidad-modal'), { //Modal para añadir una comisión
                  keyboard: false
                })


                //gestionContadores();

                /******************************************************************************/
                //          FUNCIONES
                /******************************************************************************/

                /**
                 * Repasamos las custodias
                 * @param  [type] articuloInforpor [description]
                 * @return [type]                  [description]
                 */
                function quedanEnCustodia(articuloInforpor) {
                  let custodias = 0;
                  if (typeof articuloInforpor !== 'undefined' && articuloInforpor.hasOwnProperty('custodias') && articuloInforpor.custodias.length > 0) {
                    for (i in articuloInforpor.custodias) {
                      custodias += parseInt(articuloInforpor.custodias[i].quedan);
                    }
                  }
                  return custodias;
                }

                plataforma = '<?= $plataforma ?>'; //Creamos la variable plataforma
                document.getElementById('nav-'+plataforma).classList.add('bg-light'); //Ponemos fondo al botón de la plataforma que estamos mostrando
                //  var todasOfertasPcc = dameListadoJson('todas las ofertas pcc');  //Pedimos el CSV con todas las ofertas de pcc
                //          var actualizacion = dameListadoJson('ultima actualizacion '+plataforma); //Cargamos la actualización de la plataforma correspondiente
                //console.log(todasOfertasPcc);
                var ultimosPedidos = dameListadoJson('ultimos pedidos mirakl'); //Recuperamos los úlitmos 100 pedidos
                //  listapds = colocaDatosPedido(ultimosPedidos);                                   //Lo movemos a mijs y creamos una funcion
                let articulosConPedidos = dameArticulosConPedidos(ultimosPedidos);
                //  console.log(listapds);


                //Vamos a recorrerlas ofertas de Pc componentes
                /*for (var ofe of todasOfertasPcc) {
            console.log(ofe);
          }*/

                //      $('#hora-margenes').html(actualizacion.fecha_margenes);             //colocamos la fecha y hora de las actualizaciones que estamos mostrando
                //      $('#hora-posiciones').html(actualizacion.fecha_posiciones);

                /*      $(".editable-text").editable(function(){
          alert('venga vamos');
        });*/

                //console.log(actualizacion);

                //Comprobamos si la plataforma es pccomponentes
                //        if (plataforma == 'pcc') {
                //var actualizacion = cargaUltimaActualizacion();


                //      }

                $(document).on('click', '.switch-favorito', (event) => {
                  event.preventDefault();
                  let actual = event.target;
                  let atributos = JSON.parse(actual.parentNode.parentNode.parentNode.getAttribute('data-atributos'));
                  let entidad = event.currentTarget.parentElement.parentElement.getAttribute('entidad');
                  let valorActual = actual.classList.toggle('far');
                  switchAtributo(entidad, valorActual, 12);

                  //    console.log( );
                  actual.classList.toggle('far');
                  actual.classList.toggle('fas');

                })



              });

              /***************************************************************************/
              //EVENTOS
              /*****************************************************************************/

              $(document).on('click', '.btn-guardar-comentario', (event) => {
                let este = $(event.currentTarget);
                let inputValor = $(este).parent().find('input').val();
                let entidad = $(este).parentsUntil('ul').filter('li').data('id');
                if (inputValor.length <= 3) { //Si intentamos grabar un comentario con menos de  4 caracteres nos quejamos
                  $(este).parentsUntil('.container-fluid').filter('.row').prepend('<div class="alert alert-info" role="alert">Eso no es un comentario, es un estornudo. Escribe algo digno y vuelve a intentarlo, haz el favor</div>');
                } else {
                  $('.alert-info').remove();
                  var datos = {
                    entidad: entidad,
                    comentario: inputValor
                  };

                  $.ajax({
                    type: "POST",
                    url: "ajax/unicorn_db/guardar-comentario-entidad.php",
                    data: datos,
                    success: function(data, textStatus, jqXHR) {
                      //  alert(JSON.stringify(jqXHR));
                      // location.reload();
                      //      console.log(data);
                      if (data === 'ok') {
                        //<img src="img/sorpresa-unicornio.gif" class="img-fluid" alt="Haber elegido muerte">
                        //$(exampleModal).find('img').attr('src','img/amusing-unicorn.gif');
                        //Quitamos el icono con el comentario
                        $('.list-group-item.container-fluid[data-id="' + entidad + '"] .botones').append('<i data-bs-toggle="tooltip" data-bs-placement="bottom" title="' + inputValor + '" class="fas fa-comment"></i>');

                        exitoModal.show();

                      }

                      //   alert('sorpresa');
                      //})
                      //    console.log('ok');
                      //  return data;
                      // $(bloque).append(data);
                    },
                    error: function(data, textStatus, jqXHR) {
                      // alert("error:" + respuesta);
                      // console.log(respuesta);
                      alert('Error: ' + data);
                      //location.reload();
                    },
                  });


                }

              })
              //exitoModal
              //CApturamos el click en el modal de añadir comision y mostramos el modal
              $(document).on('click', '.boton-add-comision', (event) => {
                event.preventDefault();
                //  console.log($(event.currentTarget).data('categoria'));
                $('#etiqueta-comision').html($(event.currentTarget).data('categoria'));
                comisionModal.show();
              });


              //CApturamos el click en el botón de elimnar comentario, dentro del modal
              $(document).on('click', '.btn-sorpresa', (event) => {
                exampleModal.show();
                let este = $(event.currentTarget);
                var entidad = $(este).parentsUntil('ul').filter('li').data('id');
                //Capturamos ahora el click en el botón de elimianr
                $(document).on('click', '#elimina-comentario', (event) => {
                  //Eliminamos el comentario de la base de datos
                  var datos = {
                    entidad: entidad
                  };
                  $.ajax({
                    type: "POST",
                    url: "ajax/unicorn_db/eliminar-comentario-entidad.php",
                    data: datos,
                    success: function(data, textStatus, jqXHR) {
                      if (data === 'ok') {
                        //Quitamos el icono con el comentario
                        $('.list-group-item.container-fluid[data-id="' + entidad + '"] i.fas.fa-comment').hide();
                        //Quitamos el valor del input
                        $('.list-group-item.container-fluid[data-id="' + entidad + '"] input.input-comentario').val('');
                        exampleModal.hide();
                      }
                    },
                    error: function(data, textStatus, jqXHR) {
                      alert('Error: ' + data);
                    },
                  });
                  //console.log(entidad);
                })

                //   alert('sorpresa');
              });




              //Capturamos cualquier cambio en el switch que marca si mostramos los precios con o sin iva
              var switchIva = document.getElementById('flexSwitchIVA');
              $(document).on('change', switchIva, function(e) {
                //Comprobamos el estado del check
                let isCheck = e.target.checked;
                let conIva = document.querySelectorAll('.con-iva');
                let sinIva = document.querySelectorAll('.sin-iva');
                let MostrarCon = document.getElementById('mostrar-con-iva');
                let MostrarSin = document.getElementById('mostrar-sin-iva');
                if (isCheck === true) {
                  MostrarCon.style.display = 'inline-block';
                  MostrarSin.style.display = 'none';
                  conIva.forEach((i) => {
                    i.style.display = 'block';
                  });
                  sinIva.forEach((i) => {
                    i.style.display = 'none';
                  });
                } else {
                  MostrarSin.style.display = 'inline-block';
                  MostrarCon.style.display = 'none';
                  conIva.forEach((i) => {
                    i.style.display = 'none';
                  });
                  sinIva.forEach((i) => {
                    i.style.display = 'block';
                  });
                }
              });

              //Capturamos el click para generar el enlace de pc componentes
              //btn-link-pcc
              /*let btnLinkPcc = document.getElementById('btn-link-pcc');
              btnLinkPcc.addEventListener('click', function(e){
              console.log('hemos pulsado');
              })*/

let actualizaStockLocal = document.getElementById('stock-local');
actualizaStockLocal.addEventListener('click', function(){
console.log('stock');
})

            </script>

          <?php endif ?>
          <!-- Page Heading -->
     <!--     <div class="alert alert-info alert-dismissible fade show" role="alert">
            <img src="img/unicornio-mascara.png" alt="" style="width: 64px">
            <strong>seeeeeeeeee</strong>, mejorando enlaces a Pc componentes.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
          <div class="alert alert-light" role="alert">
            Márgenes y stocks actualizados a <b id="hora-margenes"></b> . Posiciones <span class="editable-text">actualizadas</span> a <b id="hora-posiciones"></b>
          </div>-->
          <!-- Content Row -->
          <div class="row bg-white">
          <nav class="navbar ">
  <div class="container-fluid">
    <a class="navbar-brand p-2" href="<?= $_SERVER['PHP_SELF']?>?pl=pcc" id="nav-pcc">
      <img src="data:image/jpeg;base64,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" alt="Logo" width="30" height="30" class="d-inline-block align-text-top">
      Pc componentes
    </a>
    <a class="navbar-brand p-2" href="<?= $_SERVER['PHP_SELF']?>?pl=mediamarkt" id="nav-mediamarkt">
      <img src="https://cms-images.mmst.eu/osyynfyvlyjc/16vN6z9pC3Y7iy7NLvTb40/ef5c351f5e5d41f7c6d27b7588f24488/MM-LOGO.png?q=80" alt="Logo" width="30" height="30" class="d-inline-block align-text-top">
      Media Markt
    </a>
    <a class="navbar-brand p-2" href="<?= $_SERVER['PHP_SELF']?>?pl=miravia" id="nav-miravia">
      <img src="https://img.mrvcdn.com/g/tps/imgextra/i3/O1CN01POpHi51ZIp7E0pdw1_!!6000000003172-2-tps-360-360.png" alt="Logo" width="30" height="30" class="d-inline-block align-text-top">
      Miravia
    </a>
    <a class="navbar-brand p-2" href="<?= $_SERVER['PHP_SELF']?>?pl=correos" id="nav-correos">
      <img src="data:image/png;base64,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" alt="Logo" width="30" height="30" class="d-inline-block align-text-top">
      Correos
    </a>
  </div>
</nav>
            <div class="card shadow my-3">
            <!--  <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Articulos en Pc componentes</h6>          
              </div>-->
              <div class="card-body">
                <div class="table-responsive">
                  <table class="table table-bordered" id="tablaArticulos" width="100%" cellspacing="0">
                    <thead>
                      <tr>
                        <th></th>
                        <th>mpn</th>
                        <th>descripción</th>
                        <th>stock</th>
                        <th>precio</th>
                        <th>margen</th>
                        <th>posicion</th>
                        <th></th>
                        <th></th>
                      </tr>
                    </thead>
                    <tfoot>
                      <tr>
                        <th></th>
                        <th>mpn</th>
                        <th>descripción</th>
                        <th>stock</th>
                        <th>precio</th>
                        <th>margen</th>
                        <th>posicion</th>
                        <th></th>
                        <th></th>
                      </tr>
                    </tfoot>
                    <tbody>
                      <!-- Aquí van los pedidos -->
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
            <ul class="list-group list-group-flush" id="lista-articulos">
              <!--aquí va el listado de artículos -->
            </ul>

          </div>
          <div class="row">
            <div class="col-lg-12 mb-4">
            <div class="card shadow mb-4">
                  <div class="card-header py-3" data-bs-toggle="collapse" href="#collapseFeedback" role="button" aria-expanded="false" aria-controls="collapseFeedback">
                    <h6 class="m-0 font-weight-bold text-danger">¿Necesitas ayuda?</h6>
                  </div>
                  <div class="card-body collapse hide" id="collapseFeedback">
                    <h5 class="text-info">¿Qué puedo hacer desde esta página?</h5>
                    <p>Desde está página puedes consultar, modificar e incluso eliminar las ofertas disponibles en Pc componentes.</p>
                    <h5 class="text-info">¿Cómo funciona?</h5>
                    <p>Al cargar la página pedimos el listado de ofertas en tiempo real a PC componentes, eso lo juntamos con los datos almacenados en nuestra propia base de datos para ese artículo. Una vez hecho esto te lo mostramos en una bonita y práctica tabla.</p>
                    <h5 class="text-info">¿Entonces no es todo en tiempo real?</h5>
                    <p>No. La posición en Pc componentes sólo se puede calcular pidiendo los datos de cada oferta por separado, lo que llevaría muchísimo tiempo. Con los datos de compra ocurre lo mismo. De hecho, el principal propósito de esta tabla es la de identificar necesidades y datos que necesiten nuestra atención.</p>
                  </div></div>
            </div>
            </div>

          <!-- Modal de ejemplo -->
          <div class="modal" id="sorpresa-modal" tabindex="-1">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title">Grrrrr que susto!</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <img src="img/sorpresa-unicornio.gif" class="img-fluid" alt="Haber elegido muerte">
                  <p>¿Estás segur@ de eliminar este comentario?</p>
                  <button type="button" class="btn btn-danger" id="elimina-comentario">Eliminar comentario</button>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
              </div>
            </div>
          </div>
          <!-- Fin del Modal de ejemplo -->

          <!-- Modal para añadir comisión -->
          <div class="modal" id="modificar-cantidad-modal" tabindex="-1">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title">¡Suelta el secreto!</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                  <div class="mb-3">
                    <label for="inputCantidad" class="form-label">¿Cúal es la comisión para la categoria <b id="etiqueta-comision"></b> ?</label>
                    <input type="number" class="form-control" id="inputCantidad" aria-describedby="comisionHelp">
                    <div id="comisionHelp" class="form-text">Introduce sólo números enteros.</div>
                  </div>
                  <button type="button" class="btn btn-danger" id="modifica-cantidad-btn">Dale</button>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
              </div>
            </div>
          </div>
          <!-- Fin del Modal de ejemplo -->
                  

          <!-- Modal de confirmación -->
          <div class="modal" id="confirmacion-modal" tabindex="-1">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title">¿Seguro que quieres hacer esto?</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <div class="card">
                    <div style="width:100%;height:0;padding-bottom:74%;position:relative;"><iframe src="https://giphy.com/embed/lvzdeWk12qjmM" width="100%" height="100%" style="position:absolute" frameBorder="0" class="giphy-embed" allowFullScreen></iframe></div>
                    <p><a href="https://giphy.com/gifs/scared-spongebob-squarepants-nervous-lvzdeWk12qjmM">via GIPHY</a></p>
                    <div class="card-body">
                      <h5 class="card-title">Vamos a hacer algo muy interesante con tu permiso</h5>
                      <p class="card-text">¿Pero estás seguro seguro que quieres hacerlo?¿Y que debes? ¿Y que puedes?</p>
                      <div class="d-flex justify-content-evenly">
                        <button class="btn btn-success" id="btn-eliminar-oferta">vamos a darle</button>
                        <button class="btn btn-danger">miedo</button>

                      </div>
                    </div>
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
              </div>
            </div>
          </div>
          <!-- Fin del Modal de confirmación -->


          <!-- Modal de aprobación -->
          <div class="modal" id="exito-modal" tabindex="-1">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title">Otro problema resuelto!</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <iframe src="https://giphy.com/embed/QNjTSaGOEE78VD1RV0" width="480" height="480" frameBorder="0" class="giphy-embed" allowFullScreen></iframe>
                  <p><a href="https://giphy.com/gifs/AgenceWAT-excited-celebration-wat-QNjTSaGOEE78VD1RV0">via GIPHY</a></p>
                  <p>Si necesitas que te solucione otro problema solo tienes que decirmelo</p>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
              </div>
            </div>
          </div>
          <!-- Fin del Modal de aprobación -->

          <!-- Modal hemos actualizado un precio -->
          <div class="modal" id="exito-actualizacion" tabindex="1">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title">Otro problema resuelto!</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <iframe src="https://giphy.com/embed/QNjTSaGOEE78VD1RV0" width="480" height="480" frameBorder="0" class="giphy-embed" allowFullScreen></iframe>
                  <p><a href="https://giphy.com/gifs/AgenceWAT-excited-celebration-wat-QNjTSaGOEE78VD1RV0">via GIPHY</a></p>
                  <p>Bien, esto es lo que acabas de hacer: Has actualizado el artículo en Pc Componentes, y ya que estábamos y era menester lo hemos añadido (o actualizado) en The Phone House.</p>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-primary" id="volver-modal-info" data-bs-dismiss="modal" data-bs-target="#modal-info-articulo" data-bs-toggle="modal">Volver a la info</button>
                  <button type="button" class="btn btn-secondary" id="cerrar-modal-exito" data-bs-dismiss="modal" data-bs-toggle="modal">Cerrar</button>

                </div>
              </div>
            </div>
          </div>
          <!-- Fin del Modal hemos actualizado un precio -->

          <!-- Modal nuevo con los detalles -->
          <div class="modal " id="modal-info-articulo" tabindex="-1">
            <div class="modal-dialog modal-xl">
              <div class="modal-content">
                <div class="modal-header">

                  <h5 class="modal-title"><a id="btn-link-pcc" href="#" target="_blank"><img src="data:image/jpeg;base64,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" class="img-thumbnail mr-2" style="width: 36px;cursor:pointer" alt="logo pc componentes"></a><i class="fa-regular fa-heart text-info mr-2 " style="cursor:pointer" id="articulo-favorito"></i><i class="fa-solid fa-arrows-rotate text-info mr-2 " style="cursor:pointer" id="actualizable"></i><i class="fa-solid fa-message text-gray-200 mr-2" style="cursor:pointer" id="mostrar-comentarios" data-bs-toggle="tooltip" data-bs-placement="top" title="Tooltip on top"></i><span id="nombre-articulo"></span></h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <div class="row p-3" id="alerta-varias-ofertas" style="display:none">
                    <div class="alert alert-warning" role="alert">
                      Tenemos más de una oferta para este artículo, ojo
                    </div>
                  </div>
                  <div class="row px-2 visually-hidden" id="bloque-comentarios">
                  <div class="row px-2" id="alerts-comentarios">

                    </div>
                  <div class="row p-3">
                    <div class="input-group mb-3">
                      <input type="text" class="form-control" placeholder="Tu comentario" aria-label="Comenta algo" aria-describedby="button-addon2">
                      <button class="btn btn-secondary" type="button" id="enviar-comentario">Enviar</button>
                    </div>
                  </div>
                  </div>
                  <h3 class="row bg-info text-white shadow p-3 m-1">
                    <div class="col-10">
                      Stock
                    </div>
                    <div class="col" id="col-stock">

                    </div>
                  </h3>

                  <div class="row p-3">
                    <div class="col-4">
                      <ul class="list-group">
                        <li class="list-group-item d-flex justify-content-between align-items-center list-group-item-info">
                          <img src="https://www.inforpor.com/portada/estilo/imagenes/logo.svg" alt="logo inforpor" style="width:80px">
                          <span class="rounded-pill lista-stock" id="stock-total-inforpor" style="font-size:1.6em"></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                          <i class="fas fa-warehouse" aria-hidden="true"></i> stock normal
                          <span class="rounded-pill lista-stock" id="stock-normal-inforpor" style="font-size: 1.4em"></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                          <i class="fas fa-warehouse" aria-hidden="true"></i> reservas
                          <span class="rounded-pill lista-stock" id="stock-reserva-inforpor" style="font-size: 1.4em"></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                          <i class="fas fa-shield-alt" aria-hidden="true"></i> custodias
                          <span class="rounded-pill lista-stock" id="stock-custodia-inforpor" style="font-size: 1.4em"></span>
                        </li>
                      </ul>
                      <ul class="list-group mt-2">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                          <i class="fas fa-horse fa-2x" aria-hidden="true"></i> stock local
                          <p class="rounded-pill lista-stock" id="stock-local" style="font-size: 1.6em; cursor: pointer" data-bs-toggle="modal" data-bs-target="#modificar-cantidad-modal" data-bs-dismiss="modal"></p>
                        </li>
                      </ul>
                      <ul class="list-group mt-2">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                          <i class="fas fa-wave-square fa-2x" aria-hidden="true"></i> modificador
                          <span class="rounded-pill lista-stock" id="stock-modificador" style="font-size: 1.6em"></span>
                        </li>
                      </ul>
                    </div>
                    <div class="col-8" style="text-align: center">
                      <div class="chart-area"><canvas id="myAreaChartStock"></canvas></div>
                    </div>
                  </div>
                  <h3 class="row bg-info text-white shadow p-3 m-1">
                    <div class="col-10">
                      Margen
                    </div>
                    <div class="col" id="col-margen">

                    </div>
                  </h3>
                  <div class="row p-3">
                    <div class="col-6" style="text-align: center">
                      <div class="chart-area"><canvas id="myAreaChartPrecio"></canvas></div>
                    </div>
                    <div class="col-6">
                      <ul class="list-group">
                        <li class="list-group-item d-flex justify-content-between align-items-center">Precio de venta total <span id="precio-venta" class="con-iva"></span><span id="precio-venta-sin-iva" style="display: none" class="sin-iva"></span><span id="slider-nuevo-precio" class="nuevo-precio" style="display: none"></span></li>
                        <li class="list-group-item">
                          <div class="row">
                            <div class="col-9" id="col-comision">
                              Comisión ( <span id="cat-pcc"></span> - <span id="porcent-comision"></span> )

                            </div>
                            <div class="col-3 text-end con-iva" id="importe-comision">
                            </div>
                            <div class="col-3 text-end sin-iva" id="importe-comision-sin-iva" style="display: none">
                            </div>
                            <div class="col-3 text-end nuevo-precio" id="slider-importe-comision" style="display: none">
                            </div>
                          </div>
                          <div class="row" id="alerta-comision" style="display: none">
                            <div class="alert alert-danger" role="alert">No podemos calcular el margen al no disponer de la comisión.<a href="#" role="button" class="boton-add-comision" data-categoria="X1YKHG">¿La conoces?</a></div>
                          </div>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">Precio de venta sin comisión <span id="precio-sin-comision" class="con-iva"></span><span id="precio-sin-comision-sin-iva" class="sin-iva" style="display:none"></span><span id="slider-precio-sin-comision" class="nuevo-precio" style="display: none"></span></li>
                      </ul>
                      <ul class="nav nav-tabs mt-2" id="nav-compras" style="display: none" role="tablist"></ul>
                      <div class="tab-content" id="tab-compras">
                        <!--Contenido de las pestañas con los datos de compra-->
                      </div>
                      <div class="row m-2">
                        <div class="col-12">
                          <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="flexSwitchIva" checked>
                            <label class="form-check-label" for="flexSwitchCheckDefault">Precios <span id="mostrar-con-iva">con</span><span id="mostrar-sin-iva" style="font-weight: 600; display: none">sin</span> IVA</label>
                          </div>
                        </div>
                      </div>

                      <div class="row m-2 p-2">
                        <a href="#" class="btn btn-success btn-icon-split" id="btn-cambiar-precio">
                          <span class="text">Cambiar precio</span>
                          <span class="icon text-white-50">
                            <i class="fa-solid fa-dollar-sign"></i>
                          </span>

                        </a>
                      </div>

                      <div class="row m-2 p-2">
                        <div id="slider"></div>
                      </div>
                      <div class="row m-2 p-2">
                        <a href="#" class="btn btn-success btn-icon-split" id="btn-enviar" style="display: none" data-bs-target="#exito-actualizacion" data-bs-toggle="modal" data-bs-dismiss="modal">
                          <span class="text">Enviar</span>

                        </a>
                      </div>
                    </div>

                  </div>
                  <h3 class="row bg-info text-white shadow p-3 m-1">
                    <div class="col-11">
                      Posición
                    </div>
                    <div class="col" id="col-posicion">

                    </div>
                  </h3>
                  <div class="row p-3" id="lista-ofertas">
                    <div class="row mb-1 pb-1 border-bottom">
                      <div class="col-1"><i class="fab fa-slack-hash" aria-hidden="true"></i></div>
                      <div class="col-3"><i class="fas fa-store" aria-hidden="true"></i> tienda</div>
                      <div class="col-1"><i class="fas fa-star" aria-hidden="true"></i><i class="fas fa-star" aria-hidden="true"></i><i class="fas fa-star" aria-hidden="true"></i><i class="far fa-star" aria-hidden="true"></i><i class="far fa-star" aria-hidden="true"></i></div>
                      <div class="col-2"><i class="fas fa-truck" aria-hidden="true"></i> envío</div>
                      <div class="col-1 text-center"><i class="fas fa-truck" aria-hidden="true"></i> <i class="fas fa-coins" aria-hidden="true"></i></div>
                      <div class="col-2 text-center"><i class="fas fa-tag" aria-hidden="true"></i></div>
                      <div class="col" id="dif-futura"></div>
                    </div>
                  </div>
                  <h3 class="row bg-info text-white shadow p-3 m-1">
                    <div class="col-11">
                      Más info
                    </div>
                  </h3>
                  <div class="row">
                    <ul class="list-group">
                      <li class="list-group-item d-flex justify-content-between align-items-center">EAN<span class="rounded-pill comision-inicial" id="ean"></span></li>
                      <li class="list-group-item d-flex justify-content-between align-items-center">SKU<span class="rounded-pill comision-inicial" id="sku"></span></li>
                    </ul>
                  </div>
                  <h3 class="row bg-info text-white shadow p-3 m-1">
                    <div class="col-11">
                      Acciones
                    </div>
                  </h3>
                  <div class="row">
                    <div class="col-1">                  
                    <button class="btn btn-danger btn-lg" id="eliminar-oferta" data-bs-toggle="modal" data-bs-target="#confirmacion-modal" data-bs-dismiss="modal">
                      <i class="fas fa-trash"></i>
                    </button>
                    </div>
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
              </div>
            </div>
          </div>
          <!-- Fin del Modal hemos actualizado un precio -->
<!-- INICIO modal con el resultado de eliminar un artículo -->
<div class="modal" tabindex="-1" id="modal-resultado-eliminar">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Resultado</h5>
        <button type="button" class="btn-close" data-bs-toggle="modal" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p id="txt-resultado"><!-- Aqui ponemos el texto --></p>
      </div>
    <!--  <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#confirmacion-modal" data-bs-dismiss="modal">Close</button>

      </div>-->
    </div>
  </div>
</div>
<!-- INICIO modal con el resultado de eliminar un artículo -->
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
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous"></script>

  <!-- Core plugin JavaScript-->
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="js/sb-admin-2.min.js"></script>
  <script src="js/fnac.js"></script>
  <!-- Page level plugins -->
  <!--<script src="vendor/chart.js/Chart.min.js"></script>-->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.8.0/chart.min.js" integrity="sha512-sW/w8s4RWTdFFSduOTGtk4isV1+190E/GghVffMA9XczdJ2MDzSzLEubKAs5h0wzgSJOQTRYyaz73L3d6RtJSg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns@2.0.0/dist/chartjs-adapter-date-fns.min.js"></script>
  <!--<script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
<script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>-->
  <script type="text/javascript" src="https://cdn.datatables.net/v/bs5/dt-1.12.1/sp-2.0.1/sl-1.4.0/datatables.min.js"></script>
  <script src="js/datatables/lista-articulos.js"></script>
  <!-- Page level custom scripts -->
  <!--<script src="js/demo/chart-area-demo.js"></script>
  <script src="js/demo/chart-pie-demo.js"></script>-->
</body>

</html>
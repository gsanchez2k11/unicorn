<?php
/**
 * Jugar con la tarifa de inforpor.
 * Version 1.0 (24 Agosto 21)
 *  - Al añadir un artículo a las plataformas no aparece como añadido al recargar hasta que no haya pasado al menos una actualización.
 */
 ?>

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
          $('.loader-text').html('Haciendo cosas raras');                       //Cambiamos el texto del loader al iniciar
          $('.loader').show();                                                  //Activamos el loader

/**
 * Función que carga la tarifa de inforpor desde el fichero y devuelve un JSON con la tarifa completa
 */
          async function cargaTarifa() {
          //  let url = 'ajax/inforpor/dame-tarifa.php';
let url = 'var/import/inforpor.json';
            let tarifa = await JSON.parse($.ajax({
              type: "GET",
              url: url,
              dataType: 'json',
              //data: datos,
              global: false,
              async:false,
              success: function(data, textStatus, jqXHR) {
                return data;
              },
              error: function(data, textStatus, jqXHR) {
                console.log('Error al cargar el pedido: ' + JSON.stringify(data));
              }
            }).responseText);

            return tarifa;
          }

/**
 * Crea la entidad con los datos que tenemos
 * @return int id de la entidad cre
 */
function grabaEntidadEan(miArticulo){
  let arrDatos = [[miArticulo.tipoRef, miArticulo.referencia.trim()]];
  let datos = Object.fromEntries(arrDatos);
  let entidadCreada = crearEntidad(datos);
  return entidadCreada;
}



////////////////////////////////////////////////////////////////////////////////
// GUIÓN DE LA APLICACIÓN
////////////////////////////////////////////////////////////////////////////////
          $(document).ready(function(){
            /// VENTANAS MODALES
      /*    var  ResultadoActualizarModal = new bootstrap.Modal(document.getElementById('resultado-actualizar-modal'), {    //Modal de ejemplo
              keyboard: false
            });*/
var modalPcc = document.getElementById('modal-pcc');

            modalPcc.addEventListener('hide.bs.modal', function (event) { //Lanzamos las acciones al cerrar el modal
              $('.modal-body').children().hide();
          //    $('.si-codigo').hide();                                                         //En cada click ocultamos nuevamente los bloques
          //    $('.no-codigo').hide();
              $('.no-alta-posible-pcc').remove();
              $('.form-control.sku-pcc').val(''); //Borramos el input
            })


var modalFnac = document.getElementById('modal-fnac');
modalFnac.addEventListener('hide.bs.modal', function (event) { //Lanzamos las acciones al cerrar el modal
  $('.modal-body').children().hide();
//  $('.error-alta-fnac').hide();
//  $('.si-existe-fnac').hide();
})

          });
          $(document).on('click','.llama-batman', (event) => {
let mpn = $(event.currentTarget).parent().parent().attr('referencia').trim();
let codInfo = $(event.currentTarget).parent().parent().attr('codigo').trim();
let ean = $(event.currentTarget).parent().parent().attr('ean').trim();
let miArticulo = {
  tipoRef: 'ean',
  referencia: ean
};

buscaEntidadBbdd(miArticulo).then(function(respuesta){
  console.log('respuesta ' + JSON.stringify(respuesta));
  if(Object.keys(respuesta).length === 0) { // si no existe la entidad la creamos
let grabaEntidad = grabaEntidadEan(miArticulo);
    entidad = grabaEntidad; //Recuperamos la id de la entidad recien creada
    ean = miArticulo.referencia.trim();       //Aislamos el EAN para hacer la búsqueda
    //  console.log(datos);
//      console.log('datts ' + entidad);

  } else {
    entidad = Object.keys(respuesta)[0];
//    console.log('respuesta ' + JSON.stringify(respuesta));
//    console.log('entidad ' + JSON.stringify(entidad));
  }

//console.log('entidad total' + JSON.stringify(entidad));
$('#add-web-fnac').attr('data-entidad',entidad);
return entidad;
}).then(function(entidad){
  //console.log('xx ' + entidad);
  $('#add-web-fnac').attr('data-codinfo',codInfo);

  let datos = {mpn: mpn};
  let resultado = buscaOfertaFnac(datos); //Recibimos un JSON con el resultado
  let existe = resultado['@attributes']['status'];
  if (existe === 'OK') {
    //Grabamos o actualizamos la referencia de la fnac
    let sku_fnac = resultado.offer.product_fnac_id;
  let datos = {
    entidad: entidad,
    atributo: 10,
    valor: sku_fnac
  };
    addAttrValor(datos)
    $('.si-existe-fnac').show();
  } else {
      $('.no-existe-fnac').show();
  }
});


          });
          $(document).on('click','.cat-apulta', (event) => {

            $('#fetch-pcc-info').show();
let codInfo = $(event.currentTarget).parent().parent().attr('codigo').trim();
                $('#add-web-pcc').attr('data-codinfo',codInfo);
            //     let resultado = daleTiempo(event);

            let ean = $(event.currentTarget).parent().parent().attr('ean').trim();
            let miArticulo = {
              tipoRef: 'ean',
              referencia: ean
            };

            buscaEntidadBbdd(miArticulo).then(function(respuesta){
              console.log(JSON.stringify(respuesta));
              //  console.log(respuesta[Object.keys(respuesta)[0]].hasOwnProperty('referencias'));

              if (Object.keys(respuesta).length  > 0 && respuesta[Object.keys(respuesta)[0]].hasOwnProperty('referencias') === true) { //Si no es undefined es que tenemos el código de pc componentes
                $('.si-codigo').show();
                let skuPcc = respuesta[Object.keys(respuesta)[0]].referencias.pccomp;
                let loTenemos = loTenemosPcc(skuPcc);                           //Buscamos el artículo en PC componentes
                if (loTenemos === false) {                                      //Si no lo tenemos en pc componentes mostras el botón para añadirlo
                  $('.alta-pcc').show();
                } else {                                                        //Si ya lo tenemos lo que vamos a hacer es mostrar un mensaje avisando
                  $('.ya-lo-tenemos-pcc').show();
                }
              } else {
                let entidad = 0;
                let ean = 0;
                if(Object.keys(respuesta).length === 0) { // si no existe la entidad la creamos
              /*    let arrDatos = [[miArticulo.tipoRef, miArticulo.referencia.trim()]];
                  let datos = Object.fromEntries(arrDatos);
                  let entidadCreada = crearEntidad(datos);*/
                  let entidadCreada  = grabaEntidadEan(miArticulo);
                  entidad = entidadCreada; //Recuperamos la id de la entidad recien creada
                  ean = miArticulo.referencia.trim();       //Aislamos el EAN para hacer la búsqueda
                  //  console.log(datos);
                  //  console.log('datts ' + entidadCreada);

                } else {
                  //Recorremos los atributos para obtener el ean
                  let attrs = respuesta[Object.keys(respuesta)[0]];
                  for (i in attrs) {
                    ean = attrs[i].atributo == 3 ? attrs[i].valor : 0;
                  }
                  entidad = Object.keys(respuesta)[0];
                }

                let datos = {
                  entidad:entidad,
                  ean: ean
                };

                $('#add-codigo-pcc').attr('data-atributos',JSON.stringify(datos));
                $('#fetch-pcc-info').attr('data-ean',ean).attr('data-entidad',entidad);
              //  $('#add-web-pcc').attr('data-inforpor',JSON.stringify(atributos).replace(/[\/\(\)\']/g, "&apos;"));

                $('.no-codigo').show();  //Mostramos el bloque con la informacion

              }
          //    modalPcc.show();
            });


          });

          $(document).on('click','#add-codigo-pcc', (event) => {

            let datosRecibidos = $(event.currentTarget).data('atributos');
            let inputRecibido = $(event.currentTarget).prev().val();
            datosRecibidos.atributo = 7;
            datosRecibidos.valor = inputRecibido;
            let resultado = addAttrValor(datosRecibidos);
            if (resultado !== 'ko') {
              $('.no-codigo').hide();
              $('.buscando-pcc').show();
              //Buscamos en PcComponentes el código recibido
              let loTenemos = loTenemosPcc(inputRecibido);                           //Buscamos el artículo en PC componentes
              $('.buscando-pcc').hide();
              if (loTenemos === false) {                                      //Si no lo tenemos en pc componentes mostras el botón para añadirlo
                $('.alta-pcc').show();
              } else {                                                        //Si ya lo tenemos lo que vamos a hacer es mostrar un mensaje avisando
                $('.ya-lo-tenemos-pcc').show();
              }
            }

            //console.log(resultado);
          });

          $(document).on('click','#fetch-pcc-info', (event) => {
            let ean = document.querySelectorAll('[data-ean]')[0].dataset.ean;

            let miArticulo = {
              tipoRef: 'EAN',
              referencia :ean
            };

            fetchmirakl(miArticulo).then(function(respuesta){
              let productos = respuesta.products;
              console.log('fetch:' + JSON.stringify(respuesta));
              //Podemos recibir un array vacio o con los datos del artículo
              if (productos.length > 0) {
                let producto = productos[0];

                let entidad = document.querySelectorAll('[data-ean]')[0].dataset.entidad;
                let atributo = 7;
                let valor = producto.product_sku;  //Capturamos el sku de pc componentes
                let datos = {
                  entidad: entidad,
                  atributo: atributo,
                  valor: valor
                };
                let resultado = addAttrValor(datos);                                          //Grabamos el código en la base de datos, si todo va bien recibimos la entidad creada
                if (parseInt(resultado) > 0) {
                  $('.no-codigo').hide();
                  $('.alta-pcc').show(); //Mostramos el cuadro con la información de que si lo tenemos y podemos crear el artículo
                }

              } else {                                                                      //si recibimos los datos
                $('#fetch-pcc-info').hide().after('<p class="no-alta-posible-pcc">Pues parece que no somos capaces de adivinar nada de este artículo. Así que salvo que tengas la referencia de Pc componentes y la escribas me temo que no será posible dar de alta la oferta</p>');
              }


            });
          });

                    $(document).on('click','#add-web-fnac', (event) => {
let codInforpor = document.querySelectorAll('#add-web-fnac[data-codinfo]')[0].getAttribute("data-codinfo"); //Cogemos el código que hemos pasado
console.log('entidad recibida ' + entidad);
let articuloInforpor = buscaArticuloInforpor(codInforpor);
//console.log('articuloInforpor' + JSON.stringify(articuloInforpor));

addOfertaFnac(articuloInforpor).then(function(addArticuloFnac){
  $('.no-existe-fnac').hide();
  $('.buscando-batch').show();
  let batchId = addArticuloFnac.batch_id; //Nos quedamos con el batch id para consultar el resultado
  let batchStatus = damebatchStatus(batchId);
  let status = batchStatus['@attributes']['status'];
  switch (status) {
    case 'ERROR':
      $('.error-alta-fnac .txt-resultado-alta').html(batchStatus.error);
      $('.error-alta-fnac').show();
      break;
      case 'OK':
        $('.exito-alta-fnac').show();
        let sku_fnac = batchStatus['product_fnac_id'];
        let datos = {
          entidad: entidad,
          atributo: 10,
          valor: sku_fnac
        };
        addAttrValor(datos)

        break;
  }
//  console.log(status);
  console.log(batchStatus);
  console.log(batchStatus['@attributes']['status']);
});

//let addArticuloFnac = addOfertaFnac(articuloInforpor);



                    });

          $(document).on('click','#add-web-pcc', (event) => {
            let codInforpor = document.querySelectorAll('#add-web-pcc[data-codinfo]')[0].getAttribute("data-codinfo"); //Cogemos el código que hemos pasado

      //      let jsonInforpor = JSON.parse(inforpor);
            //  let codInfo = inforpor.codigo;
            //console.log('codfino');
        //    console.log(jsonInforpor);
            let articuloInforpor = buscaArticuloInforpor(codInforpor);
            console.log(articuloInforpor);
            url = 'ajax/mirakl/actualizar-oferta.php';
            //Vamos a configurar los datos para la ofertas
            let precioVentaSinIva = (articuloInforpor.Precio * 1.20) + articuloInforpor.lpi;
            let precioConPortes = precioVentaSinIva <= 60 ? precioVentaSinIva + 3.20 : precioVentaSinIva;
            let precio = precioConPortes * 1.21;
            let stock = parseInt(articuloInforpor.Stock);
            let ean = articuloInforpor.EAN;
            let mpn = articuloInforpor.Referencia;

            let offer_additional_fields = [
              {
                "code": "canon",
                "type": "NUMERIC",
                "value": "0"
              },
              {
                "code": "tipo-iva",
                "type": "NUMERIC",
                "value": "21"
              }
            ];
            let logistic_class = {                                                          //De momento ponemos de manera automática la clase logistica
              "code": 'medio',
              "label": 'Medio - Entre 2 y 10 kg'
            };

            let datos = {
              //      product_id :  mpn,
              //      product_id_type : 'MPN',
              product_id :  ean,
              product_id_type : 'EAN',
              shop_sku : mpn,
              //shop_sku : 'S10281',
              quantity : stock,
              state_code: 11,
              price: precio.toFixed(2),
              update_delete: 'update',
              offer_additional_fields: offer_additional_fields,
              logistic_class: 'medio'
            };
            console.log(JSON.stringify(datos));
            var actualizarOfertas = actualizaOferta(datos,url);
            if (actualizarOfertas.pcc === 'ok' || actualizarOfertas.phh === 'ok') {
              $('.alta-pcc').hide();
              $('.si-codigo').hide();
              $('.resultado-alta').show();
              for(a in actualizarOfertas) {
                $('#res-alta'+a).append(actualizarOfertas[a]);
              }
            }




          })
          </script>


          <div class="card shadow mb-4">
            <div class="card-header py-3">
              <h6 class="m-0 font-weight-bold text-primary">Catálogo inforpor</h6>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                    <th></th>
                      <th>imagen</th>
                      <th>gama</th>
                      <th>fabricante</th>
                      <th>referencia</th>
                      <th>nombre</th>
                      <th>stock</th>
                      <th>precio</th>
                     <th></th>

                    </tr>
                  </thead>
                  <tfoot>
                    <tr>
                 <th></th>
                      <th>imagen</th>
                      <th>gama</th>
                      <th>fabricante</th>
                      <th>referencia</th>
                      <th>nombre</th>
                      <th>stock</th>
                      <th>precio</th>
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


        </div>
        <!-- /.container-fluid -->
        <!-- Modal para mostrar el resultado -->
        <div class="modal" id="modal-pcc" tabindex="-1">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title">Looking for the fiesta</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <div class="no-codigo" style="display:none">
                  <p>
                    Vaya! No reconocemos esta referencia. 2 opciones tienes: Introducir de forma manual la referencia de PcComponentes o bien intentarlo de manera externa.
                  </p>
                  <div class="input-group mb-3">
                    <input type="text" class="form-control sku-pcc" placeholder="SKU PcComponentes" aria-label="SKU del artículo en Pc componentes" aria-describedby="button-addon2">
                    <button class="btn btn-outline-secondary" type="button" id="add-codigo-pcc">Añadir</button>
                  </div>
                  <button type="button" class="btn btn-primary" id="fetch-pcc-info">Adivinar</button>

                </div>

                <p class="si-codigo" style="display:none">
                  Genial! tenemos la referencia para pc componentes de este artículo              </p>
                  <div class="alta-pcc" style="display:none">
                    <p>Este artículo no lo tenemos dado de alta, así que podemos proceder. Para agilizar se va a dar de alta con un margen del 20% y clase logística media, podrás ajsutarlo más adelante.</p>
                    <button type="button" class="btn btn-primary" id="add-web-pcc">Añadir artículo</button>
                  </div>
                  <div class="resultado-alta" style="display:none">
                    <p>Hemos dado de alta el artículo en la siguientes plataformas:</p>
                    <ul>
                      <li id="res-alta-pcc">Pc componentes</li>
                      <li id="res-alta-phh">Phone House</li>
                    </ul>

                  </div>
                  <div class="ya-lo-tenemos-pcc" style="display:none">
                    <p>Este artículo ya está dado de alta en Pccomponentes, si no está indicado en el listado general posiblemente deberás esperar a la siguiente actualización</p>
                  </div>

                  <div class="buscando-pcc" style="display:none">
                    <img src="https://blog.hubspot.com/hs-fs/hubfs/Google%20Drive%20Integration/How%20to%20Find%20the%20Perfect%20GIF%2010%20Must-Try%20Websites-1.gif?width=390&height=294&name=How%20to%20Find%20the%20Perfect%20GIF%2010%20Must-Try%20Websites-1.gif" alt="">
                  </div>

                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
              </div>
            </div>
          </div>
          <!-- Fin del Modal de ejemplo -->
          <!-- Modal FNAC -->
          <div class="modal" id="modal-fnac" tabindex="-1">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title">Looking for the fiesta FNAC</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
<div class="si-existe-fnac" style="display:none">
  <p>Ya tenemos una oferta para este artículo en FNAC, por lo que resulta imposible volver a darlo de alta</p>
</div>
<div class="no-existe-fnac" style="display:none">
  <h4>¡Bien! No tenemos oferta para este artículo en FNAC.</h4>
  <p>Hay algo que tenemos que tener claro. No podemos hacer una consulta sobre un artículo en FNAC sin tener oferta. Eso significa que tenemos que probar a crear oferta y según la respuesta veremos</p>
  <p>Este artículo no lo tenemos dado de alta, así que podemos proceder. Para agilizar se va a dar de alta con un margen del 20% y clase logística media, podrás ajsutarlo más adelante.</p>
  <button type="button" class="btn btn-primary" id="add-web-fnac">Añadir artículo</button>
</div>
<div class="buscando-batch" style="display:none">
  <p>Esperando a ver que ha pasado</p>
</div>
<div class="error-alta-fnac" style="display:none">
  <h4>ooooooooh, no ha podido ser</h4>
  <p>Algo ha fallado, mas concretamente:</p>
  <div class="txt-resultado-alta blockquote"></div>
</div>
<div class="exito-alta-fnac" style="display:none">
  <p>Exito total</p>
</div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                  </div>
                </div>
              </div>
            </div>
            <!-- Fin del Modal FNAC -->

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
  <script src="js/fnac.js"></script>
  <!-- Page level plugins -->
  <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
  <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/jeditable.js/2.0.17/jquery.jeditable.min.js"></script>

  <!-- Page level custom scripts -->
  <script src="js/datatables/pedidos-odoo.js"></script>


</body>
</html>

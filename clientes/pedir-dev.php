<?php

namespace unicorn\clientes;

error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . '/../config.php.inc';
require_once(RAIZ . '/vendor/autoload.php');

use Ripoo\OdooClient;

require_once RAIZ . '/clases/funciones/odoo/Clientes.php';

use unicorn\clases\funciones\odoo\Clientes as cliente;

require_once RAIZ . '/clases/funciones/odoo/Articulos.php';

use unicorn\clases\funciones\odoo\Articulos as articulos;

require_once RAIZ . '/clases/funciones/odoo/Presupuestos_venta.php';

use unicorn\clases\funciones\odoo\Presupuestos_venta as presupuesto_venta;

?>
<?php
//Definimos el título de la página
$titulo_pagina = '';
include 'src/cabecera.php';                 //Incluimos la cabecera
?>

<body id="page-top">

  <style>
    body {
      font-family: 'Pixel-Art', sans-serif !important;

    }
  </style>
  <?php
  include 'src/loader.php';                 //Incluimos la cabecera
  ?>
  <!-- Page Wrapper -->
  <div id="wrapper">
    <?php include 'src/sidebar.php';                 //Incluimos el panel lateral 
    ?>
    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">
      <?php //Cargamos el cliente, en este caso Espiral
      //  echo $id_odoo;
      $campo = 'id';
      $valor = $id_odoo;
      $busqueda_clientes = cliente::busqueda($campo, $valor, 'res.partner');
      $cliente = $busqueda_clientes[0];
      //Definimos las variables que nos interesan
      $id_cliente_odoo = $valor;
      $partner_id = $valor;
      $dir_envio = $valor;


      $nombre_cliente = $cliente['name'];
      $id_tarifa = $cliente['property_product_pricelist'][0]; //Id de su tarifa
      //$busqueda_tarifa = cliente::busqueda('id',$id_tarifa,'product.pricelist');

      ?>
      <!-- Main Content -->
      <div id="content">
        <?php include 'src/topbar.php';                 //Incluimos el panel superior 
        ?>

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <script>
            idTarifa = <?= $id_tarifa ?>;
            cliente = <?= json_encode($cliente) ?>;

            //        direcciones = llamadaJson('../ajax/odoo/busqueda.php',datos);             //Buscamos en odoo
            var carrito = [];


            $(document).ready(function() {
              /*********************************************** DEFINIMOS VARIABLES *******************************************************************/
              //       var miTarifa = {};
              let terminoBusqueda = document.getElementById('button-buscar');
              let listaResultados = document.getElementById('resultados-busqueda');
              let selectSubC = document.getElementById('selectDirecciones');
              let envioMiDireccion = document.getElementById('envio-mi-direccion');
              let envioOtraDireccion = document.getElementById('envio-otra-direccion');
              let btnConfirmarPedido = document.getElementById('btn-confirmar-pedido');
              let btnSendFeedback = document.getElementById('send-feedback');

              /*********************************************** GUIÓN DE LA APLICACIÓN *******************************************************************/
              tarifaCliente = colocaCliente(cliente)
                .then(() => miTarifa = dameTarifa(cliente))
                .then(tCliente => {
                  //Pedimos los pedidos de este cliente
                  let pedidosCliente = dameInfoPedidos(cliente.sale_order_ids);
                  return tCliente;
                })




              /*********************************************** EVENTOS *******************************************************************/
              //Capturamos el click en el botón buscar
              terminoBusqueda.addEventListener("click", function() {
                clickBuscarDev(miTarifa);
              }); //Llamamos a la funcion clickBuscar

              //Capturamos la pulsación de la tecla intro
              document.getElementById('input-buscar').addEventListener("keyup", function(e) {
                if (e.key === 'Enter') {
                  clickBuscarDev(miTarifa);
                }
              }); //Llamamos a la funcion clickBuscar

              //Cuando marcamos la opción enviar a otra dirección
              envioOtraDireccion.addEventListener('change', function(event) {
                selectSubC.disabled = false;
              });
              //Cuando marcamos la opción enviar a mi dirección
              envioMiDireccion.addEventListener('change', function(event) {
                selectSubC.disabled = true;
              });



              btnSendFeedback.addEventListener('click', function() { //Capturamos el click en el botón de enviar feedback
                let txtArea = document.getElementById('textAreaSugerencias');
                if (txtArea.value.length <= 3) { //Si hemos escrito 3 o menos caracteres mostramos un error
                  alert('Escribe un poco más, por favor');
                } else {
                  let texto = {
                    txt: txtArea.value,
                    carrito: carrito
                  }; //Capturamos el texto
                  let datos = {
                    usuario: 'gabriel',
                    mensaje: JSON.stringify(texto),
                    cliente: cliente,
                    confirmacion: 'si'
                  }
                  enviarMsjRocket(datos);

                }

              });

              modalReportarArticulo.addEventListener('shown.bs.modal', (e) => {
                let mpn = e.relatedTarget.parentNode.dataset.mpn; //Capturamos el mpn
                selectReportarArticulo.setAttribute('data-mpn', mpn); //Lo colocamos en el select
              })
              btnEnviaReporteArticulo.addEventListener('click', e => {
                let motivo = selectReportarArticulo.value;
                let mpn = selectReportarArticulo.dataset.mpn;
                let infoAdicional = infoAdicionalReporte.value;
                if (motivo.length > 0) { //Nos aseguramos de haber seleccionado una de las opciones
                  //Preparamos el mensaje
                  let msjInterno = 'mpn: ' + mpn + '; motivo: ' + motivo + '; info: ' + infoAdicional;
                  let datosRocket = {
                    usuario: 'gabriel',
                    mensaje: msjInterno,
                    cliente: cliente,
                    confirmacion: 'si'
                  }
                  enviarMsjRocket(datosRocket);
                  //   console.log(mpn);
                }


              });

            });

            function dameDireccionEnvio(cliente) {
              let otraDireccion = document.getElementById('envio-otra-direccion'); //CApturamos el check de otra direccion
              let direccion;
              if (otraDireccion.checked === true) { //Comprobamos si está marcado el check de otra direccion
                let selectSubC = document.getElementById('selectDirecciones');
                if (selectSubC.value > 0) {
                  direccion = selectSubC.value;
                } else {
                  alert('elige una dirección');
                }

              } else {
                direccion = cliente.id; //De momento marcamos la id de cliente como direccion si no tenemos seleccionado otra cosa
              }
              return direccion;
            }
          </script>
          <div class="row justify-content-center">
            <div class="col-xl-11 col-lg-12 col-md-9">
              <div class="card shadow mb-1">
                <div class="row g-0">
                  <div class="card-body">
                    <h5 class="card-title">Hola Cliente</h5>
                    <p class="card-text">
                    <div class="alert alert-info" role="alert">
                      Ahora puedes reportar cualquier error con un artículo directamente, <a href="https://drive.google.com/file/d/1nRB47dAgRqx39VYBEL2FKb18jzgv8Sri/view?usp=drivesdk" target="_blank">mira como </a>
                    </div>
                    </p>

                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="row justify-content-center">
            <div class="col-xl-11 col-lg-12 col-md-9">
              <div class="card shadow mb-4">
                <div class="row g-0">
                  <div class="col-md-2" id="row-busqueda-img">
                    <img src="img/potion.png" class="img-fluid rounded-start" alt="llama">
                    <ul class="list-group list-group-flush visually-hidden" id="leyenda-stock">
                      <li class="list-group-item d-flex justify-content-between align-items-center" style="font-size: 90%;"><img src="img/full-stock.png" width="16" />A tope</li>
                      <li class="list-group-item d-flex justify-content-between align-items-center" style="font-size: 90%;"><img src="img/med-stock.png" width="16" />Últimas unidades</li>
                      <li class="list-group-item d-flex justify-content-between align-items-center" style="font-size: 90%;"><img src="img/no-stock.png" width="16" />No disponible</li>
                      <li class="list-group-item d-flex justify-content-between align-items-center" style="font-size: 90%;"><img src="img/mystery-stock.png" width="16" />Consultar</li>
                    </ul>
                  </div>
                  <div class="col-md-10" id="row-busqueda-data">
                    <div class="card-body">
                      <h5 class="card-title">Busca tus artículos</h5>
                      <p class="card-text">
                      <div class="input-group mb-3">
                        <input type="text" class="form-control" placeholder="Busquemos cosas" aria-label="Busquemos cosas" aria-describedby="button-buscar" id="input-buscar">
                        <button class="btn btn-outline-secondary" type="button" id="button-buscar">Buscar</button>
                      </div>
                      </p>
                      <table class="table table-sm visually-hidden" id="busqueda-resultados">
                        <thead>
                          <tr>
                            <th scope="col"></th>
                            <th scope="col">mpn</th>
                            <th scope="col">Nombre</th>
                            <th scope="col">Precio</th>
                            <th scope="col">Dto</th>
                            <th scope="col">Stock</th>
                            <th scope="col" class="col-2">Comprar</th>
                          </tr>
                        </thead>
                        <tbody id="cuerpo-tabla-resultados">
                          <!-- Aquí van los resultados de la búsqueda -->
                        </tbody>
                      </table>
                      <div class="card" id="busqueda-vacio">
                        <div class="card-body">
                          <h4>Sabías que ...</h4>
                          <p>Puedes buscar por nombre, referencia, serie y un montón de cosas más.</p>
                        </div>
                      </div>
                      <div class="row visually-hidden text-center" id="busqueda-cargando">
                        <h4>Buscando</h4>
                        <div style="width: 15%; margin: 0 auto">
                          <div class="tenor-gif-embed" data-postid="1678580805838246012" data-share-method="host" data-aspect-ratio="1" data-width="100%"><a href="https://tenor.com/view/eyes-noto-color-emoji-animated-looking-glance-gif-1678580805838246012">Eyes Noto Color Emoji Sticker</a>from <a href="https://tenor.com/search/eyes-stickers">Eyes Stickers</a></div>
                          <script type="text/javascript" async src="https://tenor.com/embed.js"></script>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="row justify-content-center visually-hidden">
            <div class="col-xl-11 col-lg-12 col-md-9">
              <div class="card shadow mb-4">
                <div class="row g-0">
                  <div class="col-md-9">
                    <div class="card-body">
                      <h5 class="card-title float-start">Esta es tu tarifa</h5>

                      <table class="table">
                        <thead>
                          <tr>
                            <th scope="col">#</th>
                            <th scope="col">First</th>
                            <th scope="col">Last</th>
                            <th scope="col">Handle</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <th scope="row">1</th>
                            <td>Mark</td>
                            <td>Otto</td>
                            <td>@mdo</td>
                          </tr>
                          <tr>
                            <th scope="row">2</th>
                            <td>Jacob</td>
                            <td>Thornton</td>
                            <td>@fat</td>
                          </tr>
                          <tr>
                            <th scope="row">3</th>
                            <td colspan="2">Larry the Bird</td>
                            <td>@twitter</td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <img src="https://cdn.dribbble.com/users/1787323/screenshots/5671605/attachments/1225171/dribbble_chug_bottle_2-05.png" class="img-fluid rounded-start" alt="llama">
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="row justify-content-center">
            <div class="col-xl-11 col-lg-12 col-md-9">
              <div class="card shadow mb-4">
                <div class="row g-0">
                  <div class="col-md-10">
                    <div class="card-body">
                      <h5 class="card-title">Este es tu carrito</h5>
                      <table class="table table-sm visually-hidden" id="mi-carrito">
                        <thead>
                          <tr>
                            <th scope="col">mpn</th>
                            <th scope="col">Descripción</th>
                            <th scope="col">Precio</th>
                            <th scope="col">Dto</th>
                            <th scope="col">€/ud</th>
                            <th scope="col" class="col-1">Cant.</th>
                            <th scope="col">Subtotal</th>
                            <th scope="col">Acc.</th>
                          </tr>
                        </thead>
                        <tbody>
                          <!-- Contenido del carrito -->
                        </tbody>
                        <tfoot>
                          <tr>
                            <td colspan="2">Tienes <b id="num-items-carrito"></b> artículos en tu carrito</td>
                            <td colspan="5" class="text-end">Total de tu compra: <b id="importe-total-carro"></b></td>
                            <td></td>
                          </tr>
                        </tfoot>
                      </table>
                      <div class="card" id="mi-carrito-vacio">
                        <div class="card-body">
                          <h4>No hay nada aquí</h4>
                          <p>Aquí verás lo que vayas añadiendo para comprar.</p>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-2 text-center">
                    <img src="img/cofre-cerrado.png" class="img-fluid rounded-start" alt="carrito vacío" width="250" id="img-cofre">
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="row justify-content-center">
            <div class="col-xl-6 col-lg-12 col-md-9">
              <div class="card shadow mb-4" style="min-height: 282px;">
                <div class="row g-0">
                  <div class="col-md-3">
                    <img src="img/mapa-tesoro.png" class="img-fluid rounded-start" width="250" alt="llama">
                  </div>
                  <div class="col-md-9">
                    <div class="card-body">
                      <h5 class="card-title">Envío</h5>
                    </div>
                    <div class="row no-gutters align-items-center">
                      <div class="form-check">
                        <input class="form-check-input" type="radio" name="flexRadioDefault" id="envio-mi-direccion" checked>
                        <label class="form-check-label" for="flexRadioDefault1"><svg fill="none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24">
                            <path d="M14 2h-4v2H8v2H6v2H4v2H2v2h2v10h7v-6h2v6h7V12h2v-2h-2V8h-2V6h-2V4h-2V2zm0 2v2h2v2h2v2h2v2h-2v8h-3v-6H9v6H6v-8H4v-2h2V8h2V6h2V4h4z" fill="currentColor" />
                          </svg>
                          Mi dirección
                        </label>
                      </div>
                      <div class="form-check">
                        <input class="form-check-input" type="radio" name="flexRadioDefault" id="envio-otra-direccion">
                        <label class="form-check-label" for="flexRadioDefault2"><svg fill="none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24">
                            <path d="M8 2h2v2h2v2h-2v10H8V6H6V4h2V2zM4 8V6h2v2H4zm2 10v2H4v2H2V8h2v10h2zm0 0h2v-2H6v2zm6 0h-2v-2h2v2zm2-10V6h-2v2h2zm2 0h-2v10h-2v2h2v2h2v-2h2v-2h2v-2h2V2h-2v2h-2v2h-2v2zm0 0h2V6h2v10h-2v2h-2V8z" fill="currentColor" />
                          </svg>
                          Otra dirección
                        </label>
                      </div>
                      <div class="row no-gutters align-items-center">
                        <div class="mb-3">
                          <select class="form-select" aria-label="Default select example" id="selectDirecciones" disabled>
                            <option selected>Selecciona la dirección de envío</option>
                          </select>
                        </div>
                      </div>
                      <div class="alert alert-light" role="alert">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="32">
                          <path d="M5 3h14v2H5V3zm0 16H3V5h2v14zm14 0v2H5v-2h14zm0 0h2V5h-2v14zM10 8H8v2h2V8zm4 0h2v2h-2V8zm-5 6v-2H7v2h2zm6 0v2H9v-2h6zm0 0h2v-2h-2v2z" fill="currentColor" />
                        </svg> ¿Necesitas enviar a otra dirección? Simplemente indícalo en notas
                      </div>
                    </div>
                  </div>

                </div>
              </div>
            </div>
            <div class="col-xl-5 col-lg-12 col-md-9">
              <div class="card shadow mb-4">
                <div class="row g-0">

                  <div class="col-md-9">
                    <div class="card-body">
                      <h5 class="card-title">Otros datos</h5>
                      <div class="row no-gutters align-items-center">
                        <div class="mb-3 row">
                          <div class="input-group mb-3">
                            <span class="input-group-text"><svg fill="none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24">
                                <path d="M8 5h2v2H8V5zM6 7h2v2H6V7zM4 9h2v2H4V9zm-2 2h2v2H2v-2zm2 2h2v2H4v-2zm2 2h2v2H6v-2zm2 2h2v2H8v-2zm8-12h-2v2h2V5zm2 2h-2v2h2V7zm2 2h-2v2h2V9zm2 2h-2v2h2v-2zm-2 2h-2v2h2v-2zm-2 2h-2v2h2v-2zm-2 2h-2v2h2v-2z" fill="currentColor" />
                              </svg></span>
                            <div class="form-floating">
                              <input type="text" class="form-control" id="ref-cliente" placeholder="Referencia">
                              <label for="ref-cliente">Referencia</label>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="row no-gutters align-items-center">
                        <div class="mb-3">
                          <div class="form-floating">
                            <textarea class="form-control" placeholder="Leave a comment here" id="notasPedido" style="height: 100px"></textarea>
                            <label for="floatingTextarea2"><svg fill="none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24">
                                <path d="M22 2H2v14h2V4h16v12h-8v2h-2v2H8v-4H2v2h4v4h4v-2h2v-2h10V2z" fill="currentColor" />
                              </svg> Notas</label>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-3 text-center">
                    <img src="img/note.png" class="img-fluid rounded-start mt-2" alt="llama" width="100">
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="row justify-content-center">
            <div class="col-xl-11 col-lg-12 col-md-9">
              <div class="row no-gutters align-items-center mb-4">
                <a href="#" class="btn btn-primary btn-icon-split btn-lg fs-2 disabled" data-bs-toggle="modal" data-bs-target="#confirmarPedidoModal" id="btn-abre-modal-confirmacion">
                  <span class="icon text-white-100">
                    <svg fill="none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="48">
                      <path d="M4 2h14v2H4v16h2v-6h12v6h2V6h2v16H2V2h2zm4 18h8v-4H8v4zM20 6h-2V4h2v2zM6 6h9v4H6V6z" fill="currentColor" />
                    </svg>
                  </span>
                  <span class="text">Confirmar pedido</span>
                </a>
              </div>
            </div>
          </div>
          <div class="row justify-content-center">
            <div class="col-xl-11 col-lg-12 col-md-9">
              <div class="card shadow mb-4">
                <div class="row g-0">
                  <div class="col-md-3 text-center">
                    <img src="img/megafono.png" class="img-fluid rounded-start" alt="llama" width="250">
                  </div>
                  <div class="col-md-9">
                    <div class="card-body">
                      <h5 class="card-title">Feedback</h5>
                      <p>¿Tienes problemas con tu pedido? ¿Hay algo que mejorarías o piensas que sería mejor hacerlo de otra manera? ¿Estás leyendo esto con la voz de la teletienda? Ese es tu apartado. Sólo rellena el siguiente campo y dale a enviar. <br />
                        Este campo resulta la manera de sencilla y completa de comunicar con el departamento encargado del desarrollo de la aplicación.
                        <br />Con tu ayuda todo funciona mejor, <b>¡muchas gracias!</b>
                      </p>
                      <div class="row no-gutters align-items-center">
                        <div class="mb-3">
                          <div class="form-floating">
                            <textarea class="form-control" placeholder="Leave a comment here" id="textAreaSugerencias" style="height: 100px"></textarea>
                            <label for="textAreaSugerencias"><svg fill="none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24">
                                <path d="M22 2H2v14h2V4h16v12h-8v2h-2v2H8v-4H2v2h4v4h4v-2h2v-2h10V2z" fill="currentColor" />
                              </svg> Escribe Aquí tus comentarios</label>
                          </div>
                        </div>
                        <div class="mb-3">
                          <button type="button" class="btn btn-primary float-end" id="send-feedback">Enviar</button>
                        </div>

                      </div>
                    </div>
                  </div>

                </div>
              </div>
            </div>
          </div>

          <div class="row">
            <!-- Modal confirmar pedido-->
            <div class="modal fade" id="confirmarPedidoModal" tabindex="-1" aria-labelledby="confirmarPedidoModalLabel" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="confirmarPedidoModalLabel">Confirmamos</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                    Vamos a confirmar el pedido, cualquier modificación posterior tendrá que ser realizada directamente en Odoo
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <!--<button type="button" class="btn btn-primary" id="btn-confirmar-pedido" data-bs-dismiss="modal">Confirmar</button>-->
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#resultadoPedidoModal">Confirmar</button>
                  </div>
                </div>
              </div>
            </div>
            <!-- FIN Modal confirmar pedido-->
            <!-- Modal resultado pedido-->
            <div class="modal fade" id="resultadoPedidoModal" tabindex="-1" aria-labelledby="resultadoPedidoModal" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="resultadoPedidoModalLabel">Enviando pedido</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body" id="creandoPedido">
                  <img src="img/4DPg.gif" alt="" style="max-width: 460px">
                  <p class="text-center mt-2">Estamos guardando tu pedido, espera unos momentos, por favor</p>
                  </div>
                  <div class="modal-body visually-hidden" id="pedidoExito">
                  <img src="img/pc.gif" alt="" style="max-width: 460px">
                  <p class="text-center mt-2">Hemos recibido tu pedido correctamente. Nos ponemos a trabajar inmediatamente.</p>
                  </div>
                  <div class="modal-body visually-hidden" id="pedidoError">
                  <img src="img/error.gif" alt="" style="max-width: 460px">
                  <p class="text-center mt-2">Algo ha salido mal, vuelve a intentarlo o contacta con nosotros.</p>
                  </div>
                  <div class="modal-footer visually-hidden" id="footerModalResultado">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                  </div>
                </div>
              </div>
            </div>
            <!-- FIN Modal resultado pedido -->
            <!-- Modal confirmar pedido-->
            <div class="modal fade" id="PedidoExito" tabindex="-1" aria-labelledby="PedidoExitoModalLabel" aria-hidden="false">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="PedidoExitoModalLabel">Exitazo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body text-center">
                    <img src="https://www.reactiongifs.us/wp-content/uploads/2017/10/success.gif" alt="">
                    <p >Hemos creado el presupuesto con éxito</p>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                  </div>
                </div>
              </div>
            </div>
            <!-- FIN Modal confirmar pedido-->
            <!-- Modal reportar artículo-->
            <div class="modal fade" id="modalReportarArticulo" tabindex="-1" aria-labelledby="modalReportarArticuloLabel" aria-hidden="false">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title">Reportar artículo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body text-center">
                    <select class="form-select mb-3" aria-label=".form-select" id="selectReportarArticulo">
                      <option selected>¿Que ocurre con este artículo?</option>
                      <option value="descuento">No aparece mi descuento</option>
                      <option value="precio">El precio no es correcto</option>
                      <option value="descripcion">El nombre o la descripción son erróneos</option>
                      <option value="carrito">No se añade al carrito</option>
                      <option value="otro">Otro problema (especificar)</option>
                    </select>
                    <div class="form-floating">
                      <textarea class="form-control mb-3" placeholder="Leave a comment here" id="infoAdicionalReporte" style="height: 100px"></textarea>
                      <label for="floatingTextarea2"><svg fill="none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24">
                          <path d="M22 2H2v14h2V4h16v12h-8v2h-2v2H8v-4H2v2h4v4h4v-2h2v-2h10V2z" fill="currentColor"></path>
                        </svg> Información adicional</label>
                    </div>
                    <div class="row justify-content-center">
                      <div class="col-xl-11 col-lg-12 col-md-9">
                        <div class="row no-gutters align-items-center mb-4">
                          <a href="#" class="btn btn-primary btn-icon-split fs-5" id="btnEnviaReporteArticulo">
                            <span class="icon text-white-100">
                              <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" width="24">
                                <path d="M8 2h2v4h4V2h2v4h2v3h2v2h-2v2h4v2h-4v2h2v2h-2v3H6v-3H4v-2h2v-2H2v-2h4v-2H4V9h2V6h2V2Zm8 6H8v3h8V8Zm-5 5H8v7h3v-7Zm2 7h3v-7h-3v7ZM4 9H2V7h2v2Zm0 10v2H2v-2h2Zm16 0h2v2h-2v-2Zm0-10V7h2v2h-2Z" />
                              </svg>
                            </span>
                            <span class="text">Enviar reporte</span>
                          </a>
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
            <!-- FIN Modal reportar artículo-->
            <!-- Modal Has enviado feedback-->
            <div class="modal fade" id="feedback-enviado-modal" tabindex="-1" aria-labelledby="feedback-enviado-modal" aria-hidden="false">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title">Muchas gracias</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body text-center">
                    <div class="tenor-gif-embed" data-postid="14885177" data-share-method="host" data-aspect-ratio="1.77778" data-width="100%"><a href="https://tenor.com/view/thanks-gif-14885177">Thanks GIF</a>from <a href="https://tenor.com/search/thanks-gifs">Thanks GIFs</a></div>
                    <script type="text/javascript" async src="https://tenor.com/embed.js"></script>
                    <p>Tu mensaje se ha enviado con éxito, nos pondremos en contacto contigo si es menester. ¡Muchas gracias!</p>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                  </div>
                </div>
              </div>
            </div>
            <!-- FIN Modal confirmar pedido-->
          </div>
          <!-- /.container-fluid -->
          <!-- Toasts -->
          <div class="toast-container position-fixed top-0 p-3 end-0" style="z-index: 11">
            <div id="liveToast" class="toast hide" role="alert" aria-live="assertive" aria-atomic="true">
              <div class="toast-header bg-gradient-barra">
                <i class="fas fa-horse-head" style="margin-right: 1em"></i>
                <strong class="me-auto"> FUTURA</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
              </div>
              <div class="toast-body" id="cuerpoToast">

              </div>
            </div>
          </div>
          <!-- End of Toasts -->
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
            <h5 class="modal-title" id="exampleModalLabel">¿Cerramos la sesión?</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">Si haces click en "Cerrar" saldrás de tu cuenta.</div>
          <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
            <a class="btn btn-primary" href="logout.php">Cerrar</a>
          </div>
        </div>
      </div>
    </div>
    <script>

    </script>
    <!-- Bootstrap core JavaScript-->
    <!--<script src="vendor/jquery/jquery.min.js"></script>-->
    <script src="../../unicorn/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <!-- Core plugin JavaScript-->
    <script src="../../unicorn/vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="../../unicorn/js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->

    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
    <script src="../../unicorn/vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="js/pedir.js"></script>

</body>

</html>
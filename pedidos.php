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
  <script src="js/pedidos.js"></script>
  <script src="js/odoo.js"></script>
  <script src="js/inforpor.js"></script>
  <?php
  include 'src/loader.php';                 //Incluimos el loader principal
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

            ?>
            <script>
              //         $('.loader').show();
              loaderPagina.classList.remove('visually-hidden'); //Mostramos el loader

              /******************************************************************************/
              /*Eventos
              /******************************************************************************/
              $(document).ready(function() {
                plataforma = '<?= $plataforma ?>'; //Creamos la variable plataforma
                document.getElementById('nav-' + plataforma).classList.add('bg-light'); //Ponemos fondo al botón de la plataforma que estamos mostrando

                /** 
                 * definimos variables
                 *  */
                var btnCrearArticulo = document.getElementById('btn-crear-articulo'); //Boton para crear artículo
                const modalPedido = document.getElementById('modal-pedido');
                const varModalExito = new bootstrap.Modal(modalExito, '');
                const varModalInforpor = new bootstrap.Modal(modalComprarInforpor, '');
                const varModalInfo = new bootstrap.Modal(modalComprarInforpor, '');


                /**
                 * guión de la aplicación
                 *  */

                ultimosPedidos = dameUltimosPedidos(plataforma) //cargamos los pedidos
                  .then(pedidos => {
                    creatabla(pedidos); //Creamos la tabla con los datos (datatables/pedidos.js)
                    modalPedido.addEventListener('show.bs.modal', event => { //CApturamos la acción de abrir el modal
                      let pedido = colocaDatosModal(pedidos, event)
                        .then(ped => {
                          if (plataforma != 'odoo' && plataforma != 'inforpor') { //Para odoo o inforpor no hacemos las comprobaciones
                            comprobaciones(ped); //Comprobamos la integrida de los datos

                          }
                          return ped;
                        })
                        .then(pd => {
                          buscaPedidosOdooWorker(pd);
                          return pd;
                        })
                        .then(pd => {

                          //  let compraOdoo = buscaCompraOdoo(pd) //Pedimos la compra
                          // console.log('compra');
                          // console.log(compraOdoo);
                          return pd;
                        })
                        .then(pd => {
                          //En la primera ejecución (la primera vez que abrimos un modal) le damos valor vacío a la variable
                          if (typeof(tarifas) === "undefined") {
                            tarifas = '';
                          }
                          getTarifas(tarifas)
                            .then(tarifas => {
                              // console.log('pdd');
                              //console.log(pd);
                              let obj = {
                                articulos: pd.lineas_pedido,
                                tarifas: tarifas
                              }
                              return obj;
                            })
                            .then(obj => {
                              if (plataforma != 'inforpor') { //Si es inforpor no buscamos la compra de los articulos
                                compraArticulosWorker(obj)
                              }

                            });
                        });

                    })
                    modalPedido.addEventListener('hide.bs.modal', event => { //CApturamos la acción de esconder el modal
                      limpiaDatosModal();
                    })
                  })


                //Modal de comprobar cliente
                var comprobarModal = document.getElementById('comprueba-cliente-modal')
                comprobarModal.addEventListener('show.bs.modal', function(event) { //Evento
                  var objPedido = modalCrearCliente(event);
                })
                //Al cerrar la ventana modal la reseteamos la estado original
                comprobarModal.addEventListener('hide.bs.modal', function(event) {
                  $('.comprueba-cliente-cuerpo').html('<ul class="list-group"><li class="list-group-item list-group-item-info" id="check-nif">NIF / CIF </li><li class="list-group-item " id="check-phone">Teléfono</li><li class="list-group-item " id="check-name">Nombre</li></ul><div id="resultado-check"></div>').hide();
                })


                //Modal de comprobar artículo
                var crearArticuloModal = document.getElementById('crear-articulo-modal')
                crearArticuloModal.addEventListener('show.bs.modal', function(event) {
                  // Button that triggered the modal
                  var button = event.relatedTarget
                  // Extract info from data-bs-* attributes
                  var esteArticulo = button.getAttribute('data-bs-whatever')
                  var objArticulo = JSON.parse(esteArticulo); //Convertimos la cadena en un objeto
                  //Categorias
                  let misCats = filtrarCategorias(); //Recuperamos el listado de categorias
                  let idCatBuscada = adivinaCategoria(objArticulo.cod_categoria); //Intentamos adivinar la categoría del artículo
                  misCats.forEach((cat, i) => { //Recorremos el listado de categorias
                    let option = document.createElement("option"); //Creamos un elemento option
                    option.text = cat.complete_name; //Asignamos como texto el nombre completo de la categoria
                    option.value = cat.id; //Asignamos como valor la id
                    if (cat.id === idCatBuscada) { //Si la categoria buscada coincide la seleccionamos
                      option.selected = true;
                    }
                    listaCatsSelect.add(option); //Añadimos la opcion creada a la lista
                  });

                  let listaFabricantes = listarMarcas();
                  let splitNombre = objArticulo.nombre.split(" ");
                  let nombreFab = splitNombre[0];

                  listaFabricantes.forEach((fabricante, i) => { //Recorremos el listado de categorias
                    let option = document.createElement("option"); //Creamos un elemento option
                    option.text = fabricante; //Asignamos como texto el nombre completo de la categoria
                    option.value = fabricante; //Asignamos como valor la id
                    if (nombreFab == fabricante) {
                      option.selected = true;
                    }
                    listaFabsSelect.add(option);
                  });
                  let selects = Object.values(document.getElementsByClassName('select-articulo')); //Los desplegables
                  activaBoton(selects); //Al abrir el modal ejecutamos la funcion una vez para habilitar o no el botón
                  selects.forEach((item, i) => { //El evento que escucha cualquier cambio
                    item.addEventListener('change', function(event) {
                      activaBoton(selects)
                    });
                  });
                  //Buscamos el artículo en inforpor
                  let codInfo = objArticulo.atributos_bd[5]; //Cogemos el codigo de inforpor
                  datos = {
                    codinfo: codInfo
                  }
                  let buscaArtIfp = llamadaJson('ajax/inforpor/buscar-articulo.php', datos); //Buscamos el artículo en odoo
                  let precioIfp = buscaArtIfp.CodErr == '0' ? buscaArtIfp.Precio : 0;

                  $('.crear-articulo-loader').hide();

                  btnCrearArticulo.addEventListener('click', function(event) { //CApturamos el click en crear artículo
                    event.preventDefault;
                    let datos = {
                      modelo: 'product.product',
                      arr: {
                        categ_id: listaCatsSelect.value, //Categoria, la cogemos del select
                        name: objArticulo.nombre, //Nombre del artículo
                        default_code: objArticulo.mpn, //referencia
                        type: 'product', //Tipo de producto almacenable
                        x_studio_fabricante: listaFabsSelect.value
                      }
                    };
                    if (objArticulo.atributos_bd[3] !== 'undefined') {
                      datos.arr.barcode = objArticulo.atributos_bd[3];
                    }
                    //console.log('datos para crear');
                    //console.log(datos);
                    let crearArticulo = EntradaOdoo(datos, 'crear'); //Creamos el artículo

                    if (crearArticulo.hasOwnProperty('id')) {
                      datos = {
                        campo_busqueda: 'default_code',
                        valor_antiguo: objArticulo.mpn,
                        campo_actualizar: 'list_price',
                        valor_nuevo: (objArticulo.importe - objArticulo.impuestos) / objArticulo.cantidad,
                        modelo: 'product.template'
                      }
                      let actualizarArticulo = EntradaOdoo(datos, 'actualizar'); //Creamos el artículo

                      if (actualizarArticulo === 'ok') {
                        datos.campo_actualizar = 'standard_price';
                        datos.valor_nuevo = precioIfp;
                        actualizarArticulo = EntradaOdoo(datos, 'actualizar'); //Creamos el artículo
                        if (actualizarArticulo === 'ok') {
                          crearArticuloModal.hide();
                        }
                      }
                    }
                  });

                });

                //Acciones al abrir el modal para crear PO en odoo
                /* modalCrearPoOdoo.addEventListener('show.bs.modal', function(event) {               
                   console.log('ventanuco abierto');
                 });*/
                modalCrearPoOdoo.addEventListener('hide.bs.modal', function(event) {
                  lineasPoOdoo.innerHTML = '';
                });
                //Modal de crear pedidos en odoo
                //  var crearSolicitudCompraModal = document.getElementById('crear-solicitud-compra-modal')
                modalCrearSoOdoo.addEventListener('show.bs.modal', function(event) {
                  // Button that triggered the modal
                  var button = event.relatedTarget
                  // Extract info from data-bs-* attributes
                  var estepedido = button.getAttribute('data-bs-whatever')
                  var objEstePedido = JSON.parse(estepedido); //Convertimos la cadena en un objeto
                  var objpedidoCompra = objEstePedido['pedido_compra'];
                  var objcliente = objEstePedido['cliente'];
                  var objpedidoVenta = objEstePedido['pedido_venta'];
                  var direccionesPedido = objEstePedido['direccionesPedido'];

                  document.getElementById('cliente-factura').innerHTML += direccionesPedido.factura.name; //Ponemos la dirección de factura
                  document.getElementById('cliente-envio').innerHTML += direccionesPedido.hasOwnProperty('envio') ? direccionesPedido.envio.name : direccionesPedido.factura.name; //Ponemos la dirección de factura


                  //Pedimos los distintos tipos de pedido 
                  let datosTipoPedido = {
                    modelo: 'sale.order.type'
                  }; //Preparamos los datos que vamos a Buscar
                  let TipoPedido = llamadaJson('ajax/odoo/listar.php', datosTipoPedido); //Obtenemos el listado de tipos de pedido


                  TipoPedido.forEach(function(tipo, i) {
                    let opcion = document.createElement('option');
                    opcion.innerHTML = tipo.name; //Colocamos el nombre
                    opcion.value = tipo.id; //Colocamos la id como valor
                    document.getElementById('elige-tipo-pedido').appendChild(opcion);
                  });
                  adivinaTipoPedido(objpedidoVenta, plataforma);

                  var lineasPed;
                  if (objpedidoCompra !== undefined && objpedidoCompra.hasOwnProperty('CodErr') && objpedidoCompra.CodErr == '0') { //Si recibimos pedido de inforpor
                    $('#referencia-inforpor-modal').html('Pedido número: ' + objpedidoCompra.numero); //Ponemos el número de pedido de inforpor
                    $('#referencia-cliente-modal').html('Referencia interna: ' + objpedidoCompra.numpedCli); //Ponemos la referencia interna
                    //Vamos a poner los artículos
                    lineasPed = objpedidoCompra.lineasPedR;
                    for (p in lineasPed) {
                      $('#articulos-crear-compra-modal').append('<div class="row"><div class="col-11">' + lineasPed[p].cant + ' x ' + lineasPed[p].atributos_bd[4] + '(' + lineasPed[p].atributos_bd[2] + ')</div><div class="col-1">' + lineasPed[p].precio + '€</div></div>');
                    }
                  } else { //Si no recibimos el pedido de compra obtenemos las lineas de la venta directamente
                    lineasVenta = objpedidoVenta.lineas_pedido;
                    for (p in lineasVenta) {
                      $('#articulos-crear-compra-modal').append('<div class="row"><div class="col-11">' + lineasVenta[p].cantidad + ' x ' + lineasVenta[p].mpn + '(' + lineasVenta[p].nombre + ')</div><div class="col-1"></div></div>');
                    }
                  }
                  objEstePedido['articulosVenta'] = generaArticulosVenta(objpedidoVenta, );

                  document.getElementById('btn-armageddon').addEventListener('click', function(e) {
                    armageddon(objEstePedido);
                  });
                });





                ///////////////////////////////////////////




                //Capturamos el click en buscar pedido (aún sin uso)
                $('#buscar-pedido').on('click', () => {
                  let terminoBusqueda = $('#termino-busqueda').val();
                  if (terminoBusqueda.length >= 3) {} else {
                    $('.toast').toast('show');
                  }
                });


                var listaCatsSelect = document.getElementById('seleccionar-categoria'); //Select de categorias
                var listaFabsSelect = document.getElementById('seleccionar-fabricante'); //Select de categorias

                crearArticuloModal.addEventListener('hide.bs.modal', function(event) { //Al cerrar volvemos al estado inicial
                  console.log('emoserrado el modal');
                  //Convertimos la cadena en un objeto
                  $('#crear-articulo-modal .modal-body .card img').attr('src', 'img/2273.jpg');
                  $('#crear-articulo-modal .modal-body .card h5').html('No hemos encontrado el artículo.');
                  $('#crear-articulo-modal .modal-body .card p').html('Eso no quiere decir que no exista, sólo que con estos datos no aparece nada. Echa un vistazo e intenta completar los datos con el EAN o cualquier otro campo que pueda resultar de utilidad');
                })



                modalCrearSoOdoo.addEventListener('hide.bs.modal', function(event) {

                  let bloques = ['cliente-factura', 'cliente-envio']; //Bloques a limpiar
                  bloques.forEach(bloque => {
                    document.getElementById(bloque).innerHTML = '';
                  })
                  //Convertimos la cadena en un objeto
                  $('#referencia-inforpor-modal').empty(); //Ponemos el número de pedido de inforpor
                  $('#referencia-cliente-modal').empty(); //Ponemos la referencia interna
                  $('#articulos-crear-compra-modal').empty();
                  $('#nombre-cliente-modal').empty();
                  // $('#vat-modal').empty();
                  $('#btn-armageddon').removeAttr('disabled');
                  $('#crear-solicitar-compra').html('Solicitud de compra');
                  $('#crear-presupuesta-venta').html('Presupuesto de venta');
                  $('#list-group-comprobacion').hide();
                })

                modalComprarInforpor.addEventListener('show.bs.modal', function(event) {
                  let dPedido = JSON.parse(event.relatedTarget.getAttribute('data-pedido'));
                  let pedOdoo = JSON.parse(event.relatedTarget.getAttribute('ped-odoo'));
                  //  console.log(dPedido);
                  //  console.log(event.relatedTarget);
                  //Colocamos los artículos
                  carritoCompra.innerHTML = ''; //Vaciamos el carrito
                  dPedido.carrito.forEach(art => {
                    let tr = document.createElement('tr');
                    let tdUds = document.createElement('td');
                    let tdCodigo = document.createElement('td');
                    let tdNombre = document.createElement('td');
                    let tdMpn = document.createElement('td');

                    let tdPrecioUd = document.createElement('td');
                    let tdPrecioLinea = document.createElement('td');
                    let tdStock = document.createElement('td');
                    carritoCompra.appendChild(tr);

                    tdUds.innerHTML = art.datosArticulo.cantidad;
                    tr.appendChild(tdUds);
                    tdCodigo.innerHTML = art.codigoProveedor;
                    tr.appendChild(tdCodigo);
                    tdNombre.innerHTML = art.datosArticulo.descripcion;
                    tr.appendChild(tdNombre);
                    tdMpn.innerHTML = art.datosArticulo.referencia;
                    tr.appendChild(tdMpn);
                    tdPrecioUd.innerHTML = art.precioCompra;
                    tr.appendChild(tdPrecioUd);
                    tdPrecioLinea.innerHTML = art.precioCompra * art.datosArticulo.cantidad;
                    tr.appendChild(tdPrecioLinea);
                    tdStock.innerHTML = art.stock;
                    if (art.stock == 0) {
                      tr.classList.add('text-danger');
                    }
                    tr.appendChild(tdStock);
                    if (art.hasOwnProperty('idCustodia')) {
                      tr.setAttribute('data-idCustodia', art.idCustodia)
                    }
                    if (art.hasOwnProperty('idReserva')) {
                      tr.setAttribute('data-idReserva', art.idReserva)
                    }
                  });
                  //Colocamos los datos de envío
                  let dirEnvio = dPedido.dirEnvio;
                  let provincia = sanearProvincia(dirEnvio.provincia);
                  if (dirEnvio.hasOwnProperty('empresa') && dirEnvio.empresa != '') {
                    document.getElementById('empresa-envio-compra').innerHTML = dirEnvio.empresa;
                  }
                  document.getElementById('nombre-envio-compra').innerHTML = dirEnvio.nombre_completo;
                  document.getElementById('email-envio-compra').innerHTML = dirEnvio.email;
                  document.getElementById('tlfo-envio-compra').innerHTML = dirEnvio.telefono;
                  document.getElementById('direccion-envio-compra').innerHTML = dirEnvio.direccion;
                  document.getElementById('cp-poblacion-envio-compra').innerHTML = '<span id="envioCp">' + dirEnvio.codigo_postal + '</span> - <span id="envioPoblacion">' + dirEnvio.ciudad + '</span> (<span id="envioProvincia">' + provincia + '</span>)';

                  //Colocamos la referencia del pedido
                  //inputRefPedido.value = pedOdoo;
                });
                //Capturamos el click en el botón para realizar el pedido en el proveedor
                comprarEnProveedor.addEventListener('click', event => {
                  let tipoEnvio = document.querySelectorAll('input[name="tipoEnvioPedido"]'); //Cogemos los radios para ver el tipo de envío
                  let tipoEnvioSeleccionado = [...tipoEnvio].filter(i => i.checked);
                  let tipo = tipoEnvioSeleccionado[0].id;
                  let totalParcial = document.querySelectorAll('input[name="envioParcialCompleto"]'); //Cogemos los radios para ver el tipo de envío
                  let totalParcialSeleccionado = [...totalParcial].filter(i => i.checked);
                  let parcialCompleto = totalParcialSeleccionado[0].value;
                  let DirEnvio;
                  let error = false;
                  switch (tipo) {
                    case 'envioCliente':
                      if (selectAgencia.value == 'noAgencia') {
                        alert('tienes que seleccionar una agencia de transporte');
                        error = true;
                      }
                      let empresaEnvio = document.getElementById('empresa-envio-compra').innerHTML != '' ? document.getElementById('empresa-envio-compra').innerHTML : document.getElementById('nombre-envio-compra').innerHTML; //Si tenemos empresa la ponemos, si no ponemos el nombre del cliente
                      DirEnvio = empresaEnvio + ';' + document.getElementById('nombre-envio-compra').innerHTML + ';' + document.getElementById('direccion-envio-compra').innerHTML + ';' + envioPoblacion.innerHTML + ';' + envioCp.innerHTML + ';' + envioProvincia.innerHTML + ';;' + document.getElementById('tlfo-envio-compra').innerHTML + ';' + selectAgencia.value + ';ES';
                      break;
                    case 'recogeCliente':
                      DirEnvio = 'LO RECOGEN';
                      break;
                    case 'envioFutura':
                      //   DirEnvio = 'Futura Teck de Murcia S.L.U.;;Avenida Alto de las Atalayas,18;Cabezo de Torres;30110;Murcia;968902300;;'+selectAgencia.value+';ES';         
                      DirEnvio = '';
                      break;

                  }

                  let LinPed = [];
                  let filasArticulos = carritoCompra.querySelectorAll('tr');
                  [...filasArticulos].forEach(k => {
                    let trs = k.querySelectorAll('td');
                    let lin = {
                      codinf: trs[1].innerHTML,
                      cant: trs[0].innerHTML
                    };
                    if (k.getAttribute('data-idCustodia')) { //Si tenemos la id de custodia la añadimos
                      lin.idCustodia = k.getAttribute('data-idCustodia');
                    }
                    if (k.getAttribute('data-idReserva')) { //Si tenemos la id de custodia la añadimos
                      lin.idReserva = k.getAttribute('data-idReserva');
                    }
                    LinPed.push(lin);
                  });

                  let lineasPed = {
                    LinPed: LinPed
                  };
                  objPedidoInforpor = {
                    numpedCli: inputRefPedido.value,
                    DirEnvio: DirEnvio,
                    env: parcialCompleto,
                    observaciones: notasPedido.value,
                    lineasPed: lineasPed
                  }
                  //          console.log(objPedidoInforpor);
                  if (error === false) { //Si no hay ningún error hacemos el pedido
                    let resultadoCompra = JSON.parse($.ajax({
                      type: "POST",
                      url: "ajax/inforpor/hacer-pedido.php",
                      data: objPedidoInforpor,
                      dataType: 'json',
                      global: false,
                      async: false,
                      success: function(data, textStatus, jqXHR) {
                        return data;
                      },
                      error: function(data, textStatus, jqXHR) {
                        alert('Error: ' + JSON.stringify(data));
                      }
                    }).responseText);
                    if (resultadoCompra.RealizaPedidoResult.CodErr == '0') { //Si tenemos éxito mostramos info
                      varModalInforpor.hide();
                      varModalExito.show();
                    }

                  }
                });

                modalAddIfp.addEventListener('show.bs.modal', event => { //CApturamos la acción de abrir el modal
                  let referencia = event.relatedTarget.dataset.mpn;
                  addCodigoInforpor.setAttribute('data-mpn', referencia); //Adjuntamos la referencia al botón
                });

                addCodigoInforpor.addEventListener('click', function(e) {
                  //            console.log(e.relatedTarget.dataset);
                  let referencia = e.target.dataset.mpn; //Cogemos la referencia
                  let codigo = inputCodigoIfp.value;
                  if (codigo.length == 5) {
                    let o = {
                      mpn: referencia,
                      cod_inforpor: codigo
                    }
                    let graba = addAttrValor(o);
                    if (graba == 'ok') {
                      varModalAddIfp.hide();
                      varModalExito.show();
                    }
                    //     console.log(typeof graba);
                    //    console.log(o);
                  }

                });
                //Capturamos cualquier cambio en el select del estado pedido
                listaStatus.addEventListener('change', e => {
                  let nuevoEstado = e.target.value;
                  
                  console.log(e.target.value);
                });


                /*$('#menu-plataforma a').on('click', (e) => {
                  e.preventDefault();
                  let valor = $(e.currentTarget).data('pl');
                  redireccionarPagina('pl', valor);
                })*/


                /******************************************************************************/
                // GUIÓN DE LA APLICACIÓN
                /******************************************************************************/
                //var ultimosPedidos = dameListadoJson('ultimos pedidos');                        //Recuperamos los úlitmos 100 pedidos
                /*for (s in ultimosPedidos) {                                                     //Recorremos el listado de pedidos
                var pedido = ultimosPedidos[s];                                                 //Capturamos el pedido actual en una variable
                var estadoPedido = pedido.lineas_pedido[0].estado;                              //El estado actual lo obtenemos de la primera linea del pedido
                                                                                                //Colocamos los datos del pedido en la tabla
                $('#dataTable tbody').append('<tr id="'+pedido.id+'"><td>'+pedido.fecha_creado+'</td><td>'+pedido.id+'</td><td>'+estadoPedido+'</td><td class="articulos"></td><td>'+new Intl.NumberFormat("es-ES", {style: "currency", currency: "EUR"}).format(pedido.total_pedido)+'</td><td><button class="btn btn-info btn-circle" type="submit"><i class="fas fa-horse-head"></i></button></td></tr>')
                for (a in pedido.lineas_pedido) {                                               //Colocamos la parte de los articulos
                var linea = pedido.lineas_pedido[a];
                $('#'+pedido.id+' .articulos').append(linea.cantidad+' x <b>'+linea.nombre+'</b><span style="font-size: 0.8em"> (SKU de producto: '+linea.sku+' | SKU de oferta: '+linea.sku_oferta+') </span> <br>');
                }

                }*/
                //listapds = colocaDatosPedido(ultimosPedidos);                                              //Lo movemos a mijs y creamos una funcion

              });
              $('.loader').hide();
            </script>

          <?php endif ?>
          <!-- Page Heading -->


          <div class="row bg-white">
            <nav class="navbar ">
              <div class="container-fluid">
                <a class="navbar-brand p-2" href="<?= $_SERVER['PHP_SELF'] ?>?pl=mage" id="nav-mage">
                  <img src="https://icons-for-free.com/iconfiles/png/512/development+logo+magento+icon-1320184807335224584.png" alt="Logo" width="30" height="30" class="d-inline-block align-text-top">
                  Magento
                </a>
                <a class="navbar-brand p-2" href="<?= $_SERVER['PHP_SELF'] ?>?pl=odoo" id="nav-odoo">
                  <img src="https://cu1.uicdn.net/b99/d811c420cd31b6c6cd8b920905906/webapp/icon1752x_odoo.png" alt="Logo" width="30" height="30" class="d-inline-block align-text-top">
                  Odoo
                </a>
                <a class="navbar-brand p-2" href="<?= $_SERVER['PHP_SELF'] ?>?pl=pcc" id="nav-pcc">
                  <img src="data:image/jpeg;base64,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" alt="Logo" width="30" height="30" class="d-inline-block align-text-top">
                  Pc componentes
                </a>
                <a class="navbar-brand p-2" href="<?= $_SERVER['PHP_SELF'] ?>?pl=miravia" id="nav-miravia">
                  <img src="https://img.mrvcdn.com/g/tps/imgextra/i3/O1CN01POpHi51ZIp7E0pdw1_!!6000000003172-2-tps-360-360.png" alt="Logo" width="30" height="30" class="d-inline-block align-text-top">
                  Miravia
                </a>
                <a class="navbar-brand p-2" href="<?= $_SERVER['PHP_SELF'] ?>?pl=correos" id="nav-correos">
                  <img src="data:image/png;base64,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" alt="Logo" width="30" height="30" class="d-inline-block align-text-top">
                  Correos
                </a>
                <a class="navbar-brand p-2" href="<?= $_SERVER['PHP_SELF'] ?>?pl=inforpor" id="nav-inforpor">
                  <img src="./img/logos/icono-inforpor.jpg" alt="Logo" width="30" height="30" class="d-inline-block align-text-top">
                  Inforpor
                </a>
              </div>
            </nav>
          </div>

          <div class="card shadow mb-4">
            <div class="card-header py-3">
              <h6 class="m-0 font-weight-bold text-primary">Últimos pedidos</h6>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>Creado</th>
                      <th>Número</th>
                      <th>Estado</th>
                      <th>Cliente</th>
                      <th>Detalles</th>
                      <th>Importe</th>
                      <th></th>
                    </tr>
                  </thead>
                  <tfoot>
                    <tr>
                      <th>Creado</th>
                      <th>Número</th>
                      <th>Estado</th>
                      <th>Cliente</th>
                      <th>Detalles</th>
                      <th>Importe</th>
                      <th></th>
                    </tr>
                  </tfoot>

                </table>
              </div>
            </div>
          </div>

        </div>


        <!-- /.container-fluid -->
        <!-- BOF MODALES -->
        <!-- BOF MODAL COMPRAR A INFORPOR -->
        <div class="modal" tabindex="-1" id="modalComprarInforpor">
          <div class="modal-dialog modal-xl">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title">Comprar a proveedor</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <div class="card border-info h-100 mb-4">
                  <div class="card-header bg-white">
                    <i class="fa-solid fa-boxes-stacked mr-2"></i>Artículos
                  </div>
                  <div class="card-body">
                    <div class="row">
                      <div class="alert alert-info visually-hidden" role="alert">
                        A simple info alert with <a href="#" class="alert-link">an example link</a>. Give it a click if you like.
                      </div>
                      <div class="table-responsive">
                        <table class="table table-sm table-light">
                          <thead>
                            <tr>
                              <th scope="col">Cant.</th>
                              <th scope="col">código</th>
                              <th scope="col">nombre</th>
                              <th scope="col">mpn</th>
                              <th scope="col">€/ud</th>
                              <th scope="col">€/linea</th>
                              <th scope="col">stock</th>
                            </tr>
                          </thead>
                          <tbody id="carritoCompra">
                            <!-- Aquí ponemos las líneas con los artículos -->
                          </tbody>
                        </table>
                      </div>


                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-6 ">

                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="tipoEnvioPedido" id="envioCliente" value="envio" checked>
                      <label class="form-check-label" for="envioCliente">
                        <div class="card border-info h-100 mb-4">
                          <div class="card-header"><span class="material-icons align-bottom">
                              local_shipping
                            </span> Enviar </div>
                          <div class="card-body">
                            <div class="card-title fw-bold " id="nombre-envio-compra"> <!-- Nombre para envio --> </div>
                            <div class="card-title fw-bold " id="empresa-envio-compra"></div>
                            <div class="card-text " id="email-envio-compra"><!-- Email de envío --></div>
                            <div class="card-text " id="tlfo-envio-compra"><!-- Teléfono de envio --></div>
                            <div class="card-text " id="direccion-envio-compra"><!-- direccion de envio--></div>
                            <div class="card-text " id="cp-poblacion-envio-compra"><!-- Código postal, población y provincia de envio --></div>
                          </div>
                        </div>
                        <select class="form-select" aria-label="Elige la agencia select" id="selectAgencia">
                          <option value="noAgencia" selected>¿Que agencia usamos?</option>
                          <option value="SEUR">Seur</option>
                          <option value="CHRONOEXPRES">Correos Express</option>
                          <option value="DHL">DHL</option>
                        </select>
                      </label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="tipoEnvioPedido" id="recogeCliente" value="recoge">
                      <label class="form-check-label" for="recogeCliente">
                        Recoge el cliente
                      </label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="tipoEnvioPedido" id="envioFutura" value="futura">
                      <label class="form-check-label" for="envioFutura">
                        Traer aquí
                      </label>
                    </div>
                  </div>
                  <div class="col-6 ">
                    <div class="row g-3 align-items-center">
                      <div class="col-auto">
                        <label for="inputRefPedido" class="col-form-label">Referencia del pedido: </label>
                      </div>
                      <div class="col-auto">
                        <input type="text" id="inputRefPedido" class="form-control" aria-describedby="Reerencia del pedido">
                      </div>
                    </div>

                    <div class="form-check form-check-inline">
                      <input class="form-check-input" type="radio" name="envioParcialCompleto" id="envioCompleto" value="T" checked>
                      <label class="form-check-label" for="envioCompleto">
                        Envío completo
                      </label>
                    </div>
                    <div class="form-check form-check-inline">
                      <input class="form-check-input" type="radio" name="envioParcialCompleto" id="envioParcial" value="P">
                      <label class="form-check-label" for="envioParcial">
                        Envío parcial
                      </label>
                    </div>
                    <div class="mb-3">
                      <div class="form-floating">
                        <textarea class="form-control" placeholder="Leave a comment here" id="notasPedido" style="height: 100px"></textarea>
                        <label for="floatingTextarea2"><svg fill="none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24">
                            <path d="M22 2H2v14h2V4h16v12h-8v2h-2v2H8v-4H2v2h4v4h4v-2h2v-2h10V2z" fill="currentColor"></path>
                          </svg> Notas</label>
                      </div>
                    </div>
                    <button type="button" class="btn btn-primary" id="comprarEnProveedor">Comprar</button>
                  </div>

                </div>

              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-target="#modal-pedido" data-bs-toggle="modal">volver</button>
              </div>
            </div>
          </div>
        </div>
        <!-- EOF MODAL COMPRAR A INFORPOR -->
        <!-- BOF MODAL CON INFO PEDIDO -->
        <div class="modal" id="modal-pedido" tabindex="-1">
          <!--  <div class="modal-dialog modal-xl">-->
          <div class="modal-dialog modal-fullscreen">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title">Pedido xxxxx (plataforma)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">

                <div class="row">
                  <div class="col-3      ">
                    <div class="row">
                      <div class="col-1"> <span class="material-icons align-bottom">
                          numbers
                        </span></div>
                      <div class="col"> <span class="dato-pedido" id="ref-pedido"><!-- referencia del pedido --></span></div>
                    </div>
                  </div>
                  <div class="col-3">
                    <div class="row">
                      <div class="col-1"> <span class="material-icons align-bottom">
                          calendar_month
                        </span></div>
                      <div class="col"> <span class="dato-pedido" id="fecha-pedido"><!-- fecha del pedido --></span></div>
                    </div>

                  </div>
                  <div class="col-3      ">
                    <div class="row">
                      <div class="col-1"> <span class="material-icons align-bottom">
                          military_tech
                        </span></div>
                      <div class="col visually-hidden" id="estadoNoMage"> <span class="dato-pedido" id="estado-pedido"><!-- estado del pedido --></span></div>
                      <div class="col visually-hidden" id="estadoSiMage"> <select class="form-select form-select-sm" id="listaStatus" aria-label=".form-select-sm example">
                          <option value="0">Cargando estado</option>
                          <option value="canceled">Cancelado</option>
                          <option value="payment_review">Pendiente de confirmación</option>
                          <option value="processing">En proceso</option>
                          <option value="pending_payment">Pendiente de pago</option>

                        </select><span class="material-symbols align-bottom">
                          expand_content
                        </span>
                      </div>
                    </div>
                  </div>
                  <div class="col-3 ">
                    <span class="badge datos-pedido" id="tienda-pedido"><!--etiquetas --> </span>
                  </div>
                </div>
                <div class="row">
                  <div class="row row-cols-1 row-cols-md-2 g-2 ml-1">
                    <div class="col" id="columna-factura">
                      <div class="card bg-light h-100">
                        <div class="card-header"><span class="material-icons align-bottom">
                            account_balance_wallet
                          </span> Facturar <i class="fa-solid fa-circle-notch fa-spin float-end" id="chkCliente"></i> </div>
                        <div class="card-body">
                          <h5 class="card-title datos-pedido" id="titulo-factura-pedido"> <!-- titulo para facturar --> </h5>
                          <div class="card-title datos-pedido" id="subtitulo-factura-pedido"> <!-- subtitulo para facturar --> </div>
                          <div class="card-text datos-pedido" id="email-factura-pedido"><!-- Email de facturacion --></div>
                          <div class="card-text datos-pedido" id="tlfo-factura-pedido"><!-- Teléfono de facturacion --></div>
                          <div class="card-text datos-pedido" id="nif-factura-pedido"><!-- NIF para facturacion --></div>
                          <div class="card-text datos-pedido" id="direccion-factura-pedido"><!-- direccion de factura--></div>
                          <div class="card-text datos-pedido" id="cp-poblacion-factura-pedido"><!-- Código postal, población y provincia de facturación --></div>
                        </div>
                      </div>


                    </div>
                    <div class="col" id="columna-envio" igual-factura="false">
                      <div class="card border-info h-100">
                        <div class="card-header"><span class="material-icons align-bottom">
                            local_shipping
                          </span> Enviar <i class="fa-solid fa-circle-notch fa-spin float-end" id="chkClienteEnvio"></i></div>
                        <div class="card-body">
                          <div class="card-title fw-bold datos-pedido" id="nombre-envio-pedido"> <!-- Nombre para envio --> </div>
                          <div class="card-title fw-bold datos-pedido" id="empresa-envio-pedido"> <!-- Empresa para envio --> </div>
                          <div class="card-text datos-pedido" id="email-envio-pedido"><!-- Email de envío --></div>
                          <div class="card-text datos-pedido" id="tlfo-envio-pedido"><!-- Teléfono de envio --></div>
                          <div class="card-text datos-pedido" id="direccion-envio-pedido"><!-- direccion de envio--></div>
                          <div class="card-text datos-pedido" id="cp-poblacion-envio-pedido"><!-- Código postal, población y provincia de envio --></div>
                        </div>
                      </div>

                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="row row-cols-1 row-cols-md-2 g-2 ml-1">
                    <div class="col col-md-12">
                      <div class="card">
                        <div class="card-header bg-white">
                          <i class="fa-solid fa-boxes-stacked mr-2"></i>Artículos <span class="material-icons float-end fs-2" id="totalArticulos" data-total="0" data-tenemos="0">
                            help_outline
                          </span>
                        </div>

                        <div class="row">
                          <div class="alert alert-info visually-hidden" role="alert">
                            A simple info alert with <a href="#" class="alert-link">an example link</a>. Give it a click if you like.
                          </div>
                          <div class="table-responsive">
                            <table class="table table-sm table-light">
                              <thead>
                                <tr>
                                  <th scope="col"></th>
                                  <th scope="col">Referencia</th>
                                  <th scope="col">Descripción</th>
                                  <th scope="col">uds.</th>
                                  <th scope="col">precio ud.</th>
                                  <th scope="col">total</th>
                                  <th scope="col">check</th>
                                </tr>
                              </thead>
                              <tbody id="tablaArticulos">
                                <!-- Aquí ponemos las líneas con los artículos -->
                              </tbody>
                            </table>
                          </div>


                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="row row-cols-1 row-cols-md-2 g-2 ml-1" id="rowCarritos">
                    <!-- Aqui vamos a poner el contenido de los distintos carritos para hacer las compras -->
                    <!--  <div class="col col-md-2" >
              <div class="card">
                <div class="card-header bg-white">Col1</div>
                <ul class="list-group list-group-flush">
  <li class="list-group-item">An item</li>
  <li class="list-group-item">A second item</li>
  <li class="list-group-item">A third item</li>
  <li class="list-group-item">A fourth item</li>
  <li class="list-group-item">And a fifth one</li>
</ul></div></div>-->

                  </div>
                </div>
                <div class="row">
                  <h5 class="mb-2 my-2 pr-4"><span class="material-icons fs-2 align-text-bottom mr-2">
                      account_circle
                    </span>Compras y facturación</h5>
                  <div class="row">
                    <div class="card-group">
                      <!--<div class="card mb-3" style="max-width: 540px;" id="cardIfp">-->
                      <div class="card mb-3" id="cardIfp">
                        <div class="row g-0">
                          <div class="col-md-5 text-center" style="top:30%">
                            <img src="https://tienda.inforpor.com/assets/images/logo.png" alt="logo inforpor" width="150">
                          </div>
                          <div class="col-md-7 visually-hidden" id="si-hay-tracking">
                            <div class="card-body">
                              <h5 class="card-title text-end"><!--estado del envío --></h5>
                              <ul class="list-group list-group-flush">
                                <li class="list-group-item ">
                                  <div class="row justify-content-center align-items-center g-2">
                                    <div class="col"><i class="fa-solid fa-hashtag text-gray-500"></i></div>
                                    <div class="col text-end text-gray-800" id="ref-inforpor"><!-- referencia de inforpor --></div>
                                  </div>
                                </li>
                                <li class="list-group-item ">
                                  <div class="row justify-content-center align-items-center g-2">
                                    <div class="col"><i class="fa-solid fa-user text-gray-500"></i></div>
                                    <div class="col text-end text-gray-800" id="ref-cliente"><!-- referencia interna del cliente --></div>
                                  </div>
                                </li>
                                <li class="list-group-item ">
                                  <div class="row justify-content-center align-items-center g-2">
                                    <div class="col"><i class="fa-solid fa-truck text-gray-500"></i></div>
                                    <div class="col text-end text-gray-800" id="agencia"><!-- nombre de la agencia --></div>
                                  </div>
                                </li>
                                <li class="list-group-item ">
                                  <div class="row justify-content-center align-items-center g-2">
                                    <div class="col"><i class="fa-solid fa-tag text-gray-500"></i></div>
                                    <div class="col text-end text-gray-800" id="expedicion"><!-- expedicion--></div>
                                  </div>
                                </li>
                              </ul>
                            </div>
                          </div>
                          <div class="col-md-7 text-center p-4 " id="no-hay-tracking"><img class="w-auto card-img-top" src="img/unicornio-angustia.png" alt="unicornio con angustia">
                            <div class="card-body">
                              <p class="card-text">No tenemos información de este pedido.</p>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="card mb-3">
                        <div class="row g-0">
                          <div class="col-md-4 text-center" style="top:30%">
                            <img src="https://www.odoo.com/web/image/website/1/social_default_image?unique=f75b8fa" alt="logo odoo" style="width:120px">
                          </div>
                          <div class="col-md-8">
                            <div class="card-body">
                              <div class="card">
                                <ul class="list-group list-group-flush">
                                  <li class="list-group-item ">
                                    <div class="row justify-content-center align-items-center g-2">
                                      <div class="col"><i class="fa-solid fa-file-export fa-2x text-gray-500"></i></div>
                                      <div class="col" id="pedVentaOdoo">No existe</div>
                                    </div>
                                  </li>
                                  <li class="list-group-item ">
                                    <div class="row justify-content-center align-items-center g-2 visually-hidden" id="pedsCompraOdoo">
                                      <div class="col"><i class="fa-solid fa-file-import fa-2x text-gray-500"></i></div>
                                    </div>
                                  </li>
                                  <li class="list-group-item visually-hidden" id="mostrar-btn-sonreir">
                                    <div class="d-grid gap-2 ">
                                      <button type="button" class="btn btn-info btn-lg" data-bs-toggle="modal" data-bs-target="#modalCrearSoOdoo">:) sonreir</button>
                                    </div>
                                  </li>
                                </ul>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
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
        <!-- EOF MODAL CON INFO PEDIDO -->
        <!-- EOF MODALES -->
        <!-- modal comprobar cliente -->
        <div class="modal fade" id="comprueba-cliente-modal" tabindex="-1" aria-labelledby="comprueba-cliente-modal" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="comprueba-cliente-modal-label">Comprobando cliente</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <img src="img/unicorn-loading-turquesa.gif" class="img-fluid comprueba-cliente-loader" alt="cargando cosas">
                <div class="container-fluid">
                  <div class="progress mb-2">
                    <div class="progress-bar progress-bar-striped bg-info progress-bar-animated" role="progressbar" style="width: 33%" aria-valuenow="33" aria-valuemin="0" aria-valuemax="100">comprobando</div>
                  </div>
                </div>
                <div class="container-fluid comprueba-cliente-cuerpo" style="display:none">

                </div>

              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
              </div>
            </div>
          </div>
        </div>
        <!-- FIN modal comprobar cliente -->

        <!-- BOF modal crear artículo -->
        <div class="modal fade" id="crear-articulo-modal" tabindex="-1" aria-labelledby="crear-articulo-modal" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="crear-articulo-modal-label">Comprobando articulo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <img src="img/unicorn-loading-turquesa.gif" class="img-fluid crear-articulo-loader" alt="cargando cosas">
                <div class="container-fluid">
                  <div class="w-auto card" style="width: 18rem;">
                    <img src="img/2273.jpg" class="card-img-top" alt="">
                    <div class="card-body">
                      <h5 class="card-title">No hemos encontrado el artículo.</h5>
                      <p class="card-text">Eso no quiere decir que no exista, sólo que con estos datos no aparece nada. Echa un vistazo e intenta completar los datos con el EAN o cualquier otro campo que pueda resultar de utilidad</p>
                      <p class="card-text text-info">Ahora, como novedad novedosa, podemos crear el artículo desde aquí sin hacer trabajar a los demás.</p>
                      <div class="mb-3">
                        <label for="seleccionar-categoria" class="form-label">Elige la categoría</label>

                        <select class="form-select select-articulo" aria-label="seleccionar categoria" id="seleccionar-categoria">
                          <option value="0" selected>Categoria del artículo</option>
                        </select>
                      </div>
                      <div class="mb-3">
                        <label for="seleccionar-fabricante" class="form-label">Elige el fabricante</label>

                        <select class="form-select select-articulo" aria-label="seleccionar fabricante" id="seleccionar-fabricante">
                          <option value="0" selected>Fabricante del artículo</option>
                        </select>
                      </div>
                      <div class="d-grid gap-2 col-8 mx-auto mt-2">
                        <a href="#" class="btn btn-success btn-icon-split disabled" id="btn-crear-articulo">
                          <span class="icon text-white-50">
                            <i class="fas fa-check"></i>
                          </span>
                          <span class="text">Crear artículo</span>
                        </a>
                      </div>

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

        <!-- BOF modal crear PO en Odoo -->
        <div class="modal fade" id="modalCrearPoOdoo" tabindex="-1" aria-labelledby="modal-crear-po-odoo" aria-hidden="true">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="crear-po-odoo-modal-label"><img src="img/unicornio-dinero.png" alt="">Comprando unicornios de repuesto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <div class="container-fluid">
                  <div class="row">
                    <div class="card mb-3">
                      <div class="row g-0 align-items-center">

                        <div class="card-body">
                          <!-- Some borders are removed -->
                          <ul class="list-group list-group-flush text-end">
                            <li class="list-group-item list-group-item-light">
                              <div class="row">
                                <div class="col-md-1"><i class="fa-solid fa-receipt fa-2x align-bottom"></i></div>
                                <div class="col-md-3 text-start"> Proveedor </div>
                                <div class="col-md-8">
                                  <div class="row my-1">
                                    <!--   <select class="form-select form-select-sm" aria-label=".form-select-sm example" id="elige-proveedor">
                                    </select>-->
                                    <p id="nombreProveedor" data-id-proveedor="217">Inforpor</p>
                                  </div>
                                </div>
                              </div>
                            </li>
                            <li class="list-group-item list-group-item-light">
                              <div class="row">
                                <div class="col-md-1"><i class="fa-solid fa-receipt fa-2x align-bottom"></i></div>
                                <div class="col-md-3 text-start"> Referencia del proveedor </div>
                                <div class="col-md-8">
                                  <div class="row my-1">
                                    <!--   <select class="form-select form-select-sm" aria-label=".form-select-sm example" id="elige-proveedor">
                                    </select>-->
                                    <input type="text" id="inputRefProveedor" class="form-control">
                                    <input type="hidden" id="inputFechaProveedor" class="form-control">
                                    <!--<input type="hidden" id="inputPortesProveedor" class="form-control">-->
                                  </div>
                                </div>
                              </div>
                            </li>
                          </ul>

                        </div>

                      </div>
                    </div>
                  </div>
                  <!-- Inicio Bloque Articulos-->
                  <div class="row">
                    <div class="card mb-3">
                      <div class="row g-0 align-items-center">

                        <div class="card-body">
                          <!-- Some borders are removed -->
                          <ul class="list-group list-group-flush text-end">
                            <li class="list-group-item list-group-item-light">
                              <div class="row">
                                <div class="col-md-1"><i class="fa-solid fa-boxes-stacked fa-2x align-bottom"></i></div>
                                <div class="col-md-11">
                                  <div class="row mb-2 text-start" id="lineasPoOdoo">
                                  </div>
                                </div>
                              </div>
                            </li>


                          </ul>

                        </div>

                      </div>
                    </div>
                  </div>
                  <div class="row mt-3"><button type="button" class="btn btn-danger" id="btnCrearPo">Armageddon</button></div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- BOF modal crear PO en Odoo -->


        <!-- BOF modal crear SO en Odoo -->
        <div class="modal fade" id="modalCrearSoOdoo" tabindex="-1" aria-labelledby="modalCrearSoOdoo" aria-hidden="true">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="crear-solicitud-compra-modal-label"><img src="img/unicornio-dinero.png" alt="">Comprando unicornios de repuesto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <div class="container-fluid">
                  <!-- Inicio Bloque cliente-->
                  <div class="row">
                    <div class="card mb-3">
                      <div class="row g-0 align-items-center">

                        <div class="card-body">
                          <!-- Some borders are removed -->
                          <ul class="list-group list-group-flush text-end">
                            <li class="list-group-item list-group-item-light">
                              <div class="row">
                                <div class="col-md-1"><i class="fa-solid fa-receipt fa-2x align-bottom"></i></div>
                                <div class="col-md-3 text-start"> Factura </div>
                                <div class="col-md-8">
                                  <p id="cliente-factura"></p>
                                </div>
                              </div>
                            </li>
                            <li class="list-group-item list-group-item-light">
                              <div class="row">
                                <div class="col-md-1"><i class="fa-solid fa-dolly fa-2x align-bottom"></i></div>
                                <div class="col-md-3 text-start"> Envío </div>
                                <div class="col-md-8">
                                  <p id="cliente-envio"></p>
                                </div>
                              </div>
                            </li>
                            <li class="list-group-item list-group-item-light">
                              <div class="row">
                                <div class="col-md-1"><i class="fa-solid fa-folder-tree fa-2x align-bottom"></i></div>
                                <div class="col-md-3 text-start"> Tipo de pedido </div>
                                <div class="col-md-8">
                                  <div class="row my-1">
                                    <select class="form-select form-select-sm" aria-label=".form-select-sm example" id="elige-tipo-pedido">
                                    </select>
                                  </div>
                                </div>
                              </div>
                            </li>
                            <li class="list-group-item list-group-item-light visually-hidden">
                              <div class="row">
                                <div class="col-md-1"><i class="fa-solid fa-shop fa-2x align-bottom"></i></div>
                                <div class="col-md-5" id="referencia-inforpor-modal">
                                  <!-- Referencia inforpor -->
                                </div>
                                <div class="col-md-1"><i class="fa-solid fa-fingerprint fa-2x align-bottom"></i></div>
                                <div class="col-md-5" id="referencia-cliente-modal">
                                  <!--Referencia cliente-->
                                </div>
                              </div>
                        </div>
                        </li>
                        </ul>

                      </div>

                    </div>
                  </div>

                  <!-- Fin Bloque cliente-->

                  <!-- Inicio Bloque Articulos-->
                  <div class="row">
                    <div class="card mb-3">
                      <div class="row g-0 align-items-center">

                        <div class="card-body">
                          <!-- Some borders are removed -->
                          <ul class="list-group list-group-flush text-end">
                            <li class="list-group-item list-group-item-light">
                              <div class="row">
                                <div class="col-md-1"><i class="fa-solid fa-boxes-stacked fa-2x align-bottom"></i></div>
                                <div class="col-md-11">
                                  <div class="row mb-2 text-start" id="articulos-crear-compra-modal">
                                  </div>
                                </div>
                              </div>
                            </li>
                            <li class="list-group-item list-group-item-light">
                              <div class="row">
                                <div class="col-md-1"><i class="fa-solid fa-warehouse fa-2x align-bottom"></i></div>
                                <div class="col-md-3 text-start"> Almacén </div>
                                <div class="col-md-8">
                                  <div class="row mb-2" id="elige-almacen">
                                    <select class="form-select" aria-label="Default select example">
                                      <option selected>¿De que almacen salimos?</option>
                                      <option value="1">Futura Teck</option>
                                      <option value="2">Inforpor - Custodias</option>
                                    </select>
                                  </div>
                                </div>
                              </div>
                            </li>


                          </ul>

                        </div>

                      </div>
                    </div>
                  </div>

                  <!-- Fin Bloque Articulos-->
                  <!--<iframe src="https://gifer.com/embed/6IIs" width=480 height=369.438 frameBorder="0" allowFullScreen></iframe><p><a href="https://gifer.com">via GIFER</a></p>-->
                  <div class="row mt-3"><button type="button" class="btn btn-danger" id="btn-armageddon">Armageddon</button></div>
                  <div class="row mt-3" id="list-group-comprobacion" style="display:none">
                    <ul class="list-group">
                      <li class="list-group-item d-flex justify-content-between align-items-center" id="crear-presupuesta-venta">
                        Presupuesto de venta
                      </li>
                      <li class="list-group-item d-flex justify-content-between align-items-center" id="crear-solicitar-compra">
                        Solicitud de compra
                      </li>
                    </ul>
                  </div>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
              </div>
            </div>
          </div>
        </div>
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
        <!-- modal add inforpor -->
        <div class="modal" tabindex="-1" id="modalAddIfp">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-body">
                <div class="row g-3 align-items-center">
                  <div class="col-auto">
                    <label for="inputCodigoIfp" class="col-form-label">Código</label>
                  </div>
                  <div class="col-auto">
                    <input type="number" id="inputCodigoIfp" class="form-control">
                  </div>
                  <div class="col-auto">
                    <input class="btn btn-primary" type="button" value="Enviar" id="addCodigoInforpor">
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- fin modal add inforpor -->

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
    <script>
      //ocultamos la clase propia para dejar el botón de acceder a la otra
      $('#menu-plataforma a').filter(function() {
        return $(this).data('pl') == plataforma;
      }).hide();
      //Mostramos el loader antes de nada
      //Definimos la plataforma
      /*switch (plataforma) {
      case 'pcc':
      var ultimosPedidos = dameListadoJson('ultimos pedidos mirakl');                 //Recuperamos los úlitmos 100 pedidos
      listapds = colocaDatosPedido(ultimosPedidos);                                   //Lo movemos a mijs y creamos una funcion
      break ;
      case 'phh':
      var ultimosPedidos = dameListadoJson('ultimos pedidos Phone House');            //Recuperamos los úlitmos 100 pedidos
      listapds = colocaDatosPedido(ultimosPedidos);                                   //Lo movemos a mijs y creamos una funcion
      break ;
      case 'mage':
      var ultimosPedidos = dameListadoJson('ultimos pedidos magento');                 //Recuperamos los úlitmos 100 pedidos
      listapds = colocaDatosPedido(ultimosPedidos);
      //console.log(ultimosPedidos);
      break ;
      }*/
      /******************************************************************************/
      // GUIÓN DE LA APLICACIÓN
      /******************************************************************************/
    </script>
    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

    <!--<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous"></script>-->
    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <!--<script src="vendor/datatables/jquery.dataTables.min.js"></script>
<script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jeditable.js/2.0.17/jquery.jeditable.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/v/bs5/dt-1.12.1/sp-2.0.1/sl-1.4.0/datatables.min.js"></script>
    <script src="js/datatables/pedidos.js"></script>
    <script src="https://cdn.datatables.net/plug-ins/1.12.1/filtering/type-based/html.js"></script>
    <!-- Page level custom scripts -->
    <!--<script src="js/demo/datatables-demo.js"></script>-->
</body>

</html>
<?php
/**
 * ERRORES
 */
// Cuando comprueba las referencias si existen todas marca TODAS como existen, incluyendo las de otros pedidos
?>
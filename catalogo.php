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
  <script src="js/tarifa.js"></script>
  <script src="js/odoo.js"></script>
  <script src="js/catalogo.js"></script>
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
            const querystring = window.location.search;
            console.log(querystring)
            const params = new URLSearchParams(querystring);
            //       params.set(variable, valor);
            const plataforma = params.get('pl');
            const rp = params.get('rp');
            const p = params.get('p');
            var tarifas = '';
            var idTienda;

            //const modalCambiaPrecio = document.getElementById('modal-cambiar-precio') //Modal para cambiar el precio

            $(document).ready(function() {
              const resultadoModal = new bootstrap.Modal('#resultadoModal', {
  keyboard: false
})
              let datos = {};

            //Configuramos algunas cosas según la plataforma
            switch (plataforma) {
              case 'mage245':
                idTienda = 2;
                switchPlataforma.checked = true;
                datos.idAtributo = 137;
                break;
              case 'mage':
              default:
                idTienda = 1;
                datos.idAtributo = 81;
                break;
            }
            datos.idTienda = idTienda;

            let colPlataforma = document.querySelector('.col.'+plataforma);
            console.log(colPlataforma);
            colPlataforma.getElementsByTagName('label')[0].classList.add('text-black');
            colPlataforma.getElementsByTagName('i')[0].classList.add('text-danger');
              var listadoTarifa;
              var pagina;
              var datosBusqueda;
              var listaConjuntosSelect = document.getElementById('seleccionar-conjunto-atributos'); //Select de conjunto de atributos
              var listaFabricantesSelect = document.getElementById('seleccionar-fabricante'); //Select de conjunto de atributos
              var resultadosPaginaSelect = document.getElementById('resultados-pagina'); //Select de conjunto de atributos
              var inputNombre = document.getElementById('inputNombre');
              var inputReferencia = document.getElementById('inputReferencia');
              var btnBuscar = document.getElementById('btn-buscar'); //Boton para buscar
              //     var btnNextpage = document.getElementById('next-page');   //Boton siguiente página
              //   var btnPrevpage = document.getElementById('prev-page');   //Boton siguiente página
              var tablaR = document.getElementById('tablaResultados'); //La tabla con los resultados
              var btnConfirmarAccionBasica = document.getElementById('btn-confirmar-accion-basica');
              //     const modalAccionBasicaArticulo = new bootstrap.Modal(AccionesBasicasArticuloModal,''); //Mostramos el modal para eliminar
              var btnConfirmarGeneral = document.getElementById('btn-confirmar-general'); //modalConfirmarGeneral
              var modalConfirmarGeneral = new bootstrap.Modal(ConfirmacionModal, ''); //Mostramos el modal para eliminar
              //       const varModalExito = new bootstrap.Modal(modalExito, '');

              function compras(listaBusqueda) {
                setTimeout(function() { //Utilizamos el set timeout para forzar que primero se pongan los datos generales
                  /*    if (listadoTarifa === undefined) {
                      let datos;
                      miTarifa = llamadaJson('ajax/google/dame-tarifa-2022.php',datos);             //Pedimos la tarifa si no la tenemos ya
                      listadoTarifa = damelistado(miTarifa);                                      //Obtenemos el listado plano
                    }*/
                  for (articulo of listaBusqueda) {
                    console.log(articulo);

                    if (articulo.status != 2) {
                      //  setTimeout(function(){ //Utilizamos el set timeout para forzar que primero se pongan los datos generales
                      let estaFila = document.querySelector('[data-id="' + articulo.id + '"]'); //Seleccionamos la fila con la vamos a trabajar
                      let tds = estaFila.getElementsByTagName('td');
                      let tdsInfo = []; //Array con info
                      let tdsWarning = []; //Array para mostrar los peligros
                      articulo.idTienda = idTienda;
                      let resultado = dameCompras(articulo);
                      /*  let d = {
                          mpn : articulo.sku,
                          codinfo: articulo.sku
                        }Superliga52x


                              let infoArticulo = llamadaJson('ajax/magento/dame-info-articulo.php',d);  //Buscamos la info detallada de cada artículo*/
                      //Ponemos el stock actual en la celda correspondiente
                      //      console.log('resultado');
                      //     console.log(resultado);
                      let enInforpor = resultado.enInforpor;
                      let encontrado = resultado.encontrado;
                      let infoArticulo = resultado.infoArticulo;
                      let extAttr = infoArticulo.extension_attributes;
                      let qty = extAttr.stock_item.qty;
                      let websites = extAttr.website_ids;
                      if (websites.length === 4) {
                        tdsInfo.push('Este artículo aparece en todas las tiendas');
                      }

                      tds[3].innerHTML = qty; //Colocamos la cantidad

                      //Buscamos el articulo en inforpor
                      //let enInforpor = llamadaJson('ajax/inforpor/obtener-compra.php',d);  //Buscamos la info en inforpor

                      //Buscamos el artículo en la tarifa
                      //let encontrado = listadoTarifa.find(art => art.referencia == articulo.sku);
                      //Si no existe ni en inforpor ni en la tarifa añadimos una alerta
                      if ((enInforpor.length == 0 || enInforpor.normal_inforpor.Cod == "No Datos") && encontrado === undefined) {
                        tdsWarning.push('No hay compras');
                      } else { //En caso contrario una compra hay
                        let compra;
                        let margen;
                        tds[4].innerHTML = '';
                        tds[7].innerHTML = '';
                        switch (true) {
                          case (encontrado === undefined && enInforpor.normal_inforpor.Cod != "No Datos"): //Sólo existe en inforpor
                            compra = enInforpor.normal_inforpor.Precio;
                            margen = (infoArticulo.price / compra) - 1; //OJO que estamos obviando el precio de oferta
                            break;
                          case (encontrado !== undefined && enInforpor.normal_inforpor.Cod == "No Datos"): //Sólo existe en tarifa
                            compra = encontrado.compras[0].total_compra; //De momento nos quedamos con el primer precio de compra que encontramos
                            margen = (infoArticulo.price / compra) - 1; //OJO que estamos obviando el precio de oferta
                            tds[4].innerHTML += '<i class="fa-brands fa-google-drive text-gray-300"></i> ';
                            break;
                          case (encontrado !== undefined && enInforpor.normal_inforpor.Cod != "No Datos"): //Existe en inforpor y en tarifa
                            compraTarifa = encontrado.compras[0].total_compra;
                            compra = enInforpor.normal_inforpor.Precio;
                            // compra = Math.min(compraTarifa,compraInforpor);
                            margenTarifa = (infoArticulo.price / compraTarifa) - 1; //OJO que estamos obviando el precio de oferta 
                            margen = (infoArticulo.price / compra) - 1; //OJO que estamos obviando el precio de oferta
                            tds[4].innerHTML += '<i class="fa-brands fa-google-drive text-gray-300"></i> ' + new Intl.NumberFormat('es-ES', {
                              style: 'currency',
                              currency: 'EUR'
                            }).format(compraTarifa) + '<br>';
                            tds[7].innerHTML += '<i class="fa-brands fa-google-drive text-gray-300"></i> ' + new Intl.NumberFormat('es-ES', {
                              style: 'percent',
                              minimumFractionDigits: 2
                            }).format(margenTarifa) + '<br>';
                            /*console.log('en Inforpor y en tarifa');
                            console.log(compraTarifa);
                            console.log(compraInforpor);*/
                            break;

                        }
                        tds[4].innerHTML += new Intl.NumberFormat('es-ES', {
                          style: 'currency',
                          currency: 'EUR'
                        }).format(compra);
                        tds[7].innerHTML += new Intl.NumberFormat('es-ES', {
                          style: 'percent',
                          minimumFractionDigits: 2
                        }).format(margen);
                        if (margen < 0) {
                          tds[7].classList.add('bg-danger', 'text-white');
                        }

                      }
                      //fa-triangle-exclamation
                      if (tdsInfo.length > 0) {
                        let triangle = tds[0].getElementsByClassName('fa-info')[0];
                        triangle.setAttribute('data-bs-content', JSON.stringify(tdsInfo));
                        triangle.style.display = 'block'; //Mostramos el icono de alerta para ese artículo
                        //console.log(tds[0].getElementsByClassName('fa-triangle-exclamation'));
                      }
                      if (tdsWarning.length > 0) {
                        let icoWarning = tds[0].getElementsByClassName('fa-triangle-exclamation')[0];
                        icoWarning.setAttribute('data-bs-content', JSON.stringify(tdsWarning));
                        icoWarning.style.display = 'block'; //Mostramos el icono de alerta para ese artículo
                        //console.log(tds[0].getElementsByClassName('fa-triangle-exclamation'));
                      }



                      //          console.log(enInforpor.normal_inforpor);
                      //          console.log(infoArticulo);
                      //          console.log(tdsInfo);
                    }

                  }
                }, 100);
              }





              //inicializamos los datos según la plataforma

              let listaConjuntoAtributos = llamadaJson('ajax/magento/dame-conjuntos-atributos.php', datos); //Recuperamos el listado de conjuntos de atributos
              listaConjuntoAtributos.forEach((conjunto, i) => { //Recorremos el listado de conjuntos de atributos
                if (conjunto.entity_type_id == 4) { //El resto son para otros tipos de datos como direcciones etc
                  let option = document.createElement("option"); //Creamos un elemento option
                  option.text = conjunto.attribute_set_name; //Asignamos como texto el nombre
                  option.value = conjunto.attribute_set_id; //Asignamos como valor la id
                  listaConjuntosSelect.add(option); //Añadimos la opcion creada a la lista
                }

              });



              let listaFabricantes = llamadaJson('ajax/magento/dame-valores-atributo.php', datos); //Recuperamos el listado de fabricantes

              listaFabricantes.forEach((fabricante, i) => { //Recorremos el listado de fabricante
                let option = document.createElement("option"); //Creamos un elemento option
                option.text = fabricante.label; //Asignamos como texto el nombre
                option.value = fabricante.value; //Asignamos como valor la id
                listaFabricantesSelect.add(option); //Añadimos la opcion creada a la lista
              });

              /*********************************************************************************************************
              EVENTOS
              ***********************************************************************************************************/
          
           /*   document.getElementById('next-page').addEventListener('click', function(event) { //CApturamos el click en siguiente página
                datosBusqueda.p = ++pagina;
                params.set('p', datosBusqueda.p);
                // reemplazamos el historial del navegador con esta nueva querystring
                window.history.replaceState({}, '', `${window.location.pathname}?${params}`)
                let listaBusqueda = buscar(datosBusqueda);
                colocaResultados(listaBusqueda, datosBusqueda).then(listaBusqueda => {
                  if (typeof(tarifas) === "undefined") {
                    tarifas = '';
                  }
                  getTarifas(tarifas)
                    .then(tarifas => {
                      let obj = {
                        articulos: listaBusqueda,
                        tarifas: tarifas
                      }
                      return obj;
                    })
                    .then(obj => {
                      compraArticulosWorker(obj)
                    });

                  //    console.log('listaBusqueda');
                  //   console.log(listaBusqueda);
                });;
                //colocaResultados(listaBusqueda, datosBusqueda).then(listaBusqueda => compras(listaBusqueda));
              })*/



          /*    document.getElementById('prev-page').addEventListener('click', function(event) { //CApturamos el click en siguiente página
                datosBusqueda.p = --pagina;
                let listaBusqueda = buscar(datosBusqueda);
                colocaResultados(listaBusqueda, datosBusqueda).then(listaBusqueda => compras(listaBusqueda));
                //   console.log(datosBusqueda);
              })*/

              btnConfirmarAccionBasica.addEventListener('click', function(event) { //CApturamos el click en el botón que confirma que queremos eliminar un artículo
                let cosas = {
                  accion: btnConfirmarAccionBasica.getAttribute('data-accion'),
                  plataforma: plataforma,
                  articulo: {
                    sku: btnConfirmarAccionBasica.getAttribute('data-sku'),
                    id: btnConfirmarAccionBasica.getAttribute('data-id'),
                    auto_actualizar: btnConfirmarAccionBasica.getAttribute('data-auto-actualizar')
                  }
                }
                //  console.log('kosas');
                // console.log(cosas);
                if (btnConfirmarAccionBasica.getAttribute('data-status') !== undefined) {
                  cosas.articulo.estado = btnConfirmarAccionBasica.getAttribute('data-estado');
                }

                let resultado = hacerCosas(cosas);
              });


              btnConfirmarGeneral.addEventListener('click', function(event) { //CApturamos el click en el botón que confirma que queremos eliminar un artículo
                let cosas = {
                  accion: btnConfirmarGeneral.getAttribute('data-accion'),
                  plataforma: plataforma,
                  articulo: {
                    sku: btnConfirmarGeneral.getAttribute('data-sku'),
                    precio: btnConfirmarGeneral.getAttribute('data-precio')
                  }
                }
                let resultado = hacerCosas(cosas);
                console.log(resultado);
                if (resultado === true || resultado === 'ok') {
                  cosas.plataforma = 'magento-2-beta';
                  let cosasM245 = hacerCosas(cosas);
                  if (cosasM245 === 'ok') {
                    console.log(cosasM245);
                    new bootstrap.Modal(document.getElementById('modal-cambiar-precio')).hide();
                  } else {
                    console.log(cosasM245);
                  }
                  //  modalAccionBasicaArticulo.hide();
                  //modalExito.show();
                }

              });
           
    

        //      let btnCambiarPrecio = document.getElementById('btnCambiarPrecio');
         /*     btnCambiarPrecio.addEventListener('click', function(event) {
                event.preventDefault();
                let cosas = {
                  accion: btnCambiarPrecio.getAttribute('data-accion'),
                  plataforma: plataforma,
                  articulo: {
                    sku: btnCambiarPrecio.getAttribute('data-sku'),
                    precio: document.getElementById('inputCambiarPrecio').value,
                    cost: margenActual.getAttribute('compra')
                  }
                }
                let resultado = hacerCosas(cosas);

                if (resultado === true || resultado === 'ok') {
                  cosas.plataforma = 'magento-2-beta';
                  let cosasM245 = hacerCosas(cosas);
                  if (cosasM245 === 'ok') {
                    console.log(cosasM245);
                    new bootstrap.Modal(document.getElementById('modal-cambiar-precio')).hide();
                  } else {
                    console.log(cosasM245);
                  }
                  //  modalAccionBasicaArticulo.hide();
                  //modalExito.show();
                }
              });*/
              document.getElementById('inputCambiarPrecio').addEventListener('change', e => { //Capturamo cualquier cambio en el input del nuevo precio
                let nuevoPrecio = e.target.value;
                let compra = document.getElementById('margenActual').getAttribute('compra') ?? 0;
                let nuevoMargen = ((nuevoPrecio / compra) - 1) * 100;
                document.getElementById('margenActual').innerHTML = nuevoMargen.toFixed(2) + '%';
                let articulo = {
                  price: nuevoPrecio,
                  custom_attributes: [
                    {attribute_code: 'cost', value: compra}
                  ]
                }
                btnCambiarPrecio.setAttribute('data-articulo',JSON.stringify(articulo).replace(/[\/\(\)\']/g, "&apos;"));
              //  console.log('e');
               // console.log(e);
              });

           document.addEventListener('keyup', e => { //Capturamos el enter en cualquier parte de la página y lanzamos la búsqueda
            e.preventDefault;
            var keycode = e.keyCode || e.which;
            if (keycode == 13) {
              realizaBusqueda();
            }
           })   

            }); //fin document ready

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
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800"><i class="fab fa-magento"></i> Catálogo Magento</h1>
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


            <div class="col-lg-9 mb-4">

              <!-- Illustrations -->
              <div class="card shadow mb-4">
                <div class="card-header py-3">
                  <h6 class="m-0 font-weight-bold">Filtros</h6>
                </div>
                <div class="card-body">
                  <div class="text-center">
                    <form class="row g-3">
                      <div class="col-auto">
                        <label for="inputReferencia" class="visually-hidden">Referencia</label>
                        <input type="text" class="form-control" id="inputReferencia" placeholder="Referencia">
                      </div>
                      <div class="col-auto">
                        <label for="inputNombre" class="visually-hidden">Nombre</label>
                        <input type="text" class="form-control" id="inputNombre" placeholder="Nombre">
                      </div>
                      <div class="col-auto">
                        <select class="form-select" aria-label="Default select example" id="seleccionar-conjunto-atributos">
                          <option selected value="-">Open this select menu</option>
                        </select>
                      </div>
                      <div class="col-auto">
                        <select class="form-select" aria-label="Default select example" id="seleccionar-fabricante">
                          <option selected value="-">Open this select menu</option>
                        </select>
                      </div>
                      <div class="col-auto">
                        <button type="button" class="btn btn-primary" id="btn-buscar" onclick="realizaBusqueda()">Buscar</button>
                      </div>
                    </form>
                    <form class="row g-3">
                      <div class="col-auto">
                        <select class="form-select" aria-label="Default select example" id="seleccionar-estado">
                          <option selected value="-">Estado</option>
                        </select>
                      </div>
                    </form>
                   <!-- <div class="row g-3">-->
                    
                    <div class="d-grid gap-2 d-md-block text-end">
                    <span class="mr-2"> <i class="fa-solid fa-certificate"></i> Ofertas: </span>      
  <button class="btn btn-warning" type="button" onclick="ofertasFinalizando()">finalizando</button>
  <button class="btn btn-danger" type="button" onclick="ofertasCaducadas()">caducadas</button>
</div><!--</div>-->
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-3 mb-4">
              <div class="w-auto card">
                <div class="card-header py-3">
                  <h6 class="m-0 font-weight-bold">Opciones</h6>
                </div>
                <div class="card-body">
                  <div class="d-grid gap-2">

                    <div class="row">
                      <div class="col-lg-9">
                        Resultados por página
                      </div>
                      <div class="col-lg-3">

                        <select class="form-select" aria-label="Default select example" id="resultados-pagina">
                          <option selected value="10">10</option>
                          <option value="15">15</option>
                          <option value="25">25</option>
                          <option value="1000">Todos</option>
                        </select>
                      </div>
                    </div>


                    <button type="button" class="btn btn-success btn-icon-split  btn-acciones disabled" id="btn-inventario"> <span class="icon text-white-50">
                        <i class="fas fa-clipboard-list"></i>
                      </span>
                      <span class="text">Añadir actualización</span></button>
                  </div>
                </div>
              </div>

            </div>
          </div>
          <div class="row">


            <div class="col-lg-12 mb-4">
              <div class="w-auto card">
                <div class="card-body">

                  <table id="tablaResultados" class="display" style="width:100%">
                    <thead>
                      <tr>
                      <th></th>
                        <th class="col-1">Info</th>
                        <th>Referencia</th>
                        <th class="col-4">Nombre</th>
                        <th>Stock</th>
                        <th>Compra</th>
                        <th>Venta</th>
                        <th id="th-pvp" style="display: none">PVP</th>
                        <th>Margen</th>
                        <th>Acciones</th>
                      </tr>
                    </thead>
                    <tbody>
                    </tbody>
                  </table>
                  <nav aria-label="..." id="paginacion" class="mt-4" >
                    <ul class="pagination">
                    <!--  <li class="page-item">
                        <a class="page-link" href="#" tabindex="-1" id="prev-page">Anterior</a>
                      </li>
                      <li class="page-item"><a class="page-link" href="#">1</a></li>
                      <li class="page-item"><a class="page-link" href="#">2</a></li>
                      <li class="page-item"><a class="page-link" href="#">3</a></li>
                      <li class="page-item"><a class="page-link" href="#">4</a></li>
                      <li class="page-item"><a class="page-link" href="#">5</a></li>
                      <li class="page-item">
                        <a class="page-link" href="#" id="next-page">Siguiente</a>
                      </li>-->
                    </ul>
                  </nav>
                </div>
              </div>
            </div>
          </div>





        </div>
        <!-- /.container-fluid -->
           <!-- Modal para mostrar el resultado de la acción -->
   <div class="modal" id="resultadoModal" tabindex="-1">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title">Looking for la fiesta</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
              <ul class="list-group list-group-flush" id="listaPlataformas">
  <li class="list-group-item plataforma" data-plataforma="mage">Magento 2 <span></span></li>
  <li class="list-group-item plataforma" data-plataforma="odoo">Odoo <i class="fa-sharp fa-solid fa-question ml-3 text-warning"></i></li>
  <li class="list-group-item plataforma" data-plataforma="mage245"> Otro magento 2 súper secreto en pruebas <span></span></li>
</ul>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
              </div>
            </div>
          </div>
        </div>
        <!-- Fin del Modal para mostrar el resultado de la acción -->
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
        <!-- BOF modal crear artículo -->
        <div class="modal fade" id="modalCrearArticulo" tabindex="-1" aria-labelledby="crear-articulo-modal" aria-hidden="true">
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
                        <label for="seleccionarCategoria" class="form-label">Elige la categoría</label>

                        <select class="form-select select-articulo" aria-label="seleccionar categoria" id="seleccionarCategoria">
                          <option value="0" selected>Categoria del artículo</option>
                        </select>
                      </div>
                      <div class="mb-3">
                        <label for="seleccionarFabricante" class="form-label">Elige el fabricante</label>

                        <select class="form-select select-articulo" aria-label="seleccionar fabricante" id="seleccionarFabricante">
                          <option value="0" selected>Fabricante del artículo</option>
                        </select>
                      </div>
                      <div class="d-grid gap-2 col-8 mx-auto mt-2">
                        <a href="#" class="btn btn-success btn-icon-split disabled" id="btn-crear-articulo" data-bs-dismiss="modal">
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
      </div>

      <!-- Logout Modal-->
      <div class="modal fade" id="AccionesBasicasArticuloModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="AccionesBasicasArticuloModalTitulo"><!-- título de la ventana --></h5>
              <button class="close" type="button" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
              </button>
            </div>
            <div class="modal-body" id="AccionesBasicasArticuloModalCuerpo"><!-- Cuerpo de la ventana --></div>
            <div class="modal-footer">
              <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Cancelar</button>
              <a class="btn btn-danger" id="btn-confirmar-accion-basica" data-bs-dismiss="modal"><!--Botón para confirmar --></a>
            </div>
          </div>
        </div>
      </div>

      <!-- Logout Modal-->
      <!--  <div class="modal fade" id="ExitoModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Yeah! exitazo</h5>
            </div>
            <div class="modal-body">
              <div style='position:relative; padding-bottom:calc(56.63% + 44px)'><iframe src='https://gfycat.com/ifr/InferiorOrderlyGoosefish' frameborder='0' scrolling='no' width='100%' height='100%' style='position:absolute;top:0;left:0;' allowfullscreen></iframe></div>
              <p> <a href="https://gfycat.com/inferiororderlygoosefish">via Gfycat</a></p>
              Todo ha salido a pedir de Milhouse
            </div>
            <div class="modal-footer">
              <button class="btn btn-success" type="button" data-bs-dismiss="modal">Genial</button>
            </div>
          </div>
        </div>
      </div>-->

      <!-- Logout Modal-->
      <div class="modal fade" id="ConfirmacionModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="ConfirmacionModalTitulo"><!-- título de la ventana --></h5>
              <button class="close" type="button" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
              </button>
            </div>
            <div class="modal-body" id="ConfirmacionModalCuerpo"><!-- Cuerpo de la ventana --></div>
            <div class="modal-footer">
              <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Cancelar</button>
              <a class="btn btn-danger" id="btn-confirmar-general" data-bs-dismiss="modal"><!--Botón para confirmar --></a>
            </div>
          </div>
        </div>
      </div>
      <!-- Modal cambia precio -->
      <div class="modal" tabindex="-1" id="modal-cambiar-precio">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Cambiar precio</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <form class="row g-3">
                <div class="col-lg-8 text-center">
                  <label for="inputPassword2">Nuevo precio</label>
                  <input type="number" step="any" class="form-control my-2" id="inputCambiarPrecio" placeholder="precio" style="width: 30%;text-align: center;margin: 0 auto;">
                  <button class="btn btn-primary mb-3" id="btnCambiarPrecio" data-bs-toggle="modal" data-bs-target="#resultadoModal">Cambiar precio</button>
                </div>
                <div class="col-lg-4 text-center">
                  <label>Margen</label>
                  <p id="margenActual"></p>
                </div>
              </form>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
          </div>
        </div>
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
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

  <!--<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous"></script>-->
  <!-- Core plugin JavaScript-->
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="js/sb-admin-2.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jeditable.js/2.0.17/jquery.jeditable.min.js"></script>

  <!-- Page level plugins -->
  <!-- <script src="vendor/chart.js/Chart.min.js"></script>-->

  <!-- Page level custom scripts -->
  <!-- <script src="js/demo/chart-area-demo.js"></script>
  <script src="js/demo/chart-pie-demo.js"></script>-->

</body>

</html>
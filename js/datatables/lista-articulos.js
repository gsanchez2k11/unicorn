// Call the dataTables jQuery plugin
$(document).ready(function () {
  var sliderNuevoPrecio = document.getElementById('slider-nuevo-precio');  //El bloque donde vamos a mostrar el nuevo precio
  var nuevoImporteComision = document.getElementById('slider-importe-comision');
  var nuevoPrecioSinComision = document.getElementById('slider-precio-sin-comision');

  //var colsMargen = document.querySelectorAll('.col-margen');
  //compras
  var tabs = document.getElementById('tab-compras');
  var navs = document.getElementById('nav-compras');
  //var tablaArticulos = $('#tablaArticulos').DataTable();
  $('#tablaArticulos').DataTable({
    "processing": true,
    //  "serverSide": true,
    "ajax": { 
      'url' : 'ajax/mirakl/listar-todas-ofertas.php',
      'data' : {
        'plataforma' : plataforma
      }
  },
    //    "deferLoading": 57,
    //  'ordering': false,
    //    'scrollY':        1000,
    //  'scroller':       true,
    //  'paging' : false,
    //  "order": [[3,"desc"]],
    //  "deferLoading": 1000,
    //  'sPaginationType': 'ellipses',

    'columns': [
      {
        "data": "info",
        'render': function (data, type) {
          let custodia;
          let favColor;
          let actualizable;
          if (data.favorito == 1) {
            favColor = 'fas texto-rojo';
          } else {
            favColor = 'far text-gray-400';
          }

          if (data.custodia == 1) {
            custodia = ' <i class="fas fa-shield-alt text-info" aria-hidden="true"></i>';
          } else {
            custodia = '';
          }
          if (data.actualizable == 1) {
            actualizable = ' <i class="fas fa-arrows-rotate text-danger" aria-hidden="true"></i>';
          } else {
            actualizable = '';
          }

          let favorito = '<i class="' + favColor + ' fa-heart switch-favorito" aria-hidden="true"></i>';

          return favorito + custodia + actualizable;
        }
      },
      { "data": "shop_sku" },
      { "data": "nombre" },
      {
        "data": "stock",
        'render': function (data) {
          return data + ' uds.';
        }
      },
      {
        "data": "precio",
        'render': function (data) {
          return accounting.formatMoney(data, { symbol: "€", format: "%v %s" });
        }
      },
      {
        "data": "margen",
        'render': function (data) {
          return parseFloat(data).toFixed(2) + '%';
        }
      },


      { "data": "posicion" },
      {
        "data": null,
        "defaultContent": '<button type="button" class="btn btn-primary btn-sm abre-modal-detalle" data-bs-toggle="modal" data-bs-target="#modal-info-articulo"><i class="fa-solid fa-circle-radiation"></i></button>'
      },
      {
        "data": "info",
        'render': function (data) {
          return data.custodia;
        },
        "visible": false
      },
    ],
    'searchPanes': {
      'initCollapsed': true,
      //     "viewTotal": true,
      'panes': [
        {
          'header': 'Custodia',
          'options': [{
            'label': 'En custodia',
            'value': function (rowData, rowIdx) {
              return rowData.info.custodia == 1;
            }
          }]
        },
        {
          'header': 'Favorito',
          'options': [{
            'label': 'Favorito',
            'value': function (rowData, rowIdx) {
              return rowData.info.favorito == 1;
            }
          }]
        },
        {
          'header': 'Stock',
          'options': [{
            'label': 'Sin stock',
            'value': function (rowData, rowIdx) {
              return rowData.stock == 0;
            }
          }, {
            'label': 'Con stock',
            'value': function (rowData, rowIdx) {
              return rowData.stock > 0;
            }
          }]
        }, {
          'header': 'Margen',
          'options': [{
            'label': 'Negativo',
            'value': function (rowData, rowIdx) {
              return rowData.margen < 0;
            }
          }, {
            'label': 'Margen bajo',
            'value': function (rowData, rowIdx) {
              return rowData.margen > 0 && rowData.margen < 10;
            }
          }, {
            'label': 'Margen normal',
            'value': function (rowData, rowIdx) {
              return rowData.margen >= 10 && rowData.margen <= 20;
            }
          }, {
            'label': 'Margen excesivo',
            'value': function (rowData, rowIdx) {
              return rowData.margen > 20;
            }
          }]
        }, {
          'header': 'Inforpor',
          'options': [{
            'label': 'Existe',
            'value': function (rowData, rowIdx) {
              return rowData.enIfp == 1;
            }
          }, {
            'label': 'No encontrado',
            'value': function (rowData, rowIdx) {
              return rowData.enIfp == 0;
            }
          }]
        },
                /*{
          'header': 'Posición',
          'options': [{
            'label': 'Primeros',
            'value': function (rowData, rowIdx) {
              return rowData.posicion == 1;
            }
          },
          {
            'label': 'Top 5 no primeros',
            'value': function (rowData, rowIdx) {
              return rowData.posicion > 1 && rowData.posicion <= 5;
            }
          }]
        }*/
      ]
    },
    'dom': 'Plfrtip',
    'columnDefs': [{
      'searchPanes': {
        'show': false,
      },
      'targets': [0, 1, 2, 3, 4, 5, 6, 7]
    }],
    "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
    "language": {
      'url': 'https://cdn.datatables.net/plug-ins/1.12.1/i18n/es-ES.json',
      "zeroRecords": "No hay registros que coincidan. Pulsa enter para hacer una búsqueda general"

    },
    "initComplete": function(settings, json) {
      addPosition(json);
    }
  });

  function addPosition(json){
    var table = $('#tablaArticulos').DataTable(); //La tabla




    let d = {
      plataforma: plataforma,
   //   listado: lista
    }
    let posiciones = llamadaJson('ajax/mirakl/posiciones.php',d); //Pedimos todas las posiciones
    posiciones.forEach(a =>{
      let celda = table.cell("#entidad-"+a.entidad,6); //Seleccionamos la celda a modificar
      celda.data(a.posicion);
    });
   // let celda = fila.cell(6);
   // celda.data(69);

    console.log('json');
    console.log(posiciones);
  }

  ////////////////////////////////////////////////////////////////////////////////
  //Capturamos el evento de abrir el modal
  ////////////////////////////////////////////////////////////////////////////////
  document.getElementById('modal-info-articulo').addEventListener('show.bs.modal', function (event) {
    //let attrs = dameAttrs(event.relatedTarget.parentNode.parentNode); //Pedimos los atributos de este artículo
    var attrs = event.relatedTarget.getAttribute('data-bs-whatever') != null ? JSON.parse(event.relatedTarget.getAttribute('data-bs-whatever')) : dameAttrs(event.relatedTarget.parentNode.parentNode); //Capturamos los atributos del listado general
   // console.log('atris2');
    //console.log(attrs);
    let precioVenta = attrs.precio;
    let precioVentaSinIva = precioVenta / 1.21;
    //let porcentajeComision = attrs.comision * 1.21;
    let porcentajeComision = attrs.comision / 100;
    let importeComisionSinIva  = precioVenta * porcentajeComision;
    let importeComision = importeComisionSinIva * 1.21;

   // let importeComision = precioVenta * porcentajeComision;
    //let importeComisionSinIva = importeComision / 1.21;
   // let precioSinComision = precioVenta * (1 - (porcentajeComision / 100));  //Calculamos el importe de comisión para el precio de venta inicial
    let precioSinComision = precioVenta - importeComision;  //Calculamos el importe de comisión para el precio de venta inicial
    let precioSinComisionSinIva = precioSinComision / 1.21;

    let totalSinComision = precioVenta - importeComision;
    let posicion = attrs.posicion;
    let entidad = attrs.entidad;
    let esFavorito = attrs.favorito;
    let esActualizable = attrs.actualizable;
    let comentario = attrs.comentario;
 
    let netoSinComision = totalSinComision / 1.21;

    let ean = attrs.ean;
    let sku = attrs.shop_sku;
    //Bloques
    let divNombre = document.getElementById('nombre-articulo');
    let divStock = document.getElementById('col-stock');
    let divStockLocal = document.getElementById('stock-local');
    let divModificador = document.getElementById('stock-modificador');
    let divMargen = document.getElementById('col-margen');
    let divPrecioVenta = document.getElementById('precio-venta');
    let divPrecioVentaSinIva = document.getElementById('precio-venta-sin-iva');
    let divCatPcc = document.getElementById('cat-pcc');
    let divPorcentajeComision = document.getElementById('porcent-comision');
    let divprecioSinComision = document.getElementById('precio-sin-comision');
    let divprecioSinComisionSinIva = document.getElementById('precio-sin-comision-sin-iva');
    let divImporteComision = document.getElementById('importe-comision');
    let divImporteComisionSinIva = document.getElementById('importe-comision-sin-iva');
    let divPosicion = document.getElementById('col-posicion');
    let rowAlertaComision = document.getElementById('alerta-comision');
    let divEan = document.getElementById('ean');
    let divSku = document.getElementById('sku');

    //Reseteamos y limpiamos campos
    navs.innerHTML = ''; //Vaciamos el contenido de pestañas y contenedores
    tabs.innerHTML = '';

    /**
     * colocamos los datos
     */
    divNombre.innerHTML = attrs.nombre; //Colocamos el nombre en el bloque correspondiente
    divStock.innerHTML = attrs.stock + ' uds'; //Colocamos el stock en el bloque correspondiente
    divStockLocal.innerHTML = attrs.stock_local; //Colocamos el stock local
    divModificador.innerHTML = attrs.modificador; //Colocamos el modificador
    divMargen.innerHTML = attrs.margen + '%'; //Colocamos el margen
    divPrecioVenta.innerHTML = accounting.formatMoney(precioVenta, { symbol: "€", format: "%v %s" }); //Colocamos el precio de venta actual
    divPrecioVentaSinIva.innerHTML = accounting.formatMoney(precioVentaSinIva, { symbol: "€", format: "%v %s" }); //Colocamos el precio de venta actual sin iva
    divCatPcc.innerHTML = attrs.cat_pcc; //Colocamos el código de la categoria
    divPorcentajeComision.innerHTML = attrs.comision + '%'; //Colocamos el porcentaje de comisión
    divprecioSinComision.innerHTML = accounting.formatMoney(precioSinComision, { symbol: "€", format: "%v %s" }); //Colocamos el importe de comisión
    divprecioSinComisionSinIva.innerHTML = accounting.formatMoney(precioSinComisionSinIva, { symbol: "€", format: "%v %s" }); //Colocamos el importe de comisión restando el iva
    divImporteComision.innerHTML = accounting.formatMoney(importeComision, { symbol: "€", format: "%v %s" }); //Colocamos el importe de comisión
    divImporteComisionSinIva.innerHTML = accounting.formatMoney(importeComisionSinIva / 1.21, { symbol: "€", format: "%v %s" }); //Colocamos el importe de comisión sin iva
    divPosicion.innerHTML = attrs.posicion;
    divEan.innerHTML = ean;
    divSku.innerHTML = sku;
    document.getElementById('btn-enviar').setAttribute('attrs', JSON.stringify(attrs));
    document.getElementById('articulo-favorito').setAttribute('valor-actual', esFavorito); //Creamos un atributo para controlar si es favorito
    document.getElementById('actualizable').setAttribute('valor-actual', esActualizable); //Creamos un atributo para controlar si es favorito


    //Cambiamos el color del campo stock para destacar si es negativo
    if (attrs.stock == 0) {
      divStockLocal.classList.add('text-danger');
    } else {
      divStockLocal.classList.remove('text-danger');
    }
    //Cambiamos el color del campo margen para destacar si es negativo o muy bajo
    if (attrs.margen < 0) {
      divMargen.classList.add('text-danger');
    } else if (attrs.margen >= 0 && attrs.margen < 3) {
      divMargen.classList.add('text-warning');
    } else {
      divMargen.classList.remove('text-danger');
    }

    //Si es uno de los favoritos coloreamos el icono
    if (esFavorito == 1) {
      document.getElementById('articulo-favorito').classList.remove('fa-regular');
      document.getElementById('articulo-favorito').classList.add('fa-solid');
    }
    if (esActualizable == 1) {
      document.getElementById('actualizable').classList.add('text-danger');
      document.getElementById('actualizable').classList.remove('text-info');
    }
    refrescaInfoArticulo(attrs);
    //Si no tenemos el porcentaje de comisión mostramos el mensaje para poder añadirla
    if (porcentajeComision == 'null') {
      rowAlertaComision.style.display = 'block';
    }

    //Si tenemos un comentario mostramos el icono en rojo y lo añadimos
    if (comentario != '-') {
      document.getElementById('mostrar-comentarios').classList.toggle('text-danger');
      document.getElementById('mostrar-comentarios').classList.toggle('text-gray-200');
//Añadimos el comentario modificando el DOM 
let rowComentario = document.createElement('div');
rowComentario.classList.add('row','px-2');
document.getElementById('alerts-comentarios').appendChild(rowComentario);

let alertComentario = document.createElement('div');
alertComentario.classList.add('alert','alert-secondary','fade','show');
alertComentario.setAttribute('role','alert');
rowComentario.appendChild(alertComentario);

let txtComentario = document.createElement('span');
txtComentario.innerHTML = comentario;
alertComentario.appendChild(txtComentario);

let grupoBtnsComentario = document.createElement('div');
grupoBtnsComentario.classList.add('btn-group','float-end');
grupoBtnsComentario.setAttribute('role','group');
grupoBtnsComentario.setAttribute('aria-label','Basic-example');
alertComentario.appendChild(grupoBtnsComentario);

let btnEditar = document.createElement('button');
btnEditar.classList.add('btn','btn-secondary');
btnEditar.type = 'button';
grupoBtnsComentario.appendChild(btnEditar);
let iBtnEditar = document.createElement('i');
iBtnEditar.classList.add('fa-solid','fa-square-pen');
btnEditar.appendChild(iBtnEditar);

let btnBorrar = document.createElement('button');
btnBorrar.classList.add('btn','btn-secondary');
btnBorrar.type = 'button';
grupoBtnsComentario.appendChild(btnBorrar);
let iBtnBorrar = document.createElement('i');
iBtnBorrar.classList.add('fa-solid','fa-trash-can');
btnBorrar.appendChild(iBtnBorrar);
    }

//Capturamos el click en el stock local
    divStockLocal.addEventListener('click', function(){
 let modalModificarCantidad = document.getElementById('modificar-cantidad-modal');
 modalModificarCantidad.querySelector('h5').innerHTML = '¿Cuantas tenemos?';
 modalModificarCantidad.querySelector('.form-label').innerHTML = 'Escribe la cantidad disponible de este artículo';
let valor = attrs.stock_local == '-' ? 0 : attrs.stock_local; //Ponemos el valor actual
 modalModificarCantidad.querySelector('input').value = valor;
 document.getElementById('modifica-cantidad-btn').setAttribute('accion','stock-local');
 document.getElementById('modifica-cantidad-btn').setAttribute('entidad',entidad);
    })

  });



  ////////////////////////////////////////////////////////////////////////////////
  //Capturamos el evento de acabar de abrir el modal
  ////////////////////////////////////////////////////////////////////////////////
  document.getElementById('modal-info-articulo').addEventListener('shown.bs.modal', function (event) {
    slider = document.getElementById('slider');
    //let atributos = event.relatedTarget.parentNode.parentNode != 'undefined' ? event.relatedTarget.parentNode.parentNode : event.relatedTarget.getAttribute('data-bs-whatever');
    var attrs = event.relatedTarget.getAttribute('data-bs-whatever') != null ? JSON.parse(event.relatedTarget.getAttribute('data-bs-whatever')) : dameAttrs(event.relatedTarget.parentNode.parentNode);
    let codinfo = attrs.cod_inforpor; //Tenemos el código de inforpor o un 0

    //var attrs = dameAttrs(event.relatedTarget.parentNode.parentNode); //Pedimos los atributos de este artículo

    let precioVenta = attrs.precio;
   // let porcentajeComision = attrs.comision* 1.21;
    let porcentajeComision = attrs.comision / 100;
let importeComision = precioVenta * porcentajeComision * 1.21;
let totalSinComision = precioVenta - importeComision;

    let netoSinComision = totalSinComision / 1.21;

  //  let precioSinComision = precioVenta * (1 - (porcentajeComision / 100));  //Calculamos el importe de comisión para el precio de venta inicial
    //Buscamos en inforpor las compras
    let compraInforpor = llamadaJson("ajax/inforpor/obtener-compra.php", attrs);
    //console.log(compraInforpor);
    if (compraInforpor.normal_inforpor.CodErr == 'Producto vacio') {
      console.log('Este artículo no se puede comprar en inforpor');
    }

    let stockInforpor = dameDatosStockInforpor(compraInforpor);
    document.getElementById('stock-normal-inforpor').innerHTML = stockInforpor.normalInforpor;
    document.getElementById('stock-reserva-inforpor').innerHTML = stockInforpor.reservaInforpor;
    document.getElementById('stock-custodia-inforpor').innerHTML = stockInforpor.custodiaInforpor;
    document.getElementById('stock-total-inforpor').innerHTML = stockInforpor.totalInforpor;

    //Generamos el gráfico con el historial de stock
    //generaGraficoStock(attrs);

    //Procesamos para tener los  distintos precios de inforpor
    let preciosCompra = dameDatosCompraInforpor(compraInforpor);
    //Si hay compras las colocamos
    if (Object.keys(preciosCompra).length > 0) { //Si tenemos compras las recorremos y mostramos
      for (i in Object.keys(preciosCompra)) {
        let estaCompra = preciosCompra[Object.keys(preciosCompra)[i]];
        let precioConPortes = estaCompra.precio + estaCompra.portes;
        let precioSinIva = precioConPortes + estaCompra.lpi;
        let precio = precioSinIva * 1.21;
        let margen = ((netoSinComision / precioConPortes) - 1) *100;
    //    let margen = (((totalSinComision - estaCompra.lpi) / precio) - 1) * 100;

        //Creamos el árbol
        //Navegación
        //Añadimos el elemento de la lista
        let li = document.createElement('li'); //Creamos el elemento
        li.classList.add('nav-item'); //Añadimos la clase nav item
        li.setAttribute('role', 'presentation'); //Añadimos el atributo role
        navs.appendChild(li); //Lo añadimos a la navegación
        //Creamos el enlace que vamos a utilizar de botón
        let a = document.createElement('button');
        a.classList.add('nav-link');
        a.setAttribute('data-bs-toggle', 'tab');
        a.setAttribute('data-bs-target', '#tab' + i);
        a.setAttribute('role', 'tab');
        a.innerHTML = Object.keys(preciosCompra)[i];
        li.appendChild(a);
        //Contenido de las pestañas
        //
        let div = document.createElement('div');
        div.classList.add('tab-pane', 'fade');
        div.setAttribute('role', 'tabpanel');
        if (i == '0') {
          div.classList.add('active', 'show');
        }
        div.id = 'tab' + i;
        tabs.appendChild(div);
        //
        var ul = document.createElement('ul');
        ul.classList.add('list-group');
        div.appendChild(ul);
        //
        let liMargen = document.createElement('li');
        liMargen.classList.add('list-group-item');
        ul.appendChild(liMargen);
        //
        let liPrecio = document.createElement('li');
        liPrecio.classList.add('list-group-item');
        ul.appendChild(liPrecio);
        //
        let rowMargen = document.createElement('div');
        rowMargen.classList.add('row');
        liMargen.appendChild(rowMargen);
        //
        let colTxtMargen = document.createElement('div');
        colTxtMargen.classList.add('col-9', 'col-margen');
        colTxtMargen.innerHTML = 'Margen';
        rowMargen.appendChild(colTxtMargen);
        //Porcentaje de margen actual
        let colDatosMargen = document.createElement('div');
        colDatosMargen.classList.add('col-3', 'text-end');
        colDatosMargen.innerHTML = margen.toFixed(2) + '%';
        rowMargen.appendChild(colDatosMargen);
        //Añadimos una columna para el slider
        var colFuturoMargen = document.createElement('div');
        colFuturoMargen.classList.add('col-3', 'text-end', 'col-futuro-margen');
        colFuturoMargen.id = 'pr-margen-' + i;
        //colFuturoMargen.innerHTML = margen.toFixed(2) + '%';
        colFuturoMargen.style.display = 'none';
        rowMargen.appendChild(colFuturoMargen);

        //
        let rowPrecio = document.createElement('div');
        rowPrecio.classList.add('row');
        liPrecio.appendChild(rowPrecio);
        //
        let colTxtPrecio = document.createElement('div');
        colTxtPrecio.classList.add('col-9');
        colTxtPrecio.innerHTML = 'Precio de compra';
        rowPrecio.appendChild(colTxtPrecio);
        //
        let colDatosPrecio = document.createElement('div');
        colDatosPrecio.classList.add('col-3', 'text-end', 'col-precio');
        rowPrecio.appendChild(colDatosPrecio);
        //
        let aColDatosPrecio = document.createElement('a');
        aColDatosPrecio.setAttribute('data-bs-toggle', 'collapse');
        aColDatosPrecio.setAttribute('href', '#collapseExample');
        aColDatosPrecio.setAttribute('role', 'button');
        aColDatosPrecio.setAttribute('aria-expanded', 'false');
        aColDatosPrecio.setAttribute('aria-controls', 'collapseExample');
        colDatosPrecio.appendChild(aColDatosPrecio);
        //
        let spanPrecioCon = document.createElement('span');
        spanPrecioCon.classList.add('con-iva');
        spanPrecioCon.innerHTML = accounting.formatMoney(precio, { symbol: "€", format: "%v %s" });
        aColDatosPrecio.appendChild(spanPrecioCon);
        //
        let spanPrecioSin = document.createElement('span');
        spanPrecioSin.classList.add('sin-iva');
        spanPrecioSin.style.display = 'none';
        spanPrecioSin.innerHTML = accounting.formatMoney(precioSinIva, { symbol: "€", format: "%v %s" });
        aColDatosPrecio.appendChild(spanPrecioSin);

        //
        let collapse = document.createElement('div');
        collapse.classList.add('collapse');
        collapse.id = 'collapseExample';
        rowPrecio.appendChild(collapse);
        //
        let collapseCard = document.createElement('div');
        collapseCard.classList.add('card', 'card-body', 'text-end', 'text-dark', 'bg-light', 'mt-3', 'border-0', 'fs-6');
        collapse.appendChild(collapseCard);
        //
        let collPrecio = document.createElement('div');
        collPrecio.classList.add('row');
        collapseCard.appendChild(collPrecio);
        //
        let collPrecioIzq = document.createElement('div');
        collPrecioIzq.classList.add('col-9');
        collPrecioIzq.innerHTML = 'Precio artículo';
        collPrecio.appendChild(collPrecioIzq);
        //
        let collPrecioDcha = document.createElement('div');
        collPrecioDcha.classList.add('col-3');
        collPrecio.appendChild(collPrecioDcha);
        //
        let spanCollPDchaCon = document.createElement('span');
        spanCollPDchaCon.classList.add('con-iva');
        spanCollPDchaCon.innerHTML = accounting.formatMoney(preciosCompra[Object.keys(preciosCompra)[i]].precio * 1.21, { symbol: "€", format: "%v %s" });
        collPrecioDcha.appendChild(spanCollPDchaCon);
        //
        let spanCollPDchaSin = document.createElement('span');
        spanCollPDchaSin.classList.add('sin-iva');
        spanCollPDchaSin.style.display = 'none';
        spanCollPDchaSin.innerHTML = accounting.formatMoney(preciosCompra[Object.keys(preciosCompra)[i]].precio, { symbol: "€", format: "%v %s" });
        collPrecioDcha.appendChild(spanCollPDchaSin);

        //
        let collLpi = document.createElement('div');
        collLpi.classList.add('row');
        collapseCard.appendChild(collLpi);
        //
        let collLpiIzq = document.createElement('div');
        collLpiIzq.classList.add('col-9');
        collLpiIzq.innerHTML = 'LPI';
        collPrecio.appendChild(collLpiIzq);
        //
        let collLpiDcha = document.createElement('div');
        collLpiDcha.classList.add('col-3');
        collPrecio.appendChild(collLpiDcha);
        //
        let spanCollLpiDcha = document.createElement('span');
        spanCollLpiDcha.classList.add('con-iva');
        spanCollLpiDcha.innerHTML = accounting.formatMoney(preciosCompra[Object.keys(preciosCompra)[i]].lpi * 1.21, { symbol: "€", format: "%v %s" });
        collLpiDcha.appendChild(spanCollLpiDcha);
        //
        let spanCollLpiDchaSin = document.createElement('span');
        spanCollLpiDchaSin.classList.add('sin-iva');
        spanCollLpiDchaSin.style.display = 'none';
        spanCollLpiDchaSin.innerHTML = accounting.formatMoney(preciosCompra[Object.keys(preciosCompra)[i]].lpi, { symbol: "€", format: "%v %s" });
        collLpiDcha.appendChild(spanCollLpiDchaSin);
        //
        let collPortes = document.createElement('div');
        collPortes.classList.add('row');
        collapseCard.appendChild(collPortes);
        //
        let collPortesIzq = document.createElement('div');
        collPortesIzq.classList.add('col-9');
        collPortesIzq.innerHTML = 'Portes';
        collPrecio.appendChild(collPortesIzq);
        //
        let collPortesDcha = document.createElement('div');
        collPortesDcha.classList.add('col-3');
        collPrecio.appendChild(collPortesDcha);
        //
        let spanCollPortesDcha = document.createElement('span');
        spanCollPortesDcha.classList.add('con-iva');
        spanCollPortesDcha.innerHTML = accounting.formatMoney(preciosCompra[Object.keys(preciosCompra)[i]].portes * 1.21, { symbol: "€", format: "%v %s" });
        collPortesDcha.appendChild(spanCollPortesDcha);
        //
        let spanCollPortesDchaSin = document.createElement('span');
        spanCollPortesDchaSin.classList.add('sin-iva');
        spanCollPortesDchaSin.style.display = 'none';
        spanCollPortesDchaSin.innerHTML = accounting.formatMoney(preciosCompra[Object.keys(preciosCompra)[i]].portes, { symbol: "€", format: "%v %s" });
        collPortesDcha.appendChild(spanCollPortesDchaSin);

        console.log(estaCompra);
      }
    } else {
      //Aquí vamos a mostrar un mensaje si no hubiera compras
    }
    if (Object.keys(preciosCompra).length > 1) {  //Si tenemos más de una compra mostramos la barra de navegación
      navs.style.display = 'flex';
      ul.classList.add('list-group', 'mt-2');
    } else {
      navs.style.display = 'none';
    }

    //Generamos el gráfico con el historial de precio
    //generaGraficoPrecio(attrs);

    /**
     * Capturamos el click en el botón de cambiar precio
     * @type {[type]}
     */
    var btnCambiarPrecio = document.getElementById('btn-cambiar-precio');
    btnCambiarPrecio.addEventListener('click', function (e) {
      //  btnCambiarPrecio.classList.add('disabled');
      btnCambiarPrecio.style.display = 'none';
      document.getElementById('btn-enviar').style.display = 'block';
      //  sliderNuevoPrecio.innerHTML = accounting.formatMoney(attrs.precio, { symbol: "€",  format: "%v %s" }); //Ponemos como precio inicial el precio actual
      sliderNuevoPrecio.style.display = 'block'; //Le cambiamos la visibilidad para que aparezca
      nuevoImporteComision.style.display = 'block';
      nuevoPrecioSinComision.style.display = 'block';
      document.querySelectorAll('.col-futuro-margen').forEach((i) => {
        i.style.display = 'block';
      });

      document.getElementById('col-comision').classList.remove('col-9');
      document.getElementById('col-comision').classList.add('col-6');
      document.querySelectorAll('.col-margen').forEach((i) => {
        i.classList.remove('col-9');
        i.classList.add('col-6');
      });
      document.querySelectorAll('.con-iva').forEach((i) => {
        i.classList.add('text-gray-400');
      });



      //Definimos los datos iniciales
      var start = attrs.precio;
      var min = start / 1.20;
      var max = start * 1.20;


      if (typeof slider.noUiSlider !== 'undefined') { //Si ya hemos creado un slider lo destruimos para crear el nuevo
        slider.noUiSlider.destroy();
      }

    
      noUiSlider.create(slider, {
        start: [start],
        connect: true,
        step: 0.05,
        range: {
          'min': min,
          'max': max
        }
      });

      slider.noUiSlider.on('update', function (values, handle) {
        /*let precioVenta = attrs.precio;
        let precioVentaSinIva = precioVenta / 1.21;
        //let porcentajeComision = attrs.comision * 1.21;
        let porcentajeComision = attrs.comision / 100;
        let importeComision = precioVenta * porcentajeComision;
        let importeComisionSinIva = importeComision / 1.21;
       // let precioSinComision = precioVenta * (1 - (porcentajeComision / 100));  //Calculamos el importe de comisión para el precio de venta inicial
        let precioSinComision = precioVenta - importeComision;  //Calculamos el importe de comisión para el precio de venta inicial
        let precioSinComisionSinIva = precioSinComision / 1.21;
    
        let totalSinComision = precioVenta - importeComision;
        let posicion = attrs.posicion;
        let entidad = attrs.entidad;
        let esFavorito = attrs.favorito;
        let esActualizable = attrs.actualizable;
        let comentario = attrs.comentario;*/
    
        
        let porcentajeComision = attrs.comision / 100;

        let nuevoPrecio = values[handle];
        let importeComisionNueva = nuevoPrecio * porcentajeComision * 1.21;
        let importeNuevoPrecioSinComision = nuevoPrecio - importeComisionNueva;

        sliderNuevoPrecio.innerHTML = accounting.formatMoney(nuevoPrecio, { symbol: "€", format: "%v %s" }); //Fijamos el nuevo precio que vamos a aplicar
        document.getElementById('btn-enviar').setAttribute('nuevoprecio', nuevoPrecio);

        nuevoPrecioSinComision.innerHTML = accounting.formatMoney(importeNuevoPrecioSinComision, { symbol: "€", format: "%v %s" });
        nuevoImporteComision.innerHTML = accounting.formatMoney(importeComisionNueva, { symbol: "€", format: "%v %s" });

        let arrMargenes = []; //Creamos un array para todos los márgenes posibles del artículo
        //Recorremos las compras para colocar a cada una su margen correspondiente
        for (i in Object.keys(preciosCompra)) {
          let prM = document.getElementById('pr-margen-' + i);
          let precio = (preciosCompra[Object.keys(preciosCompra)[i]].precio + preciosCompra[Object.keys(preciosCompra)[i]].lpi + preciosCompra[Object.keys(preciosCompra)[i]].portes) * 1.21;
          let nuevoMargen = ((importeNuevoPrecioSinComision / precio) - 1) * 100;
          prM.innerHTML = nuevoMargen.toFixed(2) + '%';
          arrMargenes.push(nuevoMargen); //Añadimos el margen al array de  márgenes
        }

        document.getElementById('btn-enviar').setAttribute('nuevomargen', Math.max(arrMargenes)); //Nos quedamos con el valor más alto del array de márgenes y lo añadimos como atributo al botón

        let mejorPrecio = document.querySelector('.mejor-oferta').getAttribute('precio');
        let ofertasFutura = document.querySelectorAll('.of-futura'); //Nos quedamos con las lineas del listado de oferta que sean de futura
        let listaOfertas = document.getElementById('lista-ofertas');
        ofertasFutura.forEach(i => { //Las recorremos
          i.querySelector('.of-precio').innerHTML = accounting.formatMoney(nuevoPrecio, { symbol: "€", format: "%v %s" }); //Ponemos el precio nuevo para que veamos como queda
          let ofertaAnterior = i.previousSibling;
          let ofertaPosterior = i.nextSibling;
          if (ofertaAnterior.classList == undefined) { //Si somos primeros intentamos coger el segundo precio como referencia
            mejorPrecio = ofertaPosterior.getAttribute('precio');
          }
          let simbolo = nuevoPrecio - mejorPrecio > 0 ? '+' : '';
          i.querySelector('.of-dif').innerHTML = simbolo + accounting.formatMoney(nuevoPrecio - mejorPrecio, { symbol: "€", format: "%v %s" }); //Ponemos el precio nuevo para que veamos como queda

          if (ofertaPosterior.classList != undefined && nuevoPrecio > ofertaPosterior.getAttribute('precio')) {
            i.parentNode.insertBefore(i, listaOfertas);
          }
          if (ofertaAnterior.classList != undefined && nuevoPrecio > ofertaAnterior.getAttribute('precio')) {
            console.log('hemos superado al anterior');
          }


          //let precioAnterior = ofertaAnterior.querySelector('.of-precio');
          //let precioPosterior = ofertaPosterior.querySelector('.of-precio');
          //console.log(ofertaAnterior.classList);
          //  console.log(ofertaPosterior.classList);
        })

      })

    });

    //Capturamos el click en el icono de favorito del modal
    document.getElementById('articulo-favorito').addEventListener('click', function (e) {
      let entidad = attrs.entidad;
      let valorActual = e.target.getAttribute('valor-actual');
      let nuevoValor = switchAtributo(entidad, valorActual, 12)
      document.getElementById('articulo-favorito').setAttribute('valor-actual', nuevoValor);
      document.getElementById('articulo-favorito').classList.toggle('fa-regular');
      document.getElementById('articulo-favorito').classList.toggle('fa-solid');
      console.log(attrs);
    })

    //Capturamos el click en el icono de favorito del modal
    document.getElementById('actualizable').addEventListener('click', function (e) {
      let entidad = attrs.entidad;
      let valorActual = e.target.getAttribute('valor-actual');
      let nuevoValor = switchAtributo(entidad, valorActual, 9)
      document.getElementById('actualizable').setAttribute('valor-actual', nuevoValor);
      //if (valorActual == 1) { //Si el valor actual es 1 al hacer click lo que hacemos es quitarle la actualizacion
      document.getElementById('actualizable').classList.toggle('text-danger');
      document.getElementById('actualizable').classList.toggle('text-info');
      //}
      console.log(attrs);
    })
    //Capturamos el click en el icono de eliminar oferta del modal
    document.getElementById('btn-eliminar-oferta').addEventListener('click', function (e) {
      url = 'ajax/mirakl/actualizar-oferta.php';
    /*  let offer_additional_fields = [
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
      };*/

      let datos = {
        //      product_id :  mpn,
        //      product_id_type : 'MPN',
        product_id: attrs.ean,
        product_id_type: 'EAN',
        shop_sku: attrs.shop_sku,
        //shop_sku : 'S10281',
        quantity: attrs.stock,
        state_code: 11,
        price: attrs.precio,
        update_delete: 'delete',
       // offer_additional_fields: offer_additional_fields,
        logistic_class: 'medio'
      };

     // datos['hora-corte'] = '18:00';
     let accion = 'eliminar';
      var actualizarOfertas = actualizaOferta(datos, url, plataforma,accion);
      let resultado = actualizarOfertas[plataforma];

      //confirmacion-modal
      /*let modalConfirmacion = new bootstrap.Modal(document.getElementById('confirmacion-modal')); 
      modalConfirmacion.hide();*/
      document.getElementById('txt-resultado').innerHTML = resultado;
      //myModal.options()
      modalConfirmaEliminar.show();
      //console.log('actualizarOfertas');
      //console.log(myModal);
    })

let mostrarComentarios = document.getElementById('mostrar-comentarios');
mostrarComentarios.addEventListener('click', function(){
  document.getElementById('bloque-comentarios').classList.toggle('visually-hidden');
});

  });//Fin evento abrir modal detalle

  //Evento al cerrar el modal
  document.getElementById('modal-info-articulo').addEventListener('hide.bs.modal', function (event) {

    if (typeof slider.noUiSlider !== 'undefined') { //Si ya hemos creado un slider lo destruimos para crear el nuevo
      slider.noUiSlider.destroy();
    }
    sliderNuevoPrecio.innerHTML = '';
    sliderNuevoPrecio.style.display = 'none';
    nuevoImporteComision.innerHTML = '';
    nuevoImporteComision.style.display = 'none';
    nuevoPrecioSinComision.innerHTML = '';
    nuevoPrecioSinComision.style.display = 'none';

    let colsFuturoMargen = document.querySelectorAll('col-futuro-margen');
    document.querySelectorAll('.col-margen').forEach((i) => {
      i.innerHTML = '';
      i.style.display = 'none';
    });

    document.getElementById('col-comision').classList.add('col-9');
    document.getElementById('col-comision').classList.remove('col-6');
    document.querySelectorAll('.col-margen').forEach((i) => {
      i.classList.add('col-9');
      i.classList.remove('col-6');
    });

    document.getElementById('btn-cambiar-precio').style.display = 'block';
    document.getElementById('btn-enviar').style.display = 'none';

    document.querySelectorAll('.con-iva').forEach((i) => {
      i.classList.remove('text-gray-400');
    });

    //Limpiamos la lista de precios
    let listaOfertas = document.getElementById('lista-ofertas');
    //console.log('listaOfertas');
    //console.log(listaOfertas);
    listaOfertas.querySelectorAll('.oferta').forEach((i) => {
      i.remove();
    });

    //Reiniciamos el icono de los favoritos
    document.getElementById('articulo-favorito').classList.add('fa-regular');
    document.getElementById('articulo-favorito').classList.remove('fa-solid');
    //Reiniciamos el icono de actualizable
    document.getElementById('actualizable').classList.remove('text-danger');
    document.getElementById('actualizable').classList.add('text-info');

    //Ocultamosla alerta de varias ofertas
    document.getElementById('alerta-varias-ofertas').style.display = 'none';

    //Volvemos a poner en gris el icono de mostrar comentarios
    document.getElementById('mostrar-comentarios').classList.remove('text-danger');
    document.getElementById('mostrar-comentarios').classList.add('text-gray-200');

  let graficos = document.querySelectorAll('.chartjs-size-monitor');
  graficos.forEach((i) => i.remove());
  
  //Limpiamos los canvas 
let canvasGraficos = document.querySelectorAll('canvas');
canvasGraficos.forEach((i) => i.removeAttribute('data'));

  })


  //Evento al abrir el modal de exito
  document.getElementById('exito-actualizacion').addEventListener('show.bs.modal', function (e) {
    let attrs = e.relatedTarget.getAttribute('attrs');
    document.getElementById('volver-modal-info').setAttribute('data-bs-whatever', attrs);
    let atributos = JSON.parse(attrs);
    let nPrecio = e.relatedTarget.getAttribute('nuevoprecio');
    let nMargen = e.relatedTarget.getAttribute('nuevomargen');
    let precio = JSON.parse(nPrecio);
    let margen = isNaN(nMargen) ? '' : JSON.parse(nMargen); //Si hemos recibido el margen lo capturamos si no lo dejaomos en blanco
    let shop_sku = atributos.shop_sku;
    let mpn = atributos.shop_sku;
    let ean = atributos.ean;
    let stock = atributos.stock;
    let entidad = atributos.entidad;
    let claseLogistica = atributos.clase_logistica;
    //let plataforma = 'pcc';
console.log('ssss');
console.log(atributos);
console.log(attrs);
    //Actualizamos primero la base de datos
    actualizaCampo(entidad, 'stock', stock);
    //actualizaCampo(entidad, 13, stock);
    actualizaCampo(entidad, 'precio', precio);
 //   actualizaCampo(entidad, 14, precio);
    if (margen != '') { //El margen es posible que no lo tengamos
     // actualizaCampo(entidad, 20, margen);
      actualizaCampo(entidad, 'margen', margen);
    }

    let url;                                                                     //url del archivo para la llamada ajax
    let datos;
    switch (plataforma) {
      case 'pcc':
        url = 'ajax/mirakl/actualizar-oferta.php';
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
    /*    let logistic_class = {                                                          //De momento ponemos de manera automática la clase logistica
          "code": 'medio',
          "label": 'Medio - Entre 2 y 10 kg'
        };*/
        //let shop_sku = rangoSlider.ofertaProductSku != null ? rangoSlider.ofertaProductSku :  rangoSlider.oferta;


        datos = {
          //      product_id :  mpn,
          //      product_id_type : 'MPN',
          product_id: ean,
          product_id_type: 'EAN',
          shop_sku: shop_sku,
          //shop_sku : 'S10281',
          quantity: stock,
          state_code: 11,
          price: precio.toFixed(2),
          update_delete: 'update',
          offer_additional_fields: offer_additional_fields,
          logistic_class: claseLogistica
        };

        datos['hora-corte'] = '18:00';
        console.log('datos');
        console.log(datos);

        break;
        case 'mediamarkt':
          datos = {
            //      product_id :  mpn,
            //      product_id_type : 'MPN',
            product_id: ean,
            product_id_type: 'EAN',
            shop_sku: shop_sku,
            //shop_sku : 'S10281',
            quantity: stock,
            state_code: 11,
            price: precio.toFixed(2),
            update_delete: 'update',
         //   offer_additional_fields: offer_additional_fields,
            logistic_class: claseLogistica
          };
          url = 'ajax/mirakl/actualizar-oferta.php';
          break;
      case 'fnac':
        datos = {
          mpn: mpn,
          stock_final: stock,
          precio: precio.toFixed(2),
        };
        url = 'ajax/fnac/actualizar-oferta.php';
        break;
    }
    var actualizarOfertas = actualizaOferta(datos, url, plataforma);
   // coloreaFila(shop_sku);
    //console.log(actualizaPrecio);
    //console.log(atributos);
    //console.log(nuevoPrecio);


  });



});


/**
 * FUNCIONES
 */

function coloreaFila(shop_sku, accion = 'actualizar'){
    //Vamos a colorear la fila correspondiente
    let tablaArts = document.getElementById('tablaArticulos'); //Selecionamos la tabla con los artículos
    tablaArts.querySelectorAll('tr').forEach(i => { //Seleccionamos las filas que tenemos en pantalla y recorremos el array de la consulta
      let iShop_sku = i.getAttribute('shop_sku');
      if (iShop_sku == shop_sku) { //Comprobamos si coinciden los shop_skus
        let txt;
        let fondo;
        switch (accion) {
          case 'eliminar':
            txt = 'text-white';
            fondo = 'bg-danger';
            break;
        
          default:
            txt = 'text-white';
            fondo = 'bg-info';
            break;
        }
        i.classList.add(fondo, txt);
      }
      //  console.log(i);
    });
}

function dameAttrs(tr) {
  let attrsNames = tr.attributes; //Capturamos los nombres de los atributos recibidos
  var attrs = {};
  for (var attr of attrsNames) { //Creamos un objeto con los valores recibidos
    attrs[attr.name] = attr.value;
  }
  return attrs;
}
function dameDatosStockInforpor(compraInforpor) {
  let obj = {};
  //Configuramos el stock de compra normal de inforpor
  if (compraInforpor.normal_inforpor.Stock != " ") { //Comprobamos si recibimos la info del artículo
    obj.normalInforpor = parseInt(compraInforpor.normal_inforpor.Stock) > 0 ? parseInt(compraInforpor.normal_inforpor.Stock) - 1 : 0; //Descontamos uno siempre que haya stock
  } else {
    obj.normalInforpor = '-';
  }
  //Configuramos el stock de reserva en inforpor
  obj.reservaInforpor = parseInt(compraInforpor.reserva_inforpor);
  //Y ahora las custodias
  obj.custodiaInforpor = 0;
  if (compraInforpor.custodias != false) {
    for (var cust of compraInforpor.custodias) {
      obj.custodiaInforpor += parseInt(cust.quedan);
    }
  }
  //Y la suma total
  obj.totalInforpor = obj.normalInforpor == '-' ? obj.reservaInforpor + obj.custodiaInforpor : obj.reservaInforpor + obj.custodiaInforpor + obj.normalInforpor;

  return obj;
}
function generaGraficoStock(attrs) {
  console.log('grafico');
  console.log(attrs);
  var ctx = document.getElementById("myAreaChartStock");
  let datos = {
    entidad: attrs.entidad,
    cod_atributo: 13
  }
  let historico = llamadaJson('ajax/unicorn_db/buscar-historico-pcc.php', datos);
  ctx.setAttribute('data', JSON.stringify(historico));

let labels = Object.keys(JSON.parse(ctx.getAttribute('data')));
let data = JSON.parse(ctx.getAttribute('data'));
console.log(data);

  var myLineChart = new Chart(ctx, {
    type: 'line',
    data: {
      labels: labels,
      datasets: [{
        label: "Stock",
       /* lineTension: 0.3,
        backgroundColor: "rgba(78, 115, 223, 0.05)",
        borderColor: "rgba(78, 115, 223, 1)",
        pointRadius: 3,
        pointBackgroundColor: "rgba(78, 115, 223, 1)",
        pointBorderColor: "rgba(78, 115, 223, 1)",
        pointHoverRadius: 3,
        pointHoverBackgroundColor: "rgba(78, 115, 223, 1)",
        pointHoverBorderColor: "rgba(78, 115, 223, 1)",
        pointHitRadius: 10,
        pointBorderWidth: 2,*/
        data: data,

      }],
    },
    options: {
    /*  maintainAspectRatio: false,
      layout: {
        padding: {
          left: 10,
          right: 25,
          top: 25,
          bottom: 0
        }
      },*/
scales: {
  x: {
    type: 'time',
    display: false,
    time: {
      unit: 'day'
  }
  },
  y: {
    display: true,
  },
  adapters: {
    date: {
        locale: 'es-ES'
    }
}
},
      /*scales: {
        xAxes: [{
          time: {
            unit: 'date'
          },
          gridLines: {
            display: false,
            drawBorder: false
          },
          ticks: {
            maxTicksLimit: 7
          },
          display: false
        }],
        yAxes: [{
          ticks: {
            maxTicksLimit: 5,
            padding: 10,
            min: 0
            // Include a dollar sign in the ticks
            //  callback: function(value, index, values) {
            //return '$' + number_format(value);
          //}
          //},
          gridLines: {
            color: "rgb(234, 236, 244)",
            zeroLineColor: "rgb(234, 236, 244)",
            drawBorder: false,
            borderDash: [2],
            zeroLineBorderDash: [2]
          }
        }],
      },*/
      legend: {
        display: false
      },
      tooltips: {
        backgroundColor: "rgb(255,255,255)",
        bodyFontColor: "#858796",
        titleMarginBottom: 10,
        titleFontColor: '#6e707e',
        titleFontSize: 14,
        borderColor: '#dddfeb',
        borderWidth: 1,
        xPadding: 15,
        yPadding: 15,
        displayColors: false,
        intersect: false,
        mode: 'index',
        caretPadding: 10,
        callbacks: {
          title: function (tooltipItem, chart) {
            var datasetLabel = chart.datasets[tooltipItem[0].datasetIndex].label || '';
            return datasetLabel + ' : ' + tooltipItem[0].yLabel.toFixed(2) + ' uds.';
            //  console.log(tooltipItem);
          },
          label: function (tooltipItem, chart) {
            let fecha = chart.datasets[tooltipItem.datasetIndex].data[tooltipItem.index]['x'];
            let fObj = new Date(fecha);
            let dia = fObj.getDate();
            let mes = parseInt(fObj.getMonth()) + 1;
            let anyo = fObj.getFullYear();
            let hora = fObj.getHours();
            let minuto = fObj.getMinutes();
            //  console.log(fObj);
            return dia + '-' + mes + '-' + anyo + '  ' + hora + ':' + minuto;
          }
        }
      }
    }
  });
}
function dameDatosCompraInforpor(compraInforpor) {
  //Buscamos ahora los posibles precios de compra
  let lpi = compraInforpor.normal_inforpor.lpi; //Inforpor nos da el precio normal sin incluir el lpi, pero el de custodia lo lleva incluido
  preciosCompra = { //Creamos un objeto y añadimos el precio normal de compra
    inforporNormal: {
      precio: compraInforpor.normal_inforpor.Precio,
      lpi: compraInforpor.normal_inforpor.lpi,
      portes: compraInforpor.normal_inforpor.Precio < 60 ? 3.20 : 0
    }
  };
  if (compraInforpor.custodias !== false) { //Si tenemos custodias añadimos una propiedad para cada una con su número de pedido y su precio
    for (var cust of compraInforpor.custodias) {
      preciosCompra['pedido ' + cust.pedido] = {
        precio: (cust.precio - lpi),
        lpi: lpi,
        portes: (cust.precio - lpi) < 60 ? 3.20 : 0
      }
    }
  }
  return preciosCompra;
}
function generaGraficoPrecio(attrs) {
  var ctxP = document.getElementById("myAreaChartPrecio");
  let datosPrecio = {
    entidad: attrs.entidad,
    cod_atributo: 14
  }
  let historicoP = llamadaJson('ajax/unicorn_db/buscar-historico-pcc.php', datosPrecio);
  ctxP.setAttribute('data', JSON.stringify(historicoP));
  var myLineChart = new Chart(ctxP, {
    type: 'line',
    data: {
      labels: Object.keys(JSON.parse(ctxP.getAttribute('data'))),
      datasets: [{
        label: "Precio",
        lineTension: 0.3,
        backgroundColor: "rgba(78, 115, 223, 0.05)",
        borderColor: "rgba(78, 115, 223, 1)",
        pointRadius: 3,
        pointBackgroundColor: "rgba(78, 115, 223, 1)",
        pointBorderColor: "rgba(78, 115, 223, 1)",
        pointHoverRadius: 3,
        pointHoverBackgroundColor: "rgba(78, 115, 223, 1)",
        pointHoverBorderColor: "rgba(78, 115, 223, 1)",
        pointHitRadius: 10,
        pointBorderWidth: 2,
        data: JSON.parse(ctxP.getAttribute('data')),

      }],
    },
    options: {
      maintainAspectRatio: false,
      layout: {
        padding: {
          left: 10,
          right: 25,
          top: 25,
          bottom: 0
        }
      },

      scales: {
        xAxes: [{
          time: {
            unit: 'date'
          },
          gridLines: {
            display: false,
            drawBorder: false
          },
          ticks: {
            maxTicksLimit: 7
          },
          display: false
        }],
        yAxes: [{
          ticks: {
            maxTicksLimit: 5,
            padding: 10,
            min: 0
            // Include a dollar sign in the ticks
            /*  callback: function(value, index, values) {
            return '$' + number_format(value);
          }*/
          },
          gridLines: {
            color: "rgb(234, 236, 244)",
            zeroLineColor: "rgb(234, 236, 244)",
            drawBorder: false,
            borderDash: [2],
            zeroLineBorderDash: [2]
          }
        }],
      },
      legend: {
        display: false
      },
      tooltips: {
        backgroundColor: "rgb(255,255,255)",
        bodyFontColor: "#858796",
        titleMarginBottom: 10,
        titleFontColor: '#6e707e',
        titleFontSize: 14,
        borderColor: '#dddfeb',
        borderWidth: 1,
        xPadding: 15,
        yPadding: 15,
        displayColors: false,
        intersect: false,
        mode: 'index',
        caretPadding: 10,
        callbacks: {
          title: function (tooltipItem, chart) {
            var datasetLabel = chart.datasets[tooltipItem[0].datasetIndex].label || '';
            return datasetLabel + ' : ' + tooltipItem[0].yLabel.toFixed(2) + ' €';
            //  console.log(tooltipItem);
          },
          label: function (tooltipItem, chart) {
            let fecha = chart.datasets[tooltipItem.datasetIndex].data[tooltipItem.index]['x'];
            let fObj = new Date(fecha);
            let dia = fObj.getDate();
            let mes = parseInt(fObj.getMonth()) + 1;
            let anyo = fObj.getFullYear();
            let hora = fObj.getHours();
            let minuto = fObj.getMinutes();
            //  console.log(fObj);
            return dia + '-' + mes + '-' + anyo + '  ' + hora + ':' + minuto;
          }
        }
      }
    }
  });
}
function refrescaInfoArticulo(attrs) {
  //Tenemos acceso a la variable attrs con los atributos del artículo
  //console.log(plataforma);
  //console.log('datosRecibidos');
  let dat = { 
    product_sku: attrs.sku_plataforma,
    plataforma: plataforma
   };
  let datosRecibidos = llamadaJson("ajax/mirakl/buscar-articulo.php", dat); //Buscamos el artículo en pc componentes
  if (datosRecibidos.products.length > 0) { //Si estamos recibiendo datos los procesamos
    let miArt = datosRecibidos.products[0]; //Nos quedamos con la primera entrada del array(por definición sólo deberiamos recibir un artículo)
    var ofertas = miArt.offers.filter(of => of.state_code == '11');
    //Filtramos para eliminar reacondicionados
//    console.log('datosRecibidos');
  //  console.log(ofertas);
  //  console.log(ofNuevas);
    let nombreRecibido = miArt.product_title;
    let categoria = miArt.category_code;
    let mpnRecibido = '';
    let eanRecibido = '';

    for (ref of miArt.product_references) {
      switch (ref.reference_type) {
        case 'MPN':
          mpnRecibido = ref.reference;
          break;
        case 'EAN':
          eanRecibido = ref.reference;
          break;
      }
    }
    //Generamos el enlace a la web de la plataforma
    let linkPlataforma = generaUrl(plataforma, nombreRecibido);
    //Lo asignamos al botón
    document.getElementById('btn-link-pcc').href = linkPlataforma;
   // console.log(linkPcc);


    //Colocamos los campos
    document.getElementById('nombre-articulo').innerHTML = nombreRecibido; //Colocamos el nombre en el bloque correspondiente
    document.getElementById('cat-pcc').innerHTML = categoria; //Colocamos el código de la categoria
    //Grabamos en la base de datos
    if (plataforma == 'pcc') { //El nombre solo lo actualizamos si es el de pc componentes, que está mejor formado
      actualizaCampo(attrs.entidad, 4, nombreRecibido); //Actualizamos el nombre 
    }
//    actualizaCampo(attrs.entidad, 17, categoria); //Actualizamos la categoria. Ahora se hace al cargar el listado de productos
    if (mpnRecibido != '') {
      actualizaCampo(attrs.entidad, 2, mpnRecibido); //Actualizamos el mpn
    }
    if (eanRecibido != '') {
    //  actualizaCampo(attrs.entidad, 3, eanRecibido); //Actualizamos el mpn
      actualizaCampo(attrs.entidad, 3, eanRecibido); //Actualizamos el mpn
    }




    let mejorPrecio = 0;
    let nOfertasFutura = 0; //Vamos a contar el número de ofertas de Futura para controlar los duplicados
    //Recorremos ahora el array y vamos colocando los datos
    for (i in ofertas) {

      //Añadimos una fila para cada oferta
      let row = document.createElement('div');
      row.classList.add('row', 'p-1', 'fs-6', 'py-2', 'oferta');
      document.getElementById('lista-ofertas').appendChild(row);
      row.setAttribute('precio', ofertas[i].total_price);
      if (i == 0) {
        mejorPrecio = ofertas[i].total_price;
        row.classList.add('mejor-oferta');

      }


      //Columna con la posición
      let colPosicion = document.createElement('div');
      colPosicion.classList.add('col-1');
      colPosicion.innerHTML = parseInt(i) + 1;
      row.appendChild(colPosicion);
      //Columna con el nombre
      let colNombre = document.createElement('div');
      colNombre.classList.add('col-3');
      colNombre.innerHTML = ofertas[i].shop_name;
      row.appendChild(colNombre);
      //Columna con el rating
      let colRating = document.createElement('div');
      colRating.classList.add('col-1');
      colRating.innerHTML = ofertas[i].shop_grade;
      row.appendChild(colRating);
      //Columna con el envío
      let colEnvio = document.createElement('div');
      colEnvio.classList.add('col-2');
      colEnvio.innerHTML = ofertas[i].min_shipping_type;
      row.appendChild(colEnvio);
      //Columna con los portes
      let colPortes = document.createElement('div');
      colPortes.classList.add('col-1', 'text-center');
      colPortes.innerHTML = ofertas[i].min_shipping_price;
      row.appendChild(colPortes);
      //Columna con el precio
      let colPrecio = document.createElement('div');
      colPrecio.classList.add('col-2', 'text-center', 'of-precio');
      colPrecio.innerHTML = accounting.formatMoney(ofertas[i].total_price, { symbol: "€", format: "%v %s" });
      row.appendChild(colPrecio);
      //Columna con la diferencia
      let colDiferencia = document.createElement('div');
      colDiferencia.classList.add('col', 'of-dif');
      row.appendChild(colDiferencia);

      if (ofertas[i].shop_name == 'Futura Teck') {
        ++nOfertasFutura;
        if (nOfertasFutura > 1) { //Si hay más de una oferta colocamos el error
          document.getElementById('alerta-varias-ofertas').style.display = 'block';
        }
        //row p-1 fs-2 py-2 bg-info text-white
        row.classList.remove('fs-6');
        row.classList.add('fs-4', 'bg-info', 'text-white', 'of-futura');

        if (i == 0) {
          mejorPrecio = ofertas[1].total_price; //Si tenemos el mejor precio cogemos como referencia el del segundo
        }
        let simbolo = i > 0 ? '+' : '';
        colDiferencia.innerHTML = simbolo + accounting.formatMoney(ofertas[i].total_price - mejorPrecio, { symbol: "€", format: "%v %s" });

        document.getElementById('col-posicion').innerHTML = parseInt(i) + 1; //Actualizamos la cabecera con la posición actual
        //Tambien la grabamos en la base de datos
        //actualizaCampo(attrs.entidad, 21, parseInt(i) + 1); //Actualizamos el nombre
        actualizaCampo(attrs.entidad, 'posicion', parseInt(i) + 1); //Actualizamos el nombre

        if (i > 10) {
          break;
        }

      }

    }

    //console.log(ofFutura);

  }



  console.log(datosRecibidos);
  console.log(attrs);
}

function dameNuestroprecio(ofertas) {
  //Vamos con las posiciones
  //filtramos para buscar la nuestra, así tendremos el precio, aunque no la posicion
  let ofFutura = ofertas.filter(of => of.shop_name == 'Futura Teck');
  let precioFutura = 0;
  switch (true) {
    case (ofFutura.length === 0):
      console.log('No tenemos oferta para este artículo');
      break;
    case (ofFutura.length === 1):
      precioFutura = ofFutura[0].total_price;
      console.log('Vamos a colocar los datos');
      break;
    case (ofFutura.length > 1):
      precioFutura = ofFutura[0].total_price;
      document.getElementById('alerta-varias-ofertas').style.display = 'block';
      break;

  }
}

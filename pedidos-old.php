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
   <script src="js/pedidos.js"></script>

   <?php
   include 'src/loader.php';                 //Incluimos el loader principal
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
        <?php
        /*
        * Comprobamos si NO estamos recibiendo la variable referencia mediante método GET
        * en ese caso mostramos la caja de búsqueda grande
        */
        if (filter_input(INPUT_GET, "pl", FILTER_SANITIZE_FULL_SPECIAL_CHARS)): ?>
        <?php
          $plataforma = filter_input(INPUT_GET, "pl", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
         ?>
         <script>
$('.loader').show();
var           plataforma = '<?= $plataforma ?>';

/******************************************************************************/
/*Eventos
/******************************************************************************/
$(document).ready(function(){
  var btnCrearArticulo = document.getElementById('btn-crear-articulo');   //Boton para crear artículo
//cargamos los pedidos
ultimosPedidos = dameUltimosPedidos(plataforma).then(pedidos => {
  const modalPedido = document.getElementById('modal-pedido');
  creatabla(pedidos); //Creamos la tabla con los datos (datatables/pedidos.js)
  modalPedido.addEventListener('show.bs.modal', event => { //CApturamos la acción de abrir el modal
   let pedido = colocaDatosModal(pedidos,event).then(ped => {
    comprobaciones(ped); //Comprobamos la integrida de los datos
    return ped;
   }).then(pd => {
   // buscaEnOdooWorker(pd); //Buscamos en odoo pedidos, y en caso de no encontarlos, el cliente y los artículos
   console.log('pd');
   console.log(pd);
   buscaPedidosOdooWorker(pd);
   }).then(  nPed => {
  //  console.log(nPed);
/*console.log(pd);
    let refInternaPedido = refPedidoClienteInforpor(pd.id,plataforma);                             //Creamos la referencia del pedido en inforpor

datos = {NumPedCli : refInternaPedido};                                        //Preparamos los datos que vamos a Buscar
let existeInforpor = llamadaJson('ajax/inforpor/buscar-pedido.php',datos);      //Buscamos en inforpor
console.log('existeInforpor');
console.log(existeInforpor);*/

  });

})
modalPedido.addEventListener('hide.bs.modal', event => { //CApturamos la acción de esconder el modal
     limpiaDatosModal(); 
})
});

//Modal de comprobar cliente
var comprobarModal = document.getElementById('comprueba-cliente-modal')
comprobarModal.addEventListener('show.bs.modal', function (event) {             //Evento
var objPedido =  crearClienteP(event);
})
//Al cerrar la ventana modal la reseteamos la estado original
comprobarModal.addEventListener('hide.bs.modal', function (event) {
  $('.comprueba-cliente-cuerpo').html('<ul class="list-group"><li class="list-group-item list-group-item-info" id="check-nif">NIF / CIF </li><li class="list-group-item " id="check-phone">Teléfono</li><li class="list-group-item " id="check-name">Nombre</li></ul><div id="resultado-check"></div>').hide();
})


//Modal de comprobar artículo
var crearArticuloModal = document.getElementById('crear-articulo-modal')
crearArticuloModal.addEventListener('show.bs.modal', function (event) {
  // Button that triggered the modal
  var button = event.relatedTarget
  // Extract info from data-bs-* attributes
  var esteArticulo = button.getAttribute('data-bs-whatever')
  var objArticulo = JSON.parse(esteArticulo);                                       //Convertimos la cadena en un objeto
  //Categorias
  let misCats = filtrarCategorias(); //Recuperamos el listado de categorias
  let idCatBuscada = adivinaCategoria(objArticulo.cod_categoria); //Intentamos adivinar la categoría del artículo
  misCats.forEach((cat,i) => { //Recorremos el listado de categorias
  let option = document.createElement("option"); //Creamos un elemento option
option.text = cat.complete_name; //Asignamos como texto el nombre completo de la categoria
option.value = cat.id;          //Asignamos como valor la id
if (cat.id === idCatBuscada) { //Si la categoria buscada coincide la seleccionamos
  option.selected = true;
}
listaCatsSelect.add(option);    //Añadimos la opcion creada a la lista
});

let listaFabricantes = listarMarcas();
let splitNombre = objArticulo.nombre.split(" ");
let nombreFab = splitNombre[0];

listaFabricantes.forEach((fabricante,i) => { //Recorremos el listado de categorias
    let option = document.createElement("option"); //Creamos un elemento option
    option.text = fabricante; //Asignamos como texto el nombre completo de la categoria
    option.value = fabricante;          //Asignamos como valor la id
    if (nombreFab == fabricante) {
      option.selected = true;
    }
    listaFabsSelect.add(option);
});
let selects = Object.values(document.getElementsByClassName('select-articulo')); //Los desplegables
activaBoton(selects); //Al abrir el modal ejecutamos la funcion una vez para habilitar o no el botón
selects.forEach((item, i) => {                                                  //El evento que escucha cualquier cambio
  item.addEventListener('change', function(event){
    activaBoton(selects)
  });
});

//Buscamos el artículo en inforpor
let precioIfp = 0;
if ( objArticulo.atributos_bd[5] !== undefined) {
let codInfo = objArticulo.atributos_bd[5]; //Cogemos el codigo de inforpor
datos = {
  codinfo: codInfo
}
let buscaArtIfp = llamadaJson('ajax/inforpor/buscar-articulo.php',datos);            //Buscamos el artículo en odoo
precioIfp = buscaArtIfp.CodErr == '0' ? buscaArtIfp.Precio : 0;
}
$('.crear-articulo-loader').hide();

btnCrearArticulo.addEventListener('click', function(event) { //CApturamos el click en crear artículo
  event.preventDefault;
    let datos = {
      modelo : 'product.product',
      arr : {
    categ_id : listaCatsSelect.value, //Categoria, la cogemos del select
    name : objArticulo.nombre,       //Nombre del artículo
    default_code : objArticulo.mpn,   //referencia
    type : 'product', //Tipo de producto almacenable
    x_studio_fabricante : listaFabsSelect.value
  }};
  if (objArticulo.atributos_bd[3] !== 'undefined') {
    datos.arr.barcode = objArticulo.atributos_bd[3];
  }
  //console.log('datos para crear');
  //console.log(datos);
  let crearArticulo = EntradaOdoo(datos,'crear'); //Creamos el artículo

  if (crearArticulo.hasOwnProperty('id')) {
    datos = {
      campo_busqueda : 'default_code',
      valor_antiguo : objArticulo.mpn,
      campo_actualizar: 'list_price',
      valor_nuevo : (objArticulo.importe - objArticulo.impuestos) / objArticulo.cantidad,
      modelo : 'product.template'
    }
      let actualizarArticulo = EntradaOdoo(datos,'actualizar'); //Creamos el artículo

if (actualizarArticulo === 'ok') {
  datos.campo_actualizar = 'standard_price';
  datos.valor_nuevo = precioIfp;
        actualizarArticulo = EntradaOdoo(datos,'actualizar'); //Creamos el artículo
        if (actualizarArticulo === 'ok') {
          crearArticuloModal.hide();
        }
}
  }
});

});

//Modal de crear pedidos en odoo
var crearSolicitudCompraModal = document.getElementById('crear-solicitud-compra-modal')
crearSolicitudCompraModal.addEventListener('show.bs.modal', function (event) {
  // Button that triggered the modal
  var button = event.relatedTarget
  // Extract info from data-bs-* attributes
  var estepedido = button.getAttribute('data-bs-whatever')
  var objEstePedido = JSON.parse(estepedido);                                       //Convertimos la cadena en un objeto
  //console.log(objEstePedido);
  var objpedidoCompra = objEstePedido['pedido_compra'];
  var objcliente = objEstePedido['cliente'];
  var objpedidoVenta = objEstePedido['pedido_venta'];

  $('#nombre-cliente-modal').html(objcliente.name); //Ponemos el nombre del cliente
  $('#vat-modal').html(objcliente.vat); //Ponemos el DNI / CIF

   //Pedimos los distintos tipos de pedido 
   let datosTipoPedido = {modelo : 'sale.order.type'};                                        //Preparamos los datos que vamos a Buscar
let TipoPedido = llamadaJson('ajax/odoo/listar.php',datosTipoPedido);      //Obtenemos el listado de tipos de pedido
TipoPedido.forEach(function(tipo, i){
let opcion = document.createElement('option');
opcion.innerHTML = tipo.name; //Colocamos el nombre
opcion.value = tipo.id; //Colocamos la id como valor
document.getElementById('elige-tipo-pedido').appendChild(opcion);
});
adivinaTipoPedido(objpedidoVenta,plataforma);

var lineasPed;
if (objpedidoCompra !== undefined && objpedidoCompra.hasOwnProperty('CodErr') && objpedidoCompra.CodErr == '0') {                                          //Si recibimos pedido de inforpor
  $('#referencia-inforpor-modal').html('Pedido número: ' + objpedidoCompra.numero);   //Ponemos el número de pedido de inforpor
  $('#referencia-cliente-modal').html('Referencia interna: ' + objpedidoCompra.numpedCli);  //Ponemos la referencia interna
  //Vamos a poner los artículos
lineasPed = objpedidoCompra.lineasPedR;
for (p in lineasPed) {
$('#articulos-crear-compra-modal').append('<div class="row"><div class="col-11">'+lineasPed[p].cant+' x '+lineasPed[p].atributos_bd[4]+'('+lineasPed[p].atributos_bd[2]+')</div><div class="col-1">'+lineasPed[p].precio+'€</div></div>');
}
} else {                                                                        //Si no recibimos el pedido de compra obtenemos las lineas de la venta directamente
lineasVenta = objpedidoVenta.lineas_pedido;
for (p in lineasVenta) {
  $('#articulos-crear-compra-modal').append('<div class="row"><div class="col-11">'+lineasVenta[p].cantidad+' x '+lineasVenta[p].mpn+'('+lineasVenta[p].nombre+')</div><div class="col-1"></div></div>');
}
}
objEstePedido['articulosVenta'] = generaArticulosVenta(objpedidoVenta,);

document.getElementById('btn-armageddon').addEventListener('click', function(e){
  console.log('objEstePedido');
  console.log(objEstePedido);
  armageddon(objEstePedido);  
});


});





///////////////////////////////////////////




//Capturamos el click en buscar pedido (aún sin uso)
$('#buscar-pedido').on('click', () => {
let terminoBusqueda = $('#termino-busqueda').val();
if (terminoBusqueda.length >= 3) {
} else {
$('.toast').toast('show');
}
});

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//     CLICK EN LAS ACCIONES RÁPIDAS DE PEDIDO                                //
////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////
$('#dataTable tbody').on('click', '.btn-info.btn-circle', (event) => {
existeArt = 0;
artsVenta = [];
let idFila = gestionaFila(event);                                             //Gestionamos si hay que crear o destruir la fila posterior
if (idFila !== '') {                                                        //Si recibimos true procedemos a todo lo demás

let estePedido = $(listapds).filter((index) => {                                //Filtramos y nos quedamos con este pedido en concreto
return listapds[index].id == idFila;
});
let pedActual = estePedido[0];
//console.log('pedActual');
//console.log(pedActual);

let refInternaPedido = refPedidoClienteInforpor(pedActual.id,plataforma);                             //Creamos la referencia del pedido en inforpor

datos = {NumPedCli : refInternaPedido};                                        //Preparamos los datos que vamos a Buscar
let existeInforpor = llamadaJson('ajax/inforpor/buscar-pedido.php',datos);      //Buscamos en inforpor
//console.log('existeInforpor');
//console.log(existeInforpor);
let fichaCliente = dameFichaCliente(pedActual);
chkCliente = typeof fichaCliente === 'object' ? true : false;
let tipoVat = validarNifCif(pedActual.nif);                                     //Comprobamos el tipo de documento
let tipoNombre = dameTipoNombre(pedActual.nombre_apellidos);
/*console.log('tipoNombre');
console.log(tipoNombre);*/

let chkInforpor = existeInforpor.CodErr == '0' ? true : false;                  //Checkeamos si existe el pedido en inforpor
//chkCliente = checkCliente(pedActual);                                       //Función que checkea si el cliente existe
//console.log('chkCliente');
//console.log(fichaCliente);
let artsVenta = generaArticulosVenta(pedActual,existeInforpor);                 //Generamos los artículos "enviables" para pasarlo posteriormente a  crear-presupuesto-venta.php
//console.log('artsVenta');
//console.log(artsVenta);

let chkArticulos = artsVenta.length === pedActual.lineas_pedido.length ? true : false; //Comprobamos todos los articulos , si hay el mismo número es que todos están creados ya

//Lo buscamos en odoo
let presupuestosOdoo = buscaPresupuestosOdoo(pedActual.id,plataforma);
//console.log('presupuestosOdoo');
//console.log(presupuestosOdoo);
/*datos =  {valor : refInternaPedido};
let compraOdoo = llamadaJson('ajax/odoo/buscar-solicitud-compra.php',datos);*/
//console.log('compraOdoo');
//console.log(compraOdoo);

let datosGlobales = {
  cliente : fichaCliente,
  pedido_compra : existeInforpor,
  pedido_venta : estePedido[0],
  articulosVenta : artsVenta,
  plataforma :plataforma
};
colocaTarjetasCliente(idFila, pedActual,chkCliente);                                       //Colocamos las tarjetas con la info de envio y facturacion y el botón de comprobar
colocaArticulos(idFila, pedActual,chkArticulos);                                             //Colocamos los artículos del pedido
colocaFilaInforporOdoo(idFila,refInternaPedido,datosGlobales,chkCliente,chkArticulos,presupuestosOdoo);

}

});
//};
var listaCatsSelect = document.getElementById('seleccionar-categoria'); //Select de categorias
var listaFabsSelect = document.getElementById('seleccionar-fabricante'); //Select de categorias



//Modal de comprobar artículo
/*var crearArticuloModal = document.getElementById('crear-articulo-modal')
crearArticuloModal.addEventListener('show.bs.modal', function (event) {
  // Button that triggered the modal
  var button = event.relatedTarget
  // Extract info from data-bs-* attributes
  var esteArticulo = button.getAttribute('data-bs-whatever')
  var objArticulo = JSON.parse(esteArticulo);                                       //Convertimos la cadena en un objeto
let datos = {
  modelo : 'product.category'
}
  let listaCategorias = llamadaJson('ajax/odoo/listar.php',datos);  //Recuperamos el listado de categorias
//  console.log(listaCategorias);
  function filtrarCategorias(listaCategorias){
    let categorias = listaCategorias.filter(cat => cat.child_id.length === 0);  //Filtramos para dejar sólo aquellas categorias que no tienen hijos
    return categorias;
  }
  let misCats = filtrarCategorias(listaCategorias);
  misCats.sort((a,b) => (a.complete_name > b.complete_name) ? 1 : ((b.complete_name > a.complete_name) ? -1 : 0)); //Ordenamos las categorias por el nombre completo


  //Vamos a intentar adivinar la categoria
  let codCat = objArticulo.cod_categoria;
  let catBuscada;
  let idCatBuscada;
  switch (codCat) {
    case "14YLGY": //Tintas
    idCatBuscada = 169;
      break;
      case "B31ZNA": //Impresoras
      idCatBuscada = 155;
        break;
        case "CUMPG3": //Teclado
        case "QM5839": //Fundas para tablet
        idCatBuscada = 284;
          break;
  }

misCats.forEach((cat,i) => { //Recorremos el listado de categorias
  let option = document.createElement("option"); //Creamos un elemento option
option.text = cat.complete_name; //Asignamos como texto el nombre completo de la categoria
option.value = cat.id;          //Asignamos como valor la id
if (cat.id === idCatBuscada) { //Si la categoria buscada coincide la seleccionamos
  option.selected = true;
  //btn-crear-articulo
//  btnCrearArticulo.classList.remove('disabled'); //activamos el botón
}
listaCatsSelect.add(option);    //Añadimos la opcion creada a la lista
});

datos = {
  modelo : 'ir.model.fields',
  campo : 'id',
  valor : 8352
}
let llamadaListaFabricantes = llamadaJson('ajax/odoo/busqueda.php',datos); //Pedimos el listado de fabricantes en odoo
let listaFabricantesTexts = llamadaListaFabricantes[0].selection;
//let listaFabricantesIds = llamadaListaFabricantes[0].selection_ids;
let lFab = [];
//listaFabricantesTexts = "[('Aisens', 'Aisens'), ('Agfa', 'Agfa'), ('Beinsen', 'Beinsen'), ('Brother', 'Brother'), ('Canon', 'Canon'), ('Chemica', 'Chemica'), ('DigiStar', 'DigiStar'), ('EFI', 'EFI'), ('Epson', 'Epson'), ('Felix Schoeller', 'Felix Schoeller'), ('HP', 'HP'), ('Gcc', 'Gcc'), ('Genérico', 'Genérico'), ('Graphtec', 'Graphtec'), ('Herranz', 'Herranz'), ('Keencut', 'Keencut'), ('Kyocera', 'Kyocera'), ('Lexmark', 'Lexmark'), ('LG', 'LG'), ('Logitech', 'Logitech'), ('Mactac', 'Mactac'), ('Manoukian', 'Manoukian'), ('Mutoh', 'Mutoh'), ('Nanjing Getwin industial Co.,Ltd', 'Nanjing Getwin industial Co.,Ltd'), ('OKI', 'OKI'), ('Ovili', 'Ovili'), ('PNY', 'PNY'), ('Plastgrommet', 'Plastgrommet'), ('Poli-Tape', 'Poli-Tape'), ('Ricoh', 'Ricoh'), ('Ritrama', 'Ritrama'), ('Roland', 'Roland'), ('Sappi', 'Sappi'), ('S-Race', 'S-Race'), ('Samsung', 'Samsung'), ('Siser', 'Siser'), ('Sihl', 'Sihl'), ('SkateFlash', 'SkateFlash'), ('Talius', 'Talius'), ('Xerox', 'Xerox'), ('X-Rite', 'X-Rite')]";
let arrFab = listaFabricantesTexts.split(/\'/); //Creamos un array
let filtrado = arrFab.filter(a => /^[a-zA-Z]/.test(a) === true); //Dejamos solo los nombres
let listaFabricantes = [...new Set(filtrado)]; //Usamos set para crear una instancia sin duplicados


//console.log(listaFabricantesIds);
//let listaFabricantes = JSON.parse(llamadaListaFabricantes[0].selection);
//En Pc componentes los nombres comienzan por el fabricante, vamos a intentar adivinarlo
let splitNombre = objArticulo.nombre.split(" ");
let nombreFab = splitNombre[0];

listaFabricantes.forEach((fabricante,i) => { //Recorremos el listado de categorias
    let option = document.createElement("option"); //Creamos un elemento option
    option.text = fabricante; //Asignamos como texto el nombre completo de la categoria
    option.value = fabricante;          //Asignamos como valor la id
    if (nombreFab == fabricante) {
      option.selected = true;
    }
    listaFabsSelect.add(option);
});
function activaBoton(selects){
  let resultado = selects.map(este => este.value);
  let inactivo = resultado.includes("0");
  if (inactivo === false) {
    btnCrearArticulo.classList.remove('disabled'); //activamos el botón
  } else {
    btnCrearArticulo.classList.add('disabled'); //desactivamos el botón
  }

}

let selects = Object.values(document.getElementsByClassName('select-articulo')); //Los desplegables
activaBoton(selects); //Al abrir el modal ejecutamos la funcion una vez para habilitar o no el botón
selects.forEach((item, i) => {                                                  //El evento que escucha cualquier cambio
  item.addEventListener('change', function(event){
    activaBoton(selects)
  });
});

//Buscamos el artículo en inforpor
let codInfo = objArticulo.atributos_bd[5]; //Cogemos el codigo de inforpor
datos = {
  codinfo: codInfo
}
let buscaArtIfp = llamadaJson('ajax/inforpor/buscar-articulo.php',datos);            //Buscamos el artículo en odoo
let precioIfp = buscaArtIfp.CodErr == '0' ? buscaArtIfp.Precio : 0;
console.log(precioIfp);

//console.log(objArticulo);
  //Quitamos el loader
  $('.crear-articulo-loader').hide();
//Buscamos el articulo en odoo
//Comentamos esta parte porque ahora comprobamos si el artículo existe antes de abrir el modal, por lo que sólo se muestra si no existe

btnCrearArticulo.addEventListener('click', function(event) { //CApturamos el click en crear artículo
  event.preventDefault;
    let datos = {
      modelo : 'product.product',
      arr : {
    categ_id : listaCatsSelect.value, //Categoria, la cogemos del select
    name : objArticulo.nombre,       //Nombre del artículo
    default_code : objArticulo.mpn,   //referencia
    type : 'product', //Tipo de producto almacenable
    x_studio_fabricante : listaFabsSelect.value
  }};
  if (objArticulo.atributos_bd[3] !== 'undefined') {
    datos.arr.barcode = objArticulo.atributos_bd[3];
  }
  //console.log('datos para crear');
  //console.log(datos);
  let crearArticulo = EntradaOdoo(datos,'crear'); //Creamos el artículo

  if (crearArticulo.hasOwnProperty('id')) {
    datos = {
      campo_busqueda : 'default_code',
      valor_antiguo : objArticulo.mpn,
      campo_actualizar: 'list_price',
      valor_nuevo : (objArticulo.importe - objArticulo.impuestos) / objArticulo.cantidad,
      modelo : 'product.template'
    }
      let actualizarArticulo = EntradaOdoo(datos,'actualizar'); //Creamos el artículo
      console.log('actualizarArticulo');
console.log(actualizarArticulo);
if (actualizarArticulo === 'ok') {
  datos.campo_actualizar = 'standard_price';
  datos.valor_nuevo = precioIfp;
        actualizarArticulo = EntradaOdoo(datos,'actualizar'); //Creamos el artículo
        if (actualizarArticulo === 'ok') {
          crearArticuloModal.hide();
        }
}
  }
});
})*/
crearArticuloModal.addEventListener('hide.bs.modal', function (event) {     //Al cerrar volvemos al estado inicial
  console.log('emoserrado el modal');
                                     //Convertimos la cadena en un objeto
 $('#crear-articulo-modal .modal-body .card img').attr('src','img/2273.jpg');
 $('#crear-articulo-modal .modal-body .card h5').html('No hemos encontrado el artículo.');
 $('#crear-articulo-modal .modal-body .card p').html('Eso no quiere decir que no exista, sólo que con estos datos no aparece nada. Echa un vistazo e intenta completar los datos con el EAN o cualquier otro campo que pueda resultar de utilidad');
})



crearSolicitudCompraModal.addEventListener('hide.bs.modal', function (event) {
                                     //Convertimos la cadena en un objeto
  $('#referencia-inforpor-modal').empty();   //Ponemos el número de pedido de inforpor
  $('#referencia-cliente-modal').empty();  //Ponemos la referencia interna
  $('#articulos-crear-compra-modal').empty();
  $('#nombre-cliente-modal').empty();
  $('#vat-modal').empty();
  $('#btn-armageddon').removeAttr('disabled');
  $('#crear-solicitar-compra').html('Solicitud de compra');
  $('#crear-presupuesta-venta').html('Presupuesto de venta');
  $('#list-group-comprobacion').hide();
})








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
          <div class="alert alert-info alert-dismissible fade show" role="alert">
            <img src="img/unicornio-celebrando.png" alt="" style="width: 64px">
            Por fin, lo que <strong>todos estabais esperando</strong>.
            <ul>
              <li>Funcionando con la versión nueva.</li>
              <li>Elige el tipo de pedido al crearlo</li>
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
          <div class="d-sm-flex align-items-center justify-content-between mb-4">

              <h1 class="h3 mb-0 text-gray-800">Pedidos de <span id="plataforma-pedidos"></span></h1>
          <!--<a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                      class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>-->

                      <ul class="nav" id="menu-plataforma">
                        <li class="nav-item"><img src="img/origami.png" alt="" style="width: 48px"> Cabalgar a los pedidos de: </li>
                        <li class="nav-item">
                          <a class="nav-link active" aria-current="page" href="#" data-pl="mage"><img src="https://icons-for-free.com/iconfiles/png/512/development+logo+magento+icon-1320184807335224584.png" style="width: 32px"> Magento</a>
                        </li>
                        <li class="nav-item">
                          <a class="nav-link" href="#" data-pl="pcc"><img src="https://pbs.twimg.com/profile_images/832152319236112384/rwG5mZ78.jpg" style="width: 32px">Pc componentes</a>
                        </li>
                      </ul>
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
<!-- BOF MODAL CON INFO PEDIDO -->
<div class="modal" id="modal-pedido" tabindex="-1">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Pedido xxxxx (plataforma)</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      <div class="row" >
        <h5 class="mb-2 my-2"><span class="material-icons fs-2 align-text-bottom mr-2">
        shopping_bag
</span>Pedido </h5>
<div class="col-3      ">
<span class="material-icons align-bottom">
numbers
</span> 
<span class="dato-pedido" id="ref-pedido"><!-- referencia del pedido --></span>
</div>
<div class="col-3">
<span class="material-icons align-bottom">
calendar_month
</span>
<span class="dato-pedido" id="fecha-pedido"><!-- fecha del pedido --></span>
</div>
<div class="col-3      ">
<span class="material-icons align-bottom">
donut_large
</span>
<span class="dato-pedido" id="estado-pedido"><!-- estado del pedido --></span>
</div>
<div class="col-3 ">
  <span class="badge datos-pedido" id="tienda-pedido"><!--etiquetas --> </span>
</div>
        </div>
   <div class="row">
   <h5 class="mb-2 my-2 pr-4"><span class="material-icons fs-2 align-text-bottom mr-2">
account_circle
</span>Cliente <span class="material-icons float-end fs-2" id="chkCliente">
help_outline
</span></h5>

<div class="row">
<div class="col-md-6" id="columna-factura">
    <div class="card">
    <div class="card-body">
        <h6 class="card-title">      <span class="material-icons float-end">
account_balance_wallet
</span> Facturar</h6>
        <h6 class="card-text fw-bold datos-pedido" id="nombre-factura-pedido"> <!-- Nombre para facturar --> </h6>
        <div class="card-text datos-pedido" id="telefono-factura-pedido"><!-- Teléfono de facturacion --></div>
        <div class="card-text datos-pedido" id="nif-factura-pedido"><!-- NIF para facturacion --></div>
        <div class="card-text datos-pedido" id="direccion-factura-pedido"><!-- direccion de factura--></div>
        <div class="card-text datos-pedido" id="cp-poblacion-factura-pedido"><!-- Código postal, población y provincia de facturación --></div>
      </div>
    </div>
    
  </div>
  <div class="col-md-6" id="columna-envio">
    <div class="card">
    <div class="card-body">
        <h6 class="card-title">      <span class="material-icons float-end">
        local_shipping
</span> Enviar</h6>
<h6 class="card-text fw-bold datos-pedido" id="nombre-envio-pedido"> <!-- Nombre para envio --> </h6>
        <div class="card-text datos-pedido" id="telefono-envio-pedido"><!-- Teléfono de envio --></div>
        <div class="card-text datos-pedido" id="direccion-envio-pedido"><!-- direccion de envio--></div>
        <div class="card-text datos-pedido" id="cp-poblacion-envio-pedido"><!-- Código postal, población y provincia de envio --></div>
      </div>
    </div>
    
  </div>
</div>
   </div>
   <div class="row">
   <h5 class="mb-2 my-2 pr-4"><i class="fa-solid fa-boxes-stacked mr-2"></i>Artículos <span class="material-icons float-end fs-2" id="totalArticulos" data-total="0" data-tenemos="0">
   help_outline
</span></h5>

<div class="row">
<div class="alert alert-info visually-hidden" role="alert">
  A simple info alert with <a href="#" class="alert-link">an example link</a>. Give it a click if you like.
</div>
  <div class="table-responsive">
    <table class="table table-sm">
      <tbody id="tablaArticulos">
<!-- Aquí ponemos las líneas con los artículos -->
      </tbody>
    </table>
  </div>
 
  
</div>
   </div>
   <div class="row">
   <h5 class="mb-2 my-2 pr-4"><span class="material-icons fs-2 align-text-bottom mr-2">
account_circle
</span>Compras y facturación</h5>
<div class="row">
<div class="card-group">
<div class="card mb-3" style="max-width: 540px;">
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
    <div class="col-md-7 text-center p-4 " id="no-hay-tracking"><img class="w-auto card-img-top" src="img/unicornio-angustia.png" alt="unicornio con angustia"><div class="card-body"><p class="card-text">No tenemos información de este pedido.</p></div></div>
  </div>
</div>
<div class="card mb-3" style="max-width: 540px;">
  <div class="row g-0">
    <div class="col-md-4 text-center" style="top:30%">
    <img src="https://www.odoo.com/web/image/website/1/social_default_image?unique=f75b8fa" alt="logo odoo" style="width:120px">
    </div>
    <div class="col-md-8">
    <div class="card-body"><div class="card" style="width: 18rem;">
  <ul class="list-group list-group-flush">
  <li class="list-group-item ">
<div class="row justify-content-center align-items-center g-2">
  <div class="col"><i class="fa-solid fa-file-export fa-2x text-gray-500"></i></div>
  <div class="col" id="ped-venta-odoo">No existe</div>
</div>  
  </li>
  <li class="list-group-item ">
<div class="row justify-content-center align-items-center g-2">
  <div class="col"><i class="fa-solid fa-file-import fa-2x text-gray-500"></i></div>
  <div class="col" id="ped-compra-odoo">No existe</div>
</div>  
  </li>
  <li class="list-group-item visually-hidden" id="mostrar-btn-sonreir">
  <div class="d-grid gap-2 ">
  <button type="button" class="btn btn-info btn-lg" data-bs-toggle="modal" data-bs-target="#crear-solicitud-compra-modal">:) sonreir</button>
  </div>  
</li>
  </ul>
</div></div>
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

<!-- BOF modal crear artículo -->

      <div class="modal fade" id="crear-solicitud-compra-modal" tabindex="-1" aria-labelledby="crear-solicitud-compra-modal" aria-hidden="true">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="crear-solicitud-compra-modal-label"><img src="img/unicornio-dinero.png" alt="">Comprando unicornios de repuesto</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
<div class="container-fluid">
  <!-- Inicio Bloque cliente-->
  <div class="row"><div class="card mb-3">
  <div class="row g-0 align-items-center">
    <div class="col-md-4">
      <img src="img/unicorn_profile.jpg" alt="perfil" style="width: 230px">
    </div>
    <div class="col-md-8">
      <div class="card-body">
        <h5 class="card-title" id="nombre-cliente-modal"><!--Nombre del cliente --></h5>
        <p class="card-text">
          <div id="vat-modal">
          </div>
        </p>

      </div>
    </div>
  </div>
</div></div>
<!-- Fin Bloque cliente-->
<!-- Inicio Bloque Pedido-->
<div class="row"><div class="card mb-3">
<div class="row g-0 align-items-center">
<div class="col-md-8">
<div class="row mb-2">
<label for="elige-tipo-pedido" class="form-label">Tipo de pedido</label>
<select class="form-select form-select-sm" aria-label=".form-select-sm example" id="elige-tipo-pedido"> 
</select>
</div>
    </div>
    <div class="col-md-4">
      <img src="https://thumbs.dreamstime.com/b/money-origami-unicorn-folded-real-one-dollar-bill-isolated-white-background-214705144.jpg" alt="perfil" style="width: 230px">
    </div>
  </div>

</div></div>
<!-- Fin Bloque pedido-->
<!-- Inicio Bloque Articulos-->
<div class="row"><div class="card mb-3">
<div class="row g-0 align-items-center">
<div class="col-md-2">
      <img src="https://files.pitchbook.com/website/images/ar/featured/b/2x/Canva_-_Creative_Unicorn_Pattern_on_Pink_Background.jpg" alt="perfil" style="width: 120px">
    </div>
<div class="col-md-10">
      <div class="card-body">
      <div class="row mb-2">
    <div class="col" id="referencia-inforpor-modal">
      <!-- Referencia inforpor -->
    </div>
    <div class="col" id="referencia-cliente-modal">
      <!--Referencia cliente-->
    </div>
  </div>
  <div class="row mb-2" id="articulos-crear-compra-modal">
  </div>
  <div class="row mb-2" id="elige-almacen">
    <select class="form-select" aria-label="Default select example">
  <option selected>¿De que almacen salimos?</option>
  <option value="1">Futura Teck</option>
  <option value="2">Inforpor - Custodias</option>
</select>
  </div>
      </div>
    </div>
  </div>

</div></div>
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
<script>
//ocultamos la clase propia para dejar el botón de acceder a la otra
$('#menu-plataforma a').filter(function(){
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
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>

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

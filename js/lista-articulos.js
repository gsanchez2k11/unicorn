
/**
 * Comprueba las opciones marcadas en los filtros y las aplica
 * DEPRECATED
 *
 */
function aplicaFiltros() {
  //Vamos a comprobar el estado de todos los filtros
  let estadoFiltros = document.querySelectorAll('.form-check-input');           //Seleccionamos todos
  let arrEstados = [];
  let arrFiltro = {};
  $('.linea-articulo').show();                                                  //Mostramos todos y vamos a ir descontando
  estadoFiltros.forEach((item) => {
  //  console.log(item);
    if (item.checked === true && item.id != 'flexSwitchIva') { //Descontamos el del IVA
      let id = item.id;
      if (id !== 'flexCheckNoActualizaArticulo') {
        let test = /^mostrar-todos\.*/.test(id);
        if (test === false) {
          //Tenemos 2 opciones, que queramos filtrar sólo los que si o los que no
          //Capturamos el atributo sobre el que vamos a actuar
          let arrAtributos = id.split('-');
          let atributo = arrAtributos[2];
          let siNo = arrAtributos[1];
arrFiltro[atributo] = siNo;

          if (siNo === 'si') {
            $('.linea-articulo').filter(function(){
              let atributos =  $(this).data('atributos');
              return atributos[atributo] <= 0;
            }).hide();
          } else {
            $('.linea-articulo').filter(function(){
              let atributos =  $(this).data('atributos');
              return atributos[atributo] !== 0;
            }).hide();
          }

          arrEstados.push(siNo);                                                   //Solo añadimos los que no son "mostrar todos"
          //  arrEstados.push(item.id);                                                   //Solo añadimos los que no son "mostrar todos"


        }
      }
    }

  });
//console.log(arrFiltro);

  $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
//    console.log(Object.keys(arrFiltro).length);
  //  console.log(data);
  //  console.log(arrFiltro);
//Comprobamos primero las custodias
if ((arrFiltro.hasOwnProperty('custodia') && arrFiltro.custodia == 'si' && data[7] == 1) || //Si el filtro para custodia es si
  (arrFiltro.hasOwnProperty('custodia') && arrFiltro.custodia == 'no' && data[7] == 0)  || //Si el filtro es no
  arrFiltro.hasOwnProperty('custodia') === false )
  {
  return true;
}
//Comprobamos el stock
/*if ((arrFiltro.hasOwnProperty('stock') && arrFiltro.stock == 'si' && data[3] >= 1) || //Si el filtro para custodia es si
  (arrFiltro.hasOwnProperty('stock') && arrFiltro.stock == 'no' && data[3] == 0)  || //Si el filtro es no
  arrFiltro.hasOwnProperty('stock') === false )
  {
  return true;
}*/

//    console.log('arrAtributos');
//    console.log(data);
    return false;

  });
$('#tablaArticulos').DataTable().draw();
return arrEstados;
}

function dameUrlMktPlace(plataforma,nombreArticulo) {
  let urlMarketPlace;
  switch (plataforma) {
    case 'pcc':
let   urlTienda = generaUrl(plataforma,nombreArticulo);
  urlMarketPlace =    '<a target="_blank" href="'+ urlTienda +'"><img src="https://bikemarket.pt/storage/avatars/1605868876.jpg" style="width: 24px"></a>';
      break;
    case 'fnac':
    urlMarketPlace =    '<span class="link-fnac" style="cursor: pointer"><img src="https://upload.wikimedia.org/wikipedia/commons/thumb/2/2e/Fnac_Logo.svg/1200px-Fnac_Logo.svg.png" style="width: 24px"></span>';

      break;
  }
  return urlMarketPlace;
}


function dameMejorCompra(articulo) {
  let custodia = articulo.inforpor.custodias;
  //Calculamos el margen, no hay margen si desconocemos la comisión de esa categoria
  let mejorCompra = articulo.compra_IVA;                        //Partimos del precio normal de inforpor como mejor precio
  if (custodia !== false) {                                     //Comprobamos si existe custodias
    //        console.log(custodia);
    for (c in custodia) {
      if (c === 1 || custodia[c].total_compra > mejorCompra) {                //Si la custodia o el precio es más alto que el que tenemos lo fijamos
        mejorCompra = custodia[c].total_compra;                               //De esta manera nos aseguramos que si hay custodia siempre cojamos el precio más alto de entre las custodias
      }
    }
  }
mejorCompra = accounting.unformat(mejorCompra); //Lo convertimos a numero
return mejorCompra;
}

function dameDatosMargen(articulo,categoria){
  let margen = '-';
  let margenIco = '<img src="img/unicornio-espalda.png" style="width:64px">';
  let margenAlert ='';
  let comision;

  if (typeof articulo.comision !== 'object') {                                    //Si es un objeto estamos recibiendo comision == null
    let margenFloat = parseFloat(articulo.margen);
    switch (true) {
      case (margenFloat === -100):
      margen = '-';
      break;
      case (margenFloat > -100 && margenFloat < 0):
      margen = margenFloat + '%<img src="img/unicornio-encabronado.png" style="width:32px; margin-left: 1em">';
      break;
      default:
      margen = margenFloat + '%';
    }
    switch (true) {
      case (articulo.margen < 5):
      margenIco = '<img src="img/unicornio-encabronado.png" style="width:64px">';
      break;
      case (articulo.margen >= 5 && articulo.margen < 10):
      margenIco = '<img src="img/unicornio-normal.png" style="width:64px">';
      break;
      case (articulo.margen >= 10):
      margenIco = '<img src="img/unicornio-celebrando.png" style="width:64px">';
      break;

    }
    comision = articulo.comision;
  } else {
    margenAlert = '<div class="row"><div class="alert alert-danger" role="alert">No podemos calcular el margen al no disponer de la comisión.<a href="#" role="button" class="boton-add-comision" data-categoria="'+categoria+'"">¿La conoces?</a></div></div>';
    comision = '-';
  }
let datosMargen = {
  margen: margen,
  margenIco : margenIco,
  margenAlert : margenAlert,
  comision: comision
}
  return datosMargen;
}

function dameIconoTopVentas(posTopventas){
  let posTopventasIco;
  switch (true) {
    case (posTopventas === 0):
    posTopventasIco = '<i class="fas fa-medal texto-oro fa-2x" title="El artículo más vendido" data-bs-toggle="tooltip" data-bs-placement="top"></i>';
      break;
      case (posTopventas === 1):
      posTopventasIco = '<i class="fas fa-medal texto-plata fa-2x" title="Segundo artículo más vendido" data-bs-toggle="tooltip" data-bs-placement="top"></i>';
        break;
        case (posTopventas === 2):
        posTopventasIco = '<i class="fas fa-medal texto-bronce fa-2x" title="Tercer artículo más vendido" data-bs-toggle="tooltip" data-bs-placement="top"></i>';
          break;
          case (posTopventas > 2):
          posTopventasIco = '<i class="fas fa-shopping-cart text-gray-400" title="Hemos vendido este artículo recientemente" data-bs-toggle="tooltip" data-bs-placement="top"></i>';
            break;
            default:
      posTopventasIco = '';
      break;
  }
  return posTopventasIco;
}


/**
 * EVENTOS
 */
 $(document).on('click','.jueguemos', (event) => {
   let atributos = $(event.currentTarget).data();
  let dataAttrs = $(event.currentTarget).data('atributos');
  let shop_sku = dataAttrs.shopsku;
  let mpn = dataAttrs.mpn;
  let ean = dataAttrs.ean;
  let stock = dataAttrs.stock;
  let entidad = dataAttrs.entidad;
   let precio = parseFloat(document.getElementById('slider-range-value-'+atributos.atributos.ean).innerHTML);
//Actualizamos primero la base de datos
let actualizaStock = actualizaCampo(entidad,13,stock);
let actualizaPrecio = actualizaCampo(entidad,14,precio);
console.log(actualizaPrecio);

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
     let logistic_class = {                                                          //De momento ponemos de manera automática la clase logistica
       "code": 'medio',
       "label": 'Medio - Entre 2 y 10 kg'
     };
     //let shop_sku = rangoSlider.ofertaProductSku != null ? rangoSlider.ofertaProductSku :  rangoSlider.oferta;


     datos = {
       //      product_id :  mpn,
       //      product_id_type : 'MPN',
       product_id :  ean,
       product_id_type : 'EAN',
       shop_sku : shop_sku,
       //shop_sku : 'S10281',
       quantity : stock,
       state_code: 11,
       price: precio.toFixed(2),
       update_delete: 'update',
       offer_additional_fields: offer_additional_fields,
       logistic_class: 'medio'
     };

//console.log(datos);

       break;
      case 'fnac':
      datos = {
        mpn :  mpn,
        stock_final : stock,
        precio: precio.toFixed(2),
      };
    url = 'ajax/fnac/actualizar-oferta.php';
      break;
   }
   var actualizarOfertas = actualizaOferta(datos,url);

   if (actualizarOfertas.hasOwnProperty('pcc') || actualizarOfertas.hasOwnProperty('phh') || actualizarOfertas.hasOwnProperty('fnac')) {
     exitoActualizacionModal.show();
   }

 });

 $(document).on('click','.check-art-mks', (event) => {
   checkArtMksModal.show(); //check                                             //Mostramos el modal
   let mks = document.querySelectorAll('.lista-marketplaces a');                //Capturamos la lista de marketplaces
mks.forEach(mk => {                                                             //recorremos los mks
  let esteMk = $(mk).data('mk');
  if (esteMk == plataforma) {                                                   //Desde la plataforma que estamos trabajando ya sabemos que existe
  mk.classList.add('list-group-item-success');
} else {                                                                        //Para el resto tenemos que ir buscando en su respectiva plataforma
  mk.classList.add('list-group-item-dark');
}

   } )

 });
 function switchAtributo(entidad, valorActual, atributo){
  let valorNuevo = valorActual == 0 ? 1 : 0;
actualizaCampo(entidad,atributo,valorNuevo);
return valorNuevo;
}

$(document).ready(function() {
  //Capturamos el click en el botón de modificar la cantidad
let modificaCantidadBtn = document.getElementById('modifica-cantidad-btn');
modificaCantidadBtn.addEventListener('click', function(e){
  let accion = e.target.getAttribute('accion'); //La accion que queremos 
  let cantidad = document.getElementById('inputCantidad').value;

switch (accion) {
  case 'stock-local':
    let entidad = e.target.getAttribute('entidad'); //La accion que queremos 
    var datos = {
      entidad: entidad,
      valor: cantidad,
      atributo: 18
    }    
    break;

}

$.ajax({
  type: "POST",
  url: "ajax/unicorn_db/actualiza-atributo.php",
  data: datos,
  success: function(data, textStatus, jqXHR) {
    if (data === 'ok') { //Si tenemos el ok modificamos la ventana para dar la buena noticia
      $('#modificar-cantidad-modal .modal-title').html('Ahora conocemos tus secretos');
      $('#modificar-cantidad-modal .modal-body').html('<div class=""><img class="rounded mx-auto d-block" src="https://cdn.dribbble.com/users/379548/screenshots/2309647/unicorn-loop.gif"  style="width:300px" /></div>');

    }
    console.log(data);
  },
  error: function(data, textStatus, jqXHR) {
    // alert("error:" + respuesta);
    // console.log(respuesta);
    alert('Error: ' + data);
    //location.reload();
  },
});

});


});

    /*         $(document).on('click', '#add-comision-boton', (event) => {
                event.preventDefault();
                let comision = $('#inputComision').val();
                let categoria = $('#etiqueta-comision').html();
                if (comision.length > 0 && comision > 0) { //Validamos el campo y grabamos la información
                  let datos = {
                    categoria: categoria,
                    comision: comision
                  }
                  $.ajax({
                    type: "POST",
                    url: "ajax/unicorn_db/add-categoria-comision.php",
                    data: datos,
                    success: function(data, textStatus, jqXHR) {
                      if (data === 'ok') { //Si tenemos el ok modificamos la ventana para dar la buena noticia
                        $('#modificar-cantidad-modal .modal-title').html('Ahora conocemos tus secretos');
                        $('#modificar-cantidad-modal .modal-body').html('<div class=""><img class="rounded mx-auto d-block" src="https://cdn.dribbble.com/users/379548/screenshots/2309647/unicorn-loop.gif"  style="width:300px" /></div>');

                      }
                      console.log(data);
                    },
                    error: function(data, textStatus, jqXHR) {
                      // alert("error:" + respuesta);
                      // console.log(respuesta);
                      alert('Error: ' + data);
                      //location.reload();
                    },
                  });
                }


              });*/
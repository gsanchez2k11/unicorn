/**
* Carga las custodias de un articulo a partir del codigo de inforpor dado
* @return {[type]} [description]
*/
function cargaCustodiasArticulo(codinfo) {
  let datos = {
    codinfo: codinfo
  };
  return JSON.parse($.ajax({
    type: "POST",
    url: "ajax/unicorn_db/custodias_articulo.php",
    data: datos,
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
}


/**
* DEvuelve un listado del tipo indicado sin parámetros
* @param  {[type]} tipo [description]
* @return {[type]}      [description]
*/
function dameListadoJson(tipo) {
  var url;
  var plataforma;
  var tipo;
  switch (tipo) {
    case 'ultimos pedidos mirakl':
    case 'ultimos pedidos':
    case 'ultimos pedidos pcc':
    url = "ajax/mirakl/ultimos-pedidos.php";
    plataforma = 'pcc';
    break;
    case 'ultimos pedidos Phone House':
    url = "ajax/mirakl/ultimos-pedidos.php";
    plataforma = 'phh';
    break;
    case 'ultimos pedidos mediamarkt':
      url = "ajax/mirakl/ultimos-pedidos.php";
      plataforma = 'mediamarkt';
      break;
      case 'ultimos pedidos miravia':
        url = "ajax/mirakl/ultimos-pedidos.php";
        plataforma = 'mediamarkt';
        break;
    case 'ultima actualizacion':
    case 'ultima actualizacion pcc':
    url = "ajax/unicorn_db/ultima_actualizacion.php";
    tipo = 'stock';
    plataforma = 'pcc';
    break;
    case 'ultima actualizacion phh':
    url = "ajax/unicorn_db/ultima_actualizacion.php";
    tipo = 'stock';
    plataforma = 'phh';
    break;
    case 'ultima actualizacion fnac':
    url = "ajax/unicorn_db/ultima_actualizacion.php";
    tipo = 'stock';
    plataforma = 'fnac';
    break;
    case 'mejoras de precios':
    url = "ajax/unicorn_db/listar-actualizaciones.php";
    tipo = 'precios_mejorados';
    break;
    case 'articulos con custodias':
    url = "ajax/unicorn_db/entidades_con_custodias.php";
    break;
    case 'ultimos pedidos magento':
      case 'ultimos pedidos mage':
    url = "ajax/magento/ultimos-pedidos.php";
    break;
    case 'todas las ofertas pcc':
    url = "ajax/mirakl/listar-todas-ofertas.php";
    break;
    case 'pedidos odoo':
      url = "ajax/odoo/listar-pedidos.php";
      break;
      case 'pedidos inforpor':
      url = "ajax/inforpor/listar-pedidos.php";
      break;

  }
  var datos = {
    plataforma: plataforma,
    tipo: tipo
  }
  return JSON.parse($.ajax({
    type: "POST",
    url: url,
    data: datos,
    dataType: 'json',
    global: false,
    async: false,
    success: function(data, textStatus, jqXHR) {
      //  console.log('cliente: ' + resp);
      //    $('.clientecrm').append(resp);
      $('.loader').fadeOut('slow');
      return data;
    },
    error: function(data, textStatus, jqXHR) {
      alert('Error: ' + JSON.stringify(data));
      //    $(bloque).find('.dimmer').toggleClass('active');
    }
  }).responseText);
}


function llamadaJson(url, datos) {

  return JSON.parse($.ajax({
    type: "POST",
    url: url,
    data: datos,
    dataType: 'json',
    global: false,
    async: false,
    success: function(data, textStatus, jqXHR) {
      //  console.log('cliente: ' + resp);
      //    $('.clientecrm').append(resp);
      $('.loader').fadeOut('slow');
      return data;
    },
    error: function(data, textStatus, jqXHR) {
      alert('Error: ' + JSON.stringify(data));
      //    $(bloque).find('.dimmer').toggleClass('active');
    }
  }).responseText);
}

function buscaMirakl(termino) {
  //Comprobamos si es una id de pedido
  let resultado;
  patt = /\d{6,7}-[aAbB]/g; //Regex de id de pedido
  let existe = patt.test(termino);
  if (existe === true) {
    //Buscamos el pedido
    let datos = {
      id: termino
    };
    let llamada = llamadaJson('ajax/mirakl/buscar-pedido.php', datos);
    //colocaDatosPedido(llamada);
    if (llamada.length > 0) {
      resultado = llamada;
    } else {
      resultado = 'No hay ningún pedido con esa id';
    }


  } else {
    resultado = 'Esto no es una id de pedido';
  }
  return resultado;
}

function colocaDatosPedido(pedidos) {
  for (s in pedidos) { //Recorremos el listado de pedidos
    var pedido = pedidos[s]; //Capturamos el pedido actual en una variable
    console.log(typeof pedido.lineas_pedido[0].estado);
    var estadoPedido = typeof pedido.lineas_pedido[0].estado != 'undefined' ? pedido.lineas_pedido[0].estado : pedido.estado; //El estado actual lo obtenemos de la primera linea del pedido
    var botonAcciones = estadoPedido != 'WAITING_ACCEPTANCE' ? '<button class="btn btn-info btn-circle" type="submit"><i class="fas fa-horse-head"></i></button>' : '';
    //Colocamos los datos del pedido en la tabla
    $('#dataTable tbody').append('<tr id="' + pedido.id + '"><td>' + pedido.fecha_creado + '</td><td>' + pedido.id + '</td><td>' + estadoPedido + '</td><td class="articulos"></td><td>' + new Intl.NumberFormat("es-ES", {
      style: "currency",
      currency: "EUR"
    }).format(pedido.total_pedido) + '</td><td>' + botonAcciones + '</td></tr>')
    for (a in pedido.lineas_pedido) { //Colocamos la parte de los articulos
      var linea = pedido.lineas_pedido[a];
      var htmlSkuOferta = typeof linea.sku_oferta != 'undefined' ? ' | SKU de oferta: ' + linea.sku_oferta : '';
      $('#' + pedido.id + ' .articulos').append(linea.cantidad + ' x <b>' + linea.nombre + '</b><span style="font-size: 0.8em"> (SKU de producto: ' + linea.sku + htmlSkuOferta + ') </span> <br>');
    }

  }
  return pedidos; //Devolvemos el listado de pedidos que hemos pasado como parámetro
}


function cargaContadores() {
  return JSON.parse($.ajax({
    type: "POST",
    url: "ajax/unicorn_db/carga-contadores.php",
    //               data: datos,
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
}

function colocaContador(contador) {
  let valorReal = parseFloat(contador.valor);
  let valorFake = valorReal * 1.05; //Incrementamos con un 10%
  let valor = valorFake <= 500 ? valorFake.toFixed(0) : valorReal;
  //let valor = -100;
  let max = 500;
  let porcentaje = valor * 100 / 500;
  let iconoContador;
  switch (true) {
    case (porcentaje < 25):
    iconoContador = '<img src="img/unicornio-dinero.png">';
    break;
    case (porcentaje >= 25 && porcentaje < 50):
    iconoContador = '<img src="img/unicornio-normal.png">';
    break;
    case (porcentaje >= 50 && porcentaje < 75):
    iconoContador = '<img src="img/unicornio-angustia.png">';
    break;
    case (porcentaje >= 75):
    iconoContador = '<img src="img/unicornio-espalda.png" style="width: 64px">';
    break;


  }
  $('.cifra-contador').html(valor + '/500');
  $('.barra-contador').attr('aria-valuenow', valor).width(porcentaje + '%');
  $('.icono-contador').html(iconoContador);
}

function gestionContadores(){
  let contador = cargaContadores();
  colocaContador(contador);
  setInterval(function(){
    let nuevoContador = cargaContadores();
    if (contador.valor !== nuevoContador.valor) {
      colocaContador(nuevoContador);
      contador = nuevoContador;
    }

  }, 3000);
}

function redireccionarPagina(variable, valor) {
  //window.location.href = "articulo.php?referencia=" + encodeURIComponent(referencia);
  //https://midu.dev/urlsearchparams-como-leer-la-query-string/
  // recuperamos el querystring
  const querystring = window.location.search;
  console.log(querystring)
  // usando el querystring, creamos un objeto del tipo URLSearchParams
  const params = new URLSearchParams(querystring);
  params.set(variable, valor);
  console.log(params.toString()); // "q=URLUtils.searchParams"
  // reemplazamos el historial del navegador con esta nueva querystring
  window.history.replaceState({}, '', `${location.pathname}?${params}`);
  //Volvemos a cargar el documento
  location.reload();

}


//Validamos el nif/cif
function validarNifCif(nif){
  nif = nif.toUpperCase();                                                      //Ponemos el nif/cif en mayúsculas

  var numero, letr, letra;
  var expresion_regular_nif = /^[XYZ]?\d{5,8}[A-Z]$/;                           //Expresion regular para NIFS / NIE
  var expresion_regular_cif = /^[a-zA-Z]{1}\d{7}[a-zA-Z0-9]{1}$/;                           //Expresion regular para CIF
  let resultado;
  //Cuadramos primero con el nif
  if(expresion_regular_nif.test(nif) === true){
    numero = nif.substr(0,nif.length-1);
    numero = numero.replace('X', 0);
    numero = numero.replace('Y', 1);
    numero = numero.replace('Z', 2);
    letr = nif.substr(nif.length-1, 1);
    numero = numero % 23;
    letra = 'TRWAGMYFPDXBNJZSQVHLCKET';
    letra = letra.substring(numero, numero+1);
    if (letra != letr) {
      //alert('Dni erroneo, la letra del NIF no se corresponde');
      resultado =  'nif erroneo';
    }else{
      //alert('Dni correcto');
      resultado =  'nif correcto';
    }
  }else if(expresion_regular_cif.test(nif) === true){                                                                     //Si no cuadra con un nif probamos con un CIF
    //alert('Dni erroneo, formato no válido');
    resultado = 'cif';
  } else {
    resultado = 'tipo de documento desconocido';
  }
  return resultado;
}

function dameTipoNombre(nombre_completo) {
  let nombreCompleto = nombre_completo.toUpperCase();
  let sl = /^.+S\.?L.*$/;
  let esEmpresa;
  switch (true) {
    case sl.test(nombreCompleto):
    esEmpresa =  true;
    break;
    default:
    esEmpresa = false;
    break;
  }
  return esEmpresa;
}

/**
* Funcion que captura una cantidad en formato moneda o porcentaje y devuelve los dígitos
* @param  {[type]} selector [description]
* @return {[type]}          [description]
*/
function dameCifra(selector){
  let inicialDiv = $(selector).html();                                 //Capturamos el precio original
  let inicial = Number(inicialDiv.replace(/[^0-9.-]+/g,""));          //DEjamos solo los digitos
  return inicial;
}

/**
* Genera una Url según las normas conocidas de las marketplaces
* @return {[type]} [description]
*/
function generaUrl(plataforma,nombreArticulo){
  let inicioUrl;
  let restoUrl;
  switch (plataforma) {
    case 'pcc':
  inicioUrl =   'https://www.pccomponentes.com/';
      break ;
    default:
      inicioUrl = '/';
      break;
  }
restoUrl = nombreArticulo.toLowerCase().replaceAll('ó','o').replaceAll(' ','-').replaceAll('\/','-').replaceAll('\.','').replaceAll('\"','').replaceAll('+','').replaceAll('--','-').replaceAll('+','').normalize("NFD").replace(/[\u0300-\u036f]/g, "");

  return inicioUrl + restoUrl;
}

function decodificaUTF8(cadena) {
  return decodeURIComponent(escape(cadena));
}


/**
 * Funcion que busca la referencia dada en la base de datos y devuelve su entidad
 * @var Object miArticulo objeto con las referencias o datos para buscar
 * @return Array Array con los posibles códigos del articulo
 */
  async function buscaEntidadBbdd(miArticulo) {
    let url = 'ajax/unicorn_db/buscar-entidad.php';
    let datos = miArticulo;
    //console.log(datos);
    let response = await JSON.parse($.ajax({
      type: "POST",
      url: url,
      data: datos,
      dataType: 'json',
      global: false,
      async:false,
      success: function(data, textStatus, jqXHR) {
        return data;
      },
      error: function(data, textStatus, jqXHR) {
        console.log('Error al cargar el pedido: ' + JSON.stringify(data));
      }
    }).responseText);
      let miEntidad = await response;

      if (Array.isArray(miEntidad) && miEntidad.length === 0) { //Si no existe grabamos la entidad sobre la marcha
      let grabaEnt = grabaEntidadEan(miArticulo);
      miEntidad =  JSON.parse($.ajax({
          type: "POST",
          url: url,
         data: datos,
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
      }

      return miEntidad;
  }


function crearEntidad(datos) {
  return JSON.parse($.ajax({
    type: "POST",
    url: "ajax/unicorn_db/crear-entidad.php",
   data: datos,
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
};
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

function addAttrValor(datos) {
  return JSON.parse($.ajax({
    type: "POST",
    url: "ajax/unicorn_db/add-atributo-valor.php",
   data: datos,
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
};
function buscarArticuloSkuPcc(skuPcc) {
  let datos = {
    product_sku : skuPcc
  };
  return JSON.parse($.ajax({
    type: "POST",
    url: "ajax/mirakl/buscar-articulo.php",
   data: datos,
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
}

/**
A partir de los resultados de buscarArticuloSkuPcc devuelve true o false si nosotros aparecemos en las ofertas
**/
function loTenemosPcc(skuPcc) {
  let buscar = buscarArticuloSkuPcc(skuPcc);
  let ofertas = buscar.products[0].offers;                                        //Nos quedamos solo con las ofertas
  let loTenemos = false;
  for (s in ofertas) {
    let oferta = ofertas[s];
    let nombreTienda = oferta.shop_name;
    if (nombreTienda === 'Futura Teck') {
      loTenemos = true;
    }
  }
  return loTenemos;
}


/**
* Función que busca información de un artículo mediante el EAN en pccomponentes
* @var string codigo ean
* @return object data
*/
async function fetchmirakl(miArticulo) {
  const proxy = 'https://cors-anywhere.herokuapp.com/';
  let url = 'pccomponentes-prod.mirakl.net/api/products/offers?product_references='+miArticulo.tipoRef.toUpperCase()+'|'+miArticulo.referencia;
  let direccionCompleta = proxy + url;
  //  let response = await fetch('https://cors-anywhere.herokuapp.com/pccomponentes-prod.mirakl.net/api/products/offers?product_references='+type.toUpperCase()+'|'+ref,{
  let response = await fetch(direccionCompleta,{
    method: 'GET',
    mode: 'cors',
    headers: {
      'Access-Control-Request-Headers': 'Authorization',
      'Authorization': '34e564b3-4def-4dba-b94b-bac67c091709',                  //Api de PcComponentes
      'Accept': '*/*'
    }
  });
  let data = await response.json()
  return data;

}

function buscaArticuloInforpor(codInfo) {
  let datos = {
    codinfo : codInfo
  };
  return JSON.parse($.ajax({
    type: "POST",
    url: "ajax/inforpor/buscar-articulo.php",
    data: datos,
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
 }


 /**
  * Funcion que actualiza una oferta en el marketplace correspondiente
  * @param  {[type]} datos [description]
  * @return {[type]}       [description]
  */
 /*function actualizaOferta(datos, url, plataforma) {
   return JSON.parse($.ajax({
     type: "POST",
     url: url,
     data: {
      datos: datos,
    plataforma: plataforma},
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
 }*/

 /**
  * Actualizamos un artibuto en la base de datos
  * @param  {[type]} entidad [description]
  * @param  {[type]} estado  [description]
  * @return {[type]}         [description]
  */
 function actualizaCampo(entidad,atributo,valor,plataforma = 'pcc') {
   let datos = {
     entidad: entidad,
     atributo: atributo,
     valor : valor,
     plataforma: plataforma
   }
   $.ajax({
     type: "POST",
     url: "ajax/unicorn_db/actualiza-atributo.php",
     data: datos,
     success: function(data, textStatus, jqXHR) {
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

 function compare( a, b ) {
   if ( a.cantidad < b.cantidad ){
     return 1;
   }
   if ( a.cantidad > b.cantidad ){
     return -1;
   }
   return 0;
 }

/**
 * Revisamos la lista de pedidos y devolvemos un array con los articulos vendidos y la cantidad de cada uno
 * @param  {[type]} listapds               [description]
 * @return {[type]}          [description]
 */
function dameArticulosConPedidos(listapds) {
  let articulos = [];
  for (pedido of listapds) { //recorremos los pedidos
  for (linea of pedido.lineas_pedido) {
/*    let resultado = Object.keys(articulos).filter(articulo => articulo == linea.mpn); //Buscamos el articulo actual en el array

    if (resultado.length === 0) { //Si el artículo no existe lo añadimos
  articulos[linea.mpn] = linea.cantidad;
    } else {
articulos[linea.mpn] += linea.cantidad; //Si ya existe sumamos la cantidad
}*/
let resultado = articulos.filter(articulo => articulo.mpn == linea.mpn); //Buscamos el articulo actual en el array
if (resultado.length === 0) { //Si el artículo no existe lo añadimos
  articulos.push({mpn : linea.mpn, cantidad : linea.cantidad });
}else {
for (a in articulos) {
if (articulos[a].mpn == linea.mpn) {
  articulos[a].cantidad += linea.cantidad;
}
}
}
  }
  }
  articulos.sort(compare);
    return articulos;
}
/**
 * Lanza el worker que busca las compras para un array de articulos
 * @param {*} pd 
 * 
 */
function compraArticulosWorker(objeto) {
 // let articulos = pd.lineas_pedido;
 // console.log('articulos');
 // console.log(articulos);
  if (typeof(Worker) !== "undefined") {
    if (typeof(wr) == "undefined") {
      wr = new Worker("js/workers/buscar-compras-articulo.js");
    }
//console.log('nT');
//console.log(tarifas);
/*let obj = {
  articulos: articulos,
  tarifas: tarifas
}*/
    wr.postMessage(objeto); //pasamos articulos y  tarifa al worker
    wr.onmessage = function(event) {
//console.log('hay compra');
colocaInfoCompra(event.data); //Nos llevamos esta parte a cada página concreta

    }
  }
}

async function getTarifas(tarifas) {
  let tarifa;
  if (tarifas === "") {
    //Si es la primera ejecución le hemos asignado un valor vacío
   // const url1 = "./var/import/inforpor/tarifa.000";
    const url1 = "./var/import/inforpor/tarifa.lite";
   // const url2 = "./ajax/google/dame-tarifa-2022.php";
    const url2 = "./ajax/google/dame-tarifa.json";

    const responses = await Promise.all([fetch(url1), fetch(url2)]);

    const data1 = await responses[0].json();
    const data2 = await responses[1].json();

    // do what you want with data1 and data2 here
    tarifa = {
      ifp: data1,
      google: data2,
    };
  } else {
    tarifa = tarifas;
  }
  return tarifa;
}

/**
 * Formatea un objeto Date y devuelve un string en formato YYYY-MM-DD
 * https://www.tutorialrepublic.com/faq/how-to-format-javascript-date-as-yyyy-mm-dd.php
 * @param {*} fecha 
 * return fechaFormateada
 */
function formateaFecha(fecha){
          // Get year, month, and day part from the date
var year = fecha.toLocaleString("default", { year: "numeric" });
var month = fecha.toLocaleString("default", { month: "2-digit" });
var day = fecha.toLocaleString("default", { day: "2-digit" });
// Generate yyyy-mm-dd date string
formattedDate = year + "-" + month + "-" + day;
return formattedDate;
}


/*******************************************************************************/
// EVENTOS COMUNES
//
//
/*******************************************************************************/
  $(document).on('click','#menu-plataforma a', (e) => {
  e.preventDefault();
  let valor = $(e.currentTarget).data('pl');
  redireccionarPagina('pl', valor);
})

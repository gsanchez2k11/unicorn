/**
 * Convierte la tarifa en un array para poder filtrar
 * @param {*} miTarifa 
 * @returns 
 */
function damelistado(miTarifa) {
  let arrTarifa = Object.values(miTarifa); //convertimos el objeto en array
  let listado = []; //Para sacar un listado de todas las referencias  sin categorias
  //Recorremos cada hoja por separado
    for (i in arrTarifa) {
      let hoja = arrTarifa[i]; //nombramos una variable para facilitar la identificación
for (seccion in hoja) {
  let material = Object.values(hoja[seccion]);
  for (b in material) {
  let variantes = material[b];
  for (h in variantes) {
  let art = variantes[h]; //Tenemos el artículo
  listado.push(art);
  }

}
}
    }
  return listado;
}

async function pidePaginaArticulos() {
  let url = 'ajax/magento/dame-todos-articulos.php';
  let datos = {
    p: 1,
    status: 1 //Vamos a pedir solo los que tienen el estado "habilitado"
  };
  let todosMagento = [];
  let estaPagina
  $('.loader-text').html('Cargando artículos desde magento');
  //console.log(datos);
  do {

    let response = await JSON.parse($.ajax({
      type: "POST",
      url: url,
      data: datos,
      dataType: 'json',
      global: false,
      async: false,
      timeout: 0,
      success: function(data, textStatus, jqXHR) {
        return data;
      },
      error: function(data, textStatus, jqXHR) {
        console.log('Error al cargar el pedido: ' + JSON.stringify(data));
      }
    }).responseText);
    datos.p++;
    estaPagina = await response;
    for (a in estaPagina) {
      todosMagento.push(estaPagina[a]);
    }

  } while (estaPagina.length === 100 /*&& datos.p <= 2*/ );
  //    console.log(estaPagina.length);
  console.log(todosMagento);
  return todosMagento;
}

async function dameTarifa() {
  let datos;
  let miTarifa = await llamadaJson('ajax/google/dame-tarifa-2022.php', datos); //Buscamos en la base de datos
  return miTarifa;
}

function colocaDatosTarifa(miTarifa) {
  let arrTarifa = Object.values(miTarifa); //convertimos el objeto en array
  let content = document.getElementById('content'); //Id del contenido de la página
  let bloquePrincipal = content.getElementsByClassName('container-fluid')[0]; //Bloque principal con la información
  let listado = []; //Para sacar un listado de todas las referencias  sin categorias
  //Recorremos cada hoja por separado
  for (i in arrTarifa) {
    let hoja = arrTarifa[i]; //nombramos una variable para facilitar la identificación
    let nombreHoja = Object.keys(miTarifa)[i];
    let div = document.createElement('div'); //Creamos un div
    div.classList.add('row'); //Le añadimos la clase row
    let col = document.createElement('div'); //Creamos el div con el título
    col.classList.add('col-xl-12', 'col-md-6', 'mb-4');
    let collapse = document.createElement('div'); //Creamos el div con el contenido
    collapse.classList.add('collapse');
    collapse.setAttribute('id', 'collapse' + nombreHoja);
    let collapseCard = document.createElement('div'); //Creamos el div con el contenido
    collapseCard.classList.add('card', 'card-body');

    let a = document.createElement('a');
    a.setAttribute('data-bs-toggle', 'collapse');
    a.setAttribute('href', '#collapse' + nombreHoja);
    a.setAttribute('role', 'button');
    a.setAttribute('aria-expanded', 'false');
    a.setAttribute('aria-controls', 'collapse' + nombreHoja);
    a.classList.add('card', 'border-left-info', 'shadow', 'h-100', 'py-2');
    let cardBody = document.createElement('div');
    cardBody.classList.add('card-body');
    let rowCardBody = document.createElement('div');
    rowCardBody.classList.add('row', 'no-gutters', 'align-items-center');
    let col1 = document.createElement('div');
    col1.classList.add('col-auto', 'icono-contador');
    let col2 = document.createElement('div');
    col2.classList.add('col', 'ml-3');
    let divNombreHoja = document.createElement('div');
    divNombreHoja.classList.add('row', 'no-gutters', 'align-items-center');
    let colDivNombreHoja = document.createElement('div');
    colDivNombreHoja.classList.add('col-auto');
    let nombreDiv = document.createElement('div');
    nombreDiv.classList.add('h5', 'mb-0', 'mr-3', 'font-weight-bold', 'text-info', 'cifra-contador');
    let nombreHtml = document.createTextNode(nombreHoja);
    nombreDiv.appendChild(nombreHtml);
    colDivNombreHoja.appendChild(nombreDiv);
    divNombreHoja.appendChild(colDivNombreHoja);
    col2.appendChild(divNombreHoja);
    let col3 = document.createElement('div');
    col3.classList.add('col-auto', 'num-distintos', 'fs-1');
    collapse.appendChild(collapseCard);
    rowCardBody.appendChild(col1).appendChild(col2).appendChild(col3);
    cardBody.appendChild(rowCardBody);
    a.appendChild(cardBody);
    col.appendChild(a);
    div.appendChild(col);
    bloquePrincipal.appendChild(div).appendChild(collapse);

    for (seccion in hoja) {
      let rowSeccion = document.createElement('div');
      rowSeccion.classList.add('row');
      let nombreSeccion = document.createTextNode(seccion);
      rowSeccion.appendChild(nombreSeccion);
      collapseCard.appendChild(rowSeccion);
      let material = Object.values(hoja[seccion]);
      for (b in material) {
        let variantes = material[b];
        for (h in variantes) {
          let art = variantes[h]; //Tenemos el artículo
          listado.push(art);
          let sku = art.referencia;
          datos = {
            mpn: sku
          }
          //  let tarifaInforpor = llamadaJson('ajax/magento/buscar-articulo.php',datos);             //Cargamos la tarifa de inforpor

          //  console.log(tarifaInforpor);

        }

      }

    }


  }
  return listado;
}


function cambiaPrecio(datos) {
  let url;
  if (plataforma == 'mage') {
    url = "ajax/magento/actualizar-articulo.php";
  } else {
    url = "ajax/odoo/actualizar-articulo.php";
  }
  $.ajax({
    type: "POST",
    url: url,
    data: datos,
    beforeSend: function() {
      $('#resultado-actualizar-modal .modal-title').html('Looking for the fiesta');
      $('#resultado-actualizar-modal .modal-body').html('<div class=""><img class="rounded mx-auto d-block" src="https://cdn.dribbble.com/users/2172479/screenshots/6014482/unicorn_dribble1.gif"  style="width:300px" /></div>');

    //  document.getElementById('resultado-actualizar-modal').show();
    },
    success: function(data, textStatus, jqXHR) {
      //  console.log('jqXHR');
      //  console.log(typeof jqXHR.responseText);
      if (jqXHR.responseText == '"ok"') { //Si tenemos el ok modificamos la ventana para dar la buena noticia
        let numDistintos = $('.num-distintos').html(); //Restamos uno al contador
        $('.num-distintos').html(numDistintos - 1);
        $(event.currentTarget).parent().parent().fadeOut("slow"); //Ocultamos la fila actual
        $('#resultado-actualizar-modal .modal-title').html('Hemos actualizado el precio');
        $('#resultado-actualizar-modal .modal-body').html('<div class=""><img class="rounded mx-auto d-block" src="https://media1.tenor.com/images/ce038ac1010fa9514bb40d07c2dfed7b/tenor.gif?itemid=14797681"  style="width:300px" /></div>');
      } else {
        $('#resultado-actualizar-modal .modal-title').html('Ha chocado un accidente');
        $('#resultado-actualizar-modal .modal-body').html('<div class=""><img class="rounded mx-auto d-block mb-1" src="https://media1.tenor.com/images/eb39039fd8ef067bd70dcd4320e8741c/tenor.gif?itemid=11458685"  style="width:300px" /> Es posible que algo haya salido mal, no te voy a decir que no. <br /> Quizás deberías probar un poco más tarde, o quizás deberías comunicarlo a alguien, o actualizarlo a mano, o llamar a una asesoría, en fin, que se yo.</div>');

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

/**
 * Función que sube los precios seleccionados desde la tarifa a Magento y Odoo
 * @param {*} datos 
 */
function subePrecio(datos,plataforma) { 
  let copiaDatos = Object.assign({}, datos); //Copiamos el objeto
switch (plataforma) {
  case 'magento-2':
  case 'magento-2-beta': 
  datos.sku = datos.referencia;
  datos.precio_venta = datos.precio;
  datos.plataforma = plataforma;

url = "ajax/magento/actualizar-articulo.php";
    break;
    case 'odoo':
      //Actualizamos el product.product
      datos = {
        datos : {
          campo_busqueda : 'default_code',
          valor_antiguo : datos.referencia,
          campo_actualizar : 'fix_price',
          valor_nuevo : datos.precio,
          modelo : 'product.product'
        }
      }
     url =  "ajax/odoo/actualizar.php";
     //Lanzamos el resto de actualizaciones (plantilla y compras)
     actualizaRestoCampos(copiaDatos);     
     //Hacemos lo mismo con la plantilla
//datos.datos.modelo = 'product.template';
 //  url =  "ajax/odoo/actualizar.php";
break;
}

  $.ajax({
    type: "POST",
    url: url,
    data: datos,
    beforeSend: function() {
    },
    success: function(data, textStatus, jqXHR) {
      let estaP = document.querySelector('.plataforma[data-plataforma="'+plataforma+'"] i');
      estaP.classList.remove('fa-question','text-warning');
      if (data == '"ok"') {
        estaP.classList.add('fa-check', 'bg-success', 'text-white','p-1');
        if (plataforma == 'magento-2') {
          let fila = document.getElementById(datos.referencia);
          fila.classList.remove('text-danger'); //Ponemos la fila de nuevo en su color
          fila.querySelector('.precio-anterior').classList.add('visually-hidden'); //Ocultamos el precio anterior
          fila.querySelector('.acciones .sube-precio').classList.add('visually-hidden'); //Ocultamos el botón de subir precio
          cambiaContador(datos.referencia, -1); //Modificamos los contadores
        }
      } else { //Si no recibimos el ok mostramos el error
        estaP.classList.add('fa-check', 'bg-danger', 'text-white','p-1');
        let jsData = JSON.parse(data);
        if (jsData.message != undefined) {
          let spanT = document.createElement('span');
          spanT.classList.add('text-xs','text-gray-600','ml-1');
          spanT.innerHTML = '('+jsData.message+')';
          estaP.closest('.list-group-item').appendChild(spanT);
        }

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
function actualizaRestoCampos(datosRecibidos){
 //Primero la plantilla 
 datos = {
  datos : {
    campo_busqueda : 'default_code',
    valor_antiguo : datosRecibidos.referencia,
    campo_actualizar : 'list_price',
    valor_nuevo : datosRecibidos.precio,
    modelo : 'product.template'
  }
}

url =  "ajax/odoo/actualizar.php";
//Actualizamos 
$.ajax({
  type: "POST",
  url: url,
  data: datos,
  beforeSend: function() {
  },
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

//Analizamos las compras
let compras = datosRecibidos.compras;
let da = {
  modelo : 'product.product',
  campo : 'default_code',
  valor: datosRecibidos.referencia
}
let artOdoo = llamadaJson('ajax/odoo/busqueda.php',da);             //Cargamos la tarifa de inforpor
//Pedimos las compras que ya tenemos para ese artículo en odoo
console.log(compras);
console.log(artOdoo);
/*compras.forEach(c => {
  console.log(c);
  if (c.precio > 0) {
  var d = {
    datos : {
      'currency_id' : 0,
      'delay' : 0,
      'min_qty' : 1,
      'name' : c.proveedor,
      'price' : c.precio
    }
  }
}
  url =  "ajax/odoo/crear.php";

//Actualizamos 
$.ajax({
  type: "POST",
  url: url,
  data: d,
  beforeSend: function() {
  },
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


})*/

}


//Actualización
function colocaT(miT) {
  let titulos = document.getElementById('v-pills-tab'); //Barra con los títulos
  let contenidos = document.getElementById('v-pills-tabContent'); //Barra con los títulos
  let n = 0; //un contador para activar la primera entrada
  for (const i in miT) { //Recorremos la tarifa
 //BOTONES   
let btn = document.createElement('button'); //Creamos el elemento con el botón
let iStrip = i.toLowerCase(); //Ponemos el nombre en minúsculas
btn.classList.add('nav-link','position-relative'); //Añadimos la clase
btn.id = 'v-pills-'+iStrip+'-tab'; //Añadimos la id
btn.setAttribute('data-bs-toggle','pill');
btn.setAttribute('data-bs-target','#v-pills-'+iStrip);
btn.type = 'button';
btn.role = 'tab';
btn.setAttribute('aria-controls', 'v-pills-'+iStrip);
btn.setAttribute('aria-selected', 'false');
btn.innerHTML = i;
titulos.appendChild(btn);

//Añadimos las alertas para los cambios
let spanAlerts = document.createElement('span');
spanAlerts.classList.add('position-absolute','top-0','start-100','translate-middle','badge','rounded-pill','bg-danger','contador-hoja','visually-hidden');
spanAlerts.innerHTML = '0';
btn.appendChild(spanAlerts);

//Span con el texto informativo
//let spanInfo = document.createElement('span');
//spanInfo.classList.add('visually-hidden');
//spanInfo.innerHTML = 'precios distintos';
//spanAlerts.appendChild(spanInfo);

//CONTENEDORES
let ctn = document.createElement('div');
ctn.classList.add('tab-pane','fade');
ctn.id = 'v-pills-'+iStrip;
ctn.role = 'tabpanel';
ctn.setAttribute('aria-labelledby','v-pills-'+iStrip+'-tab');
ctn.tabindex = '0';

contenidos.appendChild(ctn);

if (n == 0) { //Activamos el primer elemento
  btn.classList.add('active');
  btn.setAttribute('aria-selected', 'true');
  ctn.classList.add('show','active');
}
//ctn.innerHTML = JSON.stringify(miT[i]);
n++;

let hoja = miT[i];
//if (hoja.length > 0) {
let acco = document.createElement('div');
acco.classList.add('accordion');
acco.id = 'accordion-'+ iStrip;
ctn.appendChild(acco);

for (const a in hoja) {
  let aStrip = a.toLowerCase().split(" ").join("");
  let divA = document.createElement('div'); //Cada bloque
  divA.classList.add('accordion-item');
  //divA.innerHTML = a;
  acco.appendChild(divA);
  //H2 con el header
  let h2A = document.createElement('h2');
  h2A.classList.add('accordion-header');
  h2A.id = 'heading-'+aStrip;
  divA.appendChild(h2A);
  //Botón
  let btnH2A = document.createElement('button');
  btnH2A.classList.add('accordion-button','collapsed');
  btnH2A.type = 'button';
  btnH2A.setAttribute('data-bs-toggle','collapse');
  btnH2A.setAttribute('data-bs-target','#collapse-'+aStrip);
  btnH2A.setAttribute('aria-expanded','false');
  btnH2A.setAttribute('aria-controls','collapse-'+aStrip);
  btnH2A.innerHTML = a;
  h2A.appendChild(btnH2A);

  let spanBtnAcco = document.createElement('span');
  spanBtnAcco.classList.add('badge','text-bg-danger','ml-1','rounded-pill','contador-seccion','visually-hidden');
  spanBtnAcco.innerHTML = '0';
  btnH2A.appendChild(spanBtnAcco);

let cuerpoAcc = document.createElement('div');
cuerpoAcc.id = 'collapse-'+aStrip;
cuerpoAcc.classList.add('accordion-collapse', 'collapse');
cuerpoAcc.setAttribute('aria-labelledby', 'heading-'+aStrip);
//cuerpoAcc.setAttribute('data-bs-parent', '#accordion-'+ iStrip);
divA.appendChild(cuerpoAcc);

let divCuerpo = document.createElement('div');
cuerpoAcc.appendChild(divCuerpo);

let art = hoja[a];
for (const b in art) {
  let cardMaterial = document.createElement('div');
  cardMaterial.classList.add('card', 'm-3');
  divCuerpo.appendChild(cardMaterial);

/**
 * 
 * <a href="#" class="btn btn-info btn-icon-split btn-sm float-end">
                    <span class="icon text-white-50">
                      <i class="fa-solid fa-arrow-up-from-bracket sube-precio" aria-hidden="true"></i>
                    </span>
                    <span class="text">Subir todos</span>
                  </a>
 * 
 */
  //Colocamos el título de cada sección
  let cardHeader = document.createElement('div');
  cardHeader.classList.add('card-header');
  cardHeader.innerHTML = b;
  cardMaterial.appendChild(cardHeader);
//El span con el contador
  let spanBtnCard = document.createElement('span');
  spanBtnCard.classList.add('badge','text-bg-danger','ml-1','rounded-pill', 'contador-material','visually-hidden');
  spanBtnCard.innerHTML = '0';
  cardHeader.appendChild(spanBtnCard);
//El botón de subir todos los precios
let btnSubePrecios = document.createElement('button');
btnSubePrecios.classList.add('btn','btn-info', 'btn-icon-split', 'btn-sm', 'float-end', 'visually-hidden','btn-subir-todos');
cardHeader.appendChild(btnSubePrecios);
//Span con el icono
let spanIco = document.createElement('span');
spanIco.classList.add('icon','text-white-50');
btnSubePrecios.appendChild(spanIco);
//el icono en si
let icoSube = document.createElement('i');
icoSube.classList.add('fa-solid','fa-arrow-up-from-bracket','sube-precio');
icoSube.setAttribute('aria-hidden','true');
spanIco.appendChild(icoSube);
//El texto del botón
let txtBtnSube = document.createElement('span');
txtBtnSube.classList.add('text');
txtBtnSube.innerHTML = 'Subir todos';
btnSubePrecios.appendChild(txtBtnSube);

  let cardBody = document.createElement('div');
  cardBody.classList.add('card-body');
  cardMaterial.appendChild(cardBody);


  let tablaMedidas = document.createElement('table'); //Tabla
  tablaMedidas.classList.add('table','table-sm');
  let theaderTablaMedidas = document.createElement('thead'); //Header
  let trHead = document.createElement('tr');
  let hRefe = document.createElement('th');
  hRefe.scope = 'col';
  hRefe.innerHTML = 'referencia';

  let hNombre = document.createElement('th');
  hNombre.scope = 'col';
  hNombre.innerHTML = 'descripción';


  let hPrecio= document.createElement('th');
  hPrecio.scope = 'col';
  hPrecio.classList.add('precio-anterior','visually-hidden');
  hPrecio.innerHTML = 'Precio anterior';

  let nPrecio= document.createElement('th');
  nPrecio.scope = 'col';
  nPrecio.innerHTML = 'Precio';
  let nAcciones= document.createElement('th');
  nAcciones.scope = 'col';
  nAcciones.innerHTML = 'Acciones';

  cardBody.appendChild(tablaMedidas);
  tablaMedidas.appendChild(theaderTablaMedidas);
  theaderTablaMedidas.appendChild(trHead);
  trHead.appendChild(hRefe);
  trHead.appendChild(hNombre);
  trHead.appendChild(hPrecio);
  trHead.appendChild(nPrecio);
  trHead.appendChild(nAcciones);


  let medida = art[b];
 // console.log(Object.values(medida));
 let bodyTabla = document.createElement('tbody');
 bodyTabla.classList.add('medidas');
 tablaMedidas.appendChild(bodyTabla);
  for (const md of Object.values(medida)) {
    let trMd = document.createElement('tr'); //Creamos una fila
    trMd.id = md.referencia;
    bodyTabla.appendChild(trMd);

let tReferencia = document.createElement('td');
tReferencia.classList.add('referencia');
tReferencia.innerHTML = md.referencia;
trMd.appendChild(tReferencia);

let tNombre = document.createElement('td');
tNombre.classList.add('nombre');
tNombre.innerHTML = md.descripcion;
trMd.appendChild(tNombre);

let tnPrecio = document.createElement('td');
tnPrecio.classList.add('precio-anterior', 'visually-hidden');
tnPrecio.innerHTML = accounting.formatMoney(0, { symbol: "€", format: "%v %s" });
trMd.appendChild(tnPrecio);


let tPrecio = document.createElement('td');
tPrecio.classList.add('precio');
tPrecio.innerHTML = accounting.formatMoney(md.precio, { symbol: "€", format: "%v %s" });
trMd.appendChild(tPrecio);

let tAcciones = document.createElement('td');
tAcciones.classList.add('acciones','visually-hidden');
tAcciones.innerHTML = '<i class="fa-solid fa-arrow-up-from-bracket sube-precio" style="cursor: pointer" data-bs-toggle="modal" data-bs-target="#actualizar-modal"></i>';
tAcciones.querySelector('i').setAttribute('data-bs-whatever',JSON.stringify(md).replace(/[\/\(\)\']/g, "&apos;"));
trMd.appendChild(tAcciones);

//console.log(md);
  }
  //cardBody.innerHTML = JSON.stringify(art[b]);
 // console.log(b);
 // console.log(art[b]);
}

}
//}

  }

  /*let btnSubePrecio = document.querySelectorAll('.sube-precio');
  btnSubePrecio.forEach(i => {
i.addEventListener('click', (e) => {
  console.log(e);
})
  });*/
  let modalActualizar = document.getElementById('actualizar-modal');
  modalActualizar.addEventListener('show.bs.modal', function (event){
    let datosArticulo = JSON.parse(event.relatedTarget.getAttribute('data-bs-whatever'));
let plataformas = document.querySelectorAll('.plataforma');

plataformas.forEach(plataforma => {
 subePrecio(datosArticulo,plataforma.getAttribute('data-plataforma')); 
});

  });

  modalActualizar.addEventListener('hide.bs.modal', function (event){ //Al cerrar el modal
let plataformas = document.querySelectorAll('.plataforma i');
plataformas.forEach(plataforma => {
  plataforma.classList.remove('bg-success','text-white','p1','fa-check');
  plataforma.classList.add('text-warning','fa-question');
  plataforma.closest('.list-group').querySelector('span').remove();
});
  });
  //Botón subir todos los precios de esa sección
  let btnSubirTodos = document.querySelectorAll('.btn-subir-todos');
  btnSubirTodos.forEach(btn => {
    btn.addEventListener('click', e => {
      let tarjeta = e.target.closest('.card');
      let tabla = tarjeta.querySelectorAll('tr.text-danger');
      tabla.forEach(tr => { //Recorremos cada fila
let datosArticulo = JSON.parse(tr.querySelector('.sube-precio').getAttribute('data-bs-whatever'));
let plataformas = document.querySelectorAll('.plataforma');
console.log('plataforma');
console.log(plataforma);
plataformas.forEach(plataforma => {
  subePrecio(datosArticulo,plataforma.getAttribute('data-plataforma')); 
 });
      });
 //     console.log('filas');
  //    console.log(tabla);
    })
  });
  

  return miT;
}


function startWorker() {
  if (typeof(Worker) !== "undefined") {
    if (typeof(w) == "undefined") {
      w = new Worker("js/workers/buscar-tarifa-en-magento.js");
    }
    w.postMessage(''); //pasa
    w.onmessage = function(event) {
  //    document.getElementById("result").innerHTML = event.data;
  console.log(event.data);
    };
  } else {
 //   document.getElementById("result").innerHTML = "Sorry! No Web Worker support.";
    console.log("Sorry! No Web Worker support.");
  }
}

function stopWorker() {
  w.terminate();
  w = undefined;
}

function comparaMages(){
  fetch('./var/import/magento2.json') //Cargamos la tarifa de magento 2.4
 .then((response) => response.json())
 .then((tarifaMage) => {
  colocaTMage(tarifaMage);
  return tarifaMage;
 }).then(tarifaMage => buscarMageWorker(tarifaMage));
}

function colocaTMage(tarifaMage){
  rGoogle.classList.add('visually-hidden'); //Ocultamos la tarifa de google
  rMage.classList.remove('visually-hidden'); //Mostramos la fila donde vamos a poner la de magento
  let ulP = rMage.getElementsByTagName('ul');
  tarifaMage.forEach( i => {
   let li = document.createElement('li');
    li.innerHTML = '['+i.sku+'] '+i.name;
    li.id = i.sku;
    li.classList.add('list-group-item','visually-hidden');
    ulP[0].appendChild(li);
    let spans = document.createElement('span');
    spans.className = 'float-end';
    li.appendChild(spans);
    let spanPrice = document.createElement('span');
    spanPrice.innerHTML = i.price + '€';
    spanPrice.classList.add('mx-2');
    spans.appendChild(spanPrice);
    let flecha = document.createElement('i');
    flecha.className = 'fa-solid fa-right-long text-info mx-2';
    spans.appendChild(flecha);
    let spanNewPrice = document.createElement('span');
    //spanNewPrice.innerHTML = i.price + '€';
    spanNewPrice.classList.add('precio-nuevo','mx-2');
    spans.appendChild(spanNewPrice);
    let btn = document.createElement('button');
    btn.className = 'btn btn-primary btn-sm mx-2 btn-mage-mage';
    btn.dataset.sku = i.sku;
    btn.innerHTML = 'dale';
    spans.appendChild(btn);
  });
  console.log(ulP[0]);
}

function buscarMageWorker(tarifaMage){
if (typeof(Worker) !== "undefined") {
  if (typeof(w) == "undefined") {
    w = new Worker("js/workers/comparar-mage-mage.js");
  }
  w.postMessage(tarifaMage); //pasamos la tarifa al worker
  w.onmessage = function(event) {
    let articulo = event.data;
    fila = document.getElementById(articulo.sku);
    fila.classList.remove('visually-hidden'); //Mostramos solo en las que no coincide el precio
    fila.querySelector('.precio-nuevo').innerHTML = articulo.price + '€';
    fila.querySelector('button').dataset.precio = articulo.price;
  }
}
}
function buscarArticuloWorker() {
  document.getElementById('collapse-precios').classList.add('visually-hidden');
tarifaGoogle.then((t) => { //Resolvemos la promise
 if (typeof(Worker) !== "undefined") {
    if (typeof(w) == "undefined") {
      w = new Worker("js/workers/buscar-articulo-en-magento.js");
    }
    w.postMessage(t); //pasamos la tarifa al worker
    w.onmessage = function(event) {
      const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl)) // Inicializamos los tooltips
//console.log(tooltipList);
  //    document.getElementById("result").innerHTML = event.data;
 // console.log(event.data);
  let articulo = event.data;
  let fila;
if (articulo.hasOwnProperty('extension_attributes')) { //Si tiene la propiedad extension attributes es que es un articulo de magento
  fila = document.getElementById(articulo.sku);
  fila.classList.add('text-danger'); //Ponemos el texto en rojo
  //Vamos a crear el contenido de la columna precio anterior
  let precioActual = fila.querySelector('.precio').innerHTML.slice(0,-2);
  let precioAnterior = articulo.price;
  let precioAntFormateado = accounting.formatMoney(precioAnterior, { symbol: "€", format: "%v %s" });
  let dif = ((precioActual / precioAnterior) - 1) * 100;
  let icoDif = dif > 0 ? '<i class="fa-solid fa-angle-up text-danger"></i> ': '<i class="fa-solid fa-angle-down text-success"></i> ';
  fila.querySelector('.precio-anterior').innerHTML = precioAntFormateado + '<span>('+ icoDif + Math.abs(dif).toFixed(2) + '%)</span>'; //Colocamos el precio que hemos recibido de Magento como precio anterior y la diferencia
  fila.querySelector('.precio-anterior').classList.remove('visually-hidden');
  fila.querySelector('.acciones').classList.remove('visually-hidden');
cambiaContador(articulo.sku, 1); //Modificamos los contadores
} else { //Si no la tiene es un artículo de tarifa que no hemos encontrado en el fichero diario
  fila = document.getElementById(articulo.referencia);
let span = document.createElement('span');
let i1 = document.createElement('i');
let i2 = document.createElement('i');

span.classList.add('fa-stack');
i1.classList.add('fa-solid','fa-ban','fa-stack-2x','text-gray-500');
i2.classList.add('fa-brands','fa-magento','fa-stack-1x','text-danger');

span.appendChild(i1);
span.appendChild(i2);
 // let icono = '<span class="fa-stack"><i class="fa-solid fa-ban fa-stack-2x text-gray-500" style=""></i><i class="fa-brands fa-magento fa-stack-1x text-danger"></i></span>';
  fila.querySelector('.referencia').prepend(span);
}
    };
  } else {
 //   document.getElementById("result").innerHTML = "Sorry! No Web Worker support.";
    console.log("Sorry! No Web Worker support.");
  }
  });

}



function cambiaContador(sku, cifra){
  let fila = document.getElementById(sku);
  let ancestro = fila.closest('.card'); //Buscamos la tarjeta con el título del material
  let btnSubeTodo = ancestro.querySelector('button');
  let ancestroTabla = fila.closest('table');
  let spanContador = ancestro.querySelector('span'); //Span con el contador del material
  let ancestroSeccion = fila.closest('.accordion-item'); //Buscamos el accordeon de la sección
  let h2Seccion = ancestroSeccion.querySelector('span'); //Span con el contador de la sección
let tabPane = fila.closest('.tab-pane');
let idTab = tabPane.id; //El id de la pestaña actual
let rowMadre = fila.closest('.row');
let esteBtn = rowMadre.querySelector('[aria-controls="'+idTab+'"]');
let spanHoja =esteBtn.querySelector('span'); 

ancestroTabla.querySelector('th.precio-anterior').classList.remove('visually-hidden'); //Mostramos la columna precio anterior
if (spanContador.innerHTML >= 1) {
  btnSubeTodo.classList.remove('visually-hidden');
}

//console.log(esteBtn);
  cambiaCifra(spanContador,cifra);
  cambiaCifra(h2Seccion,cifra);
  cambiaCifra(spanHoja,cifra);

}

function cambiaCifra(bloque, cifra){
  let valorSpan = parseInt(bloque.innerHTML);
  bloque.innerHTML = valorSpan + cifra;
  let valorSpanActualizado = parseInt(bloque.innerHTML);
  if (valorSpanActualizado > 0) {
    bloque.classList.remove('visually-hidden');
  }
}




/******************************************************************************/
// FUNCIONES
/******************************************************************************/
//GENERALES---------------------------------------------------------------------
/**
 * Función que coloca la barra de progreso como completado
 *
 */
function barraProgresoCompleta() {
  $(".progress-bar")
    .css("width", "100%")
    .html("Completado")
    .removeClass("bg-info")
    .addClass("bg-success");
}
/**
 * Función que coloca la barra de progreso como Alta (2/3 del total)
 *
 */
function barraProgresoAlta() {
  $(".progress-bar").css("width", "66%").html("Alta cliente");
}

/*function refPedidoClienteInforpor(referenciaPed, plataforma) {
  let referencia;
  switch (plataforma) {
    case 'pcc':
      referencia = 'PC' + referenciaPed.substring(0, referenciaPed.length - 2);
      break;
    case 'mage':
      referencia = referenciaPed;
      break;

  }

  return referencia;
}*/

function adivinaTipoPedido(objpedidoVenta, plataforma) {
  let selectTipo = document.getElementById("elige-tipo-pedido");
  let valor = 1;
  if (plataforma == "pcc") {
    //Si es de pc componentes ponemos su diario
    valor = 7;
    selectTipo.querySelector('[value="7"]').selected = true;
  } else if (plataforma == "mage") {
    let idPedido = objpedidoVenta.id;
    let comienzo = idPedido.substring(3, 0);
    switch (comienzo) {
      case "TP0":
        valor = 4;
        break;
      case "TSO":
        valor = 3;
        break;
      case "TSU":
        valor = 5;
        break;
      case "FUT":
        valor = 6;
        break;
    }
  }
  selectTipo.querySelector('[value="' + valor + '"]').selected = true;
}
function modalCrearCliente(event) {
  var objPedido = JSON.parse(
    document.getElementById("modal-pedido").getAttribute("data-bs-whatever")
  ); //Capturamos el objeto pedido
  //console.log('crearClienteP');
  //console.log(objPedido);
  // Button that triggered the modal
  var button = event.relatedTarget;
  // Extract info from data-bs-* attributes
  var estePedido = button.getAttribute("data-bs-whatever");
  //var objPedido = JSON.parse(estePedido)                                       //Convertimos la cadena en un objeto
  barraProgresoAlta(); //Cambiamos la barra de progreso
  var modalTitle = document
    .getElementById("comprueba-cliente-modal")
    .querySelector(".modal-title");
  var dir_fra = objPedido.direccion_factura;
  var dir_env = objPedido.direccion_envio;
  var idPadre = document.getElementById("modal-pedido").hasAttribute("id-padre")
    ? document.getElementById("modal-pedido").getAttribute("id-padre")
    : false;
  let dir = idPadre !== false ? dir_env : dir_fra; //Si recibimos la id padre estamos creando la de envío, si no la de factura
  //console.log('dir');
  //console.log(dir);
  let nombreCompleto = dir.nombre_completo;
  let email = dir.email;
  let nif = dir.nif.trim();
  let nombreEmpresa = dir.empresa;
  let telefono = dir.telefono;
  let direccion = dir.direccion;
  let codigo_postal = dir.codigo_postal;
  let ciudad = dir.ciudad;
  let provincia = dir.provincia;
  let compruebaClienteCuerpo = document.querySelector(
    ".comprueba-cliente-cuerpo"
  );
  //Validamos el nif
  let tipoNif = validarNifCif(nif);

  if (tipoNif === "cif" && nombreEmpresa !== null) {
    let nSwitch = nombreCompleto;
    nombreCompleto = nombreEmpresa;
    nombreEmpresa = nSwitch;
  }
  //console.log('tipoNif');
  //console.log(tipoNif);

  modalTitle.textContent = "Comprobando " + nombreCompleto; //Fijamos la acción y el nombre como título del modal
  let rowCCC = document.createElement("div");
  rowCCC.classList.add("row");
  compruebaClienteCuerpo.appendChild(rowCCC);
  let colSm = document.createElement("div");
  colSm.classList.add("col-sm");
  colSm.id = "col-fra";
  rowCCC.appendChild(colSm);
  let card = document.createElement("div");
  card.classList.add("card");
  colSm.appendChild(card);
  let cardHeader = document.createElement("div");
  cardHeader.classList.add("card-header");
  cardHeader.innerHTML = "Contacto";
  card.appendChild(cardHeader);
  let cardBody = document.createElement("div");
  cardBody.classList.add("card-body");
  card.appendChild(cardBody);
  let cardTitle = document.createElement("h5");
  cardTitle.classList.add("card-title", "editable-text");
  cardTitle.innerHTML = nombreCompleto;
  cardTitle.id = "fra-name";
  cardBody.appendChild(cardTitle);
  let cardEmpresa = document.createElement("h6");
  //cardEmpresa.classList.add('card-title', 'editable-text');
  cardEmpresa.innerHTML = nombreEmpresa;
  //cardEmpresa.id = 'fra-name';
  cardBody.appendChild(cardEmpresa);
  let cardPhone = document.createElement("div");
  cardPhone.classList.add("card-title", "editable-text");
  cardPhone.innerHTML = telefono;
  cardPhone.id = "fra-phone";
  cardBody.appendChild(cardPhone);
  let cardEmail = document.createElement("div");
  cardEmail.classList.add("card-title", "editable-text");
  cardEmail.innerHTML = email;
  cardEmail.id = "fra-email";
  cardBody.appendChild(cardEmail);

  let bloqueNif = document.createElement("div");
  bloqueNif.classList.add("card-title");
  cardBody.appendChild(bloqueNif);
  let cardNif = document.createElement("span");
  cardNif.classList.add("editable-text");
  cardNif.innerHTML = nif;
  cardNif.id = "fra-vat";
  bloqueNif.appendChild(cardNif);
  let validaNif = document.createElement("span");
  validaNif.classList.add(
    "text-warning",
    "fs-6",
    "float-end",
    "visually-hidden"
  );
  bloqueNif.appendChild(validaNif);




  let cardDireccion = document.createElement("div");
  cardDireccion.classList.add("card-title", "editable-text");
  cardDireccion.innerHTML = direccion;
  cardDireccion.id = "fra-street";
  cardBody.appendChild(cardDireccion);

  let cardRestoDir = document.createElement("div");
  let cardCp = document.createElement("span");
  let cardSeparaCP = document.createElement("span");
  let cardCiudad = document.createElement("span");
  let abreParentesis = document.createElement("span");
  let cardProvincia = document.createElement("span");
  let cierraParentesis = document.createElement("span");
  cardCp.innerHTML = codigo_postal;
  cardCp.id = "fra-zip";
  cardCp.classList.add("editable-text");
  cardSeparaCP.innerHTML = " - ";
  cardCiudad.innerHTML = ciudad;
  cardCiudad.id = "fra-city";
  cardCiudad.classList.add("editable-text");
  abreParentesis.innerHTML = " (";
  cardProvincia.innerHTML = provincia;
  cardProvincia.id = "fra-state";
  cardProvincia.classList.add("editable-text");
  cierraParentesis.innerHTML = " (";
  cardRestoDir.appendChild(cardCp);
  cardRestoDir.appendChild(cardSeparaCP);
  cardRestoDir.appendChild(cardCiudad);
  cardRestoDir.appendChild(abreParentesis);
  cardRestoDir.appendChild(cardProvincia);
  cardRestoDir.appendChild(cierraParentesis);
  cardBody.appendChild(cardRestoDir);

  let parrafo = document.createElement("p");
  parrafo.classList.add("mt-2");
  parrafo.innerHTML =
    "Revisa los datos y edita los que sean necesarios. Cuando hayas terminado haz click en el botón crear";
  compruebaClienteCuerpo.appendChild(parrafo);

  let gridPad = document.createElement("div");
  gridPad.classList.add("d-grid", "gap-2");
  compruebaClienteCuerpo.appendChild(gridPad);
  let btnCrear = document.createElement("button");
  btnCrear.type = "button";
  btnCrear.classList.add("btn", "btn-primary");
  btnCrear.id = "crear-cliente";
  btnCrear.innerHTML = "Crear";
  gridPad.appendChild(btnCrear);

  if (tipoNif !== "nif correcto" && tipoNif !== "cif") {
    validaNif.classList.remove("visually-hidden");
    validaNif.innerHTML = tipoNif;
    btnCrear.classList.add("disabled");
  }


  // let fra_nombre_completo = typeof dir_fra.nombre_completo !== 'undefined' ? dir_fra.nombre_completo : '';
  // let fra_email = dir_fra.email.length > 0 ? dir_fra.email : '-';                 //Si tenemos el email lo pasamos, si no lo tenemos pasamos un guión que eliminaremos al crear la ficha
  //let nif = objPedido.nif;

  // $('.comprueba-cliente-cuerpo').html('<row><div class="col-sm" id="col-fra"><div class="card"><div class="card-header">Factura</div><div class="card-body"><h5 class="card-title editable-text" id="fra-name">' + fra_nombre_completo + '</h5><h6>' + dir_fra.empresa + '</h6><div class="card-text editable-text" id="fra-phone">' + dir_fra.telefono + '</div><div class="card-text editable-text" id="fra-email">' + fra_email + '</div><div class="card-text editable-text" id="fra-vat">' + objPedido.nif + '</div><div class="card-text editable-text" id="fra-street">' + dir_fra.direccion + '</div> <div class="card-text"><span class="editable-text" id="fra-zip">' + dir_fra.codigo_postal + '</span> - <span class="editable-text" id="fra-city">' + dir_fra.ciudad + '</span> (<span class="editable-text" id="fra-state">' + dir_fra.provincia + '</span>)</div></div></div></div><div class="col-sm mt-2" id="col-env" style="display: none"><div class="card"><div class="card-header">Envío</div><div class="card-body"><h5 class="card-title" id="dir-name">' + dir_env.firstname + ' ' + dir_env.lastname + '</h5><div class="card-text editable-text" id="dir-phone">' + dir_env.phone + '</div><div class="card-text editable-text" id="dir-street">' + dir_env.street_1 + '</div><div class="card-text" ><span class="editable-text" id="dir-zip">' + dir_env.zip_code + '</span> - <span class="editable-text" id="dir-city">' + dir_env.city + '</span>(<span class="editable-text" id="dir-state">' + dir_env.state + '</span>)</div></div></div></div></div></row><p class="mt-2">Revisa los datos y edita los que sean necesarios. Cuando hayas terminado haz click en el botón crear</p><div class="d-grid gap-2"><button type="button" class="btn btn-primary" id="crear-cliente">Crear</button></div>');                                       //Vaciamos el cuerpo de la ventana modal
  $(".editable-text").editable("ajax/odoo/jeditable-cliente.php", {
    callback: function (e, d, a) {
      //Llamada cuando editamos el campo
      let valor = a.value;
      let id = a.id;
      if (id == "fra-vat") {
        let check = validarNifCif(valor);
        document.getElementById(id).nextSibling.innerHTML = check; //Colocamos el texto respuesta
        if (check == "nif correcto" || check == "cif") {
          //Si es correcto lo ponemos en verde y activamos el botón
          document
            .getElementById(id)
            .nextSibling.classList.remove("text-warning");
          document.getElementById(id).nextSibling.classList.add("text-success");
          document.getElementById("crear-cliente").classList.remove("disabled"); //Activamos el botón
        }
      }
    },
  }); //Hacemos los campos editables
  //modalTitle.textContent = 'Alta ' + objPedido.nombre_apellidos;                //Cambiamos el título de la ventana modal
  //Comparamos las direcciones

  /*let misma_dir = dir_fra.firstname === dir_env.firstname && dir_fra.lastname === dir_env.lastname && dir_fra.phone === dir_env.phone && dir_fra.street_1 === dir_env.street_1 ? 1 : 0;
  if (misma_dir === 0) {                                                        //Si la dirección de envío y de factura no es la misma
    $('#col-env').show();                                                           //Mostramos la columna con el envío
  }*/
  $(".comprueba-cliente-loader").hide();
  $(".comprueba-cliente-cuerpo").show();

  document.getElementById("crear-cliente").addEventListener("click", (e) => {
    let contacto = {
      name: document.getElementById("fra-name").innerHTML,
      street: document.getElementById("fra-street").innerHTML,
      vat: document.getElementById("fra-vat").innerHTML,
      email: document.getElementById("fra-email").innerHTML,
      mobile: document.getElementById("fra-phone").innerHTML,
      zip: document.getElementById("fra-zip").innerHTML,
      city: document.getElementById("fra-city").innerHTML,
      state: document.getElementById("fra-state").innerHTML,
      parent_id: idPadre,
    };
    crearCliente(contacto);
  });

  return objPedido;
}

async function dameUltimosPedidos(plataforma) {
  //document.getElementById('plataforma-pedidos').innerText = plataforma;
  let pedidos;
  switch (plataforma) {
    case "pcc":
      pedidos = dameListadoJson("ultimos pedidos mirakl"); //Recuperamos los úlitmos 100 pedidos
      break;
    case "phh":
      pedidos = dameListadoJson("ultimos pedidos Phone House"); //Recuperamos los úlitmos 100 pedidos
      break;
    case "mage":
      pedidos = dameListadoJson("ultimos pedidos magento"); //Recuperamos los úlitmos 100 pedidos
      break;
    case "odoo":
      pedidos = dameListadoJson("pedidos odoo"); //Recuperamos los úlitmos 100 pedidos
      break;
      case "inforpor":
        pedidos = dameListadoJson("pedidos inforpor"); //Recuperamos los úlitmos 100 pedidos
        break;
  }
  console.log(pedidos);
  return pedidos;
}

/**
 * Función que filtra y coloca los datos del pedido seleccionado en la ventana modal
 * @param {array} pedidos Array de objetos tipo pedido
 * @param {object} event Datos del click
 * @returns
 */
async function colocaDatosModal(pedidos, event) {
  // Button that triggered the modal
  const button = event.relatedTarget;
  // Extract info from data-bs-* attributes
  const idPedido = button.getAttribute("data-bs-whatever");
  let infoPedido = pedidos.filter((ped) => ped.id == idPedido)[0]; //Filtramos el listado de pedidos para quedarnos con el que hemos solicitado
  //console.log(infoPedido);
  if (plataforma == "odoo") {
    //Si la plataforma es odoo tenemos que ir pidiendo los datos porque no los recibimos del listado
    infoPedido = generaDatosmodalOdoo(infoPedido);
  }
  if (plataforma == "inforpor") {
    //Si la plataforma es odoo tenemos que ir pidiendo los datos porque no los recibimos del listado
    infoPedido = generaDatosmodalInforpor(infoPedido);
  }
  let lineas_pedido = infoPedido.lineas_pedido;
  let dirFactura = infoPedido.direccion_factura;
  let dirEnvio = infoPedido.direccion_envio;
  document.getElementById("modal-pedido").setAttribute(
    "data-bs-whatever",
    JSON.stringify(infoPedido)
      .replace(/[\/\(\)\']/g, "&apos;")
      .replace(/<DOUBLE_QUOTES_4594>/g, '"')
  );
  //   console.log('dirFactura');
  //  console.log(dirFactura);
  document.getElementById("ref-pedido").innerHTML = infoPedido.id;
  document.getElementById("fecha-pedido").innerHTML = infoPedido.fecha_creado;

 
  if (plataforma == 'mage') { //Configuramos el bloque estado pedido que vamos a mostrar
    estadoSiMage.classList.remove('visually-hidden');
   /* listaStatus.options.forEach( i => {
      console.log(i);
      if (infoPedido.estado == i.text) {
        i.selected = true;
      }
    })*/
    for (const opcionEstado of listaStatus.options) {
      if (infoPedido.estado == opcionEstado.text) {
        opcionEstado.selected = true;
      }
    }
    

  } else {
    estadoNoMage.classList.remove('visually-hidden');
    document.getElementById("estado-pedido").innerHTML = infoPedido.estado;
  }
  switch (infoPedido.tienda) {
    case "mirakl":
      fondo = "bg-naranja";
      etiqueta = "Pc componentes";
      break;

    default:
      fondo = "bg-white";
      etiqueta = infoPedido.tienda;
      break;
  }
  document.getElementById("tienda-pedido").classList.add(fondo);
  document.getElementById("tienda-pedido").innerHTML = etiqueta;

  //document.getElementById('nombre-factura-pedido').innerHTML = dirFactura.nombre_completo;
  //document.getElementById('empresa-factura-pedido').innerHTML = dirFactura.empresa;
  document.getElementById("email-factura-pedido").innerHTML = dirFactura.email;
  document.getElementById("tlfo-factura-pedido").innerHTML =
    dirFactura.telefono;
  //document.getElementById('nif-factura-pedido').innerHTML = infoPedido.nif;
  document.getElementById("nif-factura-pedido").innerHTML =
    dirFactura.hasOwnProperty("nif") &&
    dirFactura.nif != "" &&
    (validarNifCif(dirFactura.nif) == "cif" ||
      validarNifCif(dirFactura.nif) == "nif correcto")
      ? dirFactura.nif
      : infoPedido.nif;
  document.getElementById("direccion-factura-pedido").innerHTML =
    dirFactura.direccion;
  document.getElementById("cp-poblacion-factura-pedido").innerHTML =
    dirFactura.codigo_postal +
    " - " +
    dirFactura.ciudad +
    " (" +
    dirFactura.provincia +
    ")";

  if (dirEnvio.hasOwnProperty("empresa") && dirEnvio.empresa != "") {
    document.getElementById("empresa-envio-pedido").innerHTML =
      dirEnvio.empresa;
  }
  document.getElementById("nombre-envio-pedido").innerHTML =
    dirEnvio.nombre_completo;
  document.getElementById("email-envio-pedido").innerHTML = dirEnvio.email;
  document.getElementById("tlfo-envio-pedido").innerHTML = dirEnvio.telefono;
  document.getElementById("direccion-envio-pedido").innerHTML =
    dirEnvio.direccion;
  document.getElementById("cp-poblacion-envio-pedido").innerHTML =
    dirEnvio.codigo_postal +
    " - " +
    dirEnvio.ciudad +
    " (" +
    dirEnvio.provincia +
    ")";
  //  console.log(filtrado);
  document
    .getElementById("totalArticulos")
    .setAttribute("data-total", lineas_pedido.length);

  let checkVat =
    dirFactura.hasOwnProperty("nif") && dirFactura.nif != ""
      ? validarNifCif(dirFactura.nif)
      : validarNifCif(infoPedido.nif);
  if (checkVat == "cif") {
    document.getElementById("titulo-factura-pedido").innerHTML =
      dirFactura.empresa;
    document.getElementById("subtitulo-factura-pedido").innerHTML =
      dirFactura.nombre_completo;
  } else {
    document.getElementById("subtitulo-factura-pedido").innerHTML =
      dirFactura.empresa;
    document.getElementById("titulo-factura-pedido").innerHTML =
      dirFactura.nombre_completo;
  }
  //console.log(pedidos);
  //Colocamos las lineas del pedido
  lineas_pedido.forEach((linea) => {
    let tablaArts = document.getElementById("tablaArticulos");
    let tr = document.createElement("tr");
    let trAux = document.createElement("tr"); //Fila auxiliar con el resto del contenido
    let trIfp = document.createElement("tr");
    let tdAcc = document.createElement("td");
    let tdRef = document.createElement("td");
    let tdNombre = document.createElement("td");
    tablaArts.appendChild(tr);
    tablaArts.appendChild(trAux);
    //tablaArts.appendChild(trIfp);
    tr.appendChild(tdAcc);
    tr.appendChild(tdRef);
    tr.appendChild(tdNombre);
    if (linea.mpn === false) {
      //Si no tenemos el mpn es porque es un comentario
      tdAcc.classList.add("bg-white");
      tdRef.classList.add("bg-white");
      tdNombre.classList.add("bg-white", "text-info");
      tdNombre.colSpan = "6";
      tdNombre.innerHTML = "<em>" + linea.nombre + "</em>";
    } else {
      let tdCantidad = document.createElement("td");
      let tdImporteUd = document.createElement("td");
      let tdImporteTotal = document.createElement("td");
      let tdCheck = document.createElement("td");
      tr.appendChild(tdCantidad);
      tr.appendChild(tdImporteUd);
      tr.appendChild(tdImporteTotal);
      tr.appendChild(tdCheck);
      tr.id = linea.mpn;
      tdRef.innerHTML = linea.mpn;
      tdNombre.innerHTML = linea.nombre;
      tdCantidad.innerHTML = linea.cantidad;
      tdImporteUd.setAttribute("importe", linea.importe);
      tdImporteUd.classList.add("importe");
      tdImporteUd.innerHTML = accounting.formatMoney(linea.importe, {
        symbol: "€",
        format: "%v %s",
      });
      tdImporteTotal.innerHTML = accounting.formatMoney(
        linea.cantidad * linea.importe,
        { symbol: "€", format: "%v %s" }
      );
      tdCheck.innerHTML =
        '<span class="material-icons float-end fs-5 chkArticulo" data-bs-whatever=\'' +
        JSON.stringify(linea).replace(/[\/\(\)\']/g, "&apos;") +
        "'>help_outline</span>";
    }

    tdAcc.scope = "row";
    tdAcc.innerHTML =
      '<span class="material-icons mas-info-linea-articulo visually-hidden" style="cursor: pointer">add</span>';

    trAux.classList.add("visually-hidden", "fila-auxiliar"); //Ocultamos la fila auxiliar
    let tdAux = document.createElement("td"); //Celda que va a ocupar todo el ancho
    tdAux.colSpan = 7;
    trAux.appendChild(tdAux);

    let listGroupCompras = document.createElement("ul");
    listGroupCompras.classList.add(
      "list-group",
      "list-group-flush",
      "compras-articulo"
    );
    tdAux.appendChild(listGroupCompras);
  });

  let masInfoLineas = document.querySelectorAll(".mas-info-linea-articulo");
  masInfoLineas.forEach((element) => {
    element.addEventListener("click", (e) => {
      let esteTr = e.target.parentNode.parentNode;
      let icono = e.target.innerHTML;
      if (icono == "add") {
        esteTr.nextElementSibling.classList.remove("visually-hidden");
        //    esteTr.nextElementSibling.nextElementSibling.classList.remove('visually-hidden');
        e.target.innerHTML = "remove";
      } else {
        esteTr.nextElementSibling.classList.add("visually-hidden");
        //  esteTr.nextElementSibling.nextElementSibling.classList.add('visually-hidden');
        e.target.innerHTML = "add";
      }
    });
  });

  return infoPedido;
}



function limpiaDatosModal() {
  let datos = document.querySelectorAll(".dato-pedido"); //Limpiamos todos los campos de esta clase
  datos.forEach((dato) => {
    dato.innerHTML = "";
  });
  let tiendaPed = document.getElementById("tienda-pedido");

  tiendaPed.classList.remove(
    tiendaPed.classList.item(tiendaPed.classList.length - 1)
  ); //Eliminamos la última clase añadida (el color de fondo)

  document.getElementById("tablaArticulos").innerHTML = "";
  document.getElementById("columna-factura").classList.toggle("col-md-6");
  document.getElementById("columna-factura").classList.remove("col-md-12");
  document.getElementById("columna-envio").classList.remove("visually-hidden");

  pedVentaOdoo.innerHTML = "No existe";

  //document.getElementById('chkCliente').innerHTML = 'help_outline';
  document.getElementById("chkCliente").classList.remove();
  document.getElementById("chkCliente").classList.add();

  let clasesEliminar = [
    "text-success",
    "text-danger",
    "text-info",
    "fa-circle-question",
    "fa-user-check",
    "fa-regular",
    "fa-user-plus",
  ];
  let clasesAdd = ["fa-circle-notch", "fa-spin", "fa-solid"];
  clasesEliminar.forEach((a) => {
    document.getElementById("chkCliente").classList.remove(a);
    document.getElementById("chkClienteEnvio").classList.remove(a);
  });
  clasesAdd.forEach((a) => {
    document.getElementById("chkCliente").classList.add(a);
    document.getElementById("chkClienteEnvio").classList.add(a);
  });

  document.getElementById("totalArticulos").innerHTML = "help_outline";
  document
    .getElementById("totalArticulos")
    .classList.remove("text-success", "text-danger", "text-info");

  /*document.querySelectorAll('.chkArticulo').forEach( i => {
    i.querySelector('.chkArticulo').classList.remove('text-success', 'text-danger');
    i.querySelector('.chkArticulo span').innerHTML = 'help_outline';
  })
  document.getElementById('.chkArticulo').querySelector('.chkArticulo').innerHTML = 'task_alt';*/
  // <span class="material-icons float-end fs-5 text-success">task_alt</span>
  document
    .getElementById("no-hay-tracking")
    .classList.remove("visually-hidden");
  document.getElementById("si-hay-tracking").classList.add("visually-hidden");
  document
    .getElementById("mostrar-btn-sonreir")
    .classList.add("visually-hidden");
  rowCarritos.innerHTML = ""; //Quitamos la información de los carritos de los proveedores
  console.log("limpiado");
  //Quitamos la info de los pedidos de compra
  document.querySelectorAll('.ped-compra').forEach(i => {
    i.remove();
  });
}

function comprobaciones(pedido) {
  // console.log('pedido');
  //console.log(pedido);
  //validamos el dni
  let tipoVat = validarNifCif(pedido.nif.trim()); //Comprobamos el DNI
  //console.log(tipoVat);
  //Comprobamos si las direcciones son iguales
  //  if (JSON.stringify(pedido.direccion_envio) === JSON.stringify(pedido.direccion_factura)) { //Si son iguales mostramos solo la de facturación
  if (pedido.dir_envio_igual_factura === true) {
    document.getElementById("columna-factura").classList.remove("col-md-6");
    document.getElementById("columna-factura").classList.add("col-md-12");
    document.getElementById("columna-envio").classList.add("visually-hidden");
    document
      .getElementById("columna-envio")
      .setAttribute("igual-factura", "true");
  }
}

function colocaInfoPedidos(ventaOdoo, compraOdoo, pedIfp) {

  if (compraOdoo !== undefined && compraOdoo.length > 0) {
    //Colocamos la compra
    console.log("compraOdoo");
    console.log(compraOdoo);
    document.getElementById("ped-compra-odoo").innerHTML =
      '<a href="https://erp.futura.es/web#id=' +
      compraOdoo[0].id +
      '&model=purchase.order&menu_id=1330" target="_blank">' +
      compraOdoo[0].name +
      "</a>";
  }

}

function lineaCompra(dLinea) {
  //  console.log('linea');
  //  console.log(dLinea);
  if (
    dLinea.hasOwnProperty("precioCompra") === false ||
    dLinea.precioCompra > 0
  ) {
    let filaArt = document.getElementById(dLinea.datosArticulo.referencia);
    let precioVenta = filaArt.querySelector(".importe").getAttribute("importe"); //CApturamos el precio por unidad al que hemos vendido el artículo
    let liCompra = document.createElement("li"); //Añadimos un elemento a la lista
    liCompra.classList.add("list-group-item", "linea-compra");
    liCompra.id = dLinea.proveedor + "-" + dLinea.referencia;
    liCompra.setAttribute("data", JSON.stringify(dLinea));
    // liCompra.style.cursor = 'pointer';
    liCompra.setAttribute("type", "button");
    dLinea.ulCompras.appendChild(liCompra);
    let rowCompra = document.createElement("div");
    rowCompra.classList.add("d-flex", "align-items-center");
    liCompra.appendChild(rowCompra);
    let colImg = document.createElement("div");
    colImg.classList.add("col-2");
    rowCompra.appendChild(colImg);
    let imgProveedor = document.createElement("img");
    imgProveedor.setAttribute("width", "150");
    colImg.appendChild(imgProveedor);
    imgProveedor.src = dLinea.urlImg;
    imgProveedor.alt = dLinea.altImg;

    //Código del proveddor
    let colCod = document.createElement("div");
    colCod.classList.add("col-2");
    //   colCod.innerHTML = 'código: ' + dLinea.codigoProveedor;
    rowCompra.appendChild(colCod);
    let rowCod = document.createElement("div");
    rowCod.classList.add("row", "text-gray-500");
    colCod.appendChild(rowCod);
    let icoCod = document.createElement("div");
    icoCod.classList.add("col-4");
    icoCod.innerHTML = '<i class="fa-solid fa-id-card-clip fa-2x"></i>';
    rowCod.appendChild(icoCod);
    let txtCod = document.createElement("div");
    txtCod.classList.add("col-8");
    txtCod.innerHTML = "referencia<br>" + dLinea.codigoProveedor;
    rowCod.appendChild(txtCod);
    // console.log('linea');
    // console.log(dLinea);

    if (
      dLinea.hasOwnProperty("idCustodia") === false &&
      dLinea.hasOwnProperty("reserva") === false
    ) {
      //Precio de compra
      let colCoste = document.createElement("div");
      colCoste.classList.add("col-2");
      rowCompra.appendChild(colCoste);
      let rowCoste = document.createElement("div");
      rowCoste.classList.add("row", "text-gray-400");
      rowCoste.style.alignItems = "center";
      colCoste.appendChild(rowCoste);
      let icoCoste = document.createElement("div");
      icoCoste.classList.add("col-4");
      icoCoste.innerHTML =
        '<i class="fa-solid fa-circle-dollar-to-slot fa-2x"></i>';
      rowCoste.appendChild(icoCoste);
      let txtCoste = document.createElement("div");
      txtCoste.classList.add("col-8");
      txtCoste.innerHTML =
        "compra<br>" +
        accounting.formatMoney(dLinea.precioCompra, {
          symbol: "€",
          format: "%v %s",
        });
      if (dLinea.lpi > 0) {
        txtCoste.innerHTML +=
          " + " +
          accounting.formatMoney(dLinea.lpi, { symbol: "€", format: "%v %s" }) +
          " lpi";
      }
      console.log(txtCoste);
      rowCoste.appendChild(txtCoste);

      //Margen
      let margen = (precioVenta / dLinea.precioCompra + dLinea.lpi - 1) * 100;
      let colMargen = document.createElement("div");
      colMargen.classList.add("col-3");
      //   colCod.innerHTML = 'código: ' + dLinea.codigoProveedor;
      rowCompra.appendChild(colMargen);
      let rowMargen = document.createElement("div");
      rowMargen.classList.add("row", "text-gray-500");
      colMargen.appendChild(rowMargen);
      let icoMargen = document.createElement("div");
      icoMargen.classList.add("col-4");
      icoMargen.innerHTML = '<i class="fa-solid fa-sack-dollar fa-2x"></i>';
      rowMargen.appendChild(icoMargen);
      let txtMargen = document.createElement("div");
      txtMargen.classList.add("col-8");
      txtMargen.innerHTML =
        "margen<br>" +
        margen.toFixed(2) +
        "% (" +
        accounting.formatMoney(
          precioVenta - (dLinea.precioCompra + dLinea.lpi),
          { symbol: "€", format: "%v %s" }
        ) +
        ")";
      rowMargen.appendChild(txtMargen);
    } else {
      if (dLinea.hasOwnProperty("idCustodia")) {
        //Custodia
        let colCust = document.createElement("div");
        colCust.classList.add("col-2");
        //   colCod.innerHTML = 'código: ' + dLinea.codigoProveedor;
        rowCompra.appendChild(colCust);
        let rowCust = document.createElement("div");
        rowCust.classList.add("row", "text-gray-500");
        colCust.appendChild(rowCust);
        let icoCust = document.createElement("div");
        icoCust.classList.add("col-4");
        icoCust.innerHTML = '<i class="fa-solid fa-shield-cat fa-2x"></i>';
        rowCust.appendChild(icoCust);
        let txtCust = document.createElement("div");
        txtCust.classList.add("col-8");
        txtCust.innerHTML = "id Custodia<br>" + dLinea.idCustodia;
        rowCust.appendChild(txtCust);
      }
      if (dLinea.hasOwnProperty("reserva")) {
        //Custodia
        let colReserva = document.createElement("div");
        colReserva.classList.add("col-2");
        //   colCod.innerHTML = 'código: ' + dLinea.codigoProveedor;
        rowCompra.appendChild(colReserva);
        let rowReserva = document.createElement("div");
        rowReserva.classList.add("row", "text-gray-500");
        colReserva.appendChild(rowReserva);
        let icoReserva = document.createElement("div");
        icoReserva.classList.add("col-4");
        icoReserva.innerHTML = '<i class="fa-solid fa-warehouse fa-2x"></i>';
        rowReserva.appendChild(icoReserva);
        let txtReserva = document.createElement("div");
        txtReserva.classList.add("col-8");
        txtReserva.innerHTML = "reserva<br>" + dLinea.reserva;
        rowReserva.appendChild(txtReserva);
      }
    }
    if (dLinea.hasOwnProperty("stock")) {
      //Código del proveddor
      let colStock = document.createElement("div");
      colStock.classList.add("col-2");
      //   colCod.innerHTML = 'código: ' + dLinea.codigoProveedor;
      rowCompra.appendChild(colStock);
      let rowStock = document.createElement("div");
      rowStock.classList.add("row", "text-gray-500");
      colStock.appendChild(rowStock);
      let icoStock = document.createElement("div");
      icoStock.classList.add("col-4");
      icoStock.innerHTML = '<i class="fa-solid fa-cubes-stacked fa-2x"></i>';
      rowStock.appendChild(icoStock);
      let txtStock = document.createElement("div");
      txtStock.classList.add("col-8");
      txtStock.innerHTML = "stock<br>" + dLinea.stock;
      rowStock.appendChild(txtStock);
    }

    //Ponemos los iconos al final de la linea para identificar custodias y reservas
    if (dLinea.hasOwnProperty("idCustodia")) {
      let icoCustBig = document.createElement("div");
      icoCustBig.classList.add("col-4", "text-end", "text-info");
      icoCustBig.innerHTML = '<i class="fa-solid fa-shield-cat fa-3x"></i>';
      rowCompra.appendChild(icoCustBig);
    }
    if (dLinea.hasOwnProperty("reserva")) {
      let icoReservaBig = document.createElement("div");
      icoReservaBig.classList.add("col-6", "text-end", "text-success");
      icoReservaBig.innerHTML = '<i class="fa-solid fa-warehouse fa-3x"></i>';
      rowCompra.appendChild(icoReservaBig);
    }
    if (dLinea.hasOwnProperty("actualizado")) {
      //Código del proveddor
      let colFecha = document.createElement("div");
      colFecha.classList.add("col-2");
      //   colCod.innerHTML = 'código: ' + dLinea.codigoProveedor;
      rowCompra.appendChild(colFecha);
      let rowFecha = document.createElement("div");
      rowFecha.classList.add("row", "text-gray-500");
      colFecha.appendChild(rowFecha);
      let icoFecha = document.createElement("div");
      icoFecha.classList.add("col-4");
      icoFecha.innerHTML = '<i class="fa-regular fa-calendar fa-2x"></i>';
      rowFecha.appendChild(icoFecha);
      let txtFecha = document.createElement("div");
      txtFecha.classList.add("col-8");
      txtFecha.innerHTML = "actualizado<br>" + dLinea.actualizado;
      rowFecha.appendChild(txtFecha);
    }

    let btnMas = document.querySelectorAll(".mas-info-linea-articulo");
    btnMas.forEach((i) => {
      i.classList.remove("visually-hidden");
    });
  }
}

function colocaInfoCompra(datos) {
  //console.log('ahora no');
  //console.log(datos);
  //let datos = event.data;
  let carritos = {}; //Inicializamos el objeto vacio
  //let referencia = datos.hasOwnProperty('Referencia') ? datos.Referencia : datos.referencia;
  let filaArt = document.getElementById(datos.datosArticulo.referencia);
  let ulCompras = filaArt.nextElementSibling.querySelector("ul");
  let rowCompra = document.createElement("div");

  if (datos !== undefined && datos !== null) {
    //Comprobamos primero si el mensaje es la info de inforpor
    if (datos.ifp !== false) {
      //Si la respuesta tiene esta propiedad viene de inforpor y si el valor es 0 es que tenemos la info
      let dLinea = {
        proveedor: "inforpor",
        // referencia: referencia,
        datosArticulo: datos.datosArticulo,
        ulCompras: ulCompras,
        urlImg: "https://tienda.inforpor.com/assets/images/logo.png",
        altImg: "logo inforpor",
        codigoProveedor: datos.ifp.codigo,
        precioCompra: datos.ifp.precio,
        lpi: datos.ifp.lpi,
        stock: datos.ifp.stock,
      };
      lineaCompra(dLinea); //Añadimos la entrada a la lista
      //Si tenemos reservas para este artículo vamos a poner una segunda linea
      if (
        datos.ifp.hasOwnProperty("reserva") &&
        parseInt(datos.ifp.reserva) > 0
      ) {
        let dLinea = {
          proveedor: "inforpor",
          // referencia: referencia,
          datosArticulo: datos.datosArticulo,
          ulCompras: ulCompras,
          urlImg: "https://tienda.inforpor.com/assets/images/logo.png",
          altImg: "logo inforpor",
          codigoProveedor: datos.ifp.codigo,
          // precioCompra: datos.ifp.precio,
          // lpi: datos.ifp.lpi,
          reserva: datos.ifp.reserva,
        };
        lineaCompra(dLinea); //Añadimos la entrada a la lista
      }
    }

    if (datos.google !== undefined) {
      //Si la respuesta tiene esta propiedad viene de inforpor y si el valor es 0 es que tenemos la info
      let comprasGoogle = datos.google.compras;

      comprasGoogle.forEach((x) => {
        let dConfig = {
          tabla: "config",
          campo: "configuracion",
          valor: "img_" + x.proveedor,
        };
        let logoProveedor = llamadaJson(
          "./ajax/unicorn_db/dame-valor.php",
          dConfig
        ); //Pedimos el logo para este proveedor
        if (logoProveedor == "") {
          //Si no lo tenemos pedimos el genérico
          dConfig.valor = "img_generico";
          logoProveedor = llamadaJson(
            "./ajax/unicorn_db/dame-valor.php",
            dConfig
          );
        }

        let dLinea = {
          proveedor: x.proveedor,
          //   referencia: referencia,
          datosArticulo: datos.datosArticulo,
          ulCompras: ulCompras,
          urlImg: logoProveedor[0].valor,
          altImg: "logo " + x.proveedor,
          codigoProveedor: x.ref_proveedor,
          precioCompra: x.total_compra,
          lpi: 0,
          actualizado: x.fecha_actualizado,
        };

        lineaCompra(dLinea); //Añadimos la entrada a la lista
      });
      //Ponemos la imagen de inforpor
      //   imgIfp.src = 'https://iphoneros.com/wp-content/uploads/2022/05/logogoogledrive.jpg';
      // imgIfp.alt = 'logo inforpor';
    }

    if (datos.ifp === false && datos.google === undefined) {
      //Si no hemos encontrado el artículo ni en la tarifa ni en el fichero de inforpor
      //Si tenemos grabado el código de inforpor hacemos la búsqueda, lo más probable es que lo tengamos en custodias
      if (datos.datosArticulo.hasOwnProperty("codinfo")) {
        gestionaCustodias(datos);
      } else {
        console.log("no hay compras");
        ulCompras.appendChild(rowCompra);
        let rowAlert = document.createElement("div");
        rowAlert.classList.add("row", "bg-white", "px-3");
        rowCompra.appendChild(rowAlert);
        let alert = document.createElement("div");
        alert.classList.add("alert", "alert-warning");
        alert.setAttribute("role", "alert");
        alert.innerHTML =
          '<i class="fa-solid fa-triangle-exclamation"></i> No hay información de compras para este artículo. <a href="#" class="add-codigo-ifp" data-bs-toggle="modal" data-bs-target="#modalAddIfp" data-mpn="' +
          datos.datosArticulo.referencia +
          '">¿Tienes el código de inforpor para este artículo?</a>';
        rowAlert.appendChild(alert);
      }
    }
  }

  function gestionaCustodias(datos) {
    let buscaIfp = llamadaJson("./ajax/inforpor/consulta-prod.php", {
      codinfo: datos.datosArticulo.codinfo,
    }); //Pedimos la info de inforpor
    if (buscaIfp.hasOwnProperty("CodErr") && buscaIfp.CodErr == "0") {
      if (buscaIfp.Referencia != "") {
        //Si estamos recibiendo datos normales los ponemos
        console.log("datos normales");
      }
      if (buscaIfp.Custodia.hasOwnProperty("LinCus")) {
        //Si hay custodias para este artículo
        let dLinea = {
          proveedor: "inforpor",
          referencia: datos.datosArticulo.referencia,
          datosArticulo: datos.datosArticulo,
          ulCompras: ulCompras,
          urlImg: "https://tienda.inforpor.com/assets/images/logo.png",
          altImg: "logo inforpor",
          codigoProveedor: datos.datosArticulo.codinfo,
          //    precioCompra: 0,
          //   lpi: 0,
          stock: buscaIfp.Custodia.LinCus.cant,
          nPedido: buscaIfp.Custodia.LinCus.Npedido,
          idCustodia: buscaIfp.Custodia.LinCus.idcustodia,
        };
        lineaCompra(dLinea); //Añadimos la entrada a la lista
      }
      console.log(buscaIfp);

      let stock = buscaIfp.Stock == "" ? 0 : buscaIfp.Stock;
      let dLinea = {
        proveedor: "inforpor custodias",
        // referencia: referencia,
        datosArticulo: datos.datosArticulo,
        ulCompras: ulCompras,
        urlImg: "https://tienda.inforpor.com/assets/images/logo.png",
        altImg: "logo inforpor",
        codigoProveedor: buscaIfp.Cod,
        precioCompra: 0,
        lpi: 0,
        stock: stock,
        custodias: buscaIfp.Custodia
      };
      lineaCompra(dLinea); //Añadimos la entrada a la lista
    }
  }

  //Utilizamos el campo "activo web" para diferenciar entre tarifa e inforpor
  /*if (datos.hasOwnProperty('activo_web')) { //Tarifa
      let filaArt = document.getElementById(datos.referencia);
      filaArt.nextElementSibling.querySelector('.precio-tarifa').innerHTML = datos.precio;
      console.log('tarifa');
   console.log(datos);
  
    } else {
    if (datos.CodErr == '0') {
      let filaArt = document.getElementById(datos.Referencia);
      console.log('fila Art');
      console.log(filaArt);
      filaArt.nextElementSibling.nextElementSibling.querySelector('.codigo-inforpor').innerHTML = datos.Cod;
      filaArt.nextElementSibling.nextElementSibling.querySelector('.precio-inforpor').innerHTML = datos.Precio;
      filaArt.nextElementSibling.nextElementSibling.querySelector('.stock-inforpor').innerHTML = datos.Stock;
    }
     // console.log('datos inforpor');
     // console.log(datos);
    }*/
  //CApturamos el click en la linea de compra
  let lineasCompra = document.querySelectorAll(".linea-compra");
  lineasCompra.forEach((i) => {
    i.addEventListener("click", (e) => {
      let li = e.target.closest("li");
      //  li.classList.toggle('bg-success');
      //Queremos tener un carrito para cada proveedor
      let attrs = li.getAttribute("data");
      let atributos = JSON.parse(attrs);
      let proveedor = atributos.proveedor;
      let referencia = atributos.datosArticulo.referencia;
      //Si no existe el carrito para este proveedor lo iniciamos
      if (!carritos.hasOwnProperty(atributos.proveedor)) {
        carritos[proveedor] = [];
      }
      //Quitamos este artículo de los carritos para evitar duplicidades
      Object.keys(carritos).forEach((c) => {
        carritos[c] = carritos[c].filter(
          (d) => d.datosArticulo.referencia != referencia
        );
      });
      //Lo añadimos al carrito que acabamos de seleccionar
      carritos[proveedor].push(atributos);
      dibujaCarritos(carritos);
      //      console.log(carritos);
      //    console.log('vamos a cambiar estado');
      /*   let idLi = li.id;
        document.getElementById(idLi).classList.toggle('bg-danger');
        let li = e.target.closest('li');
        if (li.classList.contains('bg-success')) {
          li.classList.remove('bg-success');
        } else {
          li.classList.add('bg-success');
        }*/

      //  li.classList.add('bg-success');
      //  let attrs = li.id;
      //  let split = attrs.split('-');

      //  console.log(split);
    });
  });
}

function dibujaCarritos(carritos) {
  let datosPed = JSON.parse(
    document.getElementById("modal-pedido").getAttribute("data-bs-whatever")
  ); //Capturamos los datos originales del pedido para tener acceso a la dirección de ennvío, referencia, etc
  let dirEnvio = datosPed.direccion_envio;
  let refPedido = datosPed.id;
  let datosCompra = {
    dirEnvio: dirEnvio,
    refPedido: refPedido,
  };

  let numeroCarritos = Object.keys(carritos).length; //Numero de carritos que tenemos
  let nCarros = 0;
  rowCarritos.innerHTML = ""; //Limpiamos las columnas
  for (const i in Object.values(carritos)) {
    let esteCArrito = carritos[Object.keys(carritos)[i]];
    if (esteCArrito.length > 0) {
      //Evitamos mostrar carritos vacios
      //Creamos la columna para cada carrito
      let columna = document.createElement("div");
      columna.classList.add("col", "col-carrito");
      // columna.innerHTML = JSON.stringify(carrito);
      rowCarritos.appendChild(columna);
      //Dentro de cada columna ponemos una tarjeta
      let tarjeta = document.createElement("div");
      tarjeta.classList.add("card");
      columna.appendChild(tarjeta);
      //Encabezado de cada tarjeta
      let encabezadoTarjeta = document.createElement("div");
      encabezadoTarjeta.classList.add("card-header", "bg-white");
      //encabezadoTarjeta.innerHTML = Object.keys(carritos)[i]
      encabezadoTarjeta.innerHTML =
        '<img src="' +
        esteCArrito[0].urlImg +
        '" alt="' +
        Object.keys(carritos)[i] +
        '" width="100">';
      tarjeta.appendChild(encabezadoTarjeta);
      //Cuerpo de la tarjeta
      let cuerpoTarjeta = document.createElement("div");
      cuerpoTarjeta.classList.add("card-body");
      tarjeta.appendChild(cuerpoTarjeta);
      //Ul con los artículos
      let ul = document.createElement("ul");
      ul.classList.add("list-group", "list-group-flush");
      cuerpoTarjeta.appendChild(ul);

      let totalCompra = 0;
      esteCArrito.forEach((articulo, index) => {
        let codigoProveedor = articulo.codigoProveedor;
        let precioCompra = articulo.precioCompra;
        let strStock = "";
        let li = document.createElement("li");
        li.classList.add("list-group-item", "text-gray-800");
        if (Object.keys(carritos)[i] == "inforpor") {
          //Si es de inforpor vamos a pedir los datos en tiempo real
          let consultaProd = llamadaJson("ajax/inforpor/consulta-prod.php", {
            codinfo: codigoProveedor,
          }); //Cargamos la ficha correspondiente a esa id
          console.log(consultaProd);
          precioCompra =
            parseFloat(consultaProd.Precio) + parseFloat(consultaProd.Lpi);
          esteCArrito[index].precioCompra = precioCompra; //Actualizamos el dato del carrito con el recibido
          let stock = consultaProd.Stock;
          esteCArrito[index].stock = stock; //Actualizamos el dato del carrito con el recibido
          strStock =
            " (" +
            stock +
            ' uds. disponibles)<i class="fa-solid fa-stopwatch"></i>';
          if (stock == 0) {
            //Si el stock es 0 cambiamos el color del texto a rojo
            li.classList.remove("text-gray-800");
            li.classList.add("text-danger");
          }
          //Si es de una reserva tenemos que pedir la id
          if (articulo.hasOwnProperty("reserva")) {
            let idReserva = llamadaJson("./ajax/inforpor/si-reserva.php", {
              codinfo: codigoProveedor,
            });
            if (idReserva.hasOwnProperty("cerr") && idReserva.cerr == "0") {
              esteCArrito[index].idReserva = idReserva.cant; //Actualizamos el dato del carrito con el recibido
            }
          }
        }
        let importeLinea = articulo.datosArticulo.cantidad * precioCompra;

        li.innerHTML =
          articulo.datosArticulo.cantidad +
          " x [" +
          codigoProveedor +
          "] " +
          articulo.datosArticulo.descripcion +
          " - " +
          articulo.datosArticulo.referencia +
          strStock;
        li.innerHTML +=
          '<span class="ml-3 fs-5">' +
          accounting.formatMoney(importeLinea, {
            symbol: "",
            format: "%v %s",
          }) +
          '<i class="fa-solid fa-euro-sign"></i></span>';
        ul.appendChild(li);
        totalCompra = totalCompra + importeLinea;
      });
      nCarros++;
      let pieTarjeta = document.createElement("div");
      pieTarjeta.classList.add("card-footer", "ml-3");
      pieTarjeta.innerHTML =
        'Total carrito <span class="fs-4">' +
        accounting.formatMoney(totalCompra, { symbol: "€", format: "%v %s" }) +
        "</span>";
      if (Object.keys(carritos)[i] == "inforpor") {
        datosCompra.carrito = esteCArrito;
        pieTarjeta.innerHTML +=
          '<button type="button" class="btn btn-success float-end" data-bs-target="#modalComprarInforpor" data-bs-toggle="modal" data-pedido=\'' +
          JSON.stringify(datosCompra).replace(/[\/\(\)\']/g, "&apos;") +
          "'>Comprar</button>";
      }
      tarjeta.appendChild(pieTarjeta);
    }
  }
  let cols = document.querySelectorAll(".col-carrito");
  cols.forEach((a) => {
    a.classList.add("col-md-" + 12 / nCarros);
  });
}


function sanearProvincia(provincia) {
let strSplit = provincia.split("'");
let limpio = strSplit[0].trim();
return limpio;
}

function buscaPedidosOdooWorker(pd) {
  if (plataforma != 'odoo') { //Si es un pedido de odoo no buscamos nada
  if (typeof Worker !== "undefined") {
    if (typeof w == "undefined") {
      w = new Worker("js/workers/buscar-datos-pedido-odoo.js");
    }
    w.postMessage(pd); //pasamos el pedido al worker
    w.onmessage = function (event) {
      let ventaOdoo = event.data; //La información sobre el pedido de venta o undefined
//Si recibimos el  colocamos la info en el modal
if (typeof ventaOdoo != "undefined") {
 // console.log('v');
 // console.log(ventaOdoo);
  //Colocamos la venta
  pedVentaOdoo.innerHTML =
    '<a href="https://erp.futura.es/web#id=' +
    ventaOdoo.id +
    '&model=sale.order&menu_id=280" target="_blank">' +
    ventaOdoo.name +
    "</a>";
  inputRefPedido.value = ventaOdoo.name; //Colocamos la referencia del pedido en el input para sacar de inforpor

  //Añadimos la propiedad ventaOdoo al objeto
  pd.ventaOdoo = ventaOdoo.name;
}
//Compra en inforpor
      let pedIfp;
      let chkAlbaran = false; //declaramos una variable para comprobar si las posibles compres coinciden con el pedido de inforpor
      if (plataforma != 'inforpor') {//Ahora vamos a buscar el pedido en inforpor, salvo que sea que de inforpor
      pedIfp = buscaPedidoInforporDev(pd);
        if (typeof pedIfp != "undefined") {
        let bloqueSiHayTracking = document.getElementById("si-hay-tracking");
        bloqueSiHayTracking.querySelector("h5").innerHTML = pedIfp.estado;
        bloqueSiHayTracking.querySelector("#ref-inforpor").innerHTML =
          pedIfp.numero;
        bloqueSiHayTracking.querySelector("#ref-cliente").innerHTML =
          pedIfp.numpedCli;
        bloqueSiHayTracking.querySelector("#agencia").innerHTML = pedIfp.Agencia;
        bloqueSiHayTracking.querySelector("#expedicion").innerHTML =
          pedIfp.expedicion;
    
        document.getElementById("no-hay-tracking").classList.add("visually-hidden"); //Escondemos el bloque con el mensaje de que no hay tracking
        bloqueSiHayTracking.classList.remove("visually-hidden"); //Mostramos el bloque con el tracking
      }
      } else { //Si la plataforma es inforpor lo que hacemos es buscar el albarán
var albaranIfp = llamadaJson('./ajax/inforpor/ver-albaran.php',pd);
//console.log('albaranIfp');
//console.log(albaranIfp);
      }

    //  let compraOdoo = buscaCompraOdoo(pd, ventaOdoo);
   //   let compraOdoo = llamadaJson("ajax/odoo/buscar-solicitud-compra.php", pd); //Pedimos la compra
      let checkCliente = document.getElementById("chkCliente");
      let checkClienteEnvio = document.getElementById("chkClienteEnvio");
      var dameClienteEnvio = true; // Por defecto vamos a pensar que si tenemos la direccion de envío
     // colocaInfoPedidos(ventaOdoo, compraOdoo, pedIfp); //Colocamos los datos de pedidos
      //Si no tenemos la venta tenemos que comprobar si tenemos los articulos y el cliente creados en odoo
      if (ventaOdoo === undefined) {
        //Buscamos el cliente
        let buscarClienteOdoo = buscaClienteOdoo(pd); //Buscamos al cliente en odoo
        let dameCliente = dameFichaCliente(buscarClienteOdoo); //Depuramos para encontrar la ficha u obtener false si el cliente no existe
        checkCliente.classList.remove("fa-spin", "fa-circle-notch");
        if (dameCliente !== false) {
          //Significa que el cliente ya está creado
          // console.log('dameCliente');
          // console.log(buscarClienteOdoo);
          // console.log(dameCliente);
          checkCliente.classList.add("text-success", "fa-user-check"); //Lo ponemos en verde
          //Vamos a marcar los campos encontrados en verde
          Object.keys(buscarClienteOdoo).forEach((i) => {
            if (buscarClienteOdoo[i].length > 0) {
              document.getElementById(i + "-factura-pedido").innerHTML +=
                ' <i class="fa-solid fa-check text-success"></i>'; //Añadimos un check a los campos que coinciden
            }
          });
          //Si no es la misma dirección la de envío y la de factura es cuando comprobamos
          if (pd.dir_envio_igual_factura === false) {
            let childIds = dameCliente.child_ids;
            //Recorremos las distintas direcciones del cliente buscando la que ha indicado para envío
            let existeDireccion = false;
            var dameClienteEnvio;
            childIds.forEach((i) => {
              let d = {
                modelo: "res.partner",
                campo: "id",
                valor: i,
              };
              let ficha = llamadaJson("ajax/odoo/busqueda.php", d); //Cargamos la ficha correspondiente a esa id
              //console.log('ficha');
              //console.log(ficha);
              ficha = ficha[0];
              if (ficha.type == "delivery") {
                //Si es una dirección de envío la comparamos con la del pedido
                //Vamos a comparar la calle, con esto debería ser suficiente para identificar
                let calleFicha = ficha.street.toUpperCase(); //Pasamos las direcciones a mayusculas para compararlas
                let calleFicha2 = ficha.street2;
                let calleEnvioPed = pd.direccion_envio.direccion.toUpperCase();
                //console.log('calles');
                //console.log(calleFicha);
                //console.log(calleEnvioPed);
                if (
                  calleFicha == calleEnvioPed ||
                  calleFicha2 == calleEnvioPed
                ) {
                  existeDireccion = true;
                  dameClienteEnvio = ficha; //Asignamos la ficha actual a la direccion de envío
                }
              }
            });

            //Según tengamos o no la dirección creada hacemos
            if (existeDireccion === true) {
              checkClienteEnvio.classList.remove("fa-spin", "fa-circle-notch");
              checkClienteEnvio.classList.add("text-success", "fa-user-check"); //Lo ponemos en verde
            } else {
              //Si no la tenemos tenemos que crear las herramientas para crearla
              dameClienteEnvio = false; //Asignamos el valor false a esta variable para que no deje crear el pedido
              checkClienteEnvio.classList.add("text-danger", "fa-user-plus");
              checkClienteEnvio.classList.remove("fa-spin", "fa-circle-notch");
              checkClienteEnvio.style.cursor = "pointer"; //Solo permitimos hacer click en la de factura, porque la de envío va dentro
              checkClienteEnvio.setAttribute("data-bs-toggle", "modal");
              checkClienteEnvio.setAttribute(
                "data-bs-target",
                "#comprueba-cliente-modal"
              );
              //Necesitamos pasar la id de la ficha padre
              document
                .getElementById("modal-pedido")
                .setAttribute("id-padre", dameCliente.id);
            }
          }
        } else {
          //Si el cliente no está creado, obviamente la dirección de envío tampoco
          //<span class="material-icons float-end fs-2 text-success">person_add</span>
          checkCliente.classList.add("text-danger", "fa-user-plus");
          checkClienteEnvio.classList.add("text-danger", "fa-user-xmark");
          //     checkCliente.innerHTML = 'person_add';
          checkCliente.style.cursor = "pointer"; //Solo permitimos hacer click en la de factura, porque la de envío va dentro
          checkCliente.setAttribute("data-bs-toggle", "modal");
          checkCliente.setAttribute(
            "data-bs-target",
            "#comprueba-cliente-modal"
          );
        }
        //Independientemente de si existe o no, si no es la misma dirección de factura y envío tenemos que comprobar

        let direccionesPedido = {
          factura: dameCliente,
        };

        if (pd.dir_envio_igual_factura !== true) {
          //Si las direcciones de envío y facturacion son las mismas pasamos la misma id
          dirEnvio = dameClienteEnvio; //En este caso tenemos la id al revisar
          direccionesPedido.envio = dirEnvio;
        }

        //Buscamos los artículos
        let restantesArticulos = buscaArticulosOdoo(pd);
        // console.log('podemos crear?');
        // console.log(restantesArticulos);
        //console.log(chkClienteResult);
        //Si el cliente existe y no quedan artículos sin crear mostramos el botón de sonreir
        //      if (restantesArticulos === 0 && chkClienteResult === true) {
        if (
          restantesArticulos === 0 &&
          dameCliente !== false &&
          dameClienteEnvio !== false
        ) {
          let datosGlobales = {
            direccionesPedido: direccionesPedido,
            //  cliente: dameCliente,
            pedido_compra: pedIfp,
            pedido_venta: pd,
            //  articulosVenta : artsVenta,
            plataforma: plataforma,
          };

          //JSON.stringify(linea).replace(/[\/\(\)\']/g, "&apos;")
          document
            .getElementById("mostrar-btn-sonreir")
            .querySelector("button")
            .setAttribute(
              "data-bs-whatever",
              JSON.stringify(datosGlobales).replace(/[\/\(\)\']/g, "&apos;")
            );
          document
            .getElementById("mostrar-btn-sonreir")
            .classList.remove("visually-hidden");
        }
      } else {
        //Acciones si hemos localizado la venta
        // document.getElementById.setAttribute('ped-odoo',);
        checkCliente.classList.remove("fa-spin", "fa-circle-notch", "fa-solid"); //Paramos el spin
        checkCliente.classList.add("fa-regular", "fa-circle-question"); //Paramos el spin
        checkClienteEnvio.classList.remove(
          "fa-spin",
          "fa-circle-notch",
          "fa-solid"
        ); //Paramos el spin
        checkClienteEnvio.classList.add("fa-regular", "fa-circle-question"); //Paramos el spin
      }
      //   let btnMas = document.querySelectorAll('.mas-info-linea-articulo');
      //  btnMas.forEach(i => {
      //   i.classList.remove('visually-hidden');
      // })
      //Tenemos que comparar lo enviado con que falta. De momento tenemos que buscar los artículos en inforpor para poder generar los pedidos
        //Pedimos la compra
  //console.log('pdpdc');
  //console.log(pd);
  //console.log('gggg');
  //console.log(albaranIfp);
  let compraOdoo = llamadaJson("ajax/odoo/buscar-solicitud-compra.php", pd);
  if (compraOdoo.length > 0) {
    
    compraOdoo.forEach(compra => {
      let col = document.createElement('div');
      col.classList.add('col','ped-compra');
      col.innerHTML =
    '<a href="https://erp.futura.es/web#id=' +
    compra.id +
    '&model=purchase.order&menu_id=1330" target="_blank">' +
    compra.name +
    "</a>";
    if (typeof albaranIfp !== 'undefined' && albaranIfp.hasOwnProperty('nalb') && albaranIfp.nalb == compra.partner_ref) {
      col.classList.add('bg-info');
      col.querySelector('a').classList.add('text-white');
      chkAlbaran = true;
    }
      pedsCompraOdoo.appendChild(col);
      console.log(compra);
        })
  }
 
  //Si no tenemos la compra generamos el botón para añadirla
  if (plataforma == 'inforpor' && chkAlbaran === false) {
    let col = document.createElement('div');
    col.classList.add('col','ped-compra');
    col.innerHTML = '<button type="button" class="btn btn-primary" onclick="modalCompraOdoo()" id="crearPoO">Crear</button>';
    pedsCompraOdoo.appendChild(col);
    //console.log('albaran');
    //console.log(albaranIfp);
    if (albaranIfp.hasOwnProperty('fecha')) {
      inputFechaProveedor.value = albaranIfp.fecha; //Si tenemos la fecha del albaran la añadimos
    }
    /*if (albaranIfp.hasOwnProperty('portes')) {
      inputFechaProveedor.value = albaranIfp.portes; //Si tenemos la fecha del albaran la añadimos
    }*/
    if (albaranIfp.hasOwnProperty('nalb')) { //Si tenemos el número de albarán lo ponemos para pasarlo
      inputRefProveedor.value =  albaranIfp.nalb;
    } else {
      inputRefProveedor.value =  document.getElementById('ref-pedido');
    }
    //console.log('activar botón crear compra inforpor');
  }
  pedsCompraOdoo.classList.remove('visually-hidden');
    };
  }

} else{
  inputRefPedido.value = pd.id; //Colocamos la referencia del pedido en el input para sacar de inforpor
}
}



/*function buscaPresupuestosOdoo(referencia,plataforma) {
  //Buscamos primero la venta
    let datos = {
      name : referencia
    };
    let presupuestoVenta = llamadaJson('ajax/odoo/buscar-presupuesto-venta.php',datos);
  
  //Generamos la referencia del cliente para buscar por ella
    let refCliente = refPedidoClienteInforpor(referencia,plataforma);
  //Buscamos por la referencia del cliente
    datos =  {valor : refCliente};
    let compraOdoo = llamadaJson('ajax/odoo/buscar-solicitud-compra.php',datos);
  
  //Si tenemos presupuesto de venta pero no tenemos compra buscamos por el número de presupuesto
  if (presupuestoVenta.length > 0 && compraOdoo.length === 0) {
  //  console.log(presupuestoVenta);
    //Buscamos por la referencia del cliente
      datos =  {valor : presupuestoVenta[0].name};
      compraOdoo = llamadaJson('ajax/odoo/buscar-solicitud-compra.php',datos);
  }
  let PresupuestosOdoo = {
    presupuestoVenta: presupuestoVenta,
    solicitudCompra : compraOdoo
  };
  
  return PresupuestosOdoo;
  }*/
function buscaPedidoInforporDev(pd) {
  var tracking;
  let idPedido = pd.id;

  console.log("pdd");
  console.log(pd);

  //console.log(datos);
  if (pd.hasOwnProperty('ventaOdoo') ) {
    //Si recibimos los datos del pedido de odoo primero buscamos por ellos
    let refPed = pd.ventaOdoo;
    tracking = ejecutaBusquedaInforpor(refPed);
  }
  //Si seguimos sin tener el tracking buscamos por la referencia del pedido de la plataforma
  if (tracking === undefined) {
    //Si no tenemos el tracking buscamos por la id del pedido
    let idPed = pd.id;
    //Actualmente no se graba ningún pedido de magento utilizando su referencia por lo que este paso sólo sería para pedidos de Pc componentes
    let referencia = "PC" + idPed.substring(0, idPed.length - 2);
    tracking = ejecutaBusquedaInforpor(referencia);
  }
  return tracking;
}

/*function buscaPedidoInforpor(pd, datos) {
  var tracking;
  let retorno;
  //Primero buscamos por el pedido de odoo si lo tenemos
  if (datos != '') {

let refPed = datos.order_id[1];
tracking = ejecutaBusquedaInforpor(refPed);
  }

if (tracking === undefined) { //Si no tenemos el tracking buscamos por la id del pedido
  let idPed = pd.id;
  //Actualmente no se graba ningún pedido de magento utilizando su referencia por lo que este paso sólo sería para pedidos de Pc componentes
  let referencia = 'PC'+idPed.substring(0,idPed.length - 2);
  tracking = ejecutaBusquedaInforpor(referencia);
}

//Volvemos a comprobar y si ahora lo tenemos lo mostramos
if (tracking !== undefined) { 
 let bloqueSiHayTracking = document.getElementById('si-hay-tracking');  
 bloqueSiHayTracking.querySelector('h5').innerHTML = tracking.estado;
 bloqueSiHayTracking.querySelector('#ref-inforpor').innerHTML = tracking.numero;
 bloqueSiHayTracking.querySelector('#ref-cliente').innerHTML = tracking.numpedCli;
 bloqueSiHayTracking.querySelector('#agencia').innerHTML = tracking.Agencia;
 bloqueSiHayTracking.querySelector('#expedicion').innerHTML = tracking.expedicion;

 document.getElementById('no-hay-tracking').classList.add('visually-hidden'); //Escondemos el bloque con el mensaje de que no hay tracking
 bloqueSiHayTracking.classList.remove('visually-hidden');  //Mostramos el bloque con el tracking
  //console.log('tracking');
  //console.log(tracking);
  retorno = tracking.numpedCli;
} else { //Si no lo tenemos tenemos que comprobar cada artículo
  console.log('comprobando');
pd.lineas_pedido.forEach(a => {
  console.log(a);
});


}

return retorno;
}*/

function ejecutaBusquedaInforpor(refPed) {
  let tracking;
  let d = { NumPedCli: refPed };
  let existeInforpor = llamadaJson("ajax/inforpor/buscar-pedido.php", d); //Buscamos en inforpor
  if (existeInforpor.hasOwnProperty('CodErr') && existeInforpor.CodErr == "0") {
    //Si el código de error es 0 lo hemos encontrado
    tracking = existeInforpor;
  }
  return tracking;
}


function adivinaCategoria(codCat) {
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
  return idCatBuscada;
}



function modalCompraOdoo(){
  let pedido = document.getElementById('modal-pedido').getAttribute('data-bs-whatever');
  let objPedido = JSON.parse(pedido);
  //let grabaCompra = llamadaJson('ajax/odoo/crear-solicitud-compra.php', objPedido);                                //La venta (Debemos tenerla siempre)
//console.log(objPedido);
let lineasVenta = objPedido.lineas_pedido;
for (p in lineasVenta) {
  $('#lineasPoOdoo').append('<div class="row"><div class="col-11">' + lineasVenta[p].cantidad + ' x ' + lineasVenta[p].mpn + '(' + lineasVenta[p].nombre + ')</div><div class="col-1"></div></div>');
}
//modalCrearCompra.
new bootstrap.Modal(modalCrearPoOdoo, '').show();

btnCrearPo.addEventListener('click',e => {
  let pedido = document.getElementById('modal-pedido').getAttribute('data-bs-whatever');
  let objPedido = JSON.parse(pedido);
  objPedido.refProveedor = inputRefProveedor.value;
  objPedido.fechaAlbaran = inputFechaProveedor.value;
 // objPedido.portesProveedor = inputPortesProveedor.value;
  //console.log('objPedido');
  //console.log(objPedido);
  let grabaCompra = llamadaJson('ajax/odoo/crear-solicitud-compra.php', objPedido);                                //La venta (Debemos tenerla siempre)
 // console.log(typeof grabaCompra);
  if (Number.isInteger(grabaCompra)) {
    new bootstrap.Modal(modalCrearPoOdoo, '').hide();
    new bootstrap.Modal(modalExito, '').show();
  }
//console.log(grabaCompra);
} );
//console.log();
}

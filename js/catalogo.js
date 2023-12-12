/**
 * Realiza la búsqueda en Magento
 * @param  Array criterios               Array con los criterios de búsqueda
 * @return JSON          devuelve un JSON con los artículos
 */
function buscar(criterios) {
  let listaBusqueda = llamadaJson(
    "ajax/magento/buscar-articulo.php",
    criterios
  ); //Recuperamos el listado de conjuntos de atributos
  return listaBusqueda;
}

/**
 * Coloca los datos en el modal de confirmación
 * @param  Object cosas  Objeto con los datos que queremos mostrar en el modal y los necesarios para la acción a realizar
 */
function configuraModalConfirmar(cosas) {
  let accion = cosas.accion;
  let titulo = cosas.titulo;
  let cuerpo = cosas.cuerpo;
  let txtBoton = cosas.txtBoton;
  let sku = cosas.articulo.sku;
  let precio = cosas.precio;
  //Colocamos los datos en la ventana modal

  let btnConfirmarGeneral = document.getElementById("btn-confirmar-general"); //modalConfirmarGeneral

  document.getElementById("ConfirmacionModalTitulo").innerHTML = titulo;
  document.getElementById("ConfirmacionModalCuerpo").innerHTML = cuerpo;
  btnConfirmarGeneral.innerHTML = txtBoton;
  btnConfirmarGeneral.setAttribute("data-sku", sku); //Colocamos la id del artículo en el botón de eliminar en el modal
  btnConfirmarGeneral.setAttribute("data-precio", precio); //Colocamos la id del artículo en el botón de eliminar en el modal
  btnConfirmarGeneral.setAttribute("data-accion", accion); //Colocamos la id del artículo en el botón de eliminar en el modal
  //new bootstrap.Modal(document.getElementById('ConfirmacionModal')).show();
}

function configModalCambiaPrecio(cosas, compra) {
  let sku = cosas.sku;
  let precioActual = cosas.price;
  let margen = (precioActual / compra - 1) * 100;
  let btnCambiarPrecio = document.getElementById("btnCambiarPrecio"); //modalConfirmarGeneral
  let articulo = {
    price: precioActual,
    custom_attributes: [
      {attribute_code: 'cost', value: compra}
    ]
  }
  btnCambiarPrecio.setAttribute("data-sku", sku); //Colocamos la id del artículo en el botón de eliminar en el modal
  //btnCambiarPrecio.setAttribute("data-accion", "cambiar precio"); //Colocamos la id del artículo en el botón de eliminar en el modal
  btnCambiarPrecio.setAttribute("data-accion", "actualizar articulo"); //Colocamos la id del artículo en el botón de eliminar en el modal
  btnCambiarPrecio.setAttribute("data-articulo", JSON.stringify(articulo).replace(/[\/\(\)\']/g, "&apos;")); //Colocamos la id del artículo en el botón de eliminar en el modal
  document.getElementById("inputCambiarPrecio").value = precioActual;
  document.getElementById("margenActual").setAttribute("compra", compra);
  document.getElementById("margenActual").innerHTML = margen.toFixed(2) + "%";
  //new bootstrap.Modal(document.getElementById('modal-cambiar-precio')).show();
}

/**
 * Función que gestiona la paginación y muestra o no los controles
 * @param  JSON listaBusqueda               Resultados de la búsqueda
 * @param  Array datosBusqueda              Configuración de la búsqueda
 */
function paginacion(listaBusqueda, datosBusqueda) {
  // console.log('listaBusqueda');
  // console.log(listaBusqueda);
  document.querySelector(".pagination").innerHTML = "";
  let nResultados = listaBusqueda.total_count;
  let pagActual = listaBusqueda.search_criteria.current_page ?? p;
  let pageSize = listaBusqueda.search_criteria.page_size;
  let totalPaginas = Math.ceil(nResultados / pageSize); //Total que paginas que vamos a plantear
  let urlActual =
    window.location.origin + window.location.pathname + "?pl=" + plataforma;
  console.log('pagActual',pagActual);
  let navPrev = document.createElement("li");
  navPrev.className = "page-item";
  document
    .getElementById("paginacion")
    .querySelector("ul")
    .appendChild(navPrev);
  navPrev.id = "anteriorPagina";
  let navPrevA = document.createElement("a");
  navPrevA.href = "#";
  navPrevA.className = "page-link";
  navPrevA.textContent = "Anterior";
  navPrev.appendChild(navPrevA);
  if (pagActual == 1) {
    navPrev.classList.add("disabled");
  }
  //Generamos los números de cada pagina
  for (let index = 1; index < totalPaginas; index++) {
    let liPag = document.createElement("li");
    liPag.className = "page-item";
    document
      .getElementById("paginacion")
      .querySelector("ul")
      .appendChild(liPag);
    let linkPag = document.createElement("a");
    linkPag.className = "page-link";
    linkPag.href = "#";
    linkPag.textContent = index;
    liPag.appendChild(linkPag);
    if (index == pagActual) {
      liPag.classList.add("active");
    }
  }

  let navNext = document.createElement("li");
  navNext.className = "page-item";
  document
    .getElementById("paginacion")
    .querySelector("ul")
    .appendChild(navNext);
  navNext.id = "siguientePagina";
  let navNextA = document.createElement("a");
  navNextA.href = "#";
  navNextA.className = "page-link";
  navNextA.textContent = "Siguiente";
  navNext.appendChild(navNextA);
  if (pagActual >= totalPaginas) {
    navNext.classList.add("disabled");
  }

  siguientePagina.addEventListener("click", function (e) {
    e.preventDefault;
    realizaBusqueda(++pagActual);
  });
  anteriorPagina.addEventListener("click", function (e) {
    e.preventDefault;
    realizaBusqueda(--pagActual);
  });
  /*let pag = datosBusqueda.p; // página actual
let btnNextpage = document.getElementById('next-page');   //Boton siguiente página
let btnPrevpage = document.getElementById('prev-page');   //Boton siguiente página
if (pag === 1) {
btnPrevpage.parentNode.classList.add('disabled'); //Añadimos la clase disabled
btnPrevpage.setAttribute('aria-disabled','true'); //Añadimos el atributo aria-disabled
}else{
  btnPrevpage.parentNode.classList.remove('disabled'); //quitamos la clase disabled
  btnPrevpage.removeAttribute('aria-disabled'); //Quitamos el atributo aria-disabled
}

    if (listaBusqueda.length >= datosBusqueda.ap) { //Si tenemos una página completa de resultados mostramos la paginación
      document.getElementById('paginacion').style.display = 'block';
    } else {
      document.getElementById('paginacion').style.display = 'none';
    }*/
}

/**
 * Función que se encarga de realizar las acciones
 * @param  {Object} cosas               Objeto con los datos necesarios para la acción
 * @return {String}       Devuelve true si la acción es correcta
 */
function hacerCosas(cosas) {
  //  console.log('cosas',cosas);
  let accion = cosas.accion;
  let plataforma = cosas.plataforma;
  let datos = {};
  let resultado;
  datos.sku = cosas.articulo.sku;
  datos.plataforma = plataforma;

  switch (accion) {
    case 'actualizar articulo': //Método general
    datos = cosas.articulo;
    resultado = llamadaJson("ajax/magento/actualizar-articulo.php", datos); //Buscamos la info en inforpor
      break;
    case "eliminar":
      resultado = llamadaJson("ajax/magento/eliminar-articulo.php", datos); //Buscamos la info en inforpor
      break;
      case "eliminar oferta":
        resultado = llamadaJson("ajax/magento/eliminar-oferta.php", datos); //Buscamos la info en inforpor
        break;
    case "cambiar status":
      //Configuramos el estado que queremos grabar
      let nEstado;
      switch (cosas.articulo.estado) {
        case "1":
          nEstado = "2";
          break;
        case "2":
          nEstado = "1";
          break;
      }
      datos.status = nEstado;

      resultado = llamadaJson("ajax/magento/actualizar-articulo.php", datos); //Buscamos la info en inforpor
      break;
    case "poner pvp":
    case "cambiar precio":
      datos.precio_venta = cosas.articulo.precio;
      datos.auto_actualizable = "0"; //Si fijamos el PVP quitamos la auto actualización
      datos.cost = cosas.articulo.cost;
      resultado = llamadaJson("ajax/magento/actualizar-articulo.php", datos); //Buscamos la info en inforpor
      break;
    case "cambiar auto actualizar":
      let autoUpdate;
      switch (cosas.articulo.auto_actualizar) {
        case "0":
          autoUpdate = "1";
          break;
        case "1":
          autoUpdate = "0";
          break;
      }
      datos.auto_actualizable = autoUpdate;
      resultado = llamadaJson("ajax/magento/actualizar-articulo.php", datos); //Buscamos la info en inforpor
      break;
    case "seleccionar tiendas":
      let webs = [];
      if (document.getElementById("switchFutura").checked === true) {
        webs.push(4);
      }
      if (document.getElementById("switchTiendaplotter").checked === true) {
        webs.push(1);
      }
      if (document.getElementById("switchTiendasolvente").checked === true) {
        webs.push(2);
      }
      if (document.getElementById("switchTiendasublimacion").checked === true) {
        webs.push(3);
      }
      let wsite = {
        website_ids: webs,
      };
      datos.extension_attributes = wsite;
      resultado = llamadaJson("ajax/magento/actualizar-articulo.php", datos); //Buscamos la info en inforpor
      break;
  }

  if (resultado === true || resultado === "ok") {
    let fila = document.querySelector('[data-id="' + cosas.articulo.id + '"]'); //Seleccionamos la fila sobre la que actuar

    switch (accion) {
      case "eliminar": //Si la acción es eliminar vamos a quitar la linea de la tabla
        fila.style.display = "none";
        break;
      case "cambiar auto actualizar": //Si la acción es cambiar auto actualizar cambiamos el robot de color
        let robot = fila.querySelector(".btn-toggle-auto-actualizar");
        console.log(robot);
        if (cosas.articulo.auto_actualizar == "1") {
          robot.classList.remove("text-success");
          robot.classList.add("text-gray-300");
        } else {
          robot.classList.add("text-success");
          robot.classList.remove("text-gray-300");
        }

        break;
    }

  }
  return resultado;
}
function colocaInfoCompra(event) {
  console.log(event);
  let datos = event;
  if (datos !== undefined && datos !== null) {
    let filaArt = document.querySelector(
      '[referencia="' + datos.datosArticulo.referencia + '"]'
    );
    let precioVenta = filaArt.querySelector(".importe").getAttribute("importe"); //CApturamos el precio por unidad al que hemos vendido el artículo
    let celdaStock = filaArt.querySelector(".stock");
    let celdaCompra = filaArt.querySelector(".compra");
    let celdaMargen = filaArt.querySelector(".margen");

    if (datos.ifp !== false) {
      //Si la respuesta tiene esta propiedad viene de inforpor y si el valor es 0 es que tenemos la info
      let spanStock = document.createElement("span");
      spanStock.innerHTML = datos.ifp.stock + " uds.";
      celdaStock.appendChild(spanStock);

      let spanPRecio = document.createElement("span");
      spanPRecio.setAttribute("compra", datos.ifp.precio);
      spanPRecio.innerHTML = accounting.formatMoney(datos.ifp.precio, {
        symbol: "€",
        format: "%v %s",
      });
      celdaCompra.appendChild(spanPRecio);

      let margen = (precioVenta / datos.ifp.precio + datos.ifp.lpi - 1) * 100;
      let spanMargen = document.createElement("span");
      if (margen < 0) {
        spanMargen.classList.add("bg-danger", "text-white");
      }
      spanMargen.innerHTML = margen.toFixed(2) + "%";
      celdaMargen.appendChild(spanMargen);
      // console.log('colocaCompra Inforpor');
      // console.log(datos);
    } else {
      //Si no lo hemos encontrado en inforpor vamos a mirar si es de Epson para hacer una búsqueda a través de la api
      //Tenemos que pedir los atributos guardados en la bbdd
      let t = {
        mpn: datos.datosArticulo.referencia,
      };
      let busqueda = llamadaJson("./ajax/unicorn_db/dame-atributos.php", t);
      if (typeof busqueda[5] != "undefined") {
        let buscaIfp = llamadaJson("./ajax/inforpor/consulta-prod.php", {
          codinfo: busqueda[5],
        });
        console.log("busqueda");
        console.log(buscaIfp);
        if (
          buscaIfp.hasOwnProperty("Custodia") &&
          buscaIfp.Custodia.hasOwnProperty("LinCus")
        ) {
          let spanStock = document.createElement("span");
          spanStock.innerHTML =
            '<i class="fa-solid fa-shield-cat fa-2x"></i> ' +
            buscaIfp.Custodia.LinCus.cant +
            " uds.";
          celdaStock.appendChild(spanStock);
        }
      }
    }
  }
}

function configuraModalBasico(cosas) {
  //  console.log(cosas);
  let accion = cosas.accion;
  let sku = cosas.articulo.sku;
  let titulo = cosas.titulo;
  let cuerpo = cosas.cuerpo;
  let txtBoton = cosas.txtBoton;
  let id = cosas.articulo.id;
  let autoUpdate;
  let btnConfirmarAccionBasica = document.getElementById(
    "btn-confirmar-accion-basica"
  );
  //  var modalAccionBasicaArticulo = new bootstrap.Modal(document.getElementById('AccionesBasicasArticuloModal')); //Mostramos el modal para eliminar

  for (ca of cosas.articulo.custom_attributes) {
    //Recorremos los custom attributes
    //    console.log(ca);
    if (
      ca.attribute_code == "auto_actualizable" ||
      ca.attribute_code == "auto_actualiza_precio"
    ) {
      autoUpdate = ca.value;
    }
  }
  //console.log('autoUpdate');
  //console.log(autoUpdate);
  //Colocamos los datos en la ventana modal

  document.getElementById("AccionesBasicasArticuloModalTitulo").innerHTML =
    titulo;
  document.getElementById("AccionesBasicasArticuloModalCuerpo").innerHTML =
    cuerpo;
  btnConfirmarAccionBasica.innerHTML = txtBoton;
  btnConfirmarAccionBasica.setAttribute("data-sku", sku); //Colocamos la id del artículo en el botón de eliminar en el modal
  btnConfirmarAccionBasica.setAttribute("data-id", id); //Colocamos la id del artículo en el botón de eliminar en el modal
  btnConfirmarAccionBasica.setAttribute("data-auto-actualizar", autoUpdate); //Colocamos la id del artículo en el botón de eliminar en el modal

  if (accion === "cambiar status") {
    btnConfirmarAccionBasica.setAttribute("data-estado", cosas.estado); //Colocamos la id del artículo en el botón de eliminar en el modal
    //  let compras = dameCompras(cosas.articulo);
    /*  if (compras.enInforpor.normal_inforpor.Cod == "No Datos" && compras.encontrado === undefined) { //Si no hay compras lo indicamos
      document.getElementById('AccionesBasicasArticuloModalCuerpo').innerHTML += '<p class="text-danger">Ojo, no encontramos compras para este artículo. Tal vez sea mejor eliminarlo. <a href="#" id="btn-eliminar-alt">¿Lo hacemos?</a></p>';
    }*/
  }
  btnConfirmarAccionBasica.setAttribute("data-accion", accion); //Colocamos la id del artículo en el botón de eliminar en el modal
  new bootstrap.Modal(AccionesBasicasArticuloModal, "").show();

  var btnEliminarAlt = document.getElementById("btn-eliminar-alt"); //Capturamos el click en el texto de eliminiar
  btnEliminarAlt.addEventListener("click", function (event) {
    let cosas = {
      accion: "eliminar",
      plataforma: plataforma,
      articulo: {
        sku: btnConfirmarAccionBasica.getAttribute("data-sku"),
        id: btnConfirmarAccionBasica.getAttribute("data-id"),
      },
    };

    let resultado = hacerCosas(cosas);
  });
}
function dameCompras(articulo, listadoTarifa) {
  if (listadoTarifa === undefined) {
    let datos;
    miTarifa = llamadaJson("ajax/google/dame-tarifa-2022.php", datos); //Pedimos la tarifa si no la tenemos ya
    listadoTarifa = damelistado(miTarifa); //Obtenemos el listado plano
  }
  //console.log(listadoTarifa);
  let refArticulo = articulo.sku;

  let d = {
    mpn: refArticulo,
    codinfo: articulo.sku,
    id_tienda: articulo.idTienda,
  };
  let resultado = {};
  resultado.infoArticulo = llamadaJson(
    "ajax/magento/dame-info-articulo.php",
    d
  ); //Buscamos la info detallada de cada artículo
  resultado.enInforpor = llamadaJson("ajax/inforpor/obtener-compra.php", d); //Buscamos la info en inforpor
  resultado.encontrado = listadoTarifa.find(
    (art) => art.referencia.trim() == articulo.sku
  );
  return resultado;
}

/**
 * 
 * @param {*} listaBusqueda 
 * @param {*} datosBusqueda 
 * @returns 
 */
async function colocaResultados(listaBusqueda, datosBusqueda) {
  //console.log(listaBusqueda);
  let items = listaBusqueda.hasOwnProperty("items")
    ? listaBusqueda.items
    : listaBusqueda; //Cogemos los items recibidos
  let tabla = document.getElementById("tablaResultados");
  let cabeceraTabla = tabla.querySelector("thead");
  tablaResultados.querySelector("tbody").innerHTML = ""; //Lo primero que hacemos en borrar los resultados actuales
  if (items.length > 0) {
    //Si tenemos resultados los colocamos
    cabeceraTabla.style.display = "table-header-group";
    /*  if (listaBusqueda.length >= numResultados) { //Si tenemos una página completa de resultados mostramos la paginación
        document.getElementById('paginacion').style.display = 'block';
      }*/
    //console.log(datosBusqueda);
    paginacion(listaBusqueda, datosBusqueda);
    //console.log(plataforma);
    let showPVP =
      (datosBusqueda.bmarca == 3 && plataforma == "mage") ||
      (datosBusqueda.bmarca == 1 && plataforma == "mage245")
        ? true
        : false;

    if (showPVP === true) {
      //Si hemos seleccionado la marca Epson colocamos la columna PVP y pedimos la tarifa
      document.getElementById("th-pvp").style.display = "table-cell";
      let d;
      var listadoTarifaEpson = llamadaJson("ajax/google/tarifa-epson.php", d); //Buscamos la info en la
    }

    //Recorremos los items recibidos al hacer la búsqueda
    for (fila of items) {
      //      console.log('fila');
      //console.log(fila);
      let customAttr = fila.custom_attributes;
      let enTiendas = fila.extension_attributes.website_ids;
      let precioOferta;
      let finOferta;
      let autoActualizable;
      var formattedDate;
      for (ca of customAttr) {
        //Recorremos los custom attributes
        //  console.log(ca);
        if (ca.attribute_code === "special_price") {
          //Precio de oferta
          precioOferta = parseFloat(ca.value);
        }
        if (precioOferta > 0 && ca.attribute_code === "special_to_date") {
          //Fin oferta (solo si hay precio oferta)
          finOferta = new Date(ca.value);
          
        }
        if (
          (ca.attribute_code === "auto_actualizable" && plataforma == "mage") ||
          (ca.attribute_code === "auto_actualiza_precio" &&
            plataforma == "mage245")
        ) {
          autoActualizable = ca.value;
        }
      }

      //console.log(precioOferta);
      //console.log(finOferta);
      //console.log(finOferta - Date.now());

      let tr = document.createElement("tr"); //Añadimos una fila por resultado
      tr.setAttribute("data-id", fila.id);
      tr.setAttribute("referencia", fila.sku);

      //Acciones si el artículo está deshabilitado
      if (fila.status == 2) {
        tr.classList.add("text-gray-400"); //Ponemos toda la fila en gris clarito
      }
      tablaResultados.querySelector("tbody").appendChild(tr);

      //Añadimos otra fila para manejar las ofertas
let trOfertas = document.createElement('tr');
trOfertas.id = 'offer-row-' + fila.id;
trOfertas.className = 'collapse offer-row p-3 bg-light';
tablaResultados.querySelector("tbody").appendChild(trOfertas);
      //Añadimos una segunda fila para cada artículo
let trAcciones = document.createElement('tr');
trAcciones.id = 'fila-'+fila.id;
trAcciones.className = 'collapse';
tablaResultados.querySelector("tbody").appendChild(trAcciones);



//Añadimos el nuevo botón para desplegar
let tdAcc = document.createElement("td"); //Añadimos la celda con info
//tdAcc.innerHTML = '<a href="#fila-'+fila.id+'" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="#fila-'+fila.id+'"><i class="fa-regular fa-plus text-info"></i></a><a href="#offer-row-'+fila.id+'" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="#offer-row-'+fila.id+'"><i class="fa-solid fa-certificate text-info"></i></a>';
tdAcc.innerHTML = '<a href="#offer-row-'+fila.id+'" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="#offer-row-'+fila.id+'"><i class="fa-solid fa-certificate text-info"></i></a>';
tr.appendChild(tdAcc);


      let tdInfo = document.createElement("td"); //Añadimos la celda con info
      tdInfo.innerHTML =
        '<i class="fa-solid fa-triangle-exclamation" style="display: none; float: left" tabindex="0" data-bs-toggle="popover" data-bs-trigger="hover focus" data-bs-placement="top"></i><i class="fa-solid fa-info" style="display: none" tabindex="0" data-bs-toggle="popover" data-bs-trigger="hover focus" data-bs-placement="top"></i>';
      tr.appendChild(tdInfo);

      let tdMpn = document.createElement("td"); //Añadimos la celda con la referencia
      tdMpn.innerHTML = fila.sku;
      tr.appendChild(tdMpn);

      let tdNombre = document.createElement("td"); //Añadimos la celda con el nombre
      tdNombre.innerHTML = fila.name;
      tdNombre.classList.add("nombre", "editable-text");
      tdNombre.id = fila.sku;
      tr.appendChild(tdNombre);

      let tdStock = document.createElement("td"); //Añadimos una celda vacía
      tdStock.classList.add("stock");
      tr.appendChild(tdStock);
      let tdCompra = document.createElement("td"); //Añadimos una celda vacía
      tdCompra.classList.add("compra");
      tr.appendChild(tdCompra);

      //Celda con el precio
      let tdPrecio = document.createElement("td"); //Añadimos la celda con el precio de venta
      let tdPrecioA = document.createElement("a"); //Añadimos un enlace;
      tdPrecioA.setAttribute("href", "#");
      tdPrecioA.setAttribute("data-bs-toggle", "modal");
      tdPrecioA.setAttribute("data-bs-target", "#modal-cambiar-precio");
      tdPrecioA.classList.add("btn-cambia-precio");
      tdPrecio.classList.add("importe");

      tdPrecio.appendChild(tdPrecioA);
      tr.appendChild(tdPrecio);

      let tdPvp = document.createElement("td"); //Añadimos una celda vacía
      if (showPVP === true) {
        let enTarifaEpson = listadoTarifaEpson.find(
          (art) => art.mpn == fila.sku
        );

        let pvpEpson = enTarifaEpson !== undefined ? enTarifaEpson.pvp : "-";

        if (fila.price != pvpEpson) {
          //Comprobamos si precio y PVP son iguales, en caso contrario vamos a poner un enlace botón
          let tdPvpA = document.createElement("a"); //Añadimos una celda vacía
          tdPvpA.setAttribute("href", "#");
          tdPvpA.classList.add("btn-confirmar");
          tdPvpA.setAttribute("data-pvp", pvpEpson);
          tdPvpA.innerHTML = isNaN(pvpEpson)
            ? "-"
            : new Intl.NumberFormat("es-ES", {
                style: "currency",
                currency: "EUR",
              }).format(pvpEpson);
          tdPvp.appendChild(tdPvpA);
        } else {
          tdPvp.innerHTML = new Intl.NumberFormat("es-ES", {
            style: "currency",
            currency: "EUR",
          }).format(pvpEpson);
        }
      } else {
        tdPvp.style.display = "none";
      }
      tr.appendChild(tdPvp);

      let tdMargen = document.createElement("td"); //Añadimos una celda vacía
      tdMargen.classList.add("margen");
      tr.appendChild(tdMargen);

      let tdAcciones = document.createElement("td"); //Añadimos la celda con el margen
      tdAcciones.innerHTML =
        '<i class="fa-solid fa-trash btn-borra-articulo" data-sku="' +
        fila.sku +
        '" style="color: red; cursor: pointer"></i>'; //Opción de borrar el artículo
      let icoBtnStatus = fila.status == 2 ? "fa-toggle-off" : "fa-toggle-on";
      tdAcciones.innerHTML +=
        '<i class="fa-solid ' +
        icoBtnStatus +
        ' btn-toggle-status" data-sku="' +
        fila.sku +
        '" style="cursor: pointer"></i>'; //Activar / Desactivar el artículo
      let colorAutoActualizar =
        autoActualizable == "1" ? "text-success" : "text-gray-300";
      tdAcciones.innerHTML +=
        '<i class="fa-solid fa-robot ' +
        colorAutoActualizar +
        ' btn-toggle-auto-actualizar" style="cursor: pointer"></i>'; //Auto actualizar
      tdAcciones.innerHTML +=
        '<i class="fa-solid fa-shop btn-seleccionar-tiendas" style="cursor: pointer"></i>'; //Opción de borrar el artículo
      tdAcciones.innerHTML +=
        '<i class="fa-solid fa-o btn-odoo" style="cursor: pointer"></i>'; //Opción de crear en odoo el artículo

      tdAcciones.setAttribute(
        "data-art-mage",
        JSON.stringify(fila).replace(/[\/\(\)\']/g, "&apos;")
      );
      tr.appendChild(tdAcciones);
//Celda que vamos a utilizar para poner los datos de la oferta
let tdOferta = document.createElement("td");
tdOferta.colSpan = 5;
tdOferta.className = 'p-3';
let rowTdOferta = document.createElement('div');
rowTdOferta.className='row g-3 align-items-center';
tdOferta.appendChild(rowTdOferta);
let colDivOffer=document.createElement('div');
colDivOffer.className='col-auto';
rowTdOferta.appendChild(colDivOffer);
let colFormCheck=document.createElement('div');
colFormCheck.className='form-check';
colDivOffer.appendChild(colFormCheck);
let inputCheck=document.createElement('input');
inputCheck.type='checkbox';
inputCheck.className = 'form-check-input checkOferta';
inputCheck.value = '';
inputCheck.id = 'checkOferta';
inputCheck.setAttribute('data-id',fila.id);
colFormCheck.appendChild(inputCheck);
let labelInput=document.createElement('label');
labelInput.className = 'form-check-label';
labelInput.htmlFor = 'checkOferta';
labelInput.innerText = 'cambiar';
colFormCheck.appendChild(labelInput);
let divCol2=document.createElement('div');
divCol2.className='col-auto';
rowTdOferta.appendChild(divCol2);
let inputPrecioOferta=document.createElement('input');
inputPrecioOferta.type='number';
inputPrecioOferta.step='any';
inputPrecioOferta.min='0';
inputPrecioOferta.max='99999';
inputPrecioOferta.disabled = true;
inputPrecioOferta.className='form-control inputOfertaPrecio';
inputPrecioOferta.placeholder='precio';
inputPrecioOferta.ariaDescribedBy='precio';
divCol2.appendChild(inputPrecioOferta);
let divCol3=document.createElement('div');
divCol3.className='col-auto';
rowTdOferta.appendChild(divCol3);
let inputFecha = document.createElement('input');
inputFecha.type='date';
inputFecha.disabled = true;
inputFecha.className='form-control inputOfertaFecha';
divCol3.appendChild(inputFecha);
let divCol4=document.createElement('div');
divCol4.className='col-auto';
rowTdOferta.appendChild(divCol4);
let btnPonOferta = document.createElement('button');
btnPonOferta.type="submit";
btnPonOferta.className='btn btn-primary disabled btn-enviar-oferta';
btnPonOferta.setAttribute('data-bs-toggle','modal');
btnPonOferta.setAttribute('data-bs-target','#resultadoModal');
btnPonOferta.setAttribute('data-sku',fila.sku);
btnPonOferta.setAttribute('data-accion','actualizar articulo');
//btnPonOferta.onclick = enviarOferta;
btnPonOferta.innerHTML = 'enviar';
divCol4.appendChild(btnPonOferta);

trOfertas.appendChild(tdOferta);
//Celda que vamos a utilizar para poner los datos de la oferta
let tdBtnsOferta = document.createElement("td");
tdBtnsOferta.colSpan = 5;
//tdBtnsOferta.innerHTML = '<div class="d-grid gap-2 d-md-block"><button class="btn btn-success m-1 btn-add-time btn-sm" data-measure="week" type="button">+1 semana</button><button class="btn btn-success m-1 btn-add-time btn-sm" data-measure="month" type="button">+1 mes</button><button class="btn btn-success m-1 btn-add-time btn-sm" data-measure="quarter" type="button">+3 meses</button><button class="btn btn-success m-1 btn-add-time btn-sm" data-measure="year" type="button">+1 año</button><button class="btn btn-danger m-1 boton-eliminar" type="button">Eliminar oferta</button></div>';
let grupoBtns = document.createElement('div');
grupoBtns.className='d-grid gap-2 d-md-block';
tdBtnsOferta.appendChild(grupoBtns);
let btnSemana = document.createElement('div');
btnSemana.className='btn btn-success m-1 btn-add-time btn-sm disabled';
btnSemana.innerHTML = '+1 semana';
btnSemana.setAttribute('data-measure','week');
btnSemana.setAttribute('data-sku',fila.sku);
btnSemana.setAttribute('data-accion','actualizar articulo');
grupoBtns.appendChild(btnSemana);
let btnMes = document.createElement('div');
btnMes.className='btn btn-success m-1 btn-add-time btn-sm disabled';
btnMes.innerHTML = '+1 mes';
btnMes.setAttribute('data-measure','month');
btnMes.setAttribute('data-sku',fila.sku);
btnMes.setAttribute('data-accion','actualizar articulo');
grupoBtns.appendChild(btnMes);
let btn3Mes = document.createElement('div');
btn3Mes.className='btn btn-success m-1 btn-add-time btn-sm disabled';
btn3Mes.innerHTML = '+3 meses';
btn3Mes.setAttribute('data-measure','quarter');
btn3Mes.setAttribute('data-sku',fila.sku);
btn3Mes.setAttribute('data-accion','actualizar articulo');
grupoBtns.appendChild(btn3Mes);
let btnYear = document.createElement('div');
btnYear.className='btn btn-success m-1 btn-add-time btn-sm disabled';
btnYear.innerHTML = '+1 año';
btnYear.setAttribute('data-measure','year');
btnYear.setAttribute('data-sku',fila.sku);
btnYear.setAttribute('data-accion','actualizar articulo');
grupoBtns.appendChild(btnYear);
let btnRemove = document.createElement('div');
btnRemove.className='btn btn-danger m-1 boton-eliminar btn-sm disabled';
btnRemove.innerHTML = 'Eliminar oferta';
btnRemove.setAttribute('data-sku',fila.sku);
btnRemove.setAttribute('data-accion','eliminar oferta');
grupoBtns.appendChild(btnRemove);

trOfertas.appendChild(tdBtnsOferta);

//Acciones si este artículo tiene un precio de oferta fijado
if (precioOferta !== undefined){
  inputPrecioOferta.value = precioOferta; //Ponemos el precio en el input de oferta
  inputFecha.value = formateaFecha(finOferta); //Ponemos la fecha igualmente aunque esté desfasada
  let articulo = {
    sku: fila.sku,
  }
  let semanaMas = finOferta;
  semanaMas.setDate(semanaMas.getDate()+7);
  let semana = {
    ...articulo,
    custom_attributes: [{attribute_code: 'special_to_date', value: formateaFecha(semanaMas)}]
  }
  btnSemana.setAttribute("data-articulo", JSON.stringify(semana).replace(/[\/\(\)\']/g, "&apos;")); //Colocamos la id del artículo en el botón de eliminar en el modal
  btnSemana.classList.remove('disabled'); //Habilitamos los botones para añadir tiempo a la oferta de forma rápida
  btnSemana.setAttribute('data-bs-toggle','modal');
  btnSemana.setAttribute('data-bs-target','#resultadoModal');


  let mesMas = finOferta;
  mesMas.setMonth(mesMas.getMonth()+1);
  let mes = {
    ...articulo,
    custom_attributes: [{attribute_code: 'special_to_date', value: formateaFecha(mesMas)}]
  }
  btnMes.setAttribute("data-articulo", JSON.stringify(mes).replace(/[\/\(\)\']/g, "&apos;")); //Colocamos la id del artículo en el botón de eliminar en el modal
  btnMes.classList.remove('disabled');
  btnMes.setAttribute('data-bs-toggle','modal');
  btnMes.setAttribute('data-bs-target','#resultadoModal');

    let trimestrMas = finOferta;
    trimestrMas.setMonth(trimestrMas.getMonth()+3);
  let trimestre = {
    ...articulo,
    custom_attributes: [{attribute_code: 'special_to_date', value: formateaFecha(trimestrMas)}]
  }
  btn3Mes.setAttribute("data-articulo", JSON.stringify(trimestre).replace(/[\/\(\)\']/g, "&apos;")); //Colocamos la id del artículo en el botón de eliminar en el modal
  btn3Mes.classList.remove('disabled');
  btn3Mes.setAttribute('data-bs-toggle','modal');
  btn3Mes.setAttribute('data-bs-target','#resultadoModal');
  
  let yearMas = finOferta;
  yearMas.setFullYear(yearMas.getFullYear()+1);
let year = {
  ...articulo,
  custom_attributes: [{attribute_code: 'special_to_date', value: formateaFecha(yearMas)}]
}
  btnYear.setAttribute("data-articulo", JSON.stringify(year).replace(/[\/\(\)\']/g, "&apos;")); //Colocamos la id del artículo en el botón de eliminar en el modal
  btnYear.classList.remove('disabled');
  btnYear.setAttribute('data-bs-toggle','modal');
  btnYear.setAttribute('data-bs-target','#resultadoModal');

  let limpiar = {
    ...articulo,
    custom_attributes: [{attribute_code: 'special_to_date', value: ''},{attribute_code: 'special_price', value: ''}]
  }
  btnRemove.setAttribute("data-articulo", JSON.stringify(limpiar).replace(/[\/\(\)\']/g, "&apos;")); //Colocamos la id del artículo en el botón de eliminar en el modal

  btnRemove.classList.remove('disabled');
  btnRemove.setAttribute('data-bs-toggle','modal');
  btnRemove.setAttribute('data-bs-target','#resultadoModal');
  if ( //Si la oferta está vigente mostramos el precio tachado y el precio de oferta
    finOferta !== undefined &&
    finOferta - Date.now() > 0
  ) {
    let spanPrecio = document.createElement("span");
    spanPrecio.style.textDecoration = "line-through";
    spanPrecio.style.fontSize = "0.8em";
    spanPrecio.innerHTML = fila.price;
    tdPrecioA.appendChild(spanPrecio);
    tdPrecioA.innerHTML += new Intl.NumberFormat("es-ES", {
      style: "currency",
      currency: "EUR",
    }).format(precioOferta);
    tdPrecio.setAttribute("importe", precioOferta);
  } 
} else {
  tdPrecioA.innerHTML = new Intl.NumberFormat("es-ES", {
    style: "currency",
    currency: "EUR",
  }).format(fila.price);
  tdPrecio.setAttribute("importe", fila.price);
}

//celda con los botones que afectan a los atributos del artículo
  let tdAtributos = document.createElement('td');
  tdAtributos.colSpan = 10;
  tdAtributos.innerHTML = '<button type="button" class="btn btn-danger" style="--bs-btn-padding-y: .25rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .75rem;"><i class="fa-solid fa-trash"></i> eliminar</button>';
  trAcciones.appendChild(tdAtributos);    
      /*  let iTdAcciones = tdAcciones.getElementsByTagName('i');

console.log(iTdAcciones);
for (v of iTdAcciones) {
v.setAttribute('data-art-mage',JSON.stringify(fila).replace(/[\/\(\)\']/g, "&apos;"));
}*/

      /*let tdStock = document.createElement('td'); //Añadimos la celda con el nombre
      tdStock.innerHTML = fila.name;
      tr.appendChild(tdStock);*/
    }
    var popoverTriggerList = [].slice.call(
      document.querySelectorAll('[data-bs-toggle="popover"]')
    );
    var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
      return new bootstrap.Popover(popoverTriggerEl);
    });
  } else {
    //Si no tenemos resultados de búsqueda
    cabeceraTabla.style.display = "none";
    tablaResultados.querySelector("tbody").innerHTML =
      '<div class="w-25" style="width:100%;height:0;padding-bottom:100%;position:relative;"><iframe src="https://giphy.com/embed/DRYU7xgNIJbzQjOOBH" width="100%" height="100%" style="position:absolute" frameBorder="0" class="giphy-embed" allowFullScreen></iframe></div><p><a href="https://giphy.com/gifs/bucks-DRYU7xgNIJbzQjOOBH">via GIPHY</a></p>';
    console.log("no hay resultados");
  }

  let btnBorrarArticulo = document.querySelectorAll(".btn-borra-articulo"); //Capturamos el click en borrar artículo
  btnBorrarArticulo.forEach((i) => {
    i.addEventListener("click", function (event) {
      //CApturamos el click en siguiente página
      let cosas = {
        accion: "eliminar",
        articulo: JSON.parse(i.parentNode.getAttribute("data-art-mage")),
        sku: JSON.parse(i.parentNode.getAttribute("data-art-mage")).sku,
        titulo: "¿Eliminamos esto?",
        cuerpo:
          "Si haces click en eliminar el artículo será borrado de magento, y esto es algo que no se puede deshacer.",
        txtBoton: "Eliminar",
      };
      //      console.log(i);
      configuraModalBasico(cosas);
    });
  });

  let btnToggleAutoActualizar = document.querySelectorAll(
    ".btn-toggle-auto-actualizar"
  ); //Capturamos el click en auto actualizar
  btnToggleAutoActualizar.forEach((i) => {
    i.addEventListener("click", function (event) {
      //CApturamos el click
      let cosas = {
        accion: "cambiar auto actualizar",
        articulo: JSON.parse(i.parentNode.getAttribute("data-art-mage")),
        titulo:
          "¿Vamos a cambiar la configuración de auto actualizar de este artículo?",
        cuerpo:
          "Vamos a cambiar la configuración de auto actualizar de este artículo. O sea, que si está desactivado lo vamos a desactivar.",
        txtBoton: "Cambiar",
      };
      configuraModalBasico(cosas);
    });
  });

  let btnSeleccionaTiendas = document.querySelectorAll(
    ".btn-seleccionar-tiendas"
  ); //Capturamos el click en borrar artículo
  btnSeleccionaTiendas.forEach((i) => {
    i.addEventListener("click", function (event) {
      //CApturamos el click en siguiente página
      let articulo = JSON.parse(i.parentNode.getAttribute("data-art-mage"));
      let webs = articulo.extension_attributes.website_ids; //Un array con las id de las webs donde existe el artículo
      let enTpl = webs.includes(1) === true ? "checked" : ""; //Comprobamos si existe en cada una de las tiendas y generamos el checked si es así
      let enTsl = webs.includes(2) === true ? "checked" : "";
      let enTsb = webs.includes(3) === true ? "checked" : "";
      let enFut = webs.includes(4) === true ? "checked" : "";

      let cosas = {
        accion: "seleccionar tiendas",
        articulo: articulo,
        titulo: "¿En que tiendas debe aparecer el artículo?",
        cuerpo:
          '<div class="form-check form-switch"> <input class="form-check-input" type="checkbox" id="switchFutura" ' +
          enFut +
          '><label class="form-check-label" for="switchFutura">Futura.es</label></div><div class="form-check form-switch"> <input class="form-check-input" type="checkbox" id="switchTiendaplotter" ' +
          enTpl +
          '><label class="form-check-label" for="switchTiendaplotter">Tiendaplotter.com</label></div><div class="form-check form-switch"> <input class="form-check-input" type="checkbox" id="switchTiendasolvente" ' +
          enTsl +
          '>  <label class="form-check-label" for="switchTiendasolvente">Tiendasolvente.com</label></div><div class="form-check form-switch"> <input class="form-check-input" type="checkbox" id="switchTiendasublimacion" ' +
          enTsb +
          '>  <label class="form-check-label" for="switchTiendasublimacion">Tiendasublimacion.com</label></div>',
        txtBoton: "Cambiar",
      };
      configuraModalBasico(cosas);
    });
  });

  //Cuando cambiamos el estado del check para la oferta de ese articulo
  let btnCheckOferta = document.querySelectorAll(".checkOferta"); //Capturamos el click en el check de cambiar oferta
  btnCheckOferta.forEach(i => {
    i.addEventListener('change', (e)=>{
      let fila = document.querySelector('#offer-row-'+e.target.dataset.id); //Seleccionamos la fila actual
      fila.querySelectorAll('input[type]:not([type="checkbox"])').forEach( o => { //Cambiamos el estado de los inputs
        o.disabled = !o.disabled;
      });
      fila.querySelector('.btn-enviar-oferta').classList.toggle('disabled');
     
      console.log('e', e);
    });
    });

    let inputOfertaPrecio = document.querySelectorAll('.inputOfertaPrecio');
    inputOfertaPrecio.forEach(i => {
      i.addEventListener('change', (e)=>{
        colocaDatosOferta(e);
      });
    });
    let inputOfertaFecha = document.querySelectorAll('.inputOfertaFecha');
    inputOfertaFecha.forEach(i => {
      i.addEventListener('change', (e)=>{
        colocaDatosOferta(e);
      });
    });


  let btnToggleStatusArticulo = document.querySelectorAll(".btn-toggle-status"); //Capturamos el click en borrar artículo
  btnToggleStatusArticulo.forEach((i) => {
    i.addEventListener("click", function (event) {
      //CApturamos el click en siguiente página
      let cosas = {
        accion: "cambiar status",
        articulo: JSON.parse(i.parentNode.getAttribute("data-art-mage")),
        estado: JSON.parse(i.parentNode.getAttribute("data-art-mage")).status,
        titulo: "¿Vamos a cambiar el estado de este artículo?",
        cuerpo:
          "Vamos a cambiar el estado de este artículo. O sea, que si está desactivado lo vamos a desactivar.",
        txtBoton: "Cambiar",
      };
      configuraModalBasico(cosas);
    });
  });

  let modalConfirmar = new bootstrap.Modal(
    document.getElementById("ConfirmacionModal")
  ); //Mostramos el modal para eliminar

  let btnConfirmar = document.querySelectorAll(".btn-confirmar"); //Capturamos el click en borrar artículo
  btnConfirmar.forEach((i) => {
    i.addEventListener("click", function (event) {
      //CApturamos el click en siguiente página
      let dataMage = JSON.parse(
        i.parentNode.nextElementSibling.nextElementSibling.getAttribute(
          "data-art-mage"
        )
      );
      let pvp = i.getAttribute("data-pvp");

      let cosas = {
        accion: "poner pvp",
        titulo: "Fijar el PVP como precio",
        cuerpo: "¿Seguro que quieres fijar el PVP como precio?",
        txtBoton: "Dale",
        articulo: dataMage,
        precio: pvp,
      };

      configuraModalConfirmar(cosas);

      //modalConfirmar.show();
    });
  });

  /**
   * Acción al abrir el modal para modificar el precio
   */
  document
    .getElementById("modal-cambiar-precio")
    .addEventListener("show.bs.modal", (event) => {
      const button = event.relatedTarget;
      let dataMage = JSON.parse(
        button.parentNode.nextElementSibling.nextElementSibling.nextElementSibling.getAttribute(
          "data-art-mage"
        )
      );
      let precioCompraTd =
        button.parentNode.previousElementSibling.querySelector("span")
          ? button.parentNode.previousElementSibling
              .querySelector("span")
              .getAttribute("compra")
          : 0;
      configModalCambiaPrecio(dataMage, precioCompraTd);
    });



  /////////////////
  btnCambiarPrecio.addEventListener('click', e => {
    e.preventDefault();
   // const button = e.target;
  //  console.log('button',button);
  });


  let btnOdoo = document.querySelectorAll(".btn-odoo");
  btnOdoo.forEach((i) => {
    i.addEventListener("click", function (event) {
      //CApturamos el click en siguiente página
      dataMage = JSON.parse(i.parentNode.getAttribute("data-art-mage"));
      console.log(dataMage);
      let datos = { 2: dataMage.sku };
      let buscaArt = llamadaJson("ajax/odoo/buscar-articulo.php", datos); //Buscamos el artículo en odoo
      if (buscaArt.length === 0) {
        $(".crear-articulo-loader").hide();
        let misCats = filtrarCategorias(); //Recuperamos el listado de categorias
        misCats.forEach((cat, i) => {
          //Recorremos el listado de categorias
          let option = document.createElement("option"); //Creamos un elemento option
          option.text = cat.complete_name; //Asignamos como texto el nombre completo de la categoria
          option.value = cat.id; //Asignamos como valor la id
          seleccionarCategoria.add(option); //Añadimos la opcion creada a la lista
        });
        let listaFabricantes = listarMarcas();

        listaFabricantes.forEach((fabricante, i) => {
          //Recorremos el listado de categorias
          let option = document.createElement("option"); //Creamos un elemento option
          option.text = fabricante; //Asignamos como texto el nombre completo de la categoria
          option.value = fabricante; //Asignamos como valor la id
          seleccionarFabricante.add(option);
        });
        let selects = Object.values(
          document.getElementsByClassName("select-articulo")
        ); //Los desplegables
        activaBoton(selects); //Al abrir el modal ejecutamos la funcion una vez para habilitar o no el botón
        selects.forEach((item, i) => {
          //El evento que escucha cualquier cambio
          item.addEventListener("change", function (event) {
            activaBoton(selects);
          });
        });
        //   console.log(misCats);
        new bootstrap.Modal(modalCrearArticulo, "").show();
      } else {
        alert("este articulo ya existe");
      }
    });
  });

  document
    .getElementById("btn-crear-articulo")
    .addEventListener("click", function (event) {
      //CApturamos el click en crear artículo
      event.preventDefault;
      // console.log(dataMage);
      let datos = {
        modelo: "product.product",
        arr: {
          categ_id: seleccionarCategoria.value, //Categoria, la cogemos del select
          name: dataMage.name, //Nombre del artículo
          default_code: dataMage.sku, //referencia
          type: "product", //Tipo de producto almacenable
          x_studio_fabricante: seleccionarFabricante.value,
        },
      };
      //console.log('datos para crear');
      //console.log(datos);
      let crearArticulo = EntradaOdoo(datos, "crear"); //Creamos el artículo

      if (crearArticulo.hasOwnProperty("id")) {
        datos = {
          campo_busqueda: "default_code",
          valor_antiguo: dataMage.sku,
          campo_actualizar: "list_price",
          valor_nuevo: dataMage.price,
          modelo: "product.template",
        };
        let actualizarArticulo = EntradaOdoo(datos, "actualizar"); //Creamos el artículo
        if (actualizarArticulo === "ok") {
          new bootstrap.Modal(modalExito, "").show();
        }
        /*  if (actualizarArticulo === 'ok') {
        datos.campo_actualizar = 'standard_price';
        datos.valor_nuevo = precioIfp;
        actualizarArticulo = EntradaOdoo(datos, 'actualizar'); //Creamos el artículo
        if (actualizarArticulo === 'ok') {
          crearArticuloModal.hide();
        }
      }*/
      }
    });

  $(".editable-text").editable("ajax/magento/jeditable.php", {
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

  return listaBusqueda;
}

/**
 * EN DESUSO, SE SUSTITUYE POR REALIZARBUSQUEDA
 * Funcion que ejecuta la búsqueda
 */

/*function hazBusqueda(pagina = 1, filtro = {}) {
  params.set("p", pagina);
  window.history.replaceState({}, "", `${window.location.pathname}?${params}`); // reemplazamos el historial del navegador con esta nueva querystring
  let nombre = inputNombre.value;
  let ref = inputReferencia.value;
  let conjunto = document.getElementById(
    "seleccionar-conjunto-atributos"
  ).value;
  let fabricante = document.getElementById("seleccionar-fabricante").value;
  let numResultados = document.getElementById("resultados-pagina").value;
  let tIfp;
  if (
    nombre.length < 3 &&
    ref.length < 3 &&
    isNaN(conjunto) &&
    isNaN(fabricante) &&
    filtro == {}
  ) {
    //Si no hemos seleccionado nada mostramos un error
    alert("Selecciona datos para la búsqueda");
  } else {
    datosBusqueda = {
      bnombre: nombre,
      mpn: ref,
      tipoarticulo: conjunto,
      bmarca: fabricante,
      ap: numResultados,
      p: pagina,
      idTienda: idTienda,
    };
    //Añadimos los campos que pasamos desde los botones
    for (const [key, value] of Object.entries(filtro)) {
      datosBusqueda[key] = value;
    }

    // console.log(datosBusqueda);

    let listaBusqueda = buscar(datosBusqueda);
    //console.log(listaBusqueda);
    //colocaResultados(listaBusqueda, datosBusqueda);
    colocaResultados(listaBusqueda, datosBusqueda).then((listaBusqueda) => {
      getTarifas(tarifas)
        .then((tarifass) => {
          tarifas = tarifass;
          let obj = {
            articulos: listaBusqueda,
            tarifas: tarifass,
          };
          return obj;
        })
        .then((obj) => {
          compraArticulosWorker(obj);
        });

      //    console.log('listaBusqueda');
      //   console.log(listaBusqueda);
    }); //De momento no colocamos las compras
  }
}*/

/**
 * Función nueva que realiza la búsqueda
 */
function realizaBusqueda(pagina = 1, campos = []) {
  //console.log('p',pagina);
  params.set("p", pagina);
  window.history.replaceState({}, "", `${window.location.pathname}?${params}`); // reemplazamos el historial del navegador con esta nueva querystring
  let nombre = inputNombre.value;
  let ref = inputReferencia.value;
  let conjunto = document.getElementById(
    "seleccionar-conjunto-atributos"
  ).value;
  let fabricante = document.getElementById("seleccionar-fabricante").value;
  let numResultados = document.getElementById("resultados-pagina").value;
  //Comprobamos que tenemos datos para la búsqueda
  if (
    nombre.length < 3 &&
    ref.length < 3 &&
    isNaN(conjunto) &&
    isNaN(fabricante) &&
    campos.length == 0
  ) {
    //Si no hemos seleccionado nada mostramos un error
    alert("Selecciona datos para la búsqueda");
  } else {
    //Añadimos el nombre si lo estamos recibiendo
    if (nombre.length >= 3) {
      let campoNombre = {
        field: "name",
        value: nombre,
        condition_type: "like",
      };
      campos.push(campoNombre);
    }
    //Añadimos la referencia si lo estamos recibiendo
    if (ref.length >= 3) {
      let campoRef = {
        field: "sku",
        value: ref,
        condition_type: "eq",
      };
      campos.push(campoRef);
    }
    //Añadimos el conjunto de atributos si lo estamos recibiendo
    if (!isNaN(conjunto)) {
      let campoConjunto = {
        field: "attribute_set_id",
        value: conjunto,
        condition_type: "eq",
      };
      campos.push(campoConjunto);
    }
        //Añadimos el fabricante si lo estamos recibiendo
        if (!isNaN(fabricante)) {
          let campoFabricante = {
            field: plataforma == 'mage245' ? "product_brand" : "manufacturer",
            value: fabricante,
            condition_type: "eq",
          };
          campos.push(campoFabricante);
        }

    let busqueda = {
      currentPage: pagina,
      pageSize: parseInt(numResultados),
      fields: campos,
      idTienda: plataforma == 'mage245' ? 2 : 1,
     // developer: true       //Le vamos a añadir un campo developer para poder desarrollar mientras funcionamos
    };
    let listaBusqueda = buscar(busqueda);
    colocaResultados(listaBusqueda, busqueda).then((listaBusqueda) => {
      getTarifas(tarifas)
        .then((tarifass) => {
          tarifas = tarifass;
          let obj = {
            articulos: listaBusqueda,
            tarifas: tarifass,
          };
          return obj;
        })
        .then((obj) => {
          compraArticulosWorker(obj);
        });

      //    console.log('listaBusqueda');
      //   console.log(listaBusqueda);
    }); 

    console.log('listaBusqueda', listaBusqueda);
  }
}

//Lanzamos la búsqueda especial de ofertas finalizando
function ofertasFinalizando() {
  //let finOferta;
  let campos = []; //array con todos los campos que vamos a añadir
  let periodo = 7; //Por defecto cogemos 7 dias
  let fecha = new Date(); //Creamos un objeto fecha
  let fIni = fecha.getDate();
  fecha.setDate(fecha.getDate() + periodo); //Como queremos las ofertas caducadas restamos un dia a ayer
  let dia = fecha.getDate(); //Obtenemos el dia del mes
  let mes = fecha.getMonth() + 1; //Obtenemos el mes, hay que sumarle uno porque van de 0 a 11
  let anyo = fecha.getFullYear();
  let finOferta = anyo + "-" + mes + "-" + dia;
  let fInicio = anyo + "-" + mes + "-" + fIni;
  let campoFinOferta = {
    field: "special_to_date",
    value: finOferta,
    condition_type: "lt",
  };
  campos.push(campoFinOferta);

  let fechaInicio = {
    field: "special_to_date",
    value: fInicio,
    condition_type: "gt",
  }
  campos.push(fechaInicio);
  realizaBusqueda(1, campos);

  //console.log('finalizando');
}

function ofertasCaducadas() {
  let campos = []; //array con todos los campos que vamos a añadir
 // let periodo = 7; //Por defecto cogemos 7 dias
  let fecha = new Date(); //Creamos un objeto fecha
  fecha.setDate(fecha.getDate() - 1); //Como queremos las ofertas caducadas restamos un dia a ayer
  let dia = fecha.getDate(); //Obtenemos el dia del mes
  let mes = fecha.getMonth() + 1; //Obtenemos el mes, hay que sumarle uno porque van de 0 a 11
  let anyo = fecha.getFullYear();
  let finOferta = anyo + "-" + mes + "-" + dia;
  let campoFinOferta = {
    field: "special_to_date",
    value: finOferta,
    condition_type: "lt",
  };
  campos.push(campoFinOferta);
  realizaBusqueda(1, campos);

}

function enviarOferta(e) {
  let fila = e.target.parentNode.parentNode.parentNode.parentNode;
  let fId = fila.id.substr(-4); //Nos quedamos con los 4 últimos caracteres, que son la id
  let sku = fila.parentNode.querySelector('tr[data-id="'+fId+'"]').getAttribute('referencia');
let precioOferta = e.target.parentNode.parentNode.querySelector('input[type="number"]').value;
let fechaFin = e.target.parentNode.parentNode.querySelector('input[type="date"]').value;

let articulo = {
  sku: sku,
  custom_attributes: [
    {attribute_code: 'special_to_date', value: fechaFin},
    {attribute_code: 'special_price', value: precioOferta}
  ]
}
let cosas = {
  accion: 'actualizar articulo',
  articulo: articulo
}
hacerCosas(cosas);
  //console.log('enviar oferta',articulo);
  
}

function colocaDatosOferta(e){
  let fila = e.target.parentNode.parentNode.parentNode.parentNode;
  let precioOferta = e.target.parentNode.parentNode.querySelector('input[type="number"]').value;
  let fechaFin = e.target.parentNode.parentNode.querySelector('input[type="date"]').value;
  let btnOferta = e.target.parentNode.parentNode.querySelector('button');
  let articulo = {
        custom_attributes: [
      {attribute_code: 'special_to_date', value: fechaFin},
      {attribute_code: 'special_price', value: precioOferta}
    ]
  }
  btnOferta.setAttribute("data-articulo", JSON.stringify(articulo).replace(/[\/\(\)\']/g, "&apos;")); //Colocamos la id del artículo en el botón de eliminar en el modal

 // console.log(btnOferta);
}




document.addEventListener("DOMContentLoaded", function(){
  // Handler when the DOM is fully loaded

document
.getElementById("resultadoModal")
.addEventListener("show.bs.modal", (event) => {
  event.preventDefault;
  const button = event.relatedTarget;
  let articulo = JSON.parse(button.getAttribute('data-articulo'));
  if (!articulo.hasOwnProperty('sku')) {
    articulo.sku = button.getAttribute("data-sku")
  }
 let cosas = {
    accion: button.getAttribute("data-accion"),
    plataforma: plataforma,
 //   sku: button.getAttribute("data-sku"),
    articulo: articulo/*{
      sku: button.getAttribute("data-sku"),
      precio: document.getElementById("inputCambiarPrecio").value,
      cost: margenActual.getAttribute("compra"),
    },*/
    
  };
 console.log('articulo', cosas); 
  let resultados = hacerCosas(cosas); //Recibimos un array con cada plataforma y resultado
  console.log('resultado', resultados); 

  for (const resultado of resultados) {
    let lPl;
    switch (resultado.id_tienda) {
      case 2: //Magento 2.4.6
        lPl = listaPlataformas.querySelector(
          '[data-plataforma="mage245"] span'
        );
        break;
        case 1: //Magento 2.2
        lPl = listaPlataformas.querySelector(
          '[data-plataforma="mage"] span'
        );
        break;
    }
    let str;
    switch (resultado.resultado) {
      case "ok":
        str = "fa-check text-white bg-success";
        break;
      case "ko":
        str = "fa-question text-white bg-danger";
        break;

      default:
        str = "fa-question text-warning";
        break;
    }

    lPl.innerHTML = '<i class="fa-solid ml-3 p-1 ' + str + '"></i>';
    //lPl.innerHTML = 'ssss';
  }
});
});

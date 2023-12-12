
function dameTlfo(contacto){
    let tlfo;
    if (contacto.phone_sanitized != false) {
        tlfo = contacto.phone_sanitized;
      } else{
      
        tlfo = contacto.mobile !== false ? contacto.mobile : contacto.phone;
      }
      return tlfo;
}

function dameProvincia(contacto){
    let d = {
        modelo: 'res.country.state',
        campo: 'id',
        valor: contacto.state_id[0] 
    }
let buscaProvincia = llamadaJson('./ajax/odoo/busqueda.php',d)[0];
return buscaProvincia.name;
}

/**
 * Funcion que pide los datos necesarios para mostrar y gestionar un pedido de odoo
 * @param {object} infoPedido 
 * @returns 
 */
function generaDatosmodalOdoo(infoPedido){
    /**
     * pedimos los datos de facturacion
     * 
     */
    let d = {
      modelo: 'res.partner',
      campo: 'id',
      valor: infoPedido.direccion_factura.id
    }
    let clienteFactura = llamadaJson('./ajax/odoo/busqueda.php',d)[0];
    let tlfo = dameTlfo(clienteFactura);
let dirCompleta = clienteFactura.street2 !== false ? clienteFactura.street + ' - ' + clienteFactura.street2 : clienteFactura.street;

    let cFactura = {
      email: clienteFactura.email,
      telefono: tlfo,
      nif: clienteFactura.vat,
      direccion: dirCompleta,
      ciudad: clienteFactura.city,
      provincia: dameProvincia(clienteFactura),
      codigo_postal: clienteFactura.zip,
      empresa: clienteFactura.display_name,
      nombre_completo: clienteFactura.display_name
    }
    
    /**
     * Pedimos los de envío si son distintos
     */
    let cEnvio;
    if (infoPedido.direccion_factura.id != infoPedido.direccion_envio.id) {
        d.valor = infoPedido.direccion_envio.id;
      let clienteEnvio = llamadaJson('./ajax/odoo/busqueda.php',d)[0];
      let dirCompletaEnvio = clienteEnvio.street2 !== false ? clienteEnvio.street + ' - ' + clienteEnvio.street2 : clienteEnvio.street;

      let tlfo = dameTlfo(clienteEnvio);
      cEnvio = {
        email: clienteEnvio.email,
        telefono: tlfo,
        nif: clienteEnvio.vat,
        direccion: dirCompletaEnvio,
        ciudad: clienteEnvio.city,
        provincia: dameProvincia(clienteEnvio),
        codigo_postal: clienteEnvio.zip,
        empresa: clienteEnvio.display_name,
        nombre_completo: clienteEnvio.display_name
      }
    } else {
      cEnvio = cFactura;
    }
    
    /**
     * Pedimos los artículos
     */
    let lineas_pedido = [];
    infoPedido.lineas_pedido.forEach(element => {
      let d = {
        'modelo': 'sale.order.line',
        'campo': 'id',
        'valor': element.id
      }
      let articulo = llamadaJson('./ajax/odoo/busqueda.php',d)[0];

      let l = {
        mpn: articulo.x_studio_referencia_1,
        nombre: articulo.name,
        cantidad: articulo.product_qty,
        importe: articulo.price_unit
      };
      let buscaAttrsBd = llamadaJson('./ajax/unicorn_db/dame-atributos.php',{mpn: articulo.x_studio_referencia_1});

      if (buscaAttrsBd.hasOwnProperty(5)) {
        l.codinfo = buscaAttrsBd[5];
      }
      lineas_pedido.push(l);

     // console.log(l);
      //console.log(buscaAttrsBd);
    });
    
    let cliente = {
      direccion_factura: cFactura,
      direccion_envio : cEnvio,
      lineas_pedido: lineas_pedido,
      nif: cFactura.nif,
      id: infoPedido.id
    }
    console.log(cliente);
    return cliente;
    }

    /**
 * [crearCliente description]
 * @param  {[type]} cliente [description]
 * @return {[type]}         [description]
 */
function EntradaOdoo(datos, accion) {
  let url;
  switch (accion) {
    case 'crear':
      url = "ajax/odoo/crear.php";
      break;
    case 'actualizar':
      url = "ajax/odoo/actualizar.php";
      break;

  }
  return JSON.parse($.ajax({
    type: "POST",
    url: url,
    dataType: "json",
    global: false,
    async: false,
    data: { "datos": JSON.stringify(datos) },
    success: function (data, textStatus, jqXHR) {
      return data;
    },
    error: function (data, textStatus, jqXHR) {
      console.log('Error: ' + JSON.stringify(data));
    },
  }).responseText);
}

/**
 * Buscamos el cliente en odoo a partir de los datos recibidos de la plataforma de ventas. Busca el cliente para cada campo que hayamos configurado (NIF, tlfo, nombre completo, etc).
 * @param  Object estePedido Objeto pedido a partir de los datos de pedidos
 * @return JSON   Devuelve un objeto compuesto por propiedades y el resultado para cada busqueda (NIF: {}, email: {cliente odoo},etc)
 */
function buscaClienteOdoo(estePedido) {
  let arrBusqueda;
  //console.log('estePedido');
  //console.log(estePedido);
  //     let tlfo = typeof estePedido.direccion_factura.phone !== 'undefined' ? estePedido.direccion_factura.phone : estePedido.direccion_factura.telephone;
  let tlfo = estePedido.direccion_factura.telefono;
  let nombreCompleto = estePedido.direccion_factura.nombre_completo;
  let email = estePedido.direccion_factura.email;
  let buscaCliente = {                                             //Definimos los campos por los que vamos a buscar
    nif: estePedido.direccion_factura.nif,
    tlfo: tlfo,
    //    nombreCompleto: nombreCompleto, //Dejamos de pasar el nombre para que no lo busque
    email: email
  };
  //console.log('xxx');
  //console.log(buscaCliente);
  let arr = Object.entries(buscaCliente);
  let resultadoBusqueda = {};
  for (i in arr) {
    let datos = {};

    let campo = arr[i][0];
    let valor = arr[i][1];
    datos[campo] = valor;
    //console.log(datos);
    resultadoBusqueda[campo] = llamadaJson('ajax/odoo/buscar-cliente.php', datos);

    //console.log(resultadoBusqueda);
  }
  arrBusqueda = resultadoBusqueda;

  return arrBusqueda;
}

/**
 * Depura los datos de la busqueda de cliente y devuelve la ficha del cliente tal cual o false
 * @param  Object estePedido Objeto pedido a partir de los datos de pedidos
 * @return Object            Cliente de odoo o string vacio
 */
function dameFichaCliente(buscarClienteOdoo) {
  //var buscarClienteOdoo = buscaClienteOdoo(estePedido);                        //Buscamos al cliente en odoo
  let fichaCliente = false;

  let arr = Object.keys(buscarClienteOdoo);
  let c = arr.length;                                //Como es un objeto JSON usamos object keys para contar los indices
  let i = 0;
  while ((fichaCliente === false || fichaCliente.length === 0) && i < c) {
    if (buscarClienteOdoo[arr[i]].length > 0) {                                     //Si tenemos al menos un resultado es que recibimos datos
      let ficha = buscarClienteOdoo[arr[i]];
      for (s in ficha) {

        if (ficha[s].parent_id === false) {
          if (ficha[s].hasOwnProperty('property_payment_term_id')) {
            fichaCliente = { //Dejamos solo los campos necesarios de los clientes para no pasar tantos datos por POST
              id: ficha[s].id,
              child_ids: ficha[s].child_ids,
              name: ficha[s].name,
              property_payment_term_id: ficha[s].property_payment_term_id
            }
          } else {
alert('Este cliente no tiene definido un plazo de pago en su ficha. Arregla esto antes e inténtalo de nuevo');
          }

          // fichaCliente = ficha[s];

        }
      }
      //  fichaCliente = buscarClienteOdoo[arr[i]][0];
    }
    i++;
  }
  return fichaCliente;
}

function filtrarCategorias() {
  let datos = {
    modelo: "product.category",
  };
  let listaCategorias = llamadaJson("ajax/odoo/listar.php", datos); //Recuperamos el listado de categorias
  let categorias = listaCategorias.filter((cat) => cat.child_id.length === 0); //Filtramos para dejar sólo aquellas categorias que no tienen hijos
  categorias.sort((a, b) =>
    a.complete_name > b.complete_name
      ? 1
      : b.complete_name > a.complete_name
      ? -1
      : 0
  ); //Ordenamos las categorias por el nombre completo
  return categorias;
}

function listarMarcas() {
  datos = {
    modelo: "ir.model.fields",
    campo: "id",
    valor: 8352,
  };
  let llamadaListaFabricantes = llamadaJson("ajax/odoo/busqueda.php", datos); //Pedimos el listado de fabricantes en odoo
  let listaFabricantesTexts = llamadaListaFabricantes[0].selection;
  //let listaFabricantesIds = llamadaListaFabricantes[0].selection_ids;
  let lFab = [];
  //listaFabricantesTexts = "[('Aisens', 'Aisens'), ('Agfa', 'Agfa'), ('Beinsen', 'Beinsen'), ('Brother', 'Brother'), ('Canon', 'Canon'), ('Chemica', 'Chemica'), ('DigiStar', 'DigiStar'), ('EFI', 'EFI'), ('Epson', 'Epson'), ('Felix Schoeller', 'Felix Schoeller'), ('HP', 'HP'), ('Gcc', 'Gcc'), ('Genérico', 'Genérico'), ('Graphtec', 'Graphtec'), ('Herranz', 'Herranz'), ('Keencut', 'Keencut'), ('Kyocera', 'Kyocera'), ('Lexmark', 'Lexmark'), ('LG', 'LG'), ('Logitech', 'Logitech'), ('Mactac', 'Mactac'), ('Manoukian', 'Manoukian'), ('Mutoh', 'Mutoh'), ('Nanjing Getwin industial Co.,Ltd', 'Nanjing Getwin industial Co.,Ltd'), ('OKI', 'OKI'), ('Ovili', 'Ovili'), ('PNY', 'PNY'), ('Plastgrommet', 'Plastgrommet'), ('Poli-Tape', 'Poli-Tape'), ('Ricoh', 'Ricoh'), ('Ritrama', 'Ritrama'), ('Roland', 'Roland'), ('Sappi', 'Sappi'), ('S-Race', 'S-Race'), ('Samsung', 'Samsung'), ('Siser', 'Siser'), ('Sihl', 'Sihl'), ('SkateFlash', 'SkateFlash'), ('Talius', 'Talius'), ('Xerox', 'Xerox'), ('X-Rite', 'X-Rite')]";
  let arrFab = listaFabricantesTexts.split(/\'/); //Creamos un array
  let filtrado = arrFab.filter((a) => /^[a-zA-Z]/.test(a) === true); //Dejamos solo los nombres
  return [...new Set(filtrado)]; //Usamos set para crear una instancia sin duplicados
}
function activaBoton(selects) {
  let resultado = selects.map((este) => este.value);
  let inactivo = resultado.includes("0");
  if (inactivo === false) {
    document.getElementById("btn-crear-articulo").classList.remove("disabled"); //activamos el botón
  } else {
    document.getElementById("btn-crear-articulo").classList.add("disabled"); //desactivamos el botón
  }
}

function buscaArticulosOdoo(pd) {
  //var articulosVenta = [];
  //Buscamos los articulos del pedido
  var c = 0;
  var total = pd.lineas_pedido.length;
  pd.lineas_pedido.forEach(art => {
    //buscaArticulo(art);
    let datos = Object.keys(art.atributos_bd).length > 0 ? art.atributos_bd : { 2: art.mpn };
    let checkArt = document.getElementById(art.mpn).querySelector('.chkArticulo');
    let buscaArt = llamadaJson('ajax/odoo/buscar-articulo.php', datos);            //Buscamos el artículo en odoo
    if (buscaArt.length > 0) {
      console.log('bArt');
      console.log(buscaArt);
      //Comprobamos el total de artículos
      let t = document.getElementById('totalArticulos').getAttribute('data-total'); //Total actual
      let nT = t - 1; //descontamos uno
      document.getElementById('totalArticulos').setAttribute('data-total', nT); //Grabamos el nuevo total
      if (nT == 0) { //Si llegamos a 0 sustituimos el icono por un marcar de verificación
        document.getElementById('totalArticulos').classList.add('text-success');
        document.getElementById('totalArticulos').innerHTML = 'task_alt';
      }
      checkArt.classList.add('text-success');
      checkArt.innerHTML = 'task_alt';
      c++;
    } else {

      checkArt.classList.add('text-danger');
      checkArt.innerHTML = 'add_circle_outline';
      checkArt.style.cursor = "pointer";
      checkArt.setAttribute('data-bs-toggle', 'modal');
      checkArt.setAttribute('data-bs-target', '#crear-articulo-modal');
    }


  });
  return total - c;
}

function generaArticulosVenta(pedActual) {
  // console.log('pedActual');
  //console.log(pedActual);
  //    console.log(existeInforpor);
  var articulosVenta = [];
  for (p in pedActual.lineas_pedido) {
    let articuloIfp
    //let buscaArt = buscaArticuloOdoo(estePedido[0].lineas_pedido[p].atributos_bd);
    datos = pedActual.lineas_pedido[p].atributos_bd;                        //Preparamos los datos que vamos a Buscar
    if (datos.hasOwnProperty('length') === true && datos.length === 0) {          //Si tenemos los datos vacios pasamos al menos el mpn
      //  datos[2] = pedActual.lineas_pedido[p].mpn;
      datos = {
        2: pedActual.lineas_pedido[p].mpn
      }
    }

    /*if (existeInforpor.CodErr == '0') {
    articuloIfp = existeInforpor['lineasPedR'];
    let atributosIfp = articuloIfp;
    }*/

    if (datos.hasOwnProperty('length') === false) {                                 //Si no podemos contar la longitud es porque recibimos datos

      let buscaArt = llamadaJson('ajax/odoo/buscar-articulo.php', datos);            //Buscamos el artículo en odoo
      //console.log(buscaArt);
      if (buscaArt.length > 0) {
        let descripcionArticulo = buscaArt[0].description_sale !== false ? buscaArt[0].description_sale : '';
        var lineaArt = {
          //  id : buscaArt[0].product_variant_id[0],                        //Id del artículo
          id: buscaArt[0].id,                        //Id del artículo
          nombre: buscaArt[0].name + ' ' + descripcionArticulo, //Nombre que vamos a utilizar
          precio: pedActual.lineas_pedido[p].importe /*/ pedActual.lineas_pedido[p].cantidad*/,  //Para Pc componentes era necesario realizar aquí la división, de momento lo deshabilitamos, puesto que en magento no es necesario
          cantidad: pedActual.lineas_pedido[p].cantidad,
          comision: pedActual.lineas_pedido[p].comision,
          atributos: datos,
          codCat: pedActual.lineas_pedido[p].cod_categoria
        }
        /*if (pedActual.lineas_pedido[p].hasOwnProperty('portes_pedido')) {           //Dejamos preparada la linea de portes para despues
          lineaArt.portes_pedido = pedActual.lineas_pedido[p].portes_pedido;
        }*/
        //console.log(typeof articuloIfp);
        if (typeof articuloIfp != 'undefined' && articuloIfp.length > 0) {            //Si el artículo tiene compra en inforpor lo añadimos
          for (i in articuloIfp) {
            if (datos[7] == articuloIfp[i].atributos_bd[7]) {
              lineaArt.precio_compra = articuloIfp[i].precio;
            }
          }
        } else {
          lineaArt.precio_compra = buscaArt[0].standard_price;
        }
        //  console.log(buscaArt);
        articulosVenta.push(lineaArt);
        //  articulosVenta.estePedido = estePedido[0].lineas_pedido[p];
        existeArt = 1;
        //    console.log(existeArt);
      }
      //console.log('lineaArt');
      //console.log(lineaArt);
    }
  }
  return articulosVenta;
}

function armageddon(objEstePedido) {
  $('#list-group-comprobacion').show();                                         //Mostramos el list group
  $('#btn-armageddon').attr('disabled', true);                                  //Deshabilitamos el botón para evitar duplicidades
  let almacen = $('#elige-almacen option:selected').val();
  let tipoPedido = $('#elige-tipo-pedido option:selected').val();
  if (parseInt(almacen) > 0) {
    objEstePedido.almacen = $('#elige-almacen option:selected').val();
    objEstePedido.tipoPedido = $('#elige-tipo-pedido option:selected').val();
    let grabaVenta = llamadaJson('ajax/odoo/crear-presupuesto-venta.php', objEstePedido);                                //La venta (Debemos tenerla siempre)
    if (typeof grabaVenta == 'number') {
      $('#crear-presupuesta-venta').addClass('list-group-item-info').append(' id ' + grabaVenta + '<span class="badge rounded-pill"><i class="fas fa-4x fa-check"></i></span>');
    } else {
      $('#crear-presupuesta-venta').addClass('list-group-item-danger').append(' no creada. ' + grabaVenta + '<span class="badge rounded-pill"><i class="fas fa-4x fa-times"></i></span>');

    }

//Revisar, con las modificaciones creo que no se realiza este paso
    //crear-solicitar-compra
    if (objEstePedido.pedido_compra.CodErr == '0') {                              //Si estamos recibiendo datos de inforpor la procesamos en odoo
      let grabaCompra = llamadaJson('ajax/odoo/crear-solicitud-compra.php', objEstePedido);
      if (typeof grabaCompra == 'number') {                                           //Si recibimos el número de solicitud
        $('#crear-solicitar-compra').addClass('list-group-item-info').append(' id ' + grabaCompra + '<span class="badge rounded-pill"><i class="fas fa-4x fa-check"></i></span>');
      } else {                                                                        //En caso contrario analizamos la causa
        if (grabaCompra == 'Todos los articulos son de custodias') {                  //No se crea la compra al ser todo custodias
          $('#crear-solicitar-compra').addClass('list-group-item-warning').append(' no creada. ' + grabaCompra + '<span class="badge rounded-pill"><i class="fas fa-4x fa-times"></i></span>');
        } else {                                                                      //Causa desconocida
          $('#crear-solicitar-compra').addClass('list-group-item-danger').append(' no creada. ' + grabaCompra + '<span class="badge rounded-pill"><i class="fas fa-4x fa-times"></i></span>');

        }
      }
    } else {
      $('#crear-solicitar-compra').addClass('list-group-item-warning').append(' .No hay compra que grabar. <span class="badge rounded-pill"><i class="fas fa-4x fa-times"></i></span>');
    }
    //console.log(objEstePedido);
  } else {
    alert('Es obligatorio seleccionar el almacen');
  }
}

/**
 * [crearCliente description]
 * @param  {[type]} cliente [description]
 * @return {[type]}         [description]
 */
function crearCliente(cliente) {
  var datos = cliente;
  $.ajax({
    type: "POST",
    url: "ajax/odoo/crear-cliente.php",
    data: datos,
    success: function (data, textStatus, jqXHR) {
      barraProgresoCompleta();
      $('.comprueba-cliente-cuerpo').html('<img src="img/muscular-unicorn.gif" class="img-fluid" alt="cliente creado">');
      setTimeout(function () {
        $('.comprueba-cliente-cuerpo').html('<p class="text-center">Hemos creado el cliente!</p>');
      }, 5000);
      chkCliente = true;
      //  alert(JSON.stringify(jqXHR));
      // location.reload();
      //  console.log(data);
      //    console.log('ok');
      //  return data;
      // $(bloque).append(data);
    },
    error: function (data, textStatus, jqXHR) {
      // alert("error:" + respuesta);
      // console.log(respuesta);
      alert('Error: ' + data);
      //location.reload();
    },
  });
}
/**
 * 
 * @param {*} pedido 
 * @param {*} datos  //Un array de objetos con los pedido de odoo o undefined
 * @returns 
 */
function buscaCompraOdoo(pedido) {
//pedido.ventaOdoo = pedVentaOdoo.querySelector('a').innerHTML;
console.log(pedVentaOdoo.querySelectorAll('a'));

  /*let nRef;
  switch (pedido.tienda) {
    case "mirakl":
      nRef = "PC" + pedido.id.substring(0, pedido.id.length - 2);
      break;
    case "mage":
    default:
      nRef = pedido.id;
      break;
  }
for (const key in datos) {
  datos[key]['nRef'] = nRef;
}*/
  
  let compraOdoo = llamadaJson("ajax/odoo/buscar-solicitud-compra.php", pedido);
  //console.log('compraOdoo');
  //console.log(compraOdoo);
  return compraOdoo;
}
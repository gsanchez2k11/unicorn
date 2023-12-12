/**Equivalente a document ready pero sin Jquery */
document.addEventListener("DOMContentLoaded", function(event) { 
  ///////// EVENTOS ///////////////////////////    
  resultadoPedidoModal.addEventListener('shown.bs.modal', e => {
    grabaPedido(e);
  });//Capturamos el click en el btn buscar

  });

  function grabaPedido(e){
   //Tenemos acceso al carrito
   let refCliente = document.getElementById('ref-cliente').value;
   let notas = document.getElementById('notasPedido').value; //CApturamos las notas
   let direccionEnvio = dameDireccionEnvio(cliente);
   let clienteDatBasico = {
    id: cliente.id,
    property_payment_term_id: cliente.property_payment_term_id,
    customer_payment_mode_id: cliente.customer_payment_mode_id,
    property_product_pricelist: cliente.property_product_pricelist
  }
  let objEstePedido = {
    dir_envio: direccionEnvio,
    plataforma: 'clientes',
    //    pedido_venta: pedidoVenta,
    articulos_venta: carrito,
    cliente: clienteDatBasico
  };
  objEstePedido.pedido_venta = refCliente == 'true' ? {
    id: 'ref. cliente ' + refCliente
  } : ''; //Si tenemos la referencia de cliente la añadimos
  // console.log(objEstePedido);

 let grabaVenta = llamadaJson('../ajax/odoo/crear-presupuesto-venta-dev.php', objEstePedido)[0]; //La venta (Debemos tenerla siempre)
 creandoPedido.classList.add('visually-hidden');
 footerModalResultado.classList.remove('visually-hidden');
  let testPres = /^SO\d{5}/.test(grabaVenta.name); //Comprobamos si recibimos una referencia válida
  if (testPres === true) { //Si recibimos el número de presupuesto mostramos el modal
    pedidoExito.classList.remove('visually-hidden');
    //Comprobamos si tenemos notas para grabar
    if (notas.length > 0) {
      let d = {
        datos: {
          modelo: 'mail.message'
        }
      };

      d.datos.arr = {
        description: notas,
        body: notas,
        model: 'sale.order',
        res_id: parseInt(grabaVenta.id),
        record_name: grabaVenta.name,
        subtype_id: 2, //Subtipo nota
        message_type: 'comment',

      }
      let grabaNota = llamadaJson('../ajax/odoo/crear.php', d); //Grabamos la nota
    }

        //Vaciamos el carrito
        carrito = [];
        dibujaCarritoDev(carrito);
 





   // var myModal = new bootstrap.Modal(document.getElementById('PedidoExito'), {
   //   keyboard: false
   // })
   // myModal.show();
    //Notificamos a quien sea necesario
    let msjInterno = 'Hola, el cliente ' + cliente.name + ' ha creado un nuevo presupuesto con referencia ' + grabaVenta.name + '.';
    if (notas.length > 0) {
      msjInterno += notas;
    }
    let correo = {
      destinatarios: ['compras@futura.es'],
      asunto: 'presupuesto creado',
      cuerpo: msjInterno
    }
    let enviarCorreo = llamadaJson('../ajax/otras/enviar-mail.php', correo);

    let correoCliente = {
      destinatarios: [cliente.email],
      asunto: 'Gracias por pedir a Futura Teck',
      cuerpo: '¡Hola ' + cliente.name + '!, acabamos de recibir tu pedido con referencia ' + grabaVenta.name + '. En breve lo tendrás en la dirección indicada'
    }
    enviarCorreo = llamadaJson('../ajax/otras/enviar-mail.php', correoCliente);

        //Inabilitamos el historial de pedidos para que el cliente actualice
    document.getElementById('actualiza-pedidos').style.display = 'block';
  } else {
    pedidoError.classList.remove('visually-hidden');
  }

  }
  


/**
 * Función que carga todos los artículos de la tarifa de un cliente con sus descuentos. El problema es que para tarifas complejas el rendimiento es pobre
 * @param  {int} idTarifa               id de la tarifa que queremos cargar
 * @return {array}          Array con los artículos
 */
/*async*/ function cargaMiTarifa(idTarifa) {
  /*  let loaderTarifa = document.getElementById('loader-tarifa'); //Escondemos el loader de la tarifa
    loaderTarifa.style.display = 'block';
    let datos = {
      campo: 'id',
      valor: idTarifa,
      modelo: 'product.pricelist'
    }
    let tarifa = llamadaJson('../ajax/odoo/busqueda.php', datos);
    let itemTarifa = tarifa[0].item_ids;*/
  let itemsTarifa = misArticulosTarifa(idTarifa);
  let miTarifa = [];
  for (i of itemsTarifa) {
    datos = {
      campo: 'id',
      valor: i,
      modelo: 'product.pricelist.item'
    }
    let infoItem = llamadaJson('../ajax/odoo/busqueda.php', datos);
    let item = infoItem[0];
    if (item.product_tmpl_id !== false) {

      let miArray = item.product_tmpl_id[1].split(" ");
      let ref = miArray[0].trim();
      let art = [ref.substr(1, ref.length - 2), item.name, item.percent_price, item.product_tmpl_id[0]];
      miTarifa.push(art);
    }
  }

  return miTarifa;


}

/**
 * Función que devuelve las entradas en la tabla product.pricelist.item para una tarifa dada.
 * @param  {int} idTarifa               id de la tarifa que queremos cargar
 * @return {array}          array con las entradas
 */
function misArticulosTarifa(idTarifa) {
  //let loaderTarifa = document.getElementById('loader-tarifa'); //Escondemos el loader de la tarifa
  //loaderTarifa.style.display = 'block'; //Lo deshabilitamos mientras no lo usamos
  let datos = {
    campo: 'id',
    valor: idTarifa,
    modelo: 'product.pricelist'
  }
  let tarifa = llamadaJson('../ajax/odoo/busqueda.php', datos);
  let itemsTarifa = tarifa[0].item_ids;
  //  let salida = tarifa;
  return itemsTarifa;
}
/**
 * Buscamos en odoo mediante like y retornamos un array con los resultados
 * @param  {[type]} datos               [description]
 * @return {[type]}       [description]
 */
function buscaLikeOdoo(datos) {
  let resultadosBusqueda = [];
  let buscaArt;
  let i = 0;
  do {
    buscaArt = llamadaJson('../ajax/odoo/like.php', datos);             //Buscamos en odoo
    /*console.log('buscaArt');
    console.log(buscaArt);*/
    for (let articulo of buscaArt) {
      resultadosBusqueda.push(articulo);
    }
    datos.offset = datos.offset + 10;
    i++;
  } while (buscaArt.length === 10 && i <= 10);
  return resultadosBusqueda;
}

function buscaEqOdoo(datos) {
  let resultadosBusqueda = [];
  let buscaArt;
  let i = 0;
  datos.offset = 0;
  //console.log('d');
  //console.log(datos);
  do {
    buscaArt = llamadaJson('../ajax/odoo/busqueda.php', datos);             //Buscamos en odoo
    /*console.log('buscaArt');
    console.log(buscaArt);*/
    for (let articulo of buscaArt) {
      resultadosBusqueda.push(articulo);
    }
    datos.offset = datos.offset + 10;
    i++;
  } while (buscaArt.length === 10 && i <= 10);
  return resultadosBusqueda;
}

function buscaPricelistItem(arr) { //Buscamos en la lista de items de tarifa
 // console.log('arr');
 // console.log(arr);
  let resultados = [];
  let dd = { //Primero buscamos por categoria
    'modelo': 'product.pricelist.item',
    'campo': 'categ_id',
    'valor': arr[1]
  }
  //console.log(dd);
  let buscaCat = buscaEqOdoo(dd);
  console.log('buscaCat');
  console.log(buscaCat);
  console.log(idTarifa);
  if (buscaCat.length > 0) {
    resultados.push(buscaCat);
  } //else {

  //Tengamos resultados o no hacemos todas las búsquedas
    dd.campo = 'product_id';
    dd.valor = arr[2];
    let productId = buscaEqOdoo(dd);

    if (productId.length > 0) {
      resultados.push(productId);
    } //else { //Si no buscamos por la plantilla
      dd.campo = 'product_tmpl_id';
      dd.valor = arr[0];
  
      let buscaTmpl = buscaEqOdoo(dd);
      if (buscaTmpl.length > 0) {
        resultados.push(buscaTmpl);
      }
   // }

  //}

  let salida = resultados.length > 0 ? resultados[0].filter(r => r.pricelist_id[0] == idTarifa) : resultados; //Filtramos para dejar solo los descuentos de esta tarifa
  return salida;

}

/**
 * 
 * @param {*} termino  término de búsqueda
 * @param {*} miTarifa resultado de buscar los items de la tarifa del cliente en cuestión
 */
function buscarOdoo(termino, miTarifa) {
  setTimeout(() => {
    let datos = {
      modelo: 'product.product',
      campo: 'default_code',
      valor: termino.toUpperCase(),
      offset: 0
    }

    var busquedaRef = buscaLikeOdoo(datos); //Buscamos por referencia en Odoo, admite busquedas parciales

    //Buscamos siempre también por nombre
    datos.campo = 'name';
    datos.valor = termino.toLowerCase();
    datos.offset = 0;
    var busquedaNombre = buscaLikeOdoo(datos); //Buscamos por referencia en Odoo
    //Concatenamos los arrays
    let rBusqueda = busquedaRef.concat(busquedaNombre);
    var articulosx = [...new Map(rBusqueda.map(v => [v.id, v])).values()]; //Eliminamos los duplicados para mostrar
    var articulos = articulosx.filter(a => a.purchase_ok === true && a.type == 'product');
    let cuerpoTabla = document.getElementById('cuerpo-tabla-resultados');
   // console.log(articulos);
      articulos.forEach(articulo => {
//Vamos a buscar el descuento de manera secuencial, primero por artículo y si no hay descuento buscamos por categoría, de esta manera priorizamos el descuento por artículo
        let obj = {
          ref: articulo.default_code,
          nombre: articulo.name,
      //    descuento: dto,
        //  stock: 0,
          idPlantilla: articulo.product_tmpl_id[0],
          idProducto: articulo.id,
          uomId: articulo.uom_id[0],
          id: articulo.id,
precio: articulo.list_price,
stock: articulo.qty_available,
precio_compra: articulo.standard_price,
categoria: articulo.categ_id[1]
        }

 //Buscamos el dto
 let objDto = buscaDto(obj,miTarifa);  
 obj.descuento =  objDto.tipo === 'percentage' ? objDto.cantidad : 0; 


   //     console.log('obj');
    //    console.log(obj);

        let tr = document.createElement('tr'); //Creamos la fila
        cuerpoTabla.appendChild(tr); //La añadimos a la tabla
let tdAcciones = document.createElement('td'); //Creamos la celda acciones
tdAcciones.setAttribute('data-mpn',articulo.default_code);
//tdAcciones.innerHTML = '<svg fill="none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" class="reportar-articulo" data-bs-toggle="modal" data-bs-target="#modalReportarArticulo"> <path d="M22 2H2v14h2V4h16v12h-8v2h-2v2H8v-4H2v2h4v4h4v-2h2v-2h10V2z" fill="currentColor"/> </svg>';
tdAcciones.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" width="16" class="reportar-articulo" data-bs-toggle="modal" data-bs-target="#modalReportarArticulo"> <path d="M8 2h2v4h4V2h2v4h2v3h2v2h-2v2h4v2h-4v2h2v2h-2v3H6v-3H4v-2h2v-2H2v-2h4v-2H4V9h2V6h2V2Zm8 6H8v3h8V8Zm-5 5H8v7h3v-7Zm2 7h3v-7h-3v7ZM4 9H2V7h2v2Zm0 10v2H2v-2h2Zm16 0h2v2h-2v-2Zm0-10V7h2v2h-2Z"/> </svg>';

tr.appendChild(tdAcciones); //La añadimos a la fila

        let tdMpn = document.createElement('td'); //Creamos la celda mpn
        tdMpn.innerHTML = articulo.default_code; //Contenido de la celda
        tr.appendChild(tdMpn); //La añadimos a la fila

        let tdNombre = document.createElement('td'); //Creamos la celda mpn
        tdNombre.innerHTML = articulo.name; //Contenido de la celda
        tr.appendChild(tdNombre); //La añadimos a la fila

        let tdPrecio = document.createElement('td'); //Creamos la celda mpn
        if (objDto.tipo == 'fixed') {
          obj.precio = objDto.cantidad; //Si recibimos un importe fijo modificamos el objeto
          tdPrecio.classList.add('text-info','precio-popover');
          tdPrecio.setAttribute('data-bs-toggle','popover');
          tdPrecio.setAttribute('data-bs-content','Este artículo tiene un precio especial para ti');
          tdPrecio.setAttribute('data-bs-placement','left');
          tdPrecio.setAttribute('data-bs-trigger','hover focus');
         }
        tdPrecio.innerHTML = accounting.formatMoney(obj.precio, { symbol: "€", format: "%v %s" });; //Contenido de la celda
        tr.appendChild(tdPrecio); //La añadimos a la fila

        let tdDto = document.createElement('td'); //Creamos la celda mpn
        tdDto.innerHTML = obj.descuento + '%'; //Contenido de la celda
        tr.appendChild(tdDto); //La añadimos a la fila

        let tdStock = document.createElement('td'); //Creamos la celda mpn
        tdStock.id = 'stock-'+articulo.id;
        tr.appendChild(tdStock); //La añadimos a la fila

        let tdComprar = document.createElement('td'); //Creamos la celda mpn
        tdComprar.innerHTML = '<div class="input-group mb-1 carrito"><input type="number" class="form-control" placeholder="qty" aria-label="cantidad" aria-describedby="button-addon2" value="1"><button class="btn btn-outline-secondary al-carrito" data-art=\'' + JSON.stringify(obj).replace(/[\(\)\']/g, "&apos;") + '\' type="button" onclick="alCarritoDev(this)"><svg fill="none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16"> <path d="M2 2h4v4h16v11H4V4H2V2zm4 13h14V8H6v7zm0 4h3v3H6v-3zm14 0h-3v3h3v-3z" fill="currentColor"/> </svg></button></div>'; //Contenido de la celda
        tr.appendChild(tdComprar); //La añadimos a la fila



      });
//Activamos los popovers
      const popoverTriggerList = document.querySelectorAll('[data-bs-toggle="popover"]')
      const popoverList = [...popoverTriggerList].map(popoverTriggerEl => new bootstrap.Popover(popoverTriggerEl))


    document.getElementById('busqueda-cargando').classList.add('visually-hidden'); //Ocultamos el loader y mostamos el resultado
    document.getElementById('busqueda-resultados').classList.remove('visually-hidden');

    //console.log('rb');
    //console.log(articulos);
   // return articulos;
    workerStock(articulos);
  }, 2000);
  
}


function clickBuscarDev(miTarifa = {}) {

  let resultadosBusqueda;
  var textoBuscar = document.getElementById('input-buscar');
  let termino = textoBuscar.value;
  if (termino.length < 3) {                                                       //Comprobamos si al menos hemos introducido 3 caracteres
    alert('por favor, introduce 3 o más caracteres para buscar'); //mostramos un mensaje si no
  } else {
    //Modificamos el ancho del icono y el cuadro de busqueda
    document.getElementById('row-busqueda-img').classList.add('col-md-2');
    document.getElementById('row-busqueda-img').classList.remove('col-md-3');
    document.getElementById('row-busqueda-data').classList.remove('col-md-9');
    document.getElementById('row-busqueda-data').classList.add('col-md-10');
    //Mostramos la leyenda del stock
    document.getElementById('leyenda-stock').classList.remove('visually-hidden');


    document.getElementById('cuerpo-tabla-resultados').innerHTML = ''; //Vaciamos los resultados de la búsqueda
    document.getElementById('busqueda-vacio').classList.add('visually-hidden');//Ocultamos la presentacion y mostramos el loader
    document.getElementById('busqueda-cargando').classList.remove('visually-hidden');
    let rBuscar = buscarOdoo(termino,miTarifa); //Buscamos

//Una vez hemos colocado los artículos colocamos el stock


  }

}


/**
 * [clickBuscar description]
 * @param  {[type]} miTarifa               Items obtenidos de la id de tarifa del cliente
 * @return {[type]}          [description]
 */
function clickBuscar(miTarifa = {}) {
  //Mostramos el loader

  let resultadosBusqueda;
  var textoBuscar = document.getElementById('input-buscar');
  let termino = textoBuscar.value;

  if (termino.length < 3) {                                                       //Comprobamos si al menos hemos introducido 3 caracteres
    alert('por favor, introduce 3 o más caracteres para buscar'); //mostramos un mensaje si no
  } else {
    let rBuscar = buscarOdoo(termino);


    let divs = document.getElementById('resultados-busqueda');
    divs.parentNode.parentNode.style.display = 'flex'; //la tarjeta que contiene los resultados

    if ($.fn.dataTable.isDataTable('#tabla-resultados')) {
      $('#tabla-resultados').DataTable().destroy();
    }
    //Ocultamos todos los divs

    for (hijoDiv of divs.childNodes) {
      //  console.log(hijoDiv.id);
      if (hijoDiv.id !== undefined) {
        hijoDiv.style.display = 'none';
      }
    }


    let datos = {
      modelo: 'product.product',
      campo: 'default_code',
      valor: termino.toUpperCase(),
      offset: 0
    }

    resultadosBusqueda = buscaLikeOdoo(datos); //Buscamos por referencia en Odoo

    if (resultadosBusqueda.length === 0) { //Si no tenemos resultados vamos a buscar por nombre
      datos.campo = 'name';
      datos.valor = termino.toLowerCase();
      // datos.modelo = 'product.product';
      datos.offset = 0;
      resultadosBusqueda = buscaLikeOdoo(datos); //Buscamos por referencia en Odoo
      // console.log('resultadosBusquedaNombre');
      //console.log(resultadosBusquedaNombre);
    }

    switch (true) {
      case (resultadosBusqueda.length === 0):
        document.getElementById('no-resultados').style.display = 'flex';

        break;
      case (resultadosBusqueda.length > 100):
        document.getElementById('demasiados-resultados').style.display = 'flex';
        break;
      default:
        let resultados = [];
        for (item of resultadosBusqueda) {     //Recorremos los resultados de la busqueda
          console.log('item');
          console.log(item);
          if (item.product_tmpl_id !== false) {  //Si tenemos el template del artículo

            let miArray = item.display_name.split(" ");
            let ref = miArray[0].trim();
            let refLimpia = ref.substr(1, ref.length - 2);
            let dName = item.display_name.split("]")[1];
            //Buscamos el artículo en la tarifa
            let dto = dameDto(item.product_tmpl_id[0], item.categ_id[0], idTarifa);
            //        let dto = 0;
            //       let filteredArray = miTarifa.filter(value => item.item_ids.includes(value)); //hacemos un array intersect entre los items ids del articulo y los de la tarifa del cliente
            let stockOdoo = item.qty_available; //El stock en odoo del artículo
            let stockIfp = stockOdoo > 0 ? stockOdoo : dameStockIfp(item);
            let stock = stockIfp > 0 ? '<i class="fas fa-2x fa-laugh-beam"></i>' : '<i class="fas fa-question"></i>';
            let uomId = item.uom_id[0];
            /*if (filteredArray.length > 0) { //Si tenemos resultados (deberiamos tener sólo uno) pedimos los datos de ese artículo
              let datDto = {
                modelo : 'product.pricelist.item',
                campo: 'id',
                valor: filteredArray[0],
                offset: 0
              }
                let buscaDto = llamadaJson('../ajax/odoo/busqueda.php',datDto);             //Buscamos en odoo
              dto = buscaDto[0].percent_price;
            } else {
              dto = 0; //En cualquier otro caso el descuento es 0
            }*/
            let art = [refLimpia, dName, dto, stock, item.product_tmpl_id[0], item.id, uomId]; //Referencia, nombre, descuento, stock, template id, id de producto
            resultados.push(art);
          }
        }
        colocaTarifa(resultados, 'tabla-resultados'); //Colocamos la tabla con los artículos
        $('#tabla-resultados').DataTable({
          'retrieve': true,
          'searching': false
        });

        document.getElementById('ok-resultados').style.display = 'flex';
        //     console.log(resultados);
        break;
    }
    // }); //Fin de las acciones

  }

};

/**
 * 
 * @param {*} obj Objeto con los datos de la linea de búsqueda
 * @param {*} miTarifa listado de entradas para la tarifa del cliente actual
 * @returns dto Objeto con las propiedas tipo y cantidad
 */
function buscaDto(obj,miTarifa) {
  let dto = {
    tipo: false,
    cantidad: null
  };
  let buscaTmpl = miTarifa.filter( a => a.product_tmpl_id[0] == obj.idPlantilla); //Buscamos primero por la plantilla
  if (buscaTmpl.length > 0 && buscaTmpl[0].product_id === false) { //Cuando son variantes tenemos tambien el product id, y en ese caso necesitamos buscar por ese campo para no aplicar el mismo dto a todas
dto.tipo = buscaTmpl[0].compute_price; //"percentage" o "fixed"
dto.cantidad = buscaTmpl[0].compute_price === 'percentage' ? buscaTmpl[0].percent_price : buscaTmpl[0].fixed_price; //Hay que probar con precios fijos
  } else { //Si no lo encontramos por plantilla buscamos por product_id
   let buscaId =  miTarifa.filter( a => a.product_id !== false && a.product_id[0] == obj.idProducto); //Buscamos por id de producto
   if (buscaId.length > 0) { 
    dto.tipo = buscaId[0].compute_price; //"percentage" o "fixed"
dto.cantidad = buscaId[0].compute_price === 'percentage' ? buscaId[0].percent_price : buscaId[0].fixed_price; //Hay que probar con precios fijos
   } else {
  //Si tampoco lo encontramos así lo hacemos por categoría
  let buscaCat = miTarifa.filter( a => a.categ_id !== false && a.categ_id[1] == obj.categoria); 
  if (buscaCat.length > 0) { 
  dto.tipo = buscaCat[0].compute_price; //"percentage" o "fixed"
  dto.cantidad = buscaCat[0].compute_price === 'percentage' ? buscaCat[0].percent_price : buscaId[0].fixed_price; //Hay que probar con precios fijos
  }
   }

  }
return dto;
}

function dameDto(idPlantilla, idCategoria, idTarifa) {
  //Buscamos primero por la plantilla  
  let datos = {
    modelo: 'product.pricelist.item',
    campo: 'product_tmpl_id',
    valor: idPlantilla,
    offset: 0
  }
  let tarifas = llamadaJson('../ajax/odoo/busqueda.php', datos);             //Buscamos las tarifas para ese artículo
  if (tarifas.length === 0) { //Si no encontramos nada por artículo lo buscamos por categoría
    datos.campo = 'categ_id';
    datos.valor = idCategoria;
    tarifas = llamadaJson('../ajax/odoo/busqueda.php', datos);             //Buscamos las tarifas para ese artículo
  }

  let tarifaCliente = tarifas.filter(t => t.pricelist_id[0] == idTarifa);
  let percentDto = tarifaCliente.length > 0 && tarifaCliente[0].percent_price != 'undefined' ? tarifaCliente[0].percent_price : 0;
  return percentDto;
}

function alCarritoDev(este){
  let dataArt = este.getAttribute('data-art'); //Capturamos el click con los datos
  let datos = JSON.parse(dataArt); //Creamos un objeto para trabajar con él
  let alCarritoArr = document.querySelectorAll('.carrito'); //Seleccionamos todos los inputs para quedarnos con el actual
  let cantidad;
  for (var al of alCarritoArr) {
    let hijos = al.childNodes;
    let inputHijo = hijos[0];
    let btnHijo = hijos[1];

    let datosHijo = JSON.parse(btnHijo.getAttribute('data-art'));
    if (datosHijo.idPlantilla == datos.idPlantilla) {
      //  console.log('inputHijo');
      //  console.log(inputHijo.value);
      cantidad = inputHijo.value; //CApturamos la cantidad que hay en el input
    }
     }
  datos.cantidad = cantidad;     //Añadimos la propiedad cantidad al objeto
  document.getElementById('mi-carrito').style.display = 'table';
  let existe = carrito.findIndex(art => art.ref == datos.ref);
  if (existe < 0) {
    //Cambiamos el cálculo del descuento, ahora lo hacemos al añadir al carrito
   /* let tmplId = datos.idPlantilla;
    let catId = datos.categoria;
    let productId = datos.id;
    let attsTar = [tmplId, catId, productId];
    let dTar = buscaPricelistItem(attsTar);
    //Configuramos el descuento
    let dto = dTar.length > 0 ? dTar[0].percent_price : 0; //damos por hecho que trabajamos con porcentajes
    datos.descuento = dto;*/
    //console.log('ddd');
    //console.log(datos);
    carrito.push(datos);         //Añadimos el artículo al carrito
  } else {
    carrito[existe].cantidad = parseInt(carrito[existe].cantidad) + parseInt(datos.cantidad); //Si ya tenemos este artículo en el carrito añadimos la cantidad
  }


  //Mostramos los toast
  var toastElList = [].slice.call(document.querySelectorAll('.toast'))
  var toastList = toastElList.map(function (toastEl) {
    return new bootstrap.Toast(toastEl)
  })
  let cToast = document.getElementById('cuerpoToast');
  cToast.innerHTML = datos.nombre + ' añadido al carrito correctamente';
  toastList.forEach(toast => toast.show())

  //Dibujar carrito
  dibujaCarritoDev(carrito);
}


/**
 * [alCarrito description]
 * @param  {[type]} este               [description]
 * @return {[type]}      [description]
 */
function alCarrito(este) {
  console.log('este');
    console.log(este);
  //  let dataArt = event.currentTarget.getAttribute('data-art'); //Capturamos el click con los datos
  let dataArt = este.getAttribute('data-art'); //Capturamos el click con los datos
  let datos = JSON.parse(dataArt); //Creamos un objeto para trabajar con él
  let alCarritoArr = document.querySelectorAll('.carrito'); //Seleccionamos todos los inputs para quedarnos con el actual
  let cantidad;
  //console.log(alCarritoArr);
  for (var al of alCarritoArr) {
    //console.log(al);
    //console.log(datos);

    let hijos = al.childNodes;
    let inputHijo = hijos[0];
    let btnHijo = hijos[1];

    let datosHijo = JSON.parse(btnHijo.getAttribute('data-art'));
    //console.log('btnHijo');
    //console.log(datosHijo);
    if (datosHijo.idPlantilla == datos.idPlantilla) {
      //  console.log('inputHijo');
      //  console.log(inputHijo.value);
      cantidad = inputHijo.value; //CApturamos la cantidad que hay en el input
    }
  }

  //  let cantidad = event.target.previousElementSibling.value; //CApturamos la cantidad que hay en el input
  //  let bloqueCarrito = document.getElementById('contenido-carrito');
  //  let cuerpoTabla = bloqueCarrito.getElementsByTagName('tbody')[0];

  datos.cantidad = cantidad;     //Añadimos la propiedad cantidad al objeto
  //  cuerpoTabla.innerHTML = '';  //Con cada click vaciamos el carrito y volvemos a dibujarlo entero
  //  cuerpoTabla.style.display = 'table-row-group'; //Mostramos la tabla
  //document.getElementById('carrito-vacio').style.display = 'none';
  document.getElementById('contenido-carrito').style.display = 'table';
  let existe = carrito.findIndex(art => art.ref == datos.ref);
  if (existe < 0) {
    //Antes de añadir el artículo al carrito tenemos que buscarlo en odoo
    /*  d = {
        campo: 'id',
        valor: datos.idPlantilla,
        modelo: 'product.template'
      }*/

    d = {
      campo: 'id',
      valor: datos.idProducto,
      modelo: 'product.product'
    }

    let infoTemplate = llamadaJson('../ajax/odoo/busqueda.php', d);
    console.log('infoTemplate');
    console.log(infoTemplate);
    if (infoTemplate.length > 0) {
      let template = infoTemplate[0];
      datos.id = template.product_variant_id[0];
      datos.precio = template.list_price;
      datos.stock = template.qty_available;
      datos.precio_compra = template.standard_price;
      carrito.push(datos);         //Añadimos el artículo al carrito
    } else {
      alert('este artículo no está activo y no puede ser añadido al carrito'); //Si no recibimos datos seguramente se trata de un artículo archivado
    }
  } else {
    carrito[existe].cantidad = parseInt(carrito[existe].cantidad) + parseInt(datos.cantidad);
  }
  //Mostramos los toast
  var toastElList = [].slice.call(document.querySelectorAll('.toast'))
  var toastList = toastElList.map(function (toastEl) {
    return new bootstrap.Toast(toastEl)
  })
  let cToast = document.getElementById('cuerpoToast');
  cToast.innerHTML = datos.nombre + ' añadido al carrito correctamente';
  toastList.forEach(toast => toast.show())

  //Dibujar carrito
  dibujaCarrito(carrito);

}


function manipulaLinea(event) {
  let tg =  event.target.tagName == 'path' ? event.target.parentNode: event.target;
  let accion = tg.classList[0];
  let indice = tg.parentNode.getAttribute('indice');
  let trActual = tg.parentNode.parentNode;
  let nuevaCantidad = trActual.querySelector('input').value;

  switch (accion) {
    case 'eliminar-linea':
      carrito.splice(indice, 1);
      break;
    case 'actualizar-linea':
      carrito[indice].cantidad = nuevaCantidad;

      break;

  }
  dibujaCarritoDev(carrito); //Redibujamos el carrito

}

function dibujaCarritoDev(carrito) {
  let bloqueCarrito = document.getElementById('mi-carrito');
  let avisoCarritoVacio = document.getElementById('mi-carrito-vacio');
  let cuerpoTabla = bloqueCarrito.getElementsByTagName('tbody')[0];
  let btnAbreModal = document.getElementById('btn-abre-modal-confirmacion');
  cuerpoTabla.innerHTML = '';  //Con cada click vaciamos el carrito y volvemos a dibujarlo entero
  if (carrito.length === 0) { //Si vaciamos el carrito tenemos que mostrar el aviso
    avisoCarritoVacio.style.display = 'block';
    bloqueCarrito.classList.add('visually-hidden');
    btnAbreModal.classList.add('disabled');
    document.getElementById('img-cofre').src = 'img/cofre-cerrado.png';
  } else {
    avisoCarritoVacio.style.display = 'none';
    bloqueCarrito.classList.remove('visually-hidden');
    btnAbreModal.classList.remove('disabled');
    document.getElementById('img-cofre').src = 'img/cofre-abierto.png';
    let totalCarro = 0;
    let itemsCarro = 0;
    let i = 0;
    for (let a of carrito) {
      let tr = document.createElement('tr'); //Creamos la fila
      cuerpoTabla.appendChild(tr);
/*
        let obj = {
          ref: articulo.default_code,
          nombre: articulo.name,
          descuento: dto,
        //  stock: 0,
          idPlantilla: articulo.product_tmpl_id[0],
          idProducto: articulo.id,
          uomId: articulo.uom_id[0],
          id: articulo.id,
precio: articulo.list_price,
stock: articulo.qty_available,
precio_compra: articulo.standard_price
        }
        */
        let datoImporte = a.precio * (1 - (a.descuento / 100));
        let cantidad = document.createElement('input');
        cantidad.classList.add('form-control');
        cantidad.value = a.cantidad;
        cantidad.setAttribute('type', 'number');
        let datoSubtotal = a.cantidad * datoImporte
        let subtotal = document.createTextNode(datoSubtotal.toFixed(2) + '€');
       /* let iEliminar = document.createElement('span');
        iEliminar.classList.add('eliminar-linea');
        iEliminar.innerHTML = '<svg fill="none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48"> <path d="M16 2v4h6v2h-2v14H4V8H2V6h6V2h8zm-2 2h-4v2h4V4zm0 4H6v12h12V8h-4zm-5 2h2v8H9v-8zm6 0h-2v8h2v-8z" fill="currentColor"/> </svg>';
        iEliminar.style.cursor = 'pointer';
        iEliminar.style.margin = '0.5em';
  
  
  
        let iActualizar = document.createElement('span');
        iActualizar.classList.add('actualizar-linea');
        iActualizar.innerHTML = '<svg fill="none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48"> <path d="M16 2h-2v2h2v2H4v2H2v5h2V8h12v2h-2v2h2v-2h2V8h2V6h-2V4h-2V2zM6 20h2v2h2v-2H8v-2h12v-2h2v-5h-2v5H8v-2h2v-2H8v2H6v2H4v2h2v2z" fill="currentColor"/> </svg>';
        iActualizar.style.cursor = 'pointer';
        iActualizar.style.margin = '0.5em';*/


      let tdMpn = document.createElement('td'); //Creamos la celda
      tdMpn.innerHTML = a.ref;
      tr.appendChild(tdMpn);
      let tdNombre = document.createElement('td'); //Creamos la celda
      tdNombre.innerHTML = a.nombre;
      tr.appendChild(tdNombre);
      let tdPrecio = document.createElement('td'); //Creamos la celda
      tdPrecio.innerHTML = accounting.formatMoney( a.precio, { symbol: "€", format: "%v %s" });
      tr.appendChild(tdPrecio);
      let tdDto = document.createElement('td'); //Creamos la celda
      tdDto.innerHTML = a.descuento + '%';
      tr.appendChild(tdDto);
      let tdPrecioUnidad = document.createElement('td'); //Creamos la celda
      tdPrecioUnidad.innerHTML = accounting.formatMoney( datoImporte, { symbol: "€", format: "%v %s" });
      tr.appendChild(tdPrecioUnidad);
      let tdCantidad = document.createElement('td'); //Creamos la celda
      tdCantidad.appendChild(cantidad);
      tr.appendChild(tdCantidad);
      let tdSubtotal = document.createElement('td'); //Creamos la celda
      tdSubtotal.appendChild(subtotal);
      tr.appendChild(tdSubtotal);
      let tdAcciones = document.createElement('td'); //Creamos la celda
      tdAcciones.innerHTML = '<svg style="cursor: pointer" class="eliminar-linea" fill="none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" width="32" height="32"> <path d="M16 2v4h6v2h-2v14H4V8H2V6h6V2h8zm-2 2h-4v2h4V4zm0 4H6v12h12V8h-4zm-5 2h2v8H9v-8zm6 0h-2v8h2v-8z" fill="currentColor"/> </svg>'+'<svg class="actualizar-linea" style="cursor: pointer" fill="none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" width="32" height="32"> <path d="M16 2h-2v2h2v2H4v2H2v5h2V8h12v2h-2v2h2v-2h2V8h2V6h-2V4h-2V2zM6 20h2v2h2v-2H8v-2h12v-2h2v-5h-2v5H8v-2h2v-2H8v2H6v2H4v2h2v2z" fill="currentColor"/> </svg>';

     // tdAcciones.appendChild(iActualizar);
     // tdAcciones.appendChild(iEliminar);
      tdAcciones.setAttribute("indice", i);
      tr.appendChild(tdAcciones);

      itemsCarro = parseInt(itemsCarro) + parseInt(a.cantidad);
      document.getElementById('num-items-carrito').innerHTML = itemsCarro;
      totalCarro = totalCarro + datoSubtotal;
      document.getElementById('importe-total-carro').innerHTML = accounting.formatMoney( totalCarro, { symbol: "€", format: "%v %s" });
      i++;
    }
    let eliminarLinea = document.querySelectorAll('.eliminar-linea');
    for (let linea of eliminarLinea) {
      linea.addEventListener('click', function (event) {
        manipulaLinea(event);
      })
    }

    let actualizarLinea = document.querySelectorAll('.actualizar-linea');
    for (let linea of actualizarLinea) {
      linea.addEventListener('click', function (event) {
        manipulaLinea(event);
      })
    }
  }
}

function dibujaCarrito(carrito) {
  let bloqueCarrito = document.getElementById('contenido-carrito');
  let avisoCarritoVacio = document.getElementById('carrito-vacio');
  let cuerpoTabla = bloqueCarrito.getElementsByTagName('tbody')[0];

  if (carrito.length === 0) {
    avisoCarritoVacio.style.display = 'block';
    bloqueCarrito.style.display = 'none'; //Mostramos la tabla
    cuerpoTabla.parentNode.style.display = 'none';
  } else {
    cuerpoTabla.innerHTML = '';  //Con cada click vaciamos el carrito y volvemos a dibujarlo entero
    avisoCarritoVacio.style.display = 'none';
    cuerpoTabla.style.display = 'table-row-group'; //Mostramos la tabla
    let totalCarro = 0;
    let itemsCarro = 0;
    let i = 0;
    console.log('articulos en el carrito:' + carrito.length);
    //Dibujamos el carrito
    for (let a of carrito) {
      console.log(a);
      let tr = document.createElement("tr");
      let tdRef = document.createElement("td");
      let tdNombre = document.createElement("td");
      let tdPrecio = document.createElement("td");
      let tdDto = document.createElement("td");
      let tdImporte = document.createElement("td");
      let tdCantidad = document.createElement("td");
      let tdSubtotal = document.createElement("td");
      let tdAcciones = document.createElement('td');
      tdAcciones.setAttribute("indice", i);

      //Vamos colocando elementos
      let referencia = document.createTextNode(a.ref);
      let nombre = document.createTextNode(a.nombre);
      let precio = document.createTextNode(a.precio + '€');
      let descuento = document.createTextNode(a.descuento + '%');
      let datoImporte = a.precio * (1 - (a.descuento / 100));
      let importe = document.createTextNode(datoImporte.toFixed(2) + '€');

      //  let cantidad = document.createTextNode(a.cantidad);
      let cantidad = document.createElement('input');
      cantidad.classList.add('form-control');
      cantidad.value = a.cantidad;
      cantidad.setAttribute('type', 'number');

      let datoSubtotal = a.cantidad * datoImporte
      let subtotal = document.createTextNode(datoSubtotal.toFixed(2) + '€');

      let iEliminar = document.createElement('i');
      iEliminar.classList.add('fas', 'fa-trash', 'eliminar-linea');
      iEliminar.style.cursor = 'pointer';
      iEliminar.style.margin = '0.5em';



      let iActualizar = document.createElement('i');
      iActualizar.classList.add('fas', 'fa-redo', 'actualizar-linea');
      iActualizar.style.cursor = 'pointer';
      iActualizar.style.margin = '0.5em';

      itemsCarro = parseInt(itemsCarro) + parseInt(a.cantidad);
      totalCarro = totalCarro + datoSubtotal;

      tdRef.appendChild(referencia);
      tr.appendChild(tdRef);
      tdNombre.appendChild(nombre);
      tr.appendChild(tdNombre);
      tdPrecio.appendChild(precio);
      tr.appendChild(tdPrecio);
      tdDto.appendChild(descuento);
      tr.appendChild(tdDto);
      tdImporte.appendChild(importe);
      tr.appendChild(tdImporte);
      tdCantidad.appendChild(cantidad);
      tr.appendChild(tdCantidad);
      tdSubtotal.appendChild(subtotal);
      tr.appendChild(tdSubtotal);
      tdAcciones.appendChild(iActualizar);
      tdAcciones.appendChild(iEliminar);
      tr.appendChild(tdAcciones);
      console.log(totalCarro);

      cuerpoTabla.appendChild(tr);
      i++;
    }
    let divNumItems = document.getElementById('num-items-carrito');
    divNumItems.innerHTML = itemsCarro;
    let divtotalCarro = document.getElementById('importe-total-carrito');
    divtotalCarro.innerHTML = parseFloat(totalCarro).toFixed(2);

    let eliminarLinea = document.querySelectorAll('.eliminar-linea');
    for (let linea of eliminarLinea) {
      linea.addEventListener('click', function (event) {
        manipulaLinea(event);
      })
    }

    let actualizarLinea = document.querySelectorAll('.actualizar-linea');
    for (let linea of actualizarLinea) {
      linea.addEventListener('click', function (event) {
        manipulaLinea(event);
      })
    }
    //$('#contenido-carrito').DataTable();
  }
}


function colocaTarifa(miTarifa, idTabla) {

  $('#' + idTabla).DataTable({
    'data': miTarifa,
    'columns': [
      { 'title': "mpn" },
      { 'title': "nombre" },
      {
        'title': "dto",
        'render': function (data) {
          return data + '%';
        }
      },
      { 'title': "stock" },
      {
        'title': "id_plantilla",
        'visible': false
      },
      {
        'data': null,
        //  'defaultContent': '<div class="input-group mb-3"><input type="number" class="form-control" placeholder="qty" aria-label="cantidad" aria-describedby="button-addon2"><button class="btn btn-outline-secondary al-carrito" type="button">add</button></div>'
        'render': function (data, type, row, meta) {
          let obj = {
            ref: row[0],
            nombre: row[1],
            descuento: row[2],
            stock: row[3],
            idPlantilla: row[4],
            idProducto: row[5],
            uomId: row[6]
          }
          //console.log(row);
          //return row;
          //let arr = row.split(',');
          return '<div class="input-group mb-3 carrito"><input type="number" class="form-control" placeholder="qty" aria-label="cantidad" aria-describedby="button-addon2" value="1"><button class="btn btn-outline-secondary al-carrito" data-art=\'' + JSON.stringify(obj).replace(/[\/\(\)\']/g, "&apos;") + '\' type="button" onclick="alCarrito(this)"><i class="fas fa-shopping-cart"></i></button></div>';
        }
      }
    ],
    "language": {
      'url': 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json',
      "zeroRecords": "No hay registros que coincidan. Pulsa enter para hacer una búsqueda general"

    }
  });
  $('#loader-tarifa').fadeOut("slow"); //Ocultamos el loader
  let tablaTarifa = document.getElementById('tabla-tarifa');
  tablaTarifa.style.display = 'flex';
}


/**
 * 
 * @param {Array} pedidos Ids de los pedidos del cliente
 * @returns 
 */
function dameInfoPedidos(pedidos) {
  if (pedidos.length > 0) { //Si hay pedidos los colocamos
    let url = '../ajax/odoo/busqueda.php';
    let d = {
      campo: 'id',
      modelo: 'sale.order'
    }

    for (const i in pedidos) {
      if (i < 5) {
        d.valor = pedidos[i];
        let info = llamadaJson(url, d); //Buscamos los datos para este pedido
        let p = info[0]; //La primera entrada del array de resultados es la que necesitamos
        let tabla = document.getElementById('dataTablePedidos'); //La tabla con los datos
        let tr = document.createElement('tr'); //Creamos la fila
        tabla.appendChild(tr); //La añadimos a la tabla
        //console.log(p);
        let tdRef = document.createElement('td');
        tdRef.innerHTML = p.name;
        tr.appendChild(tdRef);

        let tdFecha = document.createElement('td');
        tdFecha.innerHTML = p.date_order;
        tr.appendChild(tdFecha);

        let tdTotal = document.createElement('td');
        tdTotal.innerHTML = accounting.formatMoney(p.amount_total, { symbol: "€", format: "%v %s" });
        tr.appendChild(tdTotal);

        let tdEstado = document.createElement('td');
        tdEstado.innerHTML = p.state;
        tr.appendChild(tdEstado);

        let tdVer = document.createElement('td');
        let btns = document.createElement('div');
        tdVer.appendChild(btns);
        let aPedido = document.createElement('a');
        aPedido.setAttribute('type', 'button');
        aPedido.setAttribute('target', '_blank');
        aPedido.href = 'https://erp.futura.es' + p.access_url;
        aPedido.classList.add('btn', 'btn-primary');
        aPedido.innerText = 'Ver';
        btns.appendChild(aPedido);
        tr.appendChild(tdVer);

      }
    }

    //Mostramos la tabla
    document.getElementById('tabla-pedidos').style.display = 'block';
  } else {
    document.getElementById('no-pedidos').style.display = 'block';
  }
  document.getElementById('spinner-pedidos').style.setProperty('display', 'none', 'important');
}



/**
 * Función que devuelve el stock disponible en inforpor
 * @param  {[type]} item               [description]
 * @return {[type]}      [description]
 */
function dameStockIfp(item) {
  let resultado;
  let ean = item.barcode; //Capturamos el ean
  let mpn = item.default_code; //Capturamos el mpn
  let datos = {};
  if (ean !== false) { //si tenemos un ean buscamos por el
    datos.ean = ean;
  }
  if (mpn !== false) {
    datos.mpn = mpn;
  }
  let buscaIfp = llamadaJson('../ajax/inforpor/obtener-compra.php', datos);             //Buscamos en inforpor
  //Primero analizamos si existe en Inforpor
  let existe = /^\d{3,5}$/.test(parseInt(buscaIfp.normal_inforpor.Cod));
  if (existe === false) { //Si no tenemos código de inforpor es que el artículo no existe
    resultado = -1;
  } else { //Si tenemos código lo que hacemos es sumar los distintos resultados
    //Las custodias deben estar metidas en odoo por lo que de momento podemos obviarlas
    let reserva = buscaIfp.reserva_inforpor;
    let stockNormal = buscaIfp.normal_inforpor.Stock;
    resultado = parseInt(reserva) + parseInt(stockNormal);
    //console.log('resultado ' + resultado);
  }
  console.log('resultado ' + resultado);
  //console.log(buscaIfp);
  return resultado;
}



function enviarMsjRocket(datos) {
  $.ajax({
    type: "POST",
    url: "../ajax/rocket/enviar-mensaje.php",
    data: datos,
    // dataType: 'json',
    global: false,
    async: false,
    success: function (data, textStatus, jqXHR) {
      // console.log('cliente: ' + data);
      if (datos.confirmacion == 'si') {
        var myModal = new bootstrap.Modal(document.getElementById('feedback-enviado-modal'), {
          keyboard: false
        })
        myModal.show();
      }
    },
    error: function (data, textStatus, jqXHR) {
      alert('Error: ' + JSON.stringify(data));
      //    $(bloque).find('.dimmer').toggleClass('active');
    }
  });
}

async function dameCliente(id) {

}
function workerStock(articulos) {
  // let articulos = pd.lineas_pedido;
   if (typeof(Worker) !== "undefined") {
     if (typeof(wr) == "undefined") {
       wr = new Worker("js/workers/buscar-stock.js");
     }
     wr.postMessage(articulos); //pasamos la tarifa al worker
     wr.onmessage = function(event) {
       let datosStock = event.data;
    //   console.log('data');
     //  console.log(datosStock);
 let icoStock;
       switch (true) {
         case datosStock.stock == 0:
           icoStock = 'no';
           break;
           case datosStock.stock > 0 && datosStock <= 5:
             icoStock = 'med';
             break;
             case datosStock.stock > 5:
               icoStock = 'full';
               break;
               default:
                 icoStock = 'mystery';
                 break;
 
       }
 //document.getElementById('stock-'+datosStock.id).innerHTML = datosStock.stock;
 document.getElementById('stock-'+datosStock.id).innerHTML = '<img src="img/'+icoStock+'-stock.png" width="24" />';
 
    //   console.log('stockevent');
 //console.log(datosStock);
 
     }
   }
 }
async function colocaCliente(cliente) {
  if (typeof(Worker) !== "undefined") {
    if (typeof(wp) == "undefined") {
      wp = new Worker("js/workers/libreta-direcciones.js");
    }
    wp.postMessage(cliente); //pasamos la tarifa al worker
    wp.onmessage = function(event) {
      let direccion = event.data;
      //console.log(direccion);
      if (direccion.type == 'delivery') {
        var opt = document.createElement("option");
        opt.value = direccion.id;
        opt.text = direccion.name;
        document.getElementById('selectDirecciones').add(opt, null);
      }
      //console.log(event);
    }

  }
  /*let datos = {
    campo: 'id',
    valor: idTarifa,
    modelo: 'product.pricelist'
  }

  let tCliente = await llamadaJson('../ajax/odoo/busqueda.php', datos); //Buscamos en la base de datos
  let artsTar = buscaArtsTarifaCliente(tCliente);
  console.log(artsTar);
  return artsTar;*/
  return 'ok';
}
function dameTarifa(cliente) {
let nombreTarifa = cliente.property_product_pricelist[1]; //Cogemos el nombre de la tarifa de este cliente

let datos = {
  campo: 'pricelist_id',
 valor: nombreTarifa,
  modelo: 'product.pricelist.item'
}
let itemsTarifa = llamadaJson('../ajax/odoo/busqueda.php', datos); //Cogemos todas las entradas para esta tarifa
return itemsTarifa;
//console.log(itemsTarifa);
}

function buscaArtsTarifaCliente(tCliente) {
  let itemTarifa = tCliente[0].item_ids;
  let miTarifa = [];
  for (i of itemTarifa) {
    datos = {
      campo: 'id',
      valor: i,
      modelo: 'product.pricelist.item'
    }
    let infoItem = llamadaJson('../ajax/odoo/busqueda.php', datos);
    let item = infoItem[0];
    //console.log(item);
    //Nos quedamos con los campos que nos interesan
    let porcientoDescuento = item.percent_price;
    if (item.product_tmpl_id !== false) {


      //Buscamos ahora el product template
      datos.valor = item.product_tmpl_id[0];
      datos.modelo = 'product.template';
      //console.log(datos);
      let infoTemplate = llamadaJson('../ajax/odoo/busqueda.php', datos);
      //console.log(infoTemplate);
      //console.log('---------------------------');
      if (infoTemplate.length > 0) { //De esta manera evitamos buscar artículos archivados
        let template = infoTemplate[0];

        let idProducto = template.product_variant_id[0];
        let mpn = template.default_code;
        let nombre = template.name;
        let precio = template.list_price;
        let stock = template.qty_available;
        let articulo = {
          idProducto: idProducto,
          mpn: mpn,
          nombre: nombre,
          precio: precio,
          porcientoDescuento: porcientoDescuento,
          stock: stock
        };
        miTarifa.push(articulo);
      }
    }

    /*console.log(infoItem);
    console.log(infoTemplate);*/
  }
  return miTarifa;
}
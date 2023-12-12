this.addEventListener('message', function (e) {
    let pedido = e.data;
    //Primero buscamos si existe el presupuesto, si existe significa que ya están tanto cliente como artículos
   // buscaPre(pedido);  
   buscaPedidosOdoo(pedido);
  });

function buscaPedidosOdoo(pedido) {
  var resp;
  let name;
  let tipo;
 // console.log('pedido');
 // console.log(pedido);
  //Vamos a separar porque necesitamos hacer busqueda por referencia del pedido o por referencia de una linea
  if (pedido.hasOwnProperty('numpedCli')) { //Si tenemos esta propiedad es un pedido de inforpor y la referencia de cliente es la del pedido de Odoo
    tipo = 'pedido';
    name = pedido.numpedCli;
  } else {
    tipo = 'linea';
    name = pedido.id;
  }
  //console.log(tipo);
  //console.log(name);
               //https://developer.mozilla.org/en-US/docs/Web/API/FormData
               var data = new FormData(); //Pasamos los parámetros            
             //  data.append('name', pedido.id);
             data.append('name', name);
             data.append('tipo', tipo);
        //https://developer.mozilla.org/en-US/docs/Web/API/XMLHttpRequest
           let req = new XMLHttpRequest();
           let url = '../../ajax/odoo/buscar-presupuesto-venta.php';
           req.open('POST', url, true);
           req.send(data);
           req.responseType = "json";
           req.onreadystatechange = function () {
             if (req.readyState == 4 && req.status == 200) {
              if (req.response != null && req.response.length > 0) { //Si tenemos el número de pedido lo mandamos de vuelta
              resp = req.response[0]; //Mandamos de vuelta la respuesta
              }
              postMessage(resp);
             }

}
  
}

  function buscaPre(pedido) {
               //https://developer.mozilla.org/en-US/docs/Web/API/FormData
               var data = new FormData(); //Pasamos los parámetros
               data.append('name', pedido.id);
        //https://developer.mozilla.org/en-US/docs/Web/API/XMLHttpRequest
           let req = new XMLHttpRequest();
           let url = '../../ajax/odoo/buscar-presupuesto-venta.php';
           req.open('POST', url, true);
           req.send(data);
           req.responseType = "json";
           req.onreadystatechange = function () {
             if (req.readyState == 4 && req.status == 200) {
               //     console.log('req.response');
                //  console.log(req.response);
                let respuesta = {
                  'modelo' : 'sale.order'
              }

               if (req.response != null && req.response.length > 0) { //Si tenemos el número de pedido lo mandamos de vuelta
                respuesta.datos = {venta : req.response[0]}; //añadimos la venta a la variable
//Buscamos la compra
let nRef;
switch (pedido.tienda) {
  case 'mirakl':
    nRef = 'PC'+pedido.id.substring(0,pedido.id.length - 2);
    break;
    case 'mage':
      nRef = pedido.id;
      break;

}
url = '../../ajax/odoo/buscar-solicitud-compra.php';
req.open('POST', url, true);
data.append('valor',nRef);
req.send(data);
req.responseType = "json";
req.onreadystatechange = function () {
  if (req.readyState == 4 && req.status == 200) {
    respuesta.datos = {compra : req.response[0]}; //añadimos la venta a la variable
    postMessage(respuesta);
  }
}
                 

 
               } else { //Si no tenemos el número de pedido tenemos que buscar cliente y artículos para ver si los tenemos creados
                //Buscamos el cliente
   buscaCliente(pedido.direccion_factura);       
      
//Buscamos los articulos del pedido
pedido.lineas_pedido.forEach(art => {
buscaArticulo(art);

});
//Comprobamos el contador total de productos
//let contador = pedido.lineas_pedido.length;
//let total = document.getElementById('totalArticulos').getAttribute('data-total');
//console.log(contador + ' de ' + total);
respuesta.datos = '';
}
postMessage(respuesta);
               }
 
               //  }
 
             }
           }

function buscaArticulo(articulo) {
 // console.log(articulo);
    let   data = new FormData();
    data.append('2', articulo.mpn);
 //   data.append('3', articulo.barcode);
   let  url = '../../ajax/odoo/buscar-articulo.php';
    let req = new XMLHttpRequest();
    req.open('POST', url, true);
    req.send(data);
    req.responseType = "json";
    req.onreadystatechange = function () {
        if (req.readyState == 4 && req.status == 200) {
            let respuesta = {
                'modelo' : 'product.product',
                'datos' : req.response,
                'mpn' : articulo.mpn
            }
             postMessage(respuesta); //Pasamos la respuesta, cliente vacío o con los resultados de la busqueda

        }
    }
}

  function buscaCliente(cliente) {
  let   data = new FormData();
    data.append('nif', cliente.nif);
    data.append('tlfo', cliente.telefono);
    data.append('email', cliente.email);
   let  url = '../../ajax/odoo/buscar-cliente.php';
    let req = new XMLHttpRequest();
    req.open('POST', url, true);
    req.send(data);
    req.responseType = "json";
    req.onreadystatechange = function () {
        if (req.readyState == 4 && req.status == 200) {
            let respuesta = {
                'modelo' : 'res.partner',
                'datos' : req.response
            }
             postMessage(respuesta); //Pasamos la respuesta, cliente vacío o con los resultados de la busqueda
        }
    }
  }
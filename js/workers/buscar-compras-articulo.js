this.addEventListener("message", function (e) {
  hacerCosas(e.data);
});

/**
 * Esta función la utilizamos tanto en pedidos como en catálogo.
 * Si lo hacemos desde pedidos pasamos las distinas lineas de pedido, si lo hacemos desde catálogo los resultados de la búsqueda
 * 
 *  * @param {*} datos 
 */
function hacerCosas(datos) {
  let articulos = datos.articulos.hasOwnProperty('items') ? datos.articulos.items : datos.articulos;
  let tIfp = datos.tarifas.ifp;
  let tGoogle = datos.tarifas.google;
  
  articulos = articulos.filter((a) => a.mpn !== false); //Quitamos los que no tienen mpn

  articulos.forEach((art) => {
    //Primero buscamos el artículo tanto en el listado de inforpor como en la tarifa
    let mpn = art.hasOwnProperty("mpn") ? art.mpn : art.sku;
    let datosArticulo = {
      referencia: mpn,
      cantidad: art.cantidad,
      importe: art.importe,
      descripcion: art.nombre,
    };
    if (art.hasOwnProperty("codinfo")) {
      datosArticulo.codinfo = art.codinfo;
    }
    let compras = {
      ifp: matchIfp(art, tIfp), //Buscamos el artículo en el listado de inforpor
      google: buscaArticuloTarifa(mpn, damelistado(tGoogle)),
      datosArticulo: datosArticulo,
    };
    postMessage(compras); //Devolvemos un mensaje por cada articulo
  });
}

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
//function hacerCosas(datos,tarifa) {

function matchIfp(art, tIfp) {
  let mpn = art.hasOwnProperty("mpn") ? art.mpn : art.sku;
  //let existe = false;
  let existe = tIfp.filter((t) => t.referencia == mpn || t.referencia2 == mpn);
  let resultado = existe.length === 1 ? existe[0] : false; //Devolvemos el resultado siempre que sea uno, y solo uno
  if (resultado === false) {
  }
  return resultado;
}

function buscaArticuloTarifa(ref, tarifa) {
  let existeEnTarifa = tarifa.find((art) => art.referencia.trim() == ref);
  return existeEnTarifa;
  //postMessage(existeEnTarifa);
  //  console.log(existeEnTarifa);
}

function buscaArticuloIfp(ref) {
  //https://developer.mozilla.org/en-US/docs/Web/API/FormData
  var data = new FormData(); //Pasamos los parámetros
  //   let ref = art.atributos_bd[5] != undefined ? art.atributos_bd[5]: art.mpn;
  // console.log(ref);
  data.append("codinfo", ref);
  //https://developer.mozilla.org/en-US/docs/Web/API/XMLHttpRequest
  let req = new XMLHttpRequest();
  let url = "../../ajax/inforpor/buscar-articulo.php";
  req.open("POST", url, true);
  req.send(data);
  req.responseType = "json";
  req.onreadystatechange = function () {
    if (req.readyState == 4 && req.status == 200) {
      //                  return req.response;
      postMessage(req.response);
    }
  };
}


//Recuperamos la tarifa

this.addEventListener('message', function (e) {
  let tarifa = e.data;
 fetch('../../var/import/magento1.json') //Cargamos la tarifa de magento
 .then((response) => response.json())
 .then((tarifaMage) => comparaPrecios(tarifaMage, tarifa));

});


function comparaPrecios(tMage, tGoogle) {
  for (const hoja of Object.values(tGoogle)) {
    for (const seccion of Object.values(hoja)) {
      for (const articulo of Object.values(seccion)) {
        for (const medida of Object.values(articulo)) {
          let referencia = medida.referencia;
          let precioTarifa = medida.precio;

 //Buscamos primero el artículo en el JSON diario
 let busquedaJSON =   tMage.filter(aMage => aMage.sku == referencia);     
// if (referencia == '64LF120GL0107050') {
 //Comparamos el precio
 //console.log(busquedaJSON);
 if (busquedaJSON.length > 0) { //Si hay coincidencias
  let art = busquedaJSON[0];
  if (precioTarifa != art.price) { //Si los precios son distintos lo vamos a corroborar buscandos
  //  console.log('buscar');
    buscar(referencia,precioTarifa);
  }
 } else { //Si no lo encontramos en el archivo diario pasamos de vuelta el artículo
  postMessage(medida);
 }

//}


        }
      }
    }


  }
}

function buscar(referencia,precioTarifa){
           //https://developer.mozilla.org/en-US/docs/Web/API/FormData
           var data = new FormData(); //Pasamos los parámetros
           data.append('mpn', referencia);
           //https://developer.mozilla.org/en-US/docs/Web/API/XMLHttpRequest
           let req = new XMLHttpRequest();
           let url = '../../ajax/magento/buscar-articulo.php';
           req.open('POST', url, true);
           req.send(data);
           req.responseType = "json";
           req.onreadystatechange = function () {
             if (req.readyState == 4 && req.status == 200) {
                //    console.log('req.response');
                 // console.log(req.response);
               if (req.response != null && req.response.items.length > 0 && req.response.items[0].price != precioTarifa) {
                 postMessage(req.response.items[0]);
 
               }
 
 
               //  }
 
             }
           }
}


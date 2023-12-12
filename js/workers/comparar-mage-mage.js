
//Recuperamos la tarifa

this.addEventListener('message', function (e) {
  let tarifaM246 = e.data;
 fetch('../../var/import/magento.json') //Cargamos la tarifa de magento
 .then((response) => response.json())
 .then((tarifaMage) => comparaMages(tarifaMage, tarifaM246));

});


function comparaMages(tMage, tarifaM246) {
for (const articulo of tarifaM246) {
  let busquedaJSON =   tMage.filter(aMage => (aMage.sku == articulo.sku && (Math.abs(aMage.price - articulo.price) > 0.03)));     
  if (busquedaJSON.length > 0) { 
    let art = busquedaJSON[0];
    buscar(articulo.sku,articulo.price);
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
               //     console.log('req.response');
                //  console.log(req.response);
               if (req.response != null && req.response.length > 0 && req.response.items[0].price != precioTarifa) {
                 postMessage(req.response);
 
               }
 
 
               //  }
 
             }
           }
}


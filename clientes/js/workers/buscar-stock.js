this.addEventListener('message', function (e) {
    let articulos = e.data;
    definirStock(articulos);
});

function definirStock(articulos) {
    articulos.forEach(articulo => {
        let stockOdoo = articulo.qty_available; //Cantidad disponible Aquí
        var respuesta = {
            id: articulo.id,
stock: stockOdoo
        };

        let mpn = articulo.default_code;
        let ean = articulo.barcode;
        //https://developer.mozilla.org/en-US/docs/Web/API/FormData
        var data = new FormData(); //Pasamos los parámetros
        if (ean !== false) { //si tenemos un ean buscamos por el
            data.append('ean', ean);
        }
        if (mpn !== false) { //si tenemos un ean buscamos por el
            data.append('mpn', mpn);
        }
        //https://developer.mozilla.org/en-US/docs/Web/API/XMLHttpRequest
        let req = new XMLHttpRequest();
        let url = '../../../ajax/inforpor/obtener-compra.php';
        req.open('POST', url, true);
        req.send(data);
        req.responseType = "json";
        req.onreadystatechange = function () {
            if (req.readyState == 4 && req.status == 200) {
                console.log(req.response);
             if (req.response != null) { //Si tenemos el número de pedido lo mandamos de vuelta
           //    respuesta = req.response[0]; //Mandamos de vuelta la respuesta
           let reserva = req.response.reserva_inforpor;
           let stockNormal = req.response.normal_inforpor.Cod != "No Datos" ?  req.response.normal_inforpor.Stock : 0;
           let resultadoIfp = parseInt(reserva) + parseInt(stockNormal);
           let totalStock = stockOdoo + resultadoIfp;
respuesta.stock = totalStock;
postMessage(respuesta);
             }

            }

}

    });
}

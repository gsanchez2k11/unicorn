this.addEventListener('message', function (e) {
     let plataformas = e.data;
plataformas.forEach(plataforma => {
    buscaPedido(plataforma);
});

     });


     function buscaPedido(plataforma) {
        console.log(plataforma);
        let url;
        switch (plataforma) {
            case 'pcc':
            case 'mediamarkt':
            url = '../../ajax/mirakl/ultimos-pedidos.php';            
                break;
            case 'mage':
            url = '../../ajax/magento/ultimos-pedidos.php';            
            break;
        }
                       //https://developer.mozilla.org/en-US/docs/Web/API/FormData
                       var data = new FormData(); //Pasamos los parámetros    
                       data.append('plataforma', plataforma);          
                                   //https://developer.mozilla.org/en-US/docs/Web/API/XMLHttpRequest
               let req = new XMLHttpRequest();  
               req.open('POST', url, true);
               req.send(data);
               req.responseType = "json";
               req.onreadystatechange = function () {
                 if (req.readyState == 4 && req.status == 200) {
                    let respuesta = {
                        'plataforma' : plataforma
                    };
                    switch (plataforma) {
                        case 'pcc':
                            //WAITING_ACCEPTANCE
            let pendientes = req.response.filter(ped => ped.estado == 'WAITING_ACCEPTANCE'); //Nos quedamos con los pendientes de aceptar  
            respuesta.pendientes = pendientes.length;
                            break;
                    }
postMessage(respuesta);    

                 }
                }
     }
this.addEventListener('message', function (e) {
    let cliente = e.data;
    dameDirecciones(cliente);
});

function dameDirecciones(cliente) {
              //Pedimos los contactos hijos(las otras direcciones del cliente)
              for (const idContacto of cliente.child_ids) { //Recorremos el array de direcciones
                        //https://developer.mozilla.org/en-US/docs/Web/API/FormData
        var data = new FormData(); //Pasamos los parámetros
        data.append('campo', 'id');
        data.append('valor', idContacto);
        data.append('modelo', 'res.partner');
                //https://developer.mozilla.org/en-US/docs/Web/API/XMLHttpRequest
                let req = new XMLHttpRequest();
                let url = '../../../ajax/odoo/busqueda.php';
                req.open('POST', url, true);
                req.send(data);
                req.responseType = "json";
                req.onreadystatechange = function () {
                    if (req.readyState == 4 && req.status == 200) {
                        //console.log(req.response);
                        postMessage(req.response[0])
                    }
                }

     /*           let d = {
                  campo: 'id',
                  valor: idContacto,
                  modelo: 'res.partner'
                }*/
                //postMessage(d)
     /*           let contacto = llamadaJson('../ajax/odoo/busqueda.php', d)[0]; //Pedimos el JSON con los datos
                //Cuando guardamos la dirección se espeficica el tipo, las que nos interesan son las tipo "delivery"
                if (contacto.type == 'delivery') {
                  var opt = document.createElement("option");
                  opt.value = contacto.id;
                  opt.text = contacto.name;
                  selectSubC.add(opt, null);
                }*/

              }
}

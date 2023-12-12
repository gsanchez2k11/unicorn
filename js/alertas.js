alertas();

function alertas(){
    let listaPlataformas = ['pcc','mediamarkt','miravia','mage'];
 /*   listaPlataformas.forEach( p => {
        console.log(p);
        let ultimosPedidos = dameListadoJson('ultimos pedidos ' + p);                 //Recuperamos los úlitmos 100 pedidos

    })*/
    if (typeof(Worker) !== "undefined") {

        if (typeof(wa) == "undefined") {
          wa = new Worker("js/workers/alertas.js");
        }
        wa.postMessage(listaPlataformas); //pasamos la tarifa al worker
        wa.onmessage = function(event) {
            let plataforma = event.data.plataforma;
let pendientes = event.data.pendientes;
let numAlertasBloque = document.getElementById('num-alertas');
let pedPendienteBloque = document.getElementById('ped-pendientes-'+plataforma);
let contAlertas =  parseInt(numAlertasBloque.innerHTML);           
if (pendientes > 0) {
    numAlertasBloque.style.display = 'block'; //Mostramos el número de alertas
    numAlertasBloque.innerHTML = contAlertas + 1;
    pedPendienteBloque.classList.remove('visually-hidden'); //Mostramos el bloque correspondiente
    document.getElementById('alerta-todo-va-bien').classList.add('visually-hidden');
    pedPendienteBloque.querySelector('b').innerHTML = pendientes;
    switch (plataforma) {
        case 'pcc':
            
            break;
    
    }
}


        }
    }
}
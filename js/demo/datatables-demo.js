// Call the dataTables jQuery plugin
$(document).ready(function() {
  $('#dataTable').DataTable({
    "order": [[0,"desc"]],
    "language" : {
      "zeroRecords":    "No hay registros que coincidan. Pulsa enter para hacer una búsqueda general"

    }
});

$('input.form-control-sm').on('keyup', (event) => {
  let valor = $(event.currentTarget).val();                                     //Asignamos el valor que tenemos en el input a una variable
let tabla = $('#dataTable').DataTable();
let num_resultados = tabla.rows( {search:'applied'} ).count();                  //Contamos los resultados actuales
let tecladPulsada = event.originalEvent.keyCode;                                //Capturamos la tecla pulsada
if (num_resultados === 0 && tecladPulsada === 13) {                             //KeyCode 13 Enter
  let resultadoBusqueda = buscaMirakl(valor);
//Recibimos un string con el error o un json (vacio o con datos)
if (typeof resultadoBusqueda == 'string') {
  $('.dataTables_empty').html(resultadoBusqueda);                                                  //Ocultamos el texto por defecto
} else {
  $('.dataTables_empty').hide();
  ultimosPedidos = resultadoBusqueda;
listapds = colocaDatosPedido(resultadoBusqueda);
//console.log(listapds);
}

//console.log(typeof resultadoBusqueda);
}

})
});

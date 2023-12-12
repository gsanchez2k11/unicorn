// Call the dataTables jQuery plugin
async function dameTabla(){
  //var tarifa = llamadaJson('ajax/inforpor/dame-tarifa.php', {});
  var tarifa = llamadaJson('ajax/inforpor/dame-tarifa.php', {});
$('#dataTable').DataTable({
    "processing": true,
      //  "serverSide": true,
      //  "ajax": 'ajax/inforpor/dame-tarifa.php',
    //    "deferLoading": 57,
      //  'ordering': false,
    //    'scrollY':        1000,
  //  'scroller':       true,
  //  'paging' : false,
  //  "order": [[3,"desc"]],
  //  "deferLoading": 1000,
  //  'sPaginationType': 'ellipses',
  "data": tarifa.data,
  'dom': 'Plfrtip',
    'columns': [
      { "data": 'stickers',
    "defaultContent": "",
'render' : function(data, type){
  let salida = '';
  if(data.sioferta === 'si'){
    salida += '<img src="https://img.icons8.com/color/32/000000/sale--v1.png"   alt="En oferta en inforpor" />';
  }
  for (s in data.plataformas) {
salida += s === 'pcc' && data.plataformas[s] === 'si' ? '<img src="https://bikemarket.pt/storage/avatars/1605868876.jpg" style="width: 32px" title="Lo tenemos en PcComponentes Marketplace. Un saludo"/>' : '';
  }
  for (s in data.plataformas) {
salida += s === 'phh' && data.plataformas[s] === 'si' ? '<img src="https://media.glassdoor.com/sqll/1368182/the-phone-house-squarelogo-1534478836730.png" style="width: 32px">' : '';
  }
  for (s in data.plataformas) {
salida += s === 'fnac' && data.plataformas[s] === 'si' ? '<img src="https://upload.wikimedia.org/wikipedia/commons/thumb/2/2e/Fnac_Logo.svg/1200px-Fnac_Logo.svg.png" style="width: 32px">' : '';
  }
  //return JSON.stringify(data);
  return salida;
}
   },
            { "data": "imagen",
          'render' :  function(data, type) {
            if (type === 'display') {
              return '<img src="' + data + '" style="width: 90px" />';
            }
            return data;
          }
        },
            { "data": "gama" },
            { "data": "fabricante" },
            { "data": "referencia" },
            { "data": "descripcion" },
            { "data": "stock" },
            { "data": "precio",
            'render': function(data) {
              return data + '€';
            }
        //  'render': $.fn.dataTable.render.number( '.', ',', 2,'' ,'€' )
        },
        { "data": null,
      "defaultContent": '<i class="fas fa-cat cat-apulta" data-bs-toggle="modal" data-bs-target="#modal-pcc"></i><img class="llama-batman" src="https://img.icons8.com/ios-glyphs/30/000000/batman-new.png"  data-bs-toggle="modal" data-bs-target="#modal-fnac" / >' }
        ],
        'searchPanes': {
          'initCollapsed': true,
          'panes' : [
            {
              'header': 'Stock',
              'options': [{
                'label': 'Sin stock',
                'value': function (rowData, rowIdx) {
                  return rowData.stock == 0;
                }
              }, {
                'label': 'Con stock',
                'value': function (rowData, rowIdx) {
                  return rowData.stock > 0;
                }
              }]
            },
          ]
        },
        'columnDefs': [{
          'searchPanes': {
              'show': true,
                       },
          'targets': [3]
      }],
        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
    "language" : {
      'url': 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json',
      "zeroRecords":    "No hay registros que coincidan. Pulsa enter para hacer una búsqueda general"

    },
});
return tarifa.data;
}
$(document).ready(function() {
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

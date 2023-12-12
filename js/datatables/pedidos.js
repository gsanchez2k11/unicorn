function creatabla(pedidos){
    $('#dataTable').DataTable({
     // "processing": true,
  'data' : pedidos,
      'columns': [
        {'data': 'fecha_creado'},
        {'data': 'id'},
        {'data': 'estado'},
        {'data' : null,
        'render': function(data){
         // console.log(data.direccion_factura);
          let direccion = data.direccion_factura != null ? data.direccion_factura.nombre_completo : '-';
          return direccion;
        }},
        {'data' : null,
        'render': function(data){
  let cadena = '';
  data.lineas_pedido.forEach(l => {
    cadena = cadena + l.cantidad + ' x ' + l.nombre + '( ' + l.mpn + ' )<br>';
  })
  return cadena;
        }},
        {'data': 'total_pedido',
        'render' : function(data){
  return  accounting.formatMoney(data, { symbol: "€", format: "%v%s" }); //Colocamos el precio de venta actual
        }},
        {'data': null,
    'render' : function(data){
        return '<i class="fa-sharp fa-solid fa-horse-head fa-2x abre-modal-pedido" style="cursor: pointer" data-bs-toggle="modal" data-bs-target="#modal-pedido" data-bs-whatever="'+data.id+'"></i>';
    }}
      ],
      'searchPanes': {
        'initCollapsed': true },
      'dom': 'Plfrtip',
      "language": {
        'url': 'https://cdn.datatables.net/plug-ins/1.12.1/i18n/es-ES.json',
        "zeroRecords": "No hay registros que coincidan. Pulsa enter para hacer una búsqueda general"
  
      },
      'order' : [[0, 'desc']],
    });
  }



function colocaActualizaciones(actualizaciones,plataformas) {
    let horas = Array.from({ length: 24 }, (value, index) => index); //Creamos un array con las horas

    horas.forEach( h => {
  let actualizacionesEstaHora = actualizaciones.filter(a => a.hora == h); //Buscamos las actualizaciones para esta hora
  if (actualizacionesEstaHora.length === 0) { //Si no tenemos actualizaciones para esta hora tenemos que poner un mensaje
    let fila = document.createElement('tr');
      tablaActualizaciones.appendChild(fila);
      let tdHora = document.createElement('td');
      tdHora.scope = 'row';
      tdHora.classList.add('col-1');
      tdHora.innerHTML = h;
      fila.appendChild(tdHora);
      let tdPlataforma = document.createElement('td');
      fila.appendChild(tdPlataforma);
    tdPlataforma.innerHTML = 'No hay ninguna actualización para esta hora';
    tdPlataforma.colSpan = '8';
    tdPlataforma.classList.add('text-center');
    /*let i = 1;
    while (i <= 6) {
        let t = document.createElement('td');
        t.innerHTML = '-';
        fila.appendChild(t);
        i++;
    }*/

  } else {
    actualizacionesEstaHora.forEach( (i,k) => {
      let fila = document.createElement('tr');
      tablaActualizaciones.appendChild(fila);
      if (k === 0) {
        let tdHora = document.createElement('td');
      tdHora.scope = 'row';
      if (actualizacionesEstaHora.length > 1) {
        tdHora.rowSpan = actualizacionesEstaHora.length;
      }
      tdHora.innerHTML = h;
      tdHora.classList.add('col-1');
      fila.appendChild(tdHora);
      }
  
      let estaPlataforma = plataformas.filter(pl => pl.codigo == i.plataforma);
      let tdPlataforma = document.createElement('td');
      fila.appendChild(tdPlataforma);
      tdPlataforma.innerHTML = estaPlataforma[0].nombre;
      tdPlataforma.classList.add('col-1');

      let conjuntos = dameConjuntosAtributos(i.plataforma);
     // console.log(i.conjunto_atributos === null);
       let estaFamilia = i.conjunto_atributos === null ? null : conjuntos.filter( c => c.attribute_set_id == i.conjunto_atributos)[0].attribute_set_name;
      let tdFamilia = document.createElement('td');
      tdFamilia.innerHTML = estaFamilia !== null ? estaFamilia : '-';
      tdFamilia.classList.add('col-2');
      fila.appendChild(tdFamilia);

      let listaFabricantes = dameFabricantes(i.plataforma);
      let esteFabricante = i.fabricante === null ? null : listaFabricantes.filter( c => c.value == i.fabricante)[0].label;
      let tdFabricante = document.createElement('td');
      tdFabricante.innerHTML = esteFabricante !== null ? esteFabricante : '-';
      tdFabricante.classList.add('col-2');
      fila.appendChild(tdFabricante);
      let tdMinPrecio = document.createElement('td');
      tdMinPrecio.innerHTML = i.min_precio !== null ? i.min_precio : '-';
      tdMinPrecio.classList.add('col-1');
      fila.appendChild(tdMinPrecio);
      let tdMaxPrecio = document.createElement('td');
      tdMaxPrecio.innerHTML = i.max_precio !== null ? i.max_precio : '-';
      tdMaxPrecio.classList.add('col-1');
      fila.appendChild(tdMaxPrecio);
      let tdMargen = document.createElement('td');
      tdMargen.innerHTML = i.margen !== null ? i.margen + '%' : '-';
      tdMargen.classList.add('col-1','editable-text');
      tdMargen.id=i.id;
      fila.appendChild(tdMargen);
      let tdNotas = document.createElement('td');
      tdNotas.innerHTML = i.notas !== null ? i.notas : '-';
      tdNotas.classList.add('col-2');
      fila.appendChild(tdNotas);
      let tdAcciones = document.createElement('td');
      tdAcciones.innerHTML = '<i class="fa-solid fa-xmark fa-2x text-danger" style="cursor: pointer"></i>';
      tdAcciones.classList.add('col-1');
      fila.appendChild(tdAcciones);
    })
    
  }
  console.log('h');
  console.log(h);
  console.log(actualizacionesEstaHora);
    });
   
    $('.editable-text').editable("ajax/unicorn_db/jeditable-margenes.php");    //No funciona porque no pasamos la id de la actualización
  }

  function colocaActualizacionesTabla(actualizaciones,plataformas) {
    var datosPl = [];
    plataformas.forEach(pl => {
    //  console.log(pl);
if (/^mage/.test(pl.codigo)) { //Solo hacemos esto para magento
  let fabricantes = dameFabricantes(pl.codigo);
  let conjuntos = dameConjuntosAtributos(pl.codigo);
  datosPl[pl.codigo] = {fabricantes: fabricantes, conjuntos: conjuntos};
}
    });
       //mapeamos para tener las horas que hemos ocupado
    //https://stackoverflow.com/questions/26575018/is-there-any-function-in-jquery-that-is-equivalent-to-phps-array-column
     let horas = actualizaciones.map( el => el.hora );
     let ocupadas =  [...new Set(horas)]; //Quitamos los duplicados para tener solo las horas ocupadas
     let horasDia = Array.from({ length: 24 }, (value, index) => index); //Creamos un array con las horas
     let horasLibres = horasDia.filter(h => ocupadas.includes(String(h)) === false); //Buscamos las horas libres
     horasLibres.forEach(h => {
        let objeto = {
            hora: String(h),
            plataforma: '-',
         //   conjunto_atributos: '-',
         //   fabricante: '-',
            margen: '-',
            max_precio: '-',
            min_precio: '-',
            notas: '-'
        }
        actualizaciones.push(objeto);
     });
     
   //  console.log(actualizaciones);
   // console.log(horasLibres);
    $('#dataTable').DataTable({
        // "processing": true,
     'data' : actualizaciones,
     'columns' : [
        {'data': 'hora'},
        {'data': null,
      'render': function(data){
        return data.plataforma != '-' ? plataformas.filter(pl => pl.codigo == data.plataforma)[0].nombre : '-';
      }},
        {'data': null,
    'render': function(data){
      let familia = '-';
if (data.plataforma !== null && data.plataforma != '-') {
    let listaConjuntos = datosPl[data.plataforma].conjuntos;
  familia = data.conjunto_atributos !== null ? listaConjuntos.filter( c => c.attribute_set_id == data.conjunto_atributos)[0].attribute_set_name : '-';
} 
        return familia;
    }},
    {'data': null,
    'render': function(data){
      let fabricante = '-';
if (data.plataforma !== null && data.plataforma != '-') {
    let listaFabricantes = datosPl[data.plataforma].fabricantes;

    fabricante = data.fabricante !== null && listaFabricantes.length > 0 ? listaFabricantes.filter( c => c.value == data.fabricante)[0].label : '-';
} 
        return fabricante;
    }},   
    {'data' : null, 
  'render' : function(data){
    return data.min_precio === null ? '-' : data.min_precio;
  }},
  {'data' : null, 
  'render' : function(data){
    return data.max_precio === null ? '-' : data.max_precio;
  }},
  {'data' : null, 
  'render' : function(data){
    return data.margen !== '-' ? data.margen + '%' : data.margen;
  }},
  {'data' : null, 
  'render' : function(data){
    return data.notas === null ? '-' : data.notas;
  }},
  {'data' : null, 
  'render' : function(data){
    return '<i class="fa-solid fa-xmark fa-2x text-danger" style="cursor: pointer" data-id="'+data.id+'" data-bs-toggle="modal" data-bs-target="#modalConfirmar"></i>';
  }},
     ],
     "language": {
      'url': 'https://cdn.datatables.net/plug-ins/1.12.1/i18n/es-ES.json',
      "zeroRecords": "No hay registros que coincidan. Pulsa enter para hacer una búsqueda general"

    },
    "pageLength": 25,
    "createdRow": function( row, data, dataIndex ) {

      row.querySelectorAll('td')[6].classList.add('editable-text'); //Añadimos a la columna margen la capacidad de edición
     //   $(row).addClass( 'important' );
 
    },
    "initComplete": function(settings, json) {
      $('.editable-text').editable("ajax/unicorn_db/jeditable-margenes.php");    //Inicializamos el script
      //Generamos el rowspan
 /*     var table = $('#dataTable').DataTable(); //La tabla
let horas = table.column(0).data(); //un array con todas las horas
const resultado = horas.reduce((prev, cur) => ((prev[cur] = prev[cur] + 1 || 1), prev), {}); //Contamos los valores para cada hora
//let varios = Object.values(resultado).filter(r => r > 1);
for (const key in Object.values(resultado)) {

if (Object.values(resultado)[key] > 1) {
  let hora = key;
  let numero = Object.values(resultado)[key];
  let trs = dataTable.querySelectorAll('tr');
  let c = 1; //Contador
  trs.forEach(t => {
  let first = t.childNodes[0];
  if (first.innerHTML == key) {
    if (c == 1) {
    first.classList.add('align-middle');   
    first.rowSpan = Object.values(resultado)[key];
    } else {
     // first.classList.add('text-danger');  
     first.remove();
    }
    c++;
  }
    
  });
}
}*/

    }
    });


 
$('#dataTable').on( 'order.dt', function () {
  var table = $('#dataTable').DataTable();
    // This will show: "Ordering on column 1 (asc)", for example
    var order = table.order();
    
} );
    //Evento al abrir la ventana de confirmación
    modalConfirmar.addEventListener('show.bs.modal', event => {
    // Button that triggered the modal
    const button = event.relatedTarget
    // Extract info from data-bs-* attributes
    const idActualizacion = button.getAttribute('data-id')
    eliminaActualizacion.setAttribute('data-id', idActualizacion);
    // If necessary, you could initiate an AJAX request here
    // and then do the updating in a callback.
    //
    // Update the modal's content.
  })

  eliminaActualizacion.addEventListener('click', e => {
    let id = e.target.getAttribute('data-id');
    $.ajax({
      type: "POST",
      url: 'ajax/unicorn_db/eliminar.php',
      data: {tabla : 'familias_margenes', id: id},
    //  dataType: 'json',
      global: false,
      async: false,
      success: function(data, textStatus, jqXHR) {
        if (data === 'ok') {
        location.reload();
          //console.log('cliente: ' + data);
        }

        //    $('.clientecrm').append(resp);
      },
      error: function(data, textStatus, jqXHR) {
        alert('Error: ' + JSON.stringify(data));
        //    $(bloque).find('.dimmer').toggleClass('active');
      }
    })
//console.log(id);
  })

  }


  function dameConjuntosAtributos(codPlataforma) {
    let idTienda = codPlataforma == 'mage' ? 1 : 2; //Configuramos la id de tienda dependiendo de la versión de magento
       //Pedimos los conjuntos de atributos
          let conjuntoAtributos = JSON.parse($.ajax({
      type: "POST",
      url: 'ajax/magento/dame-conjuntos-atributos.php',
      data: {idTienda : idTienda},
      dataType: 'json',
      global: false,
      async: false,
      success: function(data, textStatus, jqXHR) {
        //  console.log('cliente: ' + resp);
        //    $('.clientecrm').append(resp);
        return data;
      },
      error: function(data, textStatus, jqXHR) {
        alert('Error: ' + JSON.stringify(data));
        //    $(bloque).find('.dimmer').toggleClass('active');
      }
    }).responseText);  
    return conjuntoAtributos;
   } 

   function dameFabricantes(codPlataforma){
   // let idTienda = codPlataforma == 'mage' ? 1 : 2; //Configuramos la id de tienda dependiendo de la versión de magento
let idTienda;
let idAttr;
let d;
    codPlataforma == 'mage' ? d = {idTienda: 1, idAttr: 81} : d = {idTienda: 2, idAttr: 137}; //Configuramos la id de tienda dependiendo de la versión de magento

    let listaFabricantes = JSON.parse($.ajax({
        type: "POST",
        url: 'ajax/magento/dame-valores-atributo.php',
        data: d,
        dataType: 'json',
        global: false,
        async: false,
        success: function(data, textStatus, jqXHR) {
          //  console.log('cliente: ' + resp);
          //    $('.clientecrm').append(resp);
          return data;
        },
        error: function(data, textStatus, jqXHR) {
          alert('Error: ' + JSON.stringify(data));
          //    $(bloque).find('.dimmer').toggleClass('active');
        }
      }).responseText);  
      return listaFabricantes;
   }
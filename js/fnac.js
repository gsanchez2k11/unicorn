/**
 * Buscan un mpn en FNAC y devuelve "ok" o "error"
 * @param  {[type]} datos               [description]
 * @return {[type]}       [description]
 */

 function buscaOfertaFnac(datos) {

   return JSON.parse($.ajax({
     type: "POST",
    url: "ajax/fnac/buscar-oferta.php",
     data: datos,
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
 }

 function damebatchStatus(batchId) {
   return JSON.parse($.ajax({
     type: "POST",
    url: "ajax/fnac/batch-status.php",
     data: {batch_id: batchId},
     dataType: 'json',
     global: false,
     async: false,
     success: function(data, textStatus, jqXHR) {
       //  console.log('cliente: ' + resp);
       //    $('.clientecrm').append(resp);
           $('.buscando-batch').hide();
       return data;
     },
     error: function(data, textStatus, jqXHR) {
       alert('Error: ' + JSON.stringify(data));
       //    $(bloque).find('.dimmer').toggleClass('active');
     }
   }).responseText);
 }

async function addOfertaFnac(articuloInforpor) {

   let response = await JSON.parse($.ajax({
     type: "POST",
    url: "ajax/fnac/add-oferta.php",
     data: articuloInforpor,
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
   let respuesta = await response;
   return respuesta;

 }

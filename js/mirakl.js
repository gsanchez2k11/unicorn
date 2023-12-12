 /**
  * Funcion que actualiza una oferta en el marketplace correspondiente
  * @param  {[type]} datos [description]
  * @return {[type]}       [description]
  */
 function actualizaOferta(datos, url, plataforma, accion = 'actualizar') {
    return JSON.parse($.ajax({
      type: "POST",
      url: url,
      data: {
       datos: datos,
     plataforma: plataforma},
      dataType: 'json',
      global: false,
      async: false,
      success: function(data, textStatus, jqXHR) {
        //  console.log('cliente: ' + resp);
        //    $('.clientecrm').append(resp);
        coloreaFila(datos.shop_sku, accion)
        return data;
      },
      error: function(data, textStatus, jqXHR) {
        alert('Error: ' + JSON.stringify(data));
        //    $(bloque).find('.dimmer').toggleClass('active');
      }
    }).responseText);
  }
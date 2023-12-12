function grabaDatos(datos,event){
  $.ajax({
    type: "POST",
    url: "ajax/magento/actualizar-articulo.php",
    data: datos,
    beforeSend: function() {
      $('#resultado-actualizar-modal .modal-title').html('Looking for the fiesta');
      $('#resultado-actualizar-modal .modal-body').html('<div class=""><img class="rounded mx-auto d-block" src="https://cdn.dribbble.com/users/2172479/screenshots/6014482/unicorn_dribble1.gif"  style="width:300px" /></div>');

      ResultadoActualizarModal.show();
    },
    success: function(data, textStatus, jqXHR) {
      if (data === 'ok' || data === '"ok"') {                                              //Si tenemos el ok modificamos la ventana para dar la buena noticia
        let colapse = $(event.currentTarget).parent().parent().parent().parent().parent().attr('id');
let numDistintos;
        switch (colapse) {
          case 'collapseExample':
          numDistintos = $('#num-of-caducadas').html();                          //Restamos uno al contador
          $('.num-distintos').html(numDistintos-1);
            break;
            case 'collapseFinalizando':
            numDistintos = $('#num-of-finalizando').html();                          //Restamos uno al contador
            $('#num-of-finalizando').html(numDistintos-1);
              break;

        }

        $(event.currentTarget).parent().parent().parent().fadeOut("slow");               //Ocultamos la fila actual
        $('#resultado-actualizar-modal .modal-title').html('Hemos actualizado el articulo');
        $('#resultado-actualizar-modal .modal-body').html('<div class=""><img class="rounded mx-auto d-block" src="https://media1.tenor.com/images/ce038ac1010fa9514bb40d07c2dfed7b/tenor.gif?itemid=14797681"  style="width:300px" /></div>');
      } else {
        $('#resultado-actualizar-modal .modal-title').html('Ha chocado un accidente');
        $('#resultado-actualizar-modal .modal-body').html('<div class=""><img class="rounded mx-auto d-block mb-1" src="https://media1.tenor.com/images/eb39039fd8ef067bd70dcd4320e8741c/tenor.gif?itemid=11458685"  style="width:300px" /> Es posible que algo haya salido mal, no te voy a decir que no. <br /> Quizás deberías probar un poco más tarde, o quizás deberías comunicarlo a alguien, o actualizarlo a mano, o llamar a una asesoría, en fin, que se yo.</div>');

      }

      console.log(data);
    },
    error: function(data, textStatus, jqXHR) {
      // alert("error:" + respuesta);
      // console.log(respuesta);
      alert('Error: ' + data);
      //location.reload();
    },
  });
//  return data;
}

async function dameOfertas(periodo,campo,idTienda) {
    let url = 'ajax/magento/dame-ofertas.php';
  
  let fecha = new Date();                                                //Creamos un objeto fecha
  fecha.setDate(fecha.getDate()+periodo);                                       //Como queremos las ofertas caducadas restamos un dia a ayer
  let dia = fecha.getDate();                                              //Obtenemos el dia del mes
  let mes = fecha.getMonth() + 1;                                         //Obtenemos el mes, hay que sumarle uno porque van de 0 a 11
  let anyo = fecha.getFullYear();
  let fechaAyer = anyo+'-'+mes+'-'+dia;
  let datos = {
    campo: campo,
    finOferta : fechaAyer,
    operador : 'lt',
    idTienda: idTienda
  };
  
  let response = await JSON.parse($.ajax({
    type: "POST",
    url: url,
    dataType: 'json',
    data: datos,
    global: false,
    async:false,
    success: function(data, textStatus, jqXHR) {
      return data;
    },
    error: function(data, textStatus, jqXHR) {
      console.log('Error al cargar el pedido: ' + JSON.stringify(data));
    }
  }).responseText);
  
    return response;
  }

  function dameDiferenciaDias(finOfertaString){
    //  let finOfertaString =   customAttribut[es].value;                           //Cogemos la fecha de fin de oferta del artículo (yyyy-mm-aa hh:mm:ss)
      let fechaFin = finOfertaString.split(" ");                                  //Troceamos el string y nos quedamos con la parte de la fecha
      let troceaFin = fechaFin[0].split('-');                                     //Troceamos ahora la parte de la fecha para tener un array con año, mes, dia
      var finOferta = new Date(troceaFin[0],troceaFin[1]-1,troceaFin[2]);             //Creamos un objet fecha con los datos que tenemos, al mes hay que restar uno porque van de 0-11
      let hoy = new Date();
      let diferencia = hoy.getTime() - finOferta.getTime();
    diferenciaDias = Math.floor(diferencia / (1000 * 60 * 60 * 24));
    let salida = {
      finOferta : finOferta,
      diferenciaDias: diferenciaDias
    }
    return salida;
    
    }

    function procesa (articul) {
        let articulos = [];
        for(o in articul){
          let articulo = articul[o];
          let art = {};
          let customAttribut = articulo.custom_attributes;
            for (es in customAttribut) {  //Recorremos los custom attributes
              if (customAttribut[es].attribute_code == 'special_price') {
              art.precioOferta =   customAttribut[es].value;
              }
              if (customAttribut[es].attribute_code == 'fin_oferta_descripcion') {
              art.finDescripcion = dameDiferenciaDias(customAttribut[es].value);
              }
              if (customAttribut[es].attribute_code == 'special_to_date') {
              art.finOferta = dameDiferenciaDias(customAttribut[es].value);
              }
              if (customAttribut[es].attribute_code == 'special_from_date') {
              art.inicioOferta = dameDiferenciaDias(customAttribut[es].value);
              }
            }
            art.sku = articulo.sku;
            art.precio = articulo.price;
            art.nombre = articulo.name;
            art.status = articulo.status;
    articulos.push(art);
        }
        return articulos;
    }

    function coloca(articulos,colapse) {
        $('#'+colapse+' .card-body img').remove();                                  //Quitamos la imagen del perrete
        for (item of articulos) {
      
          let textGray = item.status == 2 ? 'text-gray-300':'';
      let precioOferta = item.hasOwnProperty('precioOferta') === true ? parseFloat(item.precioOferta).toFixed(2)+'€' : '';
          let fila = '<div class="row m-2 p-1 '+textGray+'" data-atributos=\''+JSON.stringify(item).replace(/[\/\(\)\']/g, "&apos;")+'\'> ';
          fila += '<div class="col-xl-2">'+item.sku+'</div>';
          fila += '<div class="col-xl-4">'+item.nombre+'</div>';
          fila += '<div class="col-xl-1 text-lg"><div class="text-xs text-gray text-uppercase mb-1"><s>'+item.precio.toFixed(2)+'€</s></div>'+precioOferta+'</div>';
          if (colapse === 'collapseDescripcionFinalizada') {
            let diferenciaDiasColor = item.finDescripcion.diferenciaDias > 0 ? 'text-danger' : '';
            fila += '<div class="col-xl-1 fs-1 '+diferenciaDiasColor+'">'+Math.abs(item.finDescripcion.diferenciaDias)+'<span class="text-xs">dias</span></div>';
            fila += '<div class="col-xl-4"><div class="d-grid gap-2 d-md-block"><button class="btn btn-success m-1 btn-add-time" data-measure="month" type="button">fin mes</button><button class="btn btn-success m-1 btn-add-time" data-measure="quarter" type="button">fin trimestre</button><button class="btn btn-success m-1 btn-add-time" data-measure="year" type="button">fin año</button><button class="btn btn-danger m-1 boton-eliminar" type="button">Eliminar oferta</button></div></div>';
          } else {
            let diferenciaDiasColor = item.finOferta.diferenciaDias > 0 ? 'text-danger' : '';
            fila += '<div class="col-xl-1 fs-1 '+diferenciaDiasColor+'">'+Math.abs(item.finOferta.diferenciaDias)+'<span class="text-xs">dias</span></div>';
      
            fila += '<div class="col-xl-4"><div class="d-grid gap-2 d-md-block"><button class="btn btn-success m-1 btn-add-time btn-sm" data-measure="week" type="button">+1 semana</button><button class="btn btn-success m-1 btn-add-time btn-sm" data-measure="month" type="button">+1 mes</button><button class="btn btn-success m-1 btn-add-time btn-sm" data-measure="quarter" type="button">+3 meses</button><button class="btn btn-success m-1 btn-add-time btn-sm" data-measure="year" type="button">+1 año</button><button class="btn btn-danger m-1 boton-eliminar" type="button">Eliminar oferta</button></div></div>';
      
          }
          fila += '</div>';
      
          $('#'+colapse+' .card-body').append(fila);
        }
      }

      function borraOferta(event){
        let base = $(event.currentTarget).parent().parent().parent();
        let data = $(base).data('atributos');
        let colapse = $(base).parent().parent().attr('id');
        let datos = {
        sku: data.sku
        }
        if (colapse == 'collapseFinalizando' || colapse == 'collapseExample') {
          datos.special_to_date = '';
          datos.special_from_date = '';
          datos.special_price = '';
      } else if (colapse == 'collapseDescripcionFinalizada'){
        datos.fin_oferta_descripcion = '';
      }
      console.log(datos);
      grabaDatos(datos,event);
      //    return grabar;
      }
      function daleTiempo(event){
        let base = $(event.currentTarget).parent().parent().parent();
        let data = $(base).data('atributos');
        let colapse = $(base).parent().parent().attr('id');
        let medida = $(event.currentTarget).data('measure');
      let nuevaFecha = new Date();
      let esteMes = nuevaFecha.getMonth() + 1;
      let esteAnyo = nuevaFecha.getFullYear();
      let nF;
      let datos = {
      sku: data.sku
      }
      
      if (colapse == 'collapseFinalizando' || colapse == 'collapseExample') {
      //let finOfertaDate = new Date(data.finOferta);
      
        switch (medida) {
          case 'week':
      nuevaFecha.setDate(nuevaFecha.getDate()+7);
      //console.log(nuevaFecha);
            break;
            case 'month':
        nuevaFecha.setMonth(nuevaFecha.getMonth()+1);
              break;
              case 'quarter':
                nuevaFecha.setMonth(nuevaFecha.getMonth()+3);
                      break;
              case 'year':
          nuevaFecha.setFullYear(nuevaFecha.getFullYear()+1);
                break;
        }
      
      //let nMes = nuevaFecha.getMonth() + 1;
      let fechaActualizar = nuevaFecha.getFullYear() + '-' + (nuevaFecha.getMonth() + 1) + '-' +  nuevaFecha.getDate();
      //let fechaActualizar = nF.getFullYear() + '-' + (nF.getMonth()+1) + '-' +  nF.getDate();
      //console.log(fechaActualizar);
      
        datos.special_to_date =  fechaActualizar;
        grabar = grabaDatos(datos,event);
      } else if (colapse == 'collapseDescripcionFinalizada'){
      
        switch (medida) {
          case 'month':
          //Final de mes
          let finalMes = new Date(esteAnyo,esteMes,0);
          nF = finalMes;
            break;
            case 'quarter':
            //Final de trimestre
            let i = esteMes;
            let mesFin;
            while (i % 3 !== 0) {
                i++
              mesFin = i;
            }
            let finalTrimestre = new Date(esteAnyo,mesFin,0);
            nF = finalTrimestre;
              break;
              case 'year':
              //Final de Año
              let finalAnyo =  new Date(esteAnyo,11,31);
              nF = finalAnyo;
                break;
        }
        let fechaActualizar = nF.getFullYear() + '-' + (nF.getMonth()+1) + '-' +  nF.getDate();
        datos.fin_oferta_descripcion =  fechaActualizar;
      
      //  nuevaFecha.setDate(nF);
      grabar = grabaDatos(datos,event);
      }
      
      
      
      }
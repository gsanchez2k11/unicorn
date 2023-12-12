<?php
//Configuramos las opciones de errores
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once 'config.php.inc';              //Incluimos el archivo de configuración ?>


<?php
//Definimos el título de la página
$titulo_pagina = '';
include 'src/cabecera.php';                 //Incluimos la cabecera
?>
<body id="page-top">
  <script src="js/magento.js"></script>
  <script src="js/tarifa.js"></script>
  <?php
  include 'src/loader.php';                 //Incluimos la cabecera
  ?>
  <!-- Page Wrapper -->
  <div id="wrapper">
    <?php include 'src/sidebar.php';                 //Incluimos el panel lateral ?>
    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">
        <?php include 'src/topbar.php';                 //Incluimos el panel superior ?>

        <!-- Begin Page Content -->
        <div class="container-fluid">
          <!-- Contenido de la página-->
          <?php
          /*
          * Comprobamos si NO estamos recibiendo la variable referencia mediante método GET
          * en ese caso mostramos la caja de búsqueda grande
          */
          if (filter_input(INPUT_GET, "pl", FILTER_SANITIZE_FULL_SPECIAL_CHARS)): ?>

          <script>
          $('.loader').show();                                                   //Activamos el loader

          $(document).ready(function(){
            const querystring = window.location.search;
            console.log(querystring)
            const params = new URLSearchParams(querystring);
            //       params.set(variable, valor);
            const plataforma = params.get('pl');

            var modalSubePrecios = new bootstrap.Modal(document.getElementById('modal-sube-precios'));


            var listadoTarifa;
            var listaCatsSelect = document.getElementById('seleccionar-categoria'); //Select de categorias
            var resultadosBusqueda = document.getElementById('resultados-busqueda');
            var cardBody = resultadosBusqueda.querySelector('.card-body');
            var cardExito = document.getElementById('card-exito');
            var rowTitulos = document.getElementById('row-titulos');

            var artsPrecioD = [];

            if (plataforma == 'odoo') {
              let datos = {
                modelo : 'product.category'
              }
              let listaCategorias = llamadaJson('ajax/odoo/listar.php',datos);  //Recuperamos el listado de categorias
              function filtrarCategorias(listaCategorias){
                let categorias = listaCategorias.filter(cat => cat.child_id.length === 0);  //Filtramos para dejar sólo aquellas categorias que no tienen hijos
                return categorias;
              }
              let misCats = filtrarCategorias(listaCategorias);
              misCats.sort((a,b) => (a.complete_name > b.complete_name) ? 1 : ((b.complete_name > a.complete_name) ? -1 : 0)); //Ordenamos las categorias por el nombre completo
              misCats.forEach((cat,i) => { //Recorremos el listado de categorias
                let option = document.createElement("option"); //Creamos un elemento option
                option.text = cat.complete_name; //Asignamos como texto el nombre completo de la categoria
                option.value = cat.complete_name;          //Asignamos como valor la id
                listaCatsSelect.add(option);    //Añadimos la opcion creada a la lista
              });
              function activaBoton(selects){
                let resultado = selects.map(este => este.value);
                let inactivo = resultado.includes("0");
                if (inactivo === false) {
                  btnDameArticulos.classList.remove('disabled'); //activamos el botón
                } else {
                  btnDameArticulos.classList.add('disabled'); //desactivamos el botón
                }

              }

              let selects = Object.values(document.getElementsByClassName('select-articulo')); //Los desplegables
              var btnDameArticulos = document.getElementById('btn-dame-articulos');   //Boton para crear artículo
              activaBoton(selects); //Al abrir el modal ejecutamos la funcion una vez para habilitar o no el botón
              selects.forEach((item, i) => {                                                  //El evento que escucha cualquier cambio
                item.addEventListener('change', function(event){
                  activaBoton(selects)
                });
              });

            }
            btnDameArticulos.addEventListener('click', function(event) { //CApturamos el click en dame los artículos
              rowTitulos.style.display = 'none';
              let colsTitulos = cardBody.querySelectorAll('.col-titulo'); //Al hacer click eliminamos los resultados existentes
              for (ra of colsTitulos) {
                ra.parentNode.removeChild(ra);
              }

              let rowsArts = cardBody.querySelectorAll('.row-articulo'); //Al hacer click eliminamos los resultados existentes
              for (ra of rowsArts) {
                ra.parentNode.removeChild(ra);
              }
              cardExito.style.display = 'none'; //ocultamos a Piojo
              let datos = {
                modelo : 'product.product',
                campo : 'categ_id',
                valor: listaCatsSelect.value
              }
              let listaArticulos = llamadaJson('ajax/odoo/busqueda.php',datos);  //Buscamos los Artículos
              let titulo = resultadosBusqueda.querySelector('h5');
              let emosidoImagen = resultadosBusqueda.querySelector('img');
              let parrafo = resultadosBusqueda.querySelector('p');
              if (listaArticulos.length > 0) {
                rowTitulos.style.display = 'flex';
                titulo.innerHTML = 'Esto es lo que tenemos'; //Cambiamos el título
                emosidoImagen.style.display = 'none';
                parrafo.style.display = 'none';

                for (articulo of listaArticulos) {
                  //Creamos la fila
                  let row = document.createElement('div');
                  row.classList.add('row','row-articulo','p-2');
                  row.setAttribute('data-articulo',JSON.stringify(articulo).replace(/[\/\(\)\']/g, "&apos;"));

                  cardBody.appendChild(row);
                  //Creamos las columnas
                  //Primero la columna con la referencia
                  let colRef = document.createElement('div');
                  colRef.classList.add('col-1','col-ref');
                  colRef.appendChild(document.createTextNode(articulo.default_code));
                  row.appendChild(colRef);
                  //Ahora  la columna con el nombre
                  let colNombre = document.createElement('div');
                  colNombre.classList.add('col-7','col-nombre');
                  colNombre.appendChild(document.createTextNode(articulo.name));
                  row.appendChild(colNombre);
                  //Ahora  la columna para las acciones
                  let colAcciones = document.createElement('div');
                  colAcciones.classList.add('col-3','col-acciones');
                  colAcciones.appendChild(document.createTextNode(''));
                  row.appendChild(colAcciones);
                }
                //Activamos los botones de acción
                let btnAcciones = document.getElementsByClassName('btn-acciones');
                for (a of btnAcciones) {
                  a.classList.remove('disabled');
                }

              } else {
                titulo.innerHTML = 'Emosido engañado'; //Cambiamos el título
                emosidoImagen.style.display = 'block';
                parrafo.style.display = 'block';
              }
              console.log(listaArticulos.length);
              console.log(listaArticulos);
              resultadosBusqueda.style.display = 'inline';
            })


function ComprobarPrecioCompra(articulos)  {
  colocaColumnas();
  if (listadoTarifa === undefined) {
  let datos;
  miTarifa = llamadaJson('ajax/google/dame-tarifa-2022.php',datos);             //Pedimos la tarifa si no la tenemos ya
  listadoTarifa = damelistado(miTarifa);                                      //Obtenemos el listado plano
}
colocaResultado(articulos,'compra');
}
function colocaColumnas(){
  let colTitulosAcc = rowTitulos.querySelector('.col-acciones'); //Seleccionamos la columna donde vamos a poner los datos
  let rowTitAcc = document.createElement('div'); //Creamos un nuevo nodo, la fila con los títulos
  rowTitAcc.classList.add('row'); //Añadimos la clase
  colTitulosAcc.appendChild(rowTitAcc); //Añadimos el nodo al html

  //Colocamos los títulos
  let colPrecios = document.createElement('div'); //Nodo precios
  colPrecios.classList.add('col-6','col-titulo');
  rowTitAcc.appendChild(colPrecios);
  let rPrecio1 = document.createElement('div');
  rPrecio1.classList.add('row','align-items-center');

  rPrecio1.appendChild(document.createTextNode('Precios'));
  colPrecios.appendChild(rPrecio1);
  let rPrecio2 = document.createElement('div');
  rPrecio2.classList.add('row');
  colPrecios.appendChild(rPrecio2);

  let colPrecioOdoo = document.createElement('div');
  colPrecioOdoo.classList.add('col-6');
  colPrecioOdoo.appendChild(document.createTextNode('odoo'));
  rPrecio2.appendChild(colPrecioOdoo);
  let colPrecioMage = document.createElement('div');
  colPrecioMage.classList.add('col-6');
  colPrecioMage.appendChild(document.createTextNode('Origen'));
  rPrecio2.appendChild(colPrecioMage);

  let colStocks = document.createElement('div'); //Nodo precios
  colStocks.classList.add('col-6','col-titulo');
  rowTitAcc.appendChild(colStocks);
  let rStock1 = document.createElement('div');
  rStock1.classList.add('row');
  rStock1.appendChild(document.createTextNode('Stock'));
  colStocks.appendChild(rStock1);
  let rStock2 = document.createElement('div');
  rStock2.classList.add('row');
  colStocks.appendChild(rStock2);






  /*let colStock = document.createElement('div'); //nodo stocks
  colStock.classList.add('col-6','col-titulo');
  colStock.appendChild(document.createTextNode('Stocks'));
  rowTitAcc.appendChild(colStock);*/



  /*let rowFuentePrecio = document.createElement('div');
  rowFuentePrecio.classList.add('row');
  colPrecios.appendChild(rowFuentePrecio);

  let colPrecioOdoo = document.createElement('div');
  colPrecioOdoo.classList.add('col-4');
  colPrecioOdoo.appendChild(document.createTextNode('odoo'));
  rowFuentePrecio.appendChild(colPrecioOdoo);

  let colPrecioAcciones = document.createElement('div');
  colPrecioAcciones.classList.add('col-2');
  colPrecioAcciones.appendChild(document.createTextNode(''));
  rowFuentePrecio.appendChild(colPrecioAcciones);

  let colPrecioMagento = document.createElement('div');
  colPrecioMagento.classList.add('col-4');
  colPrecioMagento.appendChild(document.createTextNode('mage'));
  rowFuentePrecio.appendChild(colPrecioMagento);

  let colStock = document.createElement('div');
  colStock.classList.add('col-1','col-titulo');
  colStock.appendChild(document.createTextNode('Stocks'));
  rowTitulos.appendChild(colStock);

  let colDescuadre = document.createElement('div');
  colDescuadre.classList.add('col-1','col-titulo', 'text-center');
  rowTitulos.appendChild(colDescuadre);*/
}

function colocaPrecios(datosArt,precioOdoo,precioOrigen,tipo) {
  let col3 = articulo.querySelector('.col-3'); //Leemos la info del artículo
  let rowAcc = document.createElement('div'); //Creamos una fila y la añadimos
  let colPrecios = document.createElement('div');
  let colStock = document.createElement('div');
  rowAcc.classList.add('row');
  col3.appendChild(rowAcc);
  //Columna para los precios
  colPrecios.classList.add('col-6');
  rowAcc.appendChild(colPrecios);
  //Columna para los stocks
  colStock.classList.add('col-6');
  rowAcc.appendChild(colStock);
  let dta = {
    idOdoo: datosArt.id,
  //  idMage: artMage.id,
    pOdoo: precioOdoo,
    pMage: precioOrigen,
    tipo: tipo
  }
  artsPrecioD.push(dta);
  let rowPrecios = document.createElement('div');
  rowPrecios.classList.add('row');
  let colRowPrecioOdoo = document.createElement('div');
  colRowPrecioOdoo.classList.add('col-6');
  colRowPrecioOdoo.appendChild(document.createTextNode(parseFloat(precioOdoo).toFixed(2) + '€'));
  rowPrecios.appendChild(colRowPrecioOdoo);
  let colRowPrecioMagento = document.createElement('div');
  colRowPrecioMagento.classList.add('col-6');
  colRowPrecioMagento.appendChild(document.createTextNode(parseFloat(precioOrigen).toFixed(2) + '€'));
  rowPrecios.appendChild(colRowPrecioMagento);
  colPrecios.appendChild(rowPrecios);

  if (artsPrecioD.length > 0) { //Si hay precios para cambiar mostramos el botón que lo permite
    let btnSube = document.getElementById('btn-sube-precios');
    btnSube.style.display = 'block';
    //btn-sube-precios
  }
}
function colocaResultado (articulos, tipo){
        for (articulo of articulos) { //Recorremos el listado de artículos
          let col3 = articulo.querySelector('.col-3'); //Leemos la info del artículo
          let datosArt = JSON.parse(articulo.getAttribute('data-articulo')); //Y la convertimos en un objeto desde el JSON
          let fabricante = datosArt.x_studio_fabricante;  //Leemos el fabricante, para obviar los artículos de Epson
          let refOdoo = datosArt.default_code;
          let stockOdoo = datosArt.qty_available;
          let precioOdoo = datosArt.fix_price;
//Nodos comunes
      let txtTodoIgual = document.createTextNode('Los datos coinciden');

switch (tipo) {
  case 'inventario':
  let datos = {
    mpn : refOdoo
  }
  let artMage = llamadaJson('ajax/magento/dame-info-articulo.php',datos);             //Buscamos el artículo en magento
  //    console.log(artMage);
  if (artMage.hasOwnProperty('id')) {
  //  let precioMage = artMage.price; //Precio del articulo en magento
      let precioOrigen = artMage.price; //Precio del articulo en magento
    let stockLocal = artMage.custom_attributes.filter(attr => attr.attribute_code === "stock_local"); //Comprobamos si el stock de este producto se controla de manera local
    //console.log(stockLocal);
    let qtyMage = stockLocal.length > 0 && stockLocal[0].value == '1' ? artMage.custom_attributes.filter(attr => attr.attribute_code === "quantity_and_stock_status")[0].value[1] : stockOdoo; //Si se controla en local buscamos el stock de magento para comparar, si no es así directamente cogemos el valor de odoo para que sea true
    if (precioOdoo === precioOrigen && stockOdoo === qtyMage) {
      col3.appendChild(txtTodoIgual); //Si los datos son iguales lo ponemos
    } else {

      if (precioOdoo !== precioOrigen && fabricante !== 'Epson') { //Si el precio de Magento no coincide con el precio de odoo y el fabricante no es Epson
              colocaPrecios(datosArt,precioOdoo,precioOrigen,tipo);
      }
      if (stockOdoo !== qtyMage) {
        //Texto con los stocks
        let txtStock = document.createTextNode(stockOdoo + ' ' + qtyMage);
        colStock.appendChild(txtStock);
        //Botón para cambiar
        let boton = document.createElement('button');
        boton.setAttribute('type','button');
        boton.classList.add('btn', 'btn-outline-primary','btn-subir-stock');
        let txtBtn = document.createTextNode('subir');
        boton.appendChild(txtBtn);
        colStock.appendChild(boton);


      }
      //  console.log('precios ' + precioOdoo + ' | ' + precioMage);
      //  console.log('stock ' + stockOdoo + ' | ' + qtyMage);
    }

  } else {
    let txtNoEncontrado = document.createTextNode('no existe en Magento');
    col3.appendChild(txtNoEncontrado);
  }
    break;
case 'compra':
//let datosArt = JSON.parse(articulo.getAttribute('data-articulo'));
//let refOdoo = datosArt.default_code;
//let stockOdoo = datosArt.qty_available;
let encontrado = listadoTarifa.find(art => art.referencia == refOdoo);
if (encontrado !== undefined) {  //Solo procesamos los que vamos encontrando
  console.log(encontrado);
let precioOdoo = datosArt.standard_price;
//let precioOrigen =  encontrado.precio;
let precioOrigen =  encontrado.compras[0].total_compra;
if (precioOdoo === precioOrigen) {
  col3.appendChild(txtTodoIgual);
} else {
  colocaPrecios(datosArt,precioOdoo,precioOrigen,tipo);
  //  console.log(precioOdoo +'|' + precioTarifa);
}

}
//console.log(listadoTarifa);
break;

}

        }
}

            function Inventariar(articulos) {
colocaColumnas(); //Colocamos las columnas
colocaResultado(articulos,'inventario');
/*        for (articulo of articulos) { //Recorremos el listado de artículos
          let col3 = articulo.querySelector('.col-3'); //Leemos la info del artículo
          let datosArt = JSON.parse(articulo.getAttribute('data-articulo')); //Y la convertimos en un objeto desde el JSON

          let fabricante = datosArt.x_studio_fabricante;  //Leemos el fabricante, para obviar los artículos de Epson
          //  console.log(datosArt);
          let refOdoo = datosArt.default_code;
          let stockOdoo = datosArt.qty_available;
          let precioOdoo = datosArt.fix_price;



        }*/



      }


      function CompruebaCustodia(articulos){

        for (articulo of articulos) {
          let datosArt = JSON.parse(articulo.getAttribute('data-articulo'));
          let ref = datosArt.default_code;
          let stock = datosArt.qty_available;
          let datos = {
            mpn: ref
          }
          console.log(datos);
          let compraInforpor = llamadaJson('ajax/inforpor/obtener-compra.php',datos);  //Buscamos los Artículos
          console.log('compra');
          console.log(compraInforpor);
          if ((stock === 0 && compraInforpor.custodias === false) || compraInforpor.normal_inforpor.CodErr == ' Producto vacio ') { //Si el articulo no existe en inforpor o tiene stock 0 en odoo sin custodias no nos vale para nada
            articulo.parentNode.removeChild(articulo); //Lo eliminamos
          } else {
            let quedan = 0;
            if (compraInforpor.custodias !== false) {
              for (custodia of compraInforpor.custodias ) {
                quedan = quedan + parseInt(custodia.quedan);
              }
            }
            let diferencia = stock - quedan;
            if (diferencia !== 0) {
              let col3 = articulo.querySelector('.col-3'); //La columna que hemos dejado para las Acciones
              col3.classList.add('col-1'); //La modificamos y la hacemos más pequeña
              col3.classList.remove('col-3');
              let numOdoo = document.createTextNode(stock);
              col3.appendChild(numOdoo);

              let col4 = document.createElement('div'); //Añadimos una nueva columna
              col4.classList.add('col-1');
              let numInforpor = document.createTextNode(quedan);
              col4.appendChild(numInforpor);
              articulo.appendChild(col4);

              let col5 = document.createElement('div'); //Añadimos una nueva columna
              col5.classList.add('col-1');
              let dif = document.createTextNode(stock - quedan);
              col5.appendChild(dif);
              articulo.appendChild(col5);
            } else {
              articulo.parentNode.removeChild(articulo); //Lo eliminamos
            }
            console.log(datosArt);
            console.log(compraInforpor);
            console.log('quedan ' + quedan);
          }
        }
        //Contamos los artículos que tenemos
        let rowRestantes = document.querySelectorAll('.row-articulo').length;
        if (rowRestantes > 0) { //si hay más de un artículo ponemos la fila con los títulos

          let colOdoo = document.createElement('div');
          colOdoo.classList.add('col-1','col-titulo');
          colOdoo.appendChild(document.createTextNode('Odoo'));
          rowTitulos.appendChild(colOdoo);

          let colInforpor = document.createElement('div');
          colInforpor.classList.add('col-1','col-titulo');
          colInforpor.appendChild(document.createTextNode('Custodias'));
          rowTitulos.appendChild(colInforpor);

          let colDescuadre = document.createElement('div');
          colDescuadre.classList.add('col-1','col-titulo');
          colDescuadre.appendChild(document.createTextNode('Diferencia'));
          rowTitulos.appendChild(colDescuadre);


        } else {
          rowTitulos.style.display = 'none';
          cardExito.style.display = 'flex';
        }
      }

      let btnCompruebaCustodia = document.getElementById('btn-comprueba-custodias');
      btnCompruebaCustodia.addEventListener('click', function(event) { //CApturamos el click en comprobar custodias
        $('.loader').show();
        let articulos = document.querySelectorAll('.row-articulo');
        CompruebaCustodia(articulos);
        $('.loader').hide();

      });

      let btnInventario = document.getElementById('btn-inventario');
      btnInventario.addEventListener('click', function(event) { //CApturamos el click en comprobar custodias
        $('.loader').show();
        let articulos = document.querySelectorAll('.row-articulo');
        Inventariar(articulos);

        let btnSubirStock = document.querySelectorAll('.btn-subir-stock'); //CApturamos el click en el botón de subir el stock
        btnSubirStock.forEach(el => el.addEventListener('click', event => {
          let datosArt = JSON.parse(event.target.parentNode.parentNode.parentNode.parentNode.getAttribute('data-articulo'));

          let inStock = datosArt.qty_available > 0 ? 1 : 0;
          let datos = {
            sku: datosArt.default_code,
            extension_attributes : {
              stock_item : {
                qty: datosArt.qty_available,
                is_in_stock: inStock
              }
            }
          }

          $.ajax({
            type: "POST",
            url: "ajax/magento/actualizar-articulo.php",
            data: datos,
            success: function(data, textStatus, jqXHR) {
              document.getElementById('mensaje-referencia').innerHTML = datos.sku;
              new bootstrap.Toast(document.querySelector('#basicToast')).show();
              event.target.parentNode.innerHTML = '';
              //  console.log(datos);
            },
            error: function(data, textStatus, jqXHR){
              console.log('Error: ' + data);
            }
          })
          //console.log(datosArt);
        }));
        $('.loader').hide();

      });


      let btnSubePrecios = document.getElementById('btn-sube-precios');
      btnSubePrecios.addEventListener('click', function(event) {
        let numPreciosSubir = document.getElementById('num-precios-subir');    //Colocamos el número de cambios que vamos a realizar
        numPreciosSubir.innerHTML = artsPrecioD.length;
        modalSubePrecios.show(); //Mostramos el modal con la info
        let bProgreso = document.getElementById('barra-progreso-subir-precios');
        let numActual = document.getElementById('num-precios-subir-actual');
        for (let i in artsPrecioD) {
          let datos = {
            id: artsPrecioD[i].idOdoo,
            pTarifa: artsPrecioD[i].pMage,
            tipo: artsPrecioD[i].tipo
          }
          let artSubir = llamadaJson('ajax/odoo/actualizar-articulo.php',datos);  //Buscamos los Artículos
          if (artSubir == 'ok') {
            let pasoPorcentaje = 100/artsPrecioD.length;
            bProgreso.setAttribute('aria-valuenow',pasoPorcentaje*(parseInt(i)+1));
            bProgreso.style.width = pasoPorcentaje*(parseInt(i)+1) + '%';
            numActual.innerHTML = parseInt(i)+1;
          }

        }

      });

      let btnCompruebaCompras = document.getElementById('btn-compra');
      btnCompruebaCompras.addEventListener('click', function(event) {
        let articulos = document.querySelectorAll('.row-articulo');
        ComprobarPrecioCompra(articulos);
      //  console.log(articulos);
      });


      listaCatsSelect.addEventListener('change',function(event){
        artsPrecioD = [];
      });

    }); //Fin document.ready

    </script>
  <?php else:    //Si no recibimos variable get
    ?>
    <script>
    $('.loader').show();                                                   //Activamos el loader
    $(document).ready(function(){

    });



    </script>


  <?php endif; //Fin de la comprobación la variable GET?>
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-10">
        <div class="w-auto card" >
          <div class="card-body">
            <h5 class="card-title">Navegando.</h5>
            <div class="mb-3">
              <label for="seleccionar-categoria" class="form-label">Elige la categoría</label>

              <select class="form-select select-articulo" aria-label="seleccionar categoria" id="seleccionar-categoria">
                <option value="0" selected>Categoria del artículo</option>
              </select>
            </div>
            <div class="d-grid gap-2 col-8 mx-auto mt-2">
              <a href="#" class="btn btn-success btn-icon-split disabled" id="btn-dame-articulos">
                <span class="icon text-white-50">
                  <i class="fas fa-check"></i>
                </span>
                <span class="text">Dame los artículos</span>
              </a>
            </div>

          </div>
        </div>
      </div>
      <div class="col-md-2">
        <div class="w-auto card" >
          <div class="card-body">
            <h5 class="card-title">Asiones</h5>
            <div class="d-grid gap-2">
              <button type="button" class="btn btn-info btn-icon-split  btn-acciones disabled" id="btn-comprueba-custodias">    <span class="icon text-white-50">
                <i class="fas fa-shield-alt"></i>
              </span>
              <span class="text">custodias</span></button>
              <button type="button" class="btn btn-success btn-icon-split  btn-acciones disabled" id="btn-inventario">    <span class="icon text-white-50">
                <i class="fas fa-clipboard-list"></i>
              </span>
              <span class="text">Inventario</span></button>
              <button type="button" class="btn btn-success btn-icon-split  btn-acciones disabled" id="btn-compra">    <span class="icon text-white-50">
                <i class="fas fa-clipboard-list"></i>
              </span>
              <span class="text">Compra</span></button>
            </div>
          </div></div>
        </div>
      </div>

      <div class="row justify-content-center mt-2" id="resultados-busqueda" style="display:none">
        <div class="w-auto card" >
          <div class="card-body">
            <h5 class="card-title">Emosido engañado</h5>
            <img src="https://ep01.epimg.net/verne/imagenes/2020/02/12/articulo/1581533769_341780_1581537731_noticia_normal.jpg" alt="" class="mx-auto">
            <p class="card-text text-center">Esta categoria está vacía como el cerebro de muchas personas.</p>
            <div class="row p-2" id="row-titulos">
              <div class="col-1">referencia</div><div class="col-7">Nombre</div><div class="col-4 col-acciones"></div>
            </div>
            <div class="card mb-3" id="card-exito" style="display:none">
              <div class="row g-0">
                <div class="col-md-4">
                  <img src="img/piojo/fondo-cielo.png" class="rounded-start" alt="Todo guay" style="width:150px">
                </div>
                <div class="col-md-8">
                  <div class="card-body">
                    <p class="card-text">Buenas noticias. Los datos son coherentes, así que no hay nada más que ver aquí</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row justify-content-center mt-2" id="acciones-globales">
        <div class="col-2">
          <button type="button" class="btn btn-success btn-icon-split  btn-acciones disabled" id="btn-sube-precios" style="display:none">    <span class="icon text-white-50">
            <i class="fas fa-clipboard-list"></i>
          </span>
          <span class="text">Subir precios</span></button>
        </div>
      </div>

      <div class="toast-container position-fixed top-0 end-0 p-3">
        <div id="basicToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
          <div class="toast-header bg-info text-light">
            <h5 class="my-0">Actualizado</h5>
          </div>
          <div class="toast-body">
            Se ha sincronizado el stock del articulo <span id="mensaje-referencia"></span> de Odoo a Magento exisotasemente.
          </div>
        </div>
      </div>
      <!--Modal con la subida de precios -->
      <div class="modal" id="modal-sube-precios" tabindex="-1">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Cambiando precios</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <div class="progress" >
                <div id="barra-progreso-subir-precios" class="progress-bar progress-bar-striped progress-bar-animated bg-info" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
              </div>
              <p>Cambiando <span id="num-precios-subir-actual">0</span> de <span id="num-precios-subir"></span>
              </p>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
          </div>
        </div>
      </div>
      <!--Fin de Modal con la subida de precios -->
    </div>
  </div>
  <!-- /.container-fluid -->

</div>
<!-- End of Main Content -->
<?php
include 'src/footer.php';

?>

</div>
<!-- End of Content Wrapper -->
</div>
<!-- End of Page Wrapper -->
<!-- Scroll to Top Button-->
<a class="scroll-to-top rounded" href="#page-top">
  <i class="fas fa-angle-up"></i>
</a>
<!-- Logout Modal-->
<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
aria-hidden="true">
<div class="modal-dialog" role="document">
  <div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
      <button class="close" type="button" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span>
      </button>
    </div>
    <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
    <div class="modal-footer">
      <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
      <a class="btn btn-primary" href="login.html">Logout</a>
    </div>
  </div>
</div>
</div>
<!-- Bootstrap core JavaScript-->
<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous"></script>
<!-- Core plugin JavaScript-->
<script src="vendor/jquery-easing/jquery.easing.min.js"></script>

<!-- Custom scripts for all pages-->
<script src="js/sb-admin-2.min.js"></script>

<!-- Page level plugins -->
<script src="vendor/chart.js/Chart.min.js"></script>

<!-- Page level custom scripts -->
<script src="js/demo/chart-area-demo.js"></script>
<script src="js/demo/chart-pie-demo.js"></script>

</body>
</html>

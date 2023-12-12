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

         <script>
         $('.loader').show();                                                   //Activamos el loader
           $(document).ready(function(){
            let btnListarNuncaStock = document.getElementById('articulos-nunca-stock'); //Capturamos el click en el botón buscar
            btnListarNuncaStock.addEventListener("click", listarNuncaStock); //Llamamos a la funcion clickBuscar
            function listarNuncaStock() {
              console.log('bamo');
              let plataforma = 'pcc';
              let datos = {
                plataforma : plataforma
              }
              let articulos =  JSON.parse($.ajax({
    type: "POST",
    url: "ajax/unicorn_db/listar-articulos-nunca-stock.php",
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

  document.getElementById('contenido-contenedor').innerHTML = ''; //Limpiamos el cuadro de resultados
if (articulos.length > 0) {
 let listG = document.createElement('ul'); //Creamos la lista 
 listG.classList.add('list-group','list-group-flush');
 document.getElementById('contenido-contenedor').appendChild(listG);
 articulos.forEach(articulo => {
  let li = document.createElement('li');
  li.classList.add('list-group-item', 'text-gray-600');
  li.innerHTML = '[' + articulo[2] + '] ' + articulo['4'];
  listG.appendChild(li);
  let span = document.createElement('span');
  span.classList.add('float-end');
  span.setAttribute('atributos',JSON.stringify(articulo).replace(/[\/\(\)\']/g, "&apos;"));
  span.innerHTML = '<i class="fa-solid fa-wand-sparkles consulta-referencia" style="cursor: pointer"></i>';
  li.appendChild(span);
 });
}
  console.log(articulos);

  let btnConsultaReferencia = document.querySelectorAll('.consulta-referencia');
 btnConsultaReferencia.forEach( i => {
  i.addEventListener('click', function (e) {
     let atributos = JSON.parse(e.target.parentNode.getAttribute('atributos')); //Capturamos el mpn

e.target.parentNode.classList.add('visually-hidden');//Ocultamos el icono sobre el que acabamos de hacer click
//Buscamos en Pc componentes
if (atributos.hasOwnProperty('7')) {
  let skuPcc = atributos['7'];
  let enPcc = JSON.parse($.ajax({
    type: "POST",
    url: "ajax/mirakl/buscar-articulo.php",
   data: {product_sku : skuPcc},
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

let ofertas = enPcc.products[0].offers;
let ofFutura = 0;
if (ofertas !== undefined) {
  ofFutura =   ofertas.filter(of => of.shop_name == 'Futura Teck');
  
}
console.log(ofFutura);

}

  })
 });
            }


});




         </script>
         <div class="row justify-content-center">
         <div class="col-xl-2">
         <div class="card shadow mb-4">
         <div class="card-body" style="cursor:pointer" id="articulos-nunca-stock">
          <p>Artículos que nunca han tenido stock</p>
         </div>
         </div>
         </div>
         <div class="col-xl-2">
          Columna
         </div>
         <div class="col-xl-2">
          Columna
         </div>
         <div class="col-xl-2">
          Columna
         </div>
         <div class="col-xl-2">
          Columna
         </div>
         <div class="col-xl-2">
          Columna
         </div>
         </div>
         <div class="row justify-content-center" id="contenedor-listados">
         <div class="card shadow mb-4" id="contenido-contenedor">Contenido
         </div>

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

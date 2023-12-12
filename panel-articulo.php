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
          <?php
          /*
          * Comprobamos si NO estamos recibiendo la variable referencia mediante método GET
          * en ese caso mostramos la caja de búsqueda grande
          */
          if (filter_input(INPUT_GET, "q", FILTER_SANITIZE_FULL_SPECIAL_CHARS)): ?>

          <script>
          $('.loader').show();                                                   //Activamos el loader
          $(document).ready(function(){
            const querystring = window.location.search;
            console.log(querystring)
            const params = new URLSearchParams(querystring);
            //       params.set(variable, valor);
            console.log(params); // "q=URLUtils.searchParams"
          });

          </script>
        <?php else:    //Si no recibimos variable get
          ?>
          <script>
          $('.loader').show();                                                   //Activamos el loader
          $(document).ready(function(){
            let datos;
            let tarifaCompleta = llamadaJson('ajax/google/dame-tarifa-2022.php',datos);             //Buscamos en la base de datos
            var textoBuscar = document.getElementById('input-buscar');
            let terminoBusqueda = document.getElementById('button-buscar'); //Capturamos el click en el botón buscar
            let listaResultados = document.getElementById('resultados-busqueda');
            let rowResultadosBusqueda = document.getElementById('row-resultados-busqueda');
            terminoBusqueda.addEventListener("click", clickBuscar); //Llamamos a la funcion clickBuscar
            textoBuscar.addEventListener("keyup", function(e) {
              if (e.key === 'Enter') {
                clickBuscar();
              }
            }); //Llamamos a la funcion clickBuscar


function colocaListado(arr){
  listaResultados.style.display = 'block';
  rowResultadosBusqueda.style.display = 'flex';

  arr.forEach((item, i) => {
    let li = document.createElement("li");
    li.classList.add('list-group-item');
    li.append('[' + item[0] + '] ' + item[2]);
    listaResultados.append(li);

  });
}


            function clickBuscar(){
              let termino = textoBuscar.value;
              if (termino.length < 3) {                                                       //Comprobamos si al menos hemos introducido 3 caracteres
                alert('por favor, introduce 3 o más caracteres para buscar'); //mostramos un mensaje si no
              } else {
                let regex = new RegExp(termino,'i'); //configuramos el patron, incluyendo la bandera i para busqueda sin distinguir mayúsculas
                //DEfinimos las variables


                let grupoLlama = document.getElementsByClassName('grupo-llama');

                let baseDatos = document.getElementById('base-datos'); //Mostramos el elemento de la lista para la base de datos
                let tarifa = document.getElementById('tarifa'); //Mostramos el elemento de la lista para la tarifa
                let inforpor = document.getElementById('inforpor'); //Mostramos el elemento de la lista para la tarifa
                let magento = document.getElementById('magento'); //Mostramos el elemento de la lista para la tarifa
let arrFuentes = [baseDatos,tarifa,inforpor,magento];

                //Limpiamos los restos de cualquier busqueda anterior
                rowResultadosBusqueda.style.display = 'none';
                listaResultados.innerHTML = '';
                for (let l of grupoLlama) {
                  l.style.display = 'none';
                }
                for (let a of arrFuentes){
                  a.style.display = 'none';
                  a.getElementsByClassName('fa-check')[0].style.display = 'none';
                  a.getElementsByClassName('fa-times')[0].style.display = 'none';
                }
              /*  baseDatos.style.display = 'none';
                baseDatos.getElementsByClassName('fa-check')[0].style.display = 'none';
                baseDatos.getElementsByClassName('fa-times')[0].style.display = 'none';
                tarifa.style.display = 'none';
                tarifa.getElementsByClassName('fa-check')[0].style.display = 'none';
                tarifa.getElementsByClassName('fa-times')[0].style.display = 'none';*/
                //Mostramos el bloque con la info de búsqueda

                for (let l of grupoLlama) {
                  l.style.display = 'block';
                }

                baseDatos.style.display = 'block';
                datos = {termino: termino};
                let buscaArt = llamadaJson('ajax/unicorn_db/buscar-valor-general.php',datos);             //Buscamos en la base de datos
                let filtrado = buscaArt.filter(linea => linea[2] !== undefined); //Filtramos para dejar solo aquellos con datos de interes (mpn, EAN y nombre);

                if (filtrado.length > 0) {  //Si tenemos resultados los colocamos en el bloque correspondiente
let mapeado = filtrado.map(function(a){ //Mapeamos para estandarizar
  let ar = [a[2],a[3],a[4]]; //mpn,EAN, nombre
  return ar;
});
                  baseDatos.getElementsByClassName('fa-check')[0].style.display = 'block';     //Activamos el check positivo
                  colocaListado(mapeado);  //Colocamos la información

                } else { //Si no tenemos resultados en la base de datos
                  baseDatos.getElementsByClassName('fa-times')[0].style.display = 'block';     //Activamos el check negativo
                  tarifa.style.display = 'block';
                  let encontradoTarifa = [];
                  var hojaMap = new Map(Object.entries(tarifaCompleta)); //Creamos un mapa para poder iterar las hojas
                  hojaMap.forEach((hoja, i) => { //Recorremos el mapa
                    let seccionMap = new Map(Object.entries(hoja)); //Creamos ahora un mapa con las distintas secciones
                    seccionMap.forEach((seccion, i) => {   //Recorremos las secciones
                      let materialMap = new Map(Object.entries(seccion)); //Creamos el mapa del siguiente nivel, los distintos materiales
                      materialMap.forEach((material, i) => {   //los recorremos
                        let articuloMap = new Map(Object.entries(material)); //Creamos el mapa del siguiente nivel, los distintos articulos
                        articuloMap.forEach((articulo, i) => {
                          //    console.log(articulo);
                          if (regex.test(articulo.referencia) === true || regex.test(articulo.descripcion) === true) { //Buscamos en los campos referencia y nombre
                            encontradoTarifa.push(articulo);
                          } else { //Hacemos una busqueda en las referencias del proveedor
                            articulo.compras.forEach((compra, i) => {
                              if (regex.test(compra.ref_proveedor) === true) {
                                encontradoTarifa.push(articulo);
                              }
                            });
                          }
                        });
                      });
                    });
                  });
                  if (encontradoTarifa.length > 0) { //Si tenemos resultados de la tarifa los mostramos
                    let mapeado = encontradoTarifa.map(function(a){ //Mapeamos para estandarizar
                      let ar = [a.referencia,'',a.descripcion];
                      return ar;
                    });
                                      tarifa.getElementsByClassName('fa-check')[0].style.display = 'block';     //Activamos el check positivo
                                      colocaListado(mapeado);  //Colocamos la información
                    console.log(encontradoTarifa);
                  } else { //Si no tenemos resultados vamos al siguiente paso
                    tarifa.getElementsByClassName('fa-times')[0].style.display = 'block';     //Activamos el check negativo
inforpor.style.display = 'block';
datos = {codinfo: termino};
let buscaIfp = llamadaJson('ajax/inforpor/buscar-articulo.php',datos);             //Buscamos en la base de datos
//Siempre devuelve algo, CodError 0 si hay artículo, CodError Producto vacio  si no se encuentra
if (buscaIfp.CodErr === '0') { //Si hemos encontrado artículo
  inforpor.getElementsByClassName('fa-check')[0].style.display = 'block';     //Activamos el check positivo

  let codInfo = buscaIfp.Cod;
  //La búsqueda de inforpor no devuelve el nombre, asi que lo sacamos del listado completo
  let tarifaInforpor = llamadaJson('var/import/inforpor.json',datos);             //Cargamos la tarifa de inforpor
let nombre = '-';
for (i of tarifaInforpor) {
if (codInfo == i.codigo) {
nombre = i.descripcion;

}
}
let mapeado = [[buscaIfp.Referencia,buscaIfp.EAN,nombre]];
  colocaListado(mapeado);  //Colocamos la información
} else { //Si no hay elementos de inforpor
    inforpor.getElementsByClassName('fa-times')[0].style.display = 'block';     //Activamos el check negativo
    magento.style.display = 'block';
    datos = {
      mpn : termino,
      bnombre: termino,
      ean: termino
    }
let tarifaInforpor = llamadaJson('ajax/magento/buscar-articulo.json',datos);             //Cargamos la tarifa de inforpor

}

                  }


                  /*  tarifaCompleta.forEach((item, i) => {
                  console.log(item);
                });*/



              }

            }
            //   console.log(textoBuscar.value);
          }
        });



        </script>
        <div class="row justify-content-center">
          <div class="col-xl-10 col-lg-12 col-md-9">
            <div class="card shadow mb-4">
              <div class="row g-0">
                <div class="col-md-4">
                  <img src="https://cdn.dribbble.com/users/1787323/screenshots/5591006/dribbble_llama_fortnite-02.png" class="img-fluid rounded-start" alt="llama">
                </div>
                <div class="col-md-8">
                  <div class="card-body">
                    <h5 class="card-title">LLama a la llama</h5>
                    <p class="card-text">Haz cosas y pasarán cosas.</p>
                    <p class="card-text"><div class="input-group mb-3">
                      <input type="text" class="form-control" placeholder="Busquemos cosas" aria-label="Busquemos cosas" aria-describedby="button-buscar" id="input-buscar">
                      <button class="btn btn-outline-secondary" type="button" id="button-buscar">Buscar</button>
                    </div></p>
                    <h5 class="card-title grupo-llama" style="display: none">Buscando en:</h5>
                    <ul class="list-group list-group-flush grupo-llama" style="display: none">
                      <li class="list-group-item" id="base-datos" style="display: none">La base de datos <span class="float-end"><i class="fas fa-check fa-2x" style="display: none"></i><i class="fas fa-times fa-2x" style="display: none"></i></span></li>
                      <li class="list-group-item" id="tarifa" style="display: none">La tarifa 2022 <span class="float-end"><i class="fas fa-check fa-2x" style="display: none"></i><i class="fas fa-times fa-2x" style="display: none"></i></span></li>
                      <li class="list-group-item" id="inforpor" style="display: none">Inforpor <span class="float-end"><i class="fas fa-check fa-2x" style="display: none"></i><i class="fas fa-times fa-2x" style="display: none"></i></span></li>
                      <li class="list-group-item" id="magento" style="display: none">Magento <span class="float-end"><i class="fas fa-check fa-2x" style="display: none"></i><i class="fas fa-times fa-2x" style="display: none"></i></span></li>
                      <li class="list-group-item" id="pc-componentes" style="display: none">Pc componentes <span class="float-end"><i class="fas fa-check fa-2x" style="display: none"></i><i class="fas fa-times fa-2x" style="display: none"></i></span></li>
                      <li class="list-group-item" id="bares" style="display: none">Hospitales, balnearios y discotecas <span class="float-end"><i class="fas fa-check fa-2x" style="display: none"></i><i class="fas fa-times fa-2x" style="display: none"></i></span></li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="row justify-content-center" style="display: none" id="row-resultados-busqueda">
          <div class="col-xl-10 col-lg-12 col-md-9">
            <div class="card shadow mb-4 p-3">
              <div class="row">
                <h5>Resultados de la LLAMAda</h5>
                <p>Hemos encontrado varias cosas que podrían coincidir con lo que buscas, así que haz click para saber más</p>
                <ul class="list-group list-group-flush" id="resultados-busqueda">
                </ul>
              </div>
            </div>
          </div>
        </div>


        <div class="row justify-content-center" style="display: none" id="row-informacion-articulo">
          <div class="col-xl-10 col-lg-12 col-md-9">
            <div class="card shadow mb-4 p-3">
              <div class="row">
                <h5>Informasión sabrosa</h5>
                <p>Hemos encontrado varias cosas que podrían coincidir con lo que buscas, así que haz click para saber más</p>
                <ul class="list-group list-group-flush">
                </ul>
              </div>
            </div>
          </div>
        </div>
      <?php endif; //Fin de la comprobación la variable GET?>

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

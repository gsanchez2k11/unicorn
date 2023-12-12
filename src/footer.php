<?php
$frases_copyright = array(
  'Ningún unicornio resultó herido durante el desarrollo de esta aplicación',
  'El icono es un caballo porque el del unicornio era de pago',
  'Si estas leyendo esto me debes una cerveza',
  '¿Cómo están los unicornios, lo primero de todo?'
);
 ?>
<script>
         $(document).ready(function(){
//Vamos a realizar comprobaciones para las alertas
//Comprobamos si tenemos pedidos de pc componentes
/*let pedsPcc;
let numAlertas = 0;
let contadorPeds = {};
let listaPlataformas = ['pcc','mediamarkt','miravia','mage'];


//Comprobamos Pc Componentes
if (typeof plataforma !== 'undefined' && plataforma == 'pcc' && typeof listapds !== 'undefined') {
  console.log('tenemos pedidos de pc componenes');
  pedsPcc = listapds;
} else {
var ultimosPedidos = dameListadoJson('ultimos pedidos pcc');                 //Recuperamos los úlitmos 100 pedidos
pedsPcc = ultimosPedidos;
}
let pedsPendientesPcc = pedsPcc.filter(ped => ped.estado == 'WAITING_ACCEPTANCE'); //Nos quedamos con los pendientes de aceptar
  contadorPeds['Pc componentes'] = pedsPendientesPcc.length;                               //Añadimos el número de pedidos
if (pedsPendientesPcc.length > 0) {
numAlertas++;
}*/

//Comprobamos Phone House
/*if (typeof plataforma !== 'undefined' && plataforma == 'phh' && typeof listapds !== 'undefined') {
  console.log('tenemos pedidos de phone House');
  pedsPhh = listapds;
} else {
var ultimosPedidos = dameListadoJson('ultimos pedidos Phone House');                 //Recuperamos los úlitmos 100 pedidos
pedsPhh = ultimosPedidos;
}

let pedsPendientesPhh = pedsPhh.filter(ped => ped.estado == 'WAITING_ACCEPTANCE'); //Nos quedamos con los pendientes de aceptar
contadorPeds['Phone House'] = pedsPendientesPhh.length;                               //Añadimos el número de pedidos
if (pedsPendientesPhh.length > 0) {
numAlertas++;
}*/
//console.log(contadorPeds);
/*if (numAlertas > 0) {                                                           //Comprobamos si hay alertas
  $('#num-alertas').html(numAlertas).show();
  $('#alerts-dropdown a').remove();                                             //Eliminamos todos los enlaces (solo debe haber uno avisando de que no hay alertas)
//Nos quedamos con las propiedas con valor mayor que 0
let aMostrar = Object.entries(contadorPeds).filter(nped => nped[1] > 0);
for (i in aMostrar) {
  if (aMostrar[i][1] > 0) {
    $('#alerts-dropdown').append('<a class="dropdown-item d-flex align-items-center" href="#"><div class="mr-3"><div class="icon-circle bg-primary"><i class="fas fa-file-alt text-white"></i></div></div><div><span class="font-weight-bold">Hay '+aMostrar[i][1]+' pedidos para aceptar en '+aMostrar[i][0]+'</span></div></a>');

  }
}
}*/


         });
</script>
<footer class="sticky-footer bg-white">
    <div class="container my-auto">
        <div class="copyright text-center my-auto">
            <span>Copyright &copy; Uni Corn productions 2021. <?= $frases_copyright[array_rand($frases_copyright)]  ?>.</span>
        </div>
    </div>
</footer>

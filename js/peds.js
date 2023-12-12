/**Equivalente a document ready pero sin Jquery */
document.addEventListener("DOMContentLoaded", function(event) { 
///////// EVENTOS ///////////////////////////    
    btnBuscar.addEventListener('click', clickBuscar);//Capturamos el click en el btn buscar
    inputBuscar.addEventListener("keyup", function(e) {
        if (e.key === 'Enter') {
          clickBuscar();
        }
      }); //Llamamos a la funcion clickBuscar
})

////////// FUNCIONES //////////////////
function clickBuscar(){
    let termino = inputBuscar.value;
    if (termino.length < 3) {                                                       //Comprobamos si al menos hemos introducido 3 caracteres
      alert('por favor, introduce 3 o más caracteres para buscar'); //mostramos un mensaje si no
    } else {
      let regex = new RegExp(termino,'i'); //configuramos el patron, incluyendo la bandera i para busqueda sin distinguir mayúsculas
      //DEfinimos las variables
  /*    let grupoLlama = document.getElementsByClassName('grupo-llama');

      let baseDatos = document.getElementById('base-datos'); //Mostramos el elemento de la lista para la base de datos
      let tarifa = document.getElementById('tarifa'); //Mostramos el elemento de la lista para la tarifa
      let inforpor = document.getElementById('inforpor'); //Mostramos el elemento de la lista para la tarifa
      let magento = document.getElementById('magento'); //Mostramos el elemento de la lista para la tarifa
let arrFuentes = [baseDatos,tarifa,inforpor,magento];*/

      //Limpiamos los restos de cualquier busqueda anterior
   /*   rowResultadosBusqueda.style.display = 'none';
      listaResultados.innerHTML = '';
      for (let l of grupoLlama) {
        l.style.display = 'none';
      }
      for (let a of arrFuentes){
        a.style.display = 'none';
        a.getElementsByClassName('fa-check')[0].style.display = 'none';
        a.getElementsByClassName('fa-times')[0].style.display = 'none';
      }

      //Mostramos el bloque con la info de búsqueda

      for (let l of grupoLlama) {
        l.style.display = 'block';
      }*/


//Intentamos adivinar que tipo de pedido es para evitar busquedas innecesarias
let c = [];
let patronMage246 = /^TP0{4}[01]\d{4}$/;
let pareceMage246 = patronMage246.test(termino);
if(pareceMage246 === true) c.push('mage245');
let patronMage = /^(TSO|TSU|FUT)0{4}[01]\d{4}$/;
let pareceMage = patronMage.test(termino);
if(pareceMage === true) c.push('mage');
let patronOdoo = /^(SO)?2\d{4}$/;
let pareceOdoo = patronOdoo.test(termino);
datos = {termino: termino}; //este es el objeto que vamos a pasar en todo caso para hacer la busqueda
if(pareceOdoo === true) c.push('odoo');
//Si hay una sola coincidencia buscamos solo en esa plataforma
if (c.length === 1) {
let plataforma = c[0];
let buscaMagento;
switch (plataforma) {
  case 'mage':
    datos.idTienda = 1;
    pedidos = llamadaJson('ajax/magento/buscar-pedido.php',datos);             //Buscamos en la base de datos
    pedidos.plataforma = 'magento 2.2';
    break;
    case 'mage245':
      datos.idTienda = 2;
      pedidos = llamadaJson('ajax/magento/buscar-pedido.php',datos);             //Buscamos en la base de datos
      pedidos.plataforma = 'magento 2.4.6';
    break;
    case 'odoo':
      //Si no lleva delante el "SO" se lo ponemos
     let nombre = /^\d{5}$/.test(termino) ? 'SO'+termino : termino; 
    let bOdoo = {
      modelo: 'sale.order',
      campo: 'name',
      valor: nombre
    }
    pedidos = llamadaJson('ajax/odoo/busqueda.php',bOdoo);             //Buscamos en la base de datos
    pedidos.plataforma = 'Odoo';
    break;
}  
//console.log('pedidos',pedidos);
colocaDatosPedido(pedidos[0],pedidos.plataforma);
} else { //Salvo que tengamos claro de que plataforma es tenemos que buscar en todas

}


//Busquedas
//datos = {termino: termino};
//Magento
//



      baseDatos.style.display = 'block';
      
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

function colocaDatosPedido(pedido,plataforma){
document.getElementById('plataformaPedido').innerHTML = plataforma;   //Ponemos la plataforma
document.getElementById('refPedido').innerHTML = pedido.id;
document.getElementById('fechaPedido').innerHTML = pedido.fecha_creado;
document.getElementById('estadoPedido').innerHTML = pedido.estado;
console.log('pedido', pedido);
console.log('plataforma', plataforma);
}
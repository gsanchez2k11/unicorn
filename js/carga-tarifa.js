let loaderTarifa = document.getElementById('loader-tarifa'); //Escondemos el loader de la tarifa
loaderTarifa.style.display = 'block';
let datos = {
  campo: 'id',
  valor: idTarifa,
  modelo: 'product.pricelist'
}
let tarifa = llamadaJson('../ajax/odoo/busqueda.php', datos);
let itemTarifa = tarifa[0].item_ids;
let miTarifa = [];
for (i of itemTarifa) {
datos = {
campo: 'id',
valor: i,
modelo: 'product.pricelist.item'
}
let infoItem = llamadaJson('../ajax/odoo/busqueda.php', datos);
let item = infoItem[0];
if (item.product_tmpl_id !== false) {
//  console.log(item);
let miArray = item.product_tmpl_id[1].split(" ");
let ref = miArray[0].trim();
let art = [ref.substr(1,ref.length-2),item.name, item.percent_price, item.product_tmpl_id[0]];
miTarifa.push(art);
}



//Quitamos esta parte para no demorar más la carga de datos
//Nos quedamos con los campos que nos interesan
/*let porcientoDescuento = item.percent_price;
if (item.product_tmpl_id !== false) {


//Buscamos ahora el product template
datos.valor = item.product_tmpl_id[0];
datos.modelo = 'product.template';
//console.log(datos);
let infoTemplate = llamadaJson('../ajax/odoo/busqueda.php', datos);
//console.log(infoTemplate);
//console.log('---------------------------');
if (infoTemplate.length > 0) { //De esta manera evitamos buscar artículos archivados
let template = infoTemplate[0];

let idProducto = template.product_variant_id[0];
let mpn = template.default_code;
let nombre = template.name;
let precio = template.list_price;
let stock = template.qty_available;
let articulo = {
idProducto : idProducto,
mpn : mpn,
nombre : nombre,
precio : precio,
porcientoDescuento : porcientoDescuento,
stock: stock
};
miTarifa.push(articulo);
}
}*/
/*console.log(infoItem);
console.log(infoTemplate);*/
}
$('#contenido-carrito').DataTable({
'searching': false,
"paging": false,
 "info": false
});

$('#dataTable').DataTable({
'data': miTarifa,
'columns': [
            { 'title': "mpn"},
            { 'title': "nombre"},
            { 'title': "dto",
          'render': function(data) {
            return data + '%';
          }
        },
            { 'title': "id_plantilla",
          'visible': false},
        {  'data' : null,
        //  'defaultContent': '<div class="input-group mb-3"><input type="number" class="form-control" placeholder="qty" aria-label="cantidad" aria-describedby="button-addon2"><button class="btn btn-outline-secondary al-carrito" type="button">add</button></div>'
        'render' : function (data, type, row, meta){
          let obj = {
            ref : row[0],
            nombre : row[1],
            descuento : row[2],
            idPlantilla : row[3]
          }
//console.log(row);
          //return row;
        //let arr = row.split(',');
          return '<div class="input-group mb-3"><input type="number" class="form-control" placeholder="qty" aria-label="cantidad" aria-describedby="button-addon2" value="1"><button class="btn btn-outline-secondary al-carrito" data-art=\''+JSON.stringify(obj).replace(/[\/\(\)\']/g, "&apos;")+'\' type="button"><i class="fas fa-shopping-cart"></i></button></div>';
        }
       }
     ],
     "language" : {
       'url': 'https://cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json',
       "zeroRecords":    "No hay registros que coincidan. Pulsa enter para hacer una búsqueda general"

     }
});
  $('#loader-tarifa').fadeOut("slow"); //Ocultamos el loader
  let tablaTarifa = document.getElementById('tabla-tarifa');
  tablaTarifa.style.display = 'flex';

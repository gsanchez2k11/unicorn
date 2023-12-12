
/**
 * https://github.com/chniter/bstreeview
 * @returns 
 */
function dameArbol(){
    let padre = 1; //Categoría padre
    var arbol = [];
    let datos = {
        modelo : 'product.category'
      }
    let listaCategorias = llamadaJson('ajax/odoo/listar.php',datos);  //Recuperamos el listado de categorias
     //    console.log(listaCategorias);

    //Filtramos el primer nivel de categorias
    let nivel1 = listaCategorias.filter(cat => cat.parent_id[0] === 1);   //Nos quedamos con el primer nivel
    let nivel2;
    let nivel3;
    let oNivel1;
    nivel1.forEach(item => {
    nivel2 = listaCategorias.filter(cat => cat.parent_id[0] === item.id); //Buscamos las categorias hijas
oNivel1 = {
    text: item.name + '(' + item.product_count + ')',
    icon: "fa fa-folder" ,
    class: "cat-odoo"
} 
if (nivel2.length > 0) {
    oNivel1.nodes = [];
    nivel2.forEach(item => {
    //    nivel3 = listaCategorias.filter(cat => cat.parent_id[0] === item.id); //Buscamos las categorias hijas //Dejamos preparado por si hubiera más niveles

        oNivel1.nodes.push({
            id: 'cat-odoo-'+ item.id,
            text: item.name + '(' + item.product_count + ')',
            icon: "fa fa-folder" ,
            class: "cat-odoo"
        });    
    });
} else {
    oNivel1.id = 'cat-odoo-' + item.id;
}


    console.log(nivel3);
arbol.push(oNivel1);
    })  
  //  console.log(nivel1);
    
    return arbol;
}

function dameArticulos(idCategoria){
    return JSON.parse(        
        $.ajax({
        type: "POST",
        url: "ajax/odoo/busqueda.php",
                       data: {
                        modelo: 'product.product',
                        campo: 'categ_id',
                        valor: idCategoria
                       },
        dataType: 'json',
        global: false,
        async: false,
        beforeSend: function(data, textStatus, jqXHR) {
            document.getElementById('loader-buscando-articulos').classList.remove('visually-hidden');
        },
        success: function(data, textStatus, jqXHR) {
          //  console.log('cliente: ' + resp);
          //    $('.clientecrm').append(resp);
          document.getElementById('loader-buscando-articulos').classList.add('visually-hidden');

          return data;
        },
        error: function(data, textStatus, jqXHR) {
          alert('Error: ' + JSON.stringify(data));
          //    $(bloque).find('.dimmer').toggleClass('active');
        }
      }).responseText);
}

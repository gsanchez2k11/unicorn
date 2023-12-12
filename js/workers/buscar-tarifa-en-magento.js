//let tablas = document.querySelectorAll('.tabla-medidas');
//console.log(tablas);






var i = 0;

function timedCount() {
  i = i + 1;
  postMessage(i);
  setTimeout("timedCount()",500);
}

//timedCount();

//let catalogo = pideTodosArticulos();
//console.log('catalogo');
//console.log(catalogo);


function dameCatalogoMage() {
 let cat = [];
 let p = 1;   
let pag;
pag =  pidePaginaArticulos(p);
console.log('pag');
//console.log(typeof pag);
console.log(pag);


/*do {
    pag =  pidePaginaArticulos(p);

    for (const articulo of Object.values(pag)) {
        console.log(articulo);
       cat.push(articulo); 
    }
    p++;
} while (Object.values(pag).length == 100);*/
 return cat;
}






function pidePaginaArticulos(pagina){
    var articulos = []; //Array con todos los artículos de Magento
    //https://developer.mozilla.org/en-US/docs/Web/API/FormData
    var data = new FormData(); //Pasamos los parámetros
    data.append('p',pagina);
    data.append('status',1);

    //https://developer.mozilla.org/en-US/docs/Web/API/XMLHttpRequest
let req = new XMLHttpRequest();
let url = '../../ajax/magento/dame-todos-articulos.php';

req.open('POST',url,true);
req.send(data);
req.responseType = "json";
req.onreadystatechange = function() {
    if (req.readyState == 4 && req.status == 200) {
     //   alert("Form submitted successfully");
      //  document.getElementById("frmdata").reset();
       // console.log(req.responseText);
        //console.log(req.responseType);
    //    console.log(req.response);
     //   for (const a of req.response) { //Recorremos la respuesta y añadimos los artículos al array
    // console.log('req.response');
     //console.log(req.response);
     for (const a of req.response) {
        articulos.push(a);
     }
     console.log('articulos');
     console.log(articulos);
     return articulos;
      //  }
        
    }
}

}



// Ejemplo implementando el metodo POST:
async function postData(url = '', data = {}) {
    // Opciones por defecto estan marcadas con un *
    const response = await fetch(url, {
      method: 'POST', // *GET, POST, PUT, DELETE, etc.
      mode: 'cors', // no-cors, *cors, same-origin
      cache: 'no-cache', // *default, no-cache, reload, force-cache, only-if-cached
      credentials: 'same-origin', // include, *same-origin, omit
      headers: {
        'Content-Type': 'application/json'
        // 'Content-Type': 'application/x-www-form-urlencoded',
      },
      redirect: 'follow', // manual, *follow, error
      referrerPolicy: 'no-referrer', // no-referrer, *no-referrer-when-downgrade, origin, origin-when-cross-origin, same-origin, strict-origin, strict-origin-when-cross-origin, unsafe-url
      body: JSON.stringify(data) // body data type must match "Content-Type" header
    });
    return response.json(); // parses JSON response into native JavaScript objects
  }
  let url = '../../ajax/magento/buscar-articulo.php';


  postData(url, { p: 41, status: 1 })
    .then(data => {
      console.log(data); // JSON data parsed by `data.json()` call
    });

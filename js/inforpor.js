function generaDatosmodalInforpor(pedido){
    let d = {
        NumPedInf: pedido.id,
    }
    let estePedido = llamadaJson('./ajax/inforpor/buscar-pedido.php',d);
   // console.log('estePedido');
//console.log(estePedido);
    let cFactura = {
        email: 'info@futura.es',
        telefono: '968902300',
        nif: 'B30507743',
        direccion: 'Avenida Alto de las Atalayas, 18',
        ciudad: 'Cabezo de Torres',
        provincia: 'Murcia',
        codigo_postal: '30110',
        empresa: 'Futura Teck de Murcia S.L.U.',
        nombre_completo: 'Futura Teck de Murcia S.L.U.'
      }
let  cEnvio = {
    email: '',
    telefono: '',
    nif: '',
    direccion: estePedido.DirEnvio,
    ciudad: '',
    provincia: '',
    codigo_postal: '',
    empresa: '',
    nombre_completo: ''
  }
let lineas_pedido = [];
estePedido.lineas_pedido.forEach(element => {
  if (element.notas !== '0') { //Las lineas procedentes de custodia vienen con nota = 0
    let consultaProd = llamadaJson('./ajax/inforpor/consulta-prod.php',{codinfo: element.codinf});
 //   console.log(consultaProd);
    let l = {
        mpn: consultaProd.Referencia,
        nombre: element.notas,
        cantidad: element.cant,
        importe: element.precio
      };
    lineas_pedido.push(l);
  }

});


    let cliente = {
        direccion_factura: cFactura,
        direccion_envio : cEnvio,
        lineas_pedido: lineas_pedido,
        nif: cFactura.nif,
        id: pedido.id,
        numpedCli: pedido.numpedCli,
        portes: estePedido.portes.replace(',','.')
      }
   //   console.log('cliente');
    //  console.log(cliente);
      return cliente;
}
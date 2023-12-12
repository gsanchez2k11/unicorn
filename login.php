<?php
//Configuramos las opciones de errores
error_reporting(E_ALL);
ini_set('display_errors', 1);
//Iniciamos la sesion
session_start();
require_once 'config.php.inc';              //Incluimos el archivo de configuración
require_once RAIZ . '/clases/funciones/unicorn_db/Usuarios.php';


if ($_GET) {
    //Comprobamos si el formulario pasa los datos por el método GET
    //Asigamos los datos recibidos a variables
    $usuario  = $_GET['usuario'];
    $password = $_GET['password'];

echo '<br />' . $usuario . '<br />';
echo md5($password);


//Buscamos los datos introducidos en la tabla admin de la BD
    $consulta_usuario = unicorn\clases\funciones\unicorn_db\Usuarios::consultaUsuarios($usuario, $password);

    if ($consulta_usuario == true) {
        //Si existe una cuenta con esos datos
        //Guardamos en la sesion la variable usuario

        $_SESSION['usuario_logeado'] = $usuario;
        header("location: index.php");
    } else {
        $titulo_error = 'No puedes acceder a la app';
        $contenido_error = 'Es posible que estés utizando un nombre de usuario que no existe, o un password erróneo. <br>También es posible que se haya deshabilitado esta cuenta de usuario. Si piensas que es el caso, ponte en contacto con el administrador para que te proporcione unos datos de acceso nuevos';
    }
}
 ?>
 <!DOCTYPE html>
 <html lang="en">

 <head>

     <meta charset="utf-8">
     <meta http-equiv="X-UA-Compatible" content="IE=edge">
     <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
     <meta name="description" content="">
     <meta name="author" content="">

     <title>Bienvenido a unicorn</title>
     <script src="vendor/jquery/jquery.min.js"></script>
     <!-- Custom fonts for this template-->
     <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
     <link
         href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
         rel="stylesheet">

     <!-- Custom styles for this template-->
     <link href="css/sb-admin-2.min.css" rel="stylesheet">
<script>
$(document)
  .ready(function() {
    // Fetch all the forms we want to apply custom Bootstrap validation styles to
      var forms = $('.needs-validation');
      Array.prototype.slice.call(forms)
  .forEach(function (form) {
$(form).on('click','a',event => {
  if (!form.checkValidity()) {
  event.preventDefault()
  event.stopPropagation()
}

form.classList.add('was-validated');
//console.log(form);
// recuperamos el querystring
const querystring = window.location.search;
console.log(querystring)
// usando el querystring, creamos un objeto del tipo URLSearchParams
const params = new URLSearchParams(querystring);
let usuario = $(form).find('#exampleInputEmail').val();
let password = $(form).find('#exampleInputPassword').val();
params.set("usuario", usuario);
params.set("password", password);
console.log(params.toString()); // "q=URLUtils.searchParams"
// reemplazamos el historial del navegador con esta nueva querystring
window.history.replaceState({}, '', `${location.pathname}?${params}`);
//Volvemos a cargar el documento
location.reload();

});
  });
  });
</script>
 </head>

 <body class="bg-gradient-info">

     <div class="container">

         <!-- Outer Row -->
         <div class="row justify-content-center">

             <div class="col-xl-10 col-lg-12 col-md-9">

                 <div class="card o-hidden border-0 shadow-lg my-5">
                     <div class="card-body p-0">
                         <!-- Nested Row within Card Body -->
                         <div class="row">
                             <div class="col-lg-6 d-none d-lg-block bg-login-image"></div>
                             <div class="col-lg-6">
                                 <div class="p-5">
                                     <div class="text-center">
                                         <h1 class="h4 text-gray-900 mb-4">¡Bienvenido!</h1>
                                     </div>
                                     <form class="user needs-validation">
                                         <div class="form-group">
                                             <input type="email" class="form-control form-control-user"
                                                 id="exampleInputEmail" aria-describedby="emailHelp"
                                                 placeholder="Enter Email Address..." required>
                                         </div>
                                         <div class="form-group">
                                             <input type="password" class="form-control form-control-user"
                                                 id="exampleInputPassword" placeholder="Password" required>
                                         </div>
                                         <div class="form-group">
                                             <div class="custom-control custom-checkbox small">
                                                 <input type="checkbox" class="custom-control-input" id="customCheck">
                                                 <label class="custom-control-label" for="customCheck">Recuerdame</label>
                                             </div>
                                         </div>
                                         <a  class="btn btn-primary btn-user btn-block">
                                             Login
                                         </a>
                                         <!--<hr>
                                         <a href="index.html" class="btn btn-google btn-user btn-block">
                                             <i class="fab fa-google fa-fw"></i> Login with Google
                                         </a>
                                         <a href="index.html" class="btn btn-facebook btn-user btn-block">
                                             <i class="fab fa-facebook-f fa-fw"></i> Login with Facebook
                                         </a>-->
                                     </form>
                                     <hr>
                                     <div class="text-center">
                                         <a class="small" href="forgot-password.html">He olvidado mi contraseña</a>
                                     </div>
                                     <!--<div class="text-center">
                                         <a class="small" href="register.html">Create an Account!</a>
                                     </div>-->
                                 </div>
                             </div>
                         </div>
                     </div>
                 </div>

             </div>

         </div>

     </div>

     <!-- Bootstrap core JavaScript-->
     <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>

     <!--<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>-->

     <!-- Core plugin JavaScript-->
     <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

     <!-- Custom scripts for all pages-->
     <script src="js/sb-admin-2.min.js"></script>

 </body>

 </html>

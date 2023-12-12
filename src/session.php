<?php
use unicorn\clases\funciones\unicorn_db;
require_once RAIZ . '/clases/funciones/unicorn_db/Usuarios.php';

session_start();
$comprobar_usuario = $_SESSION['usuario_logeado'];
$consulta_usuario = unicorn\clases\funciones\unicorn_db\Usuarios::compruebaUsuario($comprobar_usuario);
$nombre_usuario = $consulta_usuario['nombre_usuario'];
$grupo_usuario = $consulta_usuario['grupo_usuario'];
$nivel_acceso = $consulta_usuario['nivel_acceso'];
$avatar_usuario = $consulta_usuario['avatar'];
$id_odoo = isset($consulta_usuario['id_odoo']) ? $consulta_usuario['id_odoo'] : 0;
if (!isset($_SESSION['usuario_logeado'])) {
    header("location:login.php");
}

 ?>

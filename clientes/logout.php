<?php
session_start();
unset($_SESSION['usuario_logeado']);
header("Location:login.php");
?>

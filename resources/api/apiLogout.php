<?php
session_start();

// Eliminar todas las variables de la sesión
$_SESSION = array();

// Destruir la sesion
if(ini_get("session.use_cookies")){
    setcookie(session_name(), '', time() - 42000, '/');
}

session_destroy();

header("Location: ../../../public/index.php");
exit();
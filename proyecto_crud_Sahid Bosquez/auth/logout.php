<?php
// GESTIÓN DE SESIÓN ------ Inicia el entorno de sesión para poder manipularla
session_start();

// GESTIÓN DE SESIÓN ------ Libera y limpia todas las variables almacenadas en el servidor
session_unset();    

// GESTIÓN DE SESIÓN ------ Destruye la información de la sesión de forma definitiva
session_destroy();  

// CONTROL DE REDIRECCIÓN ------ Envía al usuario a la pantalla de acceso
header("Location: login.php");

// CONTROL DE FLUJO ------ Detiene la ejecución para asegurar que se procese la redirección
exit();
?>
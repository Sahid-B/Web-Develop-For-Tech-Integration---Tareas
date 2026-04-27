<?php
// GESTIÓN DE SESIÓN ------ Inicia o reanuda la sesión existente en el servidor
session_start();

// VERIFICACIÓN DE ESTADO ------ Comprueba si la variable de sesión 'empleado_id' no está definida
// Esto detecta si un usuario intenta entrar a una página interna sin haberse logueado
if (!isset($_SESSION['empleado_id'])) {
    
    // CONTROL DE ACCESO ------ Bloquea la entrada y redirige al formulario de login
    header("Location: " . BASE_URL . "auth/login.php");
    
    // CONTROL DE FLUJO ------ Detiene la carga del resto de la página por seguridad
    exit();
}
?>
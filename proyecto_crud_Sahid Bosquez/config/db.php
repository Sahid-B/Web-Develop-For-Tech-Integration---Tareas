<?php
// CONFIGURACIÓN ------ Definición de la ruta raíz del proyecto para enlaces dinámicos
define('BASE_URL', '/proyecto_crud_Sahid Bosquez/');

/**
 * Función para centralizar la conexión a la base de datos (Pool persistente)
 */
function obtenerConexion() {
    // CONFIGURACIÓN ------ Parámetros de acceso al servidor MySQL de XAMPP
    // El prefijo "p:" establece una conexión persistente para optimizar recursos
    $host = "p:localhost";
    $usuario = "root";
    $password = "";
    $base_datos = "classic_models";
    $puerto = 3306;

    // EJECUCIÓN ------ Intento de apertura de conexión con sintaxis procedimental
    $conexion = mysqli_connect($host, $usuario, $password, $base_datos, $puerto);

    // CONTROL DE ERRORES ------ Verificación del estado de la conexión
    // Si la conexión falla, se detiene el script y se reporta el motivo técnico
    if (!$conexion) {
        die("Error de conexión: " . mysqli_connect_error());
    }

    // CONFIGURACIÓN ------ Definición del juego de caracteres para soporte de tildes y eñes
    mysqli_set_charset($conexion, "utf8mb4");

    // Retorno del recurso de conexión para ser utilizado en los archivos CRUD
    return $conexion;
}
?>
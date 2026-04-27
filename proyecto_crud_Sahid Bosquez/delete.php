<?php
require_once 'includes/auth.php';
require_once 'config/db.php';
$conexion = obtenerConexion();

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    
    // Convertimos de nuevo a string escapado por seguridad al hacer la consulta (previniendo inyeccion SQL)
    $id_seguro = mysqli_real_escape_string($conexion, $id);
    
    
    // 1. Eliminar los detalles de las ordenes vinculadas a este cliente
    $query_detalles = "DELETE FROM orderdetails WHERE orderNumber IN (SELECT orderNumber FROM orders WHERE customerNumber = $id_seguro)";
    mysqli_query($conexion, $query_detalles);
    
    // 2. Eliminar las ordenes
    $query_ordenes = "DELETE FROM orders WHERE customerNumber = $id_seguro";
    mysqli_query($conexion, $query_ordenes);
    
    // 3. Eliminar los pagos
    $query_pagos = "DELETE FROM payments WHERE customerNumber = $id_seguro";
    mysqli_query($conexion, $query_pagos);
    
    // 4. Finalmente, ejecutar el DELETE del cliente principal
    $query_cliente = "DELETE FROM customers WHERE customerNumber = $id_seguro";
    
    if (mysqli_query($conexion, $query_cliente)) {
        // Redirect to the home page with success indicator
        header("Location: index.php?message=deleted");
        exit();
    } else {
        $error = mysqli_error($conexion);
        echo "<!DOCTYPE html><html><head><meta charset='UTF-8'><link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet'></head><body class='bg-light mt-5'><div class='container'><div class='alert alert-danger'><h4>Delete Error</h4><p>$error</p><a href='index.php' class='btn btn-outline-danger mt-3'>Return to Home</a></div></div></body></html>";
    }
} else {
    // If no ID is provided in the URL, return to the home page
    header("Location: index.php");
    exit();
}

// Obligatorio: Cerrar la conexion
mysqli_close($conexion);
?>


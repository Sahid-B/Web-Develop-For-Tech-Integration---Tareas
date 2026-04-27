<?php
// CONTROL DE ACCESO ------ Verifica que el usuario esté autenticado antes de cargar la página
require_once '../includes/auth.php';

// CONFIGURACIÓN ------ Carga la conexión centralizada a la base de datos
require_once '../config/db.php';
$conexion = obtenerConexion();

$mensaje = '';
$tipo_mensaje = '';

// PROCESAMIENTO ------ Detecta el envío del formulario mediante el método POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // SEGURIDAD ------ Limpieza de datos con mysqli_real_escape_string para prevenir Inyección SQL
    // Se procesan todos los campos recibidos del formulario antes de usarlos en la consulta
    $customerName = mysqli_real_escape_string($conexion, $_POST['customerName']);
    $contactFirstName = mysqli_real_escape_string($conexion, $_POST['contactFirstName']);
    $contactLastName = mysqli_real_escape_string($conexion, $_POST['contactLastName']);
    $phone = mysqli_real_escape_string($conexion, $_POST['phone']);
    $city = mysqli_real_escape_string($conexion, $_POST['city']);
    $country = mysqli_real_escape_string($conexion, $_POST['country']);
    $addressLine1 = mysqli_real_escape_string($conexion, $_POST['addressLine1']);
    
    // LÓGICA DE NEGOCIO ------ Cálculo manual del ID del cliente
    // Dado que 'customerNumber' en la tabla classicmodels no es autoincremental, se busca el valor máximo actual
    $query_max = "SELECT MAX(customerNumber) as max_id FROM customers";
    $res_max = mysqli_query($conexion, $query_max);
    $fila_max = mysqli_fetch_assoc($res_max);
    
    // Si no existen registros, se inicia en 100; de lo contrario, se suma 1 al máximo encontrado
    $nuevo_id = $fila_max['max_id'] ? $fila_max['max_id'] + 1 : 100;

    // EJECUCIÓN ------ Definición de la sentencia SQL para la inserción (CREATE)
    $query = "INSERT INTO customers (customerNumber, customerName, contactLastName, contactFirstName, phone, addressLine1, city, country) 
              VALUES ($nuevo_id, '$customerName', '$contactLastName', '$contactFirstName', '$phone', '$addressLine1', '$city', '$country')";

    // EJECUCIÓN ------ Intento de inserción en la base de datos
    if (mysqli_query($conexion, $query)) {
        // CONTROL DE ÉXITO ------ Notificación de registro guardado correctamente
        $mensaje = "Cliente agregado exitosamente. (ID Asignado: $nuevo_id)";
        $tipo_mensaje = "success";
    } else {
        // CONTROL DE ERRORES ------ Captura y despliegue del error técnico de MySQL
        $mensaje = "Error al agregar el cliente: " . mysqli_error($conexion);
        $tipo_mensaje = "danger";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Cliente - CRM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>css/styles.css">
</head>
<body class="bg-light">
    <?php include '../includes/navbar.php'; ?>

    <div class="container pb-5">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="page-title mb-0"><i class="bi bi-person-plus text-primary"></i> Registrar Nuevo Cliente</h2>
                    <a href="customers.php" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Volver a Clientes</a>
                </div>

                <?php if ($mensaje): ?>
                    <div class="alert alert-<?php echo $tipo_mensaje; ?> alert-dismissible fade show shadow-sm" role="alert">
                        <?php echo $tipo_mensaje == 'success' ? '<i class="bi bi-check-circle-fill"></i>' : '<i class="bi bi-exclamation-triangle-fill"></i>'; ?>
                        <?php echo $mensaje; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                    </div>
                <?php endif; ?>

                <div class="card shadow-sm border-0">
                    <div class="card-body p-4 p-md-5">
                        <form action="create.php" method="POST">
                            <h5 class="text-primary mb-4 border-bottom pb-2">Información de la Empresa</h5>
                            <div class="row mb-3">
                                <div class="col-12">
                                    <label for="customerName" class="form-label text-muted fw-semibold">Nombre del Cliente o Empresa *</label>
                                    <input type="text" class="form-control form-control-lg" id="customerName" name="customerName" required placeholder="Ej: Tech Solutions Corp">
                                </div>
                            </div>
                            
                            <h5 class="text-primary mt-4 mb-4 border-bottom pb-2">Información de Contacto</h5>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="contactFirstName" class="form-label text-muted fw-semibold">Nombre del Contacto *</label>
                                    <input type="text" class="form-control" id="contactFirstName" name="contactFirstName" required placeholder="Ej: Juan">
                                </div>
                                <div class="col-md-6 mt-3 mt-md-0">
                                    <label for="contactLastName" class="form-label text-muted fw-semibold">Apellido del Contacto *</label>
                                    <input type="text" class="form-control" id="contactLastName" name="contactLastName" required placeholder="Ej: Pérez">
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="phone" class="form-label text-muted fw-semibold">Teléfono *</label>
                                    <input type="text" class="form-control" id="phone" name="phone" required placeholder="Ej: +34 900 123 456">
                                </div>
                                <div class="col-md-6 mt-3 mt-md-0">
                                    <label for="addressLine1" class="form-label text-muted fw-semibold">Dirección Principal *</label>
                                    <input type="text" class="form-control" id="addressLine1" name="addressLine1" required placeholder="Ej: Av. Principal 123">
                                </div>
                            </div>
                            
                            <div class="row mb-5">
                                <div class="col-md-6">
                                    <label for="city" class="form-label text-muted fw-semibold">Ciudad *</label>
                                    <input type="text" class="form-control" id="city" name="city" required placeholder="Ej: Madrid">
                                </div>
                                <div class="col-md-6 mt-3 mt-md-0">
                                    <label for="country" class="form-label text-muted fw-semibold">País *</label>
                                    <input type="text" class="form-control" id="country" name="country" required placeholder="Ej: España">
                                </div>
                            </div>
                            
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <button type="reset" class="btn btn-light border px-4 me-md-2">Limpiar Campos</button>
                                <button type="submit" class="btn btn-primary px-5 shadow"><i class="bi bi-save me-1"></i> Guardar Registro</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php 
// FINALIZACIÓN ------ Obligatorio: Cerrar la conexión para liberar recursos del servidor
mysqli_close($conexion); 
?>
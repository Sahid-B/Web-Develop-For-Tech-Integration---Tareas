<?php
// CONTROL DE ACCESO ------ Verifica la autenticación del empleado
require_once '../includes/auth.php';

// CONFIGURACIÓN ------ Carga la conexión centralizada a la base de datos
require_once '../config/db.php';
$conexion = obtenerConexion();

$mensaje = '';
$tipo_mensaje = '';

// RECEPCIÓN DE PARÁMETROS ------ Valida que exista un ID válido para editar
if (!isset($_GET['id']) && $_SERVER['REQUEST_METHOD'] != 'POST') {
    header("Location: customers.php");
    exit();
}

// CONTROL DE FLUJO ------ Determina el ID actual desde la URL (GET) o del formulario (POST)
$id = isset($_GET['id']) ? (int)$_GET['id'] : (int)$_POST['customerNumber'];

// PROCESAMIENTO (UPDATE) ------ Detecta el envío del formulario de actualización
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // SEGURIDAD ------ Limpieza de datos recibidos para prevenir Inyección SQL
    $customerName = mysqli_real_escape_string($conexion, $_POST['customerName']);
    $contactFirstName = mysqli_real_escape_string($conexion, $_POST['contactFirstName']);
    $contactLastName = mysqli_real_escape_string($conexion, $_POST['contactLastName']);
    $phone = mysqli_real_escape_string($conexion, $_POST['phone']);
    $addressLine1 = mysqli_real_escape_string($conexion, $_POST['addressLine1']);
    $city = mysqli_real_escape_string($conexion, $_POST['city']);
    $country = mysqli_real_escape_string($conexion, $_POST['country']);

    // EJECUCIÓN ------ Definición de la sentencia SQL para modificar el registro (UPDATE)
    $query = "UPDATE customers SET 
                customerName = '$customerName', 
                contactLastName = '$contactLastName', 
                contactFirstName = '$contactFirstName', 
                phone = '$phone', 
                addressLine1 = '$addressLine1',
                city = '$city', 
                country = '$country'
              WHERE customerNumber = $id";

    // EJECUCIÓN ------ Intento de actualización en la base de datos
    if (mysqli_query($conexion, $query)) {
        // CONTROL DE ÉXITO ------ Mensaje de confirmación tras actualizar
        $mensaje = "Customer data has been updated successfully.";
        $tipo_mensaje = "success";
    } else {
        // CONTROL DE ERRORES ------ Captura del error técnico de MySQL en caso de falla
        $mensaje = "An error occurred while updating: " . mysqli_error($conexion);
        $tipo_mensaje = "danger";
    }
}

// PRE-CARGA DE DATOS ------ Consulta SELECT para obtener los datos actuales del cliente
$query_cliente = "SELECT * FROM customers WHERE customerNumber = $id";
$resultado = mysqli_query($conexion, $query_cliente);

// CONTROL DE EXISTENCIA ------ Verifica que el cliente realmente exista en la base de datos
if (mysqli_num_rows($resultado) == 0) {
    echo "Customer not found.";
    mysqli_close($conexion);
    exit();
}

// RECOLECCIÓN ------ Asignación de los datos a un array para llenar el formulario
$cliente = mysqli_fetch_assoc($resultado);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Customer - CRM</title>
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
                    <h2 class="page-title mb-0">
                        <i class="bi bi-pencil-square text-warning"></i> Edit Customer <span class="text-secondary fs-4">#<?php echo $id; ?></span>
                    </h2>
                    <a href="customers.php" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Back to Customers</a>
                </div>

                <?php if ($mensaje): ?>
                    <div class="alert alert-<?php echo $tipo_mensaje; ?> alert-dismissible fade show shadow-sm" role="alert">
                        <?php echo $tipo_mensaje == 'success' ? '<i class="bi bi-check-circle-fill"></i>' : '<i class="bi bi-exclamation-triangle-fill"></i>'; ?>
                        <?php echo $mensaje; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <div class="card shadow-sm border-0">
                    <div class="card-body p-4 p-md-5">
                        <form action="edit.php?id=<?php echo $id; ?>" method="POST">
                            <input type="hidden" name="customerNumber" value="<?php echo htmlspecialchars($cliente['customerNumber']); ?>">
                            
                            <h5 class="text-warning mb-4 border-bottom pb-2">Business Information</h5>
                            <div class="row mb-3">
                                <div class="col-12">
                                    <label for="customerName" class="form-label text-muted fw-semibold">Company / Customer Name *</label>
                                    <input type="text" class="form-control form-control-lg" id="customerName" name="customerName" required value="<?php echo htmlspecialchars($cliente['customerName']); ?>">
                                </div>
                            </div>
                            
                            <h5 class="text-warning mt-4 mb-4 border-bottom pb-2">Contact Information</h5>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="contactFirstName" class="form-label text-muted fw-semibold">Contact First Name *</label>
                                    <input type="text" class="form-control" id="contactFirstName" name="contactFirstName" required value="<?php echo htmlspecialchars($cliente['contactFirstName']); ?>">
                                </div>
                                <div class="col-md-6 mt-3 mt-md-0">
                                    <label for="contactLastName" class="form-label text-muted fw-semibold">Contact Last Name *</label>
                                    <input type="text" class="form-control" id="contactLastName" name="contactLastName" required value="<?php echo htmlspecialchars($cliente['contactLastName']); ?>">
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="phone" class="form-label text-muted fw-semibold">Phone *</label>
                                    <input type="text" class="form-control" id="phone" name="phone" required value="<?php echo htmlspecialchars($cliente['phone']); ?>">
                                </div>
                                <div class="col-md-6 mt-3 mt-md-0">
                                    <label for="addressLine1" class="form-label text-muted fw-semibold">Address *</label>
                                    <input type="text" class="form-control" id="addressLine1" name="addressLine1" required value="<?php echo htmlspecialchars($cliente['addressLine1']); ?>">
                                </div>
                            </div>
                            
                            <div class="row mb-5">
                                <div class="col-md-6">
                                    <label for="city" class="form-label text-muted fw-semibold">City *</label>
                                    <input type="text" class="form-control" id="city" name="city" required value="<?php echo htmlspecialchars($cliente['city']); ?>">
                                </div>
                                <div class="col-md-6 mt-3 mt-md-0">
                                    <label for="country" class="form-label text-muted fw-semibold">Country *</label>
                                    <input type="text" class="form-control" id="country" name="country" required value="<?php echo htmlspecialchars($cliente['country']); ?>">
                                </div>
                            </div>
                            
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <button type="submit" class="btn btn-warning px-5 shadow text-dark fw-bold">
                                    <i class="bi bi-arrow-repeat me-1"></i> Update Record
                                </button>
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
// FINALIZACIÓN ------ Obligatorio: Cerrar la conexión para liberar recursos
mysqli_close($conexion); 
?>
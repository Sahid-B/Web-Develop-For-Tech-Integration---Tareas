<?php
// Iniciar la sesión para poder guardar los datos del usuario logueado
session_start();

// Verificar si el empleado ya tiene una sesión activa
if (isset($_SESSION['empleado_id'])) {
    // Incluir la configuración de la base de datos
    require_once '../config/db.php';
    // Redirigir directamente al dashboard si ya está logueado
    header("Location: " . BASE_URL . "dashboard/dashboard.php");
    exit();
}

// Requerir el archivo de configuración y conectar a la base de datos
require_once '../config/db.php';
$conexion = obtenerConexion();

// Variable para almacenar mensajes de error
$error = '';

// Verificar si los datos fueron enviados mediante el método POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Uso de mysqli_real_escape_string para limpiar las entradas y evitar inyecciones SQL
    // trim() elimina espacios vacíos al inicio y al final
    $email = mysqli_real_escape_string($conexion, trim($_POST['email']));
    $password = mysqli_real_escape_string($conexion, trim($_POST['password']));

    // Consulta SQL para validar si el correo y el número de empleado (usado como clave) coinciden
    $query = "SELECT * FROM employees WHERE email = '$email' AND employeeNumber = '$password'";
    
    // Ejecutar la consulta en la base de datos usando la función mysqli_query()
    $resultado = mysqli_query($conexion, $query);

    // Intentar obtener una fila de los resultados con mysqli_fetch_assoc()
    if ($empleado = mysqli_fetch_assoc($resultado)) {
        // Credenciales correctas: Se asignan las variables de sesión
        $_SESSION['empleado_id'] = $empleado['employeeNumber'];
        $_SESSION['empleado_nombre'] = $empleado['firstName'] . ' ' . $empleado['lastName'];
        $_SESSION['empleado_puesto'] = $empleado['jobTitle'];
        
        // Redirigir al usuario a la página principal del sistema
        header("Location: " . BASE_URL . "dashboard/dashboard.php");
        exit();
    } else {
        // Si no hay coincidencias, definir mensaje de error
        $error = "Usuario o contraseña incorrectos. Por favor verifique sus credenciales.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - CRM Classic Models</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* Estilos personalizados para centrar el login y mejorar el diseño */
        body { 
            background-color: #f0f2f5; 
            height: 100vh; 
            display: flex; 
            align-items: center; 
            justify-content: center;
            font-family: 'Inter', sans-serif;
        }
        .login-card { 
            max-width: 420px; 
            width: 100%; 
            border-radius: 1rem; 
            box-shadow: 0 10px 25px rgba(0,0,0,0.08); 
            border: none; 
        }
        .login-header { 
            background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
            color: white; 
            border-radius: 1rem 1rem 0 0; 
            padding: 2.5rem 2rem; 
            text-align: center; 
        }
        .form-control:focus {
            box-shadow: none;
            border-color: #0d6efd;
        }
        .input-group-text {
            background-color: white;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <div class="login-card card">
        <div class="login-header">
            <i class="bi bi-database-fill-lock fs-1 mb-2 d-block"></i>
            <h4 class="mb-0 fw-bold">CRM Classic Models</h4>
            <p class="mb-0 mt-1 opacity-75 small">Acceso Corporativo Únicamente</p>
        </div>
        <div class="card-body p-4 p-sm-5 bg-white" style="border-radius: 0 0 1rem 1rem;">
            
            <?php if ($error): ?>
                <div class="alert alert-danger py-2 px-3 text-center small shadow-sm" role="alert">
                    <i class="bi bi-exclamation-triangle-fill"></i> <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <form action="login.php" method="POST">
                <div class="mb-4">
                    <label for="email" class="form-label text-muted fw-semibold small text-uppercase">Correo Corporativo</label>
                    <div class="input-group input-group-lg shadow-sm">
                        <span class="input-group-text border-end-0"><i class="bi bi-envelope"></i></span>
                        <input type="email" class="form-control border-start-0 ps-0" id="email" name="email" required placeholder="ej: dmurphy@classicmodelcars.com">
                    </div>
                </div>
                <div class="mb-4">
                    <label for="password" class="form-label text-muted fw-semibold small text-uppercase">Número de Empleado (Contraseña)</label>
                    <div class="input-group input-group-lg shadow-sm">
                        <span class="input-group-text border-end-0"><i class="bi bi-key"></i></span>
                        <input type="password" class="form-control border-start-0 ps-0" id="password" name="password" required placeholder="ej: 1002">
                    </div>
                </div>
                <button type="submit" class="btn btn-primary w-100 py-3 fw-bold shadow-sm mt-2">
                    <i class="bi bi-box-arrow-in-right me-1"></i> Iniciar Sesión
                </button>
            </form>
            
            <div class="text-center mt-4">
                <hr class="text-muted opacity-25">
                <small class="text-muted">
                    <i class="bi bi-info-circle"></i> Use sus credenciales de la tabla <code>employees</code>.
                </small>
            </div>
        </div>
    </div>
</body>
</html>
<?php 
// IMPORTANTE: Cerrar la conexión a la base de datos al finalizar la carga de la página
mysqli_close($conexion); 
?>
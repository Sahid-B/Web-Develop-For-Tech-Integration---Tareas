<?php
// CONTROL DE ACCESO ------ Verifica que el empleado tenga una sesión activa
require_once '../includes/auth.php';

// CONFIGURACIÓN ------ Carga la conexión centralizada a la base de datos
require_once '../config/db.php';
$conexion = obtenerConexion();

// CONSULTA ------ Sentencia SQL para obtener todos los registros de la tabla 'offices'
$query_oficinas = "SELECT * FROM offices";
$resultado_oficinas = mysqli_query($conexion, $query_oficinas);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sucursales - CRM Classic Models</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>css/styles.css">
</head>
<body class="bg-light">
    <?php include '../includes/navbar.php'; ?>

    <div class="container pb-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="page-title mb-0">
                <i class="bi bi-building text-primary"></i> Oficinas Corporativas
            </h2>
            <span class="badge bg-secondary fs-6">Ubicaciones Globales</span>
        </div>

        <div class="row g-4">
            
            <?php if (mysqli_num_rows($resultado_oficinas) > 0): ?>
                
                <?php while ($oficina = mysqli_fetch_assoc($resultado_oficinas)): ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="card shadow-sm h-100 border-0">
                            <div class="card-body p-4">
                                <h4 class="card-title text-primary fw-bold mb-1">
                                    <i class="bi bi-geo-alt-fill"></i> <?php echo htmlspecialchars($oficina['city']); ?>
                                </h4>
                                <h6 class="card-subtitle mb-4 text-muted border-bottom pb-2">
                                    <?php echo htmlspecialchars($oficina['country'] . ' - Territorio: ' . $oficina['territory']); ?>
                                </h6>
                                
                                <ul class="list-unstyled mb-0">
                                    <li class="mb-2">
                                        <i class="bi bi-telephone text-secondary me-2"></i> 
                                        <strong>Teléfono:</strong> <?php echo htmlspecialchars($oficina['phone']); ?>
                                    </li>
                                    <li class="mb-2">
                                        <i class="bi bi-map text-secondary me-2"></i> 
                                        <?php echo htmlspecialchars($oficina['addressLine1']); ?>
                                    </li>
                                    <?php if ($oficina['addressLine2']): ?>
                                        <li class="mb-2">
                                            <i class="bi bi-geo text-secondary me-2"></i> 
                                            <?php echo htmlspecialchars($oficina['addressLine2']); ?>
                                        </li>
                                    <?php endif; ?>
                                    <li>
                                        <i class="bi bi-mailbox text-secondary me-2"></i> 
                                        <strong>Código Postal:</strong> <?php echo htmlspecialchars($oficina['postalCode']); ?>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="col-12 text-center text-muted py-5">
                    No se encontraron oficinas registradas.
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php 
// CIERRE ------ Finalización de la conexión con el servidor de base de datos
mysqli_close($conexion); 
?>
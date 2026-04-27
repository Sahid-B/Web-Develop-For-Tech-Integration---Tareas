<?php
require_once '../includes/auth.php';

require_once '../config/db.php';
$conexion = obtenerConexion();

// Parte 2: Consulta SELECT para obtener los registros (READ)
// Limitamos a 50 para no sobrecargar la tabla en la demostracion
$query = "SELECT customerNumber, customerName, contactFirstName, contactLastName, phone, city, country FROM customers ORDER BY customerNumber DESC LIMIT 50";
$resultado = mysqli_query($conexion, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer List - CRM</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Estilos Personalizados -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>css/styles.css">
</head>
<body>
    <!-- Inclusión del navbar reutilizable -->
    <?php include '../includes/navbar.php'; ?>

    <div class="container pb-5">
        <?php if(isset($_GET['message']) && $_GET['message'] == 'deleted'): ?>
            <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> The customer has been deleted successfully.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="page-title mb-0"><i class="bi bi-people-fill text-primary"></i> Customer Directory</h2>
            <a href="create.php" class="btn btn-primary"><i class="bi bi-plus-circle"></i> New Customer</a>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-striped align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">ID</th>
                                <th>Company / Customer</th>
                                <th>Contact</th>
                                <th>Phone</th>
                                <th>City</th>
                                <th>Country</th>
                                <th class="text-center pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (mysqli_num_rows($resultado) > 0): ?>
                                <!-- Iteramos sobre los resultados y los mostramos con mysqli_fetch_assoc -->
                                <?php while ($fila = mysqli_fetch_assoc($resultado)): ?>
                                    <tr>
                                        <td class="ps-4 fw-bold text-secondary">#<?php echo htmlspecialchars($fila['customerNumber']); ?></td>
                                        <td class="fw-medium text-dark"><?php echo htmlspecialchars($fila['customerName']); ?></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="bg-light rounded-circle p-2 me-2 text-primary" style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center;">
                                                    <i class="bi bi-person"></i>
                                                </div>
                                                <?php echo htmlspecialchars($fila['contactFirstName'] . ' ' . $fila['contactLastName']); ?>
                                            </div>
                                        </td>
                                        <td><?php echo htmlspecialchars($fila['phone']); ?></td>
                                        <td><?php echo htmlspecialchars($fila['city']); ?></td>
                                        <td><span class="badge bg-info text-dark rounded-pill px-3"><?php echo htmlspecialchars($fila['country']); ?></span></td>
                                        <td class="text-center pe-4">
                                            <div class="btn-group" role="group">
                                                <a href="view.php?id=<?php echo $fila['customerNumber']; ?>" class="btn btn-sm btn-outline-info" title="View Profile and Orders">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="edit.php?id=<?php echo $fila['customerNumber']; ?>" class="btn btn-sm btn-outline-primary" title="Edit">
                                                    <i class="bi bi-pencil-square"></i>
                                                </a>
                                                <a href="delete.php?id=<?php echo $fila['customerNumber']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to permanently delete this customer?');" title="Delete">
                                                    <i class="bi bi-trash"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="text-center py-5 text-muted">
                                        <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                        No customers were found in the database.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php
// Obligatorio: Cerrar la conexiÃ³n al final del archivo
mysqli_close($conexion);
?>




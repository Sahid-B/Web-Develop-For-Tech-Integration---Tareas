<?php
require_once '../includes/auth.php';
require_once '../config/db.php';
$conexion = obtenerConexion();

if (!isset($_GET['id'])) {
    header("Location: customers.php");
    exit();
}

$id = (int)$_GET['id'];
$id_seguro = mysqli_real_escape_string($conexion, $id);

// 1. Obtener datos del cliente y su representante de ventas (si tiene)
$query_cliente = "SELECT c.*, e.firstName as repName, e.lastName as repLastName 
                  FROM customers c 
                  LEFT JOIN employees e ON c.salesRepEmployeeNumber = e.employeeNumber 
                  WHERE c.customerNumber = $id_seguro";
$resultado_cliente = mysqli_query($conexion, $query_cliente);
$cliente = mysqli_fetch_assoc($resultado_cliente);

if (!$cliente) {
    header("Location: customers.php");
}

// 2. Obtener historial de Ã³rdenes de este cliente
$query_ordenes = "SELECT * FROM orders WHERE customerNumber = $id_seguro ORDER BY orderDate DESC";
$resultado_ordenes = mysqli_query($conexion, $query_ordenes);

// 3. Obtener historial de pagos
$query_pagos = "SELECT * FROM payments WHERE customerNumber = $id_seguro ORDER BY paymentDate DESC";
$resultado_pagos = mysqli_query($conexion, $query_pagos);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Profile - CRM</title>
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
                <i class="bi bi-person-badge text-info"></i> Profile: <?php echo htmlspecialchars($cliente['customerName']); ?>
            </h2>
            <div>
                <a href="edit.php?id=<?php echo $id; ?>" class="btn btn-outline-primary me-2"><i class="bi bi-pencil"></i> Edit Details</a>
                <a href="customers.php" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Back</a>
            </div>
        </div>

        <div class="row">
            <!-- Tarjeta de Detalles del Cliente -->
            <div class="col-lg-4 mb-4">
                <div class="card shadow-sm h-100 border-0">
                    <div class="card-body">
                        <h5 class="card-title text-muted border-bottom pb-2 mb-3">General Information</h5>
                        <ul class="list-unstyled">
                            <li class="mb-2"><strong><i class="bi bi-building text-primary"></i> Company:</strong> <?php echo htmlspecialchars($cliente['customerName']); ?></li>
                            <li class="mb-2"><strong><i class="bi bi-person text-primary"></i> Contact:</strong> <?php echo htmlspecialchars($cliente['contactFirstName'] . ' ' . $cliente['contactLastName']); ?></li>
                            <li class="mb-2"><strong><i class="bi bi-telephone text-primary"></i> Phone:</strong> <?php echo htmlspecialchars($cliente['phone']); ?></li>
                            <li class="mb-2"><strong><i class="bi bi-geo-alt text-primary"></i> Address:</strong> <?php echo htmlspecialchars($cliente['addressLine1']); ?></li>
                            <li class="mb-2"><strong><i class="bi bi-globe text-primary"></i> Location:</strong> <?php echo htmlspecialchars($cliente['city'] . ', ' . $cliente['country']); ?></li>
                            <?php if ($cliente['repName']): ?>
                                <li class="mb-2 mt-3 p-2 bg-light rounded text-dark">
                                    <small class="d-block text-muted">Sales Representative:</small>
                                    <i class="bi bi-person-workspace text-success"></i> <?php echo htmlspecialchars($cliente['repName'] . ' ' . $cliente['repLastName']); ?>
                                </li>
                            <?php endif; ?>
                            <li class="mb-2 mt-3 pt-3 border-top">
                                <strong class="d-block mb-1 text-muted">Credit Limit:</strong>
                                <span class="fs-4 text-success fw-bold">$<?php echo number_format($cliente['creditLimit'], 2); ?></span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Columna de Historial (Ordenes y Pagos) -->
            <div class="col-lg-8">
                <!-- Ordenes Recientes -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0 text-primary"><i class="bi bi-cart3"></i> Historial de Ordenes</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-3">Order #</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                        <th>Required Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (mysqli_num_rows($resultado_ordenes) > 0): ?>
                                        <?php while ($orden = mysqli_fetch_assoc($resultado_ordenes)): ?>
                                            <tr>
                                                <td class="ps-3 fw-bold text-secondary">#<?php echo $orden['orderNumber']; ?></td>
                                                <td><?php echo $orden['orderDate']; ?></td>
                                                <td>
                                                    <?php 
                                                        $estado = $orden['status'];
                                                        $badgeClass = 'bg-secondary';
                                                        if ($estado == 'Shipped') $badgeClass = 'bg-success';
                                                        if ($estado == 'In Process') $badgeClass = 'bg-warning text-dark';
                                                        if ($estado == 'Cancelled') $badgeClass = 'bg-danger';
                                                        if ($estado == 'Resolved') $badgeClass = 'bg-info text-dark';
                                                        echo "<span class='badge $badgeClass'>$estado</span>";
                                                    ?>
                                                </td>
                                                <td><?php echo $orden['requiredDate']; ?></td>
                                            </tr>
                                        <?php endwhile; ?>
                                    <?php else: ?>
                                        <tr><td colspan="4" class="text-center text-muted py-3">No orders are registered for this customer.</td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Pagos Recientes -->
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0 text-success"><i class="bi bi-cash-coin"></i> Historial de Pagos</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-3">Check # / Reference</th>
                                        <th>Payment Date</th>
                                        <th class="text-end pe-3">Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (mysqli_num_rows($resultado_pagos) > 0): ?>
                                        <?php while ($pago = mysqli_fetch_assoc($resultado_pagos)): ?>
                                            <tr>
                                                <td class="ps-3"><i class="bi bi-receipt text-muted me-2"></i><?php echo htmlspecialchars($pago['checkNumber']); ?></td>
                                                <td><?php echo $pago['paymentDate']; ?></td>
                                                <td class="text-end pe-3 fw-bold text-success">$<?php echo number_format($pago['amount'], 2); ?></td>
                                            </tr>
                                        <?php endwhile; ?>
                                    <?php else: ?>
                                        <tr><td colspan="3" class="text-center text-muted py-3">No payments are registered for this customer.</td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php mysqli_close($conexion); ?>




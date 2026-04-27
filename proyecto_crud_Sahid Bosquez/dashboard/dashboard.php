<?php
require_once '../includes/auth.php';
require_once '../config/db.php';
$conexion = obtenerConexion();

// 1. Consulta: Total de Clientes Registrados
$res_clientes = mysqli_query($conexion, "SELECT COUNT(*) as total FROM customers");
$total_clientes = mysqli_fetch_assoc($res_clientes)['total'];

// 2. Consulta: Total de Productos en el Inventario
$res_productos = mysqli_query($conexion, "SELECT COUNT(*) as total FROM products");
$total_productos = mysqli_fetch_assoc($res_productos)['total'];

// 3. Consulta: Valor Total del Inventario (Multiplicando stock por precio de compra)
$res_valor = mysqli_query($conexion, "SELECT SUM(quantityInStock * buyPrice) as total_valor FROM products");
$valor_inventario = mysqli_fetch_assoc($res_valor)['total_valor'];

// 4. Consulta: Ordenes Pendientes (Contando las que tienen estado 'In Process')
$res_ordenes = mysqli_query($conexion, "SELECT COUNT(*) as total FROM orders WHERE status = 'In Process'");
$ordenes_pendientes = mysqli_fetch_assoc($res_ordenes)['total'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - CRM</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>css/styles.css">
</head>
<body class="bg-light">
    <?php include '../includes/navbar.php'; ?>

    <div class="container pb-5">
        <h2 class="page-title mb-4"><i class="bi bi-bar-chart-line-fill text-primary"></i> System Summary</h2>

        <div class="row g-4">
            <!-- Tarjeta 1: Clientes -->
            <div class="col-md-6 col-lg-3">
                <div class="card shadow-sm border-0 h-100 border-start border-primary border-4">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h6 class="text-muted mb-0 fw-bold text-uppercase" style="font-size: 0.8rem;">Total Customers</h6>
                            <i class="bi bi-people-fill fs-3 text-primary opacity-75"></i>
                        </div>
                        <h3 class="fw-bold mb-0"><?php echo number_format($total_clientes); ?></h3>
                    </div>
                </div>
            </div>

            <!-- Tarjeta 2: Productos -->
            <div class="col-md-6 col-lg-3">
                <div class="card shadow-sm border-0 h-100 border-start border-success border-4">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h6 class="text-muted mb-0 fw-bold text-uppercase" style="font-size: 0.8rem;">Total Products</h6>
                            <i class="bi bi-box-seam-fill fs-3 text-success opacity-75"></i>
                        </div>
                        <h3 class="fw-bold mb-0"><?php echo number_format($total_productos); ?></h3>
                    </div>
                </div>
            </div>

            <!-- Tarjeta 3: Valor Inventario -->
            <div class="col-md-6 col-lg-3">
                <div class="card shadow-sm border-0 h-100 border-start border-warning border-4">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h6 class="text-muted mb-0 fw-bold text-uppercase" style="font-size: 0.8rem;">Inventory Value</h6>
                            <i class="bi bi-cash-stack fs-3 text-warning opacity-75"></i>
                        </div>
                        <h3 class="fw-bold mb-0">$<?php echo number_format($valor_inventario, 2); ?></h3>
                    </div>
                </div>
            </div>

            <!-- Tarjeta 4: odenes Pendientes -->
            <div class="col-md-6 col-lg-3">
                <div class="card shadow-sm border-0 h-100 border-start border-danger border-4">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h6 class="text-muted mb-0 fw-bold text-uppercase" style="font-size: 0.8rem;">Orders (In Process)</h6>
                            <i class="bi bi-clock-history fs-3 text-danger opacity-75"></i>
                        </div>
                        <h3 class="fw-bold mb-0"><?php echo number_format($ordenes_pendientes); ?></h3>
                        <div class="mt-2 text-danger small"><i class="bi bi-exclamation-circle"></i> Pending Shipment</div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Tarjeta de Informacion Adicional (Simple) -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card shadow-sm border-0 bg-white">
                    <div class="card-body text-center p-4">
                        <i class="bi bi-database-check text-success fs-1 mb-2 d-block"></i>
                        <h5 class="fw-bold text-dark">Active Database Connection</h5>
                        <p class="text-muted mb-4">These metrics are calculated in real time by making dynamic queries to the <b>Classic Models</b> database.</p>
                        <div class="d-flex justify-content-center gap-3">
                            <a href="<?php echo BASE_URL; ?>customers/customers.php" class="btn btn-outline-primary px-4">View Customer List</a>
                            <a href="<?php echo BASE_URL; ?>products/products.php" class="btn btn-outline-success px-4">View Inventory</a>
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
<?php 
// Obligatorio: Cerrar la conexion
mysqli_close($conexion); 
?>



<?php
require_once '../includes/auth.php';
require_once '../config/db.php';
$conexion = obtenerConexion();

// 1. Consulta a la tabla productlines (USO DE NUEVA TABLA)
$query_lineas = "SELECT productLine, textDescription FROM productlines";
$resultado_lineas = mysqli_query($conexion, $query_lineas);

// 2. Consulta para obtener inventario de productos (Tabla products)
$query = "SELECT productCode, productName, productLine, productScale, productVendor, quantityInStock, buyPrice 
          FROM products 
          ORDER BY productLine ASC, productName ASC 
          LIMIT 100";
$resultado = mysqli_query($conexion, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products Inventory - CRM</title>
    <!-- Bootstrap 5 -->
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
                <i class="bi bi-box-seam text-primary"></i> Products Catalog
            </h2>
            <div>
                <span class="badge bg-primary fs-6 me-2 d-none d-sm-inline-block">Current Stock</span>
                <a href="create.php" class="btn btn-success shadow-sm"><i class="bi bi-plus-circle me-1"></i> Add Product</a>
            </div>
        </div>

        <!-- SECTION: PRODUCT LINES (productlines TABLE) -->
        <div class="mb-5">
            <h5 class="text-muted mb-3 fw-bold"><i class="bi bi-tags-fill text-warning"></i> Official Categories (Product Lines)</h5>
            <div class="row g-3">
                <?php while ($linea = mysqli_fetch_assoc($resultado_lineas)): ?>
                    <div class="col-md-6 col-lg-3">
                        <div class="card border-0 h-100 shadow-sm" style="border-left: 4px solid #ffc107 !important;">
                            <div class="card-body">
                                <h6 class="fw-bold text-dark mb-1"><?php echo htmlspecialchars($linea['productLine']); ?></h6>
                                <p class="small text-muted mb-0" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;" title="<?php echo htmlspecialchars($linea['textDescription']); ?>">
                                    <?php echo htmlspecialchars($linea['textDescription']); ?>
                                </p>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>

        <!-- SECCION: TABLA DE PRODUCTOS -->
        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 w-100">
                        <thead class="table-dark">
                            <tr>
                                <th class="ps-4">Code</th>
                                <th>Product Name</th>
                                <th>Category</th>
                                <th>Vendor</th>
                                <th class="text-center">Stock</th>
                                <th class="text-end pe-4">Purchase Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (mysqli_num_rows($resultado) > 0): ?>
                                <?php while ($producto = mysqli_fetch_assoc($resultado)): ?>
                                    <tr>
                                        <td class="ps-4 fw-bold text-secondary"><?php echo htmlspecialchars($producto['productCode']); ?></td>
                                        <td class="fw-medium"><?php echo htmlspecialchars($producto['productName']); ?></td>
                                        <td>
                                            <span class="badge bg-info text-dark rounded-pill px-3">
                                                <?php echo htmlspecialchars($producto['productLine']); ?>
                                            </span>
                                        </td>
                                        <td class="text-muted"><small><?php echo htmlspecialchars($producto['productVendor']); ?></small></td>
                                        <td class="text-center">
                                            <?php 
                                                $stock = $producto['quantityInStock'];
                                                if ($stock < 100) {
                                                    echo "<span class='text-danger fw-bold'>$stock</span> <i class='bi bi-exclamation-circle text-danger' title='Stock Bajo'></i>";
                                                } else {
                                                    echo "<span class='text-success fw-bold'>$stock</span>";
                                                }
                                            ?>
                                        </td>
                                        <td class="text-end pe-4 fw-bold text-dark">
                                            $<?php echo number_format($producto['buyPrice'], 2); ?>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">No products were found in the inventory.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-white py-3 text-muted text-center">
                Showing the first 100 products from the Classic Models catalog.
            </div>
        </div>
    </div>

    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php mysqli_close($conexion); ?>




<?php
require_once '../includes/auth.php';
require_once '../config/db.php';
$conexion = obtenerConexion();

$mensaje = '';
$tipo_mensaje = '';

// Obtener las categorias (Lineas de producto) para llenar el menu desplegable
$query_lineas = "SELECT productLine FROM productlines";
$res_lineas = mysqli_query($conexion, $query_lineas);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Limpieza de datos (Seguridad)
    $productCode = mysqli_real_escape_string($conexion, $_POST['productCode']);
    $productName = mysqli_real_escape_string($conexion, $_POST['productName']);
    $productLine = mysqli_real_escape_string($conexion, $_POST['productLine']);
    $productScale = mysqli_real_escape_string($conexion, $_POST['productScale']);
    $productVendor = mysqli_real_escape_string($conexion, $_POST['productVendor']);
    $productDescription = mysqli_real_escape_string($conexion, $_POST['productDescription']);
    
    // Casting de numeros para seguridad extra
    $quantityInStock = (int)$_POST['quantityInStock'];
    $buyPrice = (float)$_POST['buyPrice'];
    $MSRP = (float)$_POST['MSRP']; // Precio Sugerido de Venta

    // Insercion de un nuevo producto
    $query = "INSERT INTO products (productCode, productName, productLine, productScale, productVendor, productDescription, quantityInStock, buyPrice, MSRP) 
              VALUES ('$productCode', '$productName', '$productLine', '$productScale', '$productVendor', '$productDescription', $quantityInStock, $buyPrice, $MSRP)";

    if (mysqli_query($conexion, $query)) {
        $mensaje = "El producto '$productName' fue agregado exitosamente al inventario.";
        $tipo_mensaje = "success";
    } else {
        $mensaje = "Error al agregar producto. Asegurese de que el codigo sea unico. Detalles: " . mysqli_error($conexion);
        $tipo_mensaje = "danger";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product - CRM</title>
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
                    <h2 class="page-title mb-0"><i class="bi bi-box-seam-fill text-success"></i> Register New Product</h2>
                    <a href="products.php" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Back to Catalog</a>
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
                        <form action="create.php" method="POST">
                            <h5 class="text-success mb-4 border-bottom pb-2">Product Identification</h5>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="productCode" class="form-label text-muted fw-semibold">Unique Code *</label>
                                    <input type="text" class="form-control" id="productCode" name="productCode" required placeholder="Ex: S10_1678">
                                </div>
                                <div class="col-md-8 mt-3 mt-md-0">
                                    <label for="productName" class="form-label text-muted fw-semibold">Product Name *</label>
                                    <input type="text" class="form-control" id="productName" name="productName" required placeholder="Ex: 1969 Harley Davidson Ultimate Chopper">
                                </div>
                            </div>
                            
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label for="productLine" class="form-label text-muted fw-semibold">Line / Category *</label>
                                    <!-- Dynamic menu loaded from the database -->
                                    <select class="form-select" id="productLine" name="productLine" required>
                                        <option value="" disabled selected>Select a category</option>
                                        <?php 
                                        // Volvemos al inicio de los resultados
                                        mysqli_data_seek($res_lineas, 0); 
                                        while($linea = mysqli_fetch_assoc($res_lineas)): 
                                        ?>
                                            <option value="<?php echo htmlspecialchars($linea['productLine']); ?>"><?php echo htmlspecialchars($linea['productLine']); ?></option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>
                                <div class="col-md-6 mt-3 mt-md-0">
                                    <label for="productScale" class="form-label text-muted fw-semibold">Scale *</label>
                                    <input type="text" class="form-control" id="productScale" name="productScale" required placeholder="Ex: 1:10">
                                </div>
                            </div>

                            <h5 class="text-success mt-5 mb-4 border-bottom pb-2">Details and Vendor</h5>
                            <div class="mb-3">
                                <label for="productVendor" class="form-label text-muted fw-semibold">Vendor *</label>
                                <input type="text" class="form-control" id="productVendor" name="productVendor" required placeholder="Ex: Min Lin Diecast">
                            </div>

                            <div class="mb-4">
                                <label for="productDescription" class="form-label text-muted fw-semibold">Product Description *</label>
                                <textarea class="form-control" id="productDescription" name="productDescription" rows="4" required></textarea>
                            </div>

                            <h5 class="text-success mt-5 mb-4 border-bottom pb-2">Inventory and Pricing</h5>
                            <div class="row mb-5">
                                <div class="col-md-4">
                                    <label for="quantityInStock" class="form-label text-muted fw-semibold">Initial Stock *</label>
                                    <input type="number" class="form-control" id="quantityInStock" name="quantityInStock" required min="0" value="0">
                                </div>
                                <div class="col-md-4 mt-3 mt-md-0">
                                    <label for="buyPrice" class="form-label text-muted fw-semibold">Purchase Price ($) *</label>
                                    <input type="number" step="0.01" class="form-control" id="buyPrice" name="buyPrice" required min="0" placeholder="0.00">
                                </div>
                                <div class="col-md-4 mt-3 mt-md-0">
                                    <label for="MSRP" class="form-label text-muted fw-semibold">MSRP (Sale $) *</label>
                                    <input type="number" step="0.01" class="form-control" id="MSRP" name="MSRP" required min="0" placeholder="0.00">
                                </div>
                            </div>
                            
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <button type="reset" class="btn btn-light border px-4 me-md-2">Reset</button>
                                <button type="submit" class="btn btn-success px-5 shadow"><i class="bi bi-save me-1"></i> Save Product</button>
                            </div>
                        </form>
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




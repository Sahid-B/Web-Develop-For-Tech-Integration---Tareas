<nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4 shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="<?php echo BASE_URL; ?>dashboard/dashboard.php">
            <i class="bi bi-database-fill"></i> CRM Classic Models
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo BASE_URL; ?>dashboard/dashboard.php"><i class="bi bi-bar-chart-fill"></i> Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo BASE_URL; ?>customers/customers.php"><i class="bi bi-people"></i> Customers</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo BASE_URL; ?>products/products.php"><i class="bi bi-box-seam"></i> Products</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo BASE_URL; ?>offices/offices.php"><i class="bi bi-building"></i> Offices</a>
                </li>
            </ul>
            <ul class="navbar-nav ms-auto align-items-center">
                <?php if (isset($_SESSION['empleado_nombre'])): ?>
                    <li class="nav-item me-4 text-light d-none d-lg-block text-end" style="line-height: 1.2;">
                        <small class="opacity-75" style="font-size: 0.75rem;"><i class="bi bi-person-badge"></i> <?php echo htmlspecialchars($_SESSION['empleado_puesto']); ?></small><br>
                        <span class="fw-bold"><?php echo htmlspecialchars($_SESSION['empleado_nombre']); ?></span>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-light btn-sm fw-bold text-danger shadow-sm" href="<?php echo BASE_URL; ?>auth/logout.php">
                            <i class="bi bi-box-arrow-right"></i> Logout
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>



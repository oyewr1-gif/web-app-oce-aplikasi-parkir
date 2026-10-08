<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? htmlspecialchars($title) . ' - ' . APP_NAME : APP_NAME ?></title>
    <!-- Google Fonts Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/style.css">
</head>
<body>

<div id="wrapper">
    <!-- Sidebar -->
    <div id="sidebar-wrapper">
        <div class="sidebar-heading">
            <i class="fa-solid fa-square-parking text-primary fs-3"></i>
            <span>PARKIR MVC</span>
        </div>
        <div class="list-group list-group-flush mt-3">
            <a href="<?= BASE_URL ?>/dashboard" class="list-group-item <?= (strpos($_SERVER['REQUEST_URI'], 'dashboard') !== false || $_SERVER['REQUEST_URI'] == '/') ? 'active' : '' ?>">
                <i class="fa-solid fa-chart-pie"></i> Dashboard
            </a>

            <!-- Menu Transaksi Parkir (Kasir & Admin) -->
            <div class="px-3 text-uppercase text-muted fw-bold small mt-3 mb-1">Transaksi</div>
            <a href="<?= BASE_URL ?>/parkir/masuk" class="list-group-item <?= (strpos($_SERVER['REQUEST_URI'], 'parkir/masuk') !== false) ? 'active' : '' ?>">
                <i class="fa-solid fa-right-to-bracket text-success"></i> Kendaraan Masuk
            </a>
            <a href="<?= BASE_URL ?>/parkir/keluar" class="list-group-item <?= (strpos($_SERVER['REQUEST_URI'], 'parkir/keluar') !== false) ? 'active' : '' ?>">
                <i class="fa-solid fa-right-from-bracket text-warning"></i> Kendaraan Keluar
            </a>
            <a href="<?= BASE_URL ?>/member" class="list-group-item <?= (strpos($_SERVER['REQUEST_URI'], 'member') !== false) ? 'active' : '' ?>">
                <i class="fa-solid fa-id-card text-info"></i> Member Parkir
            </a>

            <!-- Menu Master & Admin Only -->
            <?php if (Session::get('user_level') == 1): ?>
                <div class="px-3 text-uppercase text-muted fw-bold small mt-4 mb-1">Administrator</div>
                <a href="<?= BASE_URL ?>/kendaraan" class="list-group-item <?= (strpos($_SERVER['REQUEST_URI'], 'kendaraan') !== false) ? 'active' : '' ?>">
                    <i class="fa-solid fa-car"></i> Master Kendaraan
                </a>
                <a href="<?= BASE_URL ?>/user" class="list-group-item <?= (strpos($_SERVER['REQUEST_URI'], 'user') !== false) ? 'active' : '' ?>">
                    <i class="fa-solid fa-users-gear"></i> Kelola User / Kasir
                </a>
                <a href="<?= BASE_URL ?>/laporan" class="list-group-item <?= (strpos($_SERVER['REQUEST_URI'], 'laporan') !== false) ? 'active' : '' ?>">
                    <i class="fa-solid fa-file-invoice-dollar"></i> Laporan & Setoran
                </a>
            <?php endif; ?>

            <div class="px-3 text-uppercase text-muted fw-bold small mt-4 mb-1">Akun</div>
            <a href="<?= BASE_URL ?>/auth/logout" class="list-group-item text-danger" onclick="return confirm('Yakin ingin keluar aplikasi?')">
                <i class="fa-solid fa-right-from-bracket"></i> Logout
            </a>
        </div>
    </div>
    <!-- /#sidebar-wrapper -->

    <!-- Page Content Wrapper -->
    <div id="page-content-wrapper">
        <!-- Top Navbar -->
        <nav class="navbar navbar-expand-lg navbar-top">
            <div class="container-fluid">
                <span class="navbar-brand mb-0 h1 fw-semibold text-secondary fs-6">
                    <i class="fa-regular fa-clock me-1"></i> <?= date('l, d F Y') ?>
                </span>
                <div class="d-flex align-items-center gap-3">
                    <div class="user-badge">
                        <i class="fa-solid fa-user-circle me-1 text-primary"></i>
                        <?= htmlspecialchars(Session::get('user_name') ?? 'User') ?> 
                        <span class="badge bg-secondary ms-1"><?= htmlspecialchars(Session::get('user_level_nama') ?? 'Staff') ?></span>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main Body Container -->
        <div class="container-fluid p-4">
            <?php Session::flash(); ?>

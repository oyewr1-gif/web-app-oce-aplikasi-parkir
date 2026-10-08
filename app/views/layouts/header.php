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
                <?php 
                    $isMasterActive = (
                        strpos($_SERVER['REQUEST_URI'], 'kendaraan') !== false ||
                        strpos($_SERVER['REQUEST_URI'], 'tarif') !== false ||
                        strpos($_SERVER['REQUEST_URI'], 'user') !== false ||
                        strpos($_SERVER['REQUEST_URI'], 'setoran/shift') !== false
                    );
                ?>
                <div class="px-3 text-uppercase text-muted fw-bold small mt-4 mb-1">Administrator</div>
                
                <!-- Group Dropdown: Master & Kelola User -->
                <a class="list-group-item sidebar-dropdown-toggle d-flex justify-content-between align-items-center <?= $isMasterActive ? 'active text-white' : '' ?>" 
                   data-bs-toggle="collapse" 
                   href="#menuMasterUser" 
                   role="button" 
                   aria-expanded="<?= $isMasterActive ? 'true' : 'false' ?>" 
                   aria-controls="menuMasterUser">
                    <span><i class="fa-solid fa-layer-group text-warning"></i> Master & User</span>
                    <i class="fa-solid fa-chevron-down small chevron-icon"></i>
                </a>
                <div class="collapse <?= $isMasterActive ? 'show' : '' ?>" id="menuMasterUser">
                    <div class="sidebar-submenu">
                        <div class="px-3 pt-2 pb-1 text-uppercase text-white-50 fw-bold" style="font-size: 0.7rem; letter-spacing: 0.5px;">Master Data</div>
                        <a href="<?= BASE_URL ?>/kendaraan" class="list-group-item submenu-item <?= strpos($_SERVER['REQUEST_URI'], 'kendaraan') !== false ? 'active' : '' ?>">
                            <i class="fa-solid fa-car text-primary"></i> Master Kendaraan
                        </a>
                        <a href="<?= BASE_URL ?>/tarif" class="list-group-item submenu-item <?= strpos($_SERVER['REQUEST_URI'], 'tarif') !== false ? 'active' : '' ?>">
                            <i class="fa-solid fa-tags text-warning"></i> Master Tarif & Diskon
                        </a>
                        <a href="<?= BASE_URL ?>/setoran/shift" class="list-group-item submenu-item <?= strpos($_SERVER['REQUEST_URI'], 'setoran/shift') !== false ? 'active' : '' ?>">
                            <i class="fa-solid fa-clock-rotate-left text-info"></i> Master Jam Shift
                        </a>

                        <div class="px-3 pt-2 pb-1 text-uppercase text-white-50 fw-bold border-top border-secondary border-opacity-25 mt-2" style="font-size: 0.7rem; letter-spacing: 0.5px;">Pengguna</div>
                        <a href="<?= BASE_URL ?>/user" class="list-group-item submenu-item <?= (strpos($_SERVER['REQUEST_URI'], 'user') !== false && strpos($_SERVER['REQUEST_URI'], 'setoran') === false) ? 'active' : '' ?>">
                            <i class="fa-solid fa-users-gear text-success"></i> Kelola User / Kasir
                        </a>
                    </div>
                </div>

                <a href="<?= BASE_URL ?>/setoran" class="list-group-item <?= (strpos($_SERVER['REQUEST_URI'], 'setoran') !== false && strpos($_SERVER['REQUEST_URI'], 'setoran/shift') === false) ? 'active' : '' ?>">
                    <i class="fa-solid fa-receipt text-success"></i> Setoran Kasir
                </a>
                <a href="<?= BASE_URL ?>/laporan" class="list-group-item <?= (strpos($_SERVER['REQUEST_URI'], 'laporan') !== false) ? 'active' : '' ?>">
                    <i class="fa-solid fa-chart-line text-info"></i> Laporan & Audit Void
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

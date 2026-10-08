<?php require_once '../app/views/layouts/header.php'; ?>

<!-- Page Title Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold text-dark mb-0">Dashboard Ringkasan Parkir</h3>
        <p class="text-muted small mb-0">Status operasional gate parkir dan ringkasan transaksi real-time.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?= BASE_URL ?>/parkir/masuk" class="btn btn-success fw-semibold">
            <i class="fa-solid fa-plus-circle me-1"></i> Parkir Masuk
        </a>
        <a href="<?= BASE_URL ?>/parkir/keluar" class="btn btn-warning fw-semibold text-dark">
            <i class="fa-solid fa-receipt me-1"></i> Bayar / Keluar
        </a>
    </div>
</div>

<!-- Stat Cards Row -->
<div class="row g-3 mb-4">
    <div class="col-12 col-sm-6 col-md-4">
        <div class="stat-card p-3">
            <div class="d-flex align-items-center">
                <div class="icon-box bg-primary-light me-3">
                    <i class="fa-solid fa-car"></i>
                </div>
                <div>
                    <span class="text-muted small fw-semibold">Kendaraan Sedang Parkir</span>
                    <h3 class="fw-bold text-dark mb-0"><?= number_format($active_vehicles) ?></h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-md-4">
        <div class="stat-card p-3">
            <div class="d-flex align-items-center">
                <div class="icon-box bg-success-light me-3">
                    <i class="fa-solid fa-money-bill-wave"></i>
                </div>
                <div>
                    <span class="text-muted small fw-semibold">Pendapatan Parkir Hari Ini</span>
                    <h3 class="fw-bold text-dark mb-0">Rp <?= number_format($today_income) ?></h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-md-4">
        <div class="stat-card p-3">
            <div class="d-flex align-items-center">
                <div class="icon-box bg-warning-light me-3">
                    <i class="fa-solid fa-ticket"></i>
                </div>
                <div>
                    <span class="text-muted small fw-semibold">Total Kendaraan Hari Ini</span>
                    <h3 class="fw-bold text-dark mb-0"><?= number_format($today_transactions) ?></h3>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Vehicle Occupancy & Capacity Status -->
<div class="row g-3 mb-4">
    <div class="col-12 col-lg-4">
        <div class="card card-custom h-100">
            <div class="card-header">
                <i class="fa-solid fa-layer-group me-1 text-primary"></i> Kapasitas per Jenis Kendaraan
            </div>
            <div class="card-body">
                <?php foreach ($jenis_list as $j): ?>
                    <?php 
                        $pct = $j['kapasitas'] > 0 ? round(($j['terpakai'] / $j['kapasitas']) * 100) : 0;
                        $bgClass = $pct > 85 ? 'bg-danger' : ($pct > 50 ? 'bg-warning' : 'bg-success');
                    ?>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between small mb-1">
                            <span class="fw-semibold"><?= htmlspecialchars($j['nama']) ?></span>
                            <span class="text-muted"><?= $j['terpakai'] ?> / <?= $j['kapasitas'] ?> Slot (<?= $pct ?>%)</span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar <?= $bgClass ?>" role="progressbar" style="width: <?= $pct ?>%"></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Active Parked Vehicles List -->
    <div class="col-12 col-lg-8">
        <div class="card card-custom h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="fa-solid fa-clock-rotate-left me-1 text-warning"></i> Kendaraan Parkir Saat Ini</span>
                <span class="badge bg-primary rounded-pill"><?= count($active_list) ?> Unit</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>No. Tiket</th>
                                <th>Nopol</th>
                                <th>Jenis</th>
                                <th>Jam Masuk</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($active_list)): ?>
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">
                                        <i class="fa-solid fa-parking fa-2x mb-2 d-block"></i>
                                        Belum ada kendaraan yang terparkir.
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach (array_slice($active_list, 0, 6) as $item): ?>
                                    <tr>
                                        <td><span class="badge bg-light text-dark fw-bold border"><?= htmlspecialchars($item['idtrx']) ?></span></td>
                                        <td><span class="fw-bold text-uppercase text-primary"><?= htmlspecialchars($item['nopol']) ?></span></td>
                                        <td><?= htmlspecialchars($item['jn_kendaraan']) ?></td>
                                        <td><?= htmlspecialchars($item['jam_masuk']) ?> (<?= htmlspecialchars($item['tgl_masuk']) ?>)</td>
                                        <td class="text-end">
                                            <a href="<?= BASE_URL ?>/parkir/keluar?keyword=<?= urlencode($item['idtrx']) ?>" class="btn btn-sm btn-outline-warning fw-semibold">
                                                <i class="fa-solid fa-cash-register me-1"></i> Bayar
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once '../app/views/layouts/footer.php'; ?>

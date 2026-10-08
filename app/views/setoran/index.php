<?php require_once '../app/views/layouts/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-0 text-dark"><i class="fa-solid fa-receipt text-primary me-2"></i> Rekap Setoran Kasir & Shift</h4>
        <span class="text-muted small">Kelola berita acara penutupan shift kasir, pencocokan fisik uang tunai, dan laporan pendapatan</span>
    </div>
    <div>
        <a href="<?= BASE_URL ?>/setoran/shift" class="btn btn-outline-secondary me-2">
            <i class="fa-solid fa-clock me-1"></i> Master Jam Shift
        </a>
        <a href="<?= BASE_URL ?>/setoran/create" class="btn btn-primary">
            <i class="fa-solid fa-plus-circle me-1"></i> Tutup & Input Setoran Baru
        </a>
    </div>
</div>

<?php Session::flash(); ?>

<div class="card card-custom">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h6 class="fw-bold mb-0 text-dark"><i class="fa-solid fa-list me-2 text-secondary"></i> Riwayat Berita Acara Setoran (Tabel data_setoran)</h6>
        <span class="badge bg-secondary"><?= count($setoran_list) ?> Data Ditampilkan</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>No. Setoran</th>
                        <th>Tgl Setoran</th>
                        <th>Shift</th>
                        <th>Pos / Pintu</th>
                        <th>Nama Kasir</th>
                        <th>Penerima</th>
                        <th class="text-end">Sistem (Rp)</th>
                        <th class="text-end">Fisik (Rp)</th>
                        <th class="text-center">Status / Selisih</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($setoran_list)): ?>
                        <tr>
                            <td colspan="10" class="text-center py-5 text-muted">Belum ada rekaman setoran kasir.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($setoran_list as $s): ?>
                            <tr>
                                <td>
                                    <span class="fw-bold text-primary font-monospace"><?= htmlspecialchars($s['no_setoran']) ?></span>
                                </td>
                                <td><?= htmlspecialchars($s['tglsetoran']) ?></td>
                                <td><span class="badge bg-info text-dark"><?= htmlspecialchars($s['shift']) ?></span></td>
                                <td><span class="badge bg-light text-dark border"><?= htmlspecialchars($s['pintu']) ?></span></td>
                                <td class="fw-bold"><?= htmlspecialchars($s['nama_kasir'] ?: 'Petugas') ?></td>
                                <td class="text-muted small"><?= htmlspecialchars($s['penerima_nama'] ?: 'Admin') ?></td>
                                <td class="text-end fw-bold">Rp <?= number_format($s['jumuang']) ?></td>
                                <td class="text-end fw-bold text-success">Rp <?= number_format($s['fisik']) ?></td>
                                <td class="text-center">
                                    <?php if ($s['jumuangmasalah'] == 0): ?>
                                        <span class="badge bg-success"><i class="fa-solid fa-check me-1"></i> Cocok (Pas)</span>
                                    <?php elseif ($s['jumuangmasalah'] > 0): ?>
                                        <span class="badge bg-warning text-dark"><i class="fa-solid fa-arrow-up me-1"></i> Lebih (+Rp <?= number_format($s['jumuangmasalah']) ?>)</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger"><i class="fa-solid fa-arrow-down me-1"></i> Kurang (Rp <?= number_format($s['jumuangmasalah']) ?>)</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end">
                                    <div class="btn-group btn-group-sm">
                                        <a href="<?= BASE_URL ?>/setoran/detail/<?= $s['id'] ?>" class="btn btn-outline-primary" title="Lihat Rincian">
                                            <i class="fa-solid fa-eye"></i> Detail
                                        </a>
                                        <a href="<?= BASE_URL ?>/setoran/cetak/<?= $s['id'] ?>" target="_blank" class="btn btn-outline-dark" title="Cetak Berita Acara">
                                            <i class="fa-solid fa-print"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once '../app/views/layouts/footer.php'; ?>

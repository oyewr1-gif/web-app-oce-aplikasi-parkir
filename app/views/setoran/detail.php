<?php require_once '../app/views/layouts/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-0 text-dark"><i class="fa-solid fa-file-invoice text-primary me-2"></i> Detail Berita Acara Setoran</h4>
        <span class="text-muted small">Nomor Berita Acara: <strong class="text-primary font-monospace"><?= htmlspecialchars($setoran['no_setoran']) ?></strong></span>
    </div>
    <div>
        <a href="<?= BASE_URL ?>/setoran" class="btn btn-outline-secondary me-2">
            <i class="fa-solid fa-arrow-left me-1"></i> Kembali
        </a>
        <a href="<?= BASE_URL ?>/setoran/cetak/<?= $setoran['id'] ?>" target="_blank" class="btn btn-dark">
            <i class="fa-solid fa-print me-1"></i> Cetak Berita Acara
        </a>
    </div>
</div>

<?php Session::flash(); ?>

<div class="row g-4">
    <!-- Settlement Header Info -->
    <div class="col-12 col-lg-4">
        <div class="card card-custom mb-4">
            <div class="card-header bg-light py-3">
                <h6 class="fw-bold mb-0 text-dark"><i class="fa-solid fa-circle-info me-2 text-primary"></i> Informasi Setoran</h6>
            </div>
            <div class="card-body p-0">
                <table class="table table-sm mb-0">
                    <tbody>
                        <tr>
                            <td class="text-muted ps-3">No. Berita Acara</td>
                            <td class="fw-bold text-end pe-3 font-monospace"><?= htmlspecialchars($setoran['no_setoran']) ?></td>
                        </tr>
                        <tr>
                            <td class="text-muted ps-3">Tanggal Setoran</td>
                            <td class="fw-bold text-end pe-3"><?= htmlspecialchars($setoran['tglsetoran']) ?></td>
                        </tr>
                        <tr>
                            <td class="text-muted ps-3">Shift Kerja</td>
                            <td class="fw-bold text-end pe-3"><span class="badge bg-info text-dark"><?= htmlspecialchars($setoran['shift']) ?></span></td>
                        </tr>
                        <tr>
                            <td class="text-muted ps-3">Pintu / Pos Kasir</td>
                            <td class="fw-bold text-end pe-3"><?= htmlspecialchars($setoran['pintu']) ?></td>
                        </tr>
                        <tr>
                            <td class="text-muted ps-3">Petugas Kasir</td>
                            <td class="fw-bold text-end pe-3"><?= htmlspecialchars($setoran['nama_kasir'] ?: 'Kasir') ?></td>
                        </tr>
                        <tr>
                            <td class="text-muted ps-3">Penerima Setoran</td>
                            <td class="fw-bold text-end pe-3"><?= htmlspecialchars($setoran['penerima_nama'] ?: 'Admin') ?></td>
                        </tr>
                        <tr>
                            <td class="text-muted ps-3">Waktu Pencatatan</td>
                            <td class="text-end pe-3 small text-muted"><?= htmlspecialchars($setoran['waktu']) ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Cash Reconciliation Summary Card -->
        <div class="card card-custom">
            <div class="card-header bg-light py-3">
                <h6 class="fw-bold mb-0 text-dark"><i class="fa-solid fa-coins me-2 text-warning"></i> Rekonsiliasi Kas</h6>
            </div>
            <div class="card-body p-3">
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Total Sistem:</span>
                    <span class="fw-bold">Rp <?= number_format($setoran['jumuang']) ?></span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Uang Fisik Kasir:</span>
                    <span class="fw-bold text-success">Rp <?= number_format($setoran['fisik']) ?></span>
                </div>
                <hr>
                <div class="d-flex justify-content-between align-items-center">
                    <span class="fw-bold">Selisih Kas:</span>
                    <?php if ($setoran['jumuangmasalah'] == 0): ?>
                        <span class="badge bg-success fs-6">Rp 0 (Cocok / Pas)</span>
                    <?php elseif ($setoran['jumuangmasalah'] > 0): ?>
                        <span class="badge bg-warning text-dark fs-6">+Rp <?= number_format($setoran['jumuangmasalah']) ?> (Lebih)</span>
                    <?php else: ?>
                        <span class="badge bg-danger fs-6">-Rp <?= number_format(abs($setoran['jumuangmasalah'])) ?> (Kurang)</span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Settlement Details (Table detail_setoran) -->
    <div class="col-12 col-lg-8">
        <div class="card card-custom">
            <div class="card-header bg-white py-3">
                <h6 class="fw-bold mb-0 text-dark"><i class="fa-solid fa-table me-2 text-secondary"></i> Rincian Penerimaan Per Kategori Kendaraan (Tabel detail_setoran)</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Kategori Kendaraan</th>
                                <th class="text-end">Tunai (Rp)</th>
                                <th class="text-end">QRIS (Rp)</th>
                                <th class="text-end">Prepaid (Rp)</th>
                                <th class="text-end">Total (Rp)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($details)): ?>
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">Belum ada rincian data tersimpan.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($details as $d): ?>
                                    <tr class="<?= $d['nama_kendaraan'] == 'Total' ? 'table-light fw-bold' : '' ?>">
                                        <td>
                                            <?php if ($d['nama_kendaraan'] == 'Total'): ?>
                                                <span class="text-uppercase text-danger fw-bold">TOTAL KESELURUHAN</span>
                                            <?php else: ?>
                                                <i class="fa-solid fa-car-side me-2 text-muted"></i><?= htmlspecialchars($d['nama_kendaraan']) ?>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-end text-success">Rp <?= number_format($d['tunai']) ?></td>
                                        <td class="text-end text-info">Rp <?= number_format($d['qris']) ?></td>
                                        <td class="text-end text-secondary">Rp <?= number_format($d['prepaid']) ?></td>
                                        <td class="text-end fw-bold text-primary">Rp <?= number_format($d['total']) ?></td>
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

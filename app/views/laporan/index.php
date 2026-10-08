<?php require_once '../app/views/layouts/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4 no-print">
    <div>
        <h4 class="fw-bold mb-0">Laporan Transaksi & Pendapatan Parkir</h4>
        <p class="text-muted small mb-0">Rekapitulasi pendapatan, jurnal histori transaksi kendaraan, dan log pembatalan transaksi (VOID).</p>
    </div>
    <button onclick="window.print()" class="btn btn-outline-primary fw-bold">
        <i class="fa-solid fa-print me-1"></i> Cetak Laporan
    </button>
</div>

<!-- Filter Form Card -->
<div class="card card-custom mb-4 no-print">
    <div class="card-body">
        <form action="<?= BASE_URL ?>/laporan" method="GET" class="row g-3 align-items-end">
            <input type="hidden" name="tab" value="<?= htmlspecialchars($tab) ?>">
            <div class="col-12 col-md-3">
                <label for="tgl_mulai" class="form-label fw-semibold small">Tanggal Mulai</label>
                <input type="date" class="form-control" id="tgl_mulai" name="tgl_mulai" value="<?= htmlspecialchars($tgl_mulai) ?>">
            </div>
            <div class="col-12 col-md-3">
                <label for="tgl_akhir" class="form-label fw-semibold small">Tanggal Akhir</label>
                <input type="date" class="form-control" id="tgl_akhir" name="tgl_akhir" value="<?= htmlspecialchars($tgl_akhir) ?>">
            </div>
            <?php if ($tab !== 'void'): ?>
                <div class="col-12 col-md-2">
                    <label for="status" class="form-label fw-semibold small">Status Parkir</label>
                    <select class="form-select" id="status" name="status">
                        <option value="ALL" <?= $status == 'ALL' ? 'selected' : '' ?>>Semua Status</option>
                        <option value="B" <?= $status == 'B' ? 'selected' : '' ?>>Parkir Aktif (B)</option>
                        <option value="S" <?= $status == 'S' ? 'selected' : '' ?>>Sudah Keluar (S)</option>
                        <option value="N" <?= $status == 'N' ? 'selected' : '' ?>>Dibatalkan (VOID)</option>
                    </select>
                </div>
                <div class="col-12 col-md-2">
                    <label for="jn_kendaraan" class="form-label fw-semibold small">Jenis Kendaraan</label>
                    <select class="form-select" id="jn_kendaraan" name="jn_kendaraan">
                        <option value="ALL" <?= $jn_kendaraan == 'ALL' ? 'selected' : '' ?>>Semua Jenis</option>
                        <?php foreach ($jenis_list as $j): ?>
                            <option value="<?= htmlspecialchars($j['nama']) ?>" <?= $jn_kendaraan == $j['nama'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($j['nama']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            <?php endif; ?>
            <div class="col-12 col-md-2">
                <button type="submit" class="btn btn-primary w-100 fw-bold">
                    <i class="fa-solid fa-filter me-1"></i> Terapkan Filter
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Summary Stat Widgets -->
<div class="row g-3 mb-4">
    <div class="col-12 col-sm-6 col-md-3">
        <div class="card card-custom p-3">
            <span class="text-muted small fw-bold">TOTAL TRANSAKSI</span>
            <h3 class="fw-bold text-dark mb-0"><?= number_format($summary['total_transaksi'] ?? 0) ?></h3>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-md-3">
        <div class="card card-custom p-3 border-start border-success border-4">
            <span class="text-muted small fw-bold">TOTAL PENDAPATAN</span>
            <h3 class="fw-bold text-success mb-0">Rp <?= number_format($summary['total_pendapatan'] ?? 0) ?></h3>
            <?php if (!empty($summary['total_denda']) && $summary['total_denda'] > 0): ?>
                <span class="text-muted small">Termasuk Denda Rp <?= number_format($summary['total_denda']) ?></span>
            <?php endif; ?>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-md-3">
        <div class="card card-custom p-3 border-start border-primary border-4">
            <span class="text-muted small fw-bold">KENDARAAN SELESAI</span>
            <h3 class="fw-bold text-primary mb-0"><?= number_format($summary['total_selesai'] ?? 0) ?></h3>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-md-3">
        <div class="card card-custom p-3 border-start border-danger border-4">
            <span class="text-muted small fw-bold">TRANSAKSI BATAL (VOID)</span>
            <h3 class="fw-bold text-danger mb-0"><?= number_format($summary['total_batal'] ?? 0) ?></h3>
        </div>
    </div>
</div>

<!-- Nav Tabs -->
<ul class="nav nav-pills mb-3 no-print">
    <li class="nav-item">
        <a class="nav-link <?= $tab !== 'void' ? 'active' : '' ?>" href="<?= BASE_URL ?>/laporan?tab=transaksi&tgl_mulai=<?= $tgl_mulai ?>&tgl_akhir=<?= $tgl_akhir ?>">
            <i class="fa-solid fa-list me-1"></i> Jurnal Transaksi Masuk/Keluar
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= $tab === 'void' ? 'active bg-danger text-white' : '' ?>" href="<?= BASE_URL ?>/laporan?tab=void&tgl_mulai=<?= $tgl_mulai ?>&tgl_akhir=<?= $tgl_akhir ?>">
            <i class="fa-solid fa-ban me-1"></i> Riwayat Pembatalan (VOID)
        </a>
    </li>
</ul>

<!-- TAB 1: Transaksi Normal -->
<?php if ($tab !== 'void'): ?>
    <div class="card card-custom printable-area">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span class="fw-bold">Rincian Transaksi (<?= htmlspecialchars($tgl_mulai) ?> s.d <?= htmlspecialchars($tgl_akhir) ?>)</span>
            <span class="badge bg-secondary"><?= count($reports) ?> Baris Data</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>No. Tiket</th>
                            <th>Nopol</th>
                            <th>Jenis</th>
                            <th>Gate In</th>
                            <th>Gate Out</th>
                            <th>Waktu Masuk</th>
                            <th>Waktu Keluar</th>
                            <th>Metode</th>
                            <th>Status</th>
                            <th class="text-end">Tarif + Denda (Rp)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($reports)): ?>
                            <tr>
                                <td colspan="10" class="text-center py-4 text-muted">Tidak ada transaksi parkir pada periode ini.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($reports as $r): ?>
                                <tr>
                                    <td><span class="badge bg-light text-dark border font-monospace"><?= htmlspecialchars($r['idtrx']) ?></span></td>
                                    <td class="fw-bold text-primary"><?= htmlspecialchars($r['nopol']) ?></td>
                                    <td><?= htmlspecialchars($r['jn_kendaraan']) ?></td>
                                    <td><span class="badge bg-light text-dark border"><?= htmlspecialchars($r['gate'] ?? '-') ?></span></td>
                                    <td><span class="badge bg-light text-dark border"><?= htmlspecialchars($r['gateout'] ?? '-') ?></span></td>
                                    <td class="small"><?= htmlspecialchars($r['tgl_masuk']) ?> <?= htmlspecialchars($r['jam_masuk']) ?></td>
                                    <td class="small"><?= $r['tgl_keluar'] ? htmlspecialchars($r['tgl_keluar'] . ' ' . $r['jam_keluar']) : '-' ?></td>
                                    <td><span class="badge bg-light text-dark border"><?= htmlspecialchars($r['cara_bayar'] ?: 'Tunai') ?></span></td>
                                    <td>
                                        <?php if ($r['status'] == 'S'): ?>
                                            <span class="badge bg-success">LUNAS</span>
                                        <?php elseif ($r['status'] == 'B'): ?>
                                            <span class="badge bg-warning text-dark">PARKIR</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">VOID</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-end fw-bold text-dark">
                                        Rp <?= number_format($r['tarif'] + ($r['denda'] ?? 0)) ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

<!-- TAB 2: Pembatalan / VOID -->
<?php else: ?>
    <div class="card card-custom border-danger printable-area">
        <div class="card-header bg-danger text-white d-flex justify-content-between align-items-center">
            <span class="fw-bold"><i class="fa-solid fa-ban me-2"></i> Log Audit Transaksi Dibatalkan (VOID)</span>
            <span class="badge bg-light text-dark"><?= count($void_reports) ?> Transaksi Dibatalkan</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>No. Tiket</th>
                            <th>Nopol</th>
                            <th>Jenis Kendaraan</th>
                            <th>Waktu Masuk</th>
                            <th>Waktu Pembatalan</th>
                            <th>Petugas / Supervisor</th>
                            <th>Alasan Pembatalan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($void_reports)): ?>
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <i class="fa-solid fa-circle-check fs-2 text-success d-block mb-2"></i>
                                    Tidak ada catatan pembatalan (VOID) pada rentang tanggal ini.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($void_reports as $v): ?>
                                <tr>
                                    <td><span class="badge bg-light text-dark border font-monospace"><?= htmlspecialchars($v['idtrx']) ?></span></td>
                                    <td class="fw-bold text-danger"><?= htmlspecialchars($v['nopol']) ?></td>
                                    <td><?= htmlspecialchars($v['jn_kendaraan']) ?></td>
                                    <td class="small text-muted"><?= htmlspecialchars($v['waktuMasuk']) ?></td>
                                    <td class="small fw-bold text-dark"><?= htmlspecialchars($v['waktubatal']) ?></td>
                                    <td class="fw-bold text-primary"><?= htmlspecialchars($v['executor_nama'] ?: 'Petugas') ?></td>
                                    <td><span class="badge bg-light text-dark border text-wrap text-start"><?= htmlspecialchars($v['ketbatal'] ?: '-') ?></span></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php require_once '../app/views/layouts/footer.php'; ?>

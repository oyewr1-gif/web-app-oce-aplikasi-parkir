<?php require_once '../app/views/layouts/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4 no-print">
    <div>
        <h4 class="fw-bold mb-0">Laporan Transaksi & Pendapatan Parkir</h4>
        <p class="text-muted small mb-0">Rekapitulasi pendapatan, jurnal histori transaksi kendaraan, galeri audit foto CCTV, dan log pembatalan transaksi (VOID).</p>
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

            <?php if ($tab === 'foto'): ?>
                <div class="col-12 col-md-4">
                    <label for="kw_foto" class="form-label fw-semibold small">Cari Tiket / Plat Nomor</label>
                    <input type="text" class="form-control text-uppercase" id="kw_foto" name="kw_foto" value="<?= htmlspecialchars($kw_foto ?? '') ?>" placeholder="Ketik No. Tiket atau Plat...">
                </div>
            <?php elseif ($tab !== 'void'): ?>
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

<!-- Nav Tabs: 3 Tabs (Jurnal, Audit Foto, VOID) -->
<ul class="nav nav-pills mb-3 no-print">
    <li class="nav-item">
        <a class="nav-link <?= $tab === 'transaksi' || empty($tab) ? 'active' : '' ?>" href="<?= BASE_URL ?>/laporan?tab=transaksi&tgl_mulai=<?= $tgl_mulai ?>&tgl_akhir=<?= $tgl_akhir ?>">
            <i class="fa-solid fa-list me-1"></i> Jurnal Transaksi
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= $tab === 'foto' ? 'active bg-primary text-white' : '' ?>" href="<?= BASE_URL ?>/laporan?tab=foto&tgl_mulai=<?= $tgl_mulai ?>&tgl_akhir=<?= $tgl_akhir ?>">
            <i class="fa-solid fa-images me-1"></i> Audit Foto Kendaraan 
            <span class="badge bg-light text-dark ms-1"><?= number_format($total_photo_audit) ?></span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?= $tab === 'void' ? 'active bg-danger text-white' : '' ?>" href="<?= BASE_URL ?>/laporan?tab=void&tgl_mulai=<?= $tgl_mulai ?>&tgl_akhir=<?= $tgl_akhir ?>">
            <i class="fa-solid fa-ban me-1"></i> Riwayat Pembatalan (VOID)
        </a>
    </li>
</ul>

<!-- TAB 1: Transaksi Normal -->
<?php if ($tab === 'transaksi' || empty($tab)): ?>
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
                                    <td class="fw-bold text-primary"><?= htmlspecialchars($r['nopol'] ?: '-') ?></td>
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

<!-- TAB 2: AUDIT FOTO KENDARAAN (KOMPARASI MASUK VS KELUAR) -->
<?php elseif ($tab === 'foto'): ?>
    <div class="card card-custom shadow-sm border-0 mb-4 printable-area">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom">
            <div>
                <span class="fw-bold text-dark"><i class="fa-solid fa-camera text-primary me-2"></i> Galeri Audit Foto CCTV Masuk vs Keluar</span>
                <span class="text-muted small ms-2">(Menampilkan <?= count($photo_audit) ?> dari <?= number_format($total_photo_audit) ?> rekaman)</span>
            </div>
            <div>
                <span class="badge bg-info text-dark font-monospace"><i class="fa-solid fa-circle-info me-1"></i> Klik foto untuk memperbesar</span>
            </div>
        </div>
        <div class="card-body p-3 bg-light">
            <?php if (empty($photo_audit)): ?>
                <div class="text-center py-5 text-muted bg-white rounded border">
                    <i class="fa-solid fa-images fs-1 text-secondary d-block mb-3"></i>
                    <h5>Tidak Ada Data Rekaman Foto</h5>
                    <p class="small text-muted mb-0">Tidak ditemukan transaksi dengan rekaman snapshot foto pada kriteria pencarian ini.</p>
                </div>
            <?php else: ?>
                <div class="row g-3">
                    <?php foreach ($photo_audit as $p): ?>
                        <div class="col-12 col-xl-6">
                            <div class="card card-custom h-100 shadow-sm border bg-white">
                                <!-- Card Header: Info Transaksi -->
                                <div class="card-header bg-white py-2 border-bottom d-flex justify-content-between align-items-center flex-wrap gap-1">
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="badge bg-dark font-monospace"><?= htmlspecialchars($p['idtrx']) ?></span>
                                        <span class="fw-bold text-primary fs-6"><?= htmlspecialchars($p['nopol'] ?: 'TANPA NOPOL') ?></span>
                                        <span class="badge bg-secondary small"><?= htmlspecialchars($p['jn_kendaraan']) ?></span>
                                    </div>
                                    <div>
                                        <?php if ($p['status'] === 'S'): ?>
                                            <span class="badge bg-success">Lunas (S)</span>
                                        <?php elseif ($p['status'] === 'B'): ?>
                                            <span class="badge bg-warning text-dark">Parkir (B)</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">Void (N)</span>
                                        <?php endif; ?>
                                        <span class="small text-muted ms-1 font-monospace"><?= htmlspecialchars($p['durasi'] ?: '-') ?></span>
                                    </div>
                                </div>

                                <!-- Card Body: Komparasi Foto 2 Sisi -->
                                <div class="card-body p-3">
                                    <div class="row g-2 text-center">
                                        <!-- SISI MASUK (GATE IN) -->
                                        <div class="col-6 border-end">
                                            <div class="small fw-bold text-success mb-1 text-uppercase" style="font-size: 0.75rem;">
                                                <i class="fa-solid fa-right-to-bracket me-1"></i> Saat Masuk (<?= htmlspecialchars($p['gate'] ?? 'IN') ?>)
                                            </div>
                                            <div class="small text-muted mb-2" style="font-size: 0.72rem;"><?= htmlspecialchars($p['waktuMasuk']) ?></div>
                                            
                                            <div class="row g-1">
                                                <div class="col-6">
                                                    <div class="border rounded overflow-hidden bg-dark position-relative" style="cursor: pointer;" onclick="showZoomModal('<?= htmlspecialchars($p['idtrx']) ?> - Kendaraan Masuk', '<?= BASE_URL ?>/kamera/foto?idtrx=<?= urlencode($p['idtrx']) ?>&nopol=<?= urlencode($p['nopol'] ?? '') ?>&label=IN_KENDARAAN&path=<?= urlencode($p['foto_masuk1'] ?? '') ?>&time=<?= urlencode($p['waktuMasuk']) ?>')">
                                                        <img src="<?= BASE_URL ?>/kamera/foto?idtrx=<?= urlencode($p['idtrx']) ?>&nopol=<?= urlencode($p['nopol'] ?? '') ?>&label=IN_KENDARAAN&path=<?= urlencode($p['foto_masuk1'] ?? '') ?>&time=<?= urlencode($p['waktuMasuk']) ?>" alt="Plat Masuk" class="img-fluid" style="height: 85px; width: 100%; object-fit: cover;">
                                                        <div class="position-absolute bottom-0 start-0 w-100 bg-dark bg-opacity-75 text-white small" style="font-size: 0.65rem;">Kendaraan</div>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="border rounded overflow-hidden bg-dark position-relative" style="cursor: pointer;" onclick="showZoomModal('<?= htmlspecialchars($p['idtrx']) ?> - Driver Masuk', '<?= BASE_URL ?>/kamera/foto?idtrx=<?= urlencode($p['idtrx']) ?>&nopol=<?= urlencode($p['nopol'] ?? '') ?>&label=IN_DRIVER&path=<?= urlencode($p['foto_masuk2'] ?? '') ?>&time=<?= urlencode($p['waktuMasuk']) ?>')">
                                                        <img src="<?= BASE_URL ?>/kamera/foto?idtrx=<?= urlencode($p['idtrx']) ?>&nopol=<?= urlencode($p['nopol'] ?? '') ?>&label=IN_DRIVER&path=<?= urlencode($p['foto_masuk2'] ?? '') ?>&time=<?= urlencode($p['waktuMasuk']) ?>" alt="Driver Masuk" class="img-fluid" style="height: 85px; width: 100%; object-fit: cover;">
                                                        <div class="position-absolute bottom-0 start-0 w-100 bg-dark bg-opacity-75 text-white small" style="font-size: 0.65rem;">Wajah Driver</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- SISI KELUAR (GATE OUT) -->
                                        <div class="col-6">
                                            <div class="small fw-bold text-danger mb-1 text-uppercase" style="font-size: 0.75rem;">
                                                <i class="fa-solid fa-right-from-bracket me-1"></i> Saat Keluar (<?= htmlspecialchars($p['gateout'] ?? 'OUT') ?>)
                                            </div>
                                            <div class="small text-muted mb-2" style="font-size: 0.72rem;"><?= htmlspecialchars($p['waktuKeluar'] ?: 'Sedang Parkir') ?></div>

                                            <?php if ($p['status'] === 'S'): ?>
                                                <div class="row g-1">
                                                    <div class="col-6">
                                                        <div class="border rounded overflow-hidden bg-dark position-relative" style="cursor: pointer;" onclick="showZoomModal('<?= htmlspecialchars($p['idtrx']) ?> - Kendaraan Keluar', '<?= BASE_URL ?>/kamera/foto?idtrx=<?= urlencode($p['idtrx']) ?>&nopol=<?= urlencode($p['nopol'] ?? '') ?>&label=OUT_KENDARAAN&path=<?= urlencode($p['foto_out'] ?? '') ?>&time=<?= urlencode($p['waktuKeluar']) ?>')">
                                                            <img src="<?= BASE_URL ?>/kamera/foto?idtrx=<?= urlencode($p['idtrx']) ?>&nopol=<?= urlencode($p['nopol'] ?? '') ?>&label=OUT_KENDARAAN&path=<?= urlencode($p['foto_out'] ?? '') ?>&time=<?= urlencode($p['waktuKeluar']) ?>" alt="Plat Keluar" class="img-fluid" style="height: 85px; width: 100%; object-fit: cover;">
                                                            <div class="position-absolute bottom-0 start-0 w-100 bg-dark bg-opacity-75 text-white small" style="font-size: 0.65rem;">Kendaraan</div>
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="border rounded overflow-hidden bg-dark position-relative" style="cursor: pointer;" onclick="showZoomModal('<?= htmlspecialchars($p['idtrx']) ?> - Driver Keluar', '<?= BASE_URL ?>/kamera/foto?idtrx=<?= urlencode($p['idtrx']) ?>&nopol=<?= urlencode($p['nopol'] ?? '') ?>&label=OUT_DRIVER&path=<?= urlencode($p['foto_out2'] ?? '') ?>&time=<?= urlencode($p['waktuKeluar']) ?>')">
                                                            <img src="<?= BASE_URL ?>/kamera/foto?idtrx=<?= urlencode($p['idtrx']) ?>&nopol=<?= urlencode($p['nopol'] ?? '') ?>&label=OUT_DRIVER&path=<?= urlencode($p['foto_out2'] ?? '') ?>&time=<?= urlencode($p['waktuKeluar']) ?>" alt="Driver Keluar" class="img-fluid" style="height: 85px; width: 100%; object-fit: cover;">
                                                            <div class="position-absolute bottom-0 start-0 w-100 bg-dark bg-opacity-75 text-white small" style="font-size: 0.65rem;">Wajah Driver</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php else: ?>
                                                <div class="d-flex flex-column justify-content-center align-items-center py-4 bg-light rounded text-muted">
                                                    <i class="fa-solid fa-clock-rotate-left mb-1"></i>
                                                    <span class="small">Belum Selesai Keluar</span>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

<!-- TAB 3: Pembatalan / VOID -->
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
                                    <td class="fw-bold text-danger"><?= htmlspecialchars($v['nopol'] ?: '-') ?></td>
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

<!-- Modal Zoom Foto Lightbox -->
<div class="modal fade" id="modalZoomFoto" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white py-2">
                <h6 class="modal-title fw-bold" id="zoomModalTitle"><i class="fa-solid fa-camera me-2 text-warning"></i> Inspeksi Foto</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0 text-center bg-black">
                <img id="zoomModalImage" src="" class="img-fluid w-100" style="max-height: 520px; object-fit: contain;" alt="Zoom Foto">
            </div>
            <div class="modal-footer py-2 bg-light d-flex justify-content-between">
                <span class="small text-muted font-monospace"><i class="fa-solid fa-circle-info me-1"></i> Klik atau gunakan tombol Esc untuk menutup</span>
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
function showZoomModal(title, url) {
    document.getElementById('zoomModalTitle').innerHTML = '<i class="fa-solid fa-camera me-2 text-warning"></i> ' + title;
    document.getElementById('zoomModalImage').src = url;
    const modal = new bootstrap.Modal(document.getElementById('modalZoomFoto'));
    modal.show();
}
</script>

<?php require_once '../app/views/layouts/footer.php'; ?>

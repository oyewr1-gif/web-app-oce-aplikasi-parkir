<?php require_once '../app/views/layouts/header.php'; ?>

<!-- Top Tab Navigation: Kasir vs Antrean -->
<ul class="nav nav-pills mb-3 border-bottom pb-2" id="parkirTab" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link fw-bold <?= ($trx && $calc) || empty($_GET['tab']) || $_GET['tab'] !== 'antrean' ? 'active' : '' ?>" id="kasir-tab" data-bs-toggle="pill" data-bs-target="#tab-kasir" type="button" role="tab">
            <i class="fa-solid fa-cash-register me-1"></i> Kasir / Checkout Keluar
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link fw-bold <?= isset($_GET['tab']) && $_GET['tab'] === 'antrean' ? 'active' : '' ?>" id="antrean-tab" data-bs-toggle="pill" data-bs-target="#tab-antrean" type="button" role="tab">
            <i class="fa-solid fa-car me-1"></i> Kendaraan Sedang Parkir 
            <span class="badge bg-secondary ms-1"><?= count($active_list) ?></span>
        </button>
    </li>
</ul>

<div class="tab-content" id="parkirTabContent">
    <!-- TAB 1: KASIR CHECKOUT KELUAR -->
    <div class="tab-pane fade <?= ($trx && $calc) || empty($_GET['tab']) || $_GET['tab'] !== 'antrean' ? 'show active' : '' ?>" id="tab-kasir" role="tabpanel">
        
        <!-- Scanner Bar Ramping (1 Baris Horizontal) -->
        <div class="card card-custom mb-3 bg-light border-0 shadow-sm">
            <div class="card-body p-2 p-md-3">
                <form action="<?= BASE_URL ?>/parkir/keluar" method="GET" class="row g-2 align-items-center">
                    <div class="col-auto d-none d-md-block">
                        <span class="fw-bold text-dark"><i class="fa-solid fa-barcode fs-5 me-1 text-warning"></i> Scan / Cari:</span>
                    </div>
                    <div class="col">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                            <input type="text" class="form-control text-uppercase fw-bold border-start-0" id="keyword" name="keyword" value="<?= htmlspecialchars($keyword ?? '') ?>" placeholder="Scan barcode karcis / ketik nopol / nomor tiket..." autocomplete="off" <?= !($trx && $calc) ? 'autofocus' : '' ?>>
                            <button class="btn btn-warning px-4 fw-bold" type="submit">Cari (Enter)</button>
                        </div>
                    </div>
                    <?php if ($trx && $calc): ?>
                        <div class="col-auto">
                            <a href="<?= BASE_URL ?>/parkir/keluar" class="btn btn-outline-secondary fw-semibold" title="Reset / Transaksi Baru">
                                <i class="fa-solid fa-rotate-left me-1"></i> Reset
                            </a>
                        </div>
                    <?php endif; ?>
                </form>
            </div>
        </div>

        <?php Session::flash(); ?>

        <?php if ($trx && $calc): ?>
            <!-- FORM CHECKOUT 1 HALAMAN: 2 PANEL SEJAJAR -->
            <form action="<?= BASE_URL ?>/parkir/prosesKeluar" method="POST" id="formCheckout">
                <input type="hidden" name="idtrx" value="<?= htmlspecialchars($trx['idtrx']) ?>">
                <input type="hidden" id="baseTarif" value="<?= $calc['tarif'] ?>">
                <input type="hidden" name="bayar" id="inputBayar" value="<?= $calc['tarif'] ?>">

                <div class="row g-3 align-items-stretch">
                    <!-- PANEL KIRI: Data Tiket & Kendaraan -->
                    <div class="col-12 col-lg-6">
                        <div class="card card-custom h-100 shadow-sm border-0">
                            <div class="card-header bg-light py-2 d-flex justify-content-between align-items-center">
                                <span class="fw-bold text-primary small"><i class="fa-solid fa-receipt me-1"></i> Data Tiket & Kendaraan</span>
                                <button type="button" class="btn btn-xs btn-outline-danger py-0 px-2" style="font-size: 0.75rem;" data-bs-toggle="modal" data-bs-target="#modalVoid<?= $trx['id'] ?>">
                                    <i class="fa-solid fa-ban me-1"></i> Void
                                </button>
                            </div>
                            <div class="card-body p-3">
                                <table class="table table-sm table-borderless mb-2">
                                    <tbody>
                                        <tr>
                                            <td class="text-muted small py-1" width="35%">No. Tiket</td>
                                            <td class="fw-bold font-monospace text-dark py-1 text-end"><?= htmlspecialchars($trx['idtrx']) ?></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted small py-1 align-middle">Nomor Polisi</td>
                                            <td class="py-1 text-end">
                                                <?php if (!empty($trx['nopol'])): ?>
                                                    <span class="fw-bold text-primary fs-5"><?= htmlspecialchars($trx['nopol']) ?></span>
                                                    <input type="hidden" name="nopol_update" value="<?= htmlspecialchars($trx['nopol']) ?>">
                                                <?php else: ?>
                                                    <input type="text" name="nopol_update" class="form-control form-control-sm text-uppercase fw-bold text-end d-inline-block border-secondary" style="max-width: 180px;" placeholder="Ketik Plat (Opsional)" autocomplete="off">
                                                    <div class="small text-muted" style="font-size: 0.75rem;">Plat kosong saat masuk (opsional)</div>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted small py-1">Jenis Kendaraan</td>
                                            <td class="fw-semibold py-1 text-end"><?= htmlspecialchars($trx['jn_kendaraan']) ?></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted small py-1">Gate Masuk</td>
                                            <td class="py-1 text-end text-success fw-semibold small"><?= htmlspecialchars($trx['gate'] ?? 'MAN R4') ?></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted small py-1">Waktu Masuk</td>
                                            <td class="py-1 text-end small"><?= htmlspecialchars($trx['waktuMasuk']) ?></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted small py-1">Waktu Keluar</td>
                                            <td class="py-1 text-end small"><?= htmlspecialchars($calc['waktu_keluar']) ?></td>
                                        </tr>
                                        <tr class="table-light border-top">
                                            <td class="fw-bold py-1">Durasi Parkir</td>
                                            <td class="fw-bold py-1 text-end text-dark"><?= htmlspecialchars($calc['durasi_str']) ?> (<?= $calc['durasi_jam'] ?> Jam)</td>
                                        </tr>
                                    </tbody>
                                </table>

                                <?php if (isset($calc['is_member']) && $calc['is_member']): ?>
                                    <div class="alert alert-info py-1 px-2 mb-2 small text-center fw-bold">
                                        <i class="fa-solid fa-circle-check me-1"></i> Member Parkir Aktif (GRATIS)
                                    </div>
                                <?php endif; ?>

                                <!-- Snapshot Foto Masuk (Mini Thumbnails) -->
                                <div class="mt-2 pt-2 border-top">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span class="small fw-bold text-secondary"><i class="fa-solid fa-camera me-1"></i> Snapshot Foto Masuk:</span>
                                        <span class="badge bg-light text-muted border" style="font-size: 0.7rem;">Gate: <?= htmlspecialchars($trx['gate'] ?? 'MAN R4') ?></span>
                                    </div>
                                    <div class="row g-2">
                                        <div class="col-6">
                                            <div class="position-relative border rounded overflow-hidden bg-dark text-center" style="cursor: pointer;" onclick="showZoomModal('Snapshot Kendaraan Masuk', '<?= BASE_URL ?>/kamera/foto?idtrx=<?= urlencode($trx['idtrx']) ?>&nopol=<?= urlencode($trx['nopol'] ?? '') ?>&label=IN_KENDARAAN&path=<?= urlencode($photos['foto_masuk1'] ?? '') ?>&time=<?= urlencode($trx['waktuMasuk']) ?>')">
                                                <img src="<?= BASE_URL ?>/kamera/foto?idtrx=<?= urlencode($trx['idtrx']) ?>&nopol=<?= urlencode($trx['nopol'] ?? '') ?>&label=IN_KENDARAAN&path=<?= urlencode($photos['foto_masuk1'] ?? '') ?>&time=<?= urlencode($trx['waktuMasuk']) ?>" alt="Foto Kendaraan" class="img-fluid" style="height: 72px; width: 100%; object-fit: cover;">
                                                <div class="position-absolute bottom-0 start-0 w-100 bg-dark bg-opacity-75 text-white py-0 small text-truncate" style="font-size: 0.68rem;">
                                                    <i class="fa-solid fa-car me-1"></i> Kendaraan / Plat
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="position-relative border rounded overflow-hidden bg-dark text-center" style="cursor: pointer;" onclick="showZoomModal('Snapshot Pengemudi Masuk', '<?= BASE_URL ?>/kamera/foto?idtrx=<?= urlencode($trx['idtrx']) ?>&nopol=<?= urlencode($trx['nopol'] ?? '') ?>&label=IN_DRIVER&path=<?= urlencode($photos['foto_masuk2'] ?? '') ?>&time=<?= urlencode($trx['waktuMasuk']) ?>')">
                                                <img src="<?= BASE_URL ?>/kamera/foto?idtrx=<?= urlencode($trx['idtrx']) ?>&nopol=<?= urlencode($trx['nopol'] ?? '') ?>&label=IN_DRIVER&path=<?= urlencode($photos['foto_masuk2'] ?? '') ?>&time=<?= urlencode($trx['waktuMasuk']) ?>" alt="Foto Pengemudi" class="img-fluid" style="height: 72px; width: 100%; object-fit: cover;">
                                                <div class="position-absolute bottom-0 start-0 w-100 bg-dark bg-opacity-75 text-white py-0 small text-truncate" style="font-size: 0.68rem;">
                                                    <i class="fa-solid fa-user me-1"></i> Wajah Driver
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Tiket Hilang & Denda (Collapsible Ringkas) -->
                                <div class="mt-2 pt-2 border-top">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="form-check form-switch mb-0">
                                            <input class="form-check-input" type="checkbox" id="checkLost" name="is_lost" value="1">
                                            <label class="form-check-label small fw-bold text-danger" for="checkLost">
                                                Karcis Hilang (+Denda)
                                            </label>
                                        </div>
                                        <span class="small text-muted" id="lostBadgeInfo">+Rp <?= number_format($calc['denda_lost_default'] ?? 10000) ?></span>
                                    </div>
                                    <div id="lostTicketDetails" style="display: none;" class="mt-2 p-2 bg-light rounded border">
                                        <div class="row g-2 mb-1">
                                            <div class="col-6">
                                                <label class="form-label small text-muted mb-0" style="font-size: 0.75rem;">Nominal Denda (Rp)</label>
                                                <input type="number" class="form-control form-control-sm" id="inputDenda" name="denda" value="<?= $calc['denda_lost_default'] ?? 10000 ?>">
                                            </div>
                                            <div class="col-6">
                                                <label class="form-label small text-muted mb-0" style="font-size: 0.75rem;">No. STNK</label>
                                                <input type="text" class="form-control form-control-sm" name="nostnk" placeholder="No. STNK">
                                            </div>
                                        </div>
                                        <div class="row g-2">
                                            <div class="col-6">
                                                <label class="form-label small text-muted mb-0" style="font-size: 0.75rem;">Nama Pemilik</label>
                                                <input type="text" class="form-control form-control-sm" name="nama_pemilik" placeholder="Nama Pemilik">
                                            </div>
                                            <div class="col-6">
                                                <label class="form-label small text-muted mb-0" style="font-size: 0.75rem;">No. HP / KTP</label>
                                                <input type="text" class="form-control form-control-sm" name="nohp" placeholder="No HP/KTP">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- PANEL KANAN: Kasir Pembayaran & Tombol Eksekusi Cepat -->
                    <div class="col-12 col-lg-6">
                        <div class="card card-custom h-100 shadow-sm border-warning d-flex flex-column justify-content-between">
                            <div class="card-header bg-warning text-dark py-2 d-flex justify-content-between align-items-center">
                                <span class="fw-bold"><i class="fa-solid fa-coins me-1"></i> Kasir Pembayaran</span>
                                <!-- Gate Keluar Selection dibuat ringkas di header -->
                                <div class="d-flex align-items-center">
                                    <span class="small me-1 text-dark-50 fw-bold">Gate:</span>
                                    <select class="form-select form-select-sm py-0 ps-2 pe-4 fw-bold" id="gateout" name="gateout" style="max-width: 140px; font-size: 0.8rem;">
                                        <?php foreach ($pos_list as $pos): ?>
                                            <option value="<?= htmlspecialchars($pos['nama']) ?>" <?= ($trx['gateout'] ?? '') == $pos['nama'] ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($pos['nama']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="card-body p-3 d-flex flex-column justify-content-between">
                                <!-- TOTAL TAGIHAN DISPLAY BESAR -->
                                <div class="bg-dark text-white p-3 rounded mb-3 text-center shadow-sm">
                                    <div class="text-white-50 small fw-bold text-uppercase" style="letter-spacing: 1px;">TOTAL TAGIHAN PARKIR</div>
                                    <div class="display-5 fw-bold text-warning my-1" id="displayTotalTagihan">Rp <?= number_format($calc['tarif']) ?></div>
                                    <div class="badge bg-success px-3 py-1 font-monospace" id="badgeStatusBayar">
                                        <i class="fa-solid fa-check me-1"></i> UANG PAS (OTOMATIS)
                                    </div>
                                </div>

                                <!-- METODE BAYAR & HITUNG KEMBALIAN (OPSIONAL) -->
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span class="small fw-bold text-secondary">Metode Bayar:</span>
                                        <button type="button" class="btn btn-link btn-sm p-0 text-decoration-none small" id="toggleOpsiBayar">
                                            <i class="fa-solid fa-sliders me-1"></i> Opsi Lanjutan / Kembalian
                                        </button>
                                    </div>
                                    
                                    <div class="btn-group w-100 btn-group-sm" role="group">
                                        <input type="radio" class="btn-check" name="cara_bayar" id="payTunai" value="Tunai" checked>
                                        <label class="btn btn-outline-primary fw-bold" for="payTunai"><i class="fa-solid fa-money-bill-wave me-1"></i> Tunai</label>

                                        <input type="radio" class="btn-check" name="cara_bayar" id="payQris" value="QRIS">
                                        <label class="btn btn-outline-info fw-bold" for="payQris"><i class="fa-solid fa-qrcode me-1"></i> QRIS</label>

                                        <input type="radio" class="btn-check" name="cara_bayar" id="payPrepaid" value="Prepaid">
                                        <label class="btn btn-outline-secondary fw-bold" for="payPrepaid"><i class="fa-solid fa-credit-card me-1"></i> E-Money</label>
                                    </div>

                                    <!-- Panel Detail Bayar Manual (Default Hidden) -->
                                    <div id="panelManualBayar" style="display: none;" class="mt-2 p-2 bg-light rounded border">
                                        <div id="boxRefBayar" style="display: none;" class="mb-2">
                                            <label class="form-label small fw-bold mb-1" style="font-size: 0.75rem;">No. Referensi / Trace ID</label>
                                            <input type="text" class="form-control form-control-sm" name="refbayar" placeholder="Nomor Reff Transaksi">
                                        </div>

                                        <div id="boxManualTunai">
                                            <div class="row g-2 align-items-center">
                                                <div class="col-6">
                                                    <label class="form-label small fw-bold mb-0" style="font-size: 0.75rem;">Uang Diterima (Rp)</label>
                                                    <input type="number" class="form-control form-control-sm fw-bold text-end" id="inputBayarManual" value="<?= $calc['tarif'] ?>" min="<?= $calc['tarif'] ?>" step="500">
                                                </div>
                                                <div class="col-6">
                                                    <label class="form-label small text-muted mb-0" style="font-size: 0.75rem;">Kembalian</label>
                                                    <div id="displayKembalian" class="fw-bold text-success fs-6 text-end pt-1">Rp 0</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- TOMBOL UTAMA SUPER CEPAT (FOKUS UTAMA) -->
                                <div>
                                    <button type="submit" id="btnProsesKeluar" class="btn btn-warning btn-lg w-100 fw-bold py-3 shadow text-dark fs-5 border border-2 border-dark" autofocus>
                                        <i class="fa-solid fa-print me-2"></i> SELESAIKAN & CETAK STRUK <span class="badge bg-dark text-warning ms-2 small fs-6 font-monospace">↵ ENTER</span>
                                    </button>
                                    <div class="text-center mt-2 small text-muted" style="font-size: 0.8rem;">
                                        <i class="fa-solid fa-keyboard me-1"></i> Tekan tombol <strong>[Enter]</strong> pada keyboard untuk langsung checkout
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <!-- Modal Void Transaksi Aktif -->
            <div class="modal fade" id="modalVoid<?= $trx['id'] ?>" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form action="<?= BASE_URL ?>/parkir/void" method="POST">
                            <input type="hidden" name="idtrx" value="<?= htmlspecialchars($trx['idtrx']) ?>">
                            <div class="modal-header bg-danger text-white">
                                <h5 class="modal-title"><i class="fa-solid fa-ban me-2"></i> Batalkan Transaksi (VOID)</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <p class="mb-2">Anda yakin ingin membatalkan transaksi berikut?</p>
                                <div class="bg-light p-2 rounded border mb-3 small">
                                    <div><strong>No. Tiket:</strong> <?= htmlspecialchars($trx['idtrx']) ?></div>
                                    <div><strong>Plat Nomor:</strong> <?= htmlspecialchars(!empty($trx['nopol']) ? $trx['nopol'] : 'Tanpa Nopol') ?></div>
                                    <div><strong>Jenis:</strong> <?= htmlspecialchars($trx['jn_kendaraan']) ?></div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Alasan Pembatalan (Wajib Diisi)</label>
                                    <textarea class="form-control" name="ketbatal" rows="3" placeholder="Contoh: Salah input nopol kendaraan / kendaraan tidak jadi parkir" required></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                <button type="submit" class="btn btn-danger fw-bold">Batalkan Transaksi (VOID)</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        <?php else: ?>
            <!-- TAMPILAN AWAL (BELUM ADA TIKET DI-SCAN) -->
            <div class="card card-custom text-center py-5 border-0 shadow-sm bg-white">
                <div class="card-body">
                    <div class="mb-3">
                        <span class="d-inline-flex p-3 rounded-circle bg-warning bg-opacity-10 text-warning">
                            <i class="fa-solid fa-barcode fs-1"></i>
                        </span>
                    </div>
                    <h4 class="fw-bold text-dark mb-1">Siap Memproses Kendaraan Keluar</h4>
                    <p class="text-muted mb-4" style="max-width: 500px; margin: 0 auto;">
                        Scan barcode karcis parkir atau ketik nomor plat/tiket pada kolom pencarian di atas, lalu tekan <strong class="text-dark">Enter</strong>.
                    </p>
                    <div class="d-flex justify-content-center gap-2">
                        <button type="button" class="btn btn-outline-primary" onclick="document.getElementById('antrean-tab').click();">
                            <i class="fa-solid fa-car me-1"></i> Pilih Dari Kendaraan Sedang Parkir (<?= count($active_list) ?>)
                        </button>
                    </div>
                </div>
            </div>
        <?php endif; ?>

    </div>

    <!-- TAB 2: DAFTAR KENDARAAN SEDANG PARKIR -->
    <div class="tab-pane fade <?= isset($_GET['tab']) && $_GET['tab'] === 'antrean' ? 'show active' : '' ?>" id="tab-antrean" role="tabpanel">
        <div class="card card-custom shadow-sm border-0">
            <div class="card-header bg-light d-flex justify-content-between align-items-center py-3">
                <span class="fw-bold"><i class="fa-solid fa-list me-1"></i> Daftar Kendaraan Sedang Parkir</span>
                <span class="badge bg-primary fs-6"><?= count($active_list) ?> Kendaraan Aktif</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive" style="max-height: 550px;">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light sticky-top">
                            <tr>
                                <th>No. Tiket</th>
                                <th>Nopol</th>
                                <th>Jenis Kendaraan</th>
                                <th>Gate Masuk</th>
                                <th>Waktu Masuk</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($active_list)): ?>
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">
                                        <i class="fa-solid fa-circle-check fs-2 text-success d-block mb-2"></i>
                                        Tidak ada kendaraan yang sedang parkir saat ini.
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($active_list as $row): ?>
                                    <tr>
                                        <td><span class="font-monospace fw-bold text-dark small"><?= htmlspecialchars($row['idtrx']) ?></span></td>
                                        <td>
                                            <?php if (!empty($row['nopol'])): ?>
                                                <span class="badge bg-dark fs-6"><?= htmlspecialchars($row['nopol']) ?></span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary fs-6 text-white-50"><i class="fa-solid fa-minus me-1"></i>Tanpa Nopol</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= htmlspecialchars($row['jn_kendaraan']) ?></td>
                                        <td><span class="badge bg-light text-dark border"><?= htmlspecialchars($row['gate'] ?? 'POS-IN-01') ?></span></td>
                                        <td class="small text-muted"><?= htmlspecialchars($row['waktuMasuk']) ?></td>
                                        <td class="text-end">
                                            <div class="btn-group btn-group-sm">
                                                <a href="<?= BASE_URL ?>/parkir/keluar?keyword=<?= urlencode($row['idtrx']) ?>" class="btn btn-warning fw-bold" title="Proses Keluar">
                                                    <i class="fa-solid fa-calculator me-1"></i> Proses Keluar
                                                </a>
                                                <button class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#modalVoidRow<?= $row['id'] ?>" title="Batalkan (Void)">
                                                    <i class="fa-solid fa-ban"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- Modal Void from Table Row -->
                                    <div class="modal fade" id="modalVoidRow<?= $row['id'] ?>" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form action="<?= BASE_URL ?>/parkir/void" method="POST">
                                                    <input type="hidden" name="idtrx" value="<?= htmlspecialchars($row['idtrx']) ?>">
                                                    <div class="modal-header bg-danger text-white">
                                                        <h5 class="modal-title"><i class="fa-solid fa-ban me-2"></i> Batalkan Transaksi (VOID)</h5>
                                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p class="mb-2">Anda yakin ingin membatalkan transaksi parkir ini?</p>
                                                        <div class="bg-light p-2 rounded border mb-3 small">
                                                            <div><strong>No. Tiket:</strong> <?= htmlspecialchars($row['idtrx']) ?></div>
                                                            <div><strong>Plat Nomor:</strong> <?= htmlspecialchars(!empty($row['nopol']) ? $row['nopol'] : 'Tanpa Nopol') ?></div>
                                                            <div><strong>Jenis:</strong> <?= htmlspecialchars($row['jn_kendaraan']) ?></div>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label fw-bold">Alasan Pembatalan</label>
                                                            <textarea class="form-control" name="ketbatal" rows="3" placeholder="Masukkan alasan pembatalan transaksi..." required></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                                        <button type="submit" class="btn btn-danger fw-bold">Batalkan (VOID)</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const btnProsesKeluar = document.getElementById('btnProsesKeluar');
    const keywordInput = document.getElementById('keyword');
    const formCheckout = document.getElementById('formCheckout');

    // Auto-Focus Logic
    if (btnProsesKeluar) {
        btnProsesKeluar.focus();
    } else if (keywordInput) {
        keywordInput.focus();
    }

    // Enter Key Quick Checkout
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' && formCheckout && btnProsesKeluar) {
            const activeElem = document.activeElement;
            const isTextfield = activeElem && (activeElem.tagName === 'INPUT' || activeElem.tagName === 'TEXTAREA') && activeElem.id !== 'keyword';
            // If user is focused on the search box, let search submit normally
            if (activeElem && activeElem.id === 'keyword') {
                return;
            }
            // If focused on main button or body, trigger checkout
            if (!isTextfield || activeElem === btnProsesKeluar) {
                e.preventDefault();
                btnProsesKeluar.click();
            }
        }
    });

    // Lost Ticket & Calculation Elements
    const checkLost = document.getElementById('checkLost');
    const lostDetails = document.getElementById('lostTicketDetails');
    const inputDenda = document.getElementById('inputDenda');
    const baseTarifInput = document.getElementById('baseTarif');
    const displayTotal = document.getElementById('displayTotalTagihan');
    const inputBayar = document.getElementById('inputBayar');
    const inputBayarManual = document.getElementById('inputBayarManual');
    const displayKembalian = document.getElementById('displayKembalian');
    const toggleOpsiBayar = document.getElementById('toggleOpsiBayar');
    const panelManualBayar = document.getElementById('panelManualBayar');
    const boxRefBayar = document.getElementById('boxRefBayar');
    const boxManualTunai = document.getElementById('boxManualTunai');
    const badgeStatusBayar = document.getElementById('badgeStatusBayar');
    const payRadios = document.querySelectorAll('input[name="cara_bayar"]');

    function calculateTotal() {
        const base = parseInt(baseTarifInput ? baseTarifInput.value : 0) || 0;
        let denda = 0;
        if (checkLost && checkLost.checked) {
            denda = parseInt(inputDenda ? inputDenda.value : 0) || 0;
        }
        const total = base + denda;

        if (displayTotal) {
            displayTotal.textContent = 'Rp ' + total.toLocaleString('id-ID');
        }

        // Automatic exact payment default
        if (inputBayar) {
            inputBayar.value = total;
        }
        if (inputBayarManual) {
            inputBayarManual.min = total;
            if (parseInt(inputBayarManual.value) < total) {
                inputBayarManual.value = total;
            }
        }

        updateKembalian(total);
    }

    function updateKembalian(total) {
        if (!inputBayarManual || !displayKembalian) return;
        const totalTagihan = total !== undefined ? total : ((parseInt(baseTarifInput?.value) || 0) + ((checkLost?.checked ? parseInt(inputDenda?.value) : 0) || 0));
        const bayar = parseInt(inputBayarManual.value) || totalTagihan;
        const kembalian = bayar - totalTagihan;

        // Keep inputBayar synced with manual payment if changed
        if (inputBayar) {
            inputBayar.value = bayar;
        }

        if (kembalian >= 0) {
            displayKembalian.className = 'fw-bold text-success fs-6 text-end pt-1';
            displayKembalian.textContent = 'Rp ' + kembalian.toLocaleString('id-ID');
        } else {
            displayKembalian.className = 'fw-bold text-danger fs-6 text-end pt-1';
            displayKembalian.textContent = 'Kurang Rp ' + Math.abs(kembalian).toLocaleString('id-ID');
        }
    }

    if (checkLost) {
        checkLost.addEventListener('change', function() {
            if (this.checked) {
                lostDetails.style.display = 'block';
            } else {
                lostDetails.style.display = 'none';
            }
            calculateTotal();
        });
    }

    if (inputDenda) {
        inputDenda.addEventListener('input', calculateTotal);
    }

    if (inputBayarManual) {
        inputBayarManual.addEventListener('input', function() {
            updateKembalian();
        });
    }

    if (toggleOpsiBayar && panelManualBayar) {
        toggleOpsiBayar.addEventListener('click', function() {
            if (panelManualBayar.style.display === 'none') {
                panelManualBayar.style.display = 'block';
                toggleOpsiBayar.innerHTML = '<i class="fa-solid fa-chevron-up me-1"></i> Sembunyikan Opsi Lanjutan';
            } else {
                panelManualBayar.style.display = 'none';
                toggleOpsiBayar.innerHTML = '<i class="fa-solid fa-sliders me-1"></i> Opsi Lanjutan / Kembalian';
            }
        });
    }

    payRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.value === 'Tunai') {
                if (boxManualTunai) boxManualTunai.style.display = 'block';
                if (boxRefBayar) boxRefBayar.style.display = 'none';
                if (badgeStatusBayar) {
                    badgeStatusBayar.className = 'badge bg-success px-3 py-1 font-monospace';
                    badgeStatusBayar.innerHTML = '<i class="fa-solid fa-check me-1"></i> UANG PAS (OTOMATIS)';
                }
            } else {
                if (boxManualTunai) boxManualTunai.style.display = 'none';
                if (boxRefBayar) boxRefBayar.style.display = 'block';
                if (badgeStatusBayar) {
                    badgeStatusBayar.className = 'badge bg-info px-3 py-1 font-monospace text-dark';
                    badgeStatusBayar.innerHTML = '<i class="fa-solid fa-qrcode me-1"></i> ' + this.value.toUpperCase() + ' (LUNAS)';
                }
            }
            calculateTotal();
        });
    });
});

function showZoomModal(title, url) {
    document.getElementById('zoomModalTitle').innerHTML = '<i class="fa-solid fa-camera me-2 text-warning"></i> ' + title;
    document.getElementById('zoomModalImage').src = url;
    const modal = new bootstrap.Modal(document.getElementById('modalZoomFoto'));
    modal.show();
}
</script>

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

<?php require_once '../app/views/layouts/footer.php'; ?>

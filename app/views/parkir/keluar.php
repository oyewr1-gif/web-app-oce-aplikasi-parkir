<?php require_once '../app/views/layouts/header.php'; ?>

<div class="row g-4">
    <!-- Lookup & Billing Form Column -->
    <div class="col-12 col-lg-6">
        <div class="card card-custom mb-4">
            <div class="card-header bg-warning text-dark py-3">
                <h5 class="mb-0 fw-bold"><i class="fa-solid fa-magnifying-glass me-2"></i> Cari Tiket / Plat Nomor</h5>
            </div>
            <div class="card-body p-4">
                <form action="<?= BASE_URL ?>/parkir/keluar" method="GET">
                    <div class="mb-3">
                        <label for="keyword" class="form-label fw-bold">Nomor Tiket atau Plat Nomor</label>
                        <div class="input-group input-group-lg">
                            <input type="text" class="form-control auto-focus text-uppercase fw-bold" id="keyword" name="keyword" value="<?= htmlspecialchars($keyword ?? '') ?>" placeholder="Scan Tiket / Plat..." required autocomplete="off">
                            <button class="btn btn-warning fw-bold" type="submit">Cari</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <?php Session::flash(); ?>

        <!-- Billing Calculation Result Card -->
        <?php if ($trx && $calc): ?>
            <div class="card card-custom border-warning shadow-sm">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-bold text-primary"><i class="fa-solid fa-calculator me-1"></i> Rincian Biaya & Pembayaran</h6>
                    <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#modalVoid<?= $trx['id'] ?>">
                        <i class="fa-solid fa-ban me-1"></i> Batalkan (Void)
                    </button>
                </div>
                <div class="card-body p-4">
                    <form action="<?= BASE_URL ?>/parkir/prosesKeluar" method="POST" id="formCheckout">
                        <input type="hidden" name="idtrx" value="<?= htmlspecialchars($trx['idtrx']) ?>">
                        <input type="hidden" id="baseTarif" value="<?= $calc['tarif'] ?>">

                        <div class="table-responsive mb-3">
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <td class="text-muted" width="40%">Kode Tiket</td>
                                    <td class="fw-bold text-end font-monospace"><?= htmlspecialchars($trx['idtrx']) ?></td>
                                </tr>
                                <tr>
                                    <td class="text-muted align-middle">Nomor Polisi</td>
                                    <td class="text-end">
                                        <?php if (!empty($trx['nopol'])): ?>
                                            <span class="fw-bold text-primary fs-5"><?= htmlspecialchars($trx['nopol']) ?></span>
                                            <input type="hidden" name="nopol_update" value="<?= htmlspecialchars($trx['nopol']) ?>">
                                        <?php else: ?>
                                            <div class="input-group input-group-sm justify-content-end" style="max-width: 220px; float: right;">
                                                <input type="text" name="nopol_update" class="form-control text-uppercase fw-bold text-end border-secondary" placeholder="Ketik Plat (Opsional)" autocomplete="off">
                                            </div>
                                            <div class="clearfix"></div>
                                            <small class="text-muted d-block mt-1"><i class="fa-solid fa-circle-info me-1"></i>Plat kosong saat masuk (opsional / boleh dilewati).</small>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Jenis Kendaraan</td>
                                    <td class="fw-bold text-end"><?= htmlspecialchars($trx['jn_kendaraan']) ?></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Gate Masuk</td>
                                    <td class="fw-bold text-end text-success"><?= htmlspecialchars($trx['gate'] ?? 'MAN R4') ?></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Waktu Masuk</td>
                                    <td class="text-end"><?= htmlspecialchars($trx['waktuMasuk']) ?></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Waktu Keluar</td>
                                    <td class="text-end"><?= htmlspecialchars($calc['waktu_keluar']) ?></td>
                                </tr>
                                <tr class="table-light border-top">
                                    <td class="fw-bold">Durasi Parkir</td>
                                    <td class="fw-bold text-end text-dark"><?= htmlspecialchars($calc['durasi_str']) ?> (<?= $calc['durasi_jam'] ?> Jam)</td>
                                </tr>
                                <?php if (isset($calc['is_member']) && $calc['is_member']): ?>
                                    <tr class="table-info">
                                        <td colspan="2" class="text-center text-info fw-bold py-2">
                                            <i class="fa-solid fa-circle-check me-1"></i> Status: Member Parkir Langganan (GRATIS)
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </table>
                        </div>

                        <!-- Gate Keluar Selection -->
                        <div class="mb-3">
                            <label for="gateout" class="form-label fw-bold small text-secondary">Pos / Pintu Gate Keluar</label>
                            <select class="form-select" id="gateout" name="gateout" required>
                                <?php foreach ($pos_list as $pos): ?>
                                    <option value="<?= htmlspecialchars($pos['nama']) ?>" <?= ($trx['gateout'] ?? '') == $pos['nama'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($pos['nama']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Tiket Hilang & Denda (Lost Ticket) Section -->
                        <div class="card bg-light border-danger mb-3">
                            <div class="card-body p-3">
                                <div class="form-check form-switch mb-2">
                                    <input class="form-check-input" type="checkbox" id="checkLost" name="is_lost" value="1">
                                    <label class="form-check-label fw-bold text-danger" for="checkLost">
                                        <i class="fa-solid fa-triangle-exclamation me-1"></i> Tiket Parkir Hilang (Denda Lost Ticket)
                                    </label>
                                </div>
                                <div id="lostTicketDetails" style="display: none;" class="mt-2 pt-2 border-top">
                                    <div class="mb-2">
                                        <label class="form-label small fw-bold">Nominal Denda Hilang (Rp)</label>
                                        <input type="number" class="form-control form-control-sm" id="inputDenda" name="denda" value="<?= $calc['denda_lost_default'] ?? 10000 ?>">
                                    </div>
                                    <div class="row g-2 mb-2">
                                        <div class="col-6">
                                            <label class="form-label small text-muted">No. STNK</label>
                                            <input type="text" class="form-control form-control-sm" name="nostnk" placeholder="Nomor STNK">
                                        </div>
                                        <div class="col-6">
                                            <label class="form-label small text-muted">No. KTP</label>
                                            <input type="text" class="form-control form-control-sm" name="noktp" placeholder="Nomor KTP">
                                        </div>
                                    </div>
                                    <div class="row g-2">
                                        <div class="col-6">
                                            <label class="form-label small text-muted">Nama Pemilik</label>
                                            <input type="text" class="form-control form-control-sm" name="nama_pemilik" placeholder="Nama sesuai identitas">
                                        </div>
                                        <div class="col-6">
                                            <label class="form-label small text-muted">No. Handphone</label>
                                            <input type="text" class="form-control form-control-sm" name="nohp" placeholder="08xxxxxxxxxx">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- TOTAL BILLING DISPLAY -->
                        <div class="bg-light p-3 rounded mb-3 text-center border">
                            <span class="text-muted small d-block">TOTAL YANG HARUS DIBAYAR</span>
                            <h2 class="fw-bold text-danger mb-0" id="displayTotalTagihan">Rp <?= number_format($calc['tarif']) ?></h2>
                        </div>

                        <!-- METODE PEMBAYARAN -->
                        <div class="mb-3">
                            <label class="form-label fw-bold small text-secondary">Metode Pembayaran</label>
                            <div class="btn-group w-100" role="group">
                                <input type="radio" class="btn-check" name="cara_bayar" id="payTunai" value="Tunai" checked>
                                <label class="btn btn-outline-primary" for="payTunai"><i class="fa-solid fa-money-bill-wave me-1"></i> Tunai</label>

                                <input type="radio" class="btn-check" name="cara_bayar" id="payQris" value="QRIS">
                                <label class="btn btn-outline-info" for="payQris"><i class="fa-solid fa-qrcode me-1"></i> QRIS</label>

                                <input type="radio" class="btn-check" name="cara_bayar" id="payPrepaid" value="Prepaid">
                                <label class="btn btn-outline-secondary" for="payPrepaid"><i class="fa-solid fa-credit-card me-1"></i> E-Money</label>
                            </div>
                        </div>

                        <!-- Non-Tunai Ref Field -->
                        <div class="mb-3" id="boxRefBayar" style="display: none;">
                            <label class="form-label fw-bold small text-secondary">No. Referensi / Approval Transaksi</label>
                            <input type="text" class="form-control" name="refbayar" placeholder="Nomor Reff / Trace ID transaksi">
                        </div>

                        <!-- Tunai Fields -->
                        <div id="boxTunai">
                            <div class="mb-3">
                                <label for="inputBayar" class="form-label fw-bold">Nominal Uang Bayar (Rp)</label>
                                <input type="number" class="form-control form-control-lg fw-bold text-end" id="inputBayar" name="bayar" value="<?= $calc['tarif'] ?>" min="<?= $calc['tarif'] ?>" step="500" required>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-4 p-2 bg-white rounded border">
                                <span class="fw-bold text-secondary">Kembalian:</span>
                                <span id="displayKembalian" class="fw-bold text-success fs-5">Rp 0</span>
                            </div>
                        </div>

                        <button type="submit" id="btnProsesKeluar" class="btn btn-warning btn-lg w-100 fw-bold text-dark">
                            <i class="fa-solid fa-check-circle me-1"></i> Selesaikan Transaksi & Cetak Struk
                        </button>
                    </form>
                </div>
            </div>

            <!-- Modal Void Transaksi -->
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
        <?php endif; ?>
    </div>

    <!-- Active Parked Vehicles List Column -->
    <div class="col-12 col-lg-6">
        <div class="card card-custom">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span class="fw-bold"><i class="fa-solid fa-list me-1"></i> Daftar Kendaraan Sedang Parkir</span>
                <span class="badge bg-secondary"><?= count($active_list) ?> Terparkir</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive" style="max-height: 600px;">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light sticky-top">
                            <tr>
                                <th>No. Tiket</th>
                                <th>Nopol</th>
                                <th>Jenis</th>
                                <th>Waktu Masuk</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($active_list)): ?>
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted">
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
                                        <td class="small text-muted"><?= htmlspecialchars($row['waktuMasuk']) ?></td>
                                        <td class="text-end">
                                            <div class="btn-group btn-group-sm">
                                                <a href="<?= BASE_URL ?>/parkir/keluar?keyword=<?= urlencode($row['idtrx']) ?>" class="btn btn-warning fw-bold" title="Proses Keluar">
                                                    <i class="fa-solid fa-calculator"></i>
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
    const checkLost = document.getElementById('checkLost');
    const lostDetails = document.getElementById('lostTicketDetails');
    const inputDenda = document.getElementById('inputDenda');
    const baseTarifInput = document.getElementById('baseTarif');
    const displayTotal = document.getElementById('displayTotalTagihan');
    const inputBayar = document.getElementById('inputBayar');
    const displayKembalian = document.getElementById('displayKembalian');
    const boxTunai = document.getElementById('boxTunai');
    const boxRefBayar = document.getElementById('boxRefBayar');

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

        const isTunai = document.getElementById('payTunai') ? document.getElementById('payTunai').checked : true;

        if (inputBayar) {
            inputBayar.min = total;
            if (isTunai) {
                if (parseInt(inputBayar.value) < total) {
                    inputBayar.value = total;
                }
            } else {
                inputBayar.value = total;
            }
        }
        updateKembalian(total);
    }

    function updateKembalian(total) {
        if (!inputBayar || !displayKembalian) return;
        const totalTagihan = total !== undefined ? total : ((parseInt(baseTarifInput?.value) || 0) + ((checkLost?.checked ? parseInt(inputDenda?.value) : 0) || 0));
        const bayar = parseInt(inputBayar.value) || 0;
        const kembalian = bayar - totalTagihan;

        if (kembalian >= 0) {
            displayKembalian.className = 'fw-bold text-success fs-5';
            displayKembalian.textContent = 'Rp ' + kembalian.toLocaleString('id-ID');
        } else {
            displayKembalian.className = 'fw-bold text-danger fs-5';
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

    if (inputBayar) {
        inputBayar.addEventListener('input', function() {
            updateKembalian();
        });
    }

    payRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.value === 'Tunai') {
                boxTunai.style.display = 'block';
                boxRefBayar.style.display = 'none';
                inputBayar.required = true;
            } else {
                boxTunai.style.display = 'none';
                boxRefBayar.style.display = 'block';
                inputBayar.required = false;
            }
            calculateTotal();
        });
    });
});
</script>

<?php require_once '../app/views/layouts/footer.php'; ?>

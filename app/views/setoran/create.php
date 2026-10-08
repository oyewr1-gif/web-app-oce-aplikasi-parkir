<?php require_once '../app/views/layouts/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-0 text-dark"><i class="fa-solid fa-cash-register text-success me-2"></i> Form Tutup Setoran Kasir</h4>
        <span class="text-muted small">Pilih tanggal, shift, dan petugas kasir untuk merekonsiliasi total penerimaan sistem dengan fisik uang tunai</span>
    </div>
    <a href="<?= BASE_URL ?>/setoran" class="btn btn-outline-secondary">
        <i class="fa-solid fa-arrow-left me-1"></i> Kembali ke Riwayat
    </a>
</div>

<?php Session::flash(); ?>

<!-- Filter & Parameter Box -->
<div class="card card-custom mb-4">
    <div class="card-body p-4">
        <form action="<?= BASE_URL ?>/setoran/create" method="GET" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label fw-bold small text-secondary">Tanggal Transaksi</label>
                <input type="date" name="tgl" class="form-control" value="<?= htmlspecialchars($tgl) ?>" required>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold small text-secondary">Pilih Shift</label>
                <select name="shift" class="form-select">
                    <option value="">-- Semua Shift --</option>
                    <?php foreach ($shifts as $sh): ?>
                        <option value="<?= htmlspecialchars($sh['nama']) ?>" <?= $selected_shift == $sh['nama'] ? 'selected' : '' ?>>
                            Shift <?= htmlspecialchars($sh['nama']) ?> (<?= substr($sh['jama'], 0, 5) ?> - <?= substr($sh['jamb'], 0, 5) ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-bold small text-secondary">Petugas Kasir</label>
                <select name="iduser" class="form-select">
                    <option value="">-- Semua Kasir --</option>
                    <?php foreach ($kasir_list as $k): ?>
                        <option value="<?= $k['id'] ?>" <?= $selected_user == $k['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($k['nama']) ?> (<?= htmlspecialchars($k['username']) ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary w-100 fw-bold">
                    <i class="fa-solid fa-sync me-1"></i> Hitung Data Sistem
                </button>
            </div>
        </form>
    </div>
</div>

<div class="row g-4">
    <!-- Rekapitulasi Sistem Card -->
    <div class="col-12 col-lg-7">
        <div class="card card-custom h-100">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0 text-dark"><i class="fa-solid fa-calculator text-primary me-2"></i> Hasil Hitung Sistem (Tabel jurnal_transaksi)</h6>
                <span class="badge bg-primary"><?= $calc['total_kendaraan'] ?> Kendaraan Selesai</span>
            </div>
            <div class="card-body p-4">
                <div class="table-responsive mb-4">
                    <table class="table table-bordered align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Jenis Kendaraan</th>
                                <th class="text-center">Jum. Kendaraan</th>
                                <th class="text-end">Tunai (Rp)</th>
                                <th class="text-end">QRIS (Rp)</th>
                                <th class="text-end">Prepaid (Rp)</th>
                                <th class="text-end">Subtotal (Rp)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($calc['items'])): ?>
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">Tidak ada transaksi keluar pada tanggal dan filter yang dipilih.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($calc['items'] as $item): ?>
                                    <tr>
                                        <td class="fw-bold"><?= htmlspecialchars($item['jn_kendaraan']) ?></td>
                                        <td class="text-center"><span class="badge bg-light text-dark border"><?= $item['total_kendaraan'] ?></span></td>
                                        <td class="text-end">Rp <?= number_format($item['tunai']) ?></td>
                                        <td class="text-end">Rp <?= number_format($item['qris']) ?></td>
                                        <td class="text-end">Rp <?= number_format($item['prepaid']) ?></td>
                                        <td class="text-end fw-bold text-primary">Rp <?= number_format($item['total']) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                        <tfoot class="table-light fw-bold">
                            <tr>
                                <td>TOTAL KESELURUHAN</td>
                                <td class="text-center"><?= $calc['total_kendaraan'] ?></td>
                                <td class="text-end text-success">Rp <?= number_format($calc['total_tunai']) ?></td>
                                <td class="text-end text-info">Rp <?= number_format($calc['total_qris']) ?></td>
                                <td class="text-end text-secondary">Rp <?= number_format($calc['total_prepaid']) ?></td>
                                <td class="text-end fs-5 text-danger">Rp <?= number_format($calc['grand_total']) ?></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div class="row g-3">
                    <div class="col-sm-4">
                        <div class="p-3 bg-light rounded text-center border">
                            <span class="text-muted small d-block">Penerimaan Tunai</span>
                            <span class="fs-5 fw-bold text-success">Rp <?= number_format($calc['total_tunai']) ?></span>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="p-3 bg-light rounded text-center border">
                            <span class="text-muted small d-block">Non-Tunai (QRIS)</span>
                            <span class="fs-5 fw-bold text-info">Rp <?= number_format($calc['total_qris']) ?></span>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="p-3 bg-light rounded text-center border">
                            <span class="text-muted small d-block">Kartu / Prepaid</span>
                            <span class="fs-5 fw-bold text-secondary">Rp <?= number_format($calc['total_prepaid']) ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Form Submit Rekap Setoran Card -->
    <div class="col-12 col-lg-5">
        <div class="card card-custom h-100 border-success">
            <div class="card-header bg-success text-white py-3">
                <h6 class="fw-bold mb-0"><i class="fa-solid fa-file-signature me-2"></i> Verifikasi Fisik & Simpan Berita Acara</h6>
            </div>
            <div class="card-body p-4">
                <form action="<?= BASE_URL ?>/setoran/store" method="POST" id="formSetoran">
                    <input type="hidden" name="tglsetoran" value="<?= htmlspecialchars($tgl) ?>">
                    <input type="hidden" name="jumuang" id="inputJumuang" value="<?= $calc['grand_total'] ?>">

                    <div class="mb-3">
                        <label class="form-label fw-bold small">Pintu / Pos Kasir</label>
                        <select name="pintu" class="form-select" required>
                            <?php foreach ($pos_kasir as $pk): ?>
                                <option value="<?= htmlspecialchars($pk['nama']) ?>" <?= $selected_pintu == $pk['nama'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($pk['nama']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small">Shift Kerja</label>
                        <select name="shift" class="form-select" required>
                            <?php foreach ($shifts as $sh): ?>
                                <option value="<?= htmlspecialchars($sh['nama']) ?>" <?= $selected_shift == $sh['nama'] ? 'selected' : '' ?>>
                                    Shift <?= htmlspecialchars($sh['nama']) ?> (<?= substr($sh['jama'], 0, 5) ?> - <?= substr($sh['jamb'], 0, 5) ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small">Nama Petugas Kasir</label>
                        <select name="iduser_kasir" id="selectKasir" class="form-select" required>
                            <option value="">-- Pilih Kasir --</option>
                            <?php foreach ($kasir_list as $k): ?>
                                <option value="<?= $k['id'] ?>" data-nama="<?= htmlspecialchars($k['nama']) ?>" <?= $selected_user == $k['id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($k['nama']) ?> (<?= htmlspecialchars($k['username']) ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <input type="hidden" name="nama_kasir" id="namaKasirInput" value="">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-primary">Total Uang di Sistem (Rp)</label>
                        <input type="text" class="form-control form-control-lg fw-bold bg-light" value="Rp <?= number_format($calc['grand_total']) ?>" readonly>
                    </div>

                    <div class="mb-3">
                        <label for="inputFisik" class="form-label fw-bold small text-success">Total Uang Fisik Yang Diserahkan (Rp)</label>
                        <input type="number" class="form-control form-control-lg fw-bold text-end" id="inputFisik" name="fisik" value="<?= $calc['grand_total'] ?>" min="0" step="500" required>
                        <div class="form-text">Masukkan jumlah total fisik uang tunai hasil hitungan kasir.</div>
                    </div>

                    <div class="p-3 bg-light rounded border mb-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-bold small text-secondary">Selisih Fisik vs Sistem:</span>
                            <span id="displaySelisih" class="fw-bold fs-5 text-success">Rp 0 (Cocok)</span>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-success btn-lg w-100 fw-bold">
                        <i class="fa-solid fa-save me-1"></i> Simpan & Sahkan Berita Acara
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const inputFisik = document.getElementById('inputFisik');
    const inputJumuang = document.getElementById('inputJumuang');
    const displaySelisih = document.getElementById('displaySelisih');
    const selectKasir = document.getElementById('selectKasir');
    const namaKasirInput = document.getElementById('namaKasirInput');

    function updateSelisih() {
        const jumuang = parseInt(inputJumuang.value) || 0;
        const fisik = parseInt(inputFisik.value) || 0;
        const selisih = fisik - jumuang;

        if (selisih === 0) {
            displaySelisih.className = 'fw-bold fs-5 text-success';
            displaySelisih.textContent = 'Rp 0 (Cocok / Pas)';
        } else if (selisih > 0) {
            displaySelisih.className = 'fw-bold fs-5 text-warning';
            displaySelisih.textContent = '+Rp ' + selisih.toLocaleString('id-ID') + ' (Lebih)';
        } else {
            displaySelisih.className = 'fw-bold fs-5 text-danger';
            displaySelisih.textContent = '-Rp ' + Math.abs(selisih).toLocaleString('id-ID') + ' (Kurang)';
        }
    }

    function updateNamaKasir() {
        const selected = selectKasir.options[selectKasir.selectedIndex];
        if (selected) {
            namaKasirInput.value = selected.getAttribute('data-nama') || selected.text;
        }
    }

    if (inputFisik) {
        inputFisik.addEventListener('input', updateSelisih);
    }
    if (selectKasir) {
        selectKasir.addEventListener('change', updateNamaKasir);
        updateNamaKasir();
    }
});
</script>

<?php require_once '../app/views/layouts/footer.php'; ?>

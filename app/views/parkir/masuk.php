<?php require_once '../app/views/layouts/header.php'; ?>

<div class="row justify-content-center">
    <div class="col-12 col-md-8 col-lg-6">
        <div class="card card-custom">
            <div class="card-header bg-success text-white py-3">
                <h5 class="mb-0 fw-bold"><i class="fa-solid fa-right-to-bracket me-2"></i> Input Kendaraan Masuk (Gate IN)</h5>
            </div>
            <div class="card-body p-4">
                <form action="<?= BASE_URL ?>/parkir/prosesMasuk" method="POST">
                    <div class="mb-3">
                        <label for="nopol" class="form-label fw-bold">Nomor Polisi (Plat Nomor)</label>
                        <div class="input-group input-group-lg">
                            <span class="input-group-text bg-light"><i class="fa-solid fa-car"></i></span>
                            <input type="text" class="form-control text-uppercase auto-focus fw-bold" id="nopol" name="nopol" placeholder="Contoh: B 1234 ABC" required autocomplete="off" style="letter-spacing: 2px;">
                        </div>
                        <div class="form-text">Ketik plat nomor kendaraan yang masuk ke area parkir.</div>
                    </div>

                    <div class="mb-3">
                        <label for="jn_kendaraan" class="form-label fw-bold">Jenis Kendaraan</label>
                        <select class="form-select form-select-lg" id="jn_kendaraan" name="jn_kendaraan" required>
                            <option value="" disabled selected>-- Pilih Jenis Kendaraan --</option>
                            <?php foreach ($jenis_list as $j): ?>
                                <option value="<?= htmlspecialchars($j['nama']) ?>">
                                    <?= htmlspecialchars($j['nama']) ?> (Jam Ke-1: Rp <?= number_format($j['tarif_pertama']) ?>, Jam Berik: Rp <?= number_format($j['tarif_berikutnya']) ?>/jam)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-6">
                            <label for="gate" class="form-label fw-bold">Pintu Gate Masuk</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fa-solid fa-torii-gate"></i></span>
                                <select class="form-select" id="gate" name="gate" required>
                                    <option value="GATE-IN-01" selected>GATE-IN-01 (Utama)</option>
                                    <option value="GATE-IN-02">GATE-IN-02 (Barat)</option>
                                    <option value="GATE-IN-03">GATE-IN-03 (Timur)</option>
                                    <option value="GATE-IN-04">GATE-IN-04 (VIP)</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-6">
                            <label for="gateout" class="form-label fw-bold">Pintu Gate Keluar (Default)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fa-solid fa-door-open"></i></span>
                                <select class="form-select" id="gateout" name="gateout" required>
                                    <option value="GATE-OUT-01" selected>GATE-OUT-01 (Utama)</option>
                                    <option value="GATE-OUT-02">GATE-OUT-02 (Barat)</option>
                                    <option value="GATE-OUT-03">GATE-OUT-03 (Timur)</option>
                                    <option value="GATE-OUT-04">GATE-OUT-04 (VIP)</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-success btn-lg fw-bold">
                            <i class="fa-solid fa-print me-2"></i> Simpan & Cetak Tiket Parkir
                        </button>
                        <a href="<?= BASE_URL ?>/dashboard" class="btn btn-outline-secondary">
                            <i class="fa-solid fa-arrow-left me-1"></i> Batal / Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once '../app/views/layouts/footer.php'; ?>

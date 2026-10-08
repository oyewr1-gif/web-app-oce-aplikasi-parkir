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
                                    <?php if (!empty($manless_list)): ?>
                                        <?php foreach ($manless_list as $m): ?>
                                            <option value="<?= htmlspecialchars($m['nama']) ?>"><?= htmlspecialchars($m['nama']) ?></option>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <option value="MAN R4">MAN R4</option>
                                        <option value="MAN R2">MAN R2</option>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-6">
                            <label for="gateout" class="form-label fw-bold">Pintu Gate Keluar (Default)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fa-solid fa-door-open"></i></span>
                                <select class="form-select" id="gateout" name="gateout" required>
                                    <?php if (!empty($pos_list)): ?>
                                        <?php foreach ($pos_list as $p): ?>
                                            <option value="<?= htmlspecialchars($p['nama']) ?>"><?= htmlspecialchars($p['nama']) ?></option>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <option value="POS R4">POS R4</option>
                                        <option value="POS R2">POS R2</option>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-success btn-lg fw-bold">
                            <i class="fa-solid fa-print me-1"></i> Cetak Tiket Parkir & Buka Gate
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once '../app/views/layouts/footer.php'; ?>

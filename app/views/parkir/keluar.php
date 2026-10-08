<?php require_once '../app/views/layouts/header.php'; ?>

<div class="row g-4">
    <!-- Lookup Form Column -->
    <div class="col-12 col-lg-5">
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

        <!-- Billing Calculation Result Card -->
        <?php if ($trx && $calc): ?>
            <div class="card card-custom border-warning">
                <div class="card-header bg-light">
                    <h6 class="mb-0 fw-bold text-primary"><i class="fa-solid fa-calculator me-1"></i> Rincian Biaya Parkir</h6>
                </div>
                <div class="card-body">
                    <form action="<?= BASE_URL ?>/parkir/prosesKeluar" method="POST">
                        <input type="hidden" name="idtrx" value="<?= htmlspecialchars($trx['idtrx']) ?>">
                        <input type="hidden" id="inputTarif" value="<?= $calc['tarif'] ?>">

                        <div class="table-responsive">
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <td class="text-muted">Kode Tiket</td>
                                    <td class="fw-bold text-end"><?= htmlspecialchars($trx['idtrx']) ?></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Nomor Polisi</td>
                                    <td class="fw-bold text-end text-primary fs-5"><?= htmlspecialchars($trx['nopol']) ?></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Jenis Kendaraan</td>
                                    <td class="fw-bold text-end"><?= htmlspecialchars($trx['jn_kendaraan']) ?></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Gate Masuk</td>
                                    <td class="fw-bold text-end text-success"><?= htmlspecialchars($trx['gate'] ?? 'GATE-IN-01') ?></td>
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
                                        <td colspan="2" class="text-center text-info fw-bold">
                                            <i class="fa-solid fa-circle-check me-1"></i> Status: Member Parkir (GRATIS)
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </table>
                        </div>

                        <div class="mb-3">
                            <label for="gateout" class="form-label fw-bold">Pintu Gate Keluar</label>
                            <select class="form-select" id="gateout" name="gateout" required>
                                <option value="GATE-OUT-01" <?= ($trx['gateout'] ?? '') == 'GATE-OUT-01' ? 'selected' : '' ?>>GATE-OUT-01 (Utama)</option>
                                <option value="GATE-OUT-02" <?= ($trx['gateout'] ?? '') == 'GATE-OUT-02' ? 'selected' : '' ?>>GATE-OUT-02 (Barat)</option>
                                <option value="GATE-OUT-03" <?= ($trx['gateout'] ?? '') == 'GATE-OUT-03' ? 'selected' : '' ?>>GATE-OUT-03 (Timur)</option>
                                <option value="GATE-OUT-04" <?= ($trx['gateout'] ?? '') == 'GATE-OUT-04' ? 'selected' : '' ?>>GATE-OUT-04 (VIP)</option>
                            </select>
                        </div>

                        <div class="bg-light p-3 rounded mb-3 text-center border">
                            <span class="text-muted small d-block">TOTAL TARIF PARKIR</span>
                            <h2 class="fw-bold text-danger mb-0">Rp <?= number_format($calc['tarif']) ?></h2>
                        </div>

                        <div class="mb-3">
                            <label for="inputBayar" class="form-label fw-bold">Nominal Uang Bayar (Rp)</label>
                            <input type="number" class="form-control form-control-lg fw-bold text-end" id="inputBayar" name="bayar" value="<?= $calc['tarif'] ?>" min="<?= $calc['tarif'] ?>" step="500" required>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-4 p-2 bg-white rounded border">
                            <span class="fw-bold text-secondary">Kembalian:</span>
                            <span id="displayKembalian" class="fw-bold text-success fs-5">Rp 0</span>
                        </div>

                        <button type="submit" id="btnProsesKeluar" class="btn btn-warning btn-lg w-100 fw-bold text-dark">
                            <i class="fa-solid fa-check-circle me-1"></i> Selesaikan Transaksi & Cetak Struk
                        </button>
                    </form>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Active Parked Vehicles List Column -->
    <div class="col-12 col-lg-7">
        <div class="card card-custom">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span class="fw-bold"><i class="fa-solid fa-list me-1"></i> Daftar Kendaraan Aktif</span>
                <span class="badge bg-secondary"><?= count($active_list) ?> Terparkir</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive" style="max-height: 520px;">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light sticky-top">
                            <tr>
                                <th>No. Tiket</th>
                                <th>Nopol</th>
                                <th>Jenis</th>
                                <th>Gate Masuk</th>
                                <th>Gate Keluar</th>
                                <th>Waktu Masuk</th>
                                <th class="text-end">Pilih</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($active_list)): ?>
                                <tr>
                                    <td colspan="7" class="text-center py-4 text-muted">Tidak ada kendaraan di dalam lokasi parkir.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($active_list as $a): ?>
                                    <tr>
                                        <td><span class="badge bg-light text-dark border"><?= htmlspecialchars($a['idtrx']) ?></span></td>
                                        <td class="fw-bold text-primary"><?= htmlspecialchars($a['nopol']) ?></td>
                                        <td><?= htmlspecialchars($a['jn_kendaraan']) ?></td>
                                        <td><span class="badge bg-light text-dark border"><?= htmlspecialchars($a['gate'] ?? 'GATE-IN-01') ?></span></td>
                                        <td><span class="badge bg-light text-dark border"><?= htmlspecialchars($a['gateout'] ?? 'GATE-OUT-01') ?></span></td>
                                        <td><?= htmlspecialchars($a['jam_masuk']) ?></td>
                                        <td class="text-end">
                                            <a href="<?= BASE_URL ?>/parkir/keluar?keyword=<?= urlencode($a['idtrx']) ?>" class="btn btn-sm btn-warning fw-semibold">
                                                Pilih
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

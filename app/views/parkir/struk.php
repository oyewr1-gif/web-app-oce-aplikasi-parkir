<?php require_once '../app/views/layouts/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-3 no-print">
    <a href="<?= BASE_URL ?>/parkir/keluar" class="btn btn-outline-secondary">
        <i class="fa-solid fa-arrow-left me-1"></i> Kembali ke Transaksi Keluar
    </a>
    <button onclick="window.print()" class="btn btn-warning fw-bold text-dark">
        <i class="fa-solid fa-print me-1"></i> Cetak Struk Pembayaran
    </button>
</div>

<!-- Receipt Container -->
<div class="struk-container printable-area">
    <div class="struk-header">
        <h5 class="fw-bold mb-1">STRUK PEMBAYARAN PARKIR</h5>
        <div class="small fw-bold text-success">STATUS: LUNAS</div>
    </div>

    <div class="struk-body">
        <div class="row-item">
            <span>ID TRX:</span>
            <span class="fw-bold"><?= htmlspecialchars($trx['idtrx']) ?></span>
        </div>
        <div class="row-item">
            <span>NOPOL:</span>
            <span class="fw-bold fs-5"><?= htmlspecialchars(!empty($trx['nopol']) ? $trx['nopol'] : '-') ?></span>
        </div>
        <div class="row-item">
            <span>JENIS:</span>
            <span><?= htmlspecialchars($trx['jn_kendaraan']) ?></span>
        </div>
        <hr style="border-style: dashed;">
        <div class="row-item">
            <span>GATE MASUK:</span>
            <span class="fw-bold"><?= htmlspecialchars($trx['gate'] ?? 'POS-IN-01') ?></span>
        </div>
        <div class="row-item">
            <span>WAKTU MASUK:</span>
            <span><?= htmlspecialchars($trx['tgl_masuk']) ?> <?= htmlspecialchars($trx['jam_masuk']) ?></span>
        </div>
        <div class="row-item">
            <span>GATE KELUAR:</span>
            <span class="fw-bold"><?= htmlspecialchars($trx['gateout'] ?? 'POS-OUT-01') ?></span>
        </div>
        <div class="row-item">
            <span>WAKTU KELUAR:</span>
            <span><?= htmlspecialchars($trx['tgl_keluar']) ?> <?= htmlspecialchars($trx['jam_keluar']) ?></span>
        </div>
        <div class="row-item">
            <span>DURASI:</span>
            <span class="fw-bold"><?= htmlspecialchars($trx['durasi']) ?></span>
        </div>
        <hr style="border-style: dashed;">
        <div class="row-item">
            <span>TARIF PARKIR:</span>
            <span>Rp <?= number_format($trx['tarif']) ?></span>
        </div>
        <?php if (!empty($trx['denda']) && $trx['denda'] > 0): ?>
            <div class="row-item text-danger">
                <span>DENDA TIKET HILANG:</span>
                <span class="fw-bold">Rp <?= number_format($trx['denda']) ?></span>
            </div>
            <?php if (!empty($trx['nostnk'])): ?>
                <div class="row-item small text-muted">
                    <span>NO. STNK:</span>
                    <span><?= htmlspecialchars($trx['nostnk']) ?></span>
                </div>
            <?php endif; ?>
        <?php endif; ?>
        <div class="row-item fw-bold border-top pt-1">
            <span>TOTAL BIAYA:</span>
            <span>Rp <?= number_format($trx['tarif'] + ($trx['denda'] ?? 0)) ?></span>
        </div>
        <div class="row-item">
            <span>METODE BAYAR:</span>
            <span class="fw-bold"><?= htmlspecialchars($trx['cara_bayar'] ?: 'Tunai') ?></span>
        </div>
        <?php if (!empty($trx['refbayar'])): ?>
            <div class="row-item small">
                <span>NO. REFF:</span>
                <span><?= htmlspecialchars($trx['refbayar']) ?></span>
            </div>
        <?php endif; ?>
        <div class="row-item">
            <span>UANG DITERIMA:</span>
            <span>Rp <?= number_format($trx['bayar']) ?></span>
        </div>
        <div class="row-item">
            <span>KEMBALIAN:</span>
            <span class="fw-bold">Rp <?= number_format($trx['kembalian']) ?></span>
        </div>
    </div>

    <div class="struk-footer">
        <p class="mb-0">Terima kasih atas kunjungan Anda.</p>
        <p class="mb-0">Simpan struk ini sebagai bukti pembayaran yang sah.</p>
    </div>
</div>

<?php require_once '../app/views/layouts/footer.php'; ?>

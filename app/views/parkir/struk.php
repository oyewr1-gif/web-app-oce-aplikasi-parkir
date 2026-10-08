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
        <div class="small text-muted">LUNAS</div>
    </div>

    <div class="struk-body">
        <div class="row-item">
            <span>ID TRX:</span>
            <span class="fw-bold"><?= htmlspecialchars($trx['idtrx']) ?></span>
        </div>
        <div class="row-item">
            <span>NOPOL:</span>
            <span class="fw-bold fs-5"><?= htmlspecialchars($trx['nopol']) ?></span>
        </div>
        <div class="row-item">
            <span>JENIS:</span>
            <span><?= htmlspecialchars($trx['jn_kendaraan']) ?></span>
        </div>
        <hr style="border-style: dashed;">
        <div class="row-item">
            <span>GATE MASUK:</span>
            <span class="fw-bold"><?= htmlspecialchars($trx['gate'] ?? 'GATE-IN-01') ?></span>
        </div>
        <div class="row-item">
            <span>WAKTU MASUK:</span>
            <span><?= htmlspecialchars($trx['tgl_masuk']) ?> <?= htmlspecialchars($trx['jam_masuk']) ?></span>
        </div>
        <div class="row-item">
            <span>GATE KELUAR:</span>
            <span class="fw-bold"><?= htmlspecialchars($trx['gateout'] ?? 'GATE-OUT-01') ?></span>
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
            <span>TOTAL TARIF:</span>
            <span class="fw-bold">Rp <?= number_format($trx['tarif']) ?></span>
        </div>
        <div class="row-item">
            <span>BAYAR:</span>
            <span>Rp <?= number_format($trx['bayar']) ?></span>
        </div>
        <div class="row-item">
            <span>KEMBALI:</span>
            <span>Rp <?= number_format($trx['kembalian']) ?></span>
        </div>
    </div>

    <div class="mt-4 pt-2 border-top text-center small text-muted">
        <div>Petugas Kasir: <?= htmlspecialchars(Session::get('user_name') ?? 'Kasir') ?></div>
        <div class="mt-1 font-monospace">--- Terima Kasih ---</div>
    </div>
</div>

<?php require_once '../app/views/layouts/footer.php'; ?>

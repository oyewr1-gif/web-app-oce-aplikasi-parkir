<?php require_once '../app/views/layouts/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-3 no-print">
    <a href="<?= BASE_URL ?>/parkir/masuk" class="btn btn-outline-secondary">
        <i class="fa-solid fa-arrow-left me-1"></i> Kembali ke Gate Masuk
    </a>
    <button onclick="window.print()" class="btn btn-primary fw-bold">
        <i class="fa-solid fa-print me-1"></i> Cetak Tiket Parkir
    </button>
</div>

<!-- Ticket Container -->
<div class="ticket-container printable-area">
    <div class="ticket-header">
        <h5 class="fw-bold mb-1">TIKET PARKIR</h5>
        <div class="small text-muted">SISTEM PARKIR MANDIRI</div>
    </div>
    
    <div class="barcode-sim">
        *<?= htmlspecialchars($trx['idtrx']) ?>*
    </div>

    <div class="ticket-body">
        <div class="row-item">
            <span>NO. TIKET:</span>
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
        <div class="row-item">
            <span>TANGGAL:</span>
            <span><?= htmlspecialchars($trx['tgl_masuk']) ?></span>
        </div>
        <div class="row-item">
            <span>JAM MASUK:</span>
            <span class="fw-bold"><?= htmlspecialchars($trx['jam_masuk']) ?></span>
        </div>
        <div class="row-item">
            <span>GATE MASUK:</span>
            <span class="fw-bold text-uppercase"><?= htmlspecialchars($trx['gate'] ?? 'GATE-IN-01') ?></span>
        </div>
    </div>

    <div class="mt-4 pt-2 border-top text-center small text-muted">
        <div>Jangan tinggalkan barang berharga.</div>
        <div>Tiket hilang dikenakan denda.</div>
        <div class="mt-2 font-monospace">Terima Kasih</div>
    </div>
</div>

<?php require_once '../app/views/layouts/footer.php'; ?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Courier New', Courier, monospace;
            background: #f8f9fa;
            color: #000;
            padding: 20px;
        }
        .paper-receipt {
            background: #fff;
            max-width: 650px;
            margin: 0 auto;
            padding: 30px;
            border: 1px solid #ddd;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        }
        .header-title {
            text-align: center;
            border-bottom: 2px dashed #000;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .footer-sig {
            margin-top: 40px;
            border-top: 1px dashed #000;
            padding-top: 20px;
        }
        @media print {
            body {
                background: #fff;
                padding: 0;
            }
            .paper-receipt {
                border: none;
                box-shadow: none;
                max-width: 100%;
                padding: 0;
            }
            .no-print {
                display: none !important;
            }
        }
    </style>
</head>
<body>

<div class="text-center mb-3 no-print">
    <button onclick="window.print()" class="btn btn-primary btn-sm me-2">
        Cetak Dokumen
    </button>
    <button onclick="window.close()" class="btn btn-secondary btn-sm">
        Tutup
    </button>
</div>

<div class="paper-receipt">
    <div class="header-title">
        <h4 class="fw-bold mb-1">BERITA ACARA SETORAN KASIR PARKIR</h4>
        <div class="small fw-bold"><?= APP_NAME ?></div>
        <div class="small text-muted">Sistem Manajemen Parkir Otomatis</div>
    </div>

    <table class="table table-sm table-borderless small mb-3">
        <tr>
            <td width="35%">No. Berita Acara</td>
            <td width="5%">:</td>
            <td class="fw-bold"><?= htmlspecialchars($setoran['no_setoran']) ?></td>
        </tr>
        <tr>
            <td>Tanggal Setoran</td>
            <td>:</td>
            <td><?= htmlspecialchars($setoran['tglsetoran']) ?></td>
        </tr>
        <tr>
            <td>Shift / Pos</td>
            <td>:</td>
            <td><?= htmlspecialchars($setoran['shift']) ?> / <?= htmlspecialchars($setoran['pintu']) ?></td>
        </tr>
        <tr>
            <td>Nama Kasir</td>
            <td>:</td>
            <td class="fw-bold"><?= htmlspecialchars($setoran['nama_kasir'] ?: 'Petugas Kasir') ?></td>
        </tr>
        <tr>
            <td>Penerima (Supervisor)</td>
            <td>:</td>
            <td><?= htmlspecialchars($setoran['penerima_nama'] ?: 'Admin') ?></td>
        </tr>
        <tr>
            <td>Waktu Pencatatan</td>
            <td>:</td>
            <td><?= htmlspecialchars($setoran['waktu']) ?></td>
        </tr>
    </table>

    <div class="fw-bold small mb-1 border-bottom pb-1">RINCIAN PENERIMAAN:</div>
    <table class="table table-sm table-bordered small text-center mb-3">
        <thead class="table-light">
            <tr>
                <th>Kategori Kendaraan</th>
                <th>Tunai (Rp)</th>
                <th>QRIS (Rp)</th>
                <th>Prepaid (Rp)</th>
                <th>Total (Rp)</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($details as $d): ?>
                <tr class="<?= $d['nama_kendaraan'] == 'Total' ? 'fw-bold table-light' : '' ?>">
                    <td class="text-start"><?= htmlspecialchars($d['nama_kendaraan']) ?></td>
                    <td class="text-end"><?= number_format($d['tunai']) ?></td>
                    <td class="text-end"><?= number_format($d['qris']) ?></td>
                    <td class="text-end"><?= number_format($d['prepaid']) ?></td>
                    <td class="text-end"><?= number_format($d['total']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <table class="table table-sm table-borderless small mb-4">
        <tr>
            <td width="60%">Total Pendapatan Sistem</td>
            <td width="5%">:</td>
            <td class="text-end fw-bold">Rp <?= number_format($setoran['jumuang']) ?></td>
        </tr>
        <tr>
            <td>Total Fisik Uang Diterima</td>
            <td>:</td>
            <td class="text-end fw-bold">Rp <?= number_format($setoran['fisik']) ?></td>
        </tr>
        <tr class="border-top">
            <td class="fw-bold">Selisih Kas</td>
            <td>:</td>
            <td class="text-end fw-bold">
                <?php if ($setoran['jumuangmasalah'] == 0): ?>
                    Rp 0 (Pas / Cocok)
                <?php elseif ($setoran['jumuangmasalah'] > 0): ?>
                    +Rp <?= number_format($setoran['jumuangmasalah']) ?> (LEBIH)
                <?php else: ?>
                    -Rp <?= number_format(abs($setoran['jumuangmasalah'])) ?> (KURANG)
                <?php endif; ?>
            </td>
        </tr>
    </table>

    <div class="row text-center footer-sig small">
        <div class="col-6">
            <div>Yang Menyerahkan (Kasir),</div>
            <br><br><br>
            <div class="fw-bold">( <?= htmlspecialchars($setoran['nama_kasir'] ?: '......................') ?> )</div>
        </div>
        <div class="col-6">
            <div>Yang Menerima (Supervisor),</div>
            <br><br><br>
            <div class="fw-bold">( <?= htmlspecialchars($setoran['penerima_nama'] ?: 'Admin') ?> )</div>
        </div>
    </div>
</div>

<script>
window.onload = function() {
    // Optional auto-print on open
};
</script>
</body>
</html>

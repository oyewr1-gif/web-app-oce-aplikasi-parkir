<?php require_once '../app/views/layouts/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1"><i class="fa-solid fa-video text-danger me-2"></i> Pengaturan & Monitoring IP Camera</h4>
        <p class="text-muted small mb-0">Kelola alamat IP, kredensial, dan uji konektivitas kamera snapshot di gerbang masuk dan keluar.</p>
    </div>
    <div>
        <a href="<?= BASE_URL ?>/laporan?tab=foto" class="btn btn-outline-primary fw-semibold">
            <i class="fa-solid fa-images me-1"></i> Buka Galeri Audit Foto
        </a>
    </div>
</div>

<?php Session::flash(); ?>

<!-- Info Folder Penyimpanan -->
<div class="card card-custom mb-4 border-0 bg-light shadow-sm">
    <div class="card-body p-3">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div class="d-flex align-items-center gap-3">
                <div class="p-2 rounded bg-primary bg-opacity-10 text-primary">
                    <i class="fa-solid fa-folder-open fs-3"></i>
                </div>
                <div>
                    <span class="small text-muted d-block fw-bold text-uppercase">Direktori Fisik Arsip Foto</span>
                    <span class="font-monospace fw-bold text-dark fs-6"><?= htmlspecialchars($foto_dir) ?></span>
                </div>
            </div>
            <div>
                <?php if (is_dir($foto_dir)): ?>
                    <span class="badge bg-success py-2 px-3"><i class="fa-solid fa-circle-check me-1"></i> Direktori Siap Digunakan</span>
                <?php else: ?>
                    <span class="badge bg-warning text-dark py-2 px-3"><i class="fa-solid fa-triangle-exclamation me-1"></i> Folder Belum Dibuat di Server</span>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Daftar Kamera IP -->
<div class="row g-4 mb-4">
    <?php foreach ($cameras as $cam): ?>
        <div class="col-12 col-md-6 col-xl-3">
            <div class="card card-custom h-100 shadow-sm border-0 position-relative">
                <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                    <span class="fw-bold text-dark">
                        <i class="fa-solid fa-camera text-primary me-1"></i> <?= htmlspecialchars($cam['nama']) ?>
                    </span>
                    <span class="badge bg-secondary font-monospace" id="badgeStatus<?= $cam['id'] ?>">Siap Tes</span>
                </div>
                <div class="card-body p-3">
                    <div class="mb-3">
                        <div class="small text-muted mb-1">Alamat IP LAN:</div>
                        <div class="font-monospace fw-bold fs-6 text-primary p-2 bg-light rounded border text-center">
                            <?= htmlspecialchars($cam['lanip'] ?: '0.0.0.0') ?>
                        </div>
                    </div>

                    <div class="small text-muted mb-1">Kredensial Akses:</div>
                    <div class="bg-light p-2 rounded border small mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-secondary">Username:</span>
                            <span class="fw-bold font-monospace"><?= htmlspecialchars($cam['user'] ?: '-') ?></span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-secondary">Password:</span>
                            <span class="font-monospace text-muted">••••••••</span>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-outline-info btn-sm w-100 fw-bold btn-test-ping" 
                                data-id="<?= $cam['id'] ?>" 
                                data-ip="<?= htmlspecialchars($cam['lanip']) ?>">
                            <i class="fa-solid fa-network-wired me-1"></i> Uji Ping
                        </button>
                        <button type="button" class="btn btn-outline-dark btn-sm w-100 fw-bold btn-preview-frame"
                                data-nama="<?= htmlspecialchars($cam['nama']) ?>"
                                data-ip="<?= htmlspecialchars($cam['lanip']) ?>">
                            <i class="fa-solid fa-eye me-1"></i> Frame
                        </button>
                    </div>
                </div>
                <div class="card-footer bg-white border-top p-2 text-end">
                    <button class="btn btn-sm btn-link text-decoration-none fw-bold" 
                            data-bs-toggle="modal" 
                            data-bs-target="#modalEditCam<?= $cam['id'] ?>">
                        <i class="fa-solid fa-pen-to-square me-1"></i> Edit Pengaturan
                    </button>
                </div>
            </div>
        </div>

        <!-- Modal Edit Kamera -->
        <div class="modal fade" id="modalEditCam<?= $cam['id'] ?>" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="<?= BASE_URL ?>/kamera/edit" method="POST">
                        <input type="hidden" name="id" value="<?= $cam['id'] ?>">
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title"><i class="fa-solid fa-gear me-2"></i> Konfigurasi IP Camera #<?= $cam['id'] ?></h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Posisi / Nama Kamera</label>
                                <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($cam['nama']) ?>" required>
                                <div class="form-text small">Contoh: IN KENDARAAN, IN DRIVER, OUT KENDARAAN, OUT DRIVER</div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Alamat IP LAN (IPv4)</label>
                                <input type="text" name="lanip" class="form-control font-monospace" value="<?= htmlspecialchars($cam['lanip']) ?>" placeholder="192.168.1.xxx" required>
                            </div>
                            <div class="row g-2 mb-3">
                                <div class="col-6">
                                    <label class="form-label fw-bold">Username</label>
                                    <input type="text" name="user" class="form-control font-monospace" value="<?= htmlspecialchars($cam['user']) ?>" placeholder="admin">
                                </div>
                                <div class="col-6">
                                    <label class="form-label fw-bold">Password</label>
                                    <input type="password" name="pass" class="form-control font-monospace" value="<?= htmlspecialchars($cam['pass']) ?>">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold small text-secondary">Tipe Encoding / Protokol</label>
                                <select name="encode" class="form-select font-monospace">
                                    <option value="" <?= empty($cam['encode']) ? 'selected' : '' ?>>Default / HTTP Snapshot</option>
                                    <option value="H.264" <?= ($cam['encode'] ?? '') == 'H.264' ? 'selected' : '' ?>>H.264</option>
                                    <option value="H.265" <?= ($cam['encode'] ?? '') == 'H.265' ? 'selected' : '' ?>>H.265</option>
                                    <option value="MJPEG" <?= ($cam['encode'] ?? '') == 'MJPEG' ? 'selected' : '' ?>>MJPEG</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary fw-bold">Simpan Konfigurasi</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<!-- Modal Preview Frame Snapshot -->
<div class="modal fade" id="modalPreviewFrame" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white py-2">
                <h6 class="modal-title fw-bold" id="previewTitle"><i class="fa-solid fa-video me-2 text-warning"></i> Frame Snapshot Preview</h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0 text-center bg-black position-relative" style="min-height: 320px;">
                <img id="previewImage" src="" class="img-fluid w-100" style="max-height: 480px; object-fit: contain;" alt="Snapshot Camera">
                <div class="position-absolute bottom-0 start-0 w-100 p-2 text-start bg-dark bg-opacity-75 text-white small" id="previewOsd">
                    <span class="font-monospace text-warning me-3" id="previewIpText">IP: -</span>
                    <span class="font-monospace text-light" id="previewTimeText"></span>
                </div>
            </div>
            <div class="modal-footer py-2 bg-light">
                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="refreshPreview()"><i class="fa-solid fa-arrows-rotate me-1"></i> Ambil Ulang Frame</button>
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
let currentPreviewNama = '';
let currentPreviewIp = '';

document.addEventListener('DOMContentLoaded', function() {
    // Tombol Uji Ping Koneksi
    document.querySelectorAll('.btn-test-ping').forEach(btn => {
        btn.addEventListener('click', function() {
            const camId = this.getAttribute('data-id');
            const camIp = this.getAttribute('data-ip');
            const badge = document.getElementById('badgeStatus' + camId);

            badge.className = 'badge bg-warning text-dark font-monospace';
            badge.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-1"></i> Testing...';
            this.disabled = true;

            fetch('<?= BASE_URL ?>/kamera/testPing', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'ip=' + encodeURIComponent(camIp) + '&port=80'
            })
            .then(res => res.json())
            .then(data => {
                this.disabled = false;
                if (data.online) {
                    badge.className = 'badge bg-success font-monospace';
                    badge.innerHTML = '<i class="fa-solid fa-circle-check me-1"></i> ONLINE';
                } else {
                    badge.className = 'badge bg-danger font-monospace';
                    badge.innerHTML = '<i class="fa-solid fa-circle-xmark me-1"></i> OFFLINE';
                }
                alert(data.message);
            })
            .catch(err => {
                this.disabled = false;
                badge.className = 'badge bg-danger font-monospace';
                badge.innerHTML = 'Error';
                alert('Gagal menghubungi endpoint pengujian jaringan: ' + err);
            });
        });
    });

    // Tombol Preview Frame
    document.querySelectorAll('.btn-preview-frame').forEach(btn => {
        btn.addEventListener('click', function() {
            currentPreviewNama = this.getAttribute('data-nama');
            currentPreviewIp = this.getAttribute('data-ip');
            openPreviewModal(currentPreviewNama, currentPreviewIp);
        });
    });
});

function openPreviewModal(nama, ip) {
    document.getElementById('previewTitle').innerHTML = '<i class="fa-solid fa-video me-2 text-warning"></i> Snapshot: ' + nama;
    document.getElementById('previewIpText').textContent = 'IP LAN: ' + ip;
    document.getElementById('previewTimeText').textContent = new Date().toLocaleString('id-ID');

    const previewUrl = '<?= BASE_URL ?>/kamera/foto?label=' + encodeURIComponent(nama) + '&time=' + encodeURIComponent(new Date().toISOString());
    document.getElementById('previewImage').src = previewUrl;

    const modal = new bootstrap.Modal(document.getElementById('modalPreviewFrame'));
    modal.show();
}

function refreshPreview() {
    if (currentPreviewNama) {
        const previewUrl = '<?= BASE_URL ?>/kamera/foto?label=' + encodeURIComponent(currentPreviewNama) + '&t=' + Date.now();
        document.getElementById('previewImage').src = previewUrl;
        document.getElementById('previewTimeText').textContent = new Date().toLocaleString('id-ID');
    }
}
</script>

<?php require_once '../app/views/layouts/footer.php'; ?>

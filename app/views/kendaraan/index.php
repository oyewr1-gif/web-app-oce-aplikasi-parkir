<?php require_once '../app/views/layouts/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-0">Master Jenis Kendaraan & Tarif Parkir</h4>
        <p class="text-muted small mb-0">Atur tarif dasar jam pertama, tarif jam berikutnya, dan kapasitas slot parkir.</p>
    </div>
    <button type="button" class="btn btn-primary fw-bold" data-bs-toggle="modal" data-bs-target="#modalTambah">
        <i class="fa-solid fa-plus me-1"></i> Tambah Jenis Kendaraan
    </button>
</div>

<div class="card card-custom">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Kode</th>
                        <th>Nama Kendaraan</th>
                        <th>Tarif Jam Ke-1</th>
                        <th>Tarif Jam Berikutnya</th>
                        <th>Kapasitas Slot</th>
                        <th>Terpakai Saat Ini</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($list as $item): ?>
                        <tr>
                            <td><span class="badge bg-secondary"><?= htmlspecialchars($item['jn_kendaraan']) ?></span></td>
                            <td class="fw-bold"><?= htmlspecialchars($item['nama']) ?></td>
                            <td>Rp <?= number_format($item['tarif_pertama']) ?></td>
                            <td>Rp <?= number_format($item['tarif_berikutnya']) ?> / jam</td>
                            <td><?= number_format($item['kapasitas']) ?> Slot</td>
                            <td>
                                <span class="badge bg-<?= $item['terpakai'] > 0 ? 'warning text-dark' : 'light text-muted' ?>">
                                    <?= number_format($item['terpakai']) ?> Terpakai
                                </span>
                            </td>
                            <td class="text-end">
                                <button type="button" class="btn btn-sm btn-outline-primary me-1" 
                                        onclick="editJenis(<?= htmlspecialchars(json_encode($item)) ?>)">
                                    <i class="fa-solid fa-pen-to-square"></i> Edit
                                </button>
                                <a href="<?= BASE_URL ?>/kendaraan/delete/<?= $item['id'] ?>" 
                                   class="btn btn-sm btn-outline-danger" 
                                   onclick="return confirm('Hapus jenis kendaraan ini?')">
                                    <i class="fa-solid fa-trash"></i> Hapus
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog">
        <form action="<?= BASE_URL ?>/kendaraan/store" method="POST" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Tambah Jenis Kendaraan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="jn_kendaraan" class="form-label fw-semibold">Kode Kendaraan (2 Karakter)</label>
                    <input type="text" class="form-control" name="jn_kendaraan" placeholder="Contoh: 04" maxlength="2" required>
                </div>
                <div class="mb-3">
                    <label for="nama" class="form-label fw-semibold">Nama Jenis Kendaraan</label>
                    <input type="text" class="form-control" name="nama" placeholder="Contoh: Truk Tronton" required>
                </div>
                <div class="mb-3">
                    <label for="tarif_pertama" class="form-label fw-semibold">Tarif Jam Ke-1 (Rp)</label>
                    <input type="number" class="form-control" name="tarif_pertama" value="5000" step="500" required>
                </div>
                <div class="mb-3">
                    <label for="tarif_berikutnya" class="form-label fw-semibold">Tarif Jam Berikutnya (Rp/jam)</label>
                    <input type="number" class="form-control" name="tarif_berikutnya" value="2000" step="500" required>
                </div>
                <div class="mb-3">
                    <label for="kapasitas" class="form-label fw-semibold">Kapasitas Maksimal Slot</label>
                    <input type="number" class="form-control" name="kapasitas" value="50" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary fw-bold">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit -->
<div class="modal fade" id="modalEdit" tabindex="-1">
    <div class="modal-dialog">
        <form action="<?= BASE_URL ?>/kendaraan/update" method="POST" class="modal-content">
            <input type="hidden" name="id" id="edit_id">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Edit Jenis Kendaraan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Kode Kendaraan</label>
                    <input type="text" class="form-control" name="jn_kendaraan" id="edit_jn_kendaraan" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Nama Jenis Kendaraan</label>
                    <input type="text" class="form-control" name="nama" id="edit_nama" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Tarif Jam Ke-1 (Rp)</label>
                    <input type="number" class="form-control" name="tarif_pertama" id="edit_tarif_pertama" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Tarif Jam Berikutnya (Rp/jam)</label>
                    <input type="number" class="form-control" name="tarif_berikutnya" id="edit_tarif_berikutnya" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Kapasitas Maksimal Slot</label>
                    <input type="number" class="form-control" name="kapasitas" id="edit_kapasitas" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary fw-bold">Perbarui</button>
            </div>
        </form>
    </div>
</div>

<script>
function editJenis(data) {
    document.getElementById('edit_id').value = data.id;
    document.getElementById('edit_jn_kendaraan').value = data.jn_kendaraan;
    document.getElementById('edit_nama').value = data.nama;
    document.getElementById('edit_tarif_pertama').value = data.tarif_pertama;
    document.getElementById('edit_tarif_berikutnya').value = data.tarif_berikutnya;
    document.getElementById('edit_kapasitas').value = data.kapasitas;
    new bootstrap.Modal(document.getElementById('modalEdit')).show();
}
</script>

<?php require_once '../app/views/layouts/footer.php'; ?>

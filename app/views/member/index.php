<?php require_once '../app/views/layouts/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-0">Kelola Member Parkir Langganan</h4>
        <p class="text-muted small mb-0">Kendaraan member terdaftar mendapatkan hak akses bebas biaya parkir harian.</p>
    </div>
    <button type="button" class="btn btn-primary fw-bold" data-bs-toggle="modal" data-bs-target="#modalTambah">
        <i class="fa-solid fa-id-card me-1"></i> Daftarkan Member Baru
    </button>
</div>

<div class="card card-custom">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>ID Member</th>
                        <th>Nama Member</th>
                        <th>Nopol</th>
                        <th>Jenis Kendaraan</th>
                        <th>Periode Aktif</th>
                        <th>No. Telp</th>
                        <th>Status</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($members)): ?>
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">Belum ada data member parkir.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($members as $m): ?>
                            <tr>
                                <td><span class="badge bg-dark"><?= htmlspecialchars($m['id_member']) ?></span></td>
                                <td class="fw-bold"><?= htmlspecialchars($m['nama']) ?></td>
                                <td><span class="fw-bold text-primary"><?= htmlspecialchars($m['nopol']) ?></span></td>
                                <td><?= htmlspecialchars($m['jn_kendaraan']) ?></td>
                                <td><?= htmlspecialchars($m['tgl_mulai']) ?> s.d <?= htmlspecialchars($m['tgl_akhir']) ?></td>
                                <td><?= htmlspecialchars($m['no_tlp'] ?: '-') ?></td>
                                <td>
                                    <span class="badge bg-<?= $m['status'] == 1 ? 'success' : 'danger' ?>">
                                        <?= $m['status'] == 1 ? 'Aktif' : 'Non-Aktif' ?>
                                    </span>
                                </td>
                                <td class="text-end">
                                    <button type="button" class="btn btn-sm btn-outline-primary me-1" 
                                            onclick="editMember(<?= htmlspecialchars(json_encode($m)) ?>)">
                                        <i class="fa-solid fa-pen-to-square"></i> Edit
                                    </button>
                                    <a href="<?= BASE_URL ?>/member/delete/<?= $m['id'] ?>" 
                                       class="btn btn-sm btn-outline-danger" 
                                       onclick="return confirm('Hapus data member ini?')">
                                        <i class="fa-solid fa-trash"></i> Hapus
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

<!-- Modal Tambah Member -->
<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog">
        <form action="<?= BASE_URL ?>/member/store" method="POST" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Tambah Member Parkir Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Nama Lengkap</label>
                    <input type="text" class="form-control" name="nama" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Nomor Polisi (Plat Nomor)</label>
                    <input type="text" class="form-control text-uppercase" name="nopol" placeholder="B 1234 MBR" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Jenis Kendaraan</label>
                    <select class="form-select" name="jn_kendaraan" required>
                        <?php foreach ($jenis_list as $j): ?>
                            <option value="<?= htmlspecialchars($j['nama']) ?>"><?= htmlspecialchars($j['nama']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <label class="form-label fw-semibold">Tanggal Mulai</label>
                        <input type="date" class="form-control" name="tgl_mulai" value="<?= date('Y-m-d') ?>" required>
                    </div>
                    <div class="col-6">
                        <label class="form-label fw-semibold">Tanggal Akhir</label>
                        <input type="date" class="form-control" name="tgl_akhir" value="<?= date('Y-m-d', strtotime('+1 year')) ?>" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">No. Telepon / WhatsApp</label>
                    <input type="text" class="form-control" name="no_tlp">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Alamat</label>
                    <textarea class="form-control" name="alamat" rows="2"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary fw-bold">Daftarkan Member</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Member -->
<div class="modal fade" id="modalEdit" tabindex="-1">
    <div class="modal-dialog">
        <form action="<?= BASE_URL ?>/member/update" method="POST" class="modal-content">
            <input type="hidden" name="id" id="edit_id">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Edit Member Parkir</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Nama Lengkap</label>
                    <input type="text" class="form-control" name="nama" id="edit_nama" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Nomor Polisi</label>
                    <input type="text" class="form-control text-uppercase" name="nopol" id="edit_nopol" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Jenis Kendaraan</label>
                    <select class="form-select" name="jn_kendaraan" id="edit_jn_kendaraan" required>
                        <?php foreach ($jenis_list as $j): ?>
                            <option value="<?= htmlspecialchars($j['nama']) ?>"><?= htmlspecialchars($j['nama']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <label class="form-label fw-semibold">Tanggal Mulai</label>
                        <input type="date" class="form-control" name="tgl_mulai" id="edit_tgl_mulai" required>
                    </div>
                    <div class="col-6">
                        <label class="form-label fw-semibold">Tanggal Akhir</label>
                        <input type="date" class="form-control" name="tgl_akhir" id="edit_tgl_akhir" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Status Keanggotaan</label>
                    <select class="form-select" name="status" id="edit_status">
                        <option value="1">Aktif</option>
                        <option value="0">Non-Aktif</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">No. Telepon</label>
                    <input type="text" class="form-control" name="no_tlp" id="edit_no_tlp">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Alamat</label>
                    <textarea class="form-control" name="alamat" id="edit_alamat" rows="2"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary fw-bold">Perbarui Member</button>
            </div>
        </form>
    </div>
</div>

<script>
function editMember(data) {
    document.getElementById('edit_id').value = data.id;
    document.getElementById('edit_nama').value = data.nama;
    document.getElementById('edit_nopol').value = data.nopol;
    document.getElementById('edit_jn_kendaraan').value = data.jn_kendaraan;
    document.getElementById('edit_tgl_mulai').value = data.tgl_mulai;
    document.getElementById('edit_tgl_akhir').value = data.tgl_akhir;
    document.getElementById('edit_status').value = data.status;
    document.getElementById('edit_no_tlp').value = data.no_tlp || '';
    document.getElementById('edit_alamat').value = data.alamat || '';
    new bootstrap.Modal(document.getElementById('modalEdit')).show();
}
</script>

<?php require_once '../app/views/layouts/footer.php'; ?>

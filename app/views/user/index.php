<?php require_once '../app/views/layouts/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-0">Kelola Pengguna & Histori Login</h4>
        <p class="text-muted small mb-0">Atur hak akses pengguna dan pantau catatan aktivitas login/logout petugas.</p>
    </div>
    <button type="button" class="btn btn-primary fw-bold" data-bs-toggle="modal" data-bs-target="#modalTambah">
        <i class="fa-solid fa-user-plus me-1"></i> Tambah Pengguna
    </button>
</div>

<!-- Nav Tabs -->
<ul class="nav nav-tabs mb-4" id="userTab" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active fw-bold" id="users-tab" data-bs-toggle="tab" data-bs-target="#users-panel" type="button" role="tab">
            <i class="fa-solid fa-users me-1"></i> Daftar Pengguna (<?= count($users) ?>)
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link fw-bold" id="history-tab" data-bs-toggle="tab" data-bs-target="#history-panel" type="button" role="tab">
            <i class="fa-solid fa-clock-rotate-left me-1"></i> Histori Aktivitas Login (<?= count($history ?? []) ?>)
        </button>
    </li>
</ul>

<div class="tab-content" id="userTabContent">
    <!-- Users Panel -->
    <div class="tab-pane fade show active" id="users-panel" role="tabpanel">
        <div class="card card-custom">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Nama Lengkap</th>
                                <th>Username</th>
                                <th>Level Access</th>
                                <th>Jabatan</th>
                                <th>Shift</th>
                                <th>Kontak</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $u): ?>
                                <tr>
                                    <td>
                                        <div class="fw-bold"><?= htmlspecialchars($u['nama']) ?></div>
                                        <div class="small text-muted"><?= htmlspecialchars($u['email'] ?: '-') ?></div>
                                    </td>
                                    <td><span class="badge bg-light text-dark border"><?= htmlspecialchars($u['username']) ?></span></td>
                                    <td>
                                        <span class="badge bg-<?= $u['level'] == 1 ? 'primary' : 'success' ?>">
                                            <?= htmlspecialchars($u['level_nama'] ?? ($u['level'] == 1 ? 'Administrator' : 'Kasir')) ?>
                                        </span>
                                    </td>
                                    <td><?= htmlspecialchars($u['jabatan']) ?></td>
                                    <td><?= htmlspecialchars($u['shift'] ?? 'Shift 1') ?></td>
                                    <td><?= htmlspecialchars($u['notlp'] ?: '-') ?></td>
                                    <td class="text-end">
                                        <button type="button" class="btn btn-sm btn-outline-primary me-1" 
                                                onclick="editUser(<?= htmlspecialchars(json_encode($u)) ?>)">
                                            <i class="fa-solid fa-pen-to-square"></i> Edit
                                        </button>
                                        <?php if ($u['id'] != Session::get('user_id')): ?>
                                            <a href="<?= BASE_URL ?>/user/delete/<?= $u['id'] ?>" 
                                               class="btn btn-sm btn-outline-danger" 
                                               onclick="return confirm('Hapus pengguna ini?')">
                                                <i class="fa-solid fa-trash"></i> Hapus
                                            </a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Login History Panel -->
    <div class="tab-pane fade" id="history-panel" role="tabpanel">
        <div class="card card-custom">
            <div class="card-header bg-light fw-bold text-dark">
                <i class="fa-solid fa-table-list me-1 text-primary"></i> Catatan Histori Login & Logout Petugas (Tabel: <code>history_login</code>)
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>ID Record</th>
                                <th>Pengguna</th>
                                <th>Level</th>
                                <th>Waktu Login (`w_login`)</th>
                                <th>Waktu Logout (`w_logout`)</th>
                                <th>Lokasi / Pos</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($history)): ?>
                                <tr>
                                    <td colspan="7" class="text-center py-4 text-muted">Belum ada catatan histori login.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($history as $h): ?>
                                    <tr>
                                        <td><span class="badge bg-secondary">#<?= $h['id'] ?></span></td>
                                        <td>
                                            <div class="fw-bold"><?= htmlspecialchars($h['nama_user'] ?? 'User ID: ' . $h['iduser']) ?></div>
                                            <div class="small text-muted">@<?= htmlspecialchars($h['username'] ?? '-') ?></div>
                                        </td>
                                        <td>
                                            <span class="badge bg-<?= $h['level'] == 1 ? 'primary' : 'success' ?>">
                                                <?= htmlspecialchars($h['level_nama'] ?? ($h['level'] == 1 ? 'Admin' : 'Kasir')) ?>
                                            </span>
                                        </td>
                                        <td><span class="text-success fw-bold"><i class="fa-solid fa-right-to-bracket me-1"></i> <?= htmlspecialchars($h['w_login']) ?></span></td>
                                        <td>
                                            <?php if (!empty($h['w_logout'])): ?>
                                                <span class="text-danger fw-bold"><i class="fa-solid fa-right-from-bracket me-1"></i> <?= htmlspecialchars($h['w_logout']) ?></span>
                                            <?php else: ?>
                                                <span class="badge bg-warning text-dark">Masih Sesi Aktif</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= htmlspecialchars($h['lokasi'] ?? 'Pos 1') ?></td>
                                        <td><?= htmlspecialchars($h['tgl']) ?></td>
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

<!-- Modal Tambah User -->
<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog">
        <form action="<?= BASE_URL ?>/user/store" method="POST" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Tambah Pengguna Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Nama Lengkap</label>
                    <input type="text" class="form-control" name="nama" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Username</label>
                    <input type="text" class="form-control" name="username" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Password</label>
                    <input type="password" class="form-control" name="password" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Level Pengguna</label>
                    <select class="form-select" name="level" required>
                        <?php foreach ($levels as $lvl): ?>
                            <option value="<?= $lvl['id'] ?>"><?= htmlspecialchars($lvl['nama']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Jabatan</label>
                    <input type="text" class="form-control" name="jabatan" value="Petugas Gate" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Shift Kerja</label>
                    <select class="form-select" name="shift">
                        <option value="Shift 1">Shift 1 (Pagi)</option>
                        <option value="Shift 2">Shift 2 (Siang/Sore)</option>
                        <option value="Shift 3">Shift 3 (Malam)</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Email</label>
                    <input type="email" class="form-control" name="email">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">No. Telepon</label>
                    <input type="text" class="form-control" name="notlp">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary fw-bold">Simpan User</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit User -->
<div class="modal fade" id="modalEdit" tabindex="-1">
    <div class="modal-dialog">
        <form action="<?= BASE_URL ?>/user/update" method="POST" class="modal-content">
            <input type="hidden" name="id" id="edit_id">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Edit User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Nama Lengkap</label>
                    <input type="text" class="form-control" name="nama" id="edit_nama" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Username</label>
                    <input type="text" class="form-control" name="username" id="edit_username" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Password Baru (Kosongkan jika tidak diubah)</label>
                    <input type="password" class="form-control" name="password" placeholder="••••••••">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Level Pengguna</label>
                    <select class="form-select" name="level" id="edit_level" required>
                        <?php foreach ($levels as $lvl): ?>
                            <option value="<?= $lvl['id'] ?>"><?= htmlspecialchars($lvl['nama']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Jabatan</label>
                    <input type="text" class="form-control" name="jabatan" id="edit_jabatan" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Shift Kerja</label>
                    <select class="form-select" name="shift" id="edit_shift">
                        <option value="Shift 1">Shift 1 (Pagi)</option>
                        <option value="Shift 2">Shift 2 (Siang/Sore)</option>
                        <option value="Shift 3">Shift 3 (Malam)</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Email</label>
                    <input type="email" class="form-control" name="email" id="edit_email">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">No. Telepon</label>
                    <input type="text" class="form-control" name="notlp" id="edit_notlp">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary fw-bold">Perbarui User</button>
            </div>
        </form>
    </div>
</div>

<script>
function editUser(data) {
    document.getElementById('edit_id').value = data.id;
    document.getElementById('edit_nama').value = data.nama;
    document.getElementById('edit_username').value = data.username;
    document.getElementById('edit_level').value = data.level;
    document.getElementById('edit_jabatan').value = data.jabatan;
    document.getElementById('edit_shift').value = data.shift || 'Shift 1';
    document.getElementById('edit_email').value = data.email || '';
    document.getElementById('edit_notlp').value = data.notlp || '';
    new bootstrap.Modal(document.getElementById('modalEdit')).show();
}
</script>

<?php require_once '../app/views/layouts/footer.php'; ?>

<?php require_once '../app/views/layouts/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-0 text-dark"><i class="fa-solid fa-clock-rotate-left text-primary me-2"></i> Master Jam Shift Kerja</h4>
        <span class="text-muted small">Atur jadwal jam mulai dan jam selesai untuk masing-masing shift petugas parkir (Tabel jamshift)</span>
    </div>
    <a href="<?= BASE_URL ?>/setoran" class="btn btn-outline-secondary">
        <i class="fa-solid fa-arrow-left me-1"></i> Kembali ke Setoran
    </a>
</div>

<?php Session::flash(); ?>

<div class="card card-custom" style="max-width: 800px;">
    <div class="card-header bg-white py-3">
        <h6 class="fw-bold mb-0 text-dark"><i class="fa-solid fa-business-time me-2 text-secondary"></i> Daftar Shift Petugas</h6>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Nama Shift</th>
                        <th>Jam Mulai (Masuk)</th>
                        <th>Jam Selesai (Pulang)</th>
                        <th>Rentang Waktu</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($shifts as $sh): ?>
                        <tr>
                            <td><?= $sh['id'] ?></td>
                            <td class="fw-bold text-primary">Shift <?= htmlspecialchars($sh['nama']) ?></td>
                            <td><span class="badge bg-success fs-6"><?= substr($sh['jama'], 0, 5) ?></span></td>
                            <td><span class="badge bg-danger fs-6"><?= substr($sh['jamb'], 0, 5) ?></span></td>
                            <td class="text-muted"><?= substr($sh['jama'], 0, 5) ?> s/d <?= substr($sh['jamb'], 0, 5) ?> WIB</td>
                            <td class="text-end">
                                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalShift<?= $sh['id'] ?>">
                                    <i class="fa-solid fa-pen-to-square"></i> Edit
                                </button>
                            </td>
                        </tr>

                        <!-- Modal Edit Shift -->
                        <div class="modal fade" id="modalShift<?= $sh['id'] ?>" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="<?= BASE_URL ?>/setoran/updateShift" method="POST">
                                        <input type="hidden" name="id" value="<?= $sh['id'] ?>">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit Jam Shift <?= htmlspecialchars($sh['nama']) ?></h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Nama Shift</label>
                                                <input type="text" class="form-control" name="nama" value="<?= htmlspecialchars($sh['nama']) ?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Jam Mulai (Masuk)</label>
                                                <input type="time" class="form-control" name="jama" value="<?= substr($sh['jama'], 0, 5) ?>" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Jam Selesai (Pulang)</label>
                                                <input type="time" class="form-control" name="jamb" value="<?= substr($sh['jamb'], 0, 5) ?>" required>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-primary">Simpan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once '../app/views/layouts/footer.php'; ?>

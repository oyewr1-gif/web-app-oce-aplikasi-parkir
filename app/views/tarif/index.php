<?php require_once '../app/views/layouts/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-0 text-dark"><i class="fa-solid fa-tags text-primary me-2"></i> Master Tarif & Diskon</h4>
        <span class="text-muted small">Kelola seluruh skema tarif parkir, denda menginap, tiket hilang, diskon, dan hari libur</span>
    </div>
</div>

<?php Session::flash(); ?>

<div class="card card-custom">
    <div class="card-header bg-white border-bottom-0 pb-0">
        <ul class="nav nav-tabs card-header-tabs" id="tarifTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link <?= $active_tab == 'progresif' ? 'active fw-bold' : '' ?>" href="<?= BASE_URL ?>/tarif?tab=progresif">
                    <i class="fa-solid fa-clock me-1 text-primary"></i> Progresif (Jam)
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= $active_tab == 'flat' ? 'active fw-bold' : '' ?>" href="<?= BASE_URL ?>/tarif?tab=flat">
                    <i class="fa-solid fa-money-bill-wave me-1 text-success"></i> Tarif Flat
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= $active_tab == 'inap' ? 'active fw-bold' : '' ?>" href="<?= BASE_URL ?>/tarif?tab=inap">
                    <i class="fa-solid fa-moon me-1 text-warning"></i> Denda Inap
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= $active_tab == 'lost' ? 'active fw-bold' : '' ?>" href="<?= BASE_URL ?>/tarif?tab=lost">
                    <i class="fa-solid fa-ticket-simple me-1 text-danger"></i> Denda Tiket Hilang
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= $active_tab == 'maksimal' ? 'active fw-bold' : '' ?>" href="<?= BASE_URL ?>/tarif?tab=maksimal">
                    <i class="fa-solid fa-arrow-up-right-dots me-1 text-info"></i> Tarif Maksimal
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= $active_tab == 'diskon' ? 'active fw-bold' : '' ?>" href="<?= BASE_URL ?>/tarif?tab=diskon">
                    <i class="fa-solid fa-percent me-1 text-secondary"></i> Diskon & Voucher
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= $active_tab == 'libur' ? 'active fw-bold' : '' ?>" href="<?= BASE_URL ?>/tarif?tab=libur">
                    <i class="fa-solid fa-calendar-day me-1 text-danger"></i> Hari Libur
                </a>
            </li>
        </ul>
    </div>

    <div class="card-body p-4">
        <!-- TAB 1: PROGRESIF -->
        <?php if ($active_tab == 'progresif'): ?>
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-bold mb-0 text-secondary">Tarif Jam Pertama & Jam Berikutnya (Progresif)</h6>
                <span class="badge bg-primary">Tabel: tarif_awal & tarif_berjalan</span>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Kode</th>
                            <th>Jenis Kendaraan</th>
                            <th>Tarif Jam Pertama (Rp)</th>
                            <th>Tarif Jam Berikutnya (Rp)</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($progresif as $p): ?>
                            <tr>
                                <td><span class="badge bg-secondary"><?= htmlspecialchars($p['jn_kendaraan']) ?></span></td>
                                <td class="fw-bold"><?= htmlspecialchars($p['nama']) ?></td>
                                <td class="text-success fw-bold">Rp <?= number_format($p['tarif_awal']) ?></td>
                                <td class="text-primary fw-bold">Rp <?= number_format($p['tarif_berjalan']) ?> / jam</td>
                                <td class="text-end">
                                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalProgresif<?= $p['id'] ?>">
                                        <i class="fa-solid fa-pen-to-square"></i> Edit
                                    </button>
                                </td>
                            </tr>

                            <!-- Modal Edit Progresif -->
                            <div class="modal fade" id="modalProgresif<?= $p['id'] ?>" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form action="<?= BASE_URL ?>/tarif/updateProgresif" method="POST">
                                            <input type="hidden" name="kode" value="<?= htmlspecialchars($p['jn_kendaraan']) ?>">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Edit Tarif Progresif: <?= htmlspecialchars($p['nama']) ?></h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">Tarif Jam Pertama (Rp)</label>
                                                    <input type="number" class="form-control" name="tarif_awal" value="<?= $p['tarif_awal'] ?>" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">Tarif Jam Berikutnya (Rp)</label>
                                                    <input type="number" class="form-control" name="tarif_berjalan" value="<?= $p['tarif_berjalan'] ?>" required>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

        <!-- TAB 2: FLAT -->
        <?php elseif ($active_tab == 'flat'): ?>
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-bold mb-0 text-secondary">Skema Tarif Flat (Satu Kali Masuk)</h6>
                <span class="badge bg-success">Tabel: tarif_flat</span>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Kode</th>
                            <th>Kategori Kendaraan</th>
                            <th>Tarif Flat (Rp)</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($flat as $f): ?>
                            <tr>
                                <td><span class="badge bg-secondary"><?= htmlspecialchars($f['kode']) ?></span></td>
                                <td class="fw-bold"><?= htmlspecialchars($f['nama']) ?></td>
                                <td class="text-success fw-bold">Rp <?= number_format($f['tarif']) ?></td>
                                <td class="text-end">
                                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalFlat<?= $f['id'] ?>">
                                        <i class="fa-solid fa-pen-to-square"></i> Edit
                                    </button>
                                </td>
                            </tr>

                            <!-- Modal Edit Flat -->
                            <div class="modal fade" id="modalFlat<?= $f['id'] ?>" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form action="<?= BASE_URL ?>/tarif/updateFlat" method="POST">
                                            <input type="hidden" name="id" value="<?= $f['id'] ?>">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Edit Tarif Flat (<?= htmlspecialchars($f['nama']) ?>)</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">Nominal Tarif Flat (Rp)</label>
                                                    <input type="number" class="form-control" name="tarif" value="<?= $f['tarif'] ?>" required>
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

        <!-- TAB 3: INAP -->
        <?php elseif ($active_tab == 'inap'): ?>
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-bold mb-0 text-secondary">Denda / Tambahan Tarif Menginap Harian</h6>
                <span class="badge bg-warning text-dark">Tabel: tarif_inap</span>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Kode</th>
                            <th>Jenis Kendaraan</th>
                            <th>Denda Inap / Hari (Rp)</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($inap as $i): ?>
                            <tr>
                                <td><span class="badge bg-secondary"><?= htmlspecialchars($i['kode']) ?></span></td>
                                <td class="fw-bold"><?= htmlspecialchars($i['nama']) ?></td>
                                <td class="text-warning fw-bold text-dark">Rp <?= number_format($i['tarif']) ?> / malam</td>
                                <td class="text-end">
                                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalInap<?= $i['id'] ?>">
                                        <i class="fa-solid fa-pen-to-square"></i> Edit
                                    </button>
                                </td>
                            </tr>

                            <!-- Modal Edit Inap -->
                            <div class="modal fade" id="modalInap<?= $i['id'] ?>" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form action="<?= BASE_URL ?>/tarif/updateInap" method="POST">
                                            <input type="hidden" name="id" value="<?= $i['id'] ?>">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Edit Denda Inap (<?= htmlspecialchars($i['nama']) ?>)</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">Nominal Denda Menginap (Rp)</label>
                                                    <input type="number" class="form-control" name="tarif" value="<?= $i['tarif'] ?>" required>
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

        <!-- TAB 4: LOST TICKET -->
        <?php elseif ($active_tab == 'lost'): ?>
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-bold mb-0 text-secondary">Denda Tiket Parkir Hilang</h6>
                <span class="badge bg-danger">Tabel: tarif_lost</span>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Kode</th>
                            <th>Jenis Kendaraan</th>
                            <th>Denda Tiket Hilang (Rp)</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($lost as $l): ?>
                            <tr>
                                <td><span class="badge bg-secondary"><?= htmlspecialchars($l['kode']) ?></span></td>
                                <td class="fw-bold"><?= htmlspecialchars($l['nama']) ?></td>
                                <td class="text-danger fw-bold">Rp <?= number_format($l['tarif']) ?></td>
                                <td class="text-end">
                                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalLost<?= $l['id'] ?>">
                                        <i class="fa-solid fa-pen-to-square"></i> Edit
                                    </button>
                                </td>
                            </tr>

                            <!-- Modal Edit Lost -->
                            <div class="modal fade" id="modalLost<?= $l['id'] ?>" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form action="<?= BASE_URL ?>/tarif/updateLost" method="POST">
                                            <input type="hidden" name="id" value="<?= $l['id'] ?>">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Edit Denda Tiket Hilang (<?= htmlspecialchars($l['nama']) ?>)</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">Nominal Denda Hilang (Rp)</label>
                                                    <input type="number" class="form-control" name="tarif" value="<?= $l['tarif'] ?>" required>
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

        <!-- TAB 5: MAKSIMAL -->
        <?php elseif ($active_tab == 'maksimal'): ?>
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-bold mb-0 text-secondary">Batas Tarif Maksimal Per Hari (Cap Tarif)</h6>
                <span class="badge bg-info text-dark">Tabel: tarif_maksimal</span>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Kode</th>
                            <th>Jenis Kendaraan</th>
                            <th>Tarif Maksimal (Rp)</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($maksimal as $m): ?>
                            <tr>
                                <td><span class="badge bg-secondary"><?= htmlspecialchars($m['kode']) ?></span></td>
                                <td class="fw-bold"><?= htmlspecialchars($m['nama']) ?></td>
                                <td class="text-info fw-bold text-dark">Rp <?= number_format($m['tarif']) ?></td>
                                <td class="text-end">
                                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalMaksimal<?= $m['id'] ?>">
                                        <i class="fa-solid fa-pen-to-square"></i> Edit
                                    </button>
                                </td>
                            </tr>

                            <!-- Modal Edit Maksimal -->
                            <div class="modal fade" id="modalMaksimal<?= $m['id'] ?>" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form action="<?= BASE_URL ?>/tarif/updateMaksimal" method="POST">
                                            <input type="hidden" name="id" value="<?= $m['id'] ?>">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Edit Batas Tarif Maksimal (<?= htmlspecialchars($m['nama']) ?>)</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">Batas Maksimal Biaya (Rp)</label>
                                                    <input type="number" class="form-control" name="tarif" value="<?= $m['tarif'] ?>" required>
                                                    <span class="text-muted small">Jika diisi 0, maka tidak ada batas maksimal (tarif berjalan terus).</span>
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

        <!-- TAB 6: DISKON & VOUCHER -->
        <?php elseif ($active_tab == 'diskon'): ?>
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-bold mb-0 text-secondary">Daftar Kode Promo & Diskon Tarif</h6>
                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalTambahDiskon">
                    <i class="fa-solid fa-plus me-1"></i> Tambah Diskon / Voucher
                </button>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Kode Voucher</th>
                            <th>Nama Diskon</th>
                            <th>Potongan (Rp)</th>
                            <th>Status</th>
                            <th>Waktu Dibuat</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($diskon)): ?>
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">Belum ada data voucher / diskon tarif.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($diskon as $d): ?>
                                <tr>
                                    <td><span class="badge bg-dark fs-6"><?= htmlspecialchars($d['kode_trx']) ?></span></td>
                                    <td class="fw-bold"><?= htmlspecialchars($d['nama']) ?></td>
                                    <td class="text-success fw-bold">Rp <?= number_format($d['nilai']) ?></td>
                                    <td>
                                        <a href="<?= BASE_URL ?>/tarif/toggleDiskon/<?= $d['id'] ?>" class="badge text-decoration-none <?= $d['status_aktif'] == 'AKTIF' ? 'bg-success' : 'bg-secondary' ?>">
                                            <?= htmlspecialchars($d['status_aktif']) ?>
                                        </a>
                                    </td>
                                    <td class="text-muted small"><?= htmlspecialchars($d['waktu_buat']) ?></td>
                                    <td class="text-end">
                                        <a href="<?= BASE_URL ?>/tarif/deleteDiskon/<?= $d['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus voucher diskon ini?')">
                                            <i class="fa-solid fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Modal Tambah Diskon -->
            <div class="modal fade" id="modalTambahDiskon" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form action="<?= BASE_URL ?>/tarif/addDiskon" method="POST">
                            <div class="modal-header">
                                <h5 class="modal-title">Tambah Kode Diskon / Voucher</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Kode Voucher / Promo</label>
                                    <input type="text" class="form-control text-uppercase" name="kode_trx" placeholder="misal: PROMO2026" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Nama Keterangan Diskon</label>
                                    <input type="text" class="form-control" name="nama" placeholder="misal: Potongan Belanja Tenant" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Nilai Potongan (Rp)</label>
                                    <input type="number" class="form-control" name="nilai" placeholder="misal: 2000" min="0" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary">Simpan Diskon</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        <!-- TAB 7: HARI LIBUR -->
        <?php elseif ($active_tab == 'libur'): ?>
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-bold mb-0 text-secondary">Kalender Hari Libur Nasional (Skema Tarif Libur)</h6>
                <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#modalTambahLibur">
                    <i class="fa-solid fa-plus me-1"></i> Tambah Hari Libur
                </button>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle" style="max-width: 600px;">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Tanggal Libur</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($hari_libur)): ?>
                            <tr>
                                <td colspan="3" class="text-center py-4 text-muted">Belum ada data kalender hari libur nasional.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($hari_libur as $hl): ?>
                                <tr>
                                    <td><?= $hl['id'] ?></td>
                                    <td class="fw-bold text-danger"><i class="fa-solid fa-calendar-day me-2"></i><?= htmlspecialchars($hl['tgl']) ?></td>
                                    <td class="text-end">
                                        <a href="<?= BASE_URL ?>/tarif/deleteLibur/<?= $hl['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus tanggal libur ini?')">
                                            <i class="fa-solid fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Modal Tambah Hari Libur -->
            <div class="modal fade" id="modalTambahLibur" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form action="<?= BASE_URL ?>/tarif/addLibur" method="POST">
                            <div class="modal-header">
                                <h5 class="modal-title">Tambah Tanggal Hari Libur</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Pilih Tanggal</label>
                                    <input type="date" class="form-control" name="tgl" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-danger">Simpan Hari Libur</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        <?php endif; ?>
    </div>
</div>

<?php require_once '../app/views/layouts/footer.php'; ?>

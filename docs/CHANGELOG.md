# Catatan Pembaruan & Riwayat Perubahan (Changelog)

Seluruh riwayat penambahan fitur, perubahan skema penanganan data, dan modifikasi struktur pada **Aplikasi Manajemen Parkir PHP MVC** dicatat secara rinci di dokumen ini.

---

## 🚀 Versi 2.1.1 (Perbaikan Tombol Proses Keluar & Penanganan Kendaraan Tanpa Nopol) - 2026-10-08

### 📌 Ringkasan Masalah & Pembaruan
Pembaruan ini menyelesaikan kendala pada modul **Kendaraan Keluar** (`/parkir/keluar`), di mana tombol **Proses Keluar** pada tabel **Daftar Kendaraan Sedang Parkir** tidak berfungsi (hanya me-refresh halaman) ketika diklik pada baris kendaraan yang memiliki nomor polisi kosong (`nopol` kosong/null).

---

### 🔍 Analisis Penyebab Masalah (Root Cause)
1. **Parameter Query URL Berbasis Nopol**:
   Pada view `app/views/parkir/keluar.php`, tautan tombol aksi proses keluar sebelumnya dibuat menggunakan format:
   ```html
   <a href="<?= BASE_URL ?>/parkir/keluar?keyword=<?= urlencode($row['nopol']) ?>" class="btn btn-warning">
   ```
2. **Kueri Kosong Saat Nopol Kosong**:
   Ketika kendaraan masuk tanpa nomor polisi (misalnya dari mesin tiket/dispenser otomatis tanpa kamera LPR), kolom `nopol` bernilai kosong (`""`). Akibatnya, parameter URL yang dihasilkan adalah `?keyword=`.
3. **Pengecekan Controller Mengabaikan Kueri Kosong**:
   Di `ParkirController::keluar()`, terdapat validasi `if (!empty($_GET['keyword']))`. Karena `keyword` kosong, controller tidak memproses transaksi apa pun dan form rincian pembayaran tidak pernah terbuka.

---

### 🛠️ Solusi & Perubahan yang Diterapkan

#### 1. Perubahan Parameter Tombol Aksi ke `idtrx`
- Mengubah tautan tombol aksi pada tabel "Daftar Kendaraan Sedang Parkir" (`app/views/parkir/keluar.php`) dari `urlencode($row['nopol'])` menjadi `urlencode($row['idtrx'])`:
  ```html
  <a href="<?= BASE_URL ?>/parkir/keluar?keyword=<?= urlencode($row['idtrx']) ?>" class="btn btn-warning fw-bold" title="Proses Keluar">
      <i class="fa-solid fa-calculator"></i>
  </a>
  ```
- Karena `idtrx` selalu terisi dan bersifat unik untuk setiap tiket/transaksi parkir (misal: `TRX...` atau nomor barcode), tombol aksi dipastikan selalu valid dan berhasil membuka rincian pembayaran.

#### 2. Visualisasi Status Kendaraan Tanpa Nopol
- Pada tabel daftar kendaraan sedang parkir, jika `nopol` kosong, sistem kini menampilkan badge informatif:
  ```html
  <span class="badge bg-secondary fs-6 text-white-50"><i class="fa-solid fa-minus me-1"></i>Tanpa Nopol</span>
  ```
- Pada modal pembatalan transaksi (VOID), nomor polisi yang kosong ditampilkan sebagai `Tanpa Nopol` (tidak lagi kosong/blank).

#### 3. Form Input Nopol Saat Checkout Keluar (Opsional)
- Pada form rincian pembayaran keluar (`app/views/parkir/keluar.php`):
  - Jika transaksi memiliki nomor polisi (`!empty($trx['nopol'])`), nopol ditampilkan seperti biasa dalam teks tebal warna biru beserta hidden input `nopol_update`.
  - Jika transaksi tidak memiliki nomor polisi, sistem menampilkan input teks yang **bersifat opsional (tidak wajib diisi)**:
    ```html
    <input type="text" name="nopol_update" class="form-control text-uppercase fw-bold text-end border-secondary" placeholder="Ketik Plat (Opsional)" autocomplete="off">
    <small class="text-muted d-block mt-1"><i class="fa-solid fa-circle-info me-1"></i>Plat kosong saat masuk (opsional / boleh dilewati).</small>
    ```
    Hal ini memberikan fleksibilitas tinggi: kasir dapat mengetik plat nomor jika ingin melengkapi data/mengecek member, atau dapat langsung memproses pembayaran tanpa mengetik plat untuk mempercepat alur antrean keluar (*fast checkout*).

#### 4. Pembaruan Logika Pemrosesan & Database
- **`ParkirController::prosesKeluar()`**:
  - Menangkap nilai input `nopol_update` dari `$_POST`.
  - Menggunakan nopol baru untuk memvalidasi status keanggotaan/member (jika plat nomor yang diinput kasir terdaftar sebagai member langganan aktif, tarif otomatis disesuaikan menjadi Rp 0).
  - Meneruskan data `nopol` ke method `catatKeluar()`.
- **`ParkirModel::catatKeluar()`**:
  - Memperbarui query `UPDATE jurnal_transaksi` dengan `nopol = COALESCE(NULLIF(:nopol, ''), nopol)`.
  - Memperbarui kolom `nopol` transaksi di database dengan nilai plat nomor yang diisi oleh kasir tanpa merusak data jika nopol sebelumnya sudah ada.
- **`ParkirModel::hitungTarif()` & `getTarifLostByKendaraan()`**:
  - Menambahkan dukungan pencarian tarif berbasis nama kendaraan maupun kode jenis kendaraan (`WHERE (j.nama = :nama OR j.jn_kendaraan = :nama)`) untuk mendukung transaksi masuk lawas yang mencatat kode jenis (seperti `'C'` atau `'B'`).
- **`KendaraanModel::incrementOccupancy()` & `decrementOccupancy()`**:
  - Menambahkan pengecekan fleksibel `WHERE (nama = :nama OR jn_kendaraan = :nama)` agar kuota kapasitas kendaraan terpakai terhitung akurat.
- **`app/views/parkir/struk.php`**:
  - Menambahkan fallback `'-'` pada struk jika plat nomor tidak terisi.

---

### 📂 Berkas yang Dimodifikasi
- [`app/views/parkir/keluar.php`](file:///d:/Data/dev/web/aplikasi-parkir/app/views/parkir/keluar.php)
- [`app/controllers/ParkirController.php`](file:///d:/Data/dev/web/aplikasi-parkir/app/controllers/ParkirController.php)
- [`app/models/ParkirModel.php`](file:///d:/Data/dev/web/aplikasi-parkir/app/models/ParkirModel.php)
- [`app/models/KendaraanModel.php`](file:///d:/Data/dev/web/aplikasi-parkir/app/models/KendaraanModel.php)
- [`app/views/parkir/struk.php`](file:///d:/Data/dev/web/aplikasi-parkir/app/views/parkir/struk.php)
- [`docs/CHANGELOG.md`](file:///d:/Data/dev/web/aplikasi-parkir/docs/CHANGELOG.md)
- [`docs/USER_GUIDE.md`](file:///d:/Data/dev/web/aplikasi-parkir/docs/USER_GUIDE.md)

---

## 🚀 Versi 2.1.0 (Restrukturisasi Navigasi Admin & Perbaikan Dashboard) - 2026-10-08

### 📌 Ringkasan Pembaruan
Pembaruan ini berfokus pada penyempurnaan antarmuka pengguna (UI/UX) pada sidebar navigasi Administrator dengan mengelompokkan seluruh menu master dan manajemen pengguna ke dalam satu grup dropdown (*collapsible group*), serta perbaikan kompatibilitas method pada dashboard admin.

---

### 🎨 Perubahan Struktur Navigasi Menu Admin

#### 1. Pengelompokan Menu Master & Kelola User ke Dropdown Menu
Seluruh menu master data dan manajemen pengguna yang sebelumnya tersebar secara terpisah pada sidebar kini dikelompokkan ke dalam satu grup dropdown interaktif:

```text
SEBELUM (Menu Flat):
[ADMINISTRATOR]
├── 🚗 Master Kendaraan
├── 🏷️ Master Tarif & Diskon
├── 🧾 Setoran Kasir & Shift
├── 👥 Kelola User / Kasir
└── 📈 Laporan & Audit Void

SESUDAH (Dropdown Terstruktur):
[ADMINISTRATOR]
├── ▼ 📁 Master & User (Dropdown Collapsible #menuMasterUser)
│   ├── [MASTER DATA]
│   │   ├── 🚗 Master Kendaraan       (/kendaraan)
│   │   ├── 🏷️ Master Tarif & Diskon  (/tarif)
│   │   └── ⏱️ Master Jam Shift        (/setoran/shift)
│   └── [PENGGUNA]
│       └── 👥 Kelola User / Kasir    (/user)
├── 🧾 Setoran Kasir                  (/setoran)
└── 📈 Laporan & Audit Void           (/laporan)
```

#### 2. Mekanisme Teknis & Peningkatan UI
- **Komponen Bootstrap 5 Collapse**: Menggunakan elemen `data-bs-toggle="collapse"` yang terhubung dengan target `#menuMasterUser`.
- **Logika Auto-Expand Berdasarkan Halaman Aktif**:
  ```php
  $isMasterActive = (
      strpos($_SERVER['REQUEST_URI'], 'kendaraan') !== false ||
      strpos($_SERVER['REQUEST_URI'], 'tarif') !== false ||
      strpos($_SERVER['REQUEST_URI'], 'user') !== false ||
      strpos($_SERVER['REQUEST_URI'], 'setoran/shift') !== false
  );
  ```
  Dropdown secara otomatis berstatus terbuka (`class="collapse show"` dan `aria-expanded="true"`) apabila pengguna sedang berada di salah satu halaman sub-menu terkait, dan berstatus tertutup rapi saat pengguna berada di halaman lain (Dashboard, Transaksi Masuk/Keluar, Laporan, atau Setoran Kasir).
- **Styling Kustom CSS (`public/css/style.css`)**:
  - `.sidebar-dropdown-toggle`: Kursor pointer dan penataan fleksibel.
  - `.chevron-icon`: Animasi transisi rotasi panah $180^\circ$ saat dropdown dibuka.
  - `.sidebar-submenu`: Kontainer sub-menu beraksen latar gelap transparan dengan *left-border* aksen halus.
  - `.submenu-item`: Penataan padding, ukuran teks proporsional, dan penyorotan (*highlight*) saat sub-menu aktif.

---

### 🐛 Perbaikan Bug & Kompatibilitas Method

#### Perbaikan Fatal Error pada Dashboard Admin
- **Isu**: Terjadi error `Fatal error: Uncaught Error: Call to undefined method ParkirModel::getActiveTransactions() in DashboardController.php on line 17`.
- **Penyebab**: Perbedaan penamaan pemanggilan method di controller (`getActiveTransactions` dan `getRecentCompletedTransactions`) dengan deklarasi method di model (`getActiveVehicles` dan `getRecentCompleted`).
- **Solusi**: Menambahkan alias method di `app/models/ParkirModel.php`:
  - `getActiveTransactions($limit = 10)` $\rightarrow$ meneruskan ke `getActiveVehicles($limit)`
  - `getRecentCompletedTransactions($limit = 10)` $\rightarrow$ meneruskan ke `getRecentCompleted($limit)`
  - `getTransactionByIdTrx($idtrx)` $\rightarrow$ meneruskan ke `getTransactionById($idtrx)`

---

### 🛠️ Berkas yang Dimodifikasi pada Rilis 2.1.0

| Berkas | Jenis Perubahan | Deskripsi Perubahan |
|---|---|---|
| `app/views/layouts/header.php` | Modifikasi UI | Implementasi grup dropdown collapsible `#menuMasterUser`, sub-heading Master Data & Pengguna, serta logika auto-expand `$isMasterActive` |
| `public/css/style.css` | Modifikasi Style | Penambahan rule CSS untuk `.sidebar-dropdown-toggle`, `.chevron-icon`, `.sidebar-submenu`, dan `.submenu-item` |
| `app/models/ParkirModel.php` | Bug Fix / Logic | Penambahan method alias `getActiveTransactions`, `getRecentCompletedTransactions`, dan `getTransactionByIdTrx` |
| `docs/API_AND_MODELS.md` | Dokumentasi | Penambahan dokumentasi method alias transaksi aktif dan transaksi baru selesai |
| `docs/USER_GUIDE.md` | Dokumentasi | Penambahan panduan navigasi grup dropdown pada sidebar admin |
| `docs/WALKTHROUGH.md` | Dokumentasi | Pencatatan riwayat pemecahan isu dashboard dan restrukturisasi menu |
| `docs/CHANGELOG.md` | Dokumentasi | Pencatatan lengkap catatan rilis versi 2.1.0 |

---

## 🚀 Versi 2.0.0 (Pembaruan Skema Penuh Database `parkir_db`) - 2026-10-08

### 📌 Ringkasan Pembaruan
Berdasarkan analisis menyeluruh terhadap 90 tabel dasar (*base tables*) dan 32 view pada database `parkir_db`, dilakukan ekspansi arsitektur besar untuk melengkapi fitur operasional kasir, sistem keuangan, penanganan denda, dan otorisasi pembatalan.

---

### ✨ Fitur Baru yang Ditambahkan

#### 1. Modul Master Tarif & Diskon Terpadu (`TarifController` & `TarifModel`)
- **Rute URL**: `/tarif` (dengan parameter tab: `progresif`, `flat`, `inap`, `lost`, `maksimal`, `diskon`, `libur`).
- **Tabel Basis**:
  - `tarif_awal` & `tarif_berjalan` (Tarif jam pertama & jam progresif).
  - `tarif_flat` (Tarif sekali masuk).
  - `tarif_inap` (Denda parkir menginap harian).
  - `tarif_lost` (Denda kehilangan karcis/tiket parkir).
  - `tarif_maksimal` (Plafon batas maksimal tarif harian).
  - `discount_tarif` (Kupon voucher & diskon tarif promo).
  - `hari_libur` (Kalender hari libur nasional untuk tarif akhir pekan).
- **Tampilan UI**: Halaman modern berbasis Bootstrap 5 Nav-Tabs dengan modal formulir edit interaktif.

#### 2. Modul Rekap Setoran Kasir & Jam Shift (`SetoranController` & `SetoranModel`)
- **Rute URL**:
  - `/setoran`: Daftar riwayat berita acara setoran kasir.
  - `/setoran/create`: Formulir rekonsiliasi kas (perhitungan pendapatan sistem vs hitungan fisik kasir).
  - `/setoran/detail/{id}`: Rincian penerimaan per kategori kendaraan (Tunai, QRIS, E-Money).
  - `/setoran/cetak/{id}`: Dokumen Berita Acara Serah Terima Setoran siap cetak (*print-friendly*).
  - `/setoran/shift`: Pengaturan jam mulai dan jam selesai shift operasional.
- **Tabel Basis**: `data_setoran`, `detail_setoran`, `jamshift`, `pos_kasir`.

#### 3. Penanganan Kasus Tiket Hilang (*Lost Ticket*)
- **Integrasi**: Ditambahkan toggle *Tiket Hilang* pada halaman Gate Keluar (`/parkir/keluar`).
- **Kalkulasi**: Mengambil besaran denda otomatis dari tabel `tarif_lost` berdasarkan jenis kendaraan.
- **Verifikasi Pemilik**: Input data identitas pemilik kendaraan (No. STNK, No. KTP, Nama Pemilik, No. Handphone) yang langsung disimpan ke kolom `nostnk`, `noktp`, `nama`, `nohp` di tabel `jurnal_transaksi`.
- **Cetak Struk**: Menampilkan baris rincian denda kehilangan karcis dan nomor STNK pada struk thermal pembayaran.

#### 4. Pembayaran Multi-Metode (Tunai, QRIS, E-Money)
- **Metode**: Pilihan opsi pembayaran **Tunai**, **QRIS**, dan **E-Money/Prepaid** pada alur pembayaran Gate Keluar.
- **Pencatatan**: Kolom `cara_bayar` dan `refbayar` (nomor referensi / approval code transaksi non-tunai) pada tabel `jurnal_transaksi` dan rekonsiliasi `detail_setoran`.

#### 5. Fitur Pembatalan Transaksi (VOID) & Log Audit
- **Alur VOID**: Kasir maupun Supervisor dapat membatalkan transaksi aktif yang salah entri dengan mengklik tombol *Batalkan (Void)* dan mengisi alasan pembatalan.
- **Pencatatan**: Kolom `status = 'N'`, `waktubatal = NOW()`, `ketbatal`, dan `iduser_pembatalan` pada tabel `jurnal_transaksi`.
- **Laporan Audit**: Tab khusus *Riwayat Pembatalan (VOID)* pada menu Laporan (`/laporan?tab=void`) untuk memantau seluruh transaksi yang dibatalkan beserta alasannya.

#### 6. Integrasi Gate Masuk & Pos Keluar Dinamis
- Gerbang masuk membaca data mesin dispenser dari tabel `manless`.
- Pos kasir keluar membaca data gardu dari tabel `pos_kasir`.

---

### 🛠️ Berkas Baru & Berkas yang Dimodifikasi pada Rilis 2.0.0

| Tipe | Berkas | Deskripsi Perubahan |
|---|---|---|
| **Baru** | `app/controllers/TarifController.php` | Controller manajemen skema tarif lengkap & diskon |
| **Baru** | `app/controllers/SetoranController.php` | Controller rekap setoran, tutup shift, dan cetak berita acara |
| **Baru** | `app/models/TarifModel.php` | Model akses data tarif progresif, flat, inap, lost, maksimal, diskon, libur |
| **Baru** | `app/models/SetoranModel.php` | Model kueri agregasi pendapatan, header/detail setoran, dan jam shift |
| **Baru** | `app/views/tarif/index.php` | Antarmuka nav-tabs untuk manajemen tarif terpadu |
| **Baru** | `app/views/setoran/index.php` | Tampilan riwayat berita acara penutupan kasir |
| **Baru** | `app/views/setoran/create.php` | Tampilan form rekonsiliasi kas sistem vs uang fisik kasir |
| **Baru** | `app/views/setoran/detail.php` | Tampilan rincian penerimaan per kategori kendaraan |
| **Baru** | `app/views/setoran/cetak.php` | Format dokumen siap cetak serah terima kasir |
| **Baru** | `app/views/setoran/shift.php` | Tampilan master jam mulai & selesai shift kerja |
| **Modifikasi** | `app/models/ParkirModel.php` | Penambahan method lost ticket, multi-payment, dynamic gates, dan VOID |
| **Modifikasi** | `app/controllers/ParkirController.php` | Penanganan parameter checkout baru dan action void |
| **Modifikasi** | `app/views/parkir/keluar.php` | UI toggle tiket hilang, radio metode bayar, dan modal pembatalan void |
| **Modifikasi** | `app/views/parkir/masuk.php` | Pintu masuk & keluar dinamis dari `manless` dan `pos_kasir` |
| **Modifikasi** | `app/views/parkir/struk.php` | Menampilkan denda lost ticket, metode bayar, dan nomor referensi |
| **Modifikasi** | `app/models/LaporanModel.php` | Kueri laporan status 'N', getVoidReports, dan statistik total denda |
| **Modifikasi** | `app/controllers/LaporanController.php` | Dukungan tab void dan ringkasan data |
| **Modifikasi** | `app/views/laporan/index.php` | Tab Jurnal Transaksi dan Tab Log Audit Pembatalan (VOID) |
| **Modifikasi** | `app/views/layouts/header.php` | Penambahan menu Master Tarif & Diskon serta Setoran Kasir & Shift |
| **Modifikasi** | `core/Session.php` | Penambahan helper `authCheck()` |

---

### 🧪 Hasil Verifikasi & Pengujian
- Seluruh 34 berkas PHP lulus pengujian sintaks (`php -l`).
- Seluruh rute baru dan halaman view mengembalikan status HTTP `200 OK` pada server Apache XAMPP.
- Pengujian fungsional transaksi masuk, keluar dengan denda tiket hilang, pembayaran QRIS, cetak struk, pembatalan (VOID), tutup shift, dan cetak berita acara setoran berhasil 100%.

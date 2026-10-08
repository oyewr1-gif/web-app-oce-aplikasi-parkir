# Dokumentasi Walkthrough Pengembangan & Operasional Aplikasi Parkir PHP MVC

Dokumen ini berisi rangkuman komprehensif perjalanan pengembangan, arsitektur sistem, alur kerja operasional, penanganan kendala teknis, serta panduan deployment server XAMPP **Aplikasi Manajemen Parkir PHP MVC**.

---

## 📌 1. Pendahuluan

Aplikasi Parkir PHP MVC adalah sistem manajemen parkir mandiri berbasis web yang dirancang menggunakan konsep **Pure Custom PHP MVC (Model-View-Controller)** tanpa ketergantungan framework eksternal (*zero external framework dependency*). Aplikasi ini mendukung penuh pencatatan kendaraan masuk (*Gate IN*), transaksi pembayaran parkir keluar (*Gate OUT*), penanganan denda karcis hilang (*Lost Ticket*), pembayaran multi-metode (Tunai/QRIS/E-Money), pembatalan transaksi (*VOID*), tutup shift kasir & cetak berita acara setoran, manajemen multi-tarif & diskon, hingga rekapitulasi laporan pendapatan keuangan.

---

## 🏛️ 2. Arsitektur & Struktur Komponen

### A. Pola Desain (Front Controller MVC)
Setiap permintaan web diproses melalui **Front Controller** (`public/index.php`) dan diurai oleh router dinamis (`core/App.php`):

```
HTTP Request -> index.php -> App Router -> Controller -> Model (PDO) -> Database (parkir_db)
                                                |
                                                +-----> View Render (Bootstrap 5)
```

### B. Struktur Berkas Utama
- **`app/controllers/`**:
  - `AuthController.php`: Login & Logout.
  - `DashboardController.php`: Ringkasan Overview & Statistik Real-Time.
  - `ParkirController.php`: Transaksi Masuk, Transaksi Keluar, Tiket Hilang, Multi-Payment, VOID.
  - `TarifController.php`: Manajemen Master Tarif Progresif, Flat, Inap, Lost, Maksimal, Diskon, Hari Libur.
  - `SetoranController.php`: Rekapitulasi Kasir, Tutup Shift, Cetak Berita Acara, Master Jam Shift.
  - `KendaraanController.php`: Master Kategori Kendaraan & Okupansi.
  - `MemberController.php`: Manajemen Member Langganan.
  - `UserController.php`: Manajemen Akun & Riwayat Login.
  - `LaporanController.php`: Laporan Finansial & Audit Transaksi VOID.
- **`app/models/`**: `ParkirModel`, `TarifModel`, `SetoranModel`, `KendaraanModel`, `MemberModel`, `UserModel`, `LaporanModel`.
- **`core/`**: `App`, `Controller`, `Database`, `Session`.
- **`config/`**: `database.php` (Loader `.env` & Dynamic `BASE_URL`).
- **`.env`**: Konfigurasi Kredensial Database & Aplikasi.

---

## 🗄️ 3. Analisis Database `parkir_db` (90 Base Tables & 32 Views)

Database `parkir_db` memiliki skala data riil produksi (ratusan ribu baris) dengan integrasi fitur:
1. **`jurnal_transaksi`**: Menampung 366.000+ data transaksi, dilengkapi kolom `denda`, `nostnk`, `noktp`, `nohp`, `nama`, `cara_bayar`, `refbayar`, `waktubatal`, `ketbatal`, `iduser_pembatalan`.
2. **`data_setoran` & `detail_setoran`**: Rekapitulasi kasir per shift & breakdown per kategori kendaraan (Tunai, QRIS, Prepaid).
3. **`jamshift`**: Master 3 jadwal shift kerja (Pagi, Sore, Malam).
4. **`manless` & `pos_kasir`**: Master gerbang masuk otomatis dan pos kasir keluar.
5. **Skema Multi-Tarif**: `tarif_awal`, `tarif_berjalan`, `tarif_flat`, `tarif_inap`, `tarif_lost`, `tarif_maksimal`, `discount_tarif`, `hari_libur`.

---

## 🚀 4. Fitur-Fitur Utama & Alur Kerja

### A. Autentikasi Pengguna & Navigasi Dropdown Admin
- **Dua Peran Pengguna**: Administrator (`level = 1`) dan Petugas Kasir (`level = 2`).
- **Sidebar Dropdown Group**: Seluruh menu master (**Master Kendaraan**, **Master Tarif & Diskon**, **Master Jam Shift**) serta **Kelola User / Kasir** dikelompokkan ke dalam satu grup dropdown (*collapsible group*) `#menuMasterUser` yang otomatis membuka saat halaman terkait aktif.

### B. Transaksi Kendaraan Masuk (Gate IN)
- Petugas menginput Plat Nomor, memilih Jenis Kendaraan, memilih Pintu Gate Masuk (dari tabel `manless`), dan Gate Keluar (dari tabel `pos_kasir`).
- Sistem menggenerasi kode tiket unik `TRX...` dan mencetak Tiket Parkir Thermal dengan format barcode.

### C. Transaksi Kendaraan Keluar, Lost Ticket, & Multi-Payment (Gate OUT)
- Scan kode tiket atau cari plat nomor. Sistem menghitung durasi jam dan total biaya secara otomatis.
- **Deteksi Member Otomatis**: Nopol member aktif otomatis dikenakan tarif **Rp 0 (GRATIS)**.
- **Tiket Hilang**: Opsi centang tiket hilang otomatis menambahkan denda dari `tarif_lost` dan mencatat verifikasi STNK/KTP pemilik.
- **Multi-Payment**: Pilihan metode pembayaran **Tunai** (dengan hitung kembalian), **QRIS**, atau **E-Money** (dengan nomor referensi).
- **Cetak Struk**: Mencetak Struk Pembayaran Lunas memuat rincian denda kehilangan dan metode pembayaran.

### D. Fitur Pembatalan Transaksi (VOID) & Audit Log
- Kasir maupun Supervisor dapat membatalkan transaksi aktif yang keliru entri dengan mencantumkan alasan pembatalan.
- Kuota okupansi kendaraan otomatis dikembalikan dan tercatat di tab **Riwayat Pembatalan (VOID)** pada menu Laporan.

### E. Tutup Shift & Setoran Kasir (`/setoran`)
- Rekonsiliasi otomatis antara pendapatan di sistem dengan uang fisik kasir.
- Deteksi selisih otomatis (*Cocok*, *Lebih*, atau *Kurang*).
- Cetak Berita Acara Penerimaan Kasir dengan tanda tangan resmi Kasir dan Supervisor.

### F. Master Tarif & Diskon (`/tarif`)
- Antarmuka 7 Tab untuk pengaturan Tarif Progresif (Jam), Tarif Flat, Denda Inap, Denda Tiket Hilang, Cap Tarif Maksimal Harian, Kupon Voucher Diskon, dan Hari Libur Nasional.

---

## 🛠️ 5. Rekapitulasi Penanganan Kendala Teknis (Troubleshooting History)

Selama pengembangan dan penyempurnaan, sejumlah kendala teknis telah dianalisis dan diselesaikan secara tuntas:

1. **Perbaikan Eror Redirect Loop (*The page isn't redirecting properly*)**:
   - *Penyebab*: `App::parseUrl` gagal membaca rute saat `$_GET['url']` tidak terisi oleh `.htaccess`.
   - *Solusi*: Menambahkan parser fallback `$_SERVER['REQUEST_URI']` yang mampu mengurai rute di semua web server.

2. **Perbaikan Eror Memori (*Allowed memory size exhausted*)**:
   - *Penyebab*: Kueri awal mengambil seluruh riwayat transaksi tanpa batas pada dataset 366.000+ baris.
   - *Solusi*: Menerapkan pembatasan `LIMIT 15` s.d `50` pada kueri daftar aktif, sementara agregat menggunakan `SELECT COUNT(*)` dan `SELECT SUM(*)`.

3. **Deployment Server XAMPP dengan URL Bersih (*Clean URL*)**:
   - *Penyebab*: Tautan URL di subfolder XAMPP default menyertakan `/public/`.
   - *Solusi*: Mengatur `.htaccess` di root direktori untuk meneruskan request ke `public/` dan menetapkan `BASE_URL=http://localhost/aplikasi-parkir` pada berkas `.env`.

4. **Perbaikan Eror `Session::authCheck()` Undefined Method**:
   - *Penyebab*: Controller baru memanggil `Session::authCheck()` sementara method pada Session adalah `requireAdmin()`.
   - *Solusi*: Menambahkan helper `authCheck($role = 'Administrator')` di `core/Session.php` dan merapikan pemanggilan `requireAdmin()`.

5. **Perbaikan Eror Fatal `getActiveTransactions()` pada Dashboard Admin**:
   - *Penyebab*: `DashboardController.php` memanggil `getActiveTransactions()` dan `getRecentCompletedTransactions()`, sedangkan nama method di model adalah `getActiveVehicles()` dan `getRecentCompleted()`.
   - *Solusi*: Menambahkan alias method `getActiveTransactions()`, `getRecentCompletedTransactions()`, dan `getTransactionByIdTrx()` pada `ParkirModel.php`.

6. **Pengelompokan Menu Master & Kelola User ke Dropdown Sidebar**:
   - *Solusi*: Mengimplementasikan komponen collapse Bootstrap 5 (`#menuMasterUser`) dengan styling kustom CSS di `public/css/style.css` dan deteksi auto-expand saat sub-menu aktif.

---

## 🔑 6. Akun Pengguna Default

| Peran | Username | Password Default | Hak Akses |
| --- | --- | --- | --- |
| **Administrator** | `admin` | `123456` | Master Data, Tarif, Setoran, User, Laporan, Gate Parkir |
| **Petugas Kasir** | `kasir` | `123456` | Gate Parkir Masuk/Keluar, Lost Ticket, VOID, Member |

---

## 🌐 7. Alamat Akses Server Lokal (XAMPP)

- **URL Aplikasi**: [http://localhost/aplikasi-parkir](http://localhost/aplikasi-parkir)
- **Lokasi Folder Server**: `D:\Server\xampp\htdocs\aplikasi-parkir\`
- **Database Server**: MySQL/MariaDB XAMPP (`parkir_db`, user: `parkir`)

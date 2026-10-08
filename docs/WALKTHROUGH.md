# Dokumentasi Walkthrough Pengembangan & Operasional Aplikasi Parkir PHP MVC

Dokumen ini berisi rangkuman komprehensif perjalanan pengembangan, arsitektur sistem, alur kerja operasional, penanganan kendala teknis, serta panduan integrasi **Aplikasi Manajemen Parkir PHP MVC**.

---

## 📌 1. Pendahuluan

Aplikasi Parkir PHP MVC adalah sistem manajemen parkir mandiri berbasis web yang dirancang menggunakan konsep **Pure Custom PHP MVC (Model-View-Controller)** tanpa ketergantungan framework eksternal. Aplikasi ini mendukung penuh pencatatan kendaraan masuk (*Gate IN*), transaksi pembayaran parkir keluar (*Gate OUT*), cetak tiket & struk thermal, pendaftaran member langganan, manajemen pengguna & tarif, hingga rekapitulasi laporan pendapatan keuangan.

---

## 🏛️ 2. Arsitektur & Struktur Komponen

### A. Pola Desain (Front Controller MVC)
Setiap permintaan web diproses melalui **Front Controller** (`public/index.php`) dan diurai oleh router dinamis (`core/App.php`):

```
HTTP Request -> index.php -> App Router -> Controller -> Model (PDO) -> Database (parkir_db)
                                                |
                                                +-----> View Render (Bootstrap 5)
```

### B. Struktur Berkas
- **`app/controllers/`**: AuthController, DashboardController, ParkirController, KendaraanController, MemberController, UserController, LaporanController.
- **`app/models/`**: ParkirModel, KendaraanModel, MemberModel, UserModel, LaporanModel.
- **`core/`**: App (Router), Controller (Base Controller), Database (PDO Wrapper), Session (Session & Flash Messages).
- **`config/`**: `database.php` (Loader `.env` & Dynamic `BASE_URL`).
- **`.env`**: Konfigurasi Kredensial Database & Aplikasi.

---

## 🗄️ 3. Struktur Database & Relasi Tarif (`parkir_db`)

Aplikasi menggunakan skema database `parkir_db` yang fleksibel dengan fitur relasional:

1. **`jurnal_transaksi`**: Menampung catatan kendaraan aktif (`status = 'B'`) dan kendaraan selesai (`status = 'S'`), nomor tiket (`idtrx`), nopol, jenis kendaraan, pintu `gate` (Masuk) & `gateout` (Keluar), tarif, bayar, kembalian, dan stempel waktu.
2. **Relasi Tarif (`jenis_kendaraan` $\leftrightarrow$ `tarif_awal` $\leftrightarrow$ `tarif_berjalan`)**:
   - `tarif_awal`: Menyimpan besaran biaya jam ke-1.
   - `tarif_berjalan`: Menyimpan besaran biaya jam berikutnya.
3. **`member`**: Menyimpan data member terdaftar. Kendaraan member aktif secara otomatis dikenakan tarif **Rp 0 (GRATIS)**.
4. **`history_login`**: Merekam stempel waktu login (`w_login`) dan logout (`w_logout`) petugas secara presisi.

---

## 🚀 4. Fitur-Fitur Utama & Alur Kerja

### A. Autentikasi Pengguna & Sesi Login
- **Dua Peran Pengguna**: Administrator (`level = 1`) dan Petugas Kasir (`level = 2`).
- **Histori Login**: Setiap kali pengguna login, `UserModel::recordLoginHistory()` membuat entri baru di tabel `history_login`. Saat logout, `w_logout` diisi secara otomatis.

### B. Transaksi Kendaraan Masuk (Gate IN)
- Petugas menginput Plat Nomor, memilih Jenis Kendaraan, memilih Pintu Gate Masuk (`gate`), dan Gate Keluar (`gateout`).
- Sistem menggenerasi kode tiket unik `TRX...` dan mencetak Tiket Parkir Thermal dengan format barcode.

### C. Transaksi Kendaraan Keluar & Pembayaran (Gate OUT)
- Petugas menginput Nomor Tiket atau Plat Nomor.
- Sistem menghitung durasi jam parkir dan total biaya secara otomatis.
- **Deteksi Member Otomatis**: Jika nopol terdaftar sebagai member aktif, tarif otomatis diset menjadi **Rp 0 (GRATIS)**.
- Petugas menginput nominal pembayaran, memilih `gateout`, dan mencetak Struk Pembayaran Lunas.

### D. Manajemen Member & Pengguna
- **Member**: Pendaftaran member dengan rentang tanggal aktif dan penonaktifan status.
- **Kelola User & Histori**: Admin dapat mengelola akun petugas serta memantau log aktivitas login/logout real-time melalui Tab Histori Login.

---

## 🛠️ 5. Rekapitulasi Penanganan Kendala Teknis (Troubleshooting History)

Selama pengembangan, beberapa isu teknis penting telah diatasi secara tuntas:

1. **Perbaikan Eror Redirect Loop (*The page isn't redirecting properly*)**:
   - *Penyebab*: `App::parseUrl` gagal membaca rute saat `$_GET['url']` tidak terisi oleh `.htaccess`.
   - *Solusi*: Menambahkan parser fallback `$_SERVER['REQUEST_URI']` yang mampu mengurai rute di semua web server.

2. **Perbaikan Eror Memori (*Allowed memory size exhausted*)**:
   - *Penyebab*: `ParkirModel::getActiveTransactions()` menarik **228.995+ data aktif** sekaligus dari `jurnal_transaksi` tanpa batasan.
   - *Solusi*: Menerapkan pembatasan `LIMIT 50` pada kueri daftar aktif, sementara penghitungan total kendaraan tetap menggunakan `SELECT COUNT(*)`.

3. **Perbaikan Eror 404 pada Subfolder XAMPP (`http://localhost/aplikasi-parkir-mvc/`)**:
   - *Penyebab*: Aturan `RewriteCond %{REQUEST_URI} !^/public/` pada `.htaccess` terkunci dengan tanda caret `^`.
   - *Solusi*: Mengubah kondisi menjadi `!/public/` dan menambahkan file `index.php` fallback di root direktori.

4. **Penerapan Konfigurasi Kredensial `.env`**:
   - *Penyebab*: Kredensial terhubung langsung pada kode PHP.
   - *Solusi*: Memindahkan seluruh kredensial database ke berkas `.env` dengan loader native PHP di `config/database.php`.

---

## 🔑 6. Akun Pengguna Default

| Peran | Username | Password Default |
| --- | --- | --- |
| **Administrator** | `admin` | `123456` |
| **Petugas Kasir** | `kasir` | `123456` |

---

## 🌐 7. Alamat Akses Server Lokal (XAMPP)

- **URL Utama**: `http://localhost/aplikasi-parkir-mvc/`
- **Lokasi Folder Server**: `D:\xampp\htdocs\aplikasi-parkir-mvc`

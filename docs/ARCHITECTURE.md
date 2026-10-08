# Arsitektur Sistem & Struktur Kode

Dokumen ini menjelaskan rancangan arsitektur, pola desain, serta mekanisme alur kerja internal aplikasi **PHP MVC Parkir**.

---

## 🏛️ Pola Arsitektur MVC

Aplikasi dibangun menggunakan pola **Model-View-Controller (MVC)** murni tanpa dependensi framework luar (*zero external framework dependency*), memastikan kecepatan eksekusi tinggi, footprint memori minimal, dan pemeliharaan kode yang fleksibel.

```
                   +-------------------+
                   |    HTTP Request   |
                   +---------+---------+
                             |
                             v
                   +-------------------+
                   |   Front Controller|
                   | (public/index.php)|
                   +---------+---------+
                             |
                             v
                   +-------------------+
                   |   Router (App)    |
                   +---------+---------+
                             |
         +-------------------+-------------------+
         |                                       |
         v                                       v
+------------------+                    +------------------+
|    Controller    | <--- Session Check |    Controller    |
| (Parkir/Tarif/..)|                    | (Setoran/User/..)|
+--------+---------+                    +--------+---------+
         |                                       |
         v                                       v
+------------------+                    +------------------+
|      Model       |                    |      Model       |
| (Parkir/Tarif/..)|                    | (Setoran/User/..)|
+--------+---------+                    +--------+---------+
         |                                       |
         v                                       v
+------------------+                    +------------------+
|     Database     |                    |     Database     |
|   (PDO Wrapper)  |                    |   (PDO Wrapper)  |
+--------+---------+                    +--------+---------+
         |                                       |
         +-------------------+-------------------+
                             |
                             v
                   +-------------------+
                   |    View Render    |
                   | (HTML5/Bootstrap) |
                   +-------------------+
```

---

## 📂 Struktur Direktori Proyek

```
aplikasi-parkir/
├── app/                        # Direktori Inti Aplikasi MVC
│   ├── controllers/            # Controller Penangan Request & Alur Bisnis
│   │   ├── AuthController.php      # Login, Logout, dan Verifikasi Kredensial
│   │   ├── DashboardController.php # Ringkasan Statistik Parkir Real-Time
│   │   ├── KendaraanController.php # Master Jenis Kendaraan & Kapasitas
│   │   ├── LaporanController.php   # Laporan Transaksi, Keuangan & Audit VOID
│   │   ├── MemberController.php    # Manajemen Member Langganan Parkir
│   │   ├── ParkirController.php    # Gate IN (Masuk), Gate OUT (Keluar), Lost Ticket, Multi-Pay, VOID
│   │   ├── SetoranController.php   # Rekap Setoran Kasir, Tutup Shift, Cetak Berita Acara
│   │   ├── TarifController.php     # Master Tarif & Diskon (7 Tab Terintegrasi)
│   │   └── UserController.php      # Manajemen Akun Pengguna & Log Audit Login
│   ├── models/                 # Model Data & Akses Basis Data
│   │   ├── KendaraanModel.php      # Kueri Jenis Kendaraan & Okupansi
│   │   ├── LaporanModel.php        # Kueri Laporan Filter, Ringkasan, & Audit VOID
│   │   ├── MemberModel.php         # Kueri Member Parkir & Validasi Nopol
│   │   ├── ParkirModel.php         # Transaksi Parkir, Hitung Durasi/Tarif, Lost Ticket, Multi-Pay, VOID
│   │   ├── SetoranModel.php        # Kueri Rekonsiliasi Kas, Jam Shift, Header & Detail Setoran
│   │   ├── TarifModel.php          # Kueri Skema Tarif (Progresif, Flat, Inap, Lost, Max, Diskon, Libur)
│   │   └── UserModel.php           # Autentikasi User & Histori Login
│   └── views/                  # Antarmuka Tampilan (Views)
│       ├── auth/                   # Halaman Login
│       ├── dashboard/              # Halaman Dashboard Utama
│       ├── kendaraan/              # Tampilan Master Jenis Kendaraan
│       ├── laporan/                # Tampilan Laporan Transaksi & Tab Audit VOID
│       ├── layouts/                # Template Header & Footer (Sidebar & Navbar)
│       ├── member/                 # Tampilan Pengelolaan Member Parkir
│       ├── parkir/                 # Tampilan Masuk, Keluar, Cetak Tiket, Cetak Struk
│       ├── setoran/                # Riwayat Setoran, Form Tutup Shift, Cetak Berita Acara, Jam Shift
│       ├── tarif/                  # Halaman Nav-Tabs Master Tarif Lengkap & Diskon
│       └── user/                   # Manajemen Pengguna & Riwayat Login
├── config/                     # Konfigurasi Lingkungan & Database
│   └── database.php                # Pembaca .env, Koneksi PDO, & Konfigurasi BASE_URL
├── core/                       # Komponen Fondasi MVC Framework
│   ├── App.php                     # Router Front Controller & Pengurai URL
│   ├── Controller.php              # Base Controller (View Loader & Model Factory)
│   ├── Database.php                # PDO Database Wrapper
│   └── Session.php                 # Session Manager, Flash Messages, & Auth Guards
├── docs/                       # Dokumentasi Resmi Aplikasi
│   ├── README.md                   # Indeks Dokumentasi
│   ├── CHANGELOG.md                # Riwayat Pembaruan & Fitur Baru
│   ├── ARCHITECTURE.md             # Arsitektur & Struktur Kode
│   ├── DATABASE.md                 # Dokumentasi Skema & Relasi Database
│   ├── USER_GUIDE.md               # Panduan Pengoperasian Pengguna
│   ├── API_AND_MODELS.md           # Referensi Teknis API & Model
│   └── WALKTHROUGH.md              # Rangkuman Pengujian & Deployment
├── public/                     # Direktori Akses Publik Web Server
│   ├── css/                        # Custom CSS Stylesheet
│   ├── js/                         # JavaScript Logika Frontend
│   ├── .htaccess                   # Rewrite Engine Apache (Subfolder/URL Routing)
│   └── index.php                   # Entrypoint Utama Aplikasi
├── .env                        # File Variabel Lingkungan Lokal
├── .htaccess                   # Rewrite Engine Root Apache
├── index.php                   # Fallback Entrypoint Root
├── README.md                   # Ringkasan Proyek
└── schema_and_seed.sql         # Skema Database & Data Awal
```

---

## ⚡ Alur URL Routing (`core/App.php`)

Format URL pada aplikasi mengikuti pola standar Front Controller:

$$\text{URL} = \text{BASE\_URL} / \text{controller} / \text{method} / [\text{param1}, \text{param2}, \dots]$$

### Mekanisme Penguraian Rute Dinamis (`parseUrl`):
1. **Prioritas 1**: Membaca variabel `$_GET['url']` (yang diteruskan oleh file `.htaccess`).
2. **Prioritas 2 (Fallback)**: Membaca `$_SERVER['REQUEST_URI']` apabila aplikasi dijalankan di subfolder, XAMPP, atau `php -S`. Parser otomatis memotong awalan direktori pembungkus sehingga rute tetap terbaca secara konsisten tanpa merusak parameter GET.

### Mekanisme Penentuan `BASE_URL`:
- Jika variabel `BASE_URL` diset di berkas `.env` (misal: `http://localhost/aplikasi-parkir`), aplikasi secara eksplisit menggunakan alamat tersebut untuk membangun seluruh link navigasi dan asset statis (CSS/JS).
- Jika kosong, aplikasi secara otomatis mendeteksi protokol (`http` / `https`), host domain, dan folder skrip saat ini.

---

## 🔒 Otentikasi & Otorisasi (`core/Session.php`)

Setiap Controller dilindungi dengan mekanisme penjagaan akses (*Auth Guard*):
- **`Session::requireLogin()`**: Mewajibkan pengguna berstatus login; mengalihkan pengguna ke `/auth/login` jika belum terotentikasi.
- **`Session::requireAdmin()`** / **`Session::authCheck('Administrator')`**: Memverifikasi level pengguna (`user_level == 1`). Jika level tidak memenuhi syarat, pengguna dialihkan ke `/dashboard` dengan pesan peringatan penolakan akses.

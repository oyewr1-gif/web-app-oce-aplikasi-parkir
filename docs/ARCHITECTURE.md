# Arsitektur Sistem & Struktur Kode

Dokumen ini menjelaskan rancangan arsitektur, pola desain, serta mekanisme alur kerja internal aplikasi **PHP MVC Parkir**.

---

## 🏛️ Pola Arsitektur MVC

Aplikasi dibangun menggunakan pola **Model-View-Controller (MVC)** murni tanpa ketergantungan framework eksternal (*zero external framework dependency*), memastikan performa yang cepat dan ringan.

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
| (Parkir/Auth/...) |                    | (User/Member/...) |
+--------+---------+                    +--------+---------+
         |                                       |
         v                                       v
+------------------+                    +------------------+
|      Model       |                    |      Model       |
| (ParkirModel/...) |                    | (UserModel/...)  |
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
aplikasi-parkir-mvc/
├── app/                        # Berkas Utama Aplikasi
│   ├── controllers/            # Controller Penangan Permintaan
│   │   ├── AuthController.php      # Login & Logout
│   │   ├── DashboardController.php # Ringkasan Overview & Statistik
│   │   ├── KendaraanController.php # Master Jenis Kendaraan & Tarif
│   │   ├── LaporanController.php   # Rekapitulasi Financial Report
│   │   ├── MemberController.php    # Manajemen Member Langganan
│   │   ├── ParkirController.php    # Transaksi Masuk (IN) & Keluar (OUT)
│   │   └── UserController.php      # Manajemen Hak Akses Pengguna
│   ├── models/                 # Model Akses Database
│   │   ├── KendaraanModel.php      # Operasi Jenis Kendaraan & Tarif
│   │   ├── LaporanModel.php        # Kueri Rekapitulasi & Statistik
│   │   ├── MemberModel.php         # Operasi Member Parkir
│   │   ├── ParkirModel.php         # Kueri Transaksi Tiket & Tarif
│   │   └── UserModel.php           # Autentikasi & Histori Login
│   └── views/                  # Antarmuka Tampilan (Views)
│       ├── auth/                   # Tampilan Login
│       ├── dashboard/              # Tampilan Ringkasan Dashboard
│       ├── kendaraan/              # Tampilan Master Jenis Kendaraan
│       ├── laporan/                # Tampilan Rekapitulasi Laporan
│       ├── layouts/                # Header & Footer Bootstrap
│       ├── member/                 # Tampilan Pengelolaan Member
│       ├── parkir/                 # Tampilan Masuk, Keluar, Tiket, Struk
│       └── user/                   # Tampilan Pengelolaan User & Histori
├── config/                     # Konfigurasi Berkas
│   └── database.php                # Database Host, Port, Creds, & BASE_URL
├── core/                       # Core Framework Component
│   ├── App.php                     # URL Router & Dispatcher
│   ├── Controller.php              # Base Controller Helper
│   ├── Database.php                # PDO Wrapper Layer
│   └── Session.php                 # Session & Flash Message Helper
├── docs/                       # Dokumentasi Resmi Aplikasi
│   ├── README.md                   # Indeks Dokumentasi
│   ├── ARCHITECTURE.md             # Arsitektur & Struktur
│   ├── DATABASE.md                 # Skema Database & Relasi
│   ├── USER_GUIDE.md               # Panduan Pengoperasian
│   └── API_AND_MODELS.md           # Referensi Teknis API & Model
├── public/                     # Public Web Root Directory
│   ├── css/                        # Custom Stylesheet
│   ├── js/                         # JavaScript Client-side Logic
│   ├── .htaccess                   # Apache URL Rewrite Public
│   └── index.php                   # Public Front Controller Entrypoint
├── .htaccess                   # Apache Root Subfolder Rewrite
├── index.php                   # Fallback Entrypoint Root
├── README.md                   # Panduan Cepat Proyek
└── schema_and_seed.sql         # Skema DDL Database & Seed Data
```

---

## ⚡ Alur URL Routing (`core/App.php`)

Format URL pada aplikasi mengikuti pola standar Front Controller:

$$\text{URL} = \text{BASE\_URL} / \text{controller} / \text{method} / [\text{param1}, \text{param2}, \dots]$$

### Mekanisme Parsing Rute Dinamis (`parseUrl`):
1. **Prioritas 1**: Membaca variabel `$_GET['url']` (yang dipisahkan oleh `.htaccess`).
2. **Prioritas 2 (Fallback)**: Membaca `$_SERVER['REQUEST_URI']` apabila aplikasi dijalankan di subfolder, XAMPP, atau `php -S`. Parser otomatis memotong nama direktori pembungkus sehingga rute tetap terbaca secara konsisten.

# Aplikasi Parkir PHP MVC (Admin & Kasir)

Aplikasi manajemen parkir berbasis **Pure Custom PHP MVC (OOP & PDO)** dengan antarmuka modern yang dikembangkan berdasarkan skema database `parkir_db`.

---

## 🚀 Fitur Utama

1. **Authentication & Multi-Role**:
   - **Administrator**: Hak akses penuh (Kelola User, Master Kendaraan & Tarif, Data Member Parkir, Laporan & Setoran).
   - **User / Petugas Kasir Parkir**: Gate Entry (Pencatatan & Cetak Tiket) dan Gate Exit (Hitung Tarif, Pembayaran Kasir & Struk).
2. **Kalkulasi Tarif Otomatis**:
   - Perhitungan durasi parkir real-time.
   - Perhitungan tarif jam pertama + tarif jam berikutnya sesuai Jenis Kendaraan (Motor, Mobil, Truk/Bus).
   - Penanganan otomatis untuk **Member Parkir Langganan** (Bebas Biaya / Gratis).
3. **Cetak Tiket & Struk Pembayaran**:
   - Tampilan khusus siap cetak untuk Tiket Parkir Masuk dan Struk Pembayaran Keluar.
4. **Laporan & Rekapitulasi**:
   - Filter laporan berdasarkan rentang tanggal, jenis kendaraan, dan status parkir.
   - Ringkasan statistik pendapatan & jumlah kendaraan parkir.

---

## 🛠️ Langkah Instalasi & Cara Jalankan

### 1. Import Database SQL
1. Buka MySQL Management Tool (HeidiSQL / phpMyAdmin).
2. Buat database `parkir_db` atau jalankan file `schema_and_seed.sql`.
```bash
mysql -u root -p < schema_and_seed.sql
```

### 2. Konfigurasi Database (Jika Diperlukan)
Buka file `config/database.php` jika menggunakan host, port, username, atau password database yang berbeda:
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'parkir_db');
```

### 3. Jalankan Local PHP Server
Buka terminal/PowerShell di direktori proyek ini (`C:\Users\user\.gemini\antigravity\scratch\aplikasi-parkir-mvc`), lalu jalankan:
```bash
php -S localhost:8000 -t public
```

Buka browser di: `http://localhost:8000`

---

## 🔑 Akun Login Default

| Role | Username | Password | Hak Akses |
|---|---|---|---|
| **Administrator** | `admin` | `123456` | Master Data, User, Member, Laporan |
| **Petugas Kasir** | `kasir` | `123456` | Gate Parkir Masuk/Keluar & Member |

# Dokumentasi Sistem Manajemen Parkir PHP MVC

Selamat datang di pusat dokumentasi resmi **Aplikasi Manajemen Parkir PHP MVC**. Dokumentasi ini dirancang untuk memberikan panduan lengkap mengenai arsitektur, basis data, pemanfaatan fitur, hingga referensi teknis pengembang.

---

## 📑 Daftar Isi Dokumentasi

1. **[Dokumentasi Walkthrough Lengkap (WALKTHROUGH.md)](WALKTHROUGH.md)**
   - Rangkuman Pengembangan, Fitur, Penanganan Kendala Teknis, & Deployment XAMPP.

2. **[Arsitektur Sistem (ARCHITECTURE.md)](ARCHITECTURE.md)**
   - Pola Arsitektur Pure Custom PHP MVC (Model-View-Controller).
   - Struktur Direktori & Fungsi Berkas.
   - Pola Front Controller & Penanganan Rute (Routing System).

3. **[Struktur Basis Data (DATABASE.md)](DATABASE.md)**
   - Skema Database `parkir_db`.
   - Daftar Tabel Utama (`jurnal_transaksi`, `jenis_kendaraan`, `tarif_awal`, `tarif_berjalan`, `user`, `member`, `history_login`, dll).
   - Logika Tarif Relasional & Formula Penghitungan Biaya.

4. **[Panduan Pengguna (USER_GUIDE.md)](USER_GUIDE.md)**
   - Hak Akses & Kredensial Login (Admin vs Kasir).
   - Alur Transaksi Kendaraan Masuk & Cetak Tiket (Gate IN).
   - Alur Transaksi Kendaraan Keluar & Cetak Struk (Gate OUT).
   - Manajemen Member Parkir & Deteksi Otomatis Member.

5. **[Referensi API & Model (API_AND_MODELS.md)](API_AND_MODELS.md)**
   - Kelas Core Framework (`App`, `Controller`, `Database`, `Session`).
   - Spasial Kelas Model (`ParkirModel`, `KendaraanModel`, `UserModel`, `MemberModel`, `LaporanModel`).

---

## ⚙️ Ringkasan Spesifikasi Teknis

| Parameter | Spesifikasi |
| --- | --- |
| **Bahasa Pemrograman** | PHP 7.4+ / PHP 8.x (Native OOP) |
| **Pola Arsitektur** | Custom Front-Controller MVC |
| **Database Engine** | MySQL / MariaDB (PDO Driver) |
| **Tampilan UI** | HTML5, Bootstrap 5, FontAwesome 6 |
| **Sistem Rute** | Dynamic Path Parser (`parseUrl`) |
| **Konfigurasi** | Berkas `.env` Environment Variables |
| **Keamanan** | BCRYPT Password Hashing, Prepared Statements, Session Isolation |

# Dokumentasi Sistem Manajemen Parkir PHP MVC

Selamat datang di pusat dokumentasi resmi **Aplikasi Manajemen Parkir PHP MVC**. Dokumentasi ini dirancang untuk memberikan panduan lengkap mengenai arsitektur, basis data, pemanfaatan fitur, hingga referensi teknis pengembang.

---

## 📑 Daftar Isi Dokumentasi

1. **[Dokumentasi Walkthrough Lengkap (WALKTHROUGH.md)](WALKTHROUGH.md)**
   - Rangkuman Pengembangan, Fitur Awal, Penanganan Kendala Teknis, & Panduan Deployment XAMPP.

2. **[Catatan Pembaruan & Riwayat Perubahan (CHANGELOG.md)](CHANGELOG.md)**
   - Catatan rilis dan dokumentasi lengkap penambahan fitur baru (Master Tarif & Diskon, Setoran Kasir & Shift, Tiket Hilang, Multi-Payment, VOID Audit).

3. **[Arsitektur Sistem (ARCHITECTURE.md)](ARCHITECTURE.md)**
   - Pola Arsitektur Pure Custom PHP MVC (Model-View-Controller).
   - Struktur Direktori & Fungsi Berkas Terkini.
   - Pola Front Controller & Penanganan Rute (Routing System).

4. **[Struktur Basis Data (DATABASE.md)](DATABASE.md)**
   - Skema Database `parkir_db` (Analisis 90 Base Tables & 32 Views).
   - Dokumentasi Tabel Inti: `jurnal_transaksi`, `data_setoran`, `detail_setoran`, `jamshift`, `pos_kasir`, `manless`, skema tarif (`tarif_awal`, `tarif_berjalan`, `tarif_flat`, `tarif_inap`, `tarif_lost`, `tarif_maksimal`, `discount_tarif`, `hari_libur`), dll.
   - Relasi Antar Tabel & Formula Perhitungan Biaya / Denda.

5. **[Panduan Pengguna (USER_GUIDE.md)](USER_GUIDE.md)**
   - Hak Akses & Kredensial Login (Admin vs Kasir).
   - Alur Transaksi Kendaraan Masuk (Gate IN) via Dispenser Manless.
   - Alur Transaksi Kendaraan Keluar (Gate OUT): Hitung Tarif, Penanganan Tiket Hilang, Pembayaran Multi-Metode (Tunai/QRIS/E-Money).
   - Fitur Pembatalan Transaksi (VOID) & Modal Alasan Pembatalan.
   - Modul Tutup Shift & Setoran Kasir: Rekonsiliasi Kas Fisik vs Sistem, Cetak Berita Acara, & Master Jam Shift.
   - Modul Master Tarif & Diskon (7 Tab).

6. **[Referensi API & Model (API_AND_MODELS.md)](API_AND_MODELS.md)**
   - Kelas Core Framework (`App`, `Controller`, `Database`, `Session`).
   - Spesifikasi Kelas Model (`ParkirModel`, `TarifModel`, `SetoranModel`, `KendaraanModel`, `UserModel`, `MemberModel`, `LaporanModel`).

---

## ⚙️ Ringkasan Spesifikasi Teknis

| Parameter | Spesifikasi |
| --- | --- |
| **Bahasa Pemrograman** | PHP 7.4+ / PHP 8.x (Native OOP & PDO) |
| **Pola Arsitektur** | Custom Front-Controller MVC |
| **Database Engine** | MySQL / MariaDB (`parkir_db`, InnoDB) |
| **Tampilan UI** | HTML5, Bootstrap 5, FontAwesome 6, Google Fonts Inter |
| **Sistem Rute** | Dynamic Path Parser (`parseUrl`) dengan Dukungan Clean URL Apache |
| **Konfigurasi** | Berkas `.env` Environment Variables |
| **Keamanan** | BCRYPT Password Hashing, Prepared Statements, Session Isolation, Audit Trail |

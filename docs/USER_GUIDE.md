# Panduan Pengguna (User Manual)

Dokumen ini berisi petunjuk operasional lengkap penggunaan **Aplikasi Manajemen Parkir PHP MVC** bagi pengguna tingkat Administrator maupun Petugas Kasir.

---

## 🔑 Hak Akses & Akun Login

Aplikasi memiliki 2 tingkatan peran pengguna:

1. **Administrator (Level 1)**:
   - Memiliki akses penuh ke seluruh modul sistem (Dashboard, Masuk, Keluar, Jenis Kendaraan & Tarif, Pengelolaan Member, Kelora User & Histori Login, Laporan Financial).
   - Akun Bawaan: Username `admin` | Password `123456`

2. **Petugas Kasir (Level 2)**:
   - Memiliki akses ke modul operasional (Dashboard, Input Masuk, Transaksi Keluar & Pembayaran, Pendaftaran Member).
   - Akun Bawaan: Username `kasir` | Password `123456`

---

## 🚗 1. Alur Transaksi Kendaraan Masuk (Gate IN)

1. Klik menu **Kendaraan Masuk** pada sidebar.
2. Masukkan **Nomor Polisi (Plat Nomor)** (contoh: `B 1234 ABC`).
3. Pilih **Jenis Kendaraan** (Sepeda Motor / Mobil).
4. Pilih **Pintu Gate Masuk (`gate`)** (misal: `GATE-IN-01`) dan **Gate Keluar (`gateout`)**.
5. Klik **Simpan & Cetak Tiket Parkir**.
6. Sistem akan menerbitkan tiket parkir berukuran struk thermal dengan Barcode Kode Tiket unik.

---

## 💳 2. Alur Transaksi Kendaraan Keluar & Pembayaran (Gate OUT)

1. Klik menu **Kendaraan Keluar** pada sidebar.
2. Scan kode tiket atau ketik **Nomor Tiket / Plat Nomor** kendaraan pada kolom pencarian, lalu tekan **Cari**.
3. Sistem secara otomatis menghitung durasi dan total biaya parkir:
   - Durasi dibulatkan ke atas per jam.
   - Apabila kendaraan terdaftar sebagai **Member Aktif**, tarif otomatis menjadi **Rp 0 (GRATIS)**.
4. Pilih **Pintu Gate Keluar (`gateout`)**.
5. Masukkan jumlah **Nominal Uang Bayar** dari pengunjung.
6. Klik **Selesaikan Transaksi & Cetak Struk**.
7. Sistem akan mencetak Struk Pembayaran Parkir Lunas.

---

## 💳 3. Pengelolaan Member Parkir

1. Buka menu **Member Parkir**.
2. Klik **Daftarkan Member Baru**.
3. Input Nama, Nopol, Jenis Kendaraan, serta Periode Tanggal Berlaku (Mulai s.d Akhir).
4. Selama periode berlaku aktif, setiap kendaraan dengan Plat Nomor tersebut keluar dari area parkir tidak akan dikenakan biaya (Rp 0).

---

## 📊 4. Rekapitulasi Laporan & Histori Login

1. **Laporan Transaksi (`/laporan`)**:
   - Filter transaksi berdasarkan rentang tanggal, status parkir (Parkir Aktif vs Sudah Keluar), dan jenis kendaraan.
   - Cetak Laporan Keuangan secara langsung dengan tombol **Cetak Laporan**.
2. **Histori Login (`/user`)**:
   - Admin dapat berpindah ke tab **Histori Aktivitas Login** untuk melihat rincian tanggal, jam login, dan jam logout dari setiap petugas.

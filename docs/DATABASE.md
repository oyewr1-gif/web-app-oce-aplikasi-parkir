# Dokumentasi Basis Data (`parkir_db`)

Dokumen ini menjelaskan struktur basis data, tabel-tabel utama, skema relasi tarif, stored procedure, view, serta triggers pada aplikasi **Parkir PHP MVC**.

---

## 🗄️ Skema Database

- **Nama Database**: `parkir_db`
- **Charset / Collation**: `latin1_swedish_ci` / `utf8mb4_general_ci`
- **Engine**: InnoDB (Transaction-safe)

---

## 📋 Tabel-Tabel Utama Aplikasi

### 1. Tabel `jurnal_transaksi` (Tabel Utama Transaksi Parkir)
Menyimpan seluruh catatan transaksi kendaraan masuk, transaksi berjalan, hingga transaksi selesai.

| Kolom | Tipe Data | Deskripsi |
| --- | --- | --- |
| `id` | `bigint(20)` AUTO_INCREMENT | Primary Key |
| `idtrx` | `varchar(100)` | Kode Unik Tiket (misal: `TRX2026100813410012`) |
| `nopol` | `varchar(100)` | Nomor Polisi / Plat Nomor Kendaraan |
| `jn_kendaraan` | `varchar(100)` | Nama Jenis Kendaraan (Sepeda Motor / Mobil / dll) |
| `gate` | `varchar(60)` | Pintu Gate Masuk (misal: `GATE-IN-01`) |
| `gateout` | `varchar(250)` | Pintu Gate Keluar (misal: `GATE-OUT-01`) |
| `tgl_masuk` | `date` | Tanggal Kendaraan Masuk |
| `jam_masuk` | `time` | Jam Kendaraan Masuk |
| `waktuMasuk` | `datetime` | Stempel Waktu Lengkap Masuk |
| `tgl_keluar` | `date` | Tanggal Kendaraan Keluar |
| `jam_keluar` | `time` | Jam Kendaraan Keluar |
| `waktuKeluar` | `datetime` | Stempel Waktu Lengkap Keluar |
| `durasi` | `varchar(60)` | Durasi Parkir Teks (`HH:MM:SS`) |
| `tarif` | `int(11)` | Total Biaya Tarif Parkir (Rp) |
| `bayar` | `int(11)` | Nominal Uang Tunai Pembayaran (Rp) |
| `kembalian` | `int(11)` | Nominal Uang Kembalian (Rp) |
| `status` | `varchar(2)` | Status Parkir (`B` = Parkir Aktif/Belum Keluar, `S` = Selesai) |
| `status_bayar` | `int(11)` | Status Pembayaran (`0` = Belum, `1` = Lunas) |
| `id_user` | `int(11)` | ID Kasir / Petugas Parkir |
| `shift` | `varchar(60)` | Shift Kerja Petugas |

---

### 2. Tabel `jenis_kendaraan` (Master Jenis Kendaraan)
Menyimpan daftar kategori kendaraan dan kapasitas slot parkir.

| Kolom | Tipe Data | Deskripsi |
| --- | --- | --- |
| `id` | `int(11)` AUTO_INCREMENT | Primary Key |
| `jn_kendaraan` | `varchar(60)` | Kode Jenis Kendaraan (misal: `01`, `02`) |
| `nama` | `varchar(60)` | Nama Kategori (Sepeda Motor / Mobil) |
| `kapasitas` | `int(11)` | Kapasitas Maksimal Slot Parkir |
| `terpakai` | `int(11)` | Jumlah Slot Terisi Saat Ini |

---

### 3. Tabel `tarif_awal` & `tarif_berjalan` (Logika Tarif Relasional)

Sistem menggunakan skema tarif terpisah berdasarkan kode kendaraan (`jn_kendaraan` <-> `kode_kendaraan`):
- **`tarif_awal`**: Menyimpan besaran tarif untuk jam pertama (Jam Ke-1).
- **`tarif_berjalan`**: Menyimpan besaran tarif per jam berikutnya.

```sql
SELECT j.*, 
       COALESCE(ta.rupiah, 2000) AS tarif_pertama, 
       COALESCE(tb.rupiah, 1000) AS tarif_berikutnya 
FROM jenis_kendaraan j 
LEFT JOIN tarif_awal ta ON ta.kode_kendaraan = j.jn_kendaraan 
LEFT JOIN tarif_berjalan tb ON tb.kode_kendaraan = j.jn_kendaraan 
ORDER BY j.id ASC;
```

---

### 4. Tabel `user` & `level_user` (Pengguna & Hak Akses)

- **`user`**: `id`, `username`, `password`, `nama`, `jabatan`, `level`, `shift`, `email`, `notlp`, `alamat`.
- **`level_user`**: `1` = Administrator, `2` = Kasir / Petugas Parkir.

---

### 5. Tabel `member` (Member Langganan Parkir)

Menyimpan data pelanggan member terdaftar. Kendaraan yang terdaftar sebagai member aktif akan mendapatkan tarif **Rp 0 (FREE)** secara otomatis.

| Kolom | Tipe Data | Deskripsi |
| --- | --- | --- |
| `id` | `int(11)` AUTO_INCREMENT | Primary Key |
| `id_member` | `varchar(60)` | Kode ID Member (`MBR-001`) |
| `nama` | `varchar(250)` | Nama Lengkap Member |
| `nopol` | `varchar(60)` | Plat Nomor Kendaraan Member |
| `jn_kendaraan` | `varchar(60)` | Jenis Kendaraan |
| `tgl_mulai` | `date` | Tanggal Awal Masa Berlaku |
| `tgl_akhir` | `date` | Tanggal Akhir Masa Berlaku |
| `nohp` | `varchar(60)` | Nomor Telepon / WhatsApp |
| `status` | `int(11)` | Status Aktif (`1` = Aktif, `0` = Non-Aktif) |

---

### 6. Tabel `history_login` (Aktivitas Login & Logout)

Mencatat jejak riwayat sesi login dan logout petugas kasir/admin.

| Kolom | Tipe Data | Deskripsi |
| --- | --- | --- |
| `id` | `int(11)` AUTO_INCREMENT | Primary Key |
| `iduser` | `int(11)` | Foreign Key ke Tabel `user` |
| `w_login` | `datetime` | Stempel Waktu Login (`Y-m-d H:i:s`) |
| `w_logout` | `datetime` | Stempel Waktu Logout (`Y-m-d H:i:s`) |
| `tgl` | `date` | Tanggal Kejadian Login |
| `level` | `int(11)` | Level Akses Pengguna |
| `lokasi` | `varchar(60)` | Lokasi / Pos Kerja |

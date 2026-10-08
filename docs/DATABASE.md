# Dokumentasi Basis Data (`parkir_db`)

Dokumen ini menjelaskan struktur basis data, analisis 90 base tables, serta rincian tabel-tabel yang diimplementasikan pada aplikasi **Parkir PHP MVC**.

---

## 🗄️ Skema Database

- **Nama Database**: `parkir_db`
- **Charset / Collation**: `latin1_swedish_ci` / `utf8mb4_general_ci`
- **Engine**: InnoDB (Transaction-safe)
- **Total Objek Database**: 90 Base Tables & 32 Views

---

## 📋 Tabel-Tabel Utama Aplikasi

### 1. Tabel `jurnal_transaksi` (Transaksi & Jurnal Parkir)
Menyimpan riwayat lengkap transaksi kendaraan masuk, transaksi parkir berjalan, transaksi selesai, penanganan denda tiket hilang, serta audit transaksi yang dibatalkan (VOID).

| Kolom | Tipe Data | Deskripsi |
| --- | --- | --- |
| `id` | `int(11)` AUTO_INCREMENT | ID Urut Transaksi |
| `idtrx` | `varchar(60)` PRIMARY KEY | Kode Unik Tiket (misal: `TRX2026100814390615`) |
| `nopol` | `varchar(11)` | Nomor Polisi / Plat Kendaraan |
| `jn_kendaraan` | `varchar(60)` | Nama Jenis Kendaraan (misal: `SEPEDA MOTOR`, `MOBIL`) |
| `gate` | `varchar(60)` | Nama Pintu Masuk / Mesin Tiket Manless |
| `gateout` | `varchar(250)` | Nama Pos Kasir / Pintu Keluar |
| `tgl_masuk` | `varchar(60)` | Tanggal Masuk (`Y-m-d`) |
| `jam_masuk` | `varchar(60)` | Jam Masuk (`H:i:s`) |
| `waktuMasuk` | `datetime` | Stempel Waktu Lengkap Masuk |
| `tgl_keluar` | `varchar(60)` | Tanggal Keluar (`Y-m-d`) |
| `jam_keluar` | `varchar(60)` | Jam Keluar (`H:i:s`) |
| `waktuKeluar` | `datetime` | Stempel Waktu Lengkap Keluar |
| `durasi` | `varchar(60)` | Durasi Parkir Teks (`HH:MM:SS`) |
| `tarif` | `int(11)` | Total Tarif Durasi Parkir (Rp) |
| `denda` | `int(11)` | Denda Tiket Hilang / Denda Khusus (Rp) |
| `bayar` | `int(11)` | Nominal Uang Bayar Diterima Kasir (Rp) |
| `kembalian` | `int(11)` | Nominal Uang Kembalian (Rp) |
| `cara_bayar` | `varchar(250)` | Metode Pembayaran (`Tunai`, `QRIS`, `Prepaid`) |
| `refbayar` | `varchar(250)` | Nomor Referensi / Approval Transaksi Non-Tunai |
| `nostnk` | `varchar(60)` | Nomor STNK Pemilik (Khusus Tiket Hilang) |
| `noktp` | `varchar(60)` | Nomor KTP / Identitas Pemilik (Khusus Tiket Hilang) |
| `nama` | `varchar(60)` | Nama Pemilik Kendaraan (Khusus Tiket Hilang) |
| `nohp` | `varchar(60)` | No. Handphone Pemilik (Khusus Tiket Hilang) |
| `status` | `varchar(1)` | Status: `B` (Parkir Aktif), `S` (Selesai/Lunas), `N` (Dibatalkan/VOID) |
| `status_bayar` | `int(1)` | Status Bayar (`0` = Belum, `1` = Lunas) |
| `id_user` | `varchar(2)` | ID Petugas Kasir |
| `shift` | `varchar(60)` | Shift Petugas saat transaksi |
| `waktubatal` | `datetime` | Waktu Pembatalan Transaksi (Khusus Status `N`) |
| `ketbatal` | `varchar(250)` | Alasan Pembatalan Transaksi (Khusus Status `N`) |
| `iduser_pembatalan` | `int(11)` | ID Petugas yang melakukan pembatalan (VOID) |

---

### 2. Tabel `data_setoran` & `detail_setoran` (Rekap Setoran Kasir & Shift)
Mencatat hasil rekonsiliasi uang setoran kasir saat penutupan shift dan verifikasi fisik kas oleh supervisor/admin.

#### Struktur `data_setoran` (Header):
| Kolom | Tipe Data | Deskripsi |
| --- | --- | --- |
| `id` | `int(11)` AUTO_INCREMENT | Primary Key |
| `no_setoran` | `varchar(60)` | Nomor Unik Berita Acara (format: `ymdHisST`) |
| `waktu` | `datetime` | Waktu Pencatatan Setoran |
| `iduser_penerima` | `int(11)` | ID User Supervisor/Admin Penerima Kas |
| `iduser_kasir` | `int(11)` | ID User Kasir yang Menyerahkan Kas |
| `nama_kasir` | `varchar(60)` | Nama Petugas Kasir |
| `shift` | `varchar(60)` | Shift Kerja (`Pagi`, `Sore`, `Malam`) |
| `pintu` | `varchar(60)` | Pos / Pintu Kasir (`POS R4`, `POS R2`) |
| `tglsetoran` | `date` | Tanggal Transaksi Setoran |
| `jumuang` | `int(11)` | Total Penerimaan Berdasarkan Catatan Sistem |
| `fisik` | `int(11)` | Total Uang Fisik Aktual yang Diserahkan |
| `jummasalah` | `int(11)` | Indikator Masalah (`0` = Cocok, `1` = Ada Selisih) |
| `jumuangmasalah` | `int(11)` | Nominal Selisih (`fisik - jumuang`) |

#### Struktur `detail_setoran` (Rincian per Kategori):
| Kolom | Tipe Data | Deskripsi |
| --- | --- | --- |
| `id` | `bigint(20)` AUTO_INCREMENT | Primary Key |
| `no_setoran` | `varchar(60)` | Foreign Key ke `data_setoran.no_setoran` |
| `nama_kendaraan` | `varchar(60)` | Kategori Kendaraan (`SEPEDA MOTOR`, `MOBIL`, `Total`) |
| `tunai` | `int(11)` | Total Penerimaan Pembayaran Tunai (Rp) |
| `qris` | `int(11)` | Total Penerimaan Pembayaran QRIS (Rp) |
| `prepaid` | `int(11)` | Total Penerimaan Pembayaran E-Money/Prepaid (Rp) |
| `total` | `int(11)` | Total Keseluruhan Penerimaan Kategori (Rp) |

---

### 3. Tabel `jamshift` (Master Jam Kerja Shift)
Menentukan jam mulai dan selesai untuk shift operasional petugas parkir.

| Kolom | Tipe Data | Deskripsi |
| --- | --- | --- |
| `id` | `int(11)` AUTO_INCREMENT | Primary Key |
| `nama` | `varchar(60)` | Nama Shift (`Pagi`, `Sore`, `Malam`) |
| `jama` | `time` | Jam Mulai Shift (contoh: `07:00:00`) |
| `jamb` | `time` | Jam Selesai Shift (contoh: `15:00:00`) |

---

### 4. Tabel Master Gate & Pos
- **`manless`**: Daftar mesin dispenser tiket otomatis pada pintu masuk (contoh: `MAN R4`, `MAN R2`). Digunakan sebagai opsi pintu masuk (`gate`).
- **`pos_kasir`**: Daftar pos gardu kasir pada pintu keluar (contoh: `POS R4`, `POS R2`). Digunakan sebagai opsi pintu keluar (`gateout`).

---

### 5. Tabel-Tabel Skema Tarif Terpadu
Sistem mendukung multi-skema tarif yang dikelola terpusat di menu **Master Tarif & Diskon**:

1. **`tarif_awal`**: Tarif jam pertama per kode kendaraan (`kode_kendaraan`, `durasi`, `rupiah`).
2. **`tarif_berjalan`**: Tarif jam berikutnya per jam (`kode_kendaraan`, `rupiah`).
3. **`tarif_flat`**: Skema tarif sekali bayar tanpa hitungan durasi (`jnKendaraan`, `tarif`).
4. **`tarif_inap`**: Biaya tambahan denda menginap harian (`kendaraan`, `tarif`).
5. **`tarif_lost`**: Besaran denda kehilangan tiket per jenis kendaraan (`kendaraan`, `tarif`).
6. **`tarif_maksimal`**: Batas tertinggi (*cap*) tarif per hari (`kendaraan`, `tarif`).
7. **`discount_tarif`**: Kode kupon voucher/promo potongan harga (`kode_trx`, `nama`, `nilai`, `status_aktif`).
8. **`hari_libur`**: Kalender tanggal libur nasional (`tgl`) untuk pemicu tarif libur.

### 6. Tabel & View Modul Kamera & Snapshot Foto
Mengelola integrasi perangkat keras kamera IP dan pencatatan snapshot visual kendaraan:

1. **`setipcam`** (Master IP Camera):
   - `id`: Primary key.
   - `nama`: Posisi/Label Kamera (`IN KENDARAAN`, `IN DRIVER`, `OUT KENDARAAN`, `OUT DRIVER`).
   - `lanip`: Alamat IPv4 lokal kamera (misal `192.168.1.144`).
   - `user`, `pass`: Kredensial autentikasi RTSP/HTTP kamera.
   - `encode`: Protokol kompresi stream video/frame (`H.264`, `H.265`, `MJPEG`).

2. **`foto_in`** (Snapshot Kendaraan Masuk):
   - `idtrx`: Foreign key ke `jurnal_transaksi.idtrx`.
   - `foto_masuk1`: Jalur file gambar snapshot plat/kendaraan saat masuk.
   - `foto_masuk2`: Jalur file gambar snapshot pengemudi saat masuk.
   - `gate`, `tgl_masuk`, `jam_masuk`, `nopol`, `jenisKendaraan`.

3. **`foto_out`** (Snapshot Kendaraan Keluar):
   - `idtrx`: Foreign key ke `jurnal_transaksi.idtrx`.
   - `foto_out`: Jalur file gambar snapshot plat/kendaraan saat keluar.
   - `foto_out2`: Jalur file gambar snapshot pengemudi saat keluar.

4. **`view_transaksi_foto`** (View Komparasi Visual Terpadu):
   - Menghubungkan `jurnal_transaksi` dengan `foto_out`, `foto_entry`, dan `foto_lost` untuk komparasi langsung gambar masuk vs keluar.

---

## 🧮 Formula Perhitungan Tarif Parkir

$$\text{Durasi Jam} = \lceil \Delta t (\text{waktuKeluar} - \text{waktuMasuk}) \rceil$$

1. **Jika Kendaraan Terdaftar Sebagai Member Aktif**:
   $$\text{Tarif Dasar} = 0$$

2. **Jika Kendaraan Umum (Non-Member)**:
   - Durasi $\le 1 \text{ jam}$: $\text{Tarif Dasar} = \text{Tarif Awal}$
   - Durasi $> 1 \text{ jam}$: $\text{Tarif Dasar} = \text{Tarif Awal} + [(\text{Durasi Jam} - 1) \times \text{Tarif Berjalan}]$
   - Jika terdapat Tarif Maksimal ($> 0$) dan $\text{Tarif Dasar} > \text{Tarif Maksimal}$:
     $$\text{Tarif Dasar} = \text{Tarif Maksimal}$$

3. **Total Tagihan Akhir (Termasuk Tiket Hilang & Diskon)**:
   $$\text{Total Tagihan} = \max(0, \text{Tarif Dasar} + \text{Denda Tiket Hilang} - \text{Diskon})$$

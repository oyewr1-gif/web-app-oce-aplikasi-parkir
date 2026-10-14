<p align="center">
  <img src="images/smartpark-logo.jpg" alt="Logo SmartPark Pro" width="360">
</p>

# LEMBAR SPESIFIKASI FITUR SISTEM
# SmartPark Pro — Sistem Manajemen Parkir Terpadu & Kasir POS

> **Dokumen Resmi Ringkasan Fitur, Kapabilitas Operasional, & Kebutuhan Perangkat Keras (Hardware).**  
> **Aplikasi: SmartPark Pro &bull; Kategori: Sistem Manajemen Parkir &amp; POS Kasir Terpadu &bull; Versi: 2.3.0**

---

## 🚗 1. Modul Transaksi Kendaraan Masuk (Gate IN)
- [x] **Penerbitan Tiket Otomatis**: Generate kode tiket transaksi unik (`TRX...`) berbasis stempel waktu presisi.
- [x] **Pilihan Gate Dispenser Masuk**: Integrasi pilihan pintu masuk otomatis (*manless dispenser* seperti `MAN R4`, `MAN R2`).
- [x] **Pilihan Gate Keluar Default**: Penentuan pos kasir tujuan default saat kendaraan masuk.
- [x] **Pencatatan Nomor Polisi Fleksibel**: Dukungan input nomor polisi manual atau dikosongkan (*tiket barcode dispenser mandiri*).
- [x] **Kategori Jenis Kendaraan**: Pilihan kategori kendaraan (Sepeda Motor, Mobil, Truk, Bus, dll).
- [x] **Pencatatan Waktu Real-Time**: Rekam tanggal, jam, menit, dan detik masuk kendaraan secara otomatis.
- [x] **Cetak Tiket Karcis Barcode**: Format struk karcis masuk siap cetak ke printer thermal dengan barcode scannable.
- [x] **Pembaruan Kuota Okupansi**: Penambahan otomatis jumlah kendaraan terparkir pada sistem real-time.

---

## 🏁 2. Modul Kasir Keluar POS & Fast Checkout (Gate OUT)
- [x] **Layout POS 1 Layar Penuh (*No Scroll*)**: Tampilan kasir kompak 2-panel sejajar yang pas dalam satu layar tanpa perlu scroll vertikal.
- [x] **Fast Checkout 1-Detik (One-Key Enter)**: Cukup scan barcode tiket $\rightarrow$ tekan tombol **[Enter]**, transaksi lunas seketika.
- [x] **Autofokus Cerdas**: Kursor otomatis aktif di kolom scan saat buka halaman, dan berpindah otomatis ke tombol bayar saat tiket ditemukan.
- [x] **Scanner Barcode Inline**: Kolom pencarian tiket/plat nomor horizontal ramping dengan pencarian instan.
- [x] **Nominal Uang Pas Otomatis**: Pengisian nilai bayar otomatis sesuai tagihan (`bayar = totalTagihan`) untuk mempercepat antrean.
- [x] **Input Nomor Polisi Keluar Opsional**: Kasir dapat mengetik plat nomor jika sebelumnya kosong, atau melewatinya untuk kecepatan transaksi.
- [x] **Kalkulator Kembalian Cepat**: Perhitungan uang kembalian otomatis jika kasir menginput nominal tunai lebih.
- [x] **Cetak Struk Thermal Pelunasan**: Cetak struk pembayaran resmi bukti keluar dengan rincian biaya, durasi, dan metode bayar.
- [x] **Daftar Antrean Kendaraan Parkir**: Tab terpisah untuk melihat seluruh kendaraan yang sedang terparkir lengkap dengan tombol aksi proses keluar langsung.

---

## 💳 3. Modul Multi-Metode Pembayaran
- [x] **Pembayaran Tunai (*Cash*)**: Pengelolaan transaksi uang tunai dengan deteksi uang pas atau hitung kembalian instan.
- [x] **Pembayaran QRIS Dinamis**: Dukungan transaksi non-tunai dompet digital (GoPay, OVO, Dana, ShopeePay, LinkAja, Mobile Banking).
- [x] **Uang Elektronik / Kartu Prabayar (*Prepaid*)**: Dukungan pencatatan nomor referensi/approval kartu bank (Mandiri e-Money, Flazz BCA, Brizzi BRI, TapCash BNI).
- [x] **Pencatatan Trace ID / Nomor Referensi**: Kolom khusus pencatatan kode approval transaksi non-tunai untuk kebutuhan audit keuangan.

---

## 📷 4. Modul Verifikasi Visual & Integrasi IP Camera CCTV
- [x] **Master Pengaturan IP Camera (`setipcam`)**: Pengelolaan alamat IP LAN (IPv4), port, username, password, dan protokol encoding kamera.
- [x] **4 Sudut Kamera Gerbang**: Konfigurasi independen untuk kamera:
  - *IN KENDARAAN* (Plat/Bodi Masuk)
  - *IN DRIVER* (Wajah Pengemudi Masuk)
  - *OUT KENDARAAN* (Plat/Bodi Keluar)
  - *OUT DRIVER* (Wajah Pengemudi Keluar)
- [x] **Uji Koneksi Real-Time (*Test Ping / Socket*)**: Tombol tes latensi dan status online/offline kamera via AJAX tanpa reload halaman.
- [x] **Live Preview Frame Snapshot**: Modal pratinjau tangkapan frame kamera CCTV secara langsung.
- [x] **Mini Thumbnail di Layar Kasir**: Tampilan thumbnail mini foto kendaraan & wajah pengemudi saat masuk di layar kasir keluar.
- [x] **Lightbox Zoom Modal**: Fitur klik foto untuk memperbesar resolusi tinggi guna mencocokkan plat nomor dan pengemudi fisik.
- [x] **Smart Fallback Visual**: Otomatis menampilkan ilustrasi CCTV dinamis ber-watermark OSD jika kamera offline atau file fisik belum ada.
- [x] **Galeri Audit Foto Komparatif**: Halaman audit perbandingan foto masuk vs keluar per transaksi di menu laporan.

---

## 🛡️ 5. Modul Penanganan Karcis Hilang & Audit Pembatalan (VOID)
- [x] **Penanganan Karcis Hilang Terpadu**: Switch opsi tiket hilang yang otomatis menambahkan denda kehilangan resmi ke total tagihan.
- [x] **Pencatatan Identitas Klaim Tiket Hilang**: Formulir verifikasi kepemilikan mencakup No. STNK, No. KTP, Nama Pemilik, dan No. HP.
- [x] **Pembatalan Transaksi Berizin (VOID)**: Tombol pembatalan transaksi dengan kewajiban mengisi alasan pembatalan tertulis.
- [x] **Audit Trail VOID**: Seluruh pembatalan tiket otomatis masuk ke log audit khusus (status `N`) mencatat waktu dan nama petugas eksekutor.
- [x] **Pengembalian Kuota Kapasitas**: Pembatalan tiket otomatis mengembalikan slot kapasitas kendaraan terparkir.

---

## 🧾 6. Modul Tutup Shift & Rekonsiliasi Kasir
- [x] **Master Jam Kerja Shift (`jamshift`)**: Pengaturan jam operasional per shift (Shift Pagi, Sore, Malam).
- [x] **Kalkulasi Pendapatan Sistem per Shift**: Penjumlahan otomatis total pendapatan per kasir berdasarkan transaksi lunas di shift tersebut.
- [x] **Rincian Penerimaan per Kategori**: Pemisahan pendapatan per jenis kendaraan dan metode pembayaran (Tunai, QRIS, E-Money).
- [x] **Validasi Fisik Kas vs Sistem**: Kasir menginput total uang fisik yang diserahkan, sistem otomatis menghitung selisih kas.
- [x] **Indikator Status Kas Otomatis**:
  - *Cocok / Pas (Rp 0)* $\rightarrow$ Badge Hijau
  - *Lebih (+Rp)* $\rightarrow$ Badge Kuning
  - *Kurang (-Rp)* $\rightarrow$ Badge Merah
- [x] **Cetak Berita Acara Serah Terima**: Cetak dokumen fisik resmi penutupan kas shift dengan kolom tanda tangan Kasir dan Supervisor.

---

## 🏷️ 7. Modul Multi-Skema Tarif & Manajemen Diskon
- [x] **Tarif Progresif Jam Pertama & Berikutnya**: Konfigurasi tarif awal jam ke-1 dan tarif per jam berikutnya per jenis kendaraan.
- [x] **Tarif Flat (Sekali Bayar)**: Pengaturan tarif tetap sekali masuk tanpa menghitung durasi waktu.
- [x] **Tarif Menginap (*Overnight*)**: Pengenaan tarif denda tambahan untuk kendaraan yang menginap lewat tengah malam.
- [x] **Denda Karcis Hilang (*Lost Ticket*)**: Penentuan besaran denda kehilangan tiket per kategori kendaraan.
- [x] **Tarif Maksimal (*Capping*)**: Pengaturan batas atas tarif tertinggi dalam satu hari agar tagihan tetap wajar.
- [x] **Kalender Hari Libur Nasional**: Penetapan tanggal merah untuk penerapan skema tarif akhir pekan/libur.
- [x] **Kupon Diskon & Voucher Promo**: Manajemen kode voucher potongan harga (nilai rupiah/persentase) dengan status aktif/non-aktif.

---

## 👥 8. Modul Member & Kendaraan Berlangganan
- [x] **Pendaftaran Member Parkir**: Registrasi member berbasis nomor polisi, jenis kendaraan, nama pemilik, dan masa aktif (*expired date*).
- [x] **Deteksi Otomatis Saat Keluar**: Kendaraan member aktif langsung terdeteksi gratis (Rp 0) di pos kasir tanpa perlu input manual.
- [x] **Indikator Masa Berlaku Member**: Peringatan visual status keanggotaan aktif atau kedaluwarsa.
- [x] **Pencatatan Instansi / Perusahaan**: Dukungan relasi data member korporat / B2B.

---

## 👤 9. Modul Manajemen Master Kendaraan & Pengguna
- [x] **Master Kategori Kendaraan**: Penambahan, pengubahan, dan penghapusan jenis kendaraan beserta kuota daya tampung maksimal.
- [x] **Manajemen Pengguna Multi-Level**: Pengelolaan akun pengguna dengan tingkatan hak akses:
  - *Administrator* (Akses penuh seluruh master data, tarif, kamera, laporan, dan user)
  - *Supervisor* (Akses monitoring, verifikasi setoran shift, dan audit void)
  - *Kasir / Operator* (Akses khusus transaksi masuk dan kasir keluar)
- [x] **Enkripsi Sandi Keamanan**: Pengamanan kata sandi akun pengguna dengan standar enkripsi modern tingkat tinggi.
- [x] **Audit Riwayat Login**: Pencatatan riwayat sesi login petugas, waktu masuk, dan pos gardu kerja.

---

## 📊 10. Modul Pelaporan, Statistik & Audit Investigasi
- [x] **Dashboard Statistik Real-Time**: Ringkasan total transaksi hari ini, total pendapatan, okupansi aktif, dan statistik grafik.
- [x] **Laporan Jurnal Transaksi**: Rekapitulasi transaksi masuk/keluar lengkap dengan filter rentang tanggal, jenis kendaraan, dan status.
- [x] **Laporan Audit Pembatalan (VOID)**: Laporan investigasi pembatalan transaksi lengkap dengan alasan dan penanggung jawab.
- [x] **Galeri Audit Foto Komparatif**: Tampilan komparasi foto masuk vs keluar per transaksi untuk pembuktian klaim dan pencegahan pencurian.
- [x] **Format Cetak Laporan Rapi**: Seluruh halaman laporan teroptimasi untuk cetak dokumen fisik (A4 / printer kasir).

---

## 💻 11. Kebutuhan Perangkat Keras (Hardware Minimum) & Lingkungan Operasional

### A. Komputer Server / PC Induk (Administrator & Pusat Data)
- [x] **Prosesor**: Minimal Intel Core i3 (Generasi ke-4+) atau AMD Ryzen 3 (Rekomendasi Core i5 untuk lokasi dengan traffic padat >1.000 kendaraan/hari).
- [x] **Memori (RAM)**: Minimal 4 GB (Rekomendasi 8 GB).
- [x] **Media Penyimpanan**: SSD minimal 128 GB (Rekomendasi SSD 256 GB untuk menampung arsip foto snapshot CCTV jangka panjang).
- [x] **Antarmuka Jaringan**: Port LAN Gigabit Ethernet (RJ-45 100/1000 Mbps).
- [x] **Sistem Operasi**: Windows 10 / Windows 11 (64-bit) atau Linux Server (Ubuntu / Debian).
- [x] **Daya Cadangan**: UPS (*Uninterruptible Power Supply*) minimal 600 VA untuk proteksi pemadaman listrik mendadak.

### B. Komputer Pos Kasir Keluar (Gate OUT)
- [x] **Prosesor**: Minimal Intel Celeron / Intel Dual Core / Core i3 (Mendukung PC standar maupun Mini PC hemat daya).
- [x] **Memori (RAM)**: Minimal 2 GB (Rekomendasi 4 GB).
- [x] **Media Penyimpanan**: SSD minimal 64 GB / 128 GB.
- [x] **Monitor / Layar**: Resolusi layar minimal 1366 × 768 (Rekomendasi 1920 × 1080 Full HD untuk kenyamanan operasional kasir tanpa scroll).
- [x] **Port Konektivitas (I/O)**: Minimal 3 port USB aktif (untuk Barcode Scanner, Printer Thermal Struk, dan Keyboard/Mouse).
- [x] **Sistem Operasi**: Windows 7 / Windows 8.1 / Windows 10 / Windows 11.

### C. Perangkat Periferal & Lapangan
- [x] **Printer Thermal Kasir**: Printer struk thermal ukuran 58mm atau 80mm dengan antarmuka USB / LAN (Mendukung perintah standar ESC/POS dan pemotong otomatis/auto-cutter).
- [x] **Barcode Scanner**: Scanner barcode 1D atau 2D QR-Code (Model USB Handheld atau Omnidirectional Handsfree Auto-Sense).
- [x] **Kamera Pengawas (IP Camera CCTV)**: IP Camera resolusi minimal 2 Megapixel (Full HD 1080p) yang mendukung protokol RTSP / ONVIF / HTTP JPEG Snapshot untuk penangkapan visual plat nomor dan wajah pengemudi.
- [x] **Barrier Gate & Dispenser Karcis (Opsional / Integrasi)**: Kompatibel dengan Barrier Gate palang otomatis, sensor loop detector kendaraan, dan mesin tiket dispenser manless.

### D. Infrastruktur & Topologi Jaringan
- [x] **Operasional Mandiri (*Offline-First*)**: Sistem bekerja 100% pada jaringan lokal (LAN) tanpa wajib koneksi internet publik.
- [x] **Operasional Daring (*Online*)**: Sistem bekerja 100% pada jaringan internet, dan bia diakses dari internet.
- [x] **Switch / Hub Jaringan**: Switch Hub 10/100/1000 Mbps untuk distribusi kabel ke pos kasir, server, dan IP camera.
- [x] **Kabel Jaringan**: Kabel UTP standar Cat5e atau Cat6 dengan konektor RJ-45 terpasang rapi.

### E. Infrastruktur Jaringan Internet (Opsional)
- [x] **Untuk dapat diakses dari internet dibutuhkan ip public jika server mandiri.
- [x] **Atau sewa hosting jika tanpa server mandiri.

---
*SmartPark Pro — Lembar Spesifikasi Fitur & Kebutuhan Hardware Resmi.*

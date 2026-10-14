# BROSUR PENAWARAN PRODUK & SOLUSI
# SmartPark Pro — Sistem Manajemen Parkir Terpadu & Kasir POS

> **Solusi Cerdas, Cepat, dan Akuntabel untuk Efisiensi Operasional, Keamanan Maksimal, dan Pencegahan Kebocoran Pendapatan Lahan Parkir Anda.**

---

## 📌 Ringkasan Eksekutif (Executive Summary)

**SmartPark Pro** adalah perangkat lunak manajemen parkir modern berbasis web (*pure PHP MVC*) yang dirancang khusus untuk memenuhi standar operasional lahan parkir komersial skala kecil hingga enterprise. Menggabungkan kecepatan transaksi gardu keluar (*fast checkout 1-detik*), verifikasi visual kamera pengawas (*CCTV IP camera snapshot*), fleksibilitas multi-metode pembayaran (Tunai, QRIS, E-Money), serta rekonsiliasi kas tutup shift otomatis.

Sistem ini memastikan **100% transparansi pendapatan**, meminimalkan antrean di gerbang keluar, dan melindungi pengelola lahan dari potensi kecurangan (*fraud*) maupun risiko kehilangan kendaraan.

---

## 🎯 Solusi Terhadap Masalah Nyata Pengelola Parkir

| Tantangan Operasional di Lapangan | Solusi Nyata dari SmartPark Pro |
| :--- | :--- |
| **Kebocoran Kas & Pembatalan Liar**<br>Petugas kasir membatalkan transaksi atau mengantongi uang tanpa tiket resmi. | **Audit Anti-Fraud & VOID Ketat**<br>Setiap pembatalan tiket wajib menyertakan alasan tertulis dan langsung tercatat di log audit khusus yang tidak dapat dimanipulasi. |
| **Antrean Panjang di Gerbang Keluar**<br>Kasir harus mengetik ulang plat, menghitung uang, dan berpindah-pindah menu. | **Fast Checkout POS (1 Layar, 1 Tombol Enter)**<br>Scan tiket $\rightarrow$ total langsung tampil $\rightarrow$ tekan tombol Enter $\rightarrow$ struk lunas langsung tercetak dalam waktu kurang dari 2 detik. |
| **Pencurian / Tiket Tertukar**<br>Tiket hilang atau ditukar dengan kendaraan lain yang jenis/nilainya lebih murah. | **Verifikasi Visual Snapshot Kamera (CCTV)**<br>Sistem menampilkan perbandingan foto kendaraan & wajah pengemudi saat masuk vs saat keluar secara berdampingan di layar kasir. |
| **Selisih Kas Saat Pergantian Shift**<br>Sulit mencocokkan uang fisik di laci kasir dengan angka transaksi di komputer. | **Rekonsiliasi Shift & Berita Acara Otomatis**<br>Perhitungan selisih kas fisik vs sistem otomatis, lengkap dengan cetak Berita Acara Serah Terima bertandatangan kasir & supervisor. |
| **Struktur Tarif yang Kaku**<br>Sulit menerapkan tarif khusus saat malam minggu, hari libur, atau tarif inap. | **Mesin Multi-Tarif Fleksibel (7 Skema)**<br>Mendukung tarif progresif per jam, tarif flat, tarif menginap, batas maksimal harian (*capping*), denda karcis hilang, dan promo voucher. |

---

## 🌟 8 Fitur Unggulan Utama SmartPark Pro

### 1. ⚡ Fast Checkout POS (1 Halaman, 1 Detik, 1 Tombol)
- **Tampilan Khusus Gardu Kasir**: Dirancang seringkas mungkin agar pas dalam satu layar monitor tanpa perlu *scrolling* ke bawah.
- **One-Key Enter Checkout**: Setelah kasir men-scan barcode karcis, fokus kursor otomatis berada di tombol cetak. Cukup tekan tombol **[Enter]** pada keyboard, transaksi selesai dan palang terbuka.
- **Nominal Pas Otomatis**: Menghilangkan waktu mengetik angka nominal uang untuk alur kendaraan yang sangat padat.

### 2. 💳 Fleksibilitas Multi-Metode Pembayaran
- **Tunai (*Cash*)**: Perhitungan uang pas otomatis atau hitung kembalian instan.
- **QRIS Dinamis**: Mendukung pembayaran non-tunai dompet digital (GoPay, OVO, Dana, ShopeePay, BCA, dll).
- **Uang Elektronik / Prepaid**: Pencatatan approval kartu E-Money Mandiri, Flazz BCA, Brizzi, TapCash BNI.

### 3. 📷 Integrasi IP Camera CCTV & Galeri Audit Foto Visual
- **Snapshot Masuk & Keluar**: Kamera secara otomatis membidik 2 sudut penting di setiap gerbang:
  1. *Sudut Kendaraan*: Foto plat nomor dan bodi fisik kendaraan.
  2. *Sudut Driver*: Foto wajah pengemudi di jendela pos.
- **Inspeksi Langsung Kasir**: Kasir di gerbang keluar melihat foto thumbnail kendaraan saat masuk untuk memastikan kecocokan fisik sebelum meloloskan kendaraan.
- **Galeri Audit Laporan**: Tab audit foto di menu laporan memudahkan manajemen memeriksa rekaman komparasi foto masuk vs keluar untuk setiap transaksi.

### 4. 🛡️ Penanganan Tiket Hilang & Audit Pembatalan (VOID)
- **Modul Karcis Hilang Terpadu**: Centang opsi tiket hilang, sistem otomatis menambahkan denda sesuai jenis kendaraan dan mencatat nomor STNK, nomor KTP, serta nama pemilik untuk arsip klaim resmi.
- **Log Audit VOID**: Memproteksi pengelola dari manipulasi kasir yang membatalkan tiket secara sepihak.

### 5. 🧾 Rekonsiliasi Kasir & Cetak Berita Acara Tutup Shift
- **Pemisahan Jam Shift**: Pengaturan jam operasional per shift (Shift Pagi, Sore, Malam).
- **Deteksi Selisih Kas Otomatis**: Kasir menginput uang fisik yang diserahkan, sistem langsung membandingkan dengan total pendapatan tercatat di sistem (Cocok / Lebih / Kurang).
- **Cetak Dokumen Fisik Berita Acara**: Dokumen serah terima siap cetak dengan kolom tanda tangan Kasir dan Supervisor untuk pertanggungjawaban kas harian.

### 6. 🏷️ Multi-Skema Tarif & Manajemen Diskon Lengkap
- **Tarif Jam Pertama & Progresif**: Atur tarif awal dan tarif jam berikutnya per jenis kendaraan (Motor, Mobil, Truk, Bus).
- **Tarif Maksimal (*Capping*)**: Menetapkan batas maksimal biaya parkir harian agar tetap kompetitif bagi pengunjung harian.
- **Tarif Inap (*Overnight*)**: Pengenaan biaya denda menginap otomatis setelah melewati pergantian hari.
- **Promo Voucher & Diskon**: Dukungan kode kupon potongan biaya untuk tenant rekanan, toko, atau program promosi mall.

### 7. 👥 Sistem Kendaraan Member & Langganan
- **Registrasi Member**: Pendaftaran member bulanan/tahunan berbasis plat nomor.
- **Deteksi Otomatis Saat Keluar**: Kendaraan member aktif langsung terdeteksi berstatus **GRATIS (Rp 0)** di layar kasir keluar tanpa perlu penyesuaian manual.

### 8. 📊 Dashboard Manajemen & Laporan Keuangan Real-Time
- **Statistik Okupansi**: Memantau kapasitas lahan dan jumlah kendaraan yang sedang terparkir saat ini secara *live*.
- **Laporan Fleksibel**: Filter pendapatan berdasarkan rentang tanggal, gate masuk/keluar, jenis kendaraan, dan status transaksi.
- **Siap Cetak & Ekspor**: Format laporan rapi yang siap dicetak ke printer A4 atau printer kasir.

---

## 🔄 Alur Operasional Terpadu di Lapangan

```text
[GERBANG MASUK (Gate IN)]
   │
   ├─► Pengendara menekan tombol dispenser / scan karcis barcode
   ├─► IP Camera mengambil snapshot plat nomor & wajah pengemudi
   ├─► Tiket tercetak (Kode TRX unik), palang barrier gate terbuka
   │
   ▼
[AREA LAHAN PARKIR]
   │
   ├─► Status kendaraan aktif tercatat di sistem ("B - Sedang Parkir")
   ├─► Kapasitas lahan di dashboard admin otomatis terbarui (real-time)
   │
   ▼
[GERBANG KELUAR (Gate OUT / POS Kasir)]
   │
   ├─► Kasir men-scan barcode karcis / ketik tiket
   ├─► Layar menampilkan rincian durasi, total biaya, & foto saat masuk
   ├─► Kasir memverifikasi kecocokan kendaraan & menerima pembayaran
   ├─► Kasir menekan [Enter], struk lunas tercetak, palang keluar terbuka
   │
   ▼
[PENUTUPAN OPERASIONAL SHIFT]
   │
   ├─► Supervisor & Kasir menghitung fisik uang tunai di laci kasir
   ├─► Sistem memvalidasi selisih kas vs transaksi sistem
   └─► Cetak dokumen fisik "Berita Acara Serah Terima Setoran"
```

---

## 📦 Pilihan Paket Solusi & Penawaran

Kami menyediakan dua opsi paket implementasi yang fleksibel, disesuaikan dengan kebutuhan dan infrastruktur lokasi parkir Anda:

```text
┌──────────────────────────────────────────────┐  ┌──────────────────────────────────────────────┐
│           PAKET 1: ADMIN EDITION             │  │        PAKET 2: FULL ENTERPRISE POS          │
│          (Back-Office Management)            │  │          (Admin + Kasir POS Lengkap)         │
├──────────────────────────────────────────────┤  ├──────────────────────────────────────────────┤
│ Cocok untuk: Pengelola gedung, kantor, atau  │  │ Cocok untuk: Mall, Rumah Sakit, Kawasan      │
│ lahan parkir yang membutuhkan pusat kontrol  │  │ Bisnis, dan Lahan Komersial yang butuh       │
│ data, audit pendapatan, dan master tarif.    │  │ operasional gardu gerbang keluar masuk.      │
├──────────────────────────────────────────────┤  ├──────────────────────────────────────────────┤
│ ✅ Dashboard Ringkasan & Grafik Okupansi     │  │ ✅ Seluruh Fitur Paket 1 (Admin Lengkap)     │
│ ✅ Manajemen Master Kendaraan & Kapasitas    │  │ ✅ Modul Kasir Masuk (Pintu Gate IN)         │
│ ✅ Master Tarif Terpadu (Progresif/Flat/Inap)│  │ ✅ Modul Kasir Keluar POS (Fast Checkout 1s) │
│ ✅ Manajemen Pengguna & Hak Akses Kasir/SPV  │  │ ✅ Integrasi Snapshot IP Camera CCTV         │
│ ✅ Modul Monitoring & Setting IP Camera      │  │ ✅ Cetak Tiket Karcis & Struk Pembayaran     │
│ ✅ Modul Member & Langganan Kendaraan        │  │ ✅ Dukungan Multi-Payment (Tunai/QRIS/Card)  │
│ ✅ Laporan Pendapatan, Jurnal, & Audit VOID  │  │ ✅ Penanganan Tiket Hilang & Denda Otomatis  │
│ ✅ Modul Verifikasi Tutup Shift & Setoran    │  │ ✅ Tutup Shift Kasir & Berita Acara Kas      │
│ ✅ Galeri Audit Komparasi Foto Transaksi     │  │ ✅ Shortcut Keyboard POS (One-Key Enter)     │
└──────────────────────────────────────────────┘  └──────────────────────────────────────────────┘
```

---

## 💻 Spesifikasi Kebutuhan Perangkat Keras (Hardware Minimum)

Aplikasi dibangun dengan arsitektur web yang sangat ringan (*lightweight*) sehingga dapat berjalan stabil pada komputer standar:

| Komponen | Spesifikasi Minimum Rekomendasi |
| :--- | :--- |
| **Server / PC Induk** | Intel Core i3 / AMD Ryzen 3, RAM 4 GB, SSD 128 GB, OS Windows / Linux |
| **PC Pos Kasir** | Intel Dual Core / Core i3, RAM 4 GB, OS Windows 10/11 |
| **Printer Tiket & Struk** | Thermal Printer 58mm atau 80mm (USB / LAN Serial) |
| **Barcode Scanner** | Scanner Barcode 1D/2D (Omni-directional / Handheld USB) |
| **Kamera Pengawas** | IP Camera ONVIF / RTSP Snapshot (Hikvision, Dahua, dll) |
| **Jaringan** | Switch Hub & Kabel LAN UTP Cat5e/Cat6 lokal |

---

## 🏆 Mengapa Memilih SmartPark Pro?

1. **Investasi Bebas Biaya Langganan Bulanan Yang Membebani (*No Hidden Royalties*)**:
   Perangkat lunak menjadi aset milik Anda sepenuhnya, tanpa kewajiban biaya sewa perangkat lunak per transaksi.
2. **Bekerja Mandiri di Jaringan Lokal (*Offline-First*)**:
   Tidak bergantung pada koneksi internet publik. Sistem tetap beroperasi 100% normal meskipun koneksi internet provider terputus.
3. **Penyimpanan Data Terpusat & Aman**:
   Menggunakan database relational MySQL dengan integritas transaksi tinggi, backup berkala, dan enkripsi kata sandi.
4. **Antarmuka Bersih, Elegan, & Ramah Kasir**:
   Didesain agar operator baru dapat mahir mengoperasikan sistem hanya dalam pelatihan singkat selama 15 menit.

---

## 🤝 Jadwalkan Demo Sistem & Konsultasi Gratis

Tertarik untuk meningkatkan pendapatan, mempercepat antrean, dan mengamankan lahan parkir Anda? 

Kami siap menghadirkan **Live Demo Sistem** dan melakukan **Survei Kebutuhan Lokasi** untuk Anda.

- 📞 **Telepon / WhatsApp**: *[Hubungi Tim Pemasaran Kami]*
- 📧 **Email**: *[info@smartparkpro.com]*
- 🏢 **Kantor Layanan**: *[Alamat Perusahaan / Penyedia Solusi]*

---
*SmartPark Pro — Efisiensi Tanpa Batas, Keamanan Terpercaya.*

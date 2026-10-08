# Panduan Pengguna (User Manual)

Dokumen ini berisi petunjuk operasional lengkap penggunaan **Aplikasi Manajemen Parkir PHP MVC** bagi pengguna tingkat Administrator maupun Petugas Kasir.

---

## 🔑 Hak Akses & Akun Login

Aplikasi memiliki 2 tingkatan peran pengguna:

1. **Administrator (Level 1)**:
   - Memiliki hak akses penuh ke seluruh modul sistem: Dashboard, Transaksi Masuk & Keluar, Pengelolaan Member, Master Kendaraan, **Master Tarif & Diskon**, **Setoran Kasir & Shift**, Manajemen User/Kasir, serta **Laporan & Audit VOID**.
   - Akun Bawaan: Username `admin` | Password `123456`

2. **Petugas Kasir (Level 2)**:
   - Memiliki hak akses ke operasional transaksi harian: Dashboard, Input Kendaraan Masuk (Cetak Tiket), Gate Keluar & Pembayaran Multi-Metode, Penanganan Tiket Hilang, Pembatalan (VOID) transaksi yang salah, dan Pendaftaran Member Parkir.
   - Akun Bawaan: Username `kasir` | Password `123456`

---

## 🧭 Struktur Navigasi Sidebar Administrator

Pada sidebar Administrator, menu dikelompokkan dengan rapi menggunakan sistem **Dropdown Collapsible**:

```text
[ADMINISTRATOR]
├── ▼ Master & User (Klik untuk buka/tutup)
│   ├── [MASTER DATA]
│   │   ├── Master Kendaraan       (/kendaraan)
│   │   ├── Master Tarif & Diskon  (/tarif)
│   │   └── Master Jam Shift       (/setoran/shift)
│   └── [PENGGUNA]
│       └── Kelola User / Kasir    (/user)
├── Setoran Kasir                  (/setoran)
└── Laporan & Audit Void           (/laporan)
```

- **Perilaku Otomatis (*Auto-Expand*)**: Jika Anda sedang membuka halaman Master Kendaraan, Tarif, Jam Shift, atau Kelola User, grup dropdown akan otomatis terbuka dan menyorot menu yang sedang aktif.
- **Ikon Panah (*Chevron*)**: Berputar $180^\circ$ saat dropdown dalam keadaan terbuka.

---

1. Buka menu **Kendaraan Masuk** pada sidebar.
2. Masukkan **Nomor Polisi (Plat Nomor)** kendaraan (contoh: `B 1234 ABC`).
3. Pilih **Jenis Kendaraan** (Sepeda Motor, Mobil, dll).
4. Pilih **Pintu Gate Masuk** (otomatis memuat daftar mesin dispenser dari tabel `manless`, misal: `MAN R4` atau `MAN R2`).
5. Pilih **Pintu Gate Keluar Default** (dari tabel `pos_kasir`, misal: `POS R4`).
6. Klik tombol **Cetak Tiket Parkir & Buka Gate**.
7. Sistem mencetak karcis thermal dengan Barcode Kode Tiket unik (`TRX...`), tanggal, jam masuk, dan pintu gate.

---

## 💳 2. Alur Transaksi Kendaraan Keluar (Gate OUT - Fast Checkout 1 Halaman)

Tampilan kasir keluar dirancang secara khusus untuk **tampil dalam 1 layar penuh tanpa scroll vertikal**:

1. Buka menu **Kendaraan Keluar** pada sidebar.
2. Temukan transaksi kendaraan:
   - **Metode Scan/Cari (Utama)**: Scan barcode karcis atau ketik Nomor Tiket / Plat Nomor pada Scanner Bar di bagian atas, lalu tekan **Enter**.
   - **Metode Tab Kendaraan Parkir**: Klik tab **Kendaraan Sedang Parkir**, lalu klik tombol kuning (**Proses Keluar**) pada baris kendaraan yang diinginkan.
3. Sistem langsung menampilkan form checkout **2-Panel Sejajar (1 Halaman Penuh)**:
   - **Panel Kiri**: Rincian nomor tiket, plat nomor (opsional jika kosong), jenis kendaraan, gate masuk, waktu masuk, waktu keluar, dan durasi parkir.
   - **Panel Kanan**: Pilihan gate keluar, nominal **Total Tagihan Parkir** berukuran besar, dan badge status `[UANG PAS (OTOMATIS)]`.
4. **Fast Checkout (1 Detik)**:
   - Kursor otomatis langsung mengarah ke tombol **SELESAIKAN & CETAK STRUK [↵ ENTER]**.
   - Kasir cukup menekan tombol **Enter** pada keyboard (atau 1 kali klik mouse) untuk langsung menyelesaikan transaksi dan mencetak struk!
5. **Opsi Tambahan (Jika Diperlukan Saja)**:
   - **Karcis Hilang (+Denda)**: Centang switch karcis hilang untuk memuat denda kehilangan dan mengisi data verifikasi identitas (STNK/KTP).
   - **Opsi Lanjutan / Kembalian**: Klik tombol opsi lanjutan untuk memilih metode non-tunai (QRIS/E-Money) atau menghitung kembalian uang pecahan lebih.
6. Sistem mencetak struk thermal tanda bukti pelunasan biaya parkir.

---

## 🚫 3. Alur Pembatalan Transaksi Parkir (VOID)

Jika terjadi kesalahan transaksi (salah ketik nomor polisi, salah pilih jenis kendaraan, atau pengunjung batal masuk):
1. Pada halaman **Kendaraan Keluar**, cari kendaraan pada tabel *Daftar Kendaraan Sedang Parkir* atau pada kartu rincian biaya.
2. Klik tombol **Batalkan (Void)** berwarna merah.
3. Pada modal konfirmasi yang muncul, **wajib ketik alasan pembatalan** (misalnya: *"Salah input nopol oleh kasir"*).
4. Klik **Batalkan Transaksi (VOID)**.
5. Transaksi akan ditandai dengan status `'N'`, kuota okupansi kendaraan otomatis dikembalikan, dan riwayat pembatalan dicatat lengkap di **Laporan & Audit VOID**.

---

## 💵 4. Alur Tutup Shift & Setoran Kasir (`/setoran`)

Modul ini digunakan oleh Supervisor/Admin untuk merekonsiliasi kas penutupan shift kasir:

1. Buka menu **Setoran Kasir & Shift** $\rightarrow$ klik tombol **Tutup & Input Setoran Baru**.
2. Pilih parameter setoran:
   - **Tanggal Transaksi**
   - **Shift Kerja** (Shift Pagi, Sore, atau Malam)
   - **Petugas Kasir**
3. Klik **Hitung Data Sistem**. Sistem akan menjumlahkan seluruh transaksi lunas pada shift tersebut dan merinci pendapatan per kategori kendaraan (Tunai, QRIS, E-Money).
4. Masukkan **Total Uang Fisik Yang Diserahkan (Rp)** hasil hitungan fisik kasir.
5. Sistem secara otomatis menampilkan status selisih kas:
   - **Rp 0 (Cocok / Pas)**: Ditandai hijau.
   - **+Rp xxx (Lebih)**: Ditandai kuning.
   - **-Rp xxx (Kurang)**: Ditandai merah.
6. Klik **Simpan & Sahkan Berita Acara**.
7. Klik tombol **Cetak Berita Acara** untuk mencetak dokumen Berita Acara Serah Terima Setoran lengkap dengan kolom tanda tangan Kasir dan Supervisor.

### Mengatur Jam Kerja Shift (`/setoran/shift`):
- Buka menu **Master Jam Shift**.
- Edit jam mulai (masuk) dan jam selesai (pulang) untuk masing-masing shift operasional.

---

## 🏷️ 5. Mengelola Master Tarif & Diskon (`/tarif`)

Modul Administrator untuk mengatur seluruh variabel perhitungan biaya:

1. **Tab Progresif**: Atur tarif jam ke-1 dan tarif jam berikutnya per jenis kendaraan.
2. **Tab Tarif Flat**: Atur tarif tetap (sekali masuk) untuk kendaraan tertentu.
3. **Tab Denda Inap**: Atur tarif denda parkir menginap per malam.
4. **Tab Denda Tiket Hilang**: Atur besaran denda kehilangan tiket per jenis kendaraan (`tarif_lost`).
5. **Tab Tarif Maksimal**: Atur batas tertinggi (*capping*) biaya parkir dalam 1 hari.
6. **Tab Diskon & Voucher**: Tambah kode kupon promo potongan harga dan atur status keaktifannya.
7. **Tab Hari Libur**: Tambahkan kalender tanggal merah/libur nasional untuk penerapan tarif khusus.

---

## 👥 6. Pengelolaan Member & User
- **Member Parkir (`/member`)**: Mendaftarkan kendaraan langganan berdasarkan plat nomor dan masa berlaku.
- **Kelola User / Kasir (`/user`)**: Menambah akun petugas, mengatur hak akses, reset password, dan memantau riwayat aktivitas login/logout petugas.

---

## 📷 7. Manajemen & Monitoring IP Camera (`/kamera`)
Modul Administrator untuk mengelola dan memantau konektivitas kamera snapshot di gerbang parkir:

1. Buka menu **IP Camera Gate** pada dropdown *Master & User* di sidebar.
2. Halaman menampilkan 4 kamera snapshot gerbang:
   - **IN KENDARAAN**: Kamera pengambilan foto plat/kendaraan gerbang masuk.
   - **IN DRIVER**: Kamera pengambilan foto wajah pengemudi gerbang masuk.
   - **OUT KENDARAAN**: Kamera pengambilan foto plat/kendaraan gerbang keluar.
   - **OUT DRIVER**: Kamera pengambilan foto wajah pengemudi gerbang keluar.
3. **Uji Konektivitas Jaringan**:
   - Klik tombol **Uji Ping** pada kartu kamera untuk memeriksa apakah perangkat kamera dalam status ONLINE atau OFFLINE di jaringan LAN.
4. **Live Preview Frame**:
   - Klik tombol **Frame** untuk melihat pratinjau snapshot kamera.
5. **Edit Pengaturan Kamera**:
   - Klik **Edit Pengaturan** untuk mengubah IP Address LAN, username, password, dan protokol encoding kamera.

---

## 🔍 8. Galeri Audit Foto CCTV Kendaraan (`/laporan?tab=foto`)
Modul investigasi dan audit visual transaksi kendaraan:

1. Buka menu **Laporan & Audit Void** $\rightarrow$ pilih tab **Audit Foto Kendaraan**.
2. Sistem menampilkan galeri komparatif per transaksi:
   - **Sisi Kiri**: Foto snapshot saat kendaraan masuk (Plat Kendaraan & Wajah Driver).
   - **Sisi Kanan**: Foto snapshot saat kendaraan keluar (Plat Kendaraan & Wajah Driver).
3. Klik pada foto mana pun untuk memperbesar (*lightbox zoom*) guna memverifikasi kecocokan kendaraan dan mencegah penipuan tiket tertukar atau pencurian kendaraan.

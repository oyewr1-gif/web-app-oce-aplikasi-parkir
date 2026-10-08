# Referensi Teknis API & Model Class

Dokumen ini berisi dokumentasi teknis komponen Core Framework, Controllers, dan Model Classes pada aplikasi **Parkir PHP MVC**.

---

## ⚙️ Core Components

### 1. `Core\Database.php`
Wrapper PDO untuk komunikasi ke MySQL / MariaDB dengan prepared statement aman.

- **`query($sql)`**: Menyiapkan kueri SQL.
- **`bind($param, $value, $type = null)`**: Mengikat parameter dengan tipe data PDO yang tepat.
- **`execute()`**: Menjalankan statement SQL; mengembalikan boolean status sukses.
- **`resultSet()`**: Mengembalikan seluruh baris data sebagai array asosiatif.
- **`single()`**: Mengembalikan satu baris data tunggal.
- **`lastInsertId()`**: Mengembalikan ID AUTO_INCREMENT record terakhir.

---

### 2. `Core\Session.php`
Pengelola sesi pengguna, flash message Bootstrap, dan otorisasi peran.

- **`init()`**: Menginisialisasi session jika belum aktif.
- **`requireLogin()`**: Memastikan pengguna telah terotentikasi; jika belum, dialihkan ke `/auth/login`.
- **`requireAdmin()`**: Memastikan level pengguna adalah Administrator (`level == 1`); jika bukan, dialihkan ke `/dashboard` dengan pesan error.
- **`authCheck($role = 'Administrator')`**: Helper fleksibel untuk pengecekan peran pengguna.
- **`setFlash($message, $type)`** & **`flash()`**: Mengatur dan mencetak notifikasi flash message Bootstrap 5.

---

## 🗄️ Model Classes

### 1. `ParkirModel.php`

- **`getManlessGates()`**:
  - Mengambil daftar mesin dispenser tiket masuk dari tabel `manless`.
- **`getPosKasirGates()`**:
  - Mengambil daftar gardu/pos kasir keluar dari tabel `pos_kasir`.
- **`getTarifLostList()`**:
  - Mengambil seluruh daftar tarif denda kehilangan tiket dari tabel `tarif_lost` beserta nama kendaraan.
- **`getTarifLostByKendaraan($namaKendaraan)`**:
  - Mengambil nominal denda tiket hilang untuk jenis kendaraan tertentu.
- **`catatMasuk($data)`**:
  - Menerima array `$data` (`nopol`, `jn_kendaraan`, `gate`, `gateout`).
  - Menggenerasi kode tiket unik `TRX...`.
  - Menyimpan transaksi baru berstatus `'B'` (Parkir Aktif).
- **`hitungTarif($waktuMasukStr, $jenisKendaraanNama)`**:
  - Menghitung durasi jam pembulatan ke atas.
  - Membaca tarif awal (`tarif_awal`) dan tarif jam berikutnya (`tarif_berjalan`).
  - Mengembalikan array durasi jam, string durasi, stempel keluar, dan total tarif.
- **`catatKeluar($idtrx, $calcData, $bayar, $gateout, $options = [])`**:
  - Menerima opsi pembayaran multi-channel (`cara_bayar`, `refbayar`) dan denda tiket hilang (`denda`, `nostnk`, `noktp`, `nohp`, `nama`).
  - Menghitung uang kembalian untuk pembayaran tunai atau menolkan kembalian untuk non-tunai.
  - Mengubah status transaksi menjadi `'S'` (Selesai/Lunas) dan mencatat waktu serta gate keluar.
- **`voidTransaction($idtrx, $ketbatal, $iduser)`**:
  - Mengubah status transaksi menjadi `'N'` (Dibatalkan/VOID).
  - Mencatat stempel waktu pembatalan (`waktubatal`), alasan pembatalan (`ketbatal`), dan ID pengguna pelaksana (`iduser_pembatalan`).
- **`getVoidTransactions($limit = 50)`**:
  - Mengambil daftar transaksi yang dibatalkan join dengan tabel `user` untuk identitas pelaksana pembatalan.
- **`getActiveTransactions($limit = 10)`** & **`getActiveVehicles($limit = 10)`**:
  - Mengambil daftar kendaraan aktif yang saat ini sedang berada di dalam area parkir.
- **`getRecentCompletedTransactions($limit = 10)`** & **`getRecentCompleted($limit = 10)`**:
  - Mengambil daftar transaksi parkir yang baru saja selesai keluar.
- **`getTransactionByIdTrx($idtrx)`** & **`getTransactionById($idtrx)`**:
  - Mengambil data baris transaksi tunggal berdasarkan nomor tiket transaksi.

---

### 2. `TarifModel.php`

- **`getTarifProgresif()`**:
  - Mengambil tarif jam pertama (`tarif_awal`) dan jam berikutnya (`tarif_berjalan`) per jenis kendaraan.
- **`updateTarifProgresif($kode, $awal, $berjalan)`**:
  - Memperbarui atau menyisipkan nilai tarif awal dan tarif berjalan untuk kode kendaraan terkait.
- **`getTarifFlat()`** & **`updateTarifFlat($id, $tarif)`**:
  - Mengambil dan memperbarui nominal skema tarif flat (`tarif_flat`).
- **`getTarifInap()`** & **`updateTarifInap($id, $tarif)`**:
  - Mengambil dan memperbarui denda inap harian (`tarif_inap`).
- **`getTarifLost()`** & **`updateTarifLost($id, $tarif)`**:
  - Mengambil dan memperbarui besaran denda tiket hilang (`tarif_lost`).
- **`getTarifMaksimal()`** & **`updateTarifMaksimal($id, $tarif)`**:
  - Mengambil dan memperbarui batas tarif maksimal per hari (`tarif_maksimal`).
- **`getDiskon()`**, **`addDiskon($kode, $nama, $nilai)`**, **`deleteDiskon($id)`**, **`toggleDiskon($id)`**:
  - Operasi CRUD dan aktivasi kupon voucher diskon tarif (`discount_tarif`).
- **`getHariLibur()`**, **`addHariLibur($tgl)`**, **`deleteHariLibur($id)`**:
  - Operasi pengelolaan kalender hari libur nasional (`hari_libur`).

---

### 3. `SetoranModel.php`

- **`getAllSetoran($limit = 100)`**:
  - Mengambil riwayat berita acara penutupan kasir dari `data_setoran` join nama supervisor penerima.
- **`getSetoranById($id)`**:
  - Mengambil 1 record header berita acara setoran.
- **`getDetailByNoSetoran($no_setoran)`**:
  - Mengambil rincian penerimaan per kategori kendaraan (Tunai, QRIS, Prepaid) dari `detail_setoran`.
- **`getShifts()`** & **`updateShift($id, $nama, $jama, $jamb)`**:
  - Mengambil dan memperbarui jadwal jam mulai dan selesai shift kerja (`jamshift`).
- **`calculateRevenue($tgl, $shift = null, $iduser = null)`**:
  - Mengagregasi seluruh transaksi selesai (`status = 'S'`) pada tanggal, shift, dan petugas tertentu.
  - Mengembalikan rincian per jenis kendaraan serta ringkasan total penerimaan tunai, QRIS, dan prepaid.
- **`createSetoran($header, $details)`**:
  - Membuat nomor berita acara unik (`ymdHisST`).
  - Menyimpan data header ke `data_setoran` dan seluruh baris rincian ke `detail_setoran`.

---

### 4. `LaporanModel.php`

- **`getFilteredReports($startDate, $endDate, $status, $jnKendaraan, $limit)`**:
  - Mengambil daftar transaksi parkir dengan filter rentang tanggal, status (`ALL`, `B`, `S`, `N`), dan jenis kendaraan.
- **`getSummaryStats($startDate, $endDate)`**:
  - Menghitung agregat total transaksi, total pendapatan (termasuk denda tiket hilang), total transaksi lunas, total transaksi aktif, dan total transaksi dibatalkan (VOID).
- **`getVoidReports($startDate, $endDate, $limit)`**:
  - Mengambil riwayat transaksi berstatus `'N'` (VOID) join nama user pembatal.

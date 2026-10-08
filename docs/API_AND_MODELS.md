# Referensi Teknis API & Model Class

Dokumen ini berisi dokumentasi teknis komponen Core dan Model pada aplikasi **Parkir PHP MVC**.

---

## ⚙️ Core Components

### 1. `Core\Database.php`
Menginisialisasi koneksi PDO ke MySQL dengan konfigurasi prepared statements.

- **`query($sql)`**: Menyiapkan kueri SQL prepared statement.
- **`bind($param, $value, $type = null)`**: Mengikat parameter kueri dengan tipe data aman (`PDO::PARAM_INT`, `PDO::PARAM_STR`, dll).
- **`execute()`**: Mengeksekusi statement.
- **`resultSet()`**: Mengembalikan seluruh baris data sebagai array asosiatif.
- **`single()`**: Mengembalikan 1 baris data tunggal.
- **`lastInsertId()`**: Mengembalikan ID AUTO_INCREMENT terakhir dari database.

---

### 2. `Core\Session.php`
Pengelola sesi pengguna, otentikasi hak akses, dan pesan kilat (flash messages).

- **`init()`**: Menginisialisasi `session_start()` aman.
- **`isLogged()`**: Mengembalikan status login pengguna (`true`/`false`).
- **`requireLogin()`**: Memastikan pengguna sudah login; mengarahkan ke `/auth/login` jika belum.
- **`requireAdmin()`**: Memastikan pengguna memiliki level Administrator (`level == 1`).
- **`setFlash($message, $type)`** & **`flash()`**: Menyimpan dan menampilkan alert pesan flash Bootstrap.

---

## 🗄️ Model Classes

### 1. `ParkirModel.php`

- **`catatMasuk($data)`**:
  - Menerima array `$data` (`nopol`, `jn_kendaraan`, `gate`, `gateout`).
  - Menggenerasi kode tiket unik `TRX...`.
  - Menyimpan transaksi baru berstatus `'B'` (Parkir Aktif).
- **`hitungTarif($waktuMasukStr, $jenisKendaraanNama)`**:
  - Menghitung perbedaan selisih waktu masuk dan waktu sekarang.
  - Membulatkan durasi parsial ke jam berikutnya.
  - Membaca tarif awal (`tarif_awal`) dan tarif berjalan (`tarif_berjalan`).
  - Mengembalikan array durasi jam, teks durasi, dan total tarif.
- **`catatKeluar($idtrx, $calcData, $bayar, $gateout)`**:
  - Menghitung kembalian uang bayar.
  - Mengubah status transaksi menjadi `'S'` (Selesai), menyet status bayar = `1`, dan menyimpan stempel waktu keluar serta pintu gate keluar.
- **`getActiveTransactions($limit = 50)`**:
  - Mengambil daftar kendaraan terparkir aktif dengan pembatasan memori `LIMIT :limit` untuk efisiensi RAM pada puluhan ribu dataset.

---

### 2. `KendaraanModel.php`

- **`getAllJenisKendaraan()`**:
  - Melakukan `LEFT JOIN` dari `jenis_kendaraan` ke `tarif_awal` dan `tarif_berjalan`.
- **`createJenis($data)`** & **`updateJenis($data)`**:
  - Memperbarui master jenis kendaraan sekaligus menyelaraskan tarif jam pertama dan tarif berjalan.
- **`incrementOccupancy($nama)`** & **`decrementOccupancy($nama)`**:
  - Memperbarui kolom `terpakai` secara otomatis saat kendaraan masuk/keluar.

---

### 3. `UserModel.php`

- **`getUserByUsername($username)`**: Mengambil data pengguna beserta deskripsi level.
- **`recordLoginHistory($userId, $level, $lokasi)`**: Menyimpan riwayat masuk ke tabel `history_login`.
- **`recordLogoutHistory($historyId)`**: Mengisi stempel waktu `w_logout` saat sesi diakhiri.
- **`getLoginHistory($limit = 100)`**: Mengambil daftar riwayat login pengguna.

---

### 4. `MemberModel.php`

- **`getAllMembers()`**: Mengambil daftar seluruh member.
- **`getMemberByNopol($nopol)`**: Memeriksa keberadaan member aktif berdasarkan nopol dan tanggal aktif saat ini (`tgl_mulai <= CURRENT_DATE <= tgl_akhir`).
- **`createMember($data)`**: Mendaftarkan member baru dengan ID unik `MBR-...`.

---

### 5. `LaporanModel.php`

- **`getFilteredReports($startDate, $endDate, $status, $jnKendaraan, $limit = 500)`**:
  - Mengambil rincian transaksi terfilter dengan proteksi batas memori aman `LIMIT 500`.
- **`getSummaryStats($startDate, $endDate)`**:
  - Menghitung statistik total transaksi, total pendapatan, total aktif, dan total selesai.

<?php

class ParkirController extends Controller {

    public function masuk() {
        Session::requireLogin();
        $kendaraanModel = $this->model('KendaraanModel');
        $parkirModel = $this->model('ParkirModel');
        
        $data = [
            'title' => 'Kendaraan Masuk (Cetak Tiket)',
            'jenis_list' => $kendaraanModel->getAllJenisKendaraan(),
            'manless_list' => $parkirModel->getManlessGates(),
            'pos_list' => $parkirModel->getPosKasirGates()
        ];
        
        $this->view('parkir/masuk', $data);
    }

    public function prosesMasuk() {
        Session::requireLogin();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nopol = trim($_POST['nopol'] ?? '');
            $jnKendaraan = trim($_POST['jn_kendaraan'] ?? '');
            $gate = trim($_POST['gate'] ?? 'MAN R4');
            $gateout = trim($_POST['gateout'] ?? 'POS R4');

            if (empty($nopol) || empty($jnKendaraan)) {
                Session::setFlash('Nomor Polisi dan Jenis Kendaraan wajib diisi.', 'danger');
                $this->redirect('/parkir/masuk');
            }

            $parkirModel = $this->model('ParkirModel');
            $kendaraanModel = $this->model('KendaraanModel');

            // Check if vehicle is already parked actively
            $existing = $parkirModel->getActiveTransactionByKeyword($nopol);
            if ($existing) {
                Session::setFlash('Kendaraan dengan Nopol ' . strtoupper($nopol) . ' sudah berada di dalam area parkir!', 'warning');
                $this->redirect('/parkir/masuk');
            }

            $idtrx = $parkirModel->catatMasuk([
                'nopol' => $nopol,
                'jn_kendaraan' => $jnKendaraan,
                'gate' => $gate,
                'gateout' => $gateout
            ]);

            if ($idtrx) {
                $kendaraanModel->incrementOccupancy($jnKendaraan);
                Session::setFlash('Kendaraan berhasil dicatat masuk dengan Kode Tiket: ' . $idtrx, 'success');
                $this->redirect('/parkir/tiket/' . $idtrx);
            } else {
                Session::setFlash('Gagal mencatat kendaraan masuk.', 'danger');
                $this->redirect('/parkir/masuk');
            }
        }
    }

    public function keluar($keyword = '') {
        Session::requireLogin();
        $parkirModel = $this->model('ParkirModel');
        $memberModel = $this->model('MemberModel');

        $trx = null;
        $calc = null;
        $memberInfo = null;

        if (!empty($_GET['keyword'])) {
            $keyword = trim($_GET['keyword']);
        }

        if (!empty($keyword)) {
            $trx = $parkirModel->getActiveTransactionByKeyword($keyword);
            if ($trx) {
                $calc = $parkirModel->hitungTarif($trx['waktuMasuk'], $trx['jn_kendaraan']);
                $calc['denda_lost_default'] = $parkirModel->getTarifLostByKendaraan($trx['jn_kendaraan']);
                
                // Check if vehicle owner is a registered active member
                $memberInfo = $memberModel->getMemberByNopol($trx['nopol']);
                if ($memberInfo) {
                    $calc['tarif'] = 0; // Free for members
                    $calc['is_member'] = true;
                    $calc['member_name'] = $memberInfo['nama'];
                }
            } else {
                Session::setFlash('Transaksi parkir aktif tidak ditemukan untuk tiket/nopol: ' . htmlspecialchars($keyword), 'danger');
            }
        }

        $data = [
            'title' => 'Kendaraan Keluar (Pembayaran Parkir)',
            'keyword' => $keyword,
            'trx' => $trx,
            'calc' => $calc,
            'pos_list' => $parkirModel->getPosKasirGates(),
            'active_list' => $parkirModel->getActiveVehicles(15)
        ];

        $this->view('parkir/keluar', $data);
    }

    public function prosesKeluar() {
        Session::requireLogin();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $idtrx = trim($_POST['idtrx'] ?? '');
            $bayar = (int)($_POST['bayar'] ?? 0);
            $gateout = trim($_POST['gateout'] ?? 'POS R4');
            $cara_bayar = trim($_POST['cara_bayar'] ?? 'Tunai');
            $refbayar = trim($_POST['refbayar'] ?? '');

            $is_lost = !empty($_POST['is_lost']);
            $denda = $is_lost ? (int)($_POST['denda'] ?? 0) : 0;
            $nostnk = trim($_POST['nostnk'] ?? '');
            $noktp = trim($_POST['noktp'] ?? '');
            $nohp = trim($_POST['nohp'] ?? '');
            $nama = trim($_POST['nama_pemilik'] ?? '');

            if (empty($idtrx)) {
                Session::setFlash('ID Transaksi tidak valid.', 'danger');
                $this->redirect('/parkir/keluar');
            }

            $parkirModel = $this->model('ParkirModel');
            $kendaraanModel = $this->model('KendaraanModel');
            $memberModel = $this->model('MemberModel');

            $trx = $parkirModel->getTransactionById($idtrx);
            if (!$trx || $trx['status'] !== 'B') {
                Session::setFlash('Status transaksi sudah selesai atau tidak ditemukan.', 'danger');
                $this->redirect('/parkir/keluar');
            }

            $calc = $parkirModel->hitungTarif($trx['waktuMasuk'], $trx['jn_kendaraan']);
            
            $nopol_update = strtoupper(trim($_POST['nopol_update'] ?? ''));
            $effectiveNopol = !empty($nopol_update) ? $nopol_update : ($trx['nopol'] ?? '');

            // Check member
            if (!empty($effectiveNopol)) {
                $memberInfo = $memberModel->getMemberByNopol($effectiveNopol);
                if ($memberInfo) {
                    $calc['tarif'] = 0;
                }
            }

            $totalTagihan = $calc['tarif'] + $denda;

            if ($cara_bayar === 'Tunai' && $bayar < $totalTagihan) {
                Session::setFlash('Uang tunai kurang! Total tagihan: Rp ' . number_format($totalTagihan), 'danger');
                $this->redirect('/parkir/keluar?keyword=' . $idtrx);
            }

            $options = [
                'nopol' => $effectiveNopol,
                'cara_bayar' => $cara_bayar,
                'refbayar' => $refbayar,
                'denda' => $denda,
                'nostnk' => $nostnk,
                'noktp' => $noktp,
                'nohp' => $nohp,
                'nama' => $nama
            ];

            $success = $parkirModel->catatKeluar($idtrx, $calc, $bayar, $gateout, $options);

            if ($success) {
                $kendaraanModel->decrementOccupancy($trx['jn_kendaraan']);
                $kembalian = ($cara_bayar === 'Tunai') ? ($bayar - $totalTagihan) : 0;
                Session::setFlash('Pembayaran berhasil (' . $cara_bayar . ')! Kembalian: Rp ' . number_format($kembalian), 'success');
                $this->redirect('/parkir/struk/' . $idtrx);
            } else {
                Session::setFlash('Gagal memproses transaksi keluar.', 'danger');
                $this->redirect('/parkir/keluar?keyword=' . $idtrx);
            }
        }
    }

    public function void() {
        Session::requireLogin();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $idtrx = trim($_POST['idtrx'] ?? '');
            $ketbatal = trim($_POST['ketbatal'] ?? '');
            $user = Session::get('user');
            $iduser = $user['id'] ?? 1;

            if (!empty($idtrx) && !empty($ketbatal)) {
                $parkirModel = $this->model('ParkirModel');
                $trx = $parkirModel->getTransactionById($idtrx);
                
                if ($trx && $trx['status'] === 'B') {
                    $kendaraanModel = $this->model('KendaraanModel');
                    $kendaraanModel->decrementOccupancy($trx['jn_kendaraan']);
                }

                $parkirModel->voidTransaction($idtrx, $ketbatal, $iduser);
                Session::setFlash('Transaksi ' . $idtrx . ' berhasil DIBATALKAN (VOID)!', 'warning');
            } else {
                Session::setFlash('ID Transaksi dan alasan pembatalan wajib diisi.', 'danger');
            }
        }
        $this->redirect($_SERVER['HTTP_REFERER'] ?? '/parkir/keluar');
    }

    public function tiket($idtrx = '') {
        Session::requireLogin();
        $parkirModel = $this->model('ParkirModel');
        $trx = $parkirModel->getTransactionById($idtrx);

        if (!$trx) {
            Session::setFlash('Tiket parkir tidak ditemukan.', 'danger');
            $this->redirect('/parkir/masuk');
        }

        $data = [
            'title' => 'Cetak Tiket Parkir - ' . $idtrx,
            'trx' => $trx
        ];

        $this->view('parkir/tiket', $data);
    }

    public function struk($idtrx = '') {
        Session::requireLogin();
        $parkirModel = $this->model('ParkirModel');
        $trx = $parkirModel->getTransactionById($idtrx);

        if (!$trx) {
            Session::setFlash('Struk pembayaran tidak ditemukan.', 'danger');
            $this->redirect('/parkir/keluar');
        }

        $data = [
            'title' => 'Struk Pembayaran Parkir - ' . $idtrx,
            'trx' => $trx
        ];

        $this->view('parkir/struk', $data);
    }
}

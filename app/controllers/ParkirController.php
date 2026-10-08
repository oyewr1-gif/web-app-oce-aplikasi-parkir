<?php

class ParkirController extends Controller {

    public function masuk() {
        Session::requireLogin();
        $kendaraanModel = $this->model('KendaraanModel');
        
        $data = [
            'title' => 'Kendaraan Masuk (Cetak Tiket)',
            'jenis_list' => $kendaraanModel->getAllJenisKendaraan()
        ];
        
        $this->view('parkir/masuk', $data);
    }

    public function prosesMasuk() {
        Session::requireLogin();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nopol = trim($_POST['nopol'] ?? '');
            $jnKendaraan = trim($_POST['jn_kendaraan'] ?? '');
            $gate = trim($_POST['gate'] ?? 'GATE-IN-01');
            $gateout = trim($_POST['gateout'] ?? 'GATE-OUT-01');

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
                // Check if vehicle owner is a registered active member
                $memberInfo = $memberModel->getMemberByNopol($trx['nopol']);
                if ($memberInfo) {
                    $calc['tarif'] = 0; // Free for members
                    $calc['is_member'] = true;
                    $calc['member_name'] = $memberInfo['nama'];
                }
            } else {
                Session::setFlash('Transaksi parkir aktif tidak ditemukan untuk nomor tiket/nopol: ' . htmlspecialchars($keyword), 'danger');
            }
        }

        $data = [
            'title' => 'Kendaraan Keluar (Pembayaran Parkir)',
            'keyword' => $keyword,
            'trx' => $trx,
            'calc' => $calc,
            'active_list' => $parkirModel->getActiveTransactions()
        ];

        $this->view('parkir/keluar', $data);
    }

    public function prosesKeluar() {
        Session::requireLogin();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $idtrx = trim($_POST['idtrx'] ?? '');
            $bayar = (int)($_POST['bayar'] ?? 0);
            $gateout = trim($_POST['gateout'] ?? 'GATE-OUT-01');

            if (empty($idtrx)) {
                Session::setFlash('ID Transaksi tidak valid.', 'danger');
                $this->redirect('/parkir/keluar');
            }

            $parkirModel = $this->model('ParkirModel');
            $kendaraanModel = $this->model('KendaraanModel');
            $memberModel = $this->model('MemberModel');

            $trx = $parkirModel->getTransactionByIdTrx($idtrx);
            if (!$trx || $trx['status'] !== 'B') {
                Session::setFlash('Status transaksi sudah selesai atau tidak ditemukan.', 'danger');
                $this->redirect('/parkir/keluar');
            }

            $calc = $parkirModel->hitungTarif($trx['waktuMasuk'], $trx['jn_kendaraan']);
            
            // Check member
            $memberInfo = $memberModel->getMemberByNopol($trx['nopol']);
            if ($memberInfo) {
                $calc['tarif'] = 0;
            }

            if ($bayar < $calc['tarif']) {
                Session::setFlash('Uang pembayaran kurang! Total tarif: Rp ' . number_format($calc['tarif']), 'danger');
                $this->redirect('/parkir/keluar?keyword=' . $idtrx);
            }

            $success = $parkirModel->catatKeluar($idtrx, $calc, $bayar, $gateout);

            if ($success) {
                $kendaraanModel->decrementOccupancy($trx['jn_kendaraan']);
                Session::setFlash('Pembayaran berhasil! Kembalian: Rp ' . number_format($bayar - $calc['tarif']), 'success');
                $this->redirect('/parkir/struk/' . $idtrx);
            } else {
                Session::setFlash('Gagal memproses transaksi keluar.', 'danger');
                $this->redirect('/parkir/keluar?keyword=' . $idtrx);
            }
        }
    }

    public function tiket($idtrx = '') {
        Session::requireLogin();
        $parkirModel = $this->model('ParkirModel');
        $trx = $parkirModel->getTransactionByIdTrx($idtrx);

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
        $trx = $parkirModel->getTransactionByIdTrx($idtrx);

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

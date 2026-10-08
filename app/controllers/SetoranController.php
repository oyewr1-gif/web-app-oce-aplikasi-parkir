<?php

class SetoranController extends Controller {
    private $setoranModel;

    public function __construct() {
        Session::requireAdmin();
        $this->setoranModel = $this->model('SetoranModel');
    }

    public function index() {
        $data = [
            'title' => 'Rekap Setoran Kasir & Shift',
            'setoran_list' => $this->setoranModel->getAllSetoran(100)
        ];
        $this->view('setoran/index', $data);
    }

    public function create() {
        $tgl = $_GET['tgl'] ?? date('Y-m-d');
        $shift = $_GET['shift'] ?? '';
        $iduser = $_GET['iduser'] ?? '';
        $pintu = $_GET['pintu'] ?? '';

        $calc = $this->setoranModel->calculateRevenue($tgl, $shift, $iduser);

        $data = [
            'title' => 'Input Tutup Setoran Kasir',
            'tgl' => $tgl,
            'selected_shift' => $shift,
            'selected_user' => $iduser,
            'selected_pintu' => $pintu,
            'shifts' => $this->setoranModel->getShifts(),
            'pos_kasir' => $this->setoranModel->getPosKasir(),
            'kasir_list' => $this->setoranModel->getKasirList(),
            'calc' => $calc
        ];
        $this->view('setoran/create', $data);
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $tgl = $_POST['tglsetoran'] ?? date('Y-m-d');
            $shift = $_POST['shift'] ?? 'Pagi';
            $pintu = $_POST['pintu'] ?? 'POS R4';
            $id_kasir = (int)($_POST['iduser_kasir'] ?? 0);
            $nama_kasir = trim($_POST['nama_kasir'] ?? 'Kasir');
            $jumuang = (int)($_POST['jumuang'] ?? 0);
            $fisik = (int)($_POST['fisik'] ?? 0);
            $selisih = $fisik - $jumuang;
            $currentUser = Session::get('user');

            $header = [
                'iduser_penerima' => $currentUser['id'] ?? 1,
                'iduser_kasir' => $id_kasir,
                'nama_kasir' => $nama_kasir,
                'shift' => $shift,
                'pintu' => $pintu,
                'tglsetoran' => $tgl,
                'login' => $tgl . ' 07:00:00',
                'logout' => $tgl . ' ' . date('H:i:s'),
                'jumuang' => $jumuang,
                'fisik' => $fisik,
                'jummasalah' => ($selisih != 0) ? 1 : 0,
                'jumuangmasalah' => $selisih
            ];

            // Re-calculate details breakdown per vehicle
            $calc = $this->setoranModel->calculateRevenue($tgl, $shift, $id_kasir);
            $details = [];

            foreach ($calc['items'] as $item) {
                $details[] = [
                    'nama_kendaraan' => $item['jn_kendaraan'],
                    'tunai' => (int)$item['tunai'],
                    'qris' => (int)$item['qris'],
                    'prepaid' => (int)$item['prepaid'],
                    'total' => (int)$item['total']
                ];
            }

            // Total summary row
            $details[] = [
                'nama_kendaraan' => 'Total',
                'tunai' => $calc['total_tunai'],
                'qris' => $calc['total_qris'],
                'prepaid' => $calc['total_prepaid'],
                'total' => $calc['grand_total']
            ];

            $setId = $this->setoranModel->createSetoran($header, $details);

            Session::setFlash('success', 'Setoran kasir berhasil dicatat dan diverifikasi!');
            $this->redirect('/setoran/detail/' . $setId);
        }
        $this->redirect('/setoran');
    }

    public function detail($id = null) {
        if (!$id) {
            $this->redirect('/setoran');
        }
        $setoran = $this->setoranModel->getSetoranById((int)$id);
        if (!$setoran) {
            Session::setFlash('danger', 'Data setoran tidak ditemukan!');
            $this->redirect('/setoran');
        }

        $details = $this->setoranModel->getDetailByNoSetoran($setoran['no_setoran']);

        $data = [
            'title' => 'Detail Setoran - ' . $setoran['no_setoran'],
            'setoran' => $setoran,
            'details' => $details
        ];
        $this->view('setoran/detail', $data);
    }

    public function cetak($id = null) {
        if (!$id) {
            $this->redirect('/setoran');
        }
        $setoran = $this->setoranModel->getSetoranById((int)$id);
        if (!$setoran) {
            die("Data setoran tidak ditemukan");
        }
        $details = $this->setoranModel->getDetailByNoSetoran($setoran['no_setoran']);

        $data = [
            'title' => 'Cetak Berita Acara Setoran - ' . $setoran['no_setoran'],
            'setoran' => $setoran,
            'details' => $details
        ];
        $this->view('setoran/cetak', $data);
    }

    public function shift() {
        $data = [
            'title' => 'Master Jam Shift Petugas',
            'shifts' => $this->setoranModel->getShifts()
        ];
        $this->view('setoran/shift', $data);
    }

    public function updateShift() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)($_POST['id'] ?? 0);
            $nama = trim($_POST['nama'] ?? '');
            $jama = trim($_POST['jama'] ?? '');
            $jamb = trim($_POST['jamb'] ?? '');

            if ($id > 0 && !empty($nama)) {
                $this->setoranModel->updateShift($id, $nama, $jama, $jamb);
                Session::setFlash('success', 'Konfigurasi jam shift berhasil diperbarui!');
            }
        }
        $this->redirect('/setoran/shift');
    }
}

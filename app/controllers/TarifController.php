<?php

class TarifController extends Controller {
    private $tarifModel;

    public function __construct() {
        Session::requireAdmin();
        $this->tarifModel = $this->model('TarifModel');
    }

    public function index() {
        $activeTab = $_GET['tab'] ?? 'progresif';
        $data = [
            'title' => 'Master Tarif & Diskon',
            'active_tab' => $activeTab,
            'progresif' => $this->tarifModel->getTarifProgresif(),
            'flat' => $this->tarifModel->getTarifFlat(),
            'inap' => $this->tarifModel->getTarifInap(),
            'lost' => $this->tarifModel->getTarifLost(),
            'maksimal' => $this->tarifModel->getTarifMaksimal(),
            'diskon' => $this->tarifModel->getDiskon(),
            'hari_libur' => $this->tarifModel->getHariLibur()
        ];
        $this->view('tarif/index', $data);
    }

    public function updateProgresif() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $kode = trim($_POST['kode'] ?? '');
            $awal = (int)($_POST['tarif_awal'] ?? 0);
            $berjalan = (int)($_POST['tarif_berjalan'] ?? 0);

            if (!empty($kode)) {
                $this->tarifModel->updateTarifProgresif($kode, $awal, $berjalan);
                Session::setFlash('success', 'Tarif progresif jam pertama & jam berikutnya berhasil diperbarui!');
            }
        }
        $this->redirect('/tarif?tab=progresif');
    }

    public function updateFlat() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)($_POST['id'] ?? 0);
            $tarif = (int)($_POST['tarif'] ?? 0);

            if ($id > 0) {
                $this->tarifModel->updateTarifFlat($id, $tarif);
                Session::setFlash('success', 'Tarif flat berhasil diperbarui!');
            }
        }
        $this->redirect('/tarif?tab=flat');
    }

    public function updateInap() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)($_POST['id'] ?? 0);
            $tarif = (int)($_POST['tarif'] ?? 0);

            if ($id > 0) {
                $this->tarifModel->updateTarifInap($id, $tarif);
                Session::setFlash('success', 'Tarif denda inap harian berhasil diperbarui!');
            }
        }
        $this->redirect('/tarif?tab=inap');
    }

    public function updateLost() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)($_POST['id'] ?? 0);
            $tarif = (int)($_POST['tarif'] ?? 0);

            if ($id > 0) {
                $this->tarifModel->updateTarifLost($id, $tarif);
                Session::setFlash('success', 'Tarif denda tiket hilang berhasil diperbarui!');
            }
        }
        $this->redirect('/tarif?tab=lost');
    }

    public function updateMaksimal() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)($_POST['id'] ?? 0);
            $tarif = (int)($_POST['tarif'] ?? 0);

            if ($id > 0) {
                $this->tarifModel->updateTarifMaksimal($id, $tarif);
                Session::setFlash('success', 'Batas tarif maksimal per hari berhasil diperbarui!');
            }
        }
        $this->redirect('/tarif?tab=maksimal');
    }

    public function addDiskon() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $kode = strtoupper(trim($_POST['kode_trx'] ?? ''));
            $nama = trim($_POST['nama'] ?? '');
            $nilai = (int)($_POST['nilai'] ?? 0);

            if (!empty($kode) && !empty($nama)) {
                $this->tarifModel->addDiskon($kode, $nama, $nilai);
                Session::setFlash('success', 'Voucher / Diskon baru berhasil ditambahkan!');
            } else {
                Session::setFlash('danger', 'Kode dan nama diskon wajib diisi!');
            }
        }
        $this->redirect('/tarif?tab=diskon');
    }

    public function deleteDiskon($id = null) {
        if ($id) {
            $this->tarifModel->deleteDiskon((int)$id);
            Session::setFlash('success', 'Data diskon berhasil dihapus!');
        }
        $this->redirect('/tarif?tab=diskon');
    }

    public function toggleDiskon($id = null) {
        if ($id) {
            $this->tarifModel->toggleDiskon((int)$id);
            Session::setFlash('success', 'Status voucher/diskon berhasil diubah!');
        }
        $this->redirect('/tarif?tab=diskon');
    }

    public function addLibur() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $tgl = trim($_POST['tgl'] ?? '');
            if (!empty($tgl)) {
                $this->tarifModel->addHariLibur($tgl);
                Session::setFlash('success', 'Tanggal hari libur berhasil ditambahkan!');
            }
        }
        $this->redirect('/tarif?tab=libur');
    }

    public function deleteLibur($id = null) {
        if ($id) {
            $this->tarifModel->deleteHariLibur((int)$id);
            Session::setFlash('success', 'Tanggal hari libur berhasil dihapus!');
        }
        $this->redirect('/tarif?tab=libur');
    }
}

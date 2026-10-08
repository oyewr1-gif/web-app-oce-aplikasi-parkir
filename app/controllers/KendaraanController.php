<?php

class KendaraanController extends Controller {

    public function index() {
        Session::requireAdmin();
        $kendaraanModel = $this->model('KendaraanModel');

        $data = [
            'title' => 'Kelola Jenis Kendaraan & Tarif',
            'list' => $kendaraanModel->getAllJenisKendaraan()
        ];

        $this->view('kendaraan/index', $data);
    }

    public function store() {
        Session::requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'jn_kendaraan' => trim($_POST['jn_kendaraan'] ?? ''),
                'nama' => trim($_POST['nama'] ?? ''),
                'tarif_pertama' => (int)($_POST['tarif_pertama'] ?? 0),
                'tarif_berikutnya' => (int)($_POST['tarif_berikutnya'] ?? 0),
                'kapasitas' => (int)($_POST['kapasitas'] ?? 100)
            ];

            if (empty($data['jn_kendaraan']) || empty($data['nama'])) {
                Session::setFlash('Kode dan Nama Kendaraan wajib diisi.', 'danger');
                $this->redirect('/kendaraan');
            }

            $kendaraanModel = $this->model('KendaraanModel');
            if ($kendaraanModel->createJenis($data)) {
                Session::setFlash('Jenis kendaraan baru berhasil ditambahkan.', 'success');
            } else {
                Session::setFlash('Gagal menambahkan jenis kendaraan.', 'danger');
            }
            $this->redirect('/kendaraan');
        }
    }

    public function update() {
        Session::requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'id' => (int)($_POST['id'] ?? 0),
                'jn_kendaraan' => trim($_POST['jn_kendaraan'] ?? ''),
                'nama' => trim($_POST['nama'] ?? ''),
                'tarif_pertama' => (int)($_POST['tarif_pertama'] ?? 0),
                'tarif_berikutnya' => (int)($_POST['tarif_berikutnya'] ?? 0),
                'kapasitas' => (int)($_POST['kapasitas'] ?? 100)
            ];

            $kendaraanModel = $this->model('KendaraanModel');
            if ($kendaraanModel->updateJenis($data)) {
                Session::setFlash('Data jenis kendaraan berhasil diperbarui.', 'success');
            } else {
                Session::setFlash('Gagal memperbarui data jenis kendaraan.', 'danger');
            }
            $this->redirect('/kendaraan');
        }
    }

    public function delete($id = 0) {
        Session::requireAdmin();
        $kendaraanModel = $this->model('KendaraanModel');

        if ($kendaraanModel->deleteJenis($id)) {
            Session::setFlash('Jenis kendaraan berhasil dihapus.', 'success');
        } else {
            Session::setFlash('Gagal menghapus jenis kendaraan.', 'danger');
        }
        $this->redirect('/kendaraan');
    }
}

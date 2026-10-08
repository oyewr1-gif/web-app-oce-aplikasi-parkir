<?php

class MemberController extends Controller {

    public function index() {
        Session::requireLogin();
        $memberModel = $this->model('MemberModel');
        $kendaraanModel = $this->model('KendaraanModel');

        $data = [
            'title' => 'Kelola Member Parkir Langganan',
            'members' => $memberModel->getAllMembers(),
            'jenis_list' => $kendaraanModel->getAllJenisKendaraan()
        ];

        $this->view('member/index', $data);
    }

    public function store() {
        Session::requireLogin();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'nama' => trim($_POST['nama'] ?? ''),
                'nopol' => trim($_POST['nopol'] ?? ''),
                'jn_kendaraan' => trim($_POST['jn_kendaraan'] ?? ''),
                'tgl_mulai' => trim($_POST['tgl_mulai'] ?? date('Y-m-d')),
                'tgl_akhir' => trim($_POST['tgl_akhir'] ?? date('Y-m-d', strtotime('+1 year'))),
                'no_tlp' => trim($_POST['no_tlp'] ?? ''),
                'alamat' => trim($_POST['alamat'] ?? '')
            ];

            if (empty($data['nama']) || empty($data['nopol'])) {
                Session::setFlash('Nama dan Nomor Polisi Wajib diisi.', 'danger');
                $this->redirect('/member');
            }

            $memberModel = $this->model('MemberModel');
            if ($memberModel->createMember($data)) {
                Session::setFlash('Member parkir baru berhasil didaftarkan.', 'success');
            } else {
                Session::setFlash('Gagal mecantumkan member parkir.', 'danger');
            }
            $this->redirect('/member');
        }
    }

    public function update() {
        Session::requireLogin();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'id' => (int)($_POST['id'] ?? 0),
                'nama' => trim($_POST['nama'] ?? ''),
                'nopol' => trim($_POST['nopol'] ?? ''),
                'jn_kendaraan' => trim($_POST['jn_kendaraan'] ?? ''),
                'tgl_mulai' => trim($_POST['tgl_mulai'] ?? date('Y-m-d')),
                'tgl_akhir' => trim($_POST['tgl_akhir'] ?? date('Y-m-d')),
                'no_tlp' => trim($_POST['no_tlp'] ?? ''),
                'alamat' => trim($_POST['alamat'] ?? ''),
                'status' => (int)($_POST['status'] ?? 1)
            ];

            $memberModel = $this->model('MemberModel');
            if ($memberModel->updateMember($data)) {
                Session::setFlash('Data member berhasil diperbarui.', 'success');
            } else {
                Session::setFlash('Gagal memperbarui data member.', 'danger');
            }
            $this->redirect('/member');
        }
    }

    public function delete($id = 0) {
        Session::requireLogin();
        $memberModel = $this->model('MemberModel');

        if ($memberModel->deleteMember($id)) {
            Session::setFlash('Data member berhasil dihapus.', 'success');
        } else {
            Session::setFlash('Gagal menghapus data member.', 'danger');
        }
        $this->redirect('/member');
    }
}

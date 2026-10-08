<?php

class UserController extends Controller {

    public function index() {
        Session::requireAdmin();
        $userModel = $this->model('UserModel');

        $data = [
            'title' => 'Kelola Data Pengguna & Histori Login',
            'users' => $userModel->getAllUsers(),
            'levels' => $userModel->getLevels(),
            'history' => $userModel->getLoginHistory(50)
        ];

        $this->view('user/index', $data);
    }

    public function store() {
        Session::requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'username' => trim($_POST['username'] ?? ''),
                'password' => trim($_POST['password'] ?? ''),
                'nama' => trim($_POST['nama'] ?? ''),
                'jabatan' => trim($_POST['jabatan'] ?? 'Petugas'),
                'level' => (int)($_POST['level'] ?? 2),
                'shift' => trim($_POST['shift'] ?? 'Shift 1'),
                'email' => trim($_POST['email'] ?? ''),
                'notlp' => trim($_POST['notlp'] ?? ''),
                'alamat' => trim($_POST['alamat'] ?? '')
            ];

            if (empty($data['username']) || empty($data['password']) || empty($data['nama'])) {
                Session::setFlash('Username, Password, dan Nama wajib diisi.', 'danger');
                $this->redirect('/user');
            }

            $userModel = $this->model('UserModel');
            if ($userModel->getUserByUsername($data['username'])) {
                Session::setFlash('Username sudah digunakan. Silakan pilih username lain.', 'danger');
                $this->redirect('/user');
            }

            if ($userModel->createUser($data)) {
                Session::setFlash('Pengguna baru berhasil ditambahkan.', 'success');
            } else {
                Session::setFlash('Gagal menambahkan pengguna.', 'danger');
            }
            $this->redirect('/user');
        }
    }

    public function update() {
        Session::requireAdmin();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'id' => (int)($_POST['id'] ?? 0),
                'username' => trim($_POST['username'] ?? ''),
                'password' => trim($_POST['password'] ?? ''),
                'nama' => trim($_POST['nama'] ?? ''),
                'jabatan' => trim($_POST['jabatan'] ?? 'Petugas'),
                'level' => (int)($_POST['level'] ?? 2),
                'shift' => trim($_POST['shift'] ?? 'Shift 1'),
                'email' => trim($_POST['email'] ?? ''),
                'notlp' => trim($_POST['notlp'] ?? ''),
                'alamat' => trim($_POST['alamat'] ?? '')
            ];

            $userModel = $this->model('UserModel');
            if ($userModel->updateUser($data)) {
                Session::setFlash('Data pengguna berhasil diperbarui.', 'success');
            } else {
                Session::setFlash('Gagal memperbarui data pengguna.', 'danger');
            }
            $this->redirect('/user');
        }
    }

    public function delete($id = 0) {
        Session::requireAdmin();

        if ($id == Session::get('user_id')) {
            Session::setFlash('Anda tidak dapat menghapus akun Anda sendiri!', 'warning');
            $this->redirect('/user');
        }

        $userModel = $this->model('UserModel');
        if ($userModel->deleteUser($id)) {
            Session::setFlash('Pengguna berhasil dihapus.', 'success');
        } else {
            Session::setFlash('Gagal menghapus pengguna.', 'danger');
        }
        $this->redirect('/user');
    }
}

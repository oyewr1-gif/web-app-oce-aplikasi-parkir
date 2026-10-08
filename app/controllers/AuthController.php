<?php

class AuthController extends Controller {

    public function login() {
        if (Session::isLogged()) {
            $this->redirect('/dashboard');
        }
        $this->view('auth/login');
    }

    public function processLogin() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = trim($_POST['username'] ?? '');
            $password = trim($_POST['password'] ?? '');

            if (empty($username) || empty($password)) {
                Session::setFlash('Username dan Password wajib diisi.', 'danger');
                $this->redirect('/auth/login');
            }

            $userModel = $this->model('UserModel');
            $user = $userModel->getUserByUsername($username);

            if ($user) {
                // Verify password (supports password_verify and fallback for plain/MD5 if needed)
                $isValidPassword = password_verify($password, $user['password']) || ($password === $user['password']) || (md5($password) === $user['password']);

                if ($isValidPassword) {
                    Session::set('user_id', $user['id']);
                    Session::set('user_name', $user['nama']);
                    Session::set('user_username', $user['username']);
                    Session::set('user_level', $user['level']);
                    Session::set('user_level_nama', $user['level_nama']);
                    Session::set('user_shift', $user['shift'] ?? 'Shift 1');

                    // Record entry in history_login table
                    $historyId = $userModel->recordLoginHistory($user['id'], $user['level'], $user['tempat_kerja'] ?? 'Pos 1');
                    if ($historyId) {
                        Session::set('login_history_id', $historyId);
                    }

                    Session::setFlash('Selamat datang, ' . $user['nama'] . '!', 'success');
                    $this->redirect('/dashboard');
                } else {
                    Session::setFlash('Password yang Anda masukkan salah.', 'danger');
                    $this->redirect('/auth/login');
                }
            } else {
                Session::setFlash('Username tidak ditemukan.', 'danger');
                $this->redirect('/auth/login');
            }
        } else {
            $this->redirect('/auth/login');
        }
    }

    public function logout() {
        $historyId = Session::get('login_history_id');
        if ($historyId) {
            $userModel = $this->model('UserModel');
            $userModel->recordLogoutHistory($historyId);
        }

        Session::destroy();
        Session::init();
        Session::setFlash('Anda telah berhasil logout.', 'info');
        $this->redirect('/auth/login');
    }
}

<?php

class Session {
    public static function init() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function set($key, $value) {
        self::init();
        $_SESSION[$key] = $value;
    }

    public static function get($key) {
        self::init();
        return isset($_SESSION[$key]) ? $_SESSION[$key] : null;
    }

    public static function unsetKey($key) {
        self::init();
        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }
    }

    public static function destroy() {
        self::init();
        session_unset();
        session_destroy();
    }

    // Flash message helper
    public static function setFlash($message, $type = 'success') {
        self::init();
        $_SESSION['flash'] = [
            'message' => $message,
            'type' => $type
        ];
    }

    public static function flash() {
        self::init();
        if (isset($_SESSION['flash'])) {
            $flash = $_SESSION['flash'];
            unset($_SESSION['flash']);
            echo '<div class="alert alert-' . htmlspecialchars($flash['type']) . ' alert-dismissible fade show" role="alert">
                    ' . htmlspecialchars($flash['message']) . '
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                  </div>';
        }
    }

    // Auth verification helpers
    public static function isLogged() {
        self::init();
        return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
    }

    public static function requireLogin() {
        if (!self::isLogged()) {
            self::setFlash('Silakan login terlebih dahulu.', 'warning');
            $redirectUrl = defined('BASE_URL') ? rtrim(BASE_URL, '/') . '/auth/login' : '/auth/login';
            header('Location: ' . $redirectUrl);
            exit;
        }
    }

    public static function requireAdmin() {
        self::requireLogin();
        if (self::get('user_level') != 1) { // 1 = Admin
            self::setFlash('Akses ditolak. Halaman ini hanya untuk Administrator.', 'danger');
            $redirectUrl = defined('BASE_URL') ? rtrim(BASE_URL, '/') . '/dashboard' : '/dashboard';
            header('Location: ' . $redirectUrl);
            exit;
        }
    }
}

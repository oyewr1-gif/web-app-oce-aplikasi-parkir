<?php

class UserModel {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getUserByUsername($username) {
        $this->db->query("SELECT u.*, l.nama AS level_nama FROM user u LEFT JOIN level_user l ON u.level = l.id WHERE u.username = :username");
        $this->db->bind(':username', $username);
        return $this->db->single();
    }

    public function getUserById($id) {
        $this->db->query("SELECT u.*, l.nama AS level_nama FROM user u LEFT JOIN level_user l ON u.level = l.id WHERE u.id = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function getAllUsers() {
        $this->db->query("SELECT u.*, l.nama AS level_nama FROM user u LEFT JOIN level_user l ON u.level = l.id ORDER BY u.id DESC");
        return $this->db->resultSet();
    }

    public function getLevels() {
        $this->db->query("SELECT * FROM level_user ORDER BY id ASC");
        return $this->db->resultSet();
    }

    public function createUser($data) {
        $hashPassword = password_hash($data['password'], PASSWORD_BCRYPT);
        $this->db->query("INSERT INTO user (username, password, nama, jabatan, level, shift, email, notlp, alamat) 
                          VALUES (:username, :password, :nama, :jabatan, :level, :shift, :email, :notlp, :alamat)");
        $this->db->bind(':username', $data['username']);
        $this->db->bind(':password', $hashPassword);
        $this->db->bind(':nama', $data['nama']);
        $this->db->bind(':jabatan', $data['jabatan']);
        $this->db->bind(':level', $data['level']);
        $this->db->bind(':shift', $data['shift']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':notlp', $data['notlp']);
        $this->db->bind(':alamat', $data['alamat']);
        return $this->db->execute();
    }

    public function updateUser($data) {
        if (!empty($data['password'])) {
            $hashPassword = password_hash($data['password'], PASSWORD_BCRYPT);
            $sql = "UPDATE user SET username = :username, password = :password, nama = :nama, jabatan = :jabatan, 
                    level = :level, shift = :shift, email = :email, notlp = :notlp, alamat = :alamat WHERE id = :id";
            $this->db->query($sql);
            $this->db->bind(':password', $hashPassword);
        } else {
            $sql = "UPDATE user SET username = :username, nama = :nama, jabatan = :jabatan, 
                    level = :level, shift = :shift, email = :email, notlp = :notlp, alamat = :alamat WHERE id = :id";
            $this->db->query($sql);
        }

        $this->db->bind(':id', $data['id']);
        $this->db->bind(':username', $data['username']);
        $this->db->bind(':nama', $data['nama']);
        $this->db->bind(':jabatan', $data['jabatan']);
        $this->db->bind(':level', $data['level']);
        $this->db->bind(':shift', $data['shift']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':notlp', $data['notlp']);
        $this->db->bind(':alamat', $data['alamat']);
        return $this->db->execute();
    }

    public function deleteUser($id) {
        $this->db->query("DELETE FROM user WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    // Record login entry in history_login table
    public function recordLoginHistory($userId, $level, $lokasi = 'Pos 1') {
        $wLogin = date('Y-m-d H:i:s');
        $tgl = date('Y-m-d');

        $this->db->query("INSERT INTO history_login (iduser, w_login, tgl, level, lokasi) 
                          VALUES (:iduser, :w_login, :tgl, :level, :lokasi)");
        $this->db->bind(':iduser', $userId);
        $this->db->bind(':w_login', $wLogin);
        $this->db->bind(':tgl', $tgl);
        $this->db->bind(':level', $level);
        $this->db->bind(':lokasi', $lokasi);

        if ($this->db->execute()) {
            return $this->db->lastInsertId();
        }
        return false;
    }

    // Record logout timestamp in history_login table
    public function recordLogoutHistory($historyId) {
        if (!$historyId) return false;
        $wLogout = date('Y-m-d H:i:s');

        $this->db->query("UPDATE history_login SET w_logout = :w_logout WHERE id = :id");
        $this->db->bind(':w_logout', $wLogout);
        $this->db->bind(':id', $historyId);

        return $this->db->execute();
    }

    // Get all login history records (using view_aktivitas_login or raw query)
    public function getLoginHistory($limit = 100) {
        $this->db->query("SELECT h.*, u.nama AS nama_user, u.username, l.nama AS level_nama 
                          FROM history_login h 
                          LEFT JOIN user u ON u.id = h.iduser 
                          LEFT JOIN level_user l ON l.id = h.level 
                          ORDER BY h.id DESC LIMIT :limit");
        $this->db->bind(':limit', (int)$limit, PDO::PARAM_INT);
        return $this->db->resultSet();
    }
}

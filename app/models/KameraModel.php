<?php

class KameraModel {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    // Ambil semua daftar kamera
    public function getAllCameras() {
        $this->db->query("SELECT * FROM setipcam ORDER BY id ASC");
        return $this->db->resultSet();
    }

    // Ambil kamera berdasarkan ID
    public function getCameraById($id) {
        $this->db->query("SELECT * FROM setipcam WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    // Perbarui konfigurasi kamera
    public function updateCamera($id, $data) {
        $this->db->query("UPDATE setipcam SET 
            nama = :nama, 
            lanip = :lanip, 
            user = :user, 
            pass = :pass, 
            encode = :encode 
            WHERE id = :id");
        $this->db->bind(':nama', $data['nama']);
        $this->db->bind(':lanip', $data['lanip']);
        $this->db->bind(':user', $data['user']);
        $this->db->bind(':pass', $data['pass']);
        $this->db->bind(':encode', $data['encode'] ?? null);
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    // Ambil data foto berdasarkan IDTRX
    public function getPhotoByTrx($idtrx) {
        // Cek tabel foto_out terlebih dahulu
        $this->db->query("SELECT fo.*, fi.foto_masuk1, fi.foto_masuk2 
                          FROM foto_out fo 
                          LEFT JOIN foto_in fi ON fi.idtrx = fo.idtrx 
                          WHERE fo.idtrx = :idtrx LIMIT 1");
        $this->db->bind(':idtrx', $idtrx);
        $res = $this->db->single();

        if ($res) {
            return $res;
        }

        // Fallback ke foto_in jika transaksi belum keluar
        $this->db->query("SELECT *, foto_masuk1 AS foto_masuk, NULL AS foto_out FROM foto_in WHERE idtrx = :idtrx LIMIT 1");
        $this->db->bind(':idtrx', $idtrx);
        return $this->db->single();
    }

    // Ambil daftar audit transaksi foto dengan filter tanggal & keyword
    public function getPhotoAuditList($startDate, $endDate, $keyword = '', $limit = 20, $offset = 0) {
        $sql = "SELECT j.idtrx, j.nopol, j.jn_kendaraan, j.gate, j.gateout, 
                       j.waktuMasuk, j.waktuKeluar, j.durasi, j.tarif, j.status,
                       fi.foto_masuk1, fi.foto_masuk2,
                       fo.foto_out, fo.foto_out2
                FROM jurnal_transaksi j
                LEFT JOIN foto_in fi ON fi.idtrx = j.idtrx
                LEFT JOIN foto_out fo ON fo.idtrx = j.idtrx
                WHERE DATE(j.waktuMasuk) BETWEEN :start_date AND :end_date ";

        if (!empty($keyword)) {
            $sql .= " AND (j.idtrx LIKE :kw OR j.nopol LIKE :kw) ";
        }

        $sql .= " ORDER BY j.id DESC LIMIT :limit OFFSET :offset";

        $this->db->query($sql);
        $this->db->bind(':start_date', $startDate);
        $this->db->bind(':end_date', $endDate);
        if (!empty($keyword)) {
            $this->db->bind(':kw', '%' . $keyword . '%');
        }
        $this->db->bind(':limit', (int)$limit);
        $this->db->bind(':offset', (int)$offset);

        return $this->db->resultSet();
    }

    // Hitung total audit foto untuk pagination
    public function countPhotoAuditList($startDate, $endDate, $keyword = '') {
        $sql = "SELECT COUNT(*) AS total 
                FROM jurnal_transaksi j 
                WHERE DATE(j.waktuMasuk) BETWEEN :start_date AND :end_date ";

        if (!empty($keyword)) {
            $sql .= " AND (j.idtrx LIKE :kw OR j.nopol LIKE :kw) ";
        }

        $this->db->query($sql);
        $this->db->bind(':start_date', $startDate);
        $this->db->bind(':end_date', $endDate);
        if (!empty($keyword)) {
            $this->db->bind(':kw', '%' . $keyword . '%');
        }
        $res = $this->db->single();
        return $res ? (int)$res['total'] : 0;
    }
}

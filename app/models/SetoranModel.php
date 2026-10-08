<?php

class SetoranModel {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getAllSetoran($limit = 100) {
        $this->db->query("SELECT s.*, u.nama AS penerima_nama 
                          FROM data_setoran s 
                          LEFT JOIN user u ON u.id = s.iduser_penerima 
                          ORDER BY s.id DESC 
                          LIMIT :limit");
        $this->db->bind(':limit', (int)$limit);
        return $this->db->resultSet();
    }

    public function getSetoranById($id) {
        $this->db->query("SELECT s.*, u.nama AS penerima_nama 
                          FROM data_setoran s 
                          LEFT JOIN user u ON u.id = s.iduser_penerima 
                          WHERE s.id = :id");
        $this->db->bind(':id', (int)$id);
        return $this->db->single();
    }

    public function getDetailByNoSetoran($no_setoran) {
        $this->db->query("SELECT * FROM detail_setoran WHERE no_setoran = :no_setoran ORDER BY id ASC");
        $this->db->bind(':no_setoran', $no_setoran);
        return $this->db->resultSet();
    }

    public function getShifts() {
        $this->db->query("SELECT * FROM jamshift ORDER BY id ASC");
        return $this->db->resultSet();
    }

    public function getShiftById($id) {
        $this->db->query("SELECT * FROM jamshift WHERE id = :id");
        $this->db->bind(':id', (int)$id);
        return $this->db->single();
    }

    public function updateShift($id, $nama, $jama, $jamb) {
        $this->db->query("UPDATE jamshift SET nama = :nama, jama = :jama, jamb = :jamb WHERE id = :id");
        $this->db->bind(':id', (int)$id);
        $this->db->bind(':nama', $nama);
        $this->db->bind(':jama', $jama);
        $this->db->bind(':jamb', $jamb);
        return $this->db->execute();
    }

    public function getPosKasir() {
        $this->db->query("SELECT * FROM pos_kasir ORDER BY id ASC");
        return $this->db->resultSet();
    }

    public function getKasirList() {
        $this->db->query("SELECT id, username, nama FROM user ORDER BY nama ASC");
        return $this->db->resultSet();
    }

    public function calculateRevenue($tgl, $shift = null, $iduser = null) {
        $sql = "SELECT jn_kendaraan, 
                       COUNT(*) AS total_kendaraan,
                       COALESCE(SUM(tarif + COALESCE(denda, 0)), 0) AS total,
                       COALESCE(SUM(CASE WHEN cara_bayar = 'Tunai' OR cara_bayar IS NULL OR cara_bayar = '' THEN (tarif + COALESCE(denda, 0)) ELSE 0 END), 0) AS tunai,
                       COALESCE(SUM(CASE WHEN cara_bayar = 'QRIS' THEN (tarif + COALESCE(denda, 0)) ELSE 0 END), 0) AS qris,
                       COALESCE(SUM(CASE WHEN cara_bayar = 'Prepaid' THEN (tarif + COALESCE(denda, 0)) ELSE 0 END), 0) AS prepaid
                FROM jurnal_transaksi 
                WHERE status = 'S' AND (tgl_keluar = :tgl OR tgltransaksi = :tgl)";
        
        if (!empty($shift)) {
            $sql .= " AND shift = :shift";
        }
        if (!empty($iduser)) {
            $sql .= " AND id_user = :iduser";
        }
        $sql .= " GROUP BY jn_kendaraan";

        $this->db->query($sql);
        $this->db->bind(':tgl', $tgl);
        if (!empty($shift)) {
            $this->db->bind(':shift', $shift);
        }
        if (!empty($iduser)) {
            $this->db->bind(':iduser', $iduser);
        }
        $items = $this->db->resultSet();

        $grand_total = 0;
        $total_tunai = 0;
        $total_qris = 0;
        $total_prepaid = 0;
        $total_kendaraan = 0;

        foreach ($items as $item) {
            $grand_total += (int)$item['total'];
            $total_tunai += (int)$item['tunai'];
            $total_qris += (int)$item['qris'];
            $total_prepaid += (int)$item['prepaid'];
            $total_kendaraan += (int)$item['total_kendaraan'];
        }

        return [
            'items' => $items,
            'grand_total' => $grand_total,
            'total_tunai' => $total_tunai,
            'total_qris' => $total_qris,
            'total_prepaid' => $total_prepaid,
            'total_kendaraan' => $total_kendaraan
        ];
    }

    public function createSetoran($header, $details) {
        $no_setoran = date('ymdHis') . 'ST';

        $this->db->query("INSERT INTO data_setoran 
            (no_setoran, waktu, iduser_penerima, iduser_kasir, nama_kasir, shift, pintu, tglsetoran, login, logout, jumuang, fisik, jummasalah, jumuangmasalah) 
            VALUES 
            (:no_setoran, NOW(), :id_penerima, :id_kasir, :nama_kasir, :shift, :pintu, :tgl, :login, :logout, :jumuang, :fisik, :jummasalah, :jumuangmasalah)");

        $this->db->bind(':no_setoran', $no_setoran);
        $this->db->bind(':id_penerima', $header['iduser_penerima']);
        $this->db->bind(':id_kasir', $header['iduser_kasir']);
        $this->db->bind(':nama_kasir', $header['nama_kasir']);
        $this->db->bind(':shift', $header['shift']);
        $this->db->bind(':pintu', $header['pintu']);
        $this->db->bind(':tgl', $header['tglsetoran']);
        $this->db->bind(':login', $header['login']);
        $this->db->bind(':logout', $header['logout']);
        $this->db->bind(':jumuang', $header['jumuang']);
        $this->db->bind(':fisik', $header['fisik']);
        $this->db->bind(':jummasalah', $header['jummasalah']);
        $this->db->bind(':jumuangmasalah', $header['jumuangmasalah']);
        $this->db->execute();

        $setId = $this->db->lastInsertId();

        // Insert details
        foreach ($details as $d) {
            $this->db->query("INSERT INTO detail_setoran 
                (no_setoran, nama_kendaraan, tunai, qris, prepaid, total) 
                VALUES 
                (:no_setoran, :nama, :tunai, :qris, :prepaid, :total)");
            $this->db->bind(':no_setoran', $no_setoran);
            $this->db->bind(':nama', $d['nama_kendaraan']);
            $this->db->bind(':tunai', $d['tunai']);
            $this->db->bind(':qris', $d['qris']);
            $this->db->bind(':prepaid', $d['prepaid']);
            $this->db->bind(':total', $d['total']);
            $this->db->execute();
        }

        return $setId;
    }
}

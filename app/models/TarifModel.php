<?php

class TarifModel {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    // --- 1. TARIF PROGRESIF (AWAL & BERJALAN) ---
    public function getTarifProgresif() {
        $this->db->query("SELECT j.id, j.jn_kendaraan, j.nama, 
                                 COALESCE(ta.rupiah, 0) AS tarif_awal, 
                                 COALESCE(tb.rupiah, 0) AS tarif_berjalan 
                          FROM jenis_kendaraan j 
                          LEFT JOIN tarif_awal ta ON ta.kode_kendaraan = j.jn_kendaraan 
                          LEFT JOIN tarif_berjalan tb ON tb.kode_kendaraan = j.jn_kendaraan 
                          ORDER BY j.id ASC");
        return $this->db->resultSet();
    }

    public function updateTarifProgresif($kode, $awal, $berjalan) {
        // Update/Insert tarif_awal
        $this->db->query("SELECT * FROM tarif_awal WHERE kode_kendaraan = :kode");
        $this->db->bind(':kode', $kode);
        if ($this->db->single()) {
            $this->db->query("UPDATE tarif_awal SET rupiah = :rp WHERE kode_kendaraan = :kode");
            $this->db->bind(':kode', $kode);
            $this->db->bind(':rp', $awal);
            $this->db->execute();
        } else {
            $this->db->query("INSERT INTO tarif_awal (kode_kendaraan, durasi, rupiah) VALUES (:kode, '1 Jam', :rp)");
            $this->db->bind(':kode', $kode);
            $this->db->bind(':rp', $awal);
            $this->db->execute();
        }

        // Update/Insert tarif_berjalan
        $this->db->query("SELECT * FROM tarif_berjalan WHERE kode_kendaraan = :kode");
        $this->db->bind(':kode', $kode);
        if ($this->db->single()) {
            $this->db->query("UPDATE tarif_berjalan SET rupiah = :rp WHERE kode_kendaraan = :kode");
            $this->db->bind(':kode', $kode);
            $this->db->bind(':rp', $berjalan);
            $this->db->execute();
        } else {
            $this->db->query("INSERT INTO tarif_berjalan (kode_kendaraan, rupiah) VALUES (:kode, :rp)");
            $this->db->bind(':kode', $kode);
            $this->db->bind(':rp', $berjalan);
            $this->db->execute();
        }
        return true;
    }

    // --- 2. TARIF FLAT ---
    public function getTarifFlat() {
        $this->db->query("SELECT tf.id, tf.jnKendaraan AS kode, 
                                 COALESCE(j.nama, tf.jnKendaraan) AS nama, 
                                 tf.tarif 
                          FROM tarif_flat tf 
                          LEFT JOIN jenis_kendaraan j ON j.jn_kendaraan = tf.jnKendaraan 
                          ORDER BY tf.id ASC");
        return $this->db->resultSet();
    }

    public function updateTarifFlat($id, $tarif) {
        $this->db->query("UPDATE tarif_flat SET tarif = :tarif WHERE id = :id");
        $this->db->bind(':id', $id);
        $this->db->bind(':tarif', $tarif);
        return $this->db->execute();
    }

    // --- 3. TARIF INAP ---
    public function getTarifInap() {
        $this->db->query("SELECT ti.id, ti.kendaraan AS kode, 
                                 COALESCE(j.nama, ti.kendaraan) AS nama, 
                                 ti.tarif 
                          FROM tarif_inap ti 
                          LEFT JOIN jenis_kendaraan j ON j.jn_kendaraan = ti.kendaraan 
                          ORDER BY ti.id ASC");
        return $this->db->resultSet();
    }

    public function updateTarifInap($id, $tarif) {
        $this->db->query("UPDATE tarif_inap SET tarif = :tarif WHERE id = :id");
        $this->db->bind(':id', $id);
        $this->db->bind(':tarif', $tarif);
        return $this->db->execute();
    }

    // --- 4. TARIF TIKET HILANG (TARIF LOST) ---
    public function getTarifLost() {
        $this->db->query("SELECT tl.id, tl.kendaraan AS kode, 
                                 COALESCE(j.nama, tl.kendaraan) AS nama, 
                                 tl.tarif 
                          FROM tarif_lost tl 
                          LEFT JOIN jenis_kendaraan j ON j.jn_kendaraan = tl.kendaraan 
                          ORDER BY tl.id ASC");
        return $this->db->resultSet();
    }

    public function getTarifLostByKode($kode) {
        $this->db->query("SELECT * FROM tarif_lost WHERE kendaraan = :kode LIMIT 1");
        $this->db->bind(':kode', $kode);
        return $this->db->single();
    }

    public function updateTarifLost($id, $tarif) {
        $this->db->query("UPDATE tarif_lost SET tarif = :tarif WHERE id = :id");
        $this->db->bind(':id', $id);
        $this->db->bind(':tarif', $tarif);
        return $this->db->execute();
    }

    // --- 5. TARIF MAKSIMAL HARIAN ---
    public function getTarifMaksimal() {
        $this->db->query("SELECT tm.id, tm.kendaraan AS kode, 
                                 COALESCE(j.nama, tm.kendaraan) AS nama, 
                                 tm.tarif 
                          FROM tarif_maksimal tm 
                          LEFT JOIN jenis_kendaraan j ON j.jn_kendaraan = tm.kendaraan 
                          ORDER BY tm.id ASC");
        return $this->db->resultSet();
    }

    public function updateTarifMaksimal($id, $tarif) {
        $this->db->query("UPDATE tarif_maksimal SET tarif = :tarif WHERE id = :id");
        $this->db->bind(':id', $id);
        $this->db->bind(':tarif', $tarif);
        return $this->db->execute();
    }

    // --- 6. DISKON & VOUCHER ---
    public function getDiskon() {
        $this->db->query("SELECT * FROM discount_tarif ORDER BY id DESC");
        return $this->db->resultSet();
    }

    public function addDiskon($kode, $nama, $nilai, $status = 'AKTIF') {
        $this->db->query("INSERT INTO discount_tarif (waktu_buat, kode_trx, nama, nilai, status_aktif) 
                          VALUES (NOW(), :kode, :nama, :nilai, :status)");
        $this->db->bind(':kode', $kode);
        $this->db->bind(':nama', $nama);
        $this->db->bind(':nilai', $nilai);
        $this->db->bind(':status', $status);
        return $this->db->execute();
    }

    public function deleteDiskon($id) {
        $this->db->query("DELETE FROM discount_tarif WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function toggleDiskon($id) {
        $this->db->query("SELECT status_aktif FROM discount_tarif WHERE id = :id");
        $this->db->bind(':id', $id);
        $res = $this->db->single();
        if ($res) {
            $newStatus = ($res['status_aktif'] === 'AKTIF') ? 'NONAKTIF' : 'AKTIF';
            $this->db->query("UPDATE discount_tarif SET status_aktif = :status WHERE id = :id");
            $this->db->bind(':status', $newStatus);
            $this->db->bind(':id', $id);
            return $this->db->execute();
        }
        return false;
    }

    // --- 7. HARI LIBUR NASIONAL ---
    public function getHariLibur() {
        $this->db->query("SELECT * FROM hari_libur ORDER BY tgl DESC");
        return $this->db->resultSet();
    }

    public function addHariLibur($tgl) {
        $this->db->query("INSERT INTO hari_libur (tgl) VALUES (:tgl)");
        $this->db->bind(':tgl', $tgl);
        return $this->db->execute();
    }

    public function deleteHariLibur($id) {
        $this->db->query("DELETE FROM hari_libur WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }
}

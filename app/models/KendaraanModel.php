<?php

class KendaraanModel {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getAllJenisKendaraan() {
        $this->db->query("SELECT j.*, 
                                 COALESCE(ta.rupiah, 2000) AS tarif_pertama, 
                                 COALESCE(tb.rupiah, 1000) AS tarif_berikutnya 
                          FROM jenis_kendaraan j 
                          LEFT JOIN tarif_awal ta ON ta.kode_kendaraan = j.jn_kendaraan 
                          LEFT JOIN tarif_berjalan tb ON tb.kode_kendaraan = j.jn_kendaraan 
                          ORDER BY j.id ASC");
        return $this->db->resultSet();
    }

    public function getJenisById($id) {
        $this->db->query("SELECT j.*, 
                                 COALESCE(ta.rupiah, 2000) AS tarif_pertama, 
                                 COALESCE(tb.rupiah, 1000) AS tarif_berikutnya 
                          FROM jenis_kendaraan j 
                          LEFT JOIN tarif_awal ta ON ta.kode_kendaraan = j.jn_kendaraan 
                          LEFT JOIN tarif_berjalan tb ON tb.kode_kendaraan = j.jn_kendaraan 
                          WHERE j.id = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function getJenisByName($nama) {
        $this->db->query("SELECT j.*, 
                                 COALESCE(ta.rupiah, 2000) AS tarif_pertama, 
                                 COALESCE(tb.rupiah, 1000) AS tarif_berikutnya 
                          FROM jenis_kendaraan j 
                          LEFT JOIN tarif_awal ta ON ta.kode_kendaraan = j.jn_kendaraan 
                          LEFT JOIN tarif_berjalan tb ON tb.kode_kendaraan = j.jn_kendaraan 
                          WHERE j.nama = :nama");
        $this->db->bind(':nama', $nama);
        return $this->db->single();
    }

    public function createJenis($data) {
        // 1. Insert into jenis_kendaraan
        $this->db->query("INSERT INTO jenis_kendaraan (jn_kendaraan, nama, kapasitas, terpakai) 
                          VALUES (:jn_kendaraan, :nama, :kapasitas, 0)");
        $this->db->bind(':jn_kendaraan', $data['jn_kendaraan']);
        $this->db->bind(':nama', $data['nama']);
        $this->db->bind(':kapasitas', $data['kapasitas']);
        $res = $this->db->execute();

        if ($res) {
            // 2. Insert into tarif_awal
            $this->db->query("INSERT INTO tarif_awal (kode_kendaraan, durasi, rupiah) VALUES (:kode, '1 Jam', :rp)");
            $this->db->bind(':kode', $data['jn_kendaraan']);
            $this->db->bind(':rp', $data['tarif_pertama']);
            $this->db->execute();

            // 3. Insert into tarif_berjalan
            $this->db->query("INSERT INTO tarif_berjalan (kode_kendaraan, rupiah) VALUES (:kode, :rp)");
            $this->db->bind(':kode', $data['jn_kendaraan']);
            $this->db->bind(':rp', $data['tarif_berikutnya']);
            $this->db->execute();

            return true;
        }
        return false;
    }

    public function updateJenis($data) {
        // 1. Update jenis_kendaraan
        $this->db->query("UPDATE jenis_kendaraan 
                          SET jn_kendaraan = :jn_kendaraan, nama = :nama, kapasitas = :kapasitas 
                          WHERE id = :id");
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':jn_kendaraan', $data['jn_kendaraan']);
        $this->db->bind(':nama', $data['nama']);
        $this->db->bind(':kapasitas', $data['kapasitas']);
        $this->db->execute();

        // 2. Update/Insert tarif_awal
        $this->db->query("SELECT * FROM tarif_awal WHERE kode_kendaraan = :kode");
        $this->db->bind(':kode', $data['jn_kendaraan']);
        if ($this->db->single()) {
            $this->db->query("UPDATE tarif_awal SET rupiah = :rp WHERE kode_kendaraan = :kode");
        } else {
            $this->db->query("INSERT INTO tarif_awal (kode_kendaraan, durasi, rupiah) VALUES (:kode, '1 Jam', :rp)");
        }
        $this->db->bind(':kode', $data['jn_kendaraan']);
        $this->db->bind(':rp', $data['tarif_pertama']);
        $this->db->execute();

        // 3. Update/Insert tarif_berjalan
        $this->db->query("SELECT * FROM tarif_berjalan WHERE kode_kendaraan = :kode");
        $this->db->bind(':kode', $data['jn_kendaraan']);
        if ($this->db->single()) {
            $this->db->query("UPDATE tarif_berjalan SET rupiah = :rp WHERE kode_kendaraan = :kode");
        } else {
            $this->db->query("INSERT INTO tarif_berjalan (kode_kendaraan, rupiah) VALUES (:kode, :rp)");
        }
        $this->db->bind(':kode', $data['jn_kendaraan']);
        $this->db->bind(':rp', $data['tarif_berikutnya']);
        $this->db->execute();

        return true;
    }

    public function deleteJenis($id) {
        $jenis = $this->getJenisById($id);
        if ($jenis) {
            $kode = $jenis['jn_kendaraan'];

            $this->db->query("DELETE FROM jenis_kendaraan WHERE id = :id");
            $this->db->bind(':id', $id);
            $this->db->execute();

            $this->db->query("DELETE FROM tarif_awal WHERE kode_kendaraan = :kode");
            $this->db->bind(':kode', $kode);
            $this->db->execute();

            $this->db->query("DELETE FROM tarif_berjalan WHERE kode_kendaraan = :kode");
            $this->db->bind(':kode', $kode);
            $this->db->execute();

            return true;
        }
        return false;
    }

    public function incrementOccupancy($namaKendaraan) {
        $this->db->query("UPDATE jenis_kendaraan SET terpakai = COALESCE(terpakai, 0) + 1 WHERE (nama = :nama OR jn_kendaraan = :nama)");
        $this->db->bind(':nama', $namaKendaraan);
        return $this->db->execute();
    }

    public function decrementOccupancy($namaKendaraan) {
        $this->db->query("UPDATE jenis_kendaraan SET terpakai = GREATEST(0, COALESCE(terpakai, 0) - 1) WHERE (nama = :nama OR jn_kendaraan = :nama)");
        $this->db->bind(':nama', $namaKendaraan);
        return $this->db->execute();
    }
}

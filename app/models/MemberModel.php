<?php

class MemberModel {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getAllMembers() {
        $this->db->query("SELECT * FROM member ORDER BY id DESC");
        return $this->db->resultSet();
    }

    public function getMemberById($id) {
        $this->db->query("SELECT * FROM member WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function getMemberByNopol($nopol) {
        $this->db->query("SELECT * FROM member WHERE nopol = :nopol AND status = 1");
        $this->db->bind(':nopol', strtoupper(trim($nopol)));
        return $this->db->single();
    }

    public function createMember($data) {
        $idMember = 'MBR-' . sprintf("%03d", rand(1, 999));
        $this->db->query("INSERT INTO member (id_member, jn_kendaraan, nopol, tgl_mulai, tgl_akhir, nama, alamat, no_tlp, status) 
                          VALUES (:id_member, :jn_kendaraan, :nopol, :tgl_mulai, :tgl_akhir, :nama, :alamat, :no_tlp, 1)");
        $this->db->bind(':id_member', $idMember);
        $this->db->bind(':jn_kendaraan', $data['jn_kendaraan']);
        $this->db->bind(':nopol', strtoupper(trim($data['nopol'])));
        $this->db->bind(':tgl_mulai', $data['tgl_mulai']);
        $this->db->bind(':tgl_akhir', $data['tgl_akhir']);
        $this->db->bind(':nama', $data['nama']);
        $this->db->bind(':alamat', $data['alamat']);
        $this->db->bind(':no_tlp', $data['no_tlp']);
        return $this->db->execute();
    }

    public function updateMember($data) {
        $this->db->query("UPDATE member SET jn_kendaraan = :jn_kendaraan, nopol = :nopol, tgl_mulai = :tgl_mulai, 
                          tgl_akhir = :tgl_akhir, nama = :nama, alamat = :alamat, no_tlp = :no_tlp, status = :status 
                          WHERE id = :id");
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':jn_kendaraan', $data['jn_kendaraan']);
        $this->db->bind(':nopol', strtoupper(trim($data['nopol'])));
        $this->db->bind(':tgl_mulai', $data['tgl_mulai']);
        $this->db->bind(':tgl_akhir', $data['tgl_akhir']);
        $this->db->bind(':nama', $data['nama']);
        $this->db->bind(':alamat', $data['alamat']);
        $this->db->bind(':no_tlp', $data['no_tlp']);
        $this->db->bind(':status', $data['status']);
        return $this->db->execute();
    }

    public function deleteMember($id) {
        $this->db->query("DELETE FROM member WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }
}

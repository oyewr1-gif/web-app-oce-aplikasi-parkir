<?php

class LaporanModel {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getFilteredReports($startDate, $endDate, $status = 'ALL', $jnKendaraan = 'ALL', $limit = 500) {
        $sql = "SELECT * FROM jurnal_transaksi WHERE 1=1";
        
        if (!empty($startDate) && !empty($endDate)) {
            $sql .= " AND (tgl_masuk BETWEEN :startDate AND :endDate OR tgl_keluar BETWEEN :startDate AND :endDate)";
        }
        
        if ($status !== 'ALL') {
            $sql .= " AND status = :status";
        }
        
        if ($jnKendaraan !== 'ALL') {
            $sql .= " AND jn_kendaraan = :jnKendaraan";
        }

        $sql .= " ORDER BY id DESC LIMIT :limit";

        $this->db->query($sql);

        if (!empty($startDate) && !empty($endDate)) {
            $this->db->bind(':startDate', $startDate);
            $this->db->bind(':endDate', $endDate);
        }
        if ($status !== 'ALL') {
            $this->db->bind(':status', $status);
        }
        if ($jnKendaraan !== 'ALL') {
            $this->db->bind(':jnKendaraan', $jnKendaraan);
        }

        $this->db->bind(':limit', (int)$limit, PDO::PARAM_INT);

        return $this->db->resultSet();
    }

    public function getSummaryStats($startDate = null, $endDate = null) {
        if (!$startDate) $startDate = date('Y-m-d');
        if (!$endDate) $endDate = date('Y-m-d');

        $this->db->query("SELECT 
            COUNT(id) AS total_transaksi,
            COALESCE(SUM(CASE WHEN status = 'S' THEN tarif ELSE 0 END), 0) AS total_pendapatan,
            COALESCE(SUM(CASE WHEN status = 'B' THEN 1 ELSE 0 END), 0) AS total_aktif,
            COALESCE(SUM(CASE WHEN status = 'S' THEN 1 ELSE 0 END), 0) AS total_selesai
            FROM jurnal_transaksi 
            WHERE tgl_masuk BETWEEN :startDate AND :endDate OR tgl_keluar BETWEEN :startDate AND :endDate");
            
        $this->db->bind(':startDate', $startDate);
        $this->db->bind(':endDate', $endDate);
        return $this->db->single();
    }
}

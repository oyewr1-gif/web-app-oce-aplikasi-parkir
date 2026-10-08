<?php

class ParkirModel {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function generateTicketId() {
        return 'TRX' . date('YmdHis') . rand(10, 99);
    }

    public function getManlessGates() {
        $this->db->query("SELECT * FROM manless ORDER BY id ASC");
        return $this->db->resultSet();
    }

    public function getPosKasirGates() {
        $this->db->query("SELECT * FROM pos_kasir ORDER BY id ASC");
        return $this->db->resultSet();
    }

    public function getTarifLostList() {
        $this->db->query("SELECT tl.*, COALESCE(j.nama, tl.kendaraan) AS nama_kendaraan 
                          FROM tarif_lost tl 
                          LEFT JOIN jenis_kendaraan j ON j.jn_kendaraan = tl.kendaraan 
                          ORDER BY tl.id ASC");
        return $this->db->resultSet();
    }

    public function getTarifLostByKendaraan($namaKendaraan) {
        $this->db->query("SELECT tl.tarif 
                          FROM tarif_lost tl 
                          JOIN jenis_kendaraan j ON j.jn_kendaraan = tl.kendaraan 
                          WHERE (j.nama = :nama OR j.jn_kendaraan = :nama) LIMIT 1");
        $this->db->bind(':nama', $namaKendaraan);
        $res = $this->db->single();
        return $res ? (int)$res['tarif'] : 10000;
    }

    // Process Vehicle Entry
    public function catatMasuk($data) {
        $idtrx = $this->generateTicketId();
        $tglMasuk = date('Y-m-d');
        $jamMasuk = date('H:i:s');
        $waktuMasuk = date('Y-m-d H:i:s');
        $gate = !empty($data['gate']) ? trim($data['gate']) : 'POS-IN-01';
        $gateout = !empty($data['gateout']) ? trim($data['gateout']) : 'POS-OUT-01';

        $this->db->query("INSERT INTO jurnal_transaksi 
            (idtrx, nopol, jn_kendaraan, gate, gateout, tgl_masuk, jam_masuk, status, waktuMasuk, status_tiket, status_bayar, id_user, shift) 
            VALUES (:idtrx, :nopol, :jn_kendaraan, :gate, :gateout, :tgl_masuk, :jam_masuk, 'B', :waktuMasuk, 1, 0, :id_user, :shift)");
        
        $this->db->bind(':idtrx', $idtrx);
        $this->db->bind(':nopol', strtoupper(trim($data['nopol'])));
        $this->db->bind(':jn_kendaraan', $data['jn_kendaraan']);
        $this->db->bind(':gate', $gate);
        $this->db->bind(':gateout', $gateout);
        $this->db->bind(':tgl_masuk', $tglMasuk);
        $this->db->bind(':jam_masuk', $jamMasuk);
        $this->db->bind(':waktuMasuk', $waktuMasuk);
        $this->db->bind(':id_user', Session::get('user_id') ?? 1);
        $this->db->bind(':shift', Session::get('user_shift') ?? 'Shift 1');

        if ($this->db->execute()) {
            return $idtrx;
        }
        return false;
    }

    // Get Active (Parked) Transaction by IDTRX or Nopol
    public function getActiveTransactionByKeyword($keyword) {
        $this->db->query("SELECT * FROM jurnal_transaksi 
                          WHERE (idtrx = :kw OR nopol = :kw) AND status = 'B' 
                          ORDER BY id DESC LIMIT 1");
        $this->db->bind(':kw', strtoupper(trim($keyword)));
        return $this->db->single();
    }

    // Get Single Transaction by IDTRX
    public function getTransactionById($idtrx) {
        $this->db->query("SELECT * FROM jurnal_transaksi WHERE idtrx = :idtrx");
        $this->db->bind(':idtrx', $idtrx);
        return $this->db->single();
    }

    // Get List of Currently Parked Vehicles
    public function getActiveVehicles($limit = 10) {
        $this->db->query("SELECT * FROM jurnal_transaksi WHERE status = 'B' ORDER BY id DESC LIMIT :limit");
        $this->db->bind(':limit', (int)$limit);
        return $this->db->resultSet();
    }

    // Get Recent Finished Transactions
    public function getRecentCompleted($limit = 10) {
        $this->db->query("SELECT * FROM jurnal_transaksi WHERE status = 'S' ORDER BY id DESC LIMIT :limit");
        $this->db->bind(':limit', (int)$limit);
        return $this->db->resultSet();
    }

    public function getActiveTransactions($limit = 10) {
        return $this->getActiveVehicles($limit);
    }

    public function getRecentCompletedTransactions($limit = 10) {
        return $this->getRecentCompleted($limit);
    }

    public function getTransactionByIdTrx($idtrx) {
        return $this->getTransactionById($idtrx);
    }

    // Calculate Parking Duration and Rate
    public function hitungTarif($waktuMasukStr, $jenisKendaraanNama) {
        $waktuMasuk = new DateTime($waktuMasukStr);
        $waktuKeluar = new DateTime();
        $diff = $waktuMasuk->diff($waktuKeluar);

        $totalHours = ($diff->days * 24) + $diff->h;
        if ($diff->i > 0 || $diff->s > 0) {
            $totalHours += 1;
        }
        if ($totalHours <= 0) {
            $totalHours = 1;
        }

        // Get tariff setup for vehicle type
        $this->db->query("SELECT j.*, 
                                 COALESCE(ta.rupiah, 2000) AS tarif_pertama, 
                                 COALESCE(tb.rupiah, 1000) AS tarif_berikutnya 
                          FROM jenis_kendaraan j 
                          LEFT JOIN tarif_awal ta ON ta.kode_kendaraan = j.jn_kendaraan 
                          LEFT JOIN tarif_berjalan tb ON tb.kode_kendaraan = j.jn_kendaraan 
                          WHERE (j.nama = :nama OR j.jn_kendaraan = :nama)");
        $this->db->bind(':nama', $jenisKendaraanNama);
        $jenis = $this->db->single();

        $tarifPertama = $jenis ? $jenis['tarif_pertama'] : 2000;
        $tarifBerikutnya = $jenis ? $jenis['tarif_berikutnya'] : 1000;

        $totalTarif = $tarifPertama;
        if ($totalHours > 1) {
            $totalTarif += ($totalHours - 1) * $tarifBerikutnya;
        }

        $durasiStr = sprintf("%02d:%02d:%02d", ($diff->days * 24) + $diff->h, $diff->i, $diff->s);

        return [
            'durasi_jam' => $totalHours,
            'durasi_str' => $durasiStr,
            'waktu_keluar' => $waktuKeluar->format('Y-m-d H:i:s'),
            'tgl_keluar' => $waktuKeluar->format('Y-m-d'),
            'jam_keluar' => $waktuKeluar->format('H:i:s'),
            'tarif' => $totalTarif
        ];
    }

    // Process Vehicle Exit Payment with Multi-Payment & Lost Ticket Support
    public function catatKeluar($idtrx, $calcData, $bayar, $gateout = 'POS R4', $options = []) {
        $denda = (int)($options['denda'] ?? 0);
        $totalTagihan = $calcData['tarif'] + $denda;
        $cara_bayar = !empty($options['cara_bayar']) ? $options['cara_bayar'] : 'Tunai';
        $refbayar = $options['refbayar'] ?? '';

        if ($cara_bayar === 'Tunai') {
            $kembalian = $bayar - $totalTagihan;
            if ($kembalian < 0) {
                return false;
            }
        } else {
            $bayar = $totalTagihan;
            $kembalian = 0;
        }

        $gateoutVal = !empty($gateout) ? trim($gateout) : 'POS R4';
        $nopolVal = !empty($options['nopol']) ? strtoupper(trim($options['nopol'])) : null;

        $this->db->query("UPDATE jurnal_transaksi SET 
            nopol = COALESCE(NULLIF(:nopol, ''), nopol),
            tgl_keluar = :tgl_keluar, 
            jam_keluar = :jam_keluar, 
            waktuKeluar = :waktuKeluar, 
            durasi = :durasi, 
            tarif = :tarif, 
            denda = :denda,
            bayar = :bayar, 
            kembalian = :kembalian, 
            cara_bayar = :cara_bayar,
            refbayar = :refbayar,
            nostnk = :nostnk,
            noktp = :noktp,
            nohp = :nohp,
            nama = :nama,
            gateout = :gateout,
            status = 'S', 
            status_bayar = 1 
            WHERE idtrx = :idtrx AND status = 'B'");

        $this->db->bind(':nopol', $nopolVal);
        $this->db->bind(':tgl_keluar', $calcData['tgl_keluar']);
        $this->db->bind(':jam_keluar', $calcData['jam_keluar']);
        $this->db->bind(':waktuKeluar', $calcData['waktu_keluar']);
        $this->db->bind(':durasi', $calcData['durasi_str']);
        $this->db->bind(':tarif', $calcData['tarif']);
        $this->db->bind(':denda', $denda);
        $this->db->bind(':bayar', $bayar);
        $this->db->bind(':kembalian', $kembalian);
        $this->db->bind(':cara_bayar', $cara_bayar);
        $this->db->bind(':refbayar', $refbayar);
        $this->db->bind(':nostnk', $options['nostnk'] ?? null);
        $this->db->bind(':noktp', $options['noktp'] ?? null);
        $this->db->bind(':nohp', $options['nohp'] ?? null);
        $this->db->bind(':nama', $options['nama'] ?? null);
        $this->db->bind(':gateout', $gateoutVal);
        $this->db->bind(':idtrx', $idtrx);

        return $this->db->execute();
    }

    // Cancel / VOID Transaction
    public function voidTransaction($idtrx, $ketbatal, $iduser) {
        $this->db->query("UPDATE jurnal_transaksi SET 
            status = 'N', 
            waktubatal = NOW(), 
            ketbatal = :ketbatal, 
            iduser_pembatalan = :iduser 
            WHERE idtrx = :idtrx");
        $this->db->bind(':ketbatal', $ketbatal);
        $this->db->bind(':iduser', $iduser);
        $this->db->bind(':idtrx', $idtrx);
        return $this->db->execute();
    }

    // Get Voided Transactions
    public function getVoidTransactions($limit = 50) {
        $this->db->query("SELECT j.*, u.nama AS executor_nama 
                          FROM jurnal_transaksi j 
                          LEFT JOIN user u ON u.id = j.iduser_pembatalan 
                          WHERE j.status = 'N' 
                          ORDER BY j.waktubatal DESC, j.id DESC 
                          LIMIT :limit");
        $this->db->bind(':limit', (int)$limit);
        return $this->db->resultSet();
    }

    public function getActiveVehiclesCount() {
        $this->db->query("SELECT COUNT(*) AS total FROM jurnal_transaksi WHERE status = 'B'");
        $res = $this->db->single();
        return $res ? (int)$res['total'] : 0;
    }

    public function getTodayIncome() {
        $today = date('Y-m-d');
        $this->db->query("SELECT SUM(tarif + COALESCE(denda, 0)) AS total FROM jurnal_transaksi WHERE tgl_keluar = :today AND status = 'S'");
        $this->db->bind(':today', $today);
        $res = $this->db->single();
        return $res && $res['total'] ? (int)$res['total'] : 0;
    }

    public function getTodayTransactionsCount() {
        $today = date('Y-m-d');
        $this->db->query("SELECT COUNT(*) AS total FROM jurnal_transaksi WHERE tgl_masuk = :today");
        $this->db->bind(':today', $today);
        $res = $this->db->single();
        return $res ? (int)$res['total'] : 0;
    }
}

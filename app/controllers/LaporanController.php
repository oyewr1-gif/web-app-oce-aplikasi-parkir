<?php

class LaporanController extends Controller {

    public function index() {
        Session::requireLogin();
        $laporanModel = $this->model('LaporanModel');
        $kendaraanModel = $this->model('KendaraanModel');

        $startDate = $_GET['tgl_mulai'] ?? date('Y-m-d');
        $endDate = $_GET['tgl_akhir'] ?? date('Y-m-d');
        $status = $_GET['status'] ?? 'ALL';
        $jnKendaraan = $_GET['jn_kendaraan'] ?? 'ALL';
        $tab = $_GET['tab'] ?? 'transaksi';

        $reports = $laporanModel->getFilteredReports($startDate, $endDate, $status, $jnKendaraan);
        $summary = $laporanModel->getSummaryStats($startDate, $endDate);
        $voidReports = $laporanModel->getVoidReports($startDate, $endDate);

        $data = [
            'title' => 'Laporan Pendapatan & Transaksi Parkir',
            'tab' => $tab,
            'tgl_mulai' => $startDate,
            'tgl_akhir' => $endDate,
            'status' => $status,
            'jn_kendaraan' => $jnKendaraan,
            'reports' => $reports,
            'summary' => $summary,
            'void_reports' => $voidReports,
            'jenis_list' => $kendaraanModel->getAllJenisKendaraan()
        ];

        $this->view('laporan/index', $data);
    }
}

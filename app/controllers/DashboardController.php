<?php

class DashboardController extends Controller {

    public function index() {
        Session::requireLogin();

        $parkirModel = $this->model('ParkirModel');
        $kendaraanModel = $this->model('KendaraanModel');

        $data = [
            'title' => 'Dashboard Utama',
            'active_vehicles' => $parkirModel->getActiveVehiclesCount(),
            'today_income' => $parkirModel->getTodayIncome(),
            'today_transactions' => $parkirModel->getTodayTransactionsCount(),
            'jenis_list' => $kendaraanModel->getAllJenisKendaraan(),
            'active_list' => $parkirModel->getActiveTransactions(),
            'recent_completed' => $parkirModel->getRecentCompletedTransactions(5)
        ];

        $this->view('dashboard/index', $data);
    }
}

<?php
require_once __DIR__ . '/../models/Dashboard.php';

class DashboardController extends Controller {
    public function __construct() {
        $this->requireRole([1, 2]);
    }

    public function index() {
        $dashboardModel = new Dashboard();
        $insights = $dashboardModel->getDashboardInsights();

        $this->view('dashboard/index', [
            'pageTitle' => 'Dashboard',
            'insights' => $insights
        ]);
    }
}

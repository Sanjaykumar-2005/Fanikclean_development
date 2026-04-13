<?php
require_once __DIR__ . '/../models/Dashboard.php';

class ReportController extends Controller {
    public function __construct() {
        $this->checkAuth();
    }

    public function index() {
        $dashModel = new Dashboard();
        $insights = $dashModel->getDashboardInsights();

        $this->view('reports/index', [
            'pageTitle' => 'Analytics & Reporting',
            'insights' => $insights
        ]);
    }
}

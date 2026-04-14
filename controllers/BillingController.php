<?php
require_once __DIR__ . '/../models/Billing.php';

class BillingController extends Controller {
    public function __construct() {
        $this->checkAuth();
    }

    public function index() {
        // Assume retrieving previous bills
        $this->view('billing/index', [
            'pageTitle' => 'Billing Engine'
        ]);
    }

    public function generate() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $monthYear = $_POST['month_year'] ?? date('Y-m'); // format 2026-03
            
            $billingModel = new Billing();
            $billingModel->generateMonthly($monthYear);

            $_SESSION['toast'] = "Billing computationally generated for $monthYear";
            $this->redirect('/billing');
        }
    }
}

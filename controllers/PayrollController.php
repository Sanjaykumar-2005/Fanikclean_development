<?php
require_once __DIR__ . '/../models/Payroll.php';

class PayrollController extends Controller {
    public function __construct() {
        $this->checkAuth();
    }

    public function index() {
        $this->view('payroll/index', [
            'pageTitle' => 'Payroll Processing'
        ]);
    }

    public function approve() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $monthYear = $_POST['month_year'] ?? date('Y-m'); // format 2026-03
            
            $payrollModel = new Payroll();
            $payrollModel->generateMonthly($monthYear);

            $_SESSION['toast'] = "Payroll computed & approved for $monthYear";
            $this->redirect('/payroll');
        }
    }
}

<?php
require_once __DIR__ . '/../models/Payroll.php';

class PayrollController extends Controller {
    public function __construct() {
        $this->checkAuth();
    }

    public function index() {
        $payrollModel = new Payroll();
        $siteId = $this->isAdmin() ? null : $this->getSiteId();
        $payrolls = $payrollModel->getAll($siteId);

        $this->view('payroll/index', [
            'pageTitle' => 'Payroll Processing',
            'payrolls' => $payrolls
        ]);
    }

    public function export() {
        $payrollModel = new Payroll();
        $siteId = $this->isAdmin() ? null : $this->getSiteId();
        $payrolls = $payrollModel->getAll($siteId);

        if (ob_get_level()) ob_end_clean();

        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=payroll.xls");

        echo "Worker Name\tCategory\tMonth-Year\tDays Worked\tBasic Pay\tOT Pay\tAdvance\tNet Pay\tStatus\r\n";

        foreach ($payrolls as $p) {
            echo $p['name'] . "\t" . 
                 $p['category_name'] . "\t" . 
                 $p['month_year'] . "\t" . 
                 $p['days_worked'] . "\t" . 
                 $p['basic_pay'] . "\t" . 
                 $p['ot_pay'] . "\t" . 
                 $p['advance_deduction'] . "\t" . 
                 $p['net_pay'] . "\t" . 
                 $p['status'] . "\r\n";
        }
        exit;
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

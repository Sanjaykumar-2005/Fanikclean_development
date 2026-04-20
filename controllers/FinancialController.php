<?php
require_once __DIR__ . '/../models/Financial.php';

class FinancialController extends Controller {
    public function __construct() { $this->requireRole([1]); }

    public function index() {
        $model = new Financial();
        $ledger = $model->getClientLedger();
        $this->view('financial/index', [
            'pageTitle' => 'Financial Tracking',
            'ledger' => $ledger
        ]);
    }
}

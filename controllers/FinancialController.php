<?php
require_once __DIR__ . '/../models/Financial.php';

class FinancialController extends Controller {
    public function __construct() { $this->checkAuth(); }

    public function index() {
        $model = new Financial();
        $ledger = $model->getClientLedger();
        $this->view('financial/index', [
            'pageTitle' => 'Financial Tracking',
            'ledger' => $ledger
        ]);
    }
}

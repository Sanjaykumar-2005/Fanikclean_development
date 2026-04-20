<?php
require_once __DIR__ . '/../models/Billing.php';

class BillingController extends Controller {
    public function index() {
        require_once __DIR__ . '/../models/Invoice.php';
        require_once __DIR__ . '/../models/Client.php';
        $invModel = new Invoice();
        $clientModel = new Client();
        
        $siteScope = $this->isAdmin() ? null : $this->getAssignedSiteIds();
        $pending = $invModel->getPendingBilling($siteScope);
        $clients = $clientModel->getAll();

        $this->view('billing/index', [
            'pageTitle' => 'Billing Engine',
            'pending' => $pending,
            'clients' => $clients
        ]);
    }

    public function generate() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $monthYear = $_POST['month_year'] ?? date('Y-m'); // format 2026-03
            $clientId = $_POST['client_id'] ?? null;
            
            $billingModel = new Billing();
            $billingModel->generateMonthly($monthYear, $clientId);

            $scopeMsg = $clientId ? "for selected client" : "for all clients";
            $_SESSION['toast'] = "Billing computationally generated $scopeMsg ($monthYear)";
            $this->redirect('/billing');
        }
    }
}

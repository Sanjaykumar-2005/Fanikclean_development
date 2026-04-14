<?php
require_once __DIR__ . '/../models/Invoice.php';

class InvoiceController extends Controller {
    public function __construct() { 
        $this->checkAuth(); 
    }

    public function index() {
        $invModel = new Invoice();
        $siteId = $this->isAdmin() ? null : $this->getSiteId();
        
        $pendingBills = $invModel->getPendingBilling($siteId);
        $invoices = $invModel->getAllInvoices($siteId);

        $this->view('invoices/index', [
            'pageTitle' => 'Invoice Generation',
            'pendingBills' => $pendingBills,
            'invoices' => $invoices
        ]);
    }

    public function generate() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $billingId = $_POST['billing_id'] ?? null;
            if ($billingId) {
                $invModel = new Invoice();
                $invModel->generateFromBilling($billingId);
                $_SESSION['toast'] = "Official Invoice Successfully Generated!";
            }
            $this->redirect('/invoices');
        }
    }

    public function pay() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $invoice_no = $_POST['invoice_no'] ?? null;
            if ($invoice_no) {
                $db = Database::connect();
                $stmt = $db->prepare("UPDATE invoices SET status = 'Paid', payment_date = CURRENT_DATE WHERE invoice_no = :inv");
                $stmt->execute(['inv' => $invoice_no]);
                $_SESSION['toast'] = "Invoice Marked as Paid!";
            }
            $this->redirect('/invoices');
        }
    }

    public function print() {
        $invoiceNo = $_GET['inv'] ?? null;
        if (!$invoiceNo) { $this->redirect('/invoices'); }

        $invModel = new Invoice();
        $invoice = $invModel->getInvoiceDetails($invoiceNo);

        if (!$invoice) {
            $_SESSION['toast'] = "Invoice not found";
            $this->redirect('/invoices');
        }

        // Render WITHOUT layout, standalone print view
        extract($invoice);
        require __DIR__ . '/../views/invoices/print.php';
    }
}

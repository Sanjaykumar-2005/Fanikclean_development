<?php
require_once __DIR__ . '/../config/Database.php';

class Financial {
    private $db;
    public function __construct() { $this->db = Database::connect(); }

    public function getClientLedger() {
        return $this->db->query("
            SELECT 
                c.company_name,
                SUM(b.grand_total) as total_billed,
                COALESCE(SUM(i.amount) FILTER (WHERE i.status = 'Paid'), 0) as total_collected
            FROM clients c
            LEFT JOIN billing b ON c.id = b.client_id
            LEFT JOIN invoices i ON b.id = i.billing_id
            GROUP BY c.id, c.company_name
            ORDER BY total_billed DESC
        ")->fetchAll();
    }
}

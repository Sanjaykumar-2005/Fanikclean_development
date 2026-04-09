<?php
require_once __DIR__ . '/../config/Database.php';

class Billing {
    private $db;
    public function __construct() { $this->db = Database::connect(); }

    public function generateMonthly($monthYear) {
        $this->db->beginTransaction();
        try {
            $stmt = $this->db->prepare("
                SELECT c.id as client_id, c.company_name, 
                       SUM(CASE WHEN a.status = 'p' THEN 1 
                                WHEN a.status = 'h' THEN 0.5 
                                WHEN a.status = 'off' THEN 1 ELSE 0 END) as total_days,
                       wc.default_rate
                FROM attendance a
                JOIN workers w ON a.worker_id = w.id
                JOIN worker_categories wc ON w.category_id = wc.id
                JOIN sites s ON a.site_id = s.id
                JOIN clients c ON s.client_id = c.id
                WHERE TO_CHAR(a.attendance_date, 'YYYY-MM') = :my
                GROUP BY c.id, c.company_name, wc.default_rate
            ");
            $stmt->execute(['my' => $monthYear]);
            $client_data = $stmt->fetchAll();

            $billing_map = [];
            foreach ($client_data as $row) {
                if (!isset($billing_map[$row['client_id']])) {
                    $billing_map[$row['client_id']] = ['subtotal' => 0];
                }
                $billing_map[$row['client_id']]['subtotal'] += ($row['total_days'] * $row['default_rate']);
            }

            foreach ($billing_map as $clientId => $bdata) {
                $subtotal = $bdata['subtotal'];
                $cgst = $subtotal * 0.09;
                $sgst = $subtotal * 0.09;
                $grand = $subtotal + $cgst + $sgst;

                $ins = $this->db->prepare("
                    INSERT INTO billing (client_id, month_year, subtotal, cgst, sgst, grand_total, status)
                    VALUES (:cid, :my, :sub, :cg, :sg, :gt, 'Pending')
                    ON CONFLICT (client_id, month_year) DO UPDATE SET subtotal=EXCLUDED.subtotal, cgst=EXCLUDED.cgst, sgst=EXCLUDED.sgst, grand_total=EXCLUDED.grand_total
                ");
                $ins->execute([
                    'cid' => $clientId, 'my' => $monthYear,
                    'sub' => $subtotal, 'cg' => $cgst, 'sg' => $sgst, 'gt' => $grand
                ]);
            }
            $this->db->commit();
            return true;
        } catch (\Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }
}

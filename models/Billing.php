<?php
require_once __DIR__ . '/../config/Database.php';

class Billing {
    private $db;
    public function __construct() { $this->db = Database::connect(); }

    public function generateMonthly($monthYear, $clientId = null) {
        $this->db->beginTransaction();
        try {
            $sql = "
                SELECT c.id as client_id, s.id as site_id, c.company_name, 
                       SUM(CASE WHEN a.status = 'p' THEN 1 
                                WHEN a.status = 'h' THEN 0.5 
                                WHEN a.status = 'off' THEN 1
                                WHEN a.status = 'pl' THEN 1
                                WHEN a.status = 'sd' THEN 2 ELSE 0 END) as total_days,
                       wc.default_rate
                FROM attendance a
                JOIN workers w ON a.worker_id = w.id
                JOIN worker_categories wc ON w.category_id = wc.id
                JOIN sites s ON a.site_id = s.id
                JOIN clients c ON s.client_id = c.id
                WHERE TO_CHAR(a.attendance_date, 'YYYY-MM') = :my
            ";
            
            $params = ['my' => $monthYear];
            if ($clientId) {
                $sql .= " AND c.id = :cid ";
                $params['cid'] = $clientId;
            }
            
            $sql .= " GROUP BY c.id, s.id, c.company_name, wc.default_rate ";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            $client_data = $stmt->fetchAll();

            $billing_map = [];
            foreach ($client_data as $row) {
                $key = $row['client_id'] . '_' . $row['site_id'];
                if (!isset($billing_map[$key])) {
                    $billing_map[$key] = [
                        'client_id' => $row['client_id'],
                        'site_id' => $row['site_id'],
                        'subtotal' => 0
                    ];
                }
                $billing_map[$key]['subtotal'] += ($row['total_days'] * $row['default_rate']);
            }

            foreach ($billing_map as $key => $bdata) {
                $subtotal = $bdata['subtotal'];
                $cgst = $subtotal * 0.09;
                $sgst = $subtotal * 0.09;
                $grand = $subtotal + $cgst + $sgst;

                $ins = $this->db->prepare("
                    INSERT INTO billing (client_id, site_id, month_year, subtotal, cgst, sgst, grand_total, status)
                    VALUES (:cid, :sid, :my, :sub, :cg, :sg, :gt, 'Pending')
                    ON CONFLICT (client_id, site_id, month_year) DO UPDATE 
                    SET subtotal=EXCLUDED.subtotal, cgst=EXCLUDED.cgst, sgst=EXCLUDED.sgst, grand_total=EXCLUDED.grand_total
                ");
                $ins->execute([
                    'cid' => $bdata['client_id'], 'sid' => $bdata['site_id'], 'my' => $monthYear,
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

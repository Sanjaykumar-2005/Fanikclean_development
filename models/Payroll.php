<?php
require_once __DIR__ . '/../config/Database.php';

class Payroll {
    private $db;
    public function __construct() { $this->db = Database::connect(); }

    public function generateMonthly($monthYear) {
        $this->db->beginTransaction();
        try {
            $stmt = $this->db->prepare("
                SELECT w.id, w.category_id, wc.default_rate, 
                       SUM(CASE WHEN a.status='p' THEN 1 WHEN a.status='h' THEN 0.5 WHEN a.status='off' THEN 1 ELSE 0 END) as days, 
                       SUM(COALESCE(a.ot_hours, 0)) as ot_hrs
                FROM workers w
                JOIN worker_categories wc ON w.category_id = wc.id
                LEFT JOIN attendance a ON w.id = a.worker_id AND TO_CHAR(a.attendance_date, 'YYYY-MM') = :my
                GROUP BY w.id, wc.default_rate
            ");
            $stmt->execute(['my' => $monthYear]);
            $workers = $stmt->fetchAll();

            foreach ($workers as $w) {
                // Payroll Business Logic
                $days = $w['days'] ?? 0;
                $ot = $w['ot_hrs'] ?? 0;
                $rate = $w['default_rate'];
                
                $basicPay = $days * $rate;
                $otPay = $ot * ($rate / 8); // Assuming 8 hr day
                $netPay = $basicPay + $otPay;

                $ins = $this->db->prepare("
                    INSERT INTO payroll (worker_id, month_year, days_worked, basic_pay, ot_days, ot_pay, advance_deduction, net_pay)
                    VALUES (:wid, :my, :dw, :bp, :otd, :otp, 0, :np)
                    ON CONFLICT (worker_id, month_year) DO UPDATE SET days_worked=EXCLUDED.days_worked, basic_pay=EXCLUDED.basic_pay, ot_pay=EXCLUDED.ot_pay, net_pay=EXCLUDED.net_pay
                ");
                $ins->execute([
                    'wid' => $w['id'], 'my' => $monthYear,
                    'dw' => $days, 'bp' => $basicPay, 'otd' => $ot/8, 'otp' => $otPay, 'np' => $netPay
                ]);
            }
            $this->db->commit();
        } catch (\Exception $e) {
            $this->db->rollBack();
        }
    }
}

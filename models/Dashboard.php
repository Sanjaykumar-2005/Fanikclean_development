<?php
require_once __DIR__ . '/../config/Database.php';

class Dashboard {
    private $db;
    public function __construct() { $this->db = Database::connect(); }

    public function getDashboardInsights() {
        $insights = [];
        $currentMonth = date('Y-m');
        $lastMonth = date('Y-m', strtotime('-1 month'));

        // 1. Worker Insights
        $insights['total_workers'] = $this->db->query("SELECT COUNT(*) FROM workers WHERE status = 'Active'")->fetchColumn();
        $insights['new_workers'] = $this->db->query("SELECT COUNT(*) FROM workers WHERE TO_CHAR(created_at, 'YYYY-MM') = '$currentMonth'")->fetchColumn();

        // 2. Site/Client Insights
        $insights['total_sites'] = $this->db->query("SELECT COUNT(*) FROM sites")->fetchColumn();
        $insights['client_count'] = $this->db->query("SELECT COUNT(DISTINCT client_id) FROM sites")->fetchColumn();

        // 3. Revenue Insights
        $currRev = $this->db->query("SELECT SUM(grand_total) FROM billing WHERE month_year = '$currentMonth'")->fetchColumn() ?: 0;
        $prevRev = $this->db->query("SELECT SUM(grand_total) FROM billing WHERE month_year = '$lastMonth'")->fetchColumn() ?: 0;
        $insights['monthly_revenue'] = $currRev;
        $insights['revenue_growth'] = ($prevRev > 0) ? (($currRev - $prevRev) / $prevRev * 100) : 0;

        // 4. Outstanding Insights
        $insights['outstanding_amount'] = $this->db->query("SELECT SUM(amount) FROM invoices WHERE status = 'Unpaid'")->fetchColumn() ?: 0;
        $insights['unpaid_invoices_count'] = $this->db->query("SELECT COUNT(*) FROM invoices WHERE status = 'Unpaid'")->fetchColumn();

        // 5. Attendance Summary (Current Month)
        $att = $this->db->query("
            SELECT 
                COUNT(*) FILTER (WHERE status = 'p') as present,
                COUNT(*) FILTER (WHERE status = 'a') as absent,
                COUNT(*) FILTER (WHERE status = 'h') as half_day,
                SUM(COALESCE(ot_hours, 0)) as ot_hours
            FROM attendance
            WHERE TO_CHAR(attendance_date, 'YYYY-MM') = '$currentMonth'
        ")->fetch();
        $insights['attendance_summary'] = $att;

        // 6. Recent Invoices (Table)
        $insights['recent_invoices'] = $this->db->query("
            SELECT i.*, c.company_name
            FROM invoices i
            JOIN billing b ON i.billing_id = b.id
            JOIN clients c ON b.client_id = c.id
            ORDER BY i.issue_date DESC
            LIMIT 4
        ")->fetchAll();

        // 7. Pending Approvals
        $insights['pending_leave'] = $this->db->query("SELECT COUNT(*) FROM leave_requests WHERE status = 'Pending'")->fetchColumn();
        $insights['pending_attendance'] = $this->db->query("SELECT COUNT(DISTINCT attendance_date) FROM attendance WHERE locked = FALSE")->fetchColumn();
        $insights['pending_billing'] = $this->db->query("SELECT COUNT(*) FROM billing WHERE status = 'Pending'")->fetchColumn();

        return $insights;
    }

    public function getAttendanceTrends() {
        return $this->db->query("
            SELECT attendance_date, 
                   COUNT(*) FILTER (WHERE status = 'p') * 100.0 / NULLIF(COUNT(*), 0) as percentage
            FROM attendance
            WHERE attendance_date > CURRENT_DATE - INTERVAL '7 days'
            GROUP BY attendance_date
            ORDER BY attendance_date ASC
        ")->fetchAll();
    }

    public function getWorkerRoleDistribution() {
        return $this->db->query("
            SELECT wc.name, COUNT(w.id) as count
            FROM worker_categories wc
            LEFT JOIN workers w ON wc.id = w.category_id AND w.status = 'Active'
            GROUP BY wc.name
            ORDER BY count DESC
        ")->fetchAll();
    }
}

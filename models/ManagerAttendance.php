<?php
require_once __DIR__ . '/../config/Database.php';

class ManagerAttendance {
    private $db;
    public function __construct() { $this->db = Database::connect(); }

    public function getByMonth($monthYear) {
        // monthYear format: 2026-04
        $stmt = $this->db->prepare("
            SELECT ma.*, u.full_name as manager_name, u.email 
            FROM manager_attendance ma
            JOIN users u ON ma.user_id = u.id
            WHERE TO_CHAR(ma.attendance_date, 'YYYY-MM') = :my
            ORDER BY ma.attendance_date DESC, u.full_name ASC
        ");
        $stmt->execute(['my' => $monthYear]);
        return $stmt->fetchAll();
    }

    public function getByUser($userId, $monthYear) {
        $stmt = $this->db->prepare("
            SELECT * FROM manager_attendance 
            WHERE user_id = :uid AND TO_CHAR(attendance_date, 'YYYY-MM') = :my
            ORDER BY attendance_date DESC
        ");
        $stmt->execute(['uid' => $userId, 'my' => $monthYear]);
        return $stmt->fetchAll();
    }

    public function saveBulk($monthYear, $records, $adminUserId) {
        $this->db->beginTransaction();
        try {
            $stmt = $this->db->prepare("
                INSERT INTO manager_attendance (user_id, attendance_date, status, note, updated_by)
                VALUES (:uid, :adate, :status, :note, :upby)
                ON CONFLICT (user_id, attendance_date) 
                DO UPDATE SET status = EXCLUDED.status, note = EXCLUDED.note, updated_by = EXCLUDED.updated_by, updated_at = CURRENT_TIMESTAMP
            ");

            foreach ($records as $date => $siteRecords) {
                foreach ($siteRecords as $userId => $data) {
                    $stmt->execute([
                        'uid' => $userId,
                        'adate' => $date,
                        'status' => $data['status'],
                        'note' => $data['note'] ?? null,
                        'upby' => $adminUserId
                    ]);
                }
            }
            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }
    public function getMonthlyPLCounts($monthYear) {
        $stmt = $this->db->prepare("
            SELECT user_id, COUNT(*) as count 
            FROM manager_attendance 
            WHERE status = 'pl' AND TO_CHAR(attendance_date, 'YYYY-MM') = :my
            GROUP BY user_id
        ");
        $stmt->execute(['my' => $monthYear]);
        return $stmt->fetchAll(PDO::FETCH_KEY_PAIR); // returns [user_id => count]
    }
}

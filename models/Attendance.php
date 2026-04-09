<?php
require_once __DIR__ . '/../config/Database.php';

class Attendance {
    private $db;
    public function __construct() { $this->db = Database::connect(); }

    public function saveBulk($siteId, $date, $records, $userId) {
        $this->db->beginTransaction();
        try {
            $stmt = $this->db->prepare("
                INSERT INTO attendance (worker_id, site_id, attendance_date, status, ot_hours, updated_by)
                VALUES (:wid, :sid, :dt, :st, :ot, :usr)
                ON CONFLICT (worker_id, attendance_date) 
                DO UPDATE SET status = EXCLUDED.status, ot_hours = EXCLUDED.ot_hours, updated_by = EXCLUDED.updated_by
            ");
            foreach ($records as $workerId => $recordData) {
                $status = $recordData['status'] ?? '';
                $ot = !empty($recordData['ot']) ? $recordData['ot'] : 0;
                
                if (in_array($status, ['p', 'a', 'h', 'off'])) {
                    $stmt->execute([
                        'wid' => $workerId, 'sid' => $siteId,
                        'dt' => $date, 'st' => $status, 'ot' => $ot, 'usr' => $userId
                    ]);
                }
            }
            $this->db->commit();
            return true;
        } catch (\Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }
}

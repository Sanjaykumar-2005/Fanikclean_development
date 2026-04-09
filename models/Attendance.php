<?php
require_once __DIR__ . '/../config/Database.php';

class Attendance {
    private $db;
    public function __construct() { $this->db = Database::connect(); }

    public function saveBulk($siteId, $date, $records, $userId) {
        $logPath = __DIR__ . '/../debug.log';
        file_put_contents($logPath, date('Y-m-d H:i:s') . " - ATTENDANCE SAVE: siteId=$siteId, date=$date, userId=$userId, records=" . json_encode($records) . "\n", FILE_APPEND);
        
        if (empty($records)) {
            file_put_contents($logPath, date('Y-m-d H:i:s') . " - ATTENDANCE SAVE: No records to save!\n", FILE_APPEND);
            return false;
        }

        $this->db->beginTransaction();
        try {
            $stmt = $this->db->prepare("
                INSERT INTO attendance (worker_id, site_id, attendance_date, status, ot_hours, updated_by)
                VALUES (:wid, :sid, :dt, :st, :ot, :usr)
                ON CONFLICT (worker_id, attendance_date) 
                DO UPDATE SET status = EXCLUDED.status, ot_hours = EXCLUDED.ot_hours, updated_by = EXCLUDED.updated_by
            ");
            $saved = 0;
            foreach ($records as $workerId => $recordData) {
                $status = $recordData['status'] ?? '';
                $ot = !empty($recordData['ot']) ? $recordData['ot'] : 0;
                
                if (in_array($status, ['p', 'a', 'h', 'off'])) {
                    $stmt->execute([
                        'wid' => $workerId, 'sid' => $siteId,
                        'dt' => $date, 'st' => $status, 'ot' => $ot, 'usr' => $userId
                    ]);
                    $saved++;
                    file_put_contents($logPath, date('Y-m-d H:i:s') . " - ATTENDANCE SAVE: Saved worker $workerId status=$status ot=$ot\n", FILE_APPEND);
                } else {
                    file_put_contents($logPath, date('Y-m-d H:i:s') . " - ATTENDANCE SAVE: Skipped worker $workerId, status='$status' not valid\n", FILE_APPEND);
                }
            }
            $this->db->commit();
            file_put_contents($logPath, date('Y-m-d H:i:s') . " - ATTENDANCE SAVE: Committed $saved records successfully\n", FILE_APPEND);
            return true;
        } catch (\Exception $e) {
            $this->db->rollBack();
            file_put_contents($logPath, date('Y-m-d H:i:s') . " - ATTENDANCE SAVE ERROR: " . $e->getMessage() . "\n", FILE_APPEND);
            return false;
        }
    }
}

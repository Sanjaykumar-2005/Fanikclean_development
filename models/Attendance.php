<?php
require_once __DIR__ . '/../config/Database.php';

class Attendance {
    private $db;
    public function __construct() { $this->db = Database::connect(); }

    /**
     * Save the attendance grid. Each record carries its own set of dates
     * (chosen per worker via the calendar picker): the worker's status / OT /
     * note is applied to every selected day. Each worker is stored under their
     * own site; when $allowedSiteIds is an array (managers), workers outside
     * that scope are skipped. Returns [savedRows, skipped].
     *
     * $records: [ workerId => ['status'=>, 'ot'=>, 'note'=>, 'dates'=>'Y-m-d,Y-m-d,...'] ]
     */
    public function saveGrid($records, $userId, $allowedSiteIds = null) {
        if (empty($records)) {
            return false;
        }

        // Resolve each worker's current site in one query.
        $workerIds = array_keys($records);
        $ph = implode(',', array_fill(0, count($workerIds), '?'));
        $mapStmt = $this->db->prepare("SELECT id, site_id FROM workers WHERE id IN ($ph)");
        $mapStmt->execute(array_values($workerIds));
        $siteMap = [];
        foreach ($mapStmt->fetchAll() as $row) {
            $siteMap[$row['id']] = $row['site_id'];
        }

        $valid = ['p', 'a', 'h', 'off', 'pl', 'sd'];
        $saved = 0;

        $this->db->beginTransaction();
        try {
            $stmt = $this->db->prepare("
                INSERT INTO attendance (worker_id, site_id, attendance_date, status, ot_hours, note, updated_by)
                VALUES (:wid, :sid, :dt, :st, :ot, :nt, :usr)
                ON CONFLICT (worker_id, attendance_date)
                DO UPDATE SET status = EXCLUDED.status, ot_hours = EXCLUDED.ot_hours, note = EXCLUDED.note, site_id = EXCLUDED.site_id, updated_by = EXCLUDED.updated_by
            ");
            foreach ($records as $workerId => $rec) {
                $status = $rec['status'] ?? '';
                if (!in_array($status, $valid)) { continue; }

                $siteId = $siteMap[$workerId] ?? null;
                if (!$siteId) { continue; } // unassigned worker — cannot record site-based attendance
                if ($allowedSiteIds !== null && !in_array($siteId, $allowedSiteIds)) { continue; } // out of manager scope

                $ot = !empty($rec['ot']) ? $rec['ot'] : 0;
                $note = isset($rec['note']) ? trim($rec['note']) : '';

                $dates = isset($rec['dates']) ? array_filter(explode(',', $rec['dates'])) : [];
                foreach ($dates as $dt) {
                    $dt = trim($dt);
                    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $dt)) { continue; }
                    $p = explode('-', $dt);
                    if (!checkdate((int)$p[1], (int)$p[2], (int)$p[0])) { continue; }

                    $stmt->execute([
                        'wid' => $workerId, 'sid' => $siteId, 'dt' => $dt,
                        'st' => $status, 'ot' => $ot,
                        'nt' => ($note === '' ? null : $note), 'usr' => $userId
                    ]);
                    $saved++;
                }
            }
            $this->db->commit();
            return $saved;
        } catch (\Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }
}

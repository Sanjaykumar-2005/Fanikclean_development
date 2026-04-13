<?php
require_once __DIR__ . '/../config/Database.php';

class Leave {
    private $db;
    public function __construct() { $this->db = Database::connect(); }

    public function getAll() {
        return $this->db->query("
            SELECT lr.*, w.full_name as worker_name, s.name as site_name
            FROM leave_requests lr
            JOIN workers w ON lr.worker_id = w.id
            LEFT JOIN sites s ON w.site_id = s.id
            ORDER BY lr.created_at DESC
        ")->fetchAll();
    }

    public function updateStatus($id, $status, $userId) {
        $stmt = $this->db->prepare("UPDATE leave_requests SET status = :s, reviewed_by = :u WHERE id = :id");
        return $stmt->execute(['s' => $status, 'u' => $userId, 'id' => $id]);
    }
}

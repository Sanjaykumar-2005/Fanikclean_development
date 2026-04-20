<?php
require_once __DIR__ . '/../config/Database.php';

class AuditController extends Controller {
    public function __construct() { $this->requireRole([1]); }

    public function index() {
        $db = Database::connect();
        $logs = $db->query("
            SELECT al.*, u.full_name as user_name
            FROM audit_logs al
            LEFT JOIN users u ON al.user_id = u.id
            ORDER BY al.timestamp DESC
            LIMIT 100
        ")->fetchAll();
        
        $this->view('audit/index', [
            'pageTitle' => 'System Audit Log',
            'logs' => $logs
        ]);
    }
}

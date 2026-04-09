<?php
require_once __DIR__ . '/../config/Database.php';

class Worker {
    private $db;
    public function __construct() { $this->db = Database::connect(); }

    public function getAll() {
        $stmt = $this->db->query("
            SELECT w.*, c.name as category_name, s.name as site_name 
            FROM workers w 
            LEFT JOIN worker_categories c ON w.category_id = c.id
            LEFT JOIN sites s ON w.site_id = s.id
            ORDER BY w.id DESC
        ");
        return $stmt->fetchAll();
    }

    public function create($data) {
        $stmt = $this->db->prepare("
            INSERT INTO workers (worker_code, full_name, mobile, aadhaar, doj, category_id, site_id, status) 
            VALUES (:wc, :fn, :mob, :aadh, :doj, :cat, :site, :status)
        ");
        return $stmt->execute([
            'wc' => 'W-' . rand(1000, 9999), // Generator logic
            'fn' => $data['full_name'],
            'mob' => $data['mobile'],
            'aadh' => $data['aadhaar'],
            'doj' => $data['doj'],
            'cat' => $data['category_id'],
            'site' => $data['site_id'],
            'status' => $data['status']
        ]);
    }

    public function update($id, $data) {
        $stmt = $this->db->prepare("
            UPDATE workers 
            SET full_name = :fn, 
                mobile = :mob, 
                aadhaar = :aadh, 
                doj = :doj, 
                category_id = :cat, 
                site_id = :site, 
                status = :status 
            WHERE id = :id
        ");
        return $stmt->execute([
            'id' => $id,
            'fn' => $data['full_name'],
            'mob' => $data['mobile'],
            'aadh' => $data['aadhaar'],
            'doj' => $data['doj'],
            'cat' => $data['category_id'],
            'site' => $data['site_id'],
            'status' => $data['status']
        ]);
    }
}

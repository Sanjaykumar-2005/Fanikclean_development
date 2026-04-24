<?php
require_once __DIR__ . '/../config/Database.php';

class Worker {
    private $db;
    public function __construct() { $this->db = Database::connect(); }

    public function getAll($siteIds = null) {
        $query = "
            SELECT w.*, c.name as category_name, s.name as site_name 
            FROM workers w 
            LEFT JOIN worker_categories c ON w.category_id = c.id
            LEFT JOIN sites s ON w.site_id = s.id
        ";
        
        $params = [];
        if (!empty($siteIds)) {
            if (is_array($siteIds)) {
                $placeholders = implode(',', array_fill(0, count($siteIds), '?'));
                $query .= " WHERE w.site_id IN ($placeholders) ";
                $params = $siteIds;
            } else {
                $query .= " WHERE w.site_id = ? ";
                $params = [$siteIds];
            }
        }
        
        $query .= " ORDER BY w.id DESC";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function getById($id) {
        $stmt = $this->db->prepare("
            SELECT w.*, c.name as category_name, s.name as site_name 
            FROM workers w 
            LEFT JOIN worker_categories c ON w.category_id = c.id
            LEFT JOIN sites s ON w.site_id = s.id
            WHERE w.id = :id
        ");
        $stmt->execute(['id' => $id]);
        $worker = $stmt->fetch();
        if ($worker) {
            $worker['assets'] = $this->getAssets($id);
        }
        return $worker;
    }

    public function getAssets($workerId) {
        $stmt = $this->db->prepare("SELECT * FROM worker_assets WHERE worker_id = :wid ORDER BY issue_date DESC");
        $stmt->execute(['wid' => $workerId]);
        return $stmt->fetchAll();
    }

    public function create($data) {
        $stmt = $this->db->prepare("
            INSERT INTO workers (
                worker_code, full_name, mobile, aadhaar, doj, category_id, site_id, status,
                esi_number, pf_number, photo_path, age, experience, uniform_issue_date, uniform_details
            ) 
            VALUES (
                :wc, :fn, :mob, :aadh, :doj, :cat, :site, :status,
                :esi, :pf, :photo, :age, :exp, :uid, :udet
            )
        ");
        return $stmt->execute([
            'wc' => 'W-' . rand(1000, 9999), 
            'fn' => $data['full_name'],
            'mob' => $data['mobile'],
            'aadh' => $data['aadhaar'],
            'doj' => $data['doj'],
            'cat' => $data['category_id'],
            'site' => $data['site_id'],
            'status' => $data['status'],
            'esi' => $data['esi_number'] ?? null,
            'pf' => $data['pf_number'] ?? null,
            'photo' => $data['photo_path'] ?? null,
            'age' => !empty($data['age']) ? $data['age'] : null,
            'exp' => $data['experience'] ?? null,
            'uid' => !empty($data['uniform_issue_date']) ? $data['uniform_issue_date'] : null,
            'udet' => $data['uniform_details'] ?? null
        ]);
    }

    public function update($id, $data) {
        $sql = "
            UPDATE workers 
            SET full_name = :fn, 
                mobile = :mob, 
                aadhaar = :aadh, 
                doj = :doj, 
                category_id = :cat, 
                site_id = :site, 
                status = :status,
                esi_number = :esi,
                pf_number = :pf,
                age = :age,
                experience = :exp,
                uniform_issue_date = :uid,
                uniform_details = :udet
        ";
        
        $params = [
            'id' => $id,
            'fn' => $data['full_name'],
            'mob' => $data['mobile'],
            'aadh' => $data['aadhaar'],
            'doj' => $data['doj'],
            'cat' => $data['category_id'],
            'site' => $data['site_id'],
            'status' => $data['status'],
            'esi' => $data['esi_number'] ?? null,
            'pf' => $data['pf_number'] ?? null,
            'age' => !empty($data['age']) ? $data['age'] : null,
            'exp' => $data['experience'] ?? null,
            'uid' => !empty($data['uniform_issue_date']) ? $data['uniform_issue_date'] : null,
            'udet' => $data['uniform_details'] ?? null
        ];

        if (isset($data['photo_path'])) {
            $sql .= ", photo_path = :photo";
            $params['photo'] = $data['photo_path'];
        }

        $sql .= " WHERE id = :id";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

    public function bulkUpdateSite($workerIds, $siteId) {
        if (empty($workerIds) || !is_array($workerIds)) return false;
        
        $placeholders = implode(',', array_fill(0, count($workerIds), '?'));
        
        $stmt = $this->db->prepare("UPDATE workers SET site_id = ? WHERE id IN ($placeholders)");
        $params = array_merge([$siteId], $workerIds);
        
        return $stmt->execute($params);
    }

    public function bulkUpdateUniform($workerIds, $details, $issueDate) {
        if (empty($workerIds) || !is_array($workerIds)) return false;
        
        $placeholders = implode(',', array_fill(0, count($workerIds), '?'));
        
        $stmt = $this->db->prepare("UPDATE workers SET uniform_details = ?, uniform_issue_date = ? WHERE id IN ($placeholders)");
        $params = array_merge([$details, $issueDate], $workerIds);
        
        return $stmt->execute($params);
    }
}

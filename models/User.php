<?php
require_once __DIR__ . '/../config/Database.php';

class User {
    private $db;

    public function __construct() {
        $this->db = Database::connect();
    }

    public function findByEmail($email) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        return $stmt->fetch();
    }

    public function create($fullName, $email, $passwordHash, $roleId = 2) {
        $stmt = $this->db->prepare("INSERT INTO users (full_name, email, password_hash, role_id) VALUES (:f, :e, :p, :r)");
        return $stmt->execute(['f' => $fullName, 'e' => $email, 'p' => $passwordHash, 'r' => $roleId]);
    }

    public function getAll() {
        return $this->db->query("
            SELECT u.*, r.name as role_name, s.name as site_name 
            FROM users u 
            LEFT JOIN roles r ON u.role_id = r.id 
            LEFT JOIN sites s ON u.site_id = s.id 
            ORDER BY u.id DESC
        ")->fetchAll();
    }

    public function update($id, $data) {
        $stmt = $this->db->prepare("
            UPDATE users 
            SET full_name = :fn, 
                role_id = :rid, 
                site_id = :sid, 
                status = :status 
            WHERE id = :id
        ");
        return $stmt->execute([
            'id' => $id,
            'fn' => $data['full_name'],
            'rid' => $data['role_id'],
            'sid' => !empty($data['site_id']) ? $data['site_id'] : null,
            'status' => $data['status']
        ]);
    }
}

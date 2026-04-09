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
}

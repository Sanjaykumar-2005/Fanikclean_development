<?php
require_once __DIR__ . '/../config/Database.php';

class InvoiceTemplate {
    private $db;
    public function __construct() { $this->db = Database::connect(); }

    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM invoice_templates ORDER BY name");
        return $stmt->fetchAll();
    }

    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM invoice_templates WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function getBySlug($slug) {
        $stmt = $this->db->prepare("SELECT * FROM invoice_templates WHERE slug = :slug");
        $stmt->execute(['slug' => $slug]);
        return $stmt->fetch();
    }
}

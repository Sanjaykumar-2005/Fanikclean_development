<?php
require_once __DIR__ . '/../config/Database.php';

class Client {
    private $db;
    public function __construct() { $this->db = Database::connect(); }

    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM clients ORDER BY id DESC");
        return $stmt->fetchAll();
    }

    public function create($data) {
        $stmt = $this->db->prepare("
            INSERT INTO clients (company_name, contact_person, mobile, email, gstin, address, contract_start, contract_end, billing_cycle) 
            VALUES (:cn, :cp, :m, :e, :gst, :addr, :cs, :ce, :bc)
        ");
        return $stmt->execute([
            'cn' => $data['company_name'],
            'cp' => $data['contact_person'],
            'm' => $data['mobile'],
            'e' => $data['email'],
            'gst' => $data['gstin'],
            'addr' => $data['address'],
            'cs' => $data['contract_start'],
            'ce' => $data['contract_end'],
            'bc' => $data['billing_cycle']
        ]);
    }
}

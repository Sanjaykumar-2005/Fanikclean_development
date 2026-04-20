<?php
require_once __DIR__ . '/../config/Database.php';
try {
    $db = Database::connect();
    $stmt = $db->query("SELECT email, role_id FROM users LIMIT 10");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($users, JSON_PRETTY_PRINT);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

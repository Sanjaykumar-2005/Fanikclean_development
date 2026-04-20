<?php
require_once __DIR__ . '/../config/Database.php';
try {
    $db = Database::connect();
    $email = 'manager@test.com';
    $hash = password_hash('password123', PASSWORD_DEFAULT);
    $stmt = $db->prepare("UPDATE users SET password_hash = :hash WHERE email = :email");
    $stmt->execute(['hash' => $hash, 'email' => $email]);
    echo "Password reset for $email to 'password123'";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

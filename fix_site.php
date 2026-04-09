<?php
require_once __DIR__ . '/config/Database.php';
$db = Database::connect();
header('Content-Type: text/plain; charset=utf-8');

// Fix Google HQ Bangalore (site 7) to point to Google India (client 5)
$stmt = $db->query("SELECT id, name, client_id FROM sites WHERE name ILIKE '%google%'");
$sites = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo "Google-related sites:\n";
foreach ($sites as $s) {
    echo "  SiteID: {$s['id']} | Name: {$s['name']} | ClientID: {$s['client_id']}\n";
}

// Get Google India client ID
$gc = $db->query("SELECT id FROM clients WHERE company_name ILIKE '%google%'")->fetch(PDO::FETCH_ASSOC);
$googleClientId = $gc['id'];
echo "\nGoogle India ClientID: $googleClientId\n";

// Fix ALL Google sites to point to Google India
$fix = $db->prepare("UPDATE sites SET client_id = :cid WHERE name ILIKE '%google%'");
$fix->execute(['cid' => $googleClientId]);
echo "Fixed all Google sites to client_id=$googleClientId\n";

// Re-run billing
require_once __DIR__ . '/models/Billing.php';
$billing = new Billing();
$billing->generateMonthly('2026-04');
echo "Billing re-generated for 2026-04\n";

// Show final state
echo "\n--- Final Billing ---\n";
$bills = $db->query("SELECT b.*, c.company_name FROM billing b JOIN clients c ON b.client_id = c.id WHERE b.month_year='2026-04' ORDER BY b.id DESC")->fetchAll(PDO::FETCH_ASSOC);
foreach ($bills as $b) {
    echo "  Client: {$b['company_name']} | Grand: ₹{$b['grand_total']} | Status: {$b['status']}\n";
}
echo "\nDONE. Now go to /invoices to generate the Google India invoice.\n";

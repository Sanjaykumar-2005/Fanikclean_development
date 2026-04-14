<?php
require_once __DIR__ . '/config/Database.php';
$db = Database::connect();
header('Content-Type: text/plain; charset=utf-8');

echo "========== 1. ALL CLIENTS ==========\n";
$clients = $db->query("SELECT * FROM clients ORDER BY id")->fetchAll(PDO::FETCH_ASSOC);
foreach ($clients as $c) {
    echo "  ID: {$c['id']} | Company: {$c['company_name']} | Contact: " . ($c['contact_person'] ?? '-') . "\n";
}

echo "\n========== 2. ALL SITES ==========\n";
$sites = $db->query("SELECT s.*, c.company_name FROM sites s LEFT JOIN clients c ON s.client_id = c.id ORDER BY s.id")->fetchAll(PDO::FETCH_ASSOC);
foreach ($sites as $s) {
    echo "  SiteID: {$s['id']} | Name: {$s['name']} | ClientID: " . ($s['client_id'] ?? 'NULL') . " | Client: " . ($s['company_name'] ?? 'UNLINKED') . "\n";
}

echo "\n========== 3. ALL WORKERS (with site & client) ==========\n";
$workers = $db->query("
    SELECT w.id, w.full_name, w.site_id, s.name as site_name, s.client_id, c.company_name
    FROM workers w
    LEFT JOIN sites s ON w.site_id = s.id
    LEFT JOIN clients c ON s.client_id = c.id
    ORDER BY w.id DESC
")->fetchAll(PDO::FETCH_ASSOC);
foreach ($workers as $w) {
    $clientInfo = $w['company_name'] ?? 'NO CLIENT LINKED';
    echo "  WorkerID: {$w['id']} | Name: {$w['full_name']} | SiteID: " . ($w['site_id'] ?? 'NULL') . " | Site: " . ($w['site_name'] ?? '-') . " | ClientID: " . ($w['client_id'] ?? 'NULL') . " | Client: {$clientInfo}\n";
}

echo "\n========== 4. ATTENDANCE (April 2026) ==========\n";
$att = $db->query("
    SELECT a.*, w.full_name, s.name as site_name, s.client_id, c.company_name
    FROM attendance a
    JOIN workers w ON a.worker_id = w.id
    LEFT JOIN sites s ON a.site_id = s.id
    LEFT JOIN clients c ON s.client_id = c.id
    WHERE TO_CHAR(a.attendance_date, 'YYYY-MM') = '2026-04'
    ORDER BY a.attendance_date DESC
")->fetchAll(PDO::FETCH_ASSOC);
if (empty($att)) {
    echo "  NO ATTENDANCE RECORDS FOR APRIL 2026\n";
} else {
    foreach ($att as $a) {
        echo "  Worker: {$a['full_name']} | Date: {$a['attendance_date']} | Status: {$a['status']} | OT: " . ($a['ot_hours'] ?? 0) . " | Site: " . ($a['site_name'] ?? '-') . " | Client: " . ($a['company_name'] ?? 'NULL') . "\n";
    }
}

echo "\n========== 5. BILLING TABLE ==========\n";
$bills = $db->query("SELECT b.*, c.company_name FROM billing b JOIN clients c ON b.client_id = c.id ORDER BY b.id DESC")->fetchAll(PDO::FETCH_ASSOC);
if (empty($bills)) {
    echo "  NO BILLING RECORDS\n";
} else {
    foreach ($bills as $b) {
        echo "  BillingID: {$b['id']} | Client: {$b['company_name']} | Month: {$b['month_year']} | Subtotal: {$b['subtotal']} | Grand: {$b['grand_total']} | Status: {$b['status']}\n";
    }
}

echo "\n========== 6. INVOICES TABLE ==========\n";
$invs = $db->query("SELECT i.*, b.month_year, c.company_name FROM invoices i JOIN billing b ON i.billing_id = b.id JOIN clients c ON b.client_id = c.id ORDER BY i.id DESC")->fetchAll(PDO::FETCH_ASSOC);
if (empty($invs)) {
    echo "  NO INVOICES\n";
} else {
    foreach ($invs as $i) {
        echo "  InvNo: {$i['invoice_no']} | Client: {$i['company_name']} | Month: {$i['month_year']} | Amount: {$i['amount']} | Status: {$i['status']}\n";
    }
}

echo "\n========== DIAGNOSIS ==========\n";
// Check for sites with NULL client_id
$orphanSites = $db->query("SELECT * FROM sites WHERE client_id IS NULL")->fetchAll(PDO::FETCH_ASSOC);
if (!empty($orphanSites)) {
    echo "  WARNING: Sites with NO client linked:\n";
    foreach ($orphanSites as $os) {
        echo "    SiteID: {$os['id']} | Name: {$os['name']}\n";
    }
}

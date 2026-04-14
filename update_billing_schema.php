<?php
require_once 'config/Database.php';
$db = Database::connect();

try {
    // 1. Add site_id column
    $db->exec("ALTER TABLE billing ADD COLUMN IF NOT EXISTS site_id INT REFERENCES sites(id)");
    
    // 2. Drop old unique constraint
    $db->exec("ALTER TABLE billing DROP CONSTRAINT IF EXISTS billing_client_id_month_year_key");
    
    // 3. Add new unique constraint
    // Note: If there are existing records with NULL site_id, this might cause issues if we try to make it NOT NULL later.
    // For now, we allow NULL site_id for old records, but new ones will have it.
    $db->exec("ALTER TABLE billing ADD CONSTRAINT billing_client_site_month_unique UNIQUE (client_id, site_id, month_year)");
    
    echo "Database schema updated for site-specific billing.\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>

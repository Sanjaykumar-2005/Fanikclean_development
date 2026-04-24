<?php
require_once __DIR__ . '/config/Database.php';
$db = Database::connect();

try {
    // Add from_date and to_date columns to billing table
    $db->exec("ALTER TABLE billing ADD COLUMN IF NOT EXISTS from_date DATE");
    $db->exec("ALTER TABLE billing ADD COLUMN IF NOT EXISTS to_date DATE");
    
    // Drop the old unique constraint on (client_id, site_id, month_year) 
    // and add a new one on (client_id, site_id, from_date, to_date)
    $db->exec("ALTER TABLE billing DROP CONSTRAINT IF EXISTS billing_client_id_site_id_month_year_key");
    $db->exec("DO $$ BEGIN
        IF NOT EXISTS (
            SELECT 1 FROM pg_constraint WHERE conname = 'billing_client_site_dates_key'
        ) THEN
            ALTER TABLE billing ADD CONSTRAINT billing_client_site_dates_key UNIQUE(client_id, site_id, from_date, to_date);
        END IF;
    END $$;");
    
    // Backfill existing rows: convert month_year to date range
    $db->exec("UPDATE billing SET from_date = (month_year || '-01')::DATE, to_date = ((month_year || '-01')::DATE + INTERVAL '1 month' - INTERVAL '1 day')::DATE WHERE from_date IS NULL");
    
    echo "Migration successful!\n";
} catch (Exception $e) {
    echo "Migration failed: " . $e->getMessage() . "\n";
}

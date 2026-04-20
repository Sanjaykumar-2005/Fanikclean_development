-- FanikClean ERP Upgrade Migration v2

-- 1. Worker Module Enhancements (New Fields)
ALTER TABLE workers ADD COLUMN IF NOT EXISTS esi_number VARCHAR(50);
ALTER TABLE workers ADD COLUMN IF NOT EXISTS pf_number VARCHAR(50);
ALTER TABLE workers ADD COLUMN IF NOT EXISTS photo_path VARCHAR(255);
ALTER TABLE workers ADD COLUMN IF NOT EXISTS age INT;
ALTER TABLE workers ADD COLUMN IF NOT EXISTS experience VARCHAR(100);
ALTER TABLE workers ADD COLUMN IF NOT EXISTS uniform_issue_date DATE;
ALTER TABLE workers ADD COLUMN IF NOT EXISTS uniform_details TEXT;

-- 2. Manager-Site Mapping (Multi-Site Support)
CREATE TABLE IF NOT EXISTS user_site_assignments (
    id SERIAL PRIMARY KEY,
    user_id INT REFERENCES users(id) ON DELETE CASCADE,
    site_id INT REFERENCES sites(id) ON DELETE CASCADE,
    assigned_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE(user_id, site_id)
);

-- 3. Manager Attendance (Separate from Workers)
CREATE TABLE IF NOT EXISTS manager_attendance (
    id SERIAL PRIMARY KEY,
    user_id INT REFERENCES users(id) ON DELETE CASCADE,
    attendance_date DATE NOT NULL,
    status VARCHAR(10) CHECK (status IN ('p', 'a', 'h', 'off')) NOT NULL,
    note TEXT,
    updated_by INT REFERENCES users(id),
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE(user_id, attendance_date)
);

-- 4. Invoice Templates
CREATE TABLE IF NOT EXISTS invoice_templates (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) UNIQUE NOT NULL, -- matches view filenames: standard, modern
    is_default BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert Default Templates
INSERT INTO invoice_templates (name, slug, is_default) VALUES 
('Standard Minimal', 'standard', TRUE),
('Modern Vibrant', 'modern', FALSE)
ON CONFLICT (slug) DO UPDATE SET name = EXCLUDED.name, is_default = EXCLUDED.is_default;

-- 5. Billing Table Update (Add Site ID and update unique constraint)
-- Incorporating logic from update_billing_schema.php
ALTER TABLE billing ADD COLUMN IF NOT EXISTS site_id INT REFERENCES sites(id);

-- Drop old unique constraint if it exists
DO $$ 
BEGIN 
    IF EXISTS (SELECT 1 FROM pg_constraint WHERE conname = 'billing_client_id_month_year_key') THEN
        ALTER TABLE billing DROP CONSTRAINT billing_client_id_month_year_key;
    END IF;
END $$;

-- Add new unique constraint (Idempotent check)
DO $$ 
BEGIN 
    IF NOT EXISTS (SELECT 1 FROM pg_constraint WHERE conname = 'billing_client_site_month_unique') THEN
        ALTER TABLE billing ADD CONSTRAINT billing_client_site_month_unique UNIQUE (client_id, site_id, month_year);
    END IF;
END $$;

-- 6. Add template_id to invoices
ALTER TABLE invoices ADD COLUMN IF NOT EXISTS template_id INT REFERENCES invoice_templates(id);

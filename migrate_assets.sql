-- FanikClean ERP Asset Management Migration
-- Transitioning from single-field uniform tracking to a multi-item asset system.

CREATE TABLE IF NOT EXISTS worker_assets (
    id SERIAL PRIMARY KEY,
    worker_id INT REFERENCES workers(id) ON DELETE CASCADE,
    item_name VARCHAR(150) NOT NULL,
    issue_date DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Migrate existing data from 'workers' table
INSERT INTO worker_assets (worker_id, item_name, issue_date)
SELECT id, uniform_details, uniform_issue_date 
FROM workers 
WHERE 
    uniform_details IS NOT NULL 
    AND uniform_details != '' 
    AND uniform_issue_date IS NOT NULL
ON CONFLICT DO NOTHING;

-- We keep the original columns in 'workers' table for now to avoid breaking existing queries, 
-- but we will favor 'worker_assets' for new data.

-- Database Update Script: Add Guardian Details
-- Run this script to update an existing FanikClean database.

-- 1. Add guardian columns to the `workers` table
ALTER TABLE workers ADD COLUMN IF NOT EXISTS guardian_name VARCHAR(100);
ALTER TABLE workers ADD COLUMN IF NOT EXISTS guardian_phone VARCHAR(15);
ALTER TABLE workers ADD COLUMN IF NOT EXISTS guardian_place VARCHAR(150);

-- 2. Add guardian columns to the `users` table
ALTER TABLE users ADD COLUMN IF NOT EXISTS guardian_name VARCHAR(100);
ALTER TABLE users ADD COLUMN IF NOT EXISTS guardian_phone VARCHAR(15);
ALTER TABLE users ADD COLUMN IF NOT EXISTS guardian_place VARCHAR(150);

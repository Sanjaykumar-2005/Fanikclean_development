-- FanikClean ERP Upgrade Migration v3
-- 1. Update Attendance Status Constraints
-- Including 'pl' (Paid Leave) and 'sd' (Special Duty)
-- Keeping 'a' and 'p', 'h', 'off' for compatibility, but UI will focus on requested five.

-- Workers Attendance
ALTER TABLE attendance DROP CONSTRAINT IF EXISTS attendance_status_check;
ALTER TABLE attendance ADD CONSTRAINT attendance_status_check CHECK (status IN ('p', 'a', 'h', 'off', 'pl', 'sd'));

-- Manager Attendance
ALTER TABLE manager_attendance DROP CONSTRAINT IF EXISTS manager_attendance_status_check;
ALTER TABLE manager_attendance ADD CONSTRAINT manager_attendance_status_check CHECK (status IN ('p', 'a', 'h', 'off', 'pl', 'sd'));

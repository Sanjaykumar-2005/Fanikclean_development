-- PostgreSQL Database Schema for FANIKCLEAN

CREATE TABLE roles (
    id SERIAL PRIMARY KEY,
    name VARCHAR(50) UNIQUE NOT NULL
);

INSERT INTO roles (name) VALUES ('Admin'), ('Manager');

CREATE TABLE clients (
    id SERIAL PRIMARY KEY,
    company_name VARCHAR(150) NOT NULL,
    contact_person VARCHAR(100),
    mobile VARCHAR(15),
    email VARCHAR(100),
    gstin VARCHAR(50),
    address TEXT,
    contract_start DATE,
    contract_end DATE,
    billing_cycle VARCHAR(20) DEFAULT 'Monthly',
    status VARCHAR(20) DEFAULT 'Active' CHECK (status IN ('Active', 'Inactive', 'Renewal Due'))
);

CREATE TABLE sites (
    id SERIAL PRIMARY KEY,
    client_id INT REFERENCES clients(id) ON DELETE CASCADE,
    name VARCHAR(150) NOT NULL,
    address TEXT
);

CREATE TABLE users (
    id SERIAL PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    role_id INT REFERENCES roles(id),
    site_id INT REFERENCES sites(id) ON DELETE SET NULL, -- Null means All Sites (Admin)
    phone VARCHAR(15),
    status VARCHAR(20) DEFAULT 'Active' CHECK (status IN ('Active', 'Inactive')),
    last_login TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE worker_categories (
    id SERIAL PRIMARY KEY,
    name VARCHAR(50) UNIQUE NOT NULL,
    default_rate NUMERIC(10, 2) NOT NULL
);

INSERT INTO worker_categories (name, default_rate) VALUES 
('Supervisor', 700.00),
('Associate', 500.00),
('Skilled', 600.00),
('Helper', 380.00);

CREATE TABLE workers (
    id SERIAL PRIMARY KEY,
    worker_code VARCHAR(20) UNIQUE NOT NULL, -- e.g. W-001
    full_name VARCHAR(100) NOT NULL,
    mobile VARCHAR(15),
    aadhaar VARCHAR(20),
    doj DATE,
    category_id INT REFERENCES worker_categories(id),
    site_id INT REFERENCES sites(id) ON DELETE SET NULL,
    daily_rate_override NUMERIC(10, 2), -- Null means use rate_master/category default
    status VARCHAR(20) DEFAULT 'Active' CHECK (status IN ('Active', 'Inactive', 'Removed')),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE rate_master (
    id SERIAL PRIMARY KEY,
    client_id INT REFERENCES clients(id) ON DELETE CASCADE,
    category_id INT REFERENCES worker_categories(id),
    rate_per_day NUMERIC(10, 2) NOT NULL,
    UNIQUE(client_id, category_id)
);

CREATE TABLE attendance (
    id SERIAL PRIMARY KEY,
    worker_id INT REFERENCES workers(id) ON DELETE CASCADE,
    site_id INT REFERENCES sites(id) ON DELETE CASCADE,
    attendance_date DATE NOT NULL,
    status VARCHAR(10) CHECK (status IN ('p', 'a', 'h', 'off')) NOT NULL, -- p: present, a: absent, h: half
    ot_hours NUMERIC(5, 2) DEFAULT 0,
    note TEXT,
    locked BOOLEAN DEFAULT FALSE,
    updated_by INT REFERENCES users(id),
    UNIQUE(worker_id, attendance_date)
);

CREATE TABLE leave_requests (
    id SERIAL PRIMARY KEY,
    worker_id INT REFERENCES workers(id) ON DELETE CASCADE,
    from_date DATE NOT NULL,
    to_date DATE NOT NULL,
    leave_type VARCHAR(50) CHECK (leave_type IN ('Personal', 'Medical', 'Family Emergency', 'Weekly Off')),
    reason TEXT,
    status VARCHAR(20) DEFAULT 'Pending' CHECK (status IN ('Pending', 'Approved', 'Rejected')),
    reviewed_by INT REFERENCES users(id),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE billing (
    id SERIAL PRIMARY KEY,
    client_id INT REFERENCES clients(id) ON DELETE CASCADE,
    month_year VARCHAR(7) NOT NULL, -- e.g. 2026-03
    subtotal NUMERIC(12, 2) NOT NULL,
    cgst NUMERIC(12, 2) NOT NULL,
    sgst NUMERIC(12, 2) NOT NULL,
    grand_total NUMERIC(12, 2) NOT NULL,
    status VARCHAR(20) DEFAULT 'Pending' CHECK (status IN ('Pending', 'Approved', 'Invoiced')),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE(client_id, month_year)
);

CREATE TABLE invoices (
    id SERIAL PRIMARY KEY,
    billing_id INT REFERENCES billing(id) UNIQUE,
    invoice_no VARCHAR(50) UNIQUE NOT NULL,
    issue_date DATE NOT NULL,
    due_date DATE,
    amount NUMERIC(12, 2) NOT NULL,
    status VARCHAR(20) DEFAULT 'Unpaid' CHECK (status IN ('Unpaid', 'Pending', 'Paid')),
    payment_date DATE,
    payment_mode VARCHAR(50),
    payment_ref VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE payroll (
    id SERIAL PRIMARY KEY,
    worker_id INT REFERENCES workers(id) ON DELETE CASCADE,
    month_year VARCHAR(7) NOT NULL, -- e.g. 2026-03
    days_worked NUMERIC(5, 2) NOT NULL,
    basic_pay NUMERIC(10, 2) NOT NULL,
    ot_days NUMERIC(5, 2) DEFAULT 0,
    ot_pay NUMERIC(10, 2) DEFAULT 0,
    advance_deduction NUMERIC(10, 2) DEFAULT 0,
    net_pay NUMERIC(10, 2) NOT NULL,
    status VARCHAR(20) DEFAULT 'Pending' CHECK (status IN ('Pending', 'Approved', 'Paid')),
    UNIQUE(worker_id, month_year)
);

CREATE TABLE audit_logs (
    id SERIAL PRIMARY KEY,
    user_id INT REFERENCES users(id) ON DELETE SET NULL,
    action TEXT NOT NULL,
    module VARCHAR(50) NOT NULL, -- Attendance, Billing, Config, Users, Workers, Leave
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

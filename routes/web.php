<?php
// Auth Routes
$router->get('/login', 'AuthController', 'login');
$router->post('/login', 'AuthController', 'authenticate');
$router->get('/signup', 'AuthController', 'signup');
$router->post('/signup', 'AuthController', 'register');
$router->get('/logout', 'AuthController', 'logout');

// Dashboard
$router->get('/', 'DashboardController', 'index');
$router->get('/dashboard', 'DashboardController', 'index');

// Workers
$router->get('/workers', 'WorkerController', 'index');
$router->post('/workers/create', 'WorkerController', 'create');
$router->post('/workers/update', 'WorkerController', 'update');

// Clients
$router->get('/clients', 'ClientController', 'index');
$router->post('/clients/create', 'ClientController', 'create');

// Attendance
$router->get('/attendance', 'AttendanceController', 'index');
$router->post('/attendance/save', 'AttendanceController', 'saveBulk');

// Leave
$router->get('/leave', 'LeaveController', 'index');
$router->post('/leave/create', 'LeaveController', 'create');
$router->post('/leave/approve', 'LeaveController', 'approve');

// Billing & Payroll
$router->get('/billing', 'BillingController', 'index');
$router->post('/billing/generate', 'BillingController', 'generate');
$router->get('/invoices', 'InvoiceController', 'index');
$router->get('/payroll', 'PayrollController', 'index');
$router->post('/payroll/approve', 'PayrollController', 'approve');

// Sites
$router->post('/sites/create', 'SiteController', 'create');

// API Endpoints
$router->get('/api/workers', 'WorkerController', 'apiGetBySite');

// Invoices upgrade
$router->post('/invoices/generate', 'InvoiceController', 'generate');
$router->post('/invoices/pay', 'InvoiceController', 'pay');
$router->get('/invoices/print', 'InvoiceController', 'print');

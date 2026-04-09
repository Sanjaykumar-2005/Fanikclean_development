<!-- ============================================================ SIDEBAR -->
<aside class="sidebar">
  <div class="logo-area">
    <div class="logo-wrap">
      <div class="logo-icon">FC</div>
      <div>
        <div class="logo-name">FanikClean</div>
        <div class="logo-sub">Management System</div>
      </div>
    </div>
  </div>

  <div class="nav-section">
    <div class="nav-sec-label">Overview</div>
    <a href="/dashboard" class="nav-item <?= strpos($_SERVER['REQUEST_URI'], 'dashboard') !== false ? 'active' : '' ?>">Dashboard</a>
  </div>

  <div class="nav-section">
    <div class="nav-sec-label">People</div>
    <a href="/workers" class="nav-item <?= strpos($_SERVER['REQUEST_URI'], 'workers') !== false ? 'active' : '' ?>">Workers</a>
    <a href="/clients" class="nav-item <?= strpos($_SERVER['REQUEST_URI'], 'clients') !== false ? 'active' : '' ?>">Clients & Sites</a>
  </div>

  <div class="nav-section">
    <div class="nav-sec-label">Operations</div>
    <a href="/attendance" class="nav-item <?= strpos($_SERVER['REQUEST_URI'], 'attendance') !== false ? 'active' : '' ?>">Attendance</a>
    <a href="/leave" class="nav-item <?= strpos($_SERVER['REQUEST_URI'], 'leave') !== false ? 'active' : '' ?>">Leave Management</a>
  </div>

  <div class="nav-section">
    <div class="nav-sec-label">Finance</div>
    <a href="/payroll" class="nav-item <?= strpos($_SERVER['REQUEST_URI'], 'payroll') !== false ? 'active' : '' ?>">Payroll</a>
    <a href="/billing" class="nav-item <?= strpos($_SERVER['REQUEST_URI'], 'billing') !== false ? 'active' : '' ?>">Billing Engine</a>
    <a href="/invoices" class="nav-item <?= strpos($_SERVER['REQUEST_URI'], 'invoices') !== false ? 'active' : '' ?>">Invoices</a>
  </div>

  <div class="sidebar-footer">
    <a href="/logout" style="color:var(--red); text-decoration:none;">Logout</a>
  </div>
</aside>

<!-- ============================================================ MAIN -->
<div class="main">
  <!-- TOPBAR -->
  <header class="topbar">
    <div class="topbar-left">
      <div class="page-bc" id="page-bc">Module</div>
      <div class="page-ttl" id="page-ttl"><?= htmlspecialchars($pageTitle ?? 'Dashboard') ?></div>
    </div>
    <div class="topbar-right">
      <span class="role-chip"><?= $_SESSION['role_id'] == 1 ? 'Admin' : 'Manager'; ?></span>
      <div class="avatar" title="User"><?= strtoupper(substr($_SESSION['user_name'] ?? 'U', 0, 2)) ?></div>
    </div>
  </header>

  <!-- CONTENT -->
  <div class="content">
    <?php if(isset($_SESSION['toast'])): ?>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                toast("<?= $_SESSION['toast']; ?>", "success");
            });
        </script>
        <?php unset($_SESSION['toast']); ?>
    <?php endif; ?>

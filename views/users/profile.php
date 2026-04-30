<?php
// Layout is already provided by Controller.php (header and footer)
?>

<div class="panel active">
  <div class="sec-head">
    <div class="flex items-center gap16">
      <a href="/users" class="btn btn-sm btn-icon">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
      </a>
      <div class="sec-meta">System User ID: <span class="mono bold c-primary">SYS-<?= str_pad($user['id'], 4, '0', STR_PAD_LEFT) ?></span></div>
    </div>
    <div class="flex gap12">
      <button class="btn btn-sm" onclick="history.back()">
        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 4px;"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
        Edit Information
      </button>
      <button class="btn btn-primary btn-sm" onclick="window.print()">
        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 4px;"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" x2="12" y1="15" y2="3"/></svg>
        Export PDF
      </button>
    </div>
  </div>

  <div class="grid grid-cols-12 gap24">
    <!-- LEFT COLUMN: PHOTO & PRIMARY -->
    <div class="col-span-12 md:col-span-4 lg:col-span-3">
      <div class="card p24 flex flex-col items-center">
        <div class="profile-photo-wrap mb24">
            <div style="width: 150px; height: 150px; border-radius: 50%; background: var(--gray-lighter); display: flex; align-items: center; justify-content: center; border: 4px solid var(--gray-light);">
              <svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="color: var(--text-muted);"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            </div>
        </div>
        <div class="text-center">
          <h2 class="fs20 mb4"><?= htmlspecialchars($user['full_name']) ?></h2>
          <div class="chip b-indigo mb16"><?= htmlspecialchars($user['role_name']) ?></div>
          <p class="c-secondary fs13 mb24">Registered on <?= date('d M Y', strtotime($user['created_at'])) ?></p>
          
          <div class="w-full pt16 border-t flex flex-col gap12">
            <div class="flex justify-between fs13">
              <span class="c-secondary">Status</span>
              <span class="badge <?= $user['status'] == 'Active' ? 'b-green' : 'b-red' ?>"><?= $user['status'] ?></span>
            </div>
            <div class="flex justify-between fs13">
              <span class="c-secondary">Email</span>
              <span class="bold"><?= htmlspecialchars($user['email']) ?></span>
            </div>
            <div class="flex justify-between fs13">
              <span class="c-secondary">Phone</span>
              <span class="bold"><?= htmlspecialchars($user['phone'] ?? 'Not set') ?></span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- RIGHT COLUMN: DETAILED INFO -->
    <div class="col-span-12 md:col-span-8 lg:col-span-9">
      <div class="grid gap24">
        
        <!-- ROW 1: EMPLOYMENT & STATUTORY -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap24">
          <div class="card p24">
            <h3 class="fs14 fw6 mb20 flex items-center gap8">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="c-primary"><rect width="20" height="14" x="2" y="7" rx="2" ry="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg>
              System Alignment
            </h3>
            <div class="grid gap16">
              <div class="flex flex-col gap4">
                <span class="fs11 fw5 c-secondary uppercase">Assigned Site Scope</span>
                <span class="bold c-teal flex items-center gap4">
                  <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                  <?= htmlspecialchars($user['site_name'] ?? 'Universal (All Sites)') ?>
                </span>
              </div>
              <div class="flex flex-col gap4">
                <span class="fs11 fw5 c-secondary uppercase">Account Status</span>
                <span class="bold"><?= $user['status'] === 'Active' ? 'Active / Permitted' : 'Suspended' ?></span>
              </div>
              <div class="flex flex-col gap4">
                <span class="fs11 fw5 c-secondary uppercase">Last Login</span>
                <span class="bold"><?= $user['last_login'] ? date('d M Y, h:i A', strtotime($user['last_login'])) : 'Never Logged In' ?></span>
              </div>
            </div>
          </div>

          <div class="card p24">
            <h3 class="fs14 fw6 mb20 flex items-center gap8">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="c-red"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
              Emergency / Guardian Details
            </h3>
            <div class="grid gap16">
              <div class="flex flex-col gap4">
                <span class="fs11 fw5 c-secondary uppercase">Guardian Name</span>
                <span class="bold"><?= htmlspecialchars($user['guardian_name'] ?? 'Not Provided') ?></span>
              </div>
              <div class="flex flex-col gap4">
                <span class="fs11 fw5 c-secondary uppercase">Guardian Phone</span>
                <span class="bold"><?= htmlspecialchars($user['guardian_phone'] ?? 'Not Provided') ?></span>
              </div>
              <div class="flex flex-col gap4">
                <span class="fs11 fw5 c-secondary uppercase">Guardian Place</span>
                <span class="bold"><?= htmlspecialchars($user['guardian_place'] ?? 'Not Provided') ?></span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<style>
.profile-photo-wrap {
  position: relative;
  transition: transform 0.3s ease;
}
.profile-photo-wrap:hover {
  transform: scale(1.05);
}

@media print {
  .sidebar, .topbar, .sec-head, .modal-overlay, #toast, .btn {
    display: none !important;
  }
  
  body, .app, .main, .content {
    background: white !important;
    margin: 0 !important;
    padding: 0 !important;
    width: 100% !important;
    height: auto !important;
    overflow: visible !important;
  }

  .grid-cols-12 {
    display: block !important;
  }
  
  .col-span-12 {
    width: 100% !important;
    margin-bottom: 24px !important;
  }
  
  .card {
    border: none !important;
    box-shadow: none !important;
    padding: 10px 0 !important;
  }
  
  .b-green { border: 1px solid #10b981; color: #10b981; }
  .b-red { border: 1px solid #ef4444; color: #ef4444; }
  .b-indigo { border: 1px solid #6366f1; color: #6366f1; }
  
  @page {
    margin: 2cm;
    size: A4 portrait;
  }
}
</style>

<div class="panel active">
  <div class="sec-head">
    <div class="flex items-center gap16">
      <a href="/workers" class="btn btn-sm btn-icon">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
      </a>
      <div class="sec-meta">Worker ID: <span class="mono bold c-primary"><?= htmlspecialchars($worker['worker_code']) ?></span></div>
    </div>
    <div class="flex gap12">
      <button class="btn btn-sm" onclick="history.back()">
        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 4px;"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
        Edit Information
      </button>
      <button class="btn btn-primary btn-sm">
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
          <?php if($worker['photo_path']): ?>
            <img src="<?= $worker['photo_path'] ?>" alt="Worker Photo" style="width: 150px; height: 150px; border-radius: 50%; object-fit: cover; border: 4px solid var(--gray-lighter);">
          <?php else: ?>
            <div style="width: 150px; height: 150px; border-radius: 50%; background: var(--gray-lighter); display: flex; align-items: center; justify-content: center; border: 4px solid var(--gray-light);">
              <svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="color: var(--text-muted);"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            </div>
          <?php endif; ?>
        </div>
        <div class="text-center">
          <h2 class="fs20 mb4"><?= htmlspecialchars($worker['full_name']) ?></h2>
          <div class="chip b-indigo mb16"><?= htmlspecialchars($worker['category_name']) ?></div>
          <p class="c-secondary fs13 mb24">Started on <?= date('d M Y', strtotime($worker['doj'])) ?></p>
          
          <div class="w-full pt16 border-t flex flex-col gap12">
            <div class="flex justify-between fs13">
              <span class="c-secondary">Status</span>
              <span class="badge <?= $worker['status'] == 'Active' ? 'b-green' : 'b-red' ?>"><?= $worker['status'] ?></span>
            </div>
            <div class="flex justify-between fs13">
              <span class="c-secondary">Mobile</span>
              <span class="bold"><?= htmlspecialchars($worker['mobile']) ?></span>
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
              Employment Details
            </h3>
            <div class="grid gap16">
              <div class="flex flex-col gap4">
                <span class="fs11 fw5 c-secondary uppercase">Assigned Site</span>
                <span class="bold c-teal flex items-center gap4">
                  <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                  <?= htmlspecialchars($worker['site_name'] ?? 'Unassigned') ?>
                </span>
              </div>
              <div class="flex flex-col gap4">
                <span class="fs11 fw5 c-secondary uppercase">Experience Level</span>
                <span class="bold"><?= htmlspecialchars($worker['experience'] ?? 'Not Specified') ?></span>
              </div>
              <div class="flex flex-col gap4">
                <span class="fs11 fw5 c-secondary uppercase">Employee Age</span>
                <span class="bold"><?= $worker['age'] ? (htmlspecialchars($worker['age']) . ' Years') : 'N/A' ?></span>
              </div>
            </div>
          </div>

          <div class="card p24">
            <h3 class="fs14 fw6 mb20 flex items-center gap8">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="c-amber"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
              Statutory Compliance
            </h3>
            <div class="grid gap16">
              <div class="flex flex-col gap4">
                <span class="fs11 fw5 c-secondary uppercase">Aadhaar Card Number</span>
                <span class="bold mono"><?= htmlspecialchars($worker['aadhaar'] ?? 'Not Provided') ?></span>
              </div>
              <div class="flex flex-col gap4">
                <span class="fs11 fw5 c-secondary uppercase">ESI Identification</span>
                <span class="bold mono c-blue"><?= htmlspecialchars($worker['esi_number'] ?? 'Not Registered') ?></span>
              </div>
              <div class="flex flex-col gap4">
                <span class="fs11 fw5 c-secondary uppercase">PF Account Number</span>
                <span class="bold mono c-blue"><?= htmlspecialchars($worker['pf_number'] ?? 'Not Registered') ?></span>
              </div>
            </div>
          </div>
        </div>

        <!-- ROW 2: ASSET TRACKING (ADDITIVE) -->
        <div class="card p24">
          <div class="flex justify-between items-center mb20">
            <h3 class="fs14 fw6 flex items-center gap8">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="c-teal"><path d="M20.42 4.58a5.4 5.4 0 0 0-7.65 0l-.77.78-.77-.78a5.4 5.4 0 0 0-7.65 0C1.46 6.7 1.33 10.28 4 13l8 8 8-8c2.67-2.72 2.54-6.3.42-8.42z"/></svg>
              Uniform & Safety Asset History
            </h3>
            <button class="btn btn-sm btn-approve" onclick="openModal('modal-add-asset')">+ Issue New Item</button>
          </div>
          
          <div class="table-wrap" style="border: 1px solid var(--border-color); border-radius: 8px;">
            <table class="fs13">
              <thead>
                <tr style="background: var(--gray-lighter);">
                  <th class="p12">Item / Allocation</th>
                  <th class="p12">Date Issued</th>
                  <th class="p12 text-right">Delete</th>
                </tr>
              </thead>
              <tbody>
                <?php if(empty($worker['assets'])): ?>
                  <tr><td colspan="3" class="text-center p24 c-secondary italic">No assets issued to this member yet.</td></tr>
                <?php endif; ?>
                <?php foreach($worker['assets'] as $asset): ?>
                <tr>
                  <td class="p12 bold"><?= htmlspecialchars($asset['item_name']) ?></td>
                  <td class="p12 c-secondary"><?= date('d M, Y', strtotime($asset['issue_date'])) ?></td>
                  <td class="p12 text-right">
                    <form method="POST" action="/workers/assets/delete" onsubmit="return confirm('Remove from history?')" style="margin:0;">
                      <input type="hidden" name="asset_id" value="<?= $asset['id'] ?>">
                      <input type="hidden" name="worker_id" value="<?= $worker['id'] ?>">
                      <button type="submit" class="btn btn-sm" style="padding:4px; color:var(--red);">
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
                      </button>
                    </form>
                  </td>
                </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>

<!-- QUICK ISSUE ASSET MODAL -->
<div class="modal-overlay" id="modal-add-asset">
  <div class="modal" style="max-width: 400px;">
    <div class="modal-head">
      <div class="modal-title">Issue New Asset</div>
      <button class="modal-close" onclick="closeModal('modal-add-asset')">×</button>
    </div>
    <form method="POST" action="/workers/assets/add">
      <input type="hidden" name="worker_id" value="<?= $worker['id'] ?>">
      <div class="p24">
        <div class="form-group mb16">
          <label class="form-label">Item Name (Add-on)</label>
          <input type="text" name="item_name" class="form-input" placeholder="e.g. Winter Jacket, Boots" required list="asset-suggestions">
          <datalist id="asset-suggestions">
            <option value="Uniform Set (T-Shirt/Pant)">
            <option value="Safety Shoes / Boots">
            <option value="Winter Jacket">
            <option value="ID Card & Lanyard">
            <option value="Raincoat">
          </datalist>
        </div>
        <div class="form-group">
          <label class="form-label">Issue Date</label>
          <input type="date" name="issue_date" class="form-input" value="<?= date('Y-m-d') ?>" required>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary w-full">Confirm Issuance</button>
      </div>
    </form>
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
</style>

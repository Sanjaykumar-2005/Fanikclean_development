<div class="panel active">
  <div class="sec-head">
    <div class="flex gap16">
      <div class="sec-meta">Total Workers: <span class="fw7 c-teal"><?= count($workers) ?></span></div>
    </div>
    <button class="btn btn-primary" onclick="openAddWorkerModal()">
      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 6px;"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" x2="19" y1="8" y2="14"/><line x1="22" x2="16" y1="11" y2="11"/></svg>
      Add New Worker
    </button>
  </div>

  <div class="card">
    <div class="table-wrap">
      <table>
        <thead>
          <tr>
            <th>ID / Code</th>
            <th>Full Name</th>
            <th>ESI / PF</th>
            <th>Category</th>
            <th>Site / Location</th>
            <th>Contact</th>
            <th>Status</th>
            <th class="text-right">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($workers as $w): ?>
          <tr>
            <td class="mono c-secondary fs12"><?= htmlspecialchars($w['worker_code']) ?></td>
            <td class="bold">
              <a href="/workers/profile?id=<?= $w['id'] ?>" class="c-primary" style="text-decoration:none;">
                <?= htmlspecialchars($w['full_name']) ?>
              </a>
            </td>
            <td class="fs11">
              <div class="c-secondary">ESI: <?= htmlspecialchars($w['esi_number'] ?? 'N/A') ?></div>
              <div class="c-secondary">PF: <?= htmlspecialchars($w['pf_number'] ?? 'N/A') ?></div>
            </td>
            <td>
              <?php 
                $catClass = 'b-gray';
                if ($w['category_name'] == 'Supervisor') $catClass = 'b-purple';
                if ($w['category_name'] == 'Skilled') $catClass = 'b-blue';
                if ($w['category_name'] == 'Associate') $catClass = 'b-amber';
              ?>
              <span class="badge <?= $catClass ?>"><?= htmlspecialchars($w['category_name']) ?></span>
            </td>
            <td>
              <div class="flex-center gap8">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="c-secondary"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                <?= htmlspecialchars($w['site_name'] ?? 'Unassigned') ?>
              </div>
            </td>
            <td><?= htmlspecialchars($w['mobile']) ?></td>
            <td>
              <span class="badge <?= $w['status'] == 'Active' ? 'b-green' : 'b-red' ?>">
                <?= htmlspecialchars($w['status']) ?>
              </span>
            </td>
            <td class="text-right">
              <div class="flex flex-end gap8">
                <a href="/workers/profile?id=<?= $w['id'] ?>" class="btn btn-sm">
                  <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                </a>
                <button class="btn btn-sm" onclick='openEditWorkerModal(<?= json_encode($w) ?>)'>
                  <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"/></svg>
                </button>
              </div>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- ADD/EDIT WORKER MODAL -->
  <div class="modal-overlay" id="modal-add-worker">
    <div class="modal modal-lg">
      <div class="modal-head">
        <div class="modal-title" id="worker-modal-title">Worker Profile Expansion</div>
        <button type="button" class="modal-close" onclick="closeModal('modal-add-worker')">×</button>
      </div>

      <form method="POST" action="/workers/create" id="worker-form" enctype="multipart/form-data">
        <input type="hidden" name="id" id="worker-id">
        
        <div style="max-height: 70vh; overflow-y: auto; padding: 24px;">
          <div class="form-section-title">Personal Information & Photo</div>
          <div class="form-grid mb24">
            <div class="form-group">
              <label class="form-label">Full Name</label>
              <input class="form-input" type="text" name="full_name" id="worker-full_name" placeholder="Legal full name" required>
            </div>
            <div class="form-group">
              <label class="form-label">Contact Number</label>
              <input class="form-input" type="tel" name="mobile" id="worker-mobile" placeholder="10-digit number" required>
            </div>
            <div class="form-group">
              <label class="form-label">Aadhaar Card</label>
              <input class="form-input" type="text" name="aadhaar" id="worker-aadhaar" placeholder="12-digit number">
            </div>
            <div class="form-group">
              <label class="form-label">Profile Photo</label>
              <input class="form-input" type="file" name="photo" id="worker-photo" accept="image/*">
            </div>
            <div class="form-group">
              <label class="form-label">Age</label>
              <input class="form-input" type="number" name="age" id="worker-age" placeholder="Age in years">
            </div>
            <div class="form-group">
              <label class="form-label">Experience</label>
              <input class="form-input" type="text" name="experience" id="worker-experience" placeholder="e.g. 5 Years">
            </div>
          </div>

          <div class="form-section-title">Statutory & Asset Details</div>
          <div class="form-grid mb24">
            <div class="form-group">
              <label class="form-label">ESI Number</label>
              <input class="form-input" type="text" name="esi_number" id="worker-esi_number" placeholder="Statutory ESI">
            </div>
            <div class="form-group">
              <label class="form-label">PF Number</label>
              <input class="form-input" type="text" name="pf_number" id="worker-pf_number" placeholder="Provident Fund ID">
            </div>
            <div class="form-group">
              <label class="form-label">Uniform Issue Date</label>
              <input class="form-input" type="date" name="uniform_issue_date" id="worker-uniform_issue_date">
            </div>
            <div class="form-group">
              <label class="form-label">Uniform Details</label>
              <input class="form-input" type="text" name="uniform_details" id="worker-uniform_details" placeholder="Sizes, Quantity, etc.">
            </div>
          </div>

          <div class="form-section-title">Employment & Alignment</div>
          <div class="form-grid">
            <div class="form-group">
              <label class="form-label">Date of Joining</label>
              <input class="form-input" type="date" name="doj" id="worker-doj" required>
            </div>
            <div class="form-group">
              <label class="form-label">Skill Category</label>
              <select class="form-input" name="category_id" id="worker-category_id">
                <?php foreach($categories as $cat): ?>
                  <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="form-group">
              <label class="form-label">Assign to Site</label>
              <select class="form-input" name="site_id" id="worker-site_id">
                <option value="">-- No Specific Site --</option>
                <?php foreach($sites as $site): ?>
                  <option value="<?= $site['id'] ?>"><?= htmlspecialchars($site['name']) ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="form-group">
              <label class="form-label">Status</label>
              <select class="form-input" name="status" id="worker-status">
                <option value="Active">Active / On Duty</option>
                <option value="Inactive">Inactive / On Leave</option>
                <option value="Removed">Terminated / Removed</option>
              </select>
            </div>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn" style="border:none; background:transparent;" onclick="closeModal('modal-add-worker')">Discard</button>
          <button type="submit" class="btn btn-primary" style="padding: 10px 24px;">Confirm & Save</button>
        </div>
      </form>
    </div>
  </div>
</div>

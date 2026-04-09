<div class="panel active">
  <div class="sec-head">
    <div class="flex gap8">
      <!-- Filters can go here -->
    </div>
    <button class="btn btn-primary btn-sm" onclick="openAddWorkerModal()">+ Add Worker</button>
  </div>

  <div class="card">
    <div class="table-wrap">
      <table>
        <thead><tr><th>Worker ID</th><th>Name</th><th>Category</th><th>Site</th><th>Contact</th><th>Joined</th><th>Status</th><th>Actions</th></tr></thead>
        <tbody>
          <?php foreach($workers as $w): ?>
          <tr>
            <td class="mono"><?= htmlspecialchars($w['worker_code']) ?></td>
            <td class="bold"><?= htmlspecialchars($w['full_name']) ?></td>
            <td><span class="badge b-amber"><?= htmlspecialchars($w['category_name']) ?></span></td>
            <td><?= htmlspecialchars($w['site_name'] ?? 'Unassigned') ?></td>
            <td><?= htmlspecialchars($w['mobile']) ?></td>
            <td><?= htmlspecialchars($w['doj']) ?></td>
            <td><span class="badge b-green"><?= htmlspecialchars($w['status']) ?></span></td>
            <td>
              <div class="btn-group">
                <button class="btn btn-sm" onclick='openEditWorkerModal(<?= json_encode($w) ?>)'>Edit</button>
              </div>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- ADD WORKER MODAL (FIXED WITH FORM & NAME ATTRS) -->
  <div class="modal-overlay" id="modal-add-worker">
    <div class="modal modal-lg">
      <div class="modal-head">
        <div class="modal-title" id="worker-modal-title">Add / Edit Worker</div>
        <button type="button" class="modal-close" onclick="closeModal('modal-add-worker')">×</button>
      </div>

      <form method="POST" action="/workers/create" id="worker-form">
        <input type="hidden" name="id" id="worker-id">
        <div class="form-section-title">Personal Details</div>
        <div class="form-grid mb16">
          <div class="form-group"><label class="form-label">Full Name</label><input class="form-input" type="text" name="full_name" id="worker-full_name" placeholder="Worker full name" required></div>
          <div class="form-group"><label class="form-label">Mobile Number</label><input class="form-input" type="tel" name="mobile" id="worker-mobile" placeholder="10-digit number" required></div>
          <div class="form-group"><label class="form-label">Aadhaar / ID Number</label><input class="form-input" type="text" name="aadhaar" id="worker-aadhaar" placeholder="12-digit Aadhaar"></div>
          <div class="form-group"><label class="form-label">Date of Joining</label><input class="form-input" type="date" name="doj" id="worker-doj" required></div>
        </div>

        <div class="form-section-title">Assignment</div>
        <div class="form-grid">
          <div class="form-group"><label class="form-label">Worker Category</label>
            <select class="form-input" name="category_id" id="worker-category_id">
              <option value="1">Supervisor</option>
              <option value="2">Associate</option>
              <option value="3">Skilled</option>
              <option value="4" selected>Helper</option>
            </select>
          </div>
          <div class="form-group"><label class="form-label">Assign to Site</label>
            <select class="form-input" name="site_id" id="worker-site_id">
              <option value="">Unassigned</option>
              <?php
                $db = Database::connect();
                $siteRows = $db->query("SELECT id, name FROM sites ORDER BY name")->fetchAll();
                foreach($siteRows as $site):
              ?>
              <option value="<?= $site['id'] ?>"><?= htmlspecialchars($site['name']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="form-group"><label class="form-label">Status</label>
            <select class="form-input" name="status" id="worker-status">
              <option value="Active">Active</option>
              <option value="Inactive">Inactive</option>
            </select>
          </div>
        </div>

        <div class="modal-footer" style="margin-top: 20px;">
          <button type="button" class="btn btn-sm" onclick="closeModal('modal-add-worker')">Cancel</button>
          <button type="submit" class="btn btn-primary btn-sm">Save Worker</button>
        </div>
      </form>

    </div>
  </div>
</div>

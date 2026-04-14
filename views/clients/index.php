<div class="panel active">
  <div class="sec-head">
    <div class="sec-meta">Client Directory & Sites</div>
    <div class="flex gap8">
      <button class="btn btn-primary btn-sm" onclick="openModal('modal-add-client')">+ Add Client</button>
      <button class="btn btn-outline btn-sm" onclick="openModal('modal-add-site')">+ Add Site</button>
    </div>
  </div>

  <div class="card">
    <div class="table-wrap">
      <table>
        <thead><tr><th>Client ID</th><th>Company Name</th><th>Contact</th><th>Mobile</th><th>Status</th></tr></thead>
        <tbody>
          <?php if(empty($clients)): ?>
            <tr><td colspan="5" style="text-align:center;">No clients found. Add one above.</td></tr>
          <?php endif; ?>
          <?php foreach($clients as $c): ?>
          <tr>
            <td class="mono">C-<?= htmlspecialchars($c['id']) ?></td>
            <td class="bold"><?= htmlspecialchars($c['company_name']) ?></td>
            <td><?= htmlspecialchars($c['contact_person']) ?></td>
            <td><?= htmlspecialchars($c['mobile']) ?></td>
            <td><span class="badge b-green">Active</span></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- ADD CLIENT MODAL -->
  <div class="modal-overlay" id="modal-add-client">
    <div class="modal modal-lg">
      <div class="modal-head">
        <div class="modal-title">Add New Client</div>
        <button type="button" class="modal-close" onclick="closeModal('modal-add-client')">×</button>
      </div>

      <form method="POST" action="/clients/create">
        <div class="form-section-title">Client Details</div>
        <div class="form-grid mb16">
          <div class="form-group"><label class="form-label">Company Name</label><input class="form-input" type="text" name="company_name" placeholder="e.g. Sunrise Hospital" required></div>
          <div class="form-group"><label class="form-label">Contact Person</label><input class="form-input" type="text" name="contact_person" placeholder="Full name"></div>
          <div class="form-group"><label class="form-label">Mobile</label><input class="form-input" type="tel" name="mobile" placeholder="10-digit"></div>
          <div class="form-group"><label class="form-label">Email</label><input class="form-input" type="email" name="email" placeholder="contact@company.com"></div>
          <div class="form-group"><label class="form-label">GSTIN</label><input class="form-input" type="text" name="gstin" placeholder="29XXXXX1234F1Z5"></div>
          <div class="form-group"><label class="form-label">Address</label><input class="form-input" type="text" name="address" placeholder="Full address"></div>
        </div>

        <div class="form-section-title">Contract Options</div>
        <div class="form-grid">
          <div class="form-group"><label class="form-label">Contract Start</label><input class="form-input" name="contract_start" type="date"></div>
          <div class="form-group"><label class="form-label">Contract End</label><input class="form-input" name="contract_end" type="date"></div>
          <div class="form-group"><label class="form-label">Billing Cycle</label>
            <select class="form-input" name="billing_cycle">
              <option value="Monthly">Monthly</option>
              <option value="Fortnightly">Fortnightly</option>
            </select>
          </div>
        </div>

        <div class="modal-footer" style="margin-top: 20px;">
          <button type="button" class="btn btn-sm" onclick="closeModal('modal-add-client')">Cancel</button>
          <button type="submit" class="btn btn-primary btn-sm">Save Client</button>
        </div>
      </form>

    </div>
  </div>

  <!-- ADD SITE MODAL -->
  <div class="modal-overlay" id="modal-add-site">
    <div class="modal">
      <div class="modal-head">
        <div class="modal-title">Add New Site</div>
        <button type="button" class="modal-close" onclick="closeModal('modal-add-site')">×</button>
      </div>
      <form method="POST" action="/sites/create">
        <div class="form-grid mb16" style="display:block">
          <div class="form-group mb16">
            <label class="form-label">Select Client</label>
            <select class="form-input" name="client_id" required>
              <option value="">-- Select Client --</option>
              <?php foreach($clients as $c): ?>
                <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['company_name']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="form-group mb16"><label class="form-label">Site Name</label><input class="form-input" type="text" name="name" required placeholder="e.g. Block A"></div>
          <div class="form-group mb16"><label class="form-label">Address</label><input class="form-input" type="text" name="address" placeholder="Site Address"></div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-sm" onclick="closeModal('modal-add-site')">Cancel</button>
          <button type="submit" class="btn btn-primary btn-sm">Save Site</button>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="panel active">
  <div class="sec-head">
    <div class="sec-meta">User Management (Admin Only)</div>
  </div>

  <div class="card">
    <div class="table-wrap">
      <table>
        <thead>
          <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>Assigned Site</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($users as $u): ?>
          <tr>
            <td class="mono"><?= $u['id'] ?></td>
            <td class="bold"><?= htmlspecialchars($u['full_name']) ?></td>
            <td><?= htmlspecialchars($u['email']) ?></td>
            <td><span class="badge <?= $u['role_id'] == 1 ? 'b-indigo' : 'b-amber' ?>"><?= htmlspecialchars($u['role_name']) ?></span></td>
            <td><?= htmlspecialchars($u['site_name'] ?? 'All Sites') ?></td>
            <td><span class="badge <?= $u['status'] == 'Active' ? 'b-green' : 'b-red' ?>"><?= htmlspecialchars($u['status']) ?></span></td>
            <td>
              <button class="btn btn-sm" onclick='openEditUserModal(<?= json_encode($u) ?>)'>Manage</button>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- EDIT USER MODAL -->
  <div class="modal-overlay" id="modal-edit-user">
    <div class="modal">
      <div class="modal-head">
        <div class="modal-title" id="user-modal-title">Manage User Access</div>
        <button type="button" class="modal-close" onclick="closeModal('modal-edit-user')">×</button>
      </div>

      <form method="POST" action="/users/update" id="user-form">
        <input type="hidden" name="id" id="edit-user-id">
        
        <div class="form-group mb16">
          <label class="form-label">Full Name</label>
          <input class="form-input" type="text" name="full_name" id="edit-user-name" required>
        </div>

        <div class="form-group mb16">
          <label class="form-label">Role</label>
          <select class="form-input" name="role_id" id="edit-user-role">
            <?php foreach($roles as $r): ?>
              <option value="<?= $r['id'] ?>"><?= $r['name'] ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="form-group mb16">
          <label class="form-label">Assign Site (For Managers)</label>
          <select class="form-input" name="site_id" id="edit-user-site">
            <option value="">-- All Sites / Admin --</option>
            <?php foreach($sites as $s): ?>
              <option value="<?= $s['id'] ?>"><?= htmlspecialchars($s['name']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="form-group mb16">
          <label class="form-label">Status</label>
          <select class="form-input" name="status" id="edit-user-status">
            <option value="Active">Active</option>
            <option value="Inactive">Inactive</option>
          </select>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-sm" onclick="closeModal('modal-edit-user')">Cancel</button>
          <button type="submit" class="btn btn-primary btn-sm">Update User</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
function openEditUserModal(u) {
    document.getElementById('edit-user-id').value = u.id;
    document.getElementById('edit-user-name').value = u.full_name;
    document.getElementById('edit-user-role').value = u.role_id;
    document.getElementById('edit-user-site').value = u.site_id || '';
    document.getElementById('edit-user-status').value = u.status;
    openModal('modal-edit-user');
}
</script>

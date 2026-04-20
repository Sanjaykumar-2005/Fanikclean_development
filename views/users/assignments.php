<div class="panel active">
  <div class="sec-head">
    <div class="sec-meta">Assign multiple sites to each manager to define their operational scope.</div>
  </div>

  <div class="card">
    <div class="table-wrap">
      <table>
        <thead>
          <tr>
            <th>Manager Name</th>
            <th>Email</th>
            <th>Assigned Sites</th>
            <th class="text-right">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($managers as $m): ?>
          <tr>
            <td class="bold"><?= htmlspecialchars($m['full_name']) ?></td>
            <td class="c-secondary fs13"><?= htmlspecialchars($m['email']) ?></td>
            <td>
              <?php if(empty($m['assigned_site_ids'])): ?>
                <span class="chip" style="background: var(--gray-lighter); color: var(--text-muted);">No sites assigned</span>
              <?php else: ?>
                <div class="flex flex-wrap gap4">
                  <?php 
                    foreach($sites as $s) {
                      if(in_array($s['id'], $m['assigned_site_ids'])) {
                        echo '<span class="chip b-blue">' . htmlspecialchars($s['name']) . '</span>';
                      }
                    }
                  ?>
                </div>
              <?php endif; ?>
            </td>
            <td class="text-right">
              <button class="btn btn-sm" onclick='openAssignmentModal(<?= json_encode($m) ?>)'>
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 4px;"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                Edit Mapping
              </button>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- ASSIGNMENT MODAL -->
  <div class="modal-overlay" id="modal-assignments">
    <div class="modal">
      <div class="modal-head">
        <div class="modal-title">Site Assignments: <span id="assign-mgr-name" class="c-primary"></span></div>
        <button type="button" class="modal-close" onclick="closeModal('modal-assignments')">×</button>
      </div>

      <form method="POST" action="/users/assignments/save">
        <input type="hidden" name="user_id" id="assign-user-id">
        
        <div style="padding: 24px;">
          <label class="form-label mb12">Select Sites for this Manager</label>
          <div class="flex flex-col gap8" style="max-height: 300px; overflow-y: auto; padding: 4px;">
            <?php foreach($sites as $s): ?>
            <label class="flex items-center gap12 p8 border rounded hover-bg" style="cursor: pointer; border-color: var(--border-color);">
              <input type="checkbox" name="site_ids[]" value="<?= $s['id'] ?>" class="site-chk" id="site-<?= $s['id'] ?>">
              <span class="fs14"><?= htmlspecialchars($s['name']) ?></span>
            </label>
            <?php endforeach; ?>
          </div>
          <p class="fs11 c-secondary mt16">Check all sites the manager is responsible for. Clearing all will restrict the manager to no site access.</p>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn" style="border:none; background:transparent;" onclick="closeModal('modal-assignments')">Cancel</button>
          <button type="submit" class="btn btn-primary" style="padding: 10px 24px;">Update Assignments</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
function openAssignmentModal(m) {
    document.getElementById('assign-user-id').value = m.id;
    document.getElementById('assign-mgr-name').textContent = m.full_name;
    
    // Clear all checkboxes first
    document.querySelectorAll('.site-chk').forEach(chk => {
        chk.checked = false;
    });
    
    // Check assigned sites
    if(m.assigned_site_ids) {
        m.assigned_site_ids.forEach(sid => {
            const el = document.getElementById('site-' + sid);
            if(el) el.checked = true;
        });
    }
    
    openModal('modal-assignments');
}
</script>

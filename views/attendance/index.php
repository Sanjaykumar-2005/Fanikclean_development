<div class="panel active">
  <form method="POST" action="/attendance/save">
    <!-- Filter Bar -->
    <div class="card mb20">
      <div class="flex gap16 flex-wrap" style="align-items: flex-end;">
        <div class="form-group" style="flex: 1; min-width: 200px;">
          <label class="form-label">Attendance Date</label>
          <input type="date" name="attendance_date" class="form-input" value="<?= htmlspecialchars($date) ?>" required>
        </div>
        
        <div class="form-group" style="flex: 2; min-width: 250px;">
          <label class="form-label">Select Site / Project</label>
          <select class="form-input" id="site_select" name="site_id" onchange="loadWorkers()" required>
              <option value="">-- Choose Site --</option>
              <?php 
                  $db = Database::connect(); 
                  $sites = $db->query("SELECT * FROM sites")->fetchAll();
                  foreach($sites as $s): 
              ?>
              <option value="<?= $s['id'] ?>" <?= ($selectedSiteId == $s['id']) ? 'selected' : '' ?>><?= htmlspecialchars($s['name']) ?></option>
              <?php endforeach; ?>
          </select>
        </div>

        <div style="margin-bottom: 2px;">
          <button type="submit" class="btn btn-primary">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 4px;"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
            Save Register
          </button>
        </div>
      </div>
    </div>

    <!-- Attendance Register -->
    <div class="card">
      <div class="card-head">
        <div class="card-title">Worker Attendance Grid</div>
        <div class="chip b-blue"><?= date('l, d F Y', strtotime($date)) ?></div>
      </div>
      <div class="table-wrap">
        <table>
          <thead>
            <tr>
              <th>Worker Details</th>
              <th>Status</th>
              <th>OT Hours</th>
              <th>Remarks / Notes</th>
            </tr>
          </thead>
          <tbody id="worker_tbody">
            <tr><td colspan="4" style="text-align:center; padding: 40px; color: var(--text-muted);">Please select a site to load the worker register.</td></tr>
          </tbody>
        </table>
      </div>
    </div>
  </form>
</div>

<!-- Script to load workers via API -->
<script>
function loadWorkers() {
    var siteId = document.getElementById('site_select').value;
    var attDate = document.querySelector('input[name="attendance_date"]').value;
    var month = attDate ? attDate.substring(0, 7) : new Date().toISOString().substring(0, 7);
    
    var tbody = document.getElementById('worker_tbody');
    tbody.innerHTML = '<tr><td colspan="4" style="text-align:center; padding: 40px;">Loading workers...</td></tr>';
    
    if(!siteId) {
        tbody.innerHTML = '<tr><td colspan="4" style="text-align:center; padding: 40px; color: var(--text-muted);">Select a site to load workers.</td></tr>';
        return;
    }

    fetch('/api/workers?site_id=' + siteId + '&month=' + month)
    .then(res => res.json())
    .then(data => {
        if(data.length === 0) {
            tbody.innerHTML = '<tr><td colspan="4" style="text-align:center; padding: 40px; color: var(--text-muted);">No workers assigned to this site.</td></tr>';
            return;
        }
        var html = '';
        data.forEach(w => {
            html += '<tr>';
            html += '<td>';
            html += '  <div class="fw6">' + w.full_name + '</div>';
            html += '  <div class="fs11 c-secondary">' + w.worker_code + ' | ' + (w.category_name || 'General') + '</div>';
            html += '</td>';
            html += '<td>';
            html += '  <select class="form-input" name="attendance['+w.id+'][status]" style="width:145px;">';
            html += '    <option value="p" selected>Present (P)</option>';
            html += '    <option value="off">Off Duty (Off)</option>';
            html += '    <option value="h">Half-Day (H)</option>';
            
            // Only show PL if count < 4
            if (parseInt(w.pl_count) < 4) {
               html += '    <option value="pl">Paid Leave (PL)</option>';
            }
            
            html += '    <option value="sd">Special Duty (SD)</option>';
            html += '  </select>';
            html += '</td>';
            html += '<td>';
            html += '  <div class="flex-center gap8">';
            html += '    <input type="number" step="0.5" name="attendance['+w.id+'][ot]" class="form-input" style="width:80px;" value="0">';
            html += '    <span class="fs11 fw6 c-secondary">hrs</span>';
            html += '  </div>';
            html += '</td>';
            html += '<td><input type="text" name="attendance['+w.id+'][note]" class="form-input" placeholder="Optional note..."></td>';
            html += '</tr>';
        });
        tbody.innerHTML = html;
    });
}

// Auto-load if site is already selected
if(document.getElementById('site_select').value) {
    loadWorkers();
}
</script>

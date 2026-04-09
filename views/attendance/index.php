<div class="panel active">
  <form method="POST" action="/attendance/save">
      <div class="flex gap8 flex-wrap" style="align-items:center;">
        <label>Date:</label>
        <input type="date" name="attendance_date" class="form-input" value="<?= htmlspecialchars($date) ?>" style="width:168px" required>
        
        <label>Site:</label>
        <select class="form-input" id="site_select" name="site_id" onchange="loadWorkers()" style="width:200px" required>
            <option value="">-- Select Site --</option>
            <?php 
                $db = Database::connect(); 
                $sites = $db->query("SELECT * FROM sites")->fetchAll();
                foreach($sites as $s): 
            ?>
            <option value="<?= $s['id'] ?>"><?= htmlspecialchars($s['name']) ?></option>
            <?php endforeach; ?>
        </select>
      </div>
      <button type="submit" class="btn btn-primary btn-sm">Save Attendance</button>
    </div>

    <!-- Script to load workers via API -->
    <script>
    function loadWorkers() {
        var siteId = document.getElementById('site_select').value;
        var tbody = document.getElementById('worker_tbody');
        tbody.innerHTML = '<tr><td colspan="4" style="text-align:center;">Loading...</td></tr>';
        
        if(!siteId) {
            tbody.innerHTML = '<tr><td colspan="4" style="text-align:center;">Select a site to load workers.</td></tr>';
            return;
        }

        fetch('/api/workers?site_id=' + siteId)
        .then(res => res.json())
        .then(data => {
            if(data.length === 0) {
                tbody.innerHTML = '<tr><td colspan="4" style="text-align:center;">No workers assigned to this site.</td></tr>';
                return;
            }
            var html = '';
            data.forEach(w => {
                html += '<tr>';
                html += '<td class="bold">' + w.full_name + '</td>';
                html += '<td><select class="form-input" name="attendance['+w.id+'][status]" style="width:120px;" required><option value="">--</option><option value="p">Present</option><option value="a">Absent</option><option value="h">Half</option><option value="off">Off</option></select></td>';
                html += '<td><input type="number" step="0.5" name="attendance['+w.id+'][ot]" class="form-input" style="width:80px;" placeholder="0"></td>';
                html += '<td><input type="text" name="attendance['+w.id+'][note]" class="form-input" placeholder="Note"></td>';
                html += '</tr>';
            });
            tbody.innerHTML = html;
        });
    }
    </script>

    <div class="card mb16">
      <div class="card-head">
        <div class="card-title">Daily attendance register</div>
      </div>
      <div class="table-wrap">
        <table>
          <thead>
            <tr>
              <th>Worker Name</th>
              <th>Status (P/A/H/Off)</th>
              <th>OT Hours</th>
              <th>Note</th>
            </tr>
          </thead>
          <tbody id="worker_tbody">
            <tr><td colspan="4" style="text-align:center;">Select a site to load workers.</td></tr>
          </tbody>
        </table>
      </div>
    </div>
  </form>
</div>

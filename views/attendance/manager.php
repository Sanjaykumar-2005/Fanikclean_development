<div class="panel active">
  <div class="sec-head">
    <div class="sec-meta">Daily manager log. Select a date to mark attendance for all system managers.</div>
    <form method="GET" action="/attendance/manager" class="flex gap12">
      <div class="form-group mb0">
        <input type="date" name="date" value="<?= $date ?>" class="form-input" onchange="this.form.submit()">
      </div>
      <div class="chip b-blue"><?= date('l, d F Y', strtotime($date)) ?></div>
    </form>
  </div>

  <div class="card">
    <form method="POST" action="/attendance/manager/save">
      <input type="hidden" name="attendance_date" value="<?= $date ?>">
      
      <div class="table-wrap">
        <table>
          <thead>
            <tr>
              <th>Manager Name</th>
              <th>Attendance Status</th>
              <th>Notes / Remarks</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach($managers as $m): 
                // Find existing status for this specific date
                $existing = array_filter($history, function($h) use ($m, $date) {
                    return $h['user_id'] == $m['id'] && $h['attendance_date'] == $date;
                });
                $status = !empty($existing) ? array_values($existing)[0]['status'] : 'p';
                $note = !empty($existing) ? array_values($existing)[0]['note'] : '';
                $count = $plCounts[$m['id']] ?? 0;
            ?>
            <tr>
              <td class="bold fs14"><?= htmlspecialchars($m['full_name']) ?></td>
              <td>
                <select name="manager_attendance[<?= $m['id'] ?>][status]" 
                        class="form-input att-select <?= $status ?>" 
                        style="width: 200px;"
                        onchange="this.className = 'form-input att-select ' + this.value">
                  <option value="p" <?= $status == 'p' ? 'selected' : '' ?>>Present (P)</option>
                  <option value="off" <?= $status == 'off' ? 'selected' : '' ?>>Off Duty (Off)</option>
                  <option value="h" <?= $status == 'h' ? 'selected' : '' ?>>Half Day (H)</option>
                  <?php if ($count < 4 || $status == 'pl'): ?>
                      <option value="pl" <?= $status == 'pl' ? 'selected' : '' ?>>Paid Leave (PL)</option>
                  <?php endif; ?>
                  <option value="sd" <?= $status == 'sd' ? 'selected' : '' ?>>Special Duty (SD)</option>
                </select>
              </td>
              <td>
                <input type="text" name="manager_attendance[<?= $m['id'] ?>][note]" 
                       class="form-input" 
                       value="<?= htmlspecialchars($note) ?>" 
                       placeholder="Optional remarks...">
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>

      <div class="modal-footer" style="padding: 20px; background: var(--gray-lighter);">
        <div class="flex gap16 fs12 c-secondary items-center">
            <span class="flex items-center gap4"><span class="badge b-green" style="width:12px; height:12px; border-radius:2px; padding:0;"></span> Present</span>
            <span class="flex items-center gap4"><span class="badge b-gray" style="width:12px; height:12px; border-radius:2px; padding:0;"></span> Off Duty</span>
            <span class="flex items-center gap4"><span class="badge b-amber" style="width:12px; height:12px; border-radius:2px; padding:0;"></span> Half Day</span>
            <span class="flex items-center gap4"><span class="badge b-blue" style="width:12px; height:12px; border-radius:2px; padding:0;"></span> Paid Leave</span>
            <span class="flex items-center gap4"><span class="badge b-purple" style="width:12px; height:12px; border-radius:2px; padding:0;"></span> Special Duty (2x)</span>
        </div>
        <button type="submit" class="btn btn-primary" style="padding: 10px 32px;">Save Manager Attendance</button>
      </div>
    </form>
  </div>
</div>

<style>
.att-select { font-weight: 600; cursor: pointer; }
.att-select.p { background: #e6fffa !important; color: #047481; border-color: #38b2ac !important; }
.att-select.off { background: #f7fafc !important; color: #4a5568; border-color: #a0aec0 !important; }
.att-select.h { background: #fffaf0 !important; color: #9c4221; border-color: #ed8936 !important; }
.att-select.pl { background: #ebf8ff !important; color: #2b6cb0; border-color: #4299e1 !important; }
.att-select.sd { background: #faf5ff !important; color: #6b46c1; border-color: #9f7aea !important; }
</style>

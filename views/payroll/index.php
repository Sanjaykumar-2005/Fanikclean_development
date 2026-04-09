<div class="panel active">
  <div class="sec-head">
    <div class="sec-meta">Compute payroll and generate salary slips.</div>
  </div>

  <div class="card mb16">
    <div class="card-head">
      <div class="card-title">Run Payroll Engine</div>
    </div>
    
    <form method="POST" action="/payroll/approve">
        <div class="form-grid">
            <div class="form-group">
                <label class="form-label">Payroll Month</label>
                <input type="month" name="month_year" class="form-input" value="<?= date('Y-m') ?>" required>
            </div>
            <div class="form-group" style="align-self: flex-end;">
                <button type="submit" class="btn btn-primary">Compute & Approve Payroll</button>
            </div>
        </div>
    </form>
  </div>

  <div class="stats-grid mb16">
    <div class="stat-card"><div class="stat-label">Gross Payroll</div><div class="stat-val" style="font-size:20px">--</div></div>
    <div class="stat-card"><div class="stat-label">Overtime Pay</div><div class="stat-val" style="font-size:20px">--</div></div>
    <div class="stat-card"><div class="stat-label">Net Payroll</div><div class="stat-val" style="font-size:20px;color:var(--teal)">--</div></div>
  </div>

  <div class="card">
    <div class="card-head">
      <div class="card-title">Payroll Records</div>
    </div>
    <div class="table-responsive">
      <table class="data-table">
        <thead>
          <tr>
            <th>Month</th>
            <th>Employee Name</th>
            <th>Category</th>
            <th>Days</th>
            <th>Basic ₹</th>
            <th>OT Hrs</th>
            <th>OT Pay ₹</th>
            <th>Net Pay ₹</th>
          </tr>
        </thead>
        <tbody>
          <?php if(empty($payrolls)): ?>
          <tr>
            <td colspan="8" style="text-align:center;color:#64748b;padding:2rem;">No payroll records generated yet.</td>
          </tr>
          <?php else: ?>
          <?php foreach($payrolls as $p): ?>
          <tr>
            <td><span class="badge" style="background:#e0e7ff;color:#4f46e5;"><?= htmlspecialchars($p['month_year']) ?></span></td>
            <td style="font-weight:600;"><?= htmlspecialchars($p['name']) ?></td>
            <td><span class="badge" style="background:#f1f5f9;color:#64748b;"><?= htmlspecialchars($p['category_name']) ?></span></td>
            <td><?= number_format($p['days_worked'], 1) ?></td>
            <td><?= number_format($p['basic_pay'], 2) ?></td>
            <td><?= number_format($p['ot_days'] * 8, 1) ?></td> <!-- converting days back to raw hours visually -->
            <td><?= number_format($p['ot_pay'], 2) ?></td>
            <td style="font-weight:bold;color:var(--teal);">₹<?= number_format($p['net_pay'], 2) ?></td>
          </tr>
          <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

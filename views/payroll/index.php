<div class="panel active">
  <div class="sec-head">
    <div class="sec-meta">Automated salary computation and payout tracking.</div>
  </div>

  <div class="card mb20">
    <div class="card-head">
      <div class="card-title">Run Payroll Computation</div>
    </div>
    
    <form method="POST" action="/payroll/approve">
        <div class="flex gap16 flex-wrap" style="align-items: flex-end;">
            <div class="form-group" style="flex: 1; min-width: 200px;">
                <label class="form-label">Processing Month</label>
                <input type="month" name="month_year" class="form-input" value="<?= date('Y-m') ?>" required>
            </div>
            <div>
              <button type="submit" class="btn btn-primary" style="padding: 10px 24px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 6px;"><path d="m5 14 6-6 6 6"/><path d="M12 8V22"/><path d="M5 2h14"/></svg>
                Compute & Approve
              </button>
            </div>
        </div>
    </form>
  </div>

  <?php
    $totalGross = 0; $totalOT = 0; $totalNet = 0;
    foreach($payrolls as $p) {
        $totalGross += $p['basic_pay'];
        $totalOT += $p['ot_pay'];
        $totalNet += $p['net_pay'];
    }
  ?>

  <!-- Payroll Stats -->
  <div class="stats-grid mb20">
    <div class="stat-card">
      <div class="stat-label">Gross Basic Pay</div>
      <div class="stat-val">₹<?= number_format($totalGross, 2) ?></div>
    </div>
    <div class="stat-card">
      <div class="stat-label">Overtime Expenditure</div>
      <div class="stat-val">₹<?= number_format($totalOT, 2) ?></div>
    </div>
    <div class="stat-card">
      <div class="stat-label">Net Payroll Total</div>
      <div class="stat-val" style="color: var(--primary);">₹<?= number_format($totalNet, 2) ?></div>
    </div>
  </div>

  <div class="card">
    <div class="card-head">
      <div class="card-title">Payroll Records Grid</div>
      <a href="/payroll/export" class="btn btn-sm">
        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 4px;"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/><path d="M8 13h8"/><path d="M8 17h8"/><path d="M10 9h1"/></svg>
        Export to Excel
      </a>
    </div>
    <div class="table-wrap">
      <table>
        <thead>
          <tr>
            <th>Month</th>
            <th>Employee Name</th>
            <th>Category</th>
            <th>Attendance</th>
            <th>Basic Pay</th>
            <th>OT Details</th>
            <th>Net Pay</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          <?php if(empty($payrolls)): ?>
          <tr><td colspan="8" style="text-align:center; padding: 40px; color: var(--text-muted);">No payroll data generated for the selected period.</td></tr>
          <?php else: ?>
          <?php foreach($payrolls as $p): ?>
          <tr>
            <td><span class="chip"><?= htmlspecialchars($p['month_year']) ?></span></td>
            <td class="bold"><?= htmlspecialchars($p['name']) ?></td>
            <td><span class="badge b-gray"><?= htmlspecialchars($p['category_name']) ?></span></td>
            <td><span class="fw6"><?= number_format($p['days_worked'], 1) ?></span> <span class="fs11 c-secondary">days</span></td>
            <td class="mono">₹<?= number_format($p['basic_pay'], 2) ?></td>
            <td class="mono">
              <div class="fs11 c-secondary"><?= number_format($p['ot_days'] * 8, 1) ?> hrs</div>
              <div class="fw6">₹<?= number_format($p['ot_pay'], 2) ?></div>
            </td>
            <td class="bold c-teal">₹<?= number_format($p['net_pay'], 2) ?></td>
            <td>
              <span class="badge <?= $p['status'] == 'Paid' ? 'b-green' : 'b-amber' ?>">
                <?= htmlspecialchars($p['status'] ?? 'Pending') ?>
              </span>
            </td>
          </tr>
          <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

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
</div>

<div class="panel active">
  <div class="sec-head">
    <div class="sec-meta">Generate new automated billing for a specific month.</div>
  </div>

  <div class="card mb16">
    <div class="card-head">
      <div class="card-title">Run Billing Engine</div>
    </div>
    
    <form method="POST" action="/billing/generate">
        <div class="form-grid">
            <div class="form-group">
                <label class="form-label">Billing Month</label>
                <input type="month" name="month_year" class="form-input" value="<?= date('Y-m') ?>" required>
            </div>
            <div class="form-group" style="align-self: flex-end;">
                <button type="submit" class="btn btn-primary">Generate Bills</button>
            </div>
        </div>
    </form>
  </div>

  <div class="card">
    <div class="form-section-title">Past Billing Entries (Example View)</div>
    <p>After clicking generate, the backend PostgreSQL calculation runs and creates invoices automatically.</p>
  </div>
</div>

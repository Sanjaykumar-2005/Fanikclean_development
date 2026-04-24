<div class="panel active">
  <div class="sec-head">
    <div class="sec-meta">Generate client-side billing summaries based on verified attendance records.</div>
  </div>

  <div class="card mb24">
    <div class="card-head">
      <div class="card-title">Run Automated Billing Engine</div>
      <div class="fs11 c-secondary">Calculates Man-days and GST for a specific date range</div>
    </div>
    
    <form method="POST" action="/billing/generate">
        <div class="flex gap16 flex-wrap" style="align-items: flex-end;">
            <div class="form-group" style="flex: 1; min-width: 160px;">
                <label class="form-label">From Date</label>
                <input type="date" name="from_date" class="form-input" value="<?= date('Y-m-01') ?>" required>
            </div>
            <div class="form-group" style="flex: 1; min-width: 160px;">
                <label class="form-label">To Date</label>
                <input type="date" name="to_date" class="form-input" value="<?= date('Y-m-t') ?>" required>
            </div>
            <div class="form-group" style="flex: 1; min-width: 180px;">
                <label class="form-label">Client (Optional)</label>
                <select name="client_id" id="billing-client" class="form-input" onchange="filterBillingSites()">
                    <option value="">All Clients</option>
                    <?php foreach ($clients as $c): ?>
                        <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['company_name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group" style="flex: 1; min-width: 180px;">
                <label class="form-label">Site (Optional)</label>
                <select name="site_id" id="billing-site" class="form-input">
                    <option value="">All Sites</option>
                    <?php foreach ($sites as $s): ?>
                        <option value="<?= $s['id'] ?>" data-client-id="<?= $s['client_id'] ?>"><?= htmlspecialchars($s['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
              <button type="submit" class="btn btn-primary" style="padding: 10px 24px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 6px;"><path d="M21 12V7a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h7"/><path d="M16 5V3"/><path d="M8 5V3"/><path d="M3 9h16"/><rect width="8" height="6" x="14" y="14" rx="1"/><path d="M18 17h.01"/></svg>
                Initiate Billing Run
              </button>
            </div>
        </div>
    </form>
  </div>

  <div class="card">
    <div class="card-head">
      <div class="card-title">How it works</div>
    </div>
    <div class="wf-step">
      <div class="wf-circle wf-done">1</div>
      <div>
        <div class="wf-title">Date Range Selection</div>
        <div class="wf-sub">Choose any custom date range — weekly, fortnightly, monthly, or any custom period you need.</div>
      </div>
    </div>
    <div class="wf-step">
      <div class="wf-circle wf-done">2</div>
      <div>
        <div class="wf-title">Data Aggregation</div>
        <div class="wf-sub">The system gathers all attendance records between those dates across the selected sites.</div>
      </div>
    </div>
    <div class="wf-step">
      <div class="wf-circle wf-done">3</div>
      <div>
        <div class="wf-title">Rate Mapping & GST</div>
        <div class="wf-sub">Site-specific rates are applied and standard 18% GST (CGST + SGST) is calculated automatically.</div>
      </div>
    </div>
    <div class="wf-step" style="border:none;">
      <div class="wf-circle wf-done">4</div>
      <div>
        <div class="wf-title">Invoice Queue</div>
        <div class="wf-sub">Generated bills appear in the <strong>Invoice Management</strong> section for final review.</div>
      </div>
    </div>
  </div>
</div>

<script>
function filterBillingSites() {
    const clientId = document.getElementById('billing-client').value;
    const siteSelect = document.getElementById('billing-site');
    const options = siteSelect.querySelectorAll('option');
    
    options.forEach(opt => {
        if (!opt.value) { opt.style.display = ''; return; }
        if (!clientId || opt.getAttribute('data-client-id') === clientId) {
            opt.style.display = '';
        } else {
            opt.style.display = 'none';
        }
    });
    
    const selected = siteSelect.options[siteSelect.selectedIndex];
    if (selected && selected.style.display === 'none') {
        siteSelect.value = '';
    }
}
</script>

<div class="panel active">
    <!-- Section 1: Data ready to be Invoiced -->
    <div class="card mb16">
        <div class="card-head">
            <div class="card-title">Pending Billing (Needs Invoice)</div>
        </div>
        <div class="table-wrap">
            <table>
                <thead><tr><th>Client / Site</th><th>Month</th><th>System Total</th><th>Action</th></tr></thead>
                <tbody>
                    <?php if (empty($pendingBills)): ?>
                        <tr><td colspan="4" style="text-align:center;">No pending bills to invoice.</td></tr>
                    <?php endif; ?>
                    <?php foreach($pendingBills as $b): ?>
                    <tr>
                        <td class="bold"><?= htmlspecialchars($b['company_name']) ?></td>
                        <td><?= htmlspecialchars($b['month_year']) ?></td>
                        <td class="mono">₹<?= number_format($b['grand_total'], 2) ?></td>
                        <td>
                            <form method="POST" action="/invoices/generate" style="margin:0;">
                                <input type="hidden" name="billing_id" value="<?= $b['id'] ?>">
                                <button type="submit" class="btn btn-primary btn-sm">Generate Invoice</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Section 2: Historical Sent Invoices -->
    <div class="card">
        <div class="card-head">
            <div class="card-title">All Generated Invoices</div>
        </div>
        <div class="table-wrap">
            <table>
                <thead><tr><th>Invoice No.</th><th>Client</th><th>Issue Date</th><th>Total</th><th>Status</th><th>Action</th></tr></thead>
                <tbody>
                    <?php if (empty($invoices)): ?>
                        <tr><td colspan="6" style="text-align:center;">No invoices generated yet.</td></tr>
                    <?php endif; ?>
                    <?php foreach($invoices as $inv): ?>
                    <tr>
                        <td class="mono bold"><?= htmlspecialchars($inv['invoice_no']) ?></td>
                        <td><?= htmlspecialchars($inv['company_name']) ?></td>
                        <td><?= htmlspecialchars($inv['issue_date']) ?></td>
                        <td class="mono">₹<?= number_format($inv['amount'], 2) ?></td>
                        <td>
                            <span class="badge b-<?= $inv['status'] === 'Paid' ? 'green' : 'amber' ?>">
                                <?= htmlspecialchars($inv['status']) ?>
                            </span>
                        </td>
                        <td>
                            <div style="display:flex; gap:8px;">
                                <?php if($inv['status'] !== 'Paid'): ?>
                                <form method="POST" action="/invoices/pay" style="margin:0;">
                                    <input type="hidden" name="invoice_no" value="<?= $inv['invoice_no'] ?>">
                                    <button type="submit" class="btn btn-outline btn-sm">Mark Paid</button>
                                </form>
                                <?php else: ?>
                                    <span style="color:#22c55e;font-weight:bold;align-self:center;">✔ Settled</span>
                                <?php endif; ?>
                                <a href="/invoices/print?inv=<?= $inv['invoice_no'] ?>" target="_blank" class="btn btn-primary btn-sm" style="text-decoration:none;">🖨️ Print</a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice - <?= $invoice_no ?></title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; color: #1a202c; line-height: 1.5; margin: 0; padding: 40px; }
        .header { display: flex; justify-content: space-between; margin-bottom: 60px; border-bottom: 2px solid #edf2f7; padding-bottom: 20px; }
        .logo-box h1 { margin: 0; font-size: 24px; color: #3182ce; letter-spacing: -0.5px; }
        .invoice-meta { text-align: right; }
        .invoice-meta h2 { margin: 0; font-size: 28px; color: #2d3748; }
        
        .contact-info { display: grid; grid-template-columns: 1fr 1fr; gap: 40px; margin-bottom: 40px; }
        .section-label { font-size: 11px; font-weight: 700; color: #a0aec0; text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 8px; }
        .address-box { font-size: 14px; }
        
        table { width: 100%; border-collapse: collapse; margin-bottom: 40px; }
        th { background: #f7fafc; text-align: left; padding: 12px 16px; font-size: 12px; font-weight: 600; color: #4a5568; border-bottom: 2px solid #edf2f7; }
        td { padding: 16px; border-bottom: 1px solid #edf2f7; font-size: 14px; }
        .text-right { text-align: right; }
        
        .totals { margin-left: auto; width: 300px; }
        .total-row { display: flex; justify-content: space-between; padding: 8px 0; }
        .total-row.grand { border-top: 2px solid #2d3748; margin-top: 8px; padding-top: 12px; font-weight: 700; font-size: 18px; }
        
        .footer { margin-top: 100px; font-size: 12px; color: #718096; border-top: 1px solid #edf2f7; padding-top: 20px; }
        @media print { .no-print { display: none; } }
    </style>
</head>
<body>
    <div class="no-print" style="margin-bottom: 20px; text-align: right;">
        <button onclick="window.print()" style="padding: 10px 20px; background: #3182ce; color: white; border: none; border-radius: 4px; cursor: pointer;">Print Document</button>
    </div>

    <div class="header">
        <div class="logo-box">
            <h1>FANIKCLEAN SERVICES</h1>
            <p style="font-size: 12px; color: #718096; margin: 4px 0;">Premium Facility Management Solutions</p>
        </div>
        <div class="invoice-meta">
            <h2>INVOICE</h2>
            <p style="margin: 4px 0; color: #4a5568;"># <?= $invoice_no ?></p>
            <p style="font-size: 13px; color: #718096;">Date: <?= date('d M Y', strtotime($issue_date)) ?></p>
        </div>
    </div>

    <div class="contact-info">
        <div>
            <div class="section-label">Billed To</div>
            <div class="address-box">
                <strong><?= htmlspecialchars($client_name) ?></strong><br>
                <?= nl2br(htmlspecialchars($client_address)) ?><br>
                <strong>GSTIN:</strong> <?= htmlspecialchars($client_gstin) ?>
            </div>
        </div>
        <div>
            <div class="section-label">Service Location</div>
            <div class="address-box">
                <strong><?= htmlspecialchars($site_name) ?></strong><br>
                <?= nl2br(htmlspecialchars($site_address)) ?><br>
                <strong>Period:</strong> <?= date('F Y', strtotime($month_year)) ?>
            </div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Description of Services</th>
                <th class="text-right">Man-Days / Qty</th>
                <th class="text-right">Rate</th>
                <th class="text-right">Amount</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($items as $item): ?>
            <tr>
                <td><?= htmlspecialchars($item['description']) ?></td>
                <td class="text-right"><?= number_format($item['quantity'], 1) ?></td>
                <td class="text-right">₹<?= number_format($item['rate'], 2) ?></td>
                <td class="text-right">₹<?= number_format($item['quantity'] * $item['rate'], 2) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="totals">
        <div class="total-row">
            <span>Subtotal</span>
            <span>₹<?= number_format($amount, 2) ?></span>
        </div>
        <div class="total-row">
            <span>Tax (18% GST)</span>
            <span>Included</span>
        </div>
        <div class="total-row grand">
            <span>Grand Total</span>
            <span>₹<?= number_format($amount, 2) ?></span>
        </div>
    </div>

    <div class="footer">
        <p><strong>Note:</strong> Please make payments within 15 days from the date of issue. Accounts: Fanikclean Services, HDFC Bank, A/C: 123456789, IFSC: HDFC0001234.</p>
        <p style="text-align: center; margin-top: 40px;">This is a computer generated document, no signature required.</p>
    </div>
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice - <?= $invoice_no ?></title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap');
        body { font-family: 'Outfit', sans-serif; color: #1e293b; background: #f8fafc; margin: 0; padding: 0; }
        .page { background: white; max-width: 800px; margin: 40px auto; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1); border-radius: 12px; overflow: hidden; }
        
        .accent-bar { height: 8px; background: linear-gradient(90deg, #0f172a, #334155); }
        .header { padding: 48px; display: flex; justify-content: space-between; border-bottom: 1px solid #f1f5f9; }
        .brand h1 { margin: 0; font-size: 28px; font-weight: 700; color: #0f172a; }
        .brand p { margin: 4px 0 0; color: #64748b; font-size: 14px; }
        
        .inv-summary { text-align: right; }
        .inv-summary h2 { margin: 0; font-size: 32px; color: #0f172a; }
        .inv-summary p { margin: 4px 0; color: #64748b; }
        
        .container { padding: 48px; }
        .meta-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 48px; margin-bottom: 48px; }
        .meta-box h4 { margin: 0 0 12px; font-size: 11px; text-transform: uppercase; letter-spacing: 0.1em; color: #94a3b8; }
        .meta-box p { margin: 0; font-size: 15px; line-height: 1.6; }
        
        table { width: 100%; border-collapse: collapse; margin-bottom: 48px; }
        th { text-align: left; padding: 16px; border-bottom: 2px solid #f1f5f9; color: #64748b; font-weight: 600; font-size: 13px; }
        td { padding: 16px; border-bottom: 1px solid #f1f5f9; font-size: 15px; }
        .text-right { text-align: right; }
        
        .summary-box { background: #f1f5f9; border-radius: 8px; padding: 24px; width: 320px; margin-left: auto; }
        .row { display: flex; justify-content: space-between; margin-bottom: 12px; font-size: 14px; }
        .row.total { border-top: 1px solid #cbd5e1; padding-top: 12px; font-weight: 700; font-size: 18px; color: #0f172a; margin-bottom: 0; }
        
        .footer { padding: 48px; background: #0f172a; color: #94a3b8; font-size: 13px; }
        .footer strong { color: white; }
        
        @media print { 
            body { background: white; margin: 0; }
            .page { border-radius: 0; box-shadow: none; margin: 0; max-width: 100%; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="no-print" style="text-align:center; padding: 20px;">
        <button onclick="window.print()" style="padding: 12px 24px; background: #0f172a; color: white; border: none; border-radius: 6px; cursor: pointer; font-weight: 600;">Download Official Copy</button>
    </div>

    <div class="page">
        <div class="accent-bar"></div>
        <div class="header">
            <div class="brand">
                <h1>FANIKCLEAN</h1>
                <p>Operations & Management</p>
            </div>
            <div class="inv-summary">
                <h2>INVOICE</h2>
                <p>No: <?= $invoice_no ?></p>
                <p>Issued: <?= date('d M, Y', strtotime($issue_date)) ?></p>
            </div>
        </div>

        <div class="container">
            <div class="meta-grid">
                <div class="meta-box">
                    <h4>Customer Information</h4>
                    <p><strong><?= htmlspecialchars($client_name) ?></strong></p>
                    <p><?= nl2br(htmlspecialchars($client_address)) ?></p>
                    <p>GST: <?= htmlspecialchars($client_gstin) ?></p>
                </div>
                <div class="meta-box">
                    <h4>Service Operations</h4>
                    <p><strong><?= htmlspecialchars($site_name) ?></strong></p>
                    <p><?= nl2br(htmlspecialchars($site_address)) ?></p>
                    <p>Billing Period: <?= !empty($from_date) ? date('d M Y', strtotime($from_date)) . ' – ' . date('d M Y', strtotime($to_date)) : date('F Y', strtotime($month_year)) ?></p>
                </div>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>SERVICE DESCRIPTION</th>
                        <th class="text-right">UNITS</th>
                        <th class="text-right">UNIT PRICE</th>
                        <th class="text-right">TOTAL</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($items as $item): ?>
                    <tr>
                        <td class="bold"><?= htmlspecialchars($item['description']) ?></td>
                        <td class="text-right"><?= number_format($item['quantity'], 1) ?></td>
                        <td class="text-right">₹<?= number_format($item['rate'], 2) ?></td>
                        <td class="text-right">₹<?= number_format($item['quantity'] * $item['rate'], 2) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div class="summary-box">
                <div class="row">
                    <span>Subtotal</span>
                    <span>₹<?= number_format($amount, 2) ?></span>
                </div>
                <div class="row">
                    <span>Tax Estimate (GST 18%)</span>
                    <span>Rs. 0.00</span>
                </div>
                <div class="row total">
                    <span>Total Amount</span>
                    <span>₹<?= number_format($amount, 2) ?></span>
                </div>
            </div>
        </div>

        <div class="footer">
            <div style="display:flex; justify-content: space-between; align-items:flex-end;">
                <div>
                    <p><strong>Payment Instructions:</strong></p>
                    <p>Bank: HDFC Corporate Banking</p>
                    <p>A/C Name: Fanikclean Services Pvt Ltd</p>
                    <p>A/C No: 5020001928374 / IFSC: HDFC0000102</p>
                </div>
                <div style="text-align:right">
                    <p>Questions? Contact support@fanikclean.com</p>
                    <p>&copy; <?= date('Y') ?> Fanikclean Global. All rights reserved.</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

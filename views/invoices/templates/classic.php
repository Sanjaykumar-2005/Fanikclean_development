<?php
function numberToWords($num) {
    if ($num == 0) return 'Zero';
    $num = str_replace(',', '', $num);
    $num = explode('.', $num);
    $rupees = $num[0];
    $paise = isset($num[1]) ? $num[1] : 0;
    
    $words = [];
    $units = ['', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine', 'Ten', 'Eleven', 'Twelve', 'Thirteen', 'Fourteen', 'Fifteen', 'Sixteen', 'Seventeen', 'Eighteen', 'Nineteen'];
    $tens = ['', '', 'Twenty', 'Thirty', 'Forty', 'Fifty', 'Sixty', 'Seventy', 'Eighty', 'Ninety'];
    
    $parts = [
        10000000 => 'Crore',
        100000 => 'Lakh',
        1000 => 'Thousand',
        100 => 'Hundred',
        1 => ''
    ];
    
    foreach ($parts as $val => $name) {
        if ($rupees >= $val) {
            $n = floor($rupees / $val);
            $rupees = $rupees % $val;
            
            if ($n < 20) {
                $words[] = $units[$n];
            } else {
                $words[] = $tens[floor($n / 10)];
                if ($n % 10 > 0) $words[] = $units[$n % 10];
            }
            if ($name) $words[] = $name;
        }
    }
    
    $res = implode(' ', $words) . ' Only';
    return $res;
}

// Convert month string "2026-03" to "MARCH 2026"
$dateStr = $month_year . '-01';
$monthTitle = strtoupper(date('F Y', strtotime($dateStr)));

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Invoice <?= htmlspecialchars($invoice_no) ?></title>
<style>
    @page { size: A4; margin: 15mm; }
    body { font-family: 'Times New Roman', serif; margin: 0; padding: 0; color: #000; font-size: 14px; line-height: 1.4; }
    .print-wrapper { max-width: 800px; margin: 0 auto; padding: 20px; background: #fff; }
    
    .header-row { display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 5px; }
    .header-title { font-weight: bold; font-size: 16px; margin: 0 0 0 150px; text-align: center; }
    .header-meta { font-size: 12px; font-weight: bold; text-align: left; }
    .header-meta .red-text { color: #cc0000; }
    .border-thick { border-bottom: 2px solid #5a2e8c; margin-bottom: 15px; }
    
    .to-section { margin-bottom: 20px; font-weight: bold; font-size: 14px; }
    .to-section p { margin: 2px 0; }
    .state-codes { display: flex; justify-content: space-between; margin-bottom: 10px; font-weight: bold; }
    
    table { width: 100%; border-collapse: collapse; margin-bottom: 5px; }
    th, td { border: 1px solid #000; padding: 5px 8px; text-align: right; }
    th { background-color: #99ccff; font-weight: bold; text-align: center; font-size: 12px; }
    td:nth-child(2), td:nth-child(3) { text-align: left; }
    .bold { font-weight: bold; }
    
    .vertical-code { writing-mode: vertical-rl; transform: rotate(180deg); text-align: center; border-right: none; }
    
    .bank-box { border: 1px solid #bb0000; text-align: center; padding: 10px; margin: 30px auto 20px auto; width: 80%; }
    .bank-box p { margin: 3px 0; font-weight: bold; font-size: 15px; }
    .bank-box .red { color: #cc0000; }
    
    .signatures { display: flex; justify-content: space-between; margin-top: 40px; margin-bottom: 40px; }
    .signatures strong { font-size: 14px; display:block; margin-bottom: 40px;}
    
    .footer { font-size: 11px; margin-top: auto; }
    .footer-reg { display: flex; justify-content: space-between; font-weight: bold; border-bottom: 2px solid #5a2e8c; padding-bottom: 5px; margin-bottom: 5px; }
    .footer-stamp { color: #fff; background: #ea3a29; padding: 4px 8px; border-radius: 4px; display: inline-block;}
    .footer-services { text-align: justify; line-height: 1.2; }
    
    /* Watermark simulated */
    .watermark { position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); opacity: 0.1; z-index: -1; pointer-events: none; width: 300px; }
    
    @media print {
        body { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
    }
</style>
</head>
<body onload="window.print()">
<div class="print-wrapper" style="position:relative;">

    <!-- Optional Logo watermark -->
    <svg class="watermark" viewBox="0 0 100 100"><text x="50" y="50" text-anchor="middle" font-size="20" font-weight="bold" fill="#ff0000">FANIK CLEAN</text></svg>

    <!-- Header -->
    <div class="header-row">
        <div style="flex: 1;"></div>
        <div class="header-title" style="flex: 2; align-self: flex-end; padding-bottom:10px;"><?= $monthTitle ?> MONTH TAX INVOICE</div>
        <div class="header-meta" style="flex: 1;">
            <div style="display:grid; grid-template-columns: 90px 1fr;">
                <span>GSTIN:</span><span class="red-text">33AADCF8629A1ZY</span>
                <span>Invoice number</span><span>: <?= htmlspecialchars($invoice_no) ?></span>
                <span>Date</span><span>: <?= date('d-m-Y', strtotime($issue_date)) ?></span>
            </div>
        </div>
    </div>
    <div class="border-thick"></div>

    <!-- Details -->
    <div class="to-section">
        <p>To</p>
        <p><?= htmlspecialchars($company_name) ?><?= !empty($contact_person) ? ', ' . htmlspecialchars($contact_person) : '' ?></p>
        <p>GSTIN: <span class="red-text"><?= htmlspecialchars($gstin) ?></span></p>
        <p style="font-weight: normal; max-width: 50%"><?= nl2br(htmlspecialchars($address)) ?></p>
    </div>

    <!-- State mapping trick -->
    <?php 
       // Infer State from GSTIN (First 2 chars)
       $tngstCode = '33';
       $klgstCode = '32';
       $clientCode = substr(trim($gstin), 0, 2);
       if (!$clientCode || !is_numeric($clientCode)) { $clientCode = '32'; } // default
       $stateName = ($clientCode == '32') ? 'Trivandrum' : 'Local';
       $isIGST = ($clientCode !== $tngstCode);
    ?>
    <div class="state-codes">
        <div>TN State Code: <?= $tngstCode ?> &nbsp;&nbsp; KL State Code: <?= $klgstCode ?></div>
        <div>Place of Supply: <?= htmlspecialchars($stateName) ?></div>
    </div>

    <!-- Items Table -->
    <table>
        <thead>
            <tr>
                <th>SC <br>CODE</th>
                <th>S. NO</th>
                <th style="width: 30%;">DESCRIPTION</th>
                <th>QUANTITY</th>
                <th>RATE</th>
                <th>AMOUNT</th>
                <th><?= $isIGST ? 'IGST(18%)' : 'CGST(9%) SGST(9%)' ?></th>
                <th>TOTAL</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $sno = 1; 
            $grandAmt = 0; $grandTax = 0;
            ?>
            <tr>
                <!-- Vertical merged cell for SC CODE -->
                <td rowspan="8" class="vertical-code bold" style="border-right:1px solid #000; width:30px;">998513</td>
                <td colspan="7" style="padding:0; border:none;">
                    <table style="width:100%; border:none; margin:0;" cellpadding="0" cellspacing="0">
                        <?php foreach($items as $i): 
                            $qty = floatval($i['quantity']);
                            if ($qty <= 0) continue;
                            
                            $rate = floatval($i['rate']);
                            $amt = $qty * $rate;
                            $tax = $amt * 0.18;
                            $total = $amt + $tax;
                            
                            $grandAmt += $amt; $grandTax += $tax;
                        ?>
                        <tr>
                            <td class="bold" style="text-align:center; width:5%; border-top:none; border-left:none; border-bottom:1px solid #000;"><?= $sno++ ?></td>
                            <td class="bold" style="width: 31%; border-top:none; border-bottom:1px solid #000;"><?= htmlspecialchars($i['description']) ?></td>
                            <td class="bold" style="width: 10.5%; border-top:none; border-bottom:1px solid #000;"><?= number_format($qty, 1) ?></td>
                            <td class="bold" style="width: 10.5%; border-top:none; border-bottom:1px solid #000;"><?= number_format($rate, 2) ?></td>
                            <td class="bold" style="width: 14%; border-top:none; border-bottom:1px solid #000;"><?= number_format($amt, 2) ?></td>
                            <td class="bold" style="width: 14%; border-top:none; border-bottom:1px solid #000;"><?= number_format($tax, 2) ?></td>
                            <td class="bold" style="width: 14%; border-top:none; border-right:none; border-bottom:1px solid #000;"><?= number_format($total, 2) ?></td>
                        </tr>
                        <?php endforeach; ?>
                        
                        <!-- Static Subtotals -->
                        <tr>
                            <td colspan="4" class="bold" style="text-align:right; border-top:none; border-left:none; border-bottom:1px solid #000;">Total</td>
                            <td class="bold" style="border-top:none; border-bottom:1px solid #000;"><?= number_format($grandAmt, 2) ?></td>
                            <td class="bold" style="border-top:none; border-bottom:1px solid #000;"><?= number_format($grandTax, 2) ?></td>
                            <td class="bold" style="border-top:none; border-right:none; border-bottom:1px solid #000;"><?= number_format($grandAmt + $grandTax, 2) ?></td>
                        </tr>
                        <tr>
                            <td colspan="6" class="bold" style="text-align:right; border:none; border-bottom:1px solid #000;">Total Amount</td>
                            <td class="bold" style="border:none; border-left: 1px solid #000; border-bottom:1px solid #000;"><?= number_format($grandAmt + $grandTax, 2) ?></td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="5" class="bold" style="text-align:right; border-top:none;">Total Amount Before Tax</td>
                <td colspan="2" class="bold"><?= number_format($grandAmt, 2) ?></td>
            </tr>
            <tr>
                <td colspan="5" class="bold" style="text-align:right;">Add Tax Amount</td>
                <td colspan="2" class="bold"><?= number_format($grandTax, 2) ?></td>
            </tr>
            <tr>
                <td colspan="5" class="bold" style="text-align:right;">Grand Total Amount</td>
                <td colspan="2" class="bold"><?= number_format($grandAmt + $grandTax, 2) ?></td>
            </tr>
            <tr>
                <td colspan="7" class="bold" style="text-align:center;">
                    Grand Total (<?= numberToWords(round($grandAmt + $grandTax)) ?>)
                </td>
            </tr>
        </tbody>
    </table>

    <div style="text-align:center; font-weight:bold; margin-top:20px;">
        CHECKS PAYABLE / AMOUNT TRANSFERED TO
    </div>
    
    <div class="bank-box">
        <p>Account Name: <span class="red">FANIK CLEAN FACILITY SERVICES PRIVATE LIMITED</span></p>
        <p>Account no: <span class="red">074402000001820</span></p>
        <p>IFSC Code: <span class="red">IOBA0000744</span></p>
        <p>Branch Name: <span class="red">Mangarai</span></p>
    </div>

    <div class="signatures">
        <div>
            <strong>For Fanik Clean Facility Services Pvt Ltd</strong>
            <div style="height: 60px; margin-bottom: 5px;">
                <!-- Simulated signature -->
                <svg width="100" height="40" xmlns="http://www.w3.org/2000/svg">
                  <path d="M10,20 Q30,5 50,20 T90,20" fill="none" stroke="black" stroke-width="2"/>
                </svg>
            </div>
            <span>(Subi C)</span>
        </div>
        <div style="margin-right: 50px;">
            <!-- Simulated company seal -->
            <div style="width: 80px; height: 80px; border-radius: 50%; border: 2px solid #5a2e8c; display:flex; align-items:center; justify-content:center; transform: rotate(-15deg); color:#5a2e8c; font-size:10px; font-weight:bold; text-align:center;">
                FANIK CLEAN<br>SEAL
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <div class="footer-reg">
            <div style="display:flex; align-items:center;">
                <span class="footer-stamp">FANIK<br>clean</span>
                <span style="margin-left: 10px;">Reg No: U74999TN2019PTC131771</span>
            </div>
            <div style="align-self: flex-end;">
                PAN No: AADCF8629A &nbsp;&nbsp; KLGST: <span style="font-weight:normal;">32AADCF8629A1Z0</span> &nbsp;&nbsp; TNGST: <span style="font-weight:normal;">33AADCF8629A1ZY</span>
            </div>
        </div>
        <div class="footer-services">
            <span class="bold" style="color: #5a2e8c;">Services:</span> Commercial Cleaning | Yearly Housekeeping Contract | Kitchen Stewards | laundry Service | Water Tank & Sump Cleaning | Façade & Glass Cleaning | Marble polishing | Carpet, Sofa & Mattress Shampooing | Residential Cleaning | Floor Scrubbing | Chandelier Cleaning<br>
            <span class="bold" style="color: #5a2e8c;">Contact & WhattsApp:</span> 62 38 58 37 64 &nbsp; <span class="bold" style="color: #5a2e8c;">Email:</span> office@fanikclean.in &nbsp; <span class="bold" style="color: #5a2e8c;">Website:</span> www.fanikclean.in<br>
            <span class="bold" style="color: #5a2e8c;">Office Address:</span> T.C 15/3009, Pattom, Marappalam, Trivandrum &nbsp; <span class="bold" style="color: #5a2e8c;">Branch Office:</span> SPA TOWER, No 7, Sri Ram Nagar, Coimbatore, Tamil Nadu<br>
            <span class="bold" style="color: #5a2e8c;">Registered Office:</span> Mundan Vilakam House, Paloor, Kottavilai, Karungal, Kanyakumari District
        </div>
    </div>

</div>
</body>
</html>

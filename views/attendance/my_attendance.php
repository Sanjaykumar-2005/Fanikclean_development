<div class="panel active">
  <div class="sec-head">
    <div class="sec-meta">Review your attendance records for the selected period. These logs are maintained by system administrators.</div>
    <form method="GET" action="/attendance/my" class="flex gap12">
      <input type="month" name="month" value="<?= $month ?>" class="form-input" onchange="this.form.submit()">
    </form>
  </div>

  <div class="grid grid-cols-12 gap24">
    <!-- SUMMARY CARDS -->
    <?php 
       $present = count(array_filter($history, fn($h) => $h['status'] == 'p'));
       $absent = count(array_filter($history, fn($h) => $h['status'] == 'a'));
       $half = count(array_filter($history, fn($h) => $h['status'] == 'h'));
    ?>
    <div class="col-span-12 lg:col-span-3">
      <div class="card p20 mb16" style="border-left: 4px solid var(--success);">
        <div class="fs11 c-secondary uppercase mb8">Days Present</div>
        <div class="fs24 bold"><?= $present ?></div>
      </div>
      <div class="card p20 mb16" style="border-left: 4px solid var(--danger);">
        <div class="fs11 c-secondary uppercase mb8">Days Absent</div>
        <div class="fs24 bold"><?= $absent ?></div>
      </div>
      <div class="card p20" style="border-left: 4px solid var(--amber);">
        <div class="fs11 c-secondary uppercase mb8">Half Days</div>
        <div class="fs24 bold"><?= $half ?></div>
      </div>
    </div>

    <!-- CALENDAR/LIST VIEW -->
    <div class="col-span-12 lg:col-span-9">
      <div class="card">
        <div class="table-wrap">
          <table>
            <thead>
              <tr>
                <th>Date</th>
                <th>Day</th>
                <th>Attendance Status</th>
                <th>Remarks</th>
              </tr>
            </thead>
            <tbody>
              <?php if(empty($history)): ?>
                <tr><td colspan="4" class="text-center c-secondary p24">No attendance records found for this month.</td></tr>
              <?php endif; ?>
              <?php foreach($history as $h): ?>
              <tr>
                <td class="bold"><?= date('d M Y', strtotime($h['attendance_date'])) ?></td>
                <td class="c-secondary"><?= date('l', strtotime($h['attendance_date'])) ?></td>
                <td>
                  <?php if($h['status'] == 'p'): ?>
                    <span class="badge b-green">Present</span>
                  <?php elseif($h['status'] == 'a'): ?>
                    <span class="badge b-red">Absent</span>
                  <?php elseif($h['status'] == 'h'): ?>
                    <span class="badge b-amber">Half Day</span>
                  <?php elseif($h['status'] == 'off'): ?>
                    <span class="badge b-gray">Weekly Off</span>
                  <?php endif; ?>
                </td>
                <td class="italic fs13"><?= htmlspecialchars($h['note'] ?? '-') ?></td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

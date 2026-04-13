<div class="panel active">
    <!-- Header -->
    <div class="sec-head">
        <div class="sec-meta">Process and manage employee leave applications.</div>
    </div>

    <!-- Active Requests Table -->
    <div class="card">
        <div class="card-head">
            <div class="card-title">Pending Leave Approvals</div>
            <div class="fs11 c-secondary">Verify and approve worker absences</div>
        </div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Worker Name</th>
                        <th>Site / Location</th>
                        <th>Leave Dates</th>
                        <th>Type</th>
                        <th>Reason</th>
                        <th>Status</th>
                        <th class="text-right">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($requests)): ?>
                        <tr><td colspan="7" style="text-align:center; padding: 40px; color: var(--text-muted);">No pending leave requests found.</td></tr>
                    <?php else: ?>
                        <?php foreach($requests as $r): ?>
                        <tr>
                            <td class="bold"><?= htmlspecialchars($r['worker_name']) ?></td>
                            <td><span class="c-secondary"><?= htmlspecialchars($r['site_name'] ?? 'Unassigned') ?></span></td>
                            <td class="mono fs12">
                                <?= date('d M', strtotime($r['from_date'])) ?> — <?= date('d M, Y', strtotime($r['to_date'])) ?>
                            </td>
                            <td><span class="badge b-gray"><?= htmlspecialchars($r['leave_type']) ?></span></td>
                            <td style="max-width: 200px;" class="fs12" title="<?= htmlspecialchars($r['reason']) ?>">
                                <?= htmlspecialchars(substr($r['reason'], 0, 50)) ?><?= strlen($r['reason']) > 50 ? '...' : '' ?>
                            </td>
                            <td>
                                <?php
                                    $sClass = 'b-amber';
                                    if ($r['status'] == 'Approved') $sClass = 'b-green';
                                    if ($r['status'] == 'Rejected') $sClass = 'b-red';
                                ?>
                                <span class="badge <?= $sClass ?>"><?= htmlspecialchars($r['status']) ?></span>
                            </td>
                            <td class="text-right">
                                <?php if($r['status'] == 'Pending'): ?>
                                <div class="flex gap8" style="justify-content: flex-end;">
                                    <form method="POST" action="/leave/approve" style="margin:0;">
                                        <input type="hidden" name="id" value="<?= $r['id'] ?>">
                                        <button type="submit" class="btn btn-sm btn-approve" title="Approve">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                                        </button>
                                    </form>
                                    <form method="POST" action="/leave/reject" style="margin:0;">
                                        <input type="hidden" name="id" value="<?= $r['id'] ?>">
                                        <button type="submit" class="btn btn-sm btn-danger" style="padding: 4px 8px;" title="Reject">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                        </button>
                                    </form>
                                </div>
                                <?php else: ?>
                                    <span class="fs11 c-secondary italic">Processed</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

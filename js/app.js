// ---- Modal Helpers ----
function openModal(id)  { 
    document.getElementById(id).classList.add('open'); 
    document.body.style.overflow='hidden'; 
}

function closeModal(id) { 
    document.getElementById(id).classList.remove('open'); 
    document.body.style.overflow=''; 
}

document.querySelectorAll('.modal-overlay').forEach(m => {
  m.addEventListener('click', e => { if (e.target === m) closeModal(m.id); });
});

// ---- Toast Notifications ----
let toastTimer;
function toast(msg, type='success') {
    const el = document.getElementById('toast');
    const ic = document.getElementById('toast-icon');
    if (!el || !ic) return;
    
    document.getElementById('toast-msg').textContent = msg;
    el.className = type;
    
    if (type === 'success') {
        ic.innerHTML = '<svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6L9 17l-5-5"/></svg>';
    } else if (type === 'warn') {
        ic.innerHTML = '<svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>';
    } else {
        ic.innerHTML = '<svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>';
    }
    
    el.classList.add('show');
    clearTimeout(toastTimer);
    toastTimer = setTimeout(() => el.classList.remove('show'), 3500);
}

// ---- Worker Management ----
function openAddWorkerModal() {
    const form = document.getElementById('worker-form');
    if (!form) return;
    form.reset();
    form.action = '/workers/create';
    document.getElementById('worker-id').value = '';
    document.getElementById('worker-modal-title').textContent = 'Add New Worker';
    openModal('modal-add-worker');
}

function openEditWorkerModal(w) {
    const form = document.getElementById('worker-form');
    if (!form) return;
    form.action = '/workers/update';
    document.getElementById('worker-modal-title').textContent = 'Edit Worker Profile';
    
    document.getElementById('worker-id').value = w.id;
    document.getElementById('worker-full_name').value = w.full_name;
    document.getElementById('worker-mobile').value = w.mobile;
    document.getElementById('worker-aadhaar').value = w.aadhaar || '';
    document.getElementById('worker-doj').value = w.doj;
    document.getElementById('worker-category_id').value = w.category_id;
    document.getElementById('worker-site_id').value = w.site_id || '';
    document.getElementById('worker-status').value = w.status;
    
    // New Fields
    document.getElementById('worker-esi_number').value = w.esi_number || '';
    document.getElementById('worker-pf_number').value = w.pf_number || '';
    document.getElementById('worker-age').value = w.age || '';
    document.getElementById('worker-experience').value = w.experience || '';
    document.getElementById('worker-uniform_issue_date').value = w.uniform_issue_date || '';
    document.getElementById('worker-uniform_details').value = w.uniform_details || '';
    
    openModal('modal-add-worker');
}

// ---- User Management ----
function openAddUserModal() {
    openModal('modal-add-user');
}

function openEditUserModal(u) {
    const form = document.getElementById('user-form');
    if (!form) return;
    document.getElementById('edit-user-id').value = u.id;
    document.getElementById('edit-user-name').value = u.full_name;
    document.getElementById('edit-user-role').value = u.role_id;
    document.getElementById('edit-user-site').value = u.site_id || '';
    document.getElementById('edit-user-status').value = u.status;
    openModal('modal-edit-user');
}

// ============================================================
// FANIKCLEAN — app.js
// All navigation, modals, attendance, expenses, templates, etc.
// ============================================================

// ---- PAGE DEFINITIONS ----
const pages = {
  dashboard:  { bc:'Overview',       title:'Dashboard'          },
  users:      { bc:'People',         title:'User Management'    },
  workers:    { bc:'People',         title:'Worker Management'  },
  clients:    { bc:'People',         title:'Clients & Sites'    },
  attendance: { bc:'Operations',     title:'Attendance'         },
  leave:      { bc:'Operations',     title:'Leave Management'   },
  payroll:    { bc:'Operations',     title:'Payroll'            },
  expenses:   { bc:'Operations',     title:'Expense Management' },
  billing:    { bc:'Finance',        title:'Billing Engine'     },
  invoices:   { bc:'Finance',        title:'Invoice Management' },
  financial:  { bc:'Finance',        title:'Financial Tracking' },
  rates:      { bc:'Configuration',  title:'Rate Configuration' },
  reports:    { bc:'Configuration',  title:'Reports & Export'   },
  audit:      { bc:'Configuration',  title:'Audit Log'          },
  settings:   { bc:'Configuration',  title:'Language & Policy'  },
};

// ---- NAVIGATION ----
function switchPanel(key) {
  document.querySelectorAll('.nav-item').forEach(n => {
    n.classList.toggle('active', n.dataset.panel === key);
  });
  document.querySelectorAll('.panel').forEach(p => {
    p.classList.toggle('active', p.id === 'panel-' + key);
  });
  const pg = pages[key] || { bc: '', title: key };
  document.getElementById('page-bc').textContent  = pg.bc;
  document.getElementById('page-ttl').textContent = pg.title;
  if (key === 'attendance') buildAtt();
  window.scrollTo(0, 0);
}

document.querySelectorAll('.nav-item').forEach(n => {
  n.addEventListener('click', () => {
    if (n.dataset.panel) switchPanel(n.dataset.panel);
  });
});

// ---- MODAL ----
function openModal(id)  {
  const el = document.getElementById(id);
  if (el) { el.classList.add('open'); document.body.style.overflow = 'hidden'; }
}
function closeModal(id) {
  const el = document.getElementById(id);
  if (el) { el.classList.remove('open'); document.body.style.overflow = ''; }
}
document.querySelectorAll('.modal-overlay').forEach(m => {
  m.addEventListener('click', e => { if (e.target === m) closeModal(m.id); });
});

// ---- TOAST ----
let toastTimer;
function toast(msg, type = 'success') {
  const el = document.getElementById('toast');
  const ic = document.getElementById('toast-icon');
  if (!el || !ic) return;
  document.getElementById('toast-msg').textContent = msg;
  el.className = type;
  if (type === 'success') ic.innerHTML = '<path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>';
  else if (type === 'warn') ic.innerHTML = '<path d="M1 21h22L12 2 1 21zm12-3h-2v-2h2v2zm0-4h-2v-4h2v4z"/>';
  else ic.innerHTML = '<path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/>';
  el.classList.add('show');
  clearTimeout(toastTimer);
  toastTimer = setTimeout(() => el.classList.remove('show'), 2800);
}

// ---- ATTENDANCE ----
const workerList = [
  'Arjun Swamy', 'Kavitha Nair', 'Mohan Das', 'Lakshmi P',
  'Ramesh B', 'Sundar K', 'Priya M', 'Rajan T', 'Deepika V'
];
const attState = {};
workerList.forEach((_, i) => attState[i] = 'p');

function buildAtt() {
  const body = document.getElementById('att-body');
  if (!body) return;
  body.innerHTML = '';
  let pc = 0, ac = 0, hc = 0;
  workerList.forEach((w, i) => {
    const v = attState[i];
    if (v === 'p') pc++; else if (v === 'a') ac++; else hc++;
    const row = document.createElement('div');
    row.className = 'att-row';
    const cls = s => 'att-btn att-' + s + (v === s ? ' att-a' + s : '');
    row.innerHTML =
      `<span class="att-name">${w}</span>` +
      `<button class="${cls('p')}" onclick="setAtt(${i},'p')">Present</button>` +
      `<button class="${cls('a')}" onclick="setAtt(${i},'a')">Absent</button>` +
      `<button class="${cls('h')}" onclick="setAtt(${i},'h')">Half-day</button>` +
      `<input type="number" min="0" max="8" step="0.5" placeholder="0" style="width:68px;padding:5px 8px;border:1px solid var(--border2);border-radius:6px;font-size:12px;font-family:'DM Sans',sans-serif;color:var(--text);background:var(--card)">` +
      `<input type="text" placeholder="Note..." style="width:100%;padding:5px 8px;border:1px solid var(--border2);border-radius:6px;font-size:12px;font-family:'DM Sans',sans-serif;color:var(--text);background:var(--card)">`;
    body.appendChild(row);
  });
  const pEl = document.getElementById('att-p-count');
  const aEl = document.getElementById('att-a-count');
  const hEl = document.getElementById('att-h-count');
  if (pEl) pEl.textContent = pc + ' Present';
  if (aEl) aEl.textContent = ac + ' Absent';
  if (hEl) hEl.textContent = hc + ' Half-day';
}

window.setAtt = function(i, val) {
  attState[i] = val;
  buildAtt();
};

function bulkAtt(v) {
  workerList.forEach((_, i) => attState[i] = v);
  buildAtt();
}

// ---- PHOTO UPLOAD ----
window.handlePhotoClick = function(inputId) {
  document.getElementById(inputId).click();
};

window.handlePhotoChange = function(inputEl, previewId, boxId) {
  const file = inputEl.files[0];
  if (!file) return;
  const reader = new FileReader();
  reader.onload = e => {
    const prev = document.getElementById(previewId);
    const box  = document.getElementById(boxId);
    if (prev) { prev.src = e.target.result; prev.style.display = 'block'; }
    if (box)  { box.style.display = 'none'; }
  };
  reader.readAsDataURL(file);
};

// ---- INVOICE TEMPLATE SELECTION ----
window.selectTemplate = function(name) {
  document.querySelectorAll('.tpl-card').forEach(c => c.classList.remove('selected'));
  const sel = document.getElementById('tpl-' + name);
  if (sel) sel.classList.add('selected');
  // update preview area
  const previewArea = document.getElementById('inv-preview-area');
  if (!previewArea) return;
  const templates = {
    normal: `
      <div class="inv-box">
        <div class="inv-head">
          <div><div class="inv-brand">FanikClean</div><div class="inv-no">TAX INVOICE · #INV-2603 · March 2026</div></div>
          <div style="text-align:right;font-size:12px"><div class="fw7">FanikClean Services Pvt Ltd</div><div class="c-secondary">GSTIN: 29ABCDE1234F1Z5</div><div class="c-secondary">Srikakulam, AP</div></div>
        </div>
        <div style="font-size:12px;margin-bottom:12px"><div class="fw7">Bill To: Horizon Hotels</div><div class="c-secondary">16 Beach Road, Vizag</div></div>
        <table class="inv-tbl w-full">
          <tr><td>Supervisor × 78 days @ ₹750</td><td class="r">₹58,500</td></tr>
          <tr><td>Associate × 156 days @ ₹520</td><td class="r">₹81,120</td></tr>
          <tr><td>CGST @ 9%</td><td class="r">₹19,961</td></tr>
          <tr><td>SGST @ 9%</td><td class="r">₹19,961</td></tr>
          <tr class="total"><td>Grand Total</td><td class="r">₹2,61,702</td></tr>
        </table>
        <button class="btn btn-primary btn-sm" style="margin-top:12px" onclick="toast('Downloading PDF...','success')">Download PDF</button>
      </div>`,
    swiggy: `
      <div style="background:var(--navy);border-radius:14px;padding:22px;color:#fff">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:14px;padding-bottom:12px;border-bottom:1px solid rgba(255,255,255,.1)">
          <div><div style="font-size:20px;font-weight:800;color:var(--teal)">FanikClean</div><div style="font-size:10px;color:rgba(255,255,255,.4);text-transform:uppercase;letter-spacing:.8px;margin-top:2px">TAX INVOICE</div></div>
          <div style="text-align:right;font-size:11px;color:rgba(255,255,255,.5)"><div style="font-weight:700;color:#fff">#INV-2603</div><div>March 2026</div></div>
        </div>
        <div style="font-size:12px;margin-bottom:12px;color:rgba(255,255,255,.7)"><div style="font-weight:700;color:#fff;margin-bottom:2px">Horizon Hotels</div><div>16 Beach Road, Vizag</div></div>
        <table style="width:100%;border-collapse:collapse;font-size:12px">
          <tr style="border-bottom:1px solid rgba(255,255,255,.08)"><td style="padding:7px 0;color:rgba(255,255,255,.65)">Supervisor × 78d @ ₹750</td><td style="text-align:right;color:#fff;font-weight:600">₹58,500</td></tr>
          <tr style="border-bottom:1px solid rgba(255,255,255,.08)"><td style="padding:7px 0;color:rgba(255,255,255,.65)">Associate × 156d @ ₹520</td><td style="text-align:right;color:#fff;font-weight:600">₹81,120</td></tr>
          <tr style="border-bottom:1px solid rgba(255,255,255,.08)"><td style="padding:7px 0;color:rgba(255,255,255,.65)">CGST + SGST @ 18%</td><td style="text-align:right;color:#fff;font-weight:600">₹39,922</td></tr>
          <tr><td style="padding-top:10px;font-weight:800;color:#fff">Total</td><td style="text-align:right;font-size:16px;font-weight:800;color:var(--teal);padding-top:10px">₹2,61,702</td></tr>
        </table>
        <button class="btn btn-sm" style="margin-top:14px;background:var(--teal);color:#fff;border:none" onclick="toast('Downloading PDF...','success')">Download PDF</button>
      </div>`,
    single: `
      <div style="border:2px solid var(--navy);border-radius:14px;padding:22px;text-align:center">
        <div style="font-size:18px;font-weight:800;color:var(--navy);letter-spacing:-.5px">FanikClean Services</div>
        <div style="height:2px;background:var(--teal);width:60px;margin:8px auto 6px;border-radius:1px"></div>
        <div style="font-size:11px;color:var(--text3);text-transform:uppercase;letter-spacing:.8px;margin-bottom:16px">TAX INVOICE · INV-2603</div>
        <div style="display:flex;justify-content:space-between;font-size:12px;margin-bottom:6px;text-align:left"><span>Horizon Hotels</span><span>March 2026</span></div>
        <hr style="border:none;border-top:1px solid var(--border);margin:10px 0">
        <table style="width:100%;border-collapse:collapse;font-size:12px">
          <tr><td style="padding:5px 0;text-align:left;color:var(--text2)">Supervisor (78d)</td><td style="text-align:right;font-weight:600">₹58,500</td></tr>
          <tr><td style="padding:5px 0;text-align:left;color:var(--text2)">Associate (156d)</td><td style="text-align:right;font-weight:600">₹81,120</td></tr>
          <tr><td style="padding:5px 0;text-align:left;color:var(--text2)">Tax (18%)</td><td style="text-align:right;font-weight:600">₹39,922</td></tr>
        </table>
        <hr style="border:none;border-top:2px solid var(--navy);margin:10px 0">
        <div style="display:flex;justify-content:space-between;font-weight:800;font-size:16px"><span>Total</span><span style="color:var(--teal)">₹2,61,702</span></div>
        <button class="btn btn-primary btn-sm" style="margin-top:14px;width:100%" onclick="toast('Downloading PDF...','success')">Download PDF</button>
      </div>`
  };
  previewArea.innerHTML = templates[name] || templates.normal;
  toast('Template "' + name.charAt(0).toUpperCase() + name.slice(1) + '" selected', 'success');
};

// ---- LANGUAGE SELECTION ----
window.selectLang = function(el) {
  document.querySelectorAll('.lang-option').forEach(l => l.classList.remove('selected'));
  el.classList.add('selected');
  const lang = el.dataset.lang;
  toast('Language set to ' + lang, 'success');
};

// ---- TOGGLE SWITCH ----
window.toggleSwitch = function(el) {
  el.classList.toggle('on');
};

// ---- EXPENSE TYPE SELECTION ----
window.selectExpType = function(el) {
  document.querySelectorAll('.exp-type-chip').forEach(c => c.classList.remove('selected'));
  el.classList.add('selected');
};

// ---- ROLE RESTRICTION HELPERS ----
const ROLE_ACCESS = {
  admin: ['dashboard','users','workers','clients','attendance','leave','payroll','expenses','billing','invoices','financial','rates','reports','audit','settings'],
  manager: ['dashboard','workers','clients','attendance','leave','reports'],
  field_officer: ['attendance','expenses']
};

window.checkRoleAccess = function(role, panel) {
  return ROLE_ACCESS[role] && ROLE_ACCESS[role].includes(panel);
};

// ---- ESI / PF CALCULATOR ----
window.calcESIPF = function() {
  const gross = parseFloat(document.getElementById('esi-gross-input')?.value || 0);
  if (!gross) return;
  const esi_emp = (gross * 0.0075).toFixed(2);
  const esi_er  = (gross * 0.0325).toFixed(2);
  const pf_emp  = (gross * 0.12).toFixed(2);
  const pf_er   = (gross * 0.12).toFixed(2);
  const netTake = (gross - parseFloat(esi_emp) - parseFloat(pf_emp)).toFixed(2);
  const resEl = document.getElementById('esi-result');
  if (resEl) {
    resEl.innerHTML = `
      <div class="mini-row"><span class="mini-label">ESI (Employee 0.75%)</span><span class="mini-val c-red">-₹${esi_emp}</span></div>
      <div class="mini-row"><span class="mini-label">ESI (Employer 3.25%)</span><span class="mini-val c-amber">₹${esi_er}</span></div>
      <div class="mini-row"><span class="mini-label">PF (Employee 12%)</span><span class="mini-val c-red">-₹${pf_emp}</span></div>
      <div class="mini-row"><span class="mini-label">PF (Employer 12%)</span><span class="mini-val c-amber">₹${pf_er}</span></div>
      <div class="mini-row" style="font-size:14px"><span class="fw7">Net Take-home</span><span class="fw7 c-teal">₹${netTake}</span></div>`;
    resEl.style.display = 'block';
  }
};

// Add site row helper (inline since it's small)
function addSiteRow() {
  const list = document.getElementById('site-list');
  if (!list) return;
  const count = list.querySelectorAll('.form-grid').length + 1;
  const div = document.createElement('div');
  div.className = 'form-grid mb8';
  div.innerHTML = `
    <div class="form-group"><label class="form-label">Site ${count} Name</label><input class="form-input" type="text" placeholder="Site name"></div>
    <div class="form-group"><label class="form-label">Site ${count} Location</label><input class="form-input" type="text" placeholder="Full address"></div>`;
  list.appendChild(div);
}
// ---- INIT ----
buildAtt();
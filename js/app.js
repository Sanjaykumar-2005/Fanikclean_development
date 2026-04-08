
// ---- Navigation ----
const pages = {
  dashboard:  { bc:'Overview',       title:'Dashboard'          },
  users:      { bc:'People',         title:'User Management'    },
  workers:    { bc:'People',         title:'Worker Management'  },
  clients:    { bc:'People',         title:'Clients & Sites'    },
  attendance: { bc:'Operations',     title:'Attendance'         },
  leave:      { bc:'Operations',     title:'Leave Management'   },
  payroll:    { bc:'Operations',     title:'Payroll'            },
  billing:    { bc:'Finance',        title:'Billing Engine'     },
  invoices:   { bc:'Finance',        title:'Invoice Management' },
  financial:  { bc:'Finance',        title:'Financial Tracking' },
  rates:      { bc:'Configuration',  title:'Rate Configuration' },
  reports:    { bc:'Configuration',  title:'Reports & Export'   },
  audit:      { bc:'Configuration',  title:'Audit Log'          },
};

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

// ---- Modal ----
function openModal(id)  { document.getElementById(id).classList.add('open');    document.body.style.overflow='hidden'; }
function closeModal(id) { document.getElementById(id).classList.remove('open'); document.body.style.overflow='';       }
document.querySelectorAll('.modal-overlay').forEach(m => {
  m.addEventListener('click', e => { if (e.target === m) closeModal(m.id); });
});

// ---- Toast ----
let toastTimer;
function toast(msg, type='success') {
  const el = document.getElementById('toast');
  const ic = document.getElementById('toast-icon');
  document.getElementById('toast-msg').textContent = msg;
  el.className = type;
  if (type==='success') ic.innerHTML='<path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>';
  else if (type==='warn')  ic.innerHTML='<path d="M1 21h22L12 2 1 21zm12-3h-2v-2h2v2zm0-4h-2v-4h2v4z"/>';
  else                     ic.innerHTML='<path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/>';
  el.classList.add('show');
  clearTimeout(toastTimer);
  toastTimer = setTimeout(() => el.classList.remove('show'), 2800);
}

// ---- Attendance ----
const workerList = [
  'Arjun Swamy','Kavitha Nair','Mohan Das','Lakshmi P',
  'Ramesh B','Sundar K','Priya M','Rajan T','Deepika V'
];
const attState = {};
workerList.forEach((_, i) => attState[i] = 'p');

function buildAtt() {
  const body = document.getElementById('att-body');
  if (!body) return;
  body.innerHTML = '';
  let pc=0, ac=0, hc=0;
  workerList.forEach((w, i) => {
    const v = attState[i];
    if(v==='p') pc++; else if(v==='a') ac++; else hc++;
    const row = document.createElement('div');
    row.className = 'att-row';
    const cls = (s) => 'att-btn att-' + s + (v===s ? ' att-a'+s : '');
    row.innerHTML =
      `<span class="att-name">${w}</span>` +
      `<button class="${cls('p')}" onclick="setAtt(${i},'p')">Present</button>` +
      `<button class="${cls('a')}" onclick="setAtt(${i},'a')">Absent</button>` +
      `<button class="${cls('h')}" onclick="setAtt(${i},'h')">Half-day</button>` +
      `<input type="number" min="0" max="8" step="0.5" placeholder="0" style="width:68px;padding:5px 8px;border:1px solid var(--border2);border-radius:6px;font-size:12px;font-family:'DM Sans',sans-serif;color:var(--text);background:var(--card)">` +
      `<input type="text" placeholder="Optional note" style="width:100%;padding:5pxpx;border:1px solid var(--border2);border-radius:6px;font-size:12px;font-family:'DM Sans',sans-serif;color:var(--text);background:var(--card)">` +
      `<input type="text" placeholder="Optional note" style="width:100%;padding:5px 8px;border:1px solid var(--border2);border-radius:6px;font-size:12px;font-family:'DM Sans',sans-serif;color:var(--text);background:var(--card)">`;
    body.appendChild(row);
  });
  const pEl = document.getElementById('att-present');
  const aEl = document.getElementById('att-absent');
  const hEl = document.getElementById('att-half');
  if (pEl) pEl.textContent = pc;
  if (aEl) aEl.textContent = ac;
  if (hEl) hEl.textContent = hc;
}

function setAtt(i, val) {
  attState[i] = val;
  buildAtt();
}

function saveAtt() {
  toast('Attendance saved successfully!', 'success');
}


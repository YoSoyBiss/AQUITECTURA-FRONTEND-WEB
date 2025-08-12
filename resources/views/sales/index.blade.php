<!-- resources/views/sales/index.blade.php -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista de Ventas</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4e3d7; padding: 20px; }
        .container { background: #fff; padding: 20px; max-width: 1100px; margin: auto; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,.1); }
        h1 { text-align: center; color: #333; margin-bottom: 10px; }

        .toolbar { display: grid; grid-template-columns: repeat(6, 1fr); gap: 10px; align-items: end; margin: 14px 0 8px; }
        .toolbar .field { display: flex; flex-direction: column; gap: 6px; }
        .toolbar label { font-size: 12px; color: #333; }
        .toolbar input { padding: 8px 10px; border: 1px solid #ccc; border-radius: 6px; background: #fff; }
        .toolbar .actions { display: flex; gap: 10px; grid-column: span 6; }
        .btn { display: inline-block; padding: 10px 14px; border-radius: 6px; text-decoration: none; border: none; cursor: pointer; font-weight: 600; }
        .btn-primary { background: #28a745; color: #fff; }
        .btn-primary:hover { background: #218838; }
        .btn-secondary { background: #5a3e36; color: #fff; }
        .btn-secondary:hover { filter: brightness(1.05); }
        .btn-disabled { background: #ccc; color: #666; cursor: not-allowed; pointer-events: none; }
        .add-button { background: #8d6e63; color: white; text-decoration: none; border-radius: 6px; padding: 10px 16px; font-weight: bold; }

        .alert-success { background: #d4edda; padding: 10px; color: #155724; margin-bottom: 15px; border-radius: 6px; }
        .alert-error { background: #f8d7da; padding: 10px; color: #721c24; margin-bottom: 15px; border-radius: 6px; }

        table { width: 100%; border-collapse: collapse; margin-top: 14px; }
        th, td { padding: 10px; border: 1px solid #ddd; text-align: center; }
        th { background: #5a3e36; color: #fff; }
        tr:nth-child(even) { background: #93bab0ff; }

        .pill { display:inline-block; padding: 3px 8px; border-radius: 999px; background:#e9ecef; font-size:12px; }
        .actions-cell { display:flex; gap:8px; justify-content:center; }
        .toggle-btn { padding: 6px 10px; border-radius: 6px; border: 1px solid #5a3e36; background: #fff; color:#5a3e36; cursor:pointer; }
        .toggle-btn:hover { background: #f3e9e6; }

        /* Modal (ventana flotante) */
        .modal-backdrop {
            position: fixed; inset: 0; background: rgba(0,0,0,.45);
            display: none; align-items: center; justify-content: center; padding: 20px; z-index: 9999;
        }
        .modal {
            width: 100%; max-width: 780px; background: #fff; border-radius: 12px; overflow: hidden;
            box-shadow: 0 20px 60px rgba(0,0,0,.25);
        }
        .modal-header { display:flex; justify-content: space-between; align-items:center; padding: 14px 18px; background:#5a3e36; color:#fff; }
        .modal-body { padding: 16px 18px; }
        .modal-footer { padding: 10px 18px 16px; display:flex; gap: 10px; justify-content: flex-end; }
        .close-x { background: transparent; border: none; color: #fff; font-size: 20px; cursor: pointer; }

        .ticket { border: 1px dashed #ccc; padding: 14px; border-radius: 8px; }
        .ticket h3 { margin: 0 0 6px 0; }
        .kv { display: grid; grid-template-columns: 160px 1fr; gap: 6px 10px; margin-bottom: 10px; }
        .ticket table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .ticket th, .ticket td { border: 1px solid #eee; padding: 8px; text-align: center; }
        .ticket-total { text-align: right; margin-top: 10px; font-weight: 700; }

        /* Impresión: solo el ticket */
        @media print {
            body * { visibility: hidden !important; }
            #print-area, #print-area * { visibility: visible !important; }
            #print-area { position: fixed; left: 0; top: 0; width: 100%; }
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Lista de Ventas</h1>

    {{-- DEBUG TEMPORAL: Mostrar rol actual --}}
    <p><strong>Rol actual:</strong> {{ session('user_role') ?? 'no definido' }}</p>

    @if (session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert-error">{{ $errors->first() }}</div>
    @endif

    {{-- Acciones principales --}}
    <div style="display:flex; gap:10px; flex-wrap:wrap; align-items:center; justify-content:space-between; margin:8px 0 16px;">
        <div style="display:flex; gap:10px; align-items:center; flex-wrap:wrap;">
            
                <a class="btn btn-primary" href="{{ route('sales.createsales') }}">+ Crear Nueva Venta</a>
           
                <button class="btn btn-primary btn-disabled" disabled title="Solo administradores o vendedores pueden crear ventas">+ Crear Nueva Venta</button>
            

            <a href="{{ route('dashboard.redirect') }}" class="add-button">🏠📚 Menú principal</a>
        </div>
        <div style="display:flex; gap:10px;">
            @if (Route::has('sales.reporte'))
                <a class="btn btn-secondary" href="{{ route('sales.reporte') }}">📄 Ver reporte</a>
            @endif
            @if (Route::has('sales.descargarPDF'))
                <a class="btn btn-secondary" href="{{ route('sales.descargarPDF') }}">⬇️ Descargar PDF</a>
            @endif
        </div>
    </div>

    {{-- Filtros (cliente) --}}
    <div class="toolbar" id="filters">
        <div class="field">
            <label for="f-saleId">ID de venta</label>
            <input type="text" id="f-saleId" placeholder="Ej: 66bfa1..." />
        </div>
        <div class="field">
            <label for="f-productId">ID de producto</label>
            <input type="number" id="f-productId" placeholder="Ej: 101" />
        </div>
        <div class="field">
            <label for="f-userId">Usuario (ObjectId)</label>
            <input type="text" id="f-userId" placeholder="Ej: 66c0a3..." />
        </div>
        <div class="field">
            <label for="f-dateFrom">Fecha desde</label>
            <input type="date" id="f-dateFrom" />
        </div>
        <div class="field">
            <label for="f-dateTo">Fecha hasta</label>
            <input type="date" id="f-dateTo" />
        </div>
        <div class="actions">
            <button class="btn btn-secondary" id="btnApply">Aplicar filtros</button>
            <button class="btn" id="btnClear" style="border:1px solid #ccc; background:#fff;">Limpiar</button>
        </div>
    </div>

    {{-- Tabla de ventas --}}
    <table id="salesTable">
        <thead>
            <tr>
                <th>Venta</th>
                <th>Fecha</th>
                <th>Usuario</th>
                <th>Items</th>
                <th>Total</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody id="salesBody">
        @php $ventas = $sales ?? []; @endphp

        @forelse ($ventas as $sale)
            @php
                // --- Normalizaciones ---
                $saleIdRaw = $sale['_id'] ?? 'N/A';
                $saleId = is_array($saleIdRaw)
                    ? ($saleIdRaw['$oid'] ?? json_encode($saleIdRaw, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
                    : (string) $saleIdRaw;

                $date = \Carbon\Carbon::parse($sale['date'] ?? now())->format('Y-m-d');

                $userRaw = $sale['userId'] ?? 'N/A';
                $userIdStr = is_array($userRaw)
                    ? ($userRaw['$oid'] ?? json_encode($userRaw, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
                    : (string) $userRaw;

                // Mostrar abreviado bonito (66c0a3…b2e9)
                $userShort = strlen($userIdStr) > 12 ? substr($userIdStr, 0, 6).'…'.substr($userIdStr, -4) : $userIdStr;

                $details = is_array($sale['details'] ?? null) ? $sale['details'] : [];
                $itemsCount = count($details);
                $total = isset($sale['total']) ? number_format((float)$sale['total'], 2) : '0.00';

                // JSONs seguros para data-*
                $detailsJson = json_encode($details, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                if ($detailsJson === false) { $detailsJson = '[]'; }
            @endphp

            <tr class="sale-row"
                data-sale-id="{{ $saleId }}"
                data-user-id="{{ $userIdStr }}"
                data-date="{{ $date }}"
                data-details='{{ $detailsJson }}'
                data-total="{{ $total }}">
                <td><code>{{ $saleId }}</code></td>
                <td>{{ $date }}</td>
                <td>
                    <span class="pill" title="{{ $userIdStr }}">{{ $userShort }}</span>
                    <button class="toggle-btn" data-copy="{{ $userIdStr }}" title="Copiar ID de usuario">Copiar</button>
                </td>
                <td>{{ $itemsCount }}</td>
                <td>${{ $total }}</td>
                <td class="actions-cell">
                    <button class="toggle-btn open-modal" data-sale="{{ $saleId }}">Ver detalles</button>
                    @if (Route::has('sales.showsales'))
                        <a class="toggle-btn" href="{{ route('sales.showsales', $saleId) }}">Abrir</a>
                    @endif
                </td>
            </tr>
        @empty
            <tr><td colspan="6">No hay ventas registradas.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>

{{-- Modal (ventana flotante) --}}
<div class="modal-backdrop" id="modalBackdrop" aria-hidden="true">
  <div class="modal" role="dialog" aria-modal="true" aria-labelledby="modalTitle">
    <div class="modal-header">
      <h2 id="modalTitle">Detalle de venta</h2>
      <button class="close-x" id="btnCloseModal" aria-label="Cerrar">✕</button>
    </div>
    <div class="modal-body">
      <div id="print-area">
        <div class="ticket" id="ticket">
          <h3 id="t-venta">Venta: —</h3>
          <div class="kv">
            <div><strong>Fecha</strong></div><div id="t-fecha">—</div>
            <div><strong>Usuario</strong></div><div id="t-usuario">—</div>
            <div><strong>Items</strong></div><div id="t-items">—</div>
            <div><strong>Total</strong></div><div id="t-total">$0.00</div>
          </div>
          <table id="t-detalles">
            <thead>
              <tr>
                <th>#</th>
                <th>ID Producto</th>
                <th>Cantidad</th>
                <th>Precio Unitario</th>
                <th>Subtotal</th>
              </tr>
            </thead>
            <tbody><!-- rows dinámicas --></tbody>
          </table>
          <div class="ticket-total" id="t-total-bottom">Total: $0.00</div>
        </div>
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn btn-secondary" id="btnPrint">🖨️ Imprimir ticket</button>
      <button class="btn" id="btnCloseModal2" style="border:1px solid #ccc; background:#fff;">Cerrar</button>
    </div>
  </div>
</div>

<script>
// Copiar al portapapeles
document.querySelectorAll('button[data-copy]').forEach(btn => {
  btn.addEventListener('click', async () => {
    try {
      await navigator.clipboard.writeText(btn.getAttribute('data-copy') || '');
      btn.textContent = 'Copiado';
      setTimeout(() => btn.textContent = 'Copiar', 1200);
    } catch (e) { alert('No se pudo copiar'); }
  });
});

// Filtros en cliente
const $saleId   = document.getElementById('f-saleId');
const $productId= document.getElementById('f-productId');
const $userId   = document.getElementById('f-userId');
const $dateFrom = document.getElementById('f-dateFrom');
const $dateTo   = document.getElementById('f-dateTo');

function applyFilters() {
  const saleId = ($saleId?.value || '').trim().toLowerCase();
  const productId = ($productId?.value || '').trim();
  const userId = ($userId?.value || '').trim().toLowerCase();
  const dateFrom = $dateFrom?.value ? new Date($dateFrom.value) : null;
  const dateTo = $dateTo?.value ? new Date($dateTo.value) : null;

  document.querySelectorAll('#salesBody .sale-row').forEach(row => {
    const rSaleId = (row.dataset.saleId || '').toLowerCase();
    const rUserId = (row.dataset.userId || '').toLowerCase();
    const rDateStr = row.dataset.date || '';
    const rDate = rDateStr ? new Date(rDateStr + 'T00:00:00') : null;

    let details = [];
    try { details = JSON.parse(row.dataset.details || '[]') || []; } catch(e) {}

    let ok = true;

    if (saleId && !rSaleId.includes(saleId)) ok = false;
    if (userId && !rUserId.includes(userId)) ok = false;

    if (productId) {
      const pid = parseInt(productId, 10);
      if (!details.some(d => Number(d?.productId) === pid)) ok = false;
    }

    if (dateFrom && rDate && rDate < dateFrom) ok = false;
    if (dateTo && rDate && rDate > dateTo) ok = false;

    row.style.display = ok ? '' : 'none';
  });
}
document.getElementById('btnApply').addEventListener('click', applyFilters);
document.getElementById('btnClear').addEventListener('click', () => {
  [$saleId, $productId, $userId, $dateFrom, $dateTo].forEach(i => { if(i) i.value = ''; });
  applyFilters();
});

// Modal
const backdrop = document.getElementById('modalBackdrop');
const btnClose = document.getElementById('btnCloseModal');
const btnClose2= document.getElementById('btnCloseModal2');
const btnPrint = document.getElementById('btnPrint');

function openModalForRow(row) {
  if (!row) return;

  const saleId = row.dataset.saleId || '—';
  const date = row.dataset.date || '—';
  const user = row.dataset.userId || '—';
  const total = row.dataset.total || '0.00';

  // usuario abreviado bonito en el modal
  const userShort = (user.length > 12) ? (user.slice(0,6) + '…' + user.slice(-4)) : user;

  let details = [];
  try { details = JSON.parse(row.dataset.details || '[]') || []; } catch(e) {}

  // Rellenar cabecera del ticket
  document.getElementById('t-venta').textContent = 'Venta: ' + saleId;
  document.getElementById('t-fecha').textContent = date;
  document.getElementById('t-usuario').textContent = userShort + '  (' + user + ')';
  document.getElementById('t-total').textContent = '$' + total;

  const tbody = document.querySelector('#t-detalles tbody');
  tbody.innerHTML = '';
  let itemsCount = 0;
  details.forEach((d, idx) => {
    const p = Number(d?.productId || 0);
    const q = Number(d?.quantity || 0);
    const u = Number(d?.unitPrice || 0);
    const sub = (q * u).toFixed(2);
    itemsCount += 1;

    const tr = document.createElement('tr');
    tr.innerHTML = `<td>${idx+1}</td>
                    <td>${p}</td>
                    <td>${q}</td>
                    <td>$${u.toFixed(2)}</td>
                    <td>$${sub}</td>`;
    tbody.appendChild(tr);
  });

  document.getElementById('t-items').textContent = String(itemsCount);
  document.getElementById('t-total-bottom').textContent = 'Total: $' + total;

  // Mostrar modal
  backdrop.style.display = 'flex';
  backdrop.setAttribute('aria-hidden', 'false');
}

function closeModal() {
  backdrop.style.display = 'none';
  backdrop.setAttribute('aria-hidden', 'true');
}

document.querySelectorAll('.open-modal').forEach(btn => {
  btn.addEventListener('click', () => {
    const saleId = btn.getAttribute('data-sale');
    const row = document.querySelector(`.sale-row[data-sale-id="${saleId}"]`);
    openModalForRow(row);
  });
});
btnClose.addEventListener('click', closeModal);
btnClose2.addEventListener('click', closeModal);
backdrop.addEventListener('click', (e) => { if (e.target === backdrop) closeModal(); });

// Imprimir solo el ticket
btnPrint.addEventListener('click', () => {
  // usamos @media print con #print-area, solo llamamos a print
  window.print();
});
</script>
</body>
</html>

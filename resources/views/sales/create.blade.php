<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Registrar Venta</title>
  <style>
    :root{
      --primary:#28a745; --primary-700:#218838; --ink:#5a3e36; --bg:#f4e3d7; --card:#fff;
      --muted:#6b7280; --ring:#d1d5db;
    }
    *{box-sizing:border-box}
    body{font-family:Arial, sans-serif; background:var(--bg); padding:40px; color:#111;}
    .container{max-width:900px; margin:auto; background:var(--card); padding:28px; border-radius:14px; box-shadow:0 10px 30px rgba(0,0,0,.08)}
    h1{margin:0 0 18px; color:var(--ink); text-align:center}
    .lead{margin:-6px 0 20px; text-align:center; color:var(--muted)}
    .row{display:grid; gap:14px}
    .grid-2{grid-template-columns:1fr 1fr}
    label{font-weight:600; color:#333; font-size:14px}
    input, select{width:100%; padding:10px 12px; border:1px solid var(--ring); border-radius:8px; outline:none}
    input[readonly]{background:#f9fafb}
    .help{font-size:12px; color:var(--muted); margin-top:4px}
    .bar{display:flex; gap:10px; flex-wrap:wrap; justify-content:space-between; align-items:center; margin-top:16px}
    .btn{appearance:none; border:none; border-radius:10px; padding:10px 14px; font-weight:700; cursor:pointer}
    .btn-primary{background:var(--primary); color:#fff}
    .btn-primary:hover{background:var(--primary-700)}
    .btn-ghost{border:1px solid var(--ring); background:#fff; color:#111}
    .btn-danger{background:#ef4444; color:#fff}
    .btn-danger:hover{filter:brightness(.95)}
    .card{border:1px solid #eee; border-radius:12px; padding:16px}
    table{width:100%; border-collapse:separate; border-spacing:0 8px}
    th,td{padding:10px 8px; text-align:left}
    th{font-size:12px; text-transform:uppercase; letter-spacing:.04em; color:#555}
    tr{background:#fafafa}
    tr td:first-child, tr th:first-child{border-top-left-radius:10px; border-bottom-left-radius:10px}
    tr td:last-child, tr th:last-child{border-top-right-radius:10px; border-bottom-right-radius:10px}
    .right{text-align:right}
    .error{background:#fde2e1; color:#7a2321; padding:10px 12px; border-radius:8px; margin-bottom:12px}
    .total-box{display:flex; justify-content:flex-end; margin-top:8px}
    .total-card{min-width:260px; border:1px dashed #ddd; padding:14px 16px; border-radius:12px; background:#fff}
    .total-row{display:flex; justify-content:space-between; margin:4px 0}
    .total-row strong{font-size:18px}
    a.link{color:var(--ink); text-decoration:none}
    a.link:hover{text-decoration:underline}
  </style>
</head>
<body>
<div class="container">
  <h1>Registrar Nueva Venta</h1>
  <p class="lead">Agrega uno o más productos, verifica los importes y guarda la venta.</p>

  @if ($errors->any())
    <div class="error">
      <ul style="margin:0; padding-left:18px">
        @foreach ($errors->all() as $error)
          <li>{{ is_array($error) ? implode(', ', $error) : $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <form action="{{ route('sales.store') }}" method="POST" id="saleForm">
    @csrf

    <!-- Datos principales -->
    <div class="row grid-2">
      <div>
        <label for="userId">ID del Usuario</label>
        <input type="text" name="userId" id="userId" value="{{ old('userId') }}" placeholder="ObjectId del usuario" required>
        <div class="help">Pega el ObjectId del usuario (tu API espera <code>userId</code>).</div>
      </div>

      <div>
        <label for="date">Fecha</label>
        <input type="date" id="date" value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" readonly>
        <div class="help">La fecha la define el backend; se muestra aquí solo como referencia.</div>
      </div>
    </div>

    <!-- Items -->
    <div class="card" style="margin-top:18px">
      <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:8px">
        <h3 style="margin:0; color:var(--ink)">Productos</h3>
        <button type="button" class="btn btn-ghost" id="btnAddRow">+ Agregar producto</button>
      </div>

      <table id="itemsTable" aria-label="Productos en la venta">
        <thead>
          <tr>
            <th style="width:42%">Producto</th>
            <th style="width:14%" class="right">Cantidad</th>
            <th style="width:20%" class="right">P. Unitario</th>
            <th style="width:20%" class="right">Subtotal</th>
            <th style="width:4%"></th>
          </tr>
        </thead>
        <tbody id="itemsBody">
          <!-- La primera fila se inyecta desde JS para mantener el mismo flujo -->
        </tbody>
      </table>

      <div class="total-box">
        <div class="total-card">
          <div class="total-row"><span>Subtotal</span><span id="lblSubtotal">$0.00</span></div>
          <div class="total-row"><span>Impuestos (0%)</span><span id="lblTax">$0.00</span></div>
          <div class="total-row"><strong>Total</strong><strong id="lblTotal">$0.00</strong></div>
        </div>
      </div>

      <!-- Total para backend -->
      <input type="hidden" name="total" id="total" value="0.00">
    </div>

    <!-- Acciones -->
    <div class="bar">
      <a href="{{ route('sales.index') }}" class="link">← Cancelar y volver</a>
      <div style="display:flex; gap:10px">
        <button type="button" class="btn btn-ghost" id="btnClear">Limpiar productos</button>
        <button type="submit" class="btn btn-primary">Guardar venta</button>
      </div>
    </div>
  </form>
</div>

<!-- Template de fila -->
<template id="rowTemplate">
  <tr class="item-row">
    <td>
      <select class="productSelect" required>
        <option value="">-- Selecciona un producto --</option>
        @foreach ($products as $product)
          <option value="{{ $product['id'] }}"
                  data-price="{{ number_format((float)$product['price'], 2, '.', '') }}"
                  @if(isset($product['stock'])) data-stock="{{ (int)$product['stock'] }}" @endif>
            ID {{ $product['id'] }} — {{ $product['title'] }}
            (Precio: ${{ number_format((float)$product['price'], 2) }}
            @if(isset($product['stock'])) | Stock: {{ (int)$product['stock'] }} @endif)
          </option>
        @endforeach
      </select>
      <input type="hidden" class="field-productId" name="">
    </td>
    <td class="right">
      <input type="number" class="qtyInput" min="1" step="1" value="1" required>
    </td>
    <td class="right">
      <input type="number" class="unitInput" step="0.01" value="0.00" required readonly>
    </td>
    <td class="right">
      <input type="text" class="subInput" value="$0.00" readonly style="text-align:right">
    </td>
    <td class="right">
      <button type="button" class="btn btn-danger btnRemove" title="Quitar">✕</button>
    </td>
    <!-- Campos reales para el backend -->
    <input type="hidden" class="field-quantity" name="">
    <input type="hidden" class="field-unitPrice" name="">
  </tr>
</template>

<script>
  // ===== Dataset de productos desde <option> (para acceso rápido si lo necesitas)
  // No se envía al backend, es solo para UI
  const productsMeta = Array.from(document.querySelectorAll('#rowTemplate option[value]'))
    .map(opt => ({
      id: Number(opt.value),
      price: parseFloat(opt.dataset.price || '0'),
      stock: parseInt(opt.dataset.stock || '0', 10)
    }));

  const $tbody   = document.getElementById('itemsBody');
  const tpl      = document.getElementById('rowTemplate');
  const $lblSubtotal = document.getElementById('lblSubtotal');
  const $lblTax      = document.getElementById('lblTax');
  const $lblTotal    = document.getElementById('lblTotal');
  const $total       = document.getElementById('total');

  const $btnAddRow = document.getElementById('btnAddRow');
  const $btnClear  = document.getElementById('btnClear');
  const $form      = document.getElementById('saleForm');

  function money(n){ return '$' + (Number(n||0).toFixed(2)); }

  // Crea una fila y configura eventos
  function addRow(prefill = null){
    const node = tpl.content.firstElementChild.cloneNode(true);

    const sel = node.querySelector('.productSelect');
    const qty = node.querySelector('.qtyInput');
    const unit= node.querySelector('.unitInput');
    const sub = node.querySelector('.subInput');
    const btnR= node.querySelector('.btnRemove');

    const fPid  = node.querySelector('.field-productId');
    const fQty  = node.querySelector('.field-quantity');
    const fUnit = node.querySelector('.field-unitPrice');

    // Prefill (opcional)
    if (prefill && prefill.productId){
      sel.value = String(prefill.productId);
      unit.value = Number(prefill.unitPrice||0).toFixed(2);
      qty.value  = parseInt(prefill.quantity||1,10);
    }

    // Eventos
    sel.addEventListener('change', () => {
      const opt   = sel.options[sel.selectedIndex];
      const price = parseFloat(opt?.dataset?.price || '0');
      unit.value  = price.toFixed(2);

      // stock (si viene)
      const stockAttr = opt?.dataset?.stock;
      if (stockAttr != null){
        const stock = parseInt(stockAttr, 10);
        if (stock > 0){ qty.max = stock; } else { qty.removeAttribute('max'); }
      } else {
        qty.removeAttribute('max');
      }

      syncRow();
    });

    qty.addEventListener('input', syncRow);

    btnR.addEventListener('click', () => {
      node.remove();
      renumberRows();
      recalcTotals();
    });

    // Sincroniza inputs visibles con los hidden que va a leer el backend
    function syncRow(){
      const pid = Number(sel.value || 0);
      const q   = Math.max(1, parseInt(qty.value || 1,10));
      qty.value = q;

      // Si hay max (stock) respétalo
      if (qty.max){
        const m = parseInt(qty.max,10);
        if (q > m){ qty.value = m; }
      }

      const u   = parseFloat(unit.value || '0');
      const subtotal = q * u;

      sub.value = money(subtotal);

      fPid.value  = pid ? pid : '';
      fQty.value  = q;
      fUnit.value = u.toFixed(2);

      recalcTotals();
    }

    // Inserta y numera
    $tbody.appendChild(node);
    renumberRows();
    // Si no venía prefill, inicializar con el primer valor del select (si lo desea el usuario lo cambia)
    if (!prefill){ syncRow(); }
  }

  // Renumera los name="details[i][campo]" para todas las filas
  function renumberRows(){
    const rows = $tbody.querySelectorAll('.item-row');
    rows.forEach((row, i) => {
      row.querySelector('.field-productId').setAttribute('name', `details[${i}][productId]`);
      row.querySelector('.field-quantity').setAttribute('name', `details[${i}][quantity]`);
      row.querySelector('.field-unitPrice').setAttribute('name', `details[${i}][unitPrice]`);
    });
  }

  // Recalcula totales globales
  function recalcTotals(){
    let sum = 0;
    $tbody.querySelectorAll('.item-row').forEach(row => {
      const qty  = parseFloat(row.querySelector('.field-quantity')?.value || '0');
      const unit = parseFloat(row.querySelector('.field-unitPrice')?.value || '0');
      sum += (qty * unit);
    });

    const tax = 0; // Si más adelante agregas IVA, calcúlalo aquí
    const total = sum + tax;

    $lblSubtotal.textContent = money(sum);
    $lblTax.textContent      = money(tax);
    $lblTotal.textContent    = money(total);
    $total.value             = total.toFixed(2);
  }

  // Limpiar todas las filas
  function clearRows(){
    $tbody.innerHTML = '';
    addRow(); // deja una fila base
    recalcTotals();
  }

  // Validación básica antes de enviar
  $form.addEventListener('submit', (e) => {
    const rows = $tbody.querySelectorAll('.item-row');
    if (rows.length === 0){
      e.preventDefault();
      alert('Agrega al menos un producto.');
      return;
    }
    // Verifica que todos tengan productId
    for (const row of rows){
      const pid = row.querySelector('.field-productId').value;
      if (!pid){
        e.preventDefault();
        alert('Hay una fila sin producto seleccionado.');
        return;
      }
    }
    // Total no negativo
    if (parseFloat($total.value || '0') <= 0){
      e.preventDefault();
      alert('El total debe ser mayor a 0.');
      return;
    }
  });

  // Botones
  $btnAddRow.addEventListener('click', () => addRow());
  $btnClear.addEventListener('click', clearRows);

  // Inicializar con una fila
  addRow();
</script>
</body>
</html>

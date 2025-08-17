<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Reporte de Ventas</title>
  <style>
    :root{
      --bg:#e0cdba;
      --card:#e8d9d9;
      --ink:#2e2a27;
      --muted:#6b7280;
      --ring:#e5e7eb;
      --brand:#5a3e36;
      --brand-600:#4d342d;
      --accent:#2ea69a;
      --accent-600:#23867b;
      --warn:#f59e0b;
      --ok:#10b981;
    }
    *{box-sizing:border-box}
    body{font-family:Arial, sans-serif; margin:0; background:var(--bg); color:var(--ink)}
    .shell{max-width:1200px; margin:40px auto; padding:0 20px}
    .header{display:flex; align-items:center; gap:12px; margin-bottom:18px}
    .title{margin:0; font-size:28px; color:var(--brand)}
    .sub{margin:0; color:var(--muted)}
    .card{background:var(--card); border:1px solid var(--ring); border-radius:14px; box-shadow:0 8px 24px rgba(0,0,0,.05)}
    .toolbar{display:flex; gap:12px; flex-wrap:wrap; justify-content:space-between; align-items:center; padding:14px 16px; border-bottom:1px solid var(--ring)}
    .btn{appearance:none; border:none; border-radius:10px; padding:10px 14px; font-weight:700; cursor:pointer}
    .btn-primary{background:var(--accent); color:#fff}
    .btn-primary:hover{background:var(--accent-600)}
    .btn-ghost{border:1px solid var(--ring); background:#fff}
    .link{color:var(--brand); text-decoration:none}
    .link:hover{text-decoration:underline}

    /* Tabs */
    .tabs{display:flex; gap:8px; padding:10px; border-bottom:1px solid var(--ring)}
    .tab{padding:10px 12px; border-radius:10px; cursor:pointer; color:var(--muted); font-weight:700}
    .tab.active{background:var(--brand); color:#fff}
    .panel{display:none; padding:18px}
    .panel.active{display:block}

    /* Stats */
    .stats{display:grid; gap:14px; grid-template-columns: repeat(4, 1fr);}
    .stat{background:#fafafa; border:1px solid var(--ring); border-radius:12px; padding:14px}
    .stat h4{margin:0 0 4px; color:var(--muted); font-size:12px; text-transform:uppercase; letter-spacing:.04em}
    .stat p{margin:0; font-size:20px; font-weight:800}

    /* Tables */
    .section-title{margin:8px 0 12px; color:var(--brand)}
    table{width:100%; border-collapse:separate; border-spacing:0 8px}
    th, td{padding:10px 12px; text-align:left}
    th{font-size:12px; text-transform:uppercase; letter-spacing:.04em; color:#555}
    tr{background:#fff; border:1px solid var(--ring)}
    tr td:first-child, tr th:first-child{border-top-left-radius:8px; border-bottom-left-radius:8px}
    tr td:last-child, tr th:last-child{border-top-right-radius:8px; border-bottom-right-radius:8px}
    .right{text-align:right}
    .muted{color:var(--muted)}
    .badge{display:inline-block; padding:3px 8px; border-radius:999px; background:#eef2ff; font-size:12px}
    .total-row{background:#f9fafb; font-weight:700}
    .totals{display:flex; justify-content:flex-end; margin-top:8px}
    .totals-box{min-width:280px; border:1px dashed var(--ring); border-radius:12px; background:#fff; padding:12px 14px}
    .totals-box .row{display:flex; justify-content:space-between; margin:4px 0}
    .totals-box .row strong{font-size:18px}
    .grid-2{display:grid; gap:18px; grid-template-columns:1fr 1fr}
    .grid-1{display:grid; gap:18px; grid-template-columns:1fr}
    @media (max-width: 900px){ .stats{grid-template-columns: repeat(2,1fr)} .grid-2{grid-template-columns:1fr} }
  </style>
</head>
<body>
<div class="shell">
  <div class="header">
    <h1 class="title">Reporte de Ventas</h1>
    <p class="sub">Resumen general, desglose por día, por usuario y productos más vendidos.</p>
  </div>

  @php
    // ==========================================
    // Preparación de datos
    // ==========================================
    $profitMargin = isset($profitMargin) ? (float)$profitMargin : 0.20; // 20% por defecto si no se pasa desde el controlador
    $ventas = $ventas ?? [];

    // Mapeo opcional de productos: $productsById[productId] = ['title' => '...', 'cost' => 12.34]
    $productsById = $productsById ?? [];

    $globalTotal = 0.0;
    $globalProfit = 0.0;
    $countVentas = 0;
    $countItems  = 0;

    // Agrupar por día (YYYY-mm-dd)
    $byDay = [];
    // Agrupar por usuario
    $byUser = [];
    // Ranking de productos
    $prodAgg = []; // [productId] => ['qty'=>x, 'revenue'=>y, 'title'=>..., 'cost'=>...]

    foreach ($ventas as $v) {
        $countVentas++;
        $date = \Carbon\Carbon::parse($v['date'] ?? now())->format('Y-m-d');
        $userIdMixed = $v['userId'] ?? null;
        $userId = is_array($userIdMixed) ? ($userIdMixed['_id'] ?? ($userIdMixed['$oid'] ?? 'N/A')) : (string)($userIdMixed ?? 'N/A');
        $userName = is_array($userIdMixed) ? ($userIdMixed['name'] ?? 'Usuario desconocido') : 'Usuario desconocido';

        $details = is_array($v['details'] ?? null) ? $v['details'] : [];
        $total   = (float)($v['total'] ?? 0);
        $globalTotal += $total;

        // Calcular ganancia por venta: si tenemos costos por producto usar eso; si no, usar margen
        $profitVenta = 0.0;
        foreach ($details as $d) {
            $countItems++;
            $pid = (int)($d['productId'] ?? 0);
            $qty = (int)($d['quantity'] ?? 0);
            $unit = (float)($d['unitPrice'] ?? 0);
            $revenue = $qty * $unit;

            // Agg por producto
            if (!isset($prodAgg[$pid])) {
                $prodAgg[$pid] = [
                    'qty' => 0,
                    'revenue' => 0.0,
                    'title' => $productsById[$pid]['title'] ?? null,
                    'cost' => $productsById[$pid]['cost'] ?? null,
                ];
            }
            $prodAgg[$pid]['qty'] += $qty;
            $prodAgg[$pid]['revenue'] += $revenue;

            // Ganancia por ítem
            if (isset($prodAgg[$pid]['cost']) && $prodAgg[$pid]['cost'] !== null) {
                $profitVenta += max(0, $revenue - ($prodAgg[$pid]['cost'] * $qty));
            } else {
                $profitVenta += $revenue * $profitMargin;
            }
        }
        $globalProfit += $profitVenta;

        // By day
        if (!isset($byDay[$date])) $byDay[$date] = ['ventas'=>[], 'total'=>0.0, 'profit'=>0.0, 'items'=>0];
        $byDay[$date]['ventas'][] = $v;
        $byDay[$date]['total'] += $total;
        $byDay[$date]['profit'] += $profitVenta;
        $byDay[$date]['items']  += count($details);

        // By user
        if (!isset($byUser[$userId])) $byUser[$userId] = ['name'=>$userName, 'ventas'=>[], 'total'=>0.0, 'items'=>0];
        $byUser[$userId]['ventas'][] = $v;
        $byUser[$userId]['total'] += $total;
        $byUser[$userId]['items'] += count($details);
    }

    // Ordenar por día (desc)
    krsort($byDay);

    // Top productos (por cantidad y por ingreso)
    $topByQty = $prodAgg;
    uasort($topByQty, fn($a, $b) => $b['qty'] <=> $a['qty']);
    $topByQty = array_slice($topByQty, 0, 10, true);

    $topByRevenue = $prodAgg;
    uasort($topByRevenue, fn($a, $b) => $b['revenue'] <=> $a['revenue']);
    $topByRevenue = array_slice($topByRevenue, 0, 10, true);

    function money($n){ return '$'.number_format((float)$n, 2); }
  @endphp

  <div class="card">
    <div class="toolbar">
      <a href="{{ route('dashboard.redirect') }}" class="link">🏠 Volver al menú</a>
      <div style="display:flex; gap:10px">
        @if (Route::has('sales.report.pdf'))
          <a class="btn btn-primary" href="{{ route('sales.report.pdf') }}" target="_blank">⬇️ Descargar PDF</a>
        @endif
      </div>
    </div>

    <div class="tabs" id="tabs">
      <div class="tab active" data-panel="p-overview">Overview</div>
      <div class="tab" data-panel="p-day">Por día</div>
      <div class="tab" data-panel="p-user">Por usuario</div>
      <div class="tab" data-panel="p-top">Top productos</div>
    </div>

    <!-- Panel: Overview -->
    <div class="panel active" id="p-overview">
      <div class="stats">
        <div class="stat">
          <h4>Ventas</h4>
          <p>{{ $countVentas }}</p>
          <div class="muted">Registros de venta</div>
        </div>
        <div class="stat">
          <h4>Items</h4>
          <p>{{ $countItems }}</p>
          <div class="muted">Productos vendidos</div>
        </div>
        <div class="stat">
          <h4>Ingresos</h4>
          <p>{{ money($globalTotal) }}</p>
          <div class="muted">Total acumulado</div>
        </div>
        <div class="stat">
          <h4>Ganancia</h4>
          <p>{{ money($globalProfit) }}</p>
          <div class="muted">
            @if(empty($productsById))
              Calculada con margen {{ (int)($profitMargin*100) }}%
            @else
              Basada en costo/producto (si falta costo, se usa margen {{ (int)($profitMargin*100) }}%)
            @endif
          </div>
        </div>
      </div>

      <h3 class="section-title">Resumen por día (últimos)</h3>
      <div class="grid-2">
        <div class="card" style="padding:12px">
          <table>
            <thead>
              <tr>
                <th>Fecha</th>
                <th class="right">Ventas</th>
                <th class="right">Items</th>
                <th class="right">Ingresos</th>
                <th class="right">Ganancia</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($byDay as $d => $info)
                <tr>
                  <td><span class="badge">{{ $d }}</span></td>
                  <td class="right">{{ count($info['ventas']) }}</td>
                  <td class="right">{{ $info['items'] }}</td>
                  <td class="right">{{ money($info['total']) }}</td>
                  <td class="right">{{ money($info['profit']) }}</td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>

        <div class="card" style="padding:12px">
          <h4 style="margin:8px 0">Notas</h4>
          <ul class="muted">
            <li>La ganancia se saca por producto usando el costo (si lo hay) o el margen que hayas puesto.</li>
<li>Desde el controlador puedes mandar <code>$profitMargin</code> (por ejemplo, <code>0.25</code> para un 25%).</li>
<li>Si necesitas el nombre del producto, usa <code>$productsById[productId]['title']</code>.</li>

          </ul>
        </div>
      </div>
    </div>

    <!-- Panel: Por día -->
    <div class="panel" id="p-day">
      @forelse ($byDay as $d => $info)
        <h3 class="section-title">Fecha: {{ $d }}</h3>
        <table>
          <thead>
            <tr>
              <th>Venta</th>
              <th>Usuario</th>
              <th class="right">Producto</th>
              <th class="right">Cant.</th>
              <th class="right">P. Unit.</th>
              <th class="right">Subtotal</th>
            </tr>
          </thead>
          <tbody>
            @php $sumDia = 0; @endphp
            @foreach ($info['ventas'] as $venta)
              @php
                $ventaId = is_array($venta['_id'] ?? null) ? ($venta['_id']['$oid'] ?? 'N/A') : ($venta['_id'] ?? 'N/A');
                $uRaw = $venta['userId'] ?? null;
                $uName = is_array($uRaw) ? ($uRaw['name'] ?? 'Usuario desconocido') : 'Usuario desconocido';
              @endphp

              @foreach (($venta['details'] ?? []) as $det)
                @php
                  $pid = (int)($det['productId'] ?? 0);
                  $qty = (int)($det['quantity'] ?? 0);
                  $unit= (float)($det['unitPrice'] ?? 0);
                  $sub = $qty * $unit; $sumDia += $sub;
                  $title = $productsById[$pid]['title'] ?? null;
                @endphp
                <tr>
                  <td class="muted">{{ $ventaId }}</td>
                  <td>{{ $uName }}</td>
                  <td class="right">
                    {{ $pid }} @if($title) — <span class="muted">{{ $title }}</span>@endif
                  </td>
                  <td class="right">{{ $qty }}</td>
                  <td class="right">{{ money($unit) }}</td>
                  <td class="right">{{ money($sub) }}</td>
                </tr>
              @endforeach

              <tr class="total-row">
                <td colspan="5" class="right">Total venta</td>
                <td class="right">{{ money($venta['total'] ?? 0) }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>

        <div class="totals">
          <div class="totals-box">
            <div class="row"><span>Ventas</span><span>{{ count($info['ventas']) }}</span></div>
            <div class="row"><span>Items</span><span>{{ $info['items'] }}</span></div>
            <div class="row"><span>Ingresos del día</span><span>{{ money($info['total']) }}</span></div>
            <div class="row"><strong>Ganancia del día</strong><strong>{{ money($info['profit']) }}</strong></div>
          </div>
        </div>
        <hr style="border:none; border-top:1px solid var(--ring); margin:18px 0">
      @empty
        <p class="muted">Sin datos.</p>
      @endforelse
    </div>

    <!-- Panel: Por usuario -->
    <div class="panel" id="p-user">
      @forelse ($byUser as $uid => $uInfo)
        <h3 class="section-title">Usuario: {{ $uInfo['name'] }} <span class="muted">({{ $uid }})</span></h3>
        <table>
          <thead>
            <tr>
              <th>Venta</th>
              <th class="right">Producto</th>
              <th class="right">Cant.</th>
              <th class="right">P. Unit.</th>
              <th class="right">Subtotal</th>
              <th class="right">Total venta</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($uInfo['ventas'] as $venta)
              @php $ventaId = is_array($venta['_id'] ?? null) ? ($venta['_id']['$oid'] ?? 'N/A') : ($venta['_id'] ?? 'N/A'); @endphp
              @foreach (($venta['details'] ?? []) as $det)
                @php
                  $pid = (int)($det['productId'] ?? 0);
                  $qty = (int)($det['quantity'] ?? 0);
                  $unit= (float)($det['unitPrice'] ?? 0);
                  $sub = $qty * $unit;
                  $title = $productsById[$pid]['title'] ?? null;
                @endphp
                <tr>
                  <td class="muted">{{ $ventaId }}</td>
                  <td class="right">
                    {{ $pid }} @if($title) — <span class="muted">{{ $title }}</span>@endif
                  </td>
                  <td class="right">{{ $qty }}</td>
                  <td class="right">{{ money($unit) }}</td>
                  <td class="right">{{ money($sub) }}</td>
                  <td class="right">{{ money($venta['total'] ?? 0) }}</td>
                </tr>
              @endforeach
            @endforeach
          </tbody>
        </table>
        <div class="totals">
          <div class="totals-box">
            <div class="row"><span>Ventas</span><span>{{ count($uInfo['ventas']) }}</span></div>
            <div class="row"><span>Items</span><span>{{ $uInfo['items'] }}</span></div>
            <div class="row"><strong>Total del usuario</strong><strong>{{ money($uInfo['total']) }}</strong></div>
          </div>
        </div>
        <hr style="border:none; border-top:1px solid var(--ring); margin:18px 0">
      @empty
        <p class="muted">Sin datos.</p>
      @endforelse
    </div>

    <!-- Panel: Top productos -->
    <div class="panel" id="p-top">
      <div class="grid-2">
        <div>
          <h3 class="section-title">Top por cantidad</h3>
          <table>
            <thead>
              <tr>
                <th>Producto</th>
                <th class="right">Cantidad</th>
                <th class="right">Ingresos</th>
              </tr>
            </thead>
            <tbody>
              @forelse ($topByQty as $pid => $agg)
                <tr>
                  <td>
                    {{ $pid }}
                    @if($agg['title']) — <span class="muted">{{ $agg['title'] }}</span>@endif
                  </td>
                  <td class="right">{{ $agg['qty'] }}</td>
                  <td class="right">{{ money($agg['revenue']) }}</td>
                </tr>
              @empty
                <tr><td colspan="3" class="muted">Sin datos.</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>

        <div>
          <h3 class="section-title">Top por ingresos</h3>
          <table>
            <thead>
              <tr>
                <th>Producto</th>
                <th class="right">Cantidad</th>
                <th class="right">Ingresos</th>
              </tr>
            </thead>
            <tbody>
              @forelse ($topByRevenue as $pid => $agg)
                <tr>
                  <td>
                    {{ $pid }}
                    @if($agg['title']) — <span class="muted">{{ $agg['title'] }}</span>@endif
                  </td>
                  <td class="right">{{ $agg['qty'] }}</td>
                  <td class="right">{{ money($agg['revenue']) }}</td>
                </tr>
              @empty
                <tr><td colspan="3" class="muted">Sin datos.</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>

  </div>
</div>

<script>
  // Tabs simples
  const tabs = document.querySelectorAll('.tab');
  const panels = document.querySelectorAll('.panel');
  tabs.forEach(t => {
    t.addEventListener('click', () => {
      tabs.forEach(x => x.classList.remove('active'));
      panels.forEach(p => p.classList.remove('active'));
      t.classList.add('active');
      const id = t.dataset.panel;
      document.getElementById(id)?.classList.add('active');
      window.scrollTo({top:0, behavior:'smooth'});
    });
  });
</script>
</body>
</html>

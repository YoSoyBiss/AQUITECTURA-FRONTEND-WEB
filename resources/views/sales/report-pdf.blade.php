<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Reporte de Ventas</title>
  <style>
    body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
    h2 { background-color: #ccc; padding: 5px; }
    table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
    th, td { border: 1px solid #999; padding: 4px; }
    th { background-color: #eee; }
    .total { font-weight: bold; background-color: #f0f0f0; }
  </style>
</head>
<body>
  <h1>Reporte de Ventas por Usuario</h1>

  @php
    $ventasPorUsuario = [];
    $totalGlobal = 0;
  @endphp

  @foreach ($ventas as $venta)
    @php
      $userId = $venta['userId']['_id'] ?? $venta['userId'];
      $userName = $venta['userId']['name'] ?? 'Usuario desconocido';

      if (!isset($ventasPorUsuario[$userId])) {
          $ventasPorUsuario[$userId] = ['name' => $userName, 'ventas' => []];
      }

      $ventasPorUsuario[$userId]['ventas'][] = $venta;
      $totalGlobal += $venta['total'];
    @endphp
  @endforeach

  @foreach ($ventasPorUsuario as $userId => $info)
    <h2>ID Usuario: {{ $userId }} — {{ $info['name'] }}</h2>
    <table>
      <thead>
        <tr>
          <th>ID Venta</th>
          <th>Producto ID</th>
          <th>Cantidad</th>
          <th>Precio Unitario</th>
          <th>Subtotal</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($info['ventas'] as $venta)
          @foreach ($venta['details'] as $detalle)
            <tr>
              <td>{{ $venta['_id'] }}</td>
              <td>{{ $detalle['productId'] }}</td>
              <td>{{ $detalle['quantity'] }}</td>
              <td>${{ number_format($detalle['unitPrice'], 2) }}</td>
              <td>${{ number_format($detalle['quantity'] * $detalle['unitPrice'], 2) }}</td>
            </tr>
          @endforeach
          <tr class="total">
            <td colspan="4" align="right">Total de Venta:</td>
            <td>${{ number_format($venta['total'], 2) }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>
  @endforeach

  <h3>Total Global de Ventas: ${{ number_format($totalGlobal, 2) }}</h3>
</body>
</html>

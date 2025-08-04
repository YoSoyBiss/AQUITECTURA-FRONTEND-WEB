<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Reporte de Ventas</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 40px;
      background-color: #ddc9b9ff;
    }

    h2 {
      background-color: #4a4a4a;
      color: white;
      padding: 10px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 40px;
    }

    th, td {
      border: 1px solid #ccc;
      padding: 8px;
      text-align: left;
    }

    th {
      background-color: #eee;
    }

    .total {
      font-weight: bold;
      background-color: #858383ff;
    }

    .global-total {
      font-size: 1.3em;
      font-weight: bold;
      text-align: right;
      margin-top: 30px;
    }
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
      $userId = $venta['userId']['_id'] ?? $venta['userId']; // por si no viene como objeto
      $userName = $venta['userId']['name'] ?? 'Usuario desconocido';

      if (!isset($ventasPorUsuario[$userId])) {
          $ventasPorUsuario[$userId] = [
              'name' => $userName,
              'ventas' => [],
          ];
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
            <td colspan="4" style="text-align:right;">Total de Venta:</td>
            <td>${{ number_format($venta['total'], 2) }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>
  @endforeach

  <div class="global-total">
    Total Global de Ventas: ${{ number_format($totalGlobal, 2) }}
  </div>
  <a href="{{ route('sales.report.pdf') }}" class="btn btn-danger" target="_blank">
    Descargar PDF
  </a>

</body>
</html>

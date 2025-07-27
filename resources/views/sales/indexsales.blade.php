<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista de Ventas</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4e3d7;
            padding: 20px;
        }

        .container {
            background: white;
            padding: 20px;
            max-width: 900px;
            margin: auto;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 10px;
            border: 1px solid #ccc;
            text-align: center;
        }

        th {
            background-color: #5a3e36;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #93bab0ff;
        }

        .btn-create {
            display: inline-block;
            margin-bottom: 20px;
            background-color: #28a745;
            color: white;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 4px;
        }

        .btn-create:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Lista de Ventas</h1>

        <a class="btn-create" href="{{ route('sales.createsales') }}">+ Crear Nueva Venta</a>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Precio</th>
                    <th>Fecha</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($sales as $sale)
                    <tr>
                        <td>{{ $sale['_id'] ?? 'N/A' }}</td>
                        <td>{{ $sale['product'] }}</td>
                        <td>{{ $sale['quantity'] }}</td>
                        <td>${{ number_format($sale['price'], 2) }}</td>
                        <td>{{ \Carbon\Carbon::parse($sale['date'])->format('Y-m-d') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">No hay ventas registradas.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</body>
</html>

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
            max-width: 1000px;
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

        .btn-disabled {
            background-color: #ccc;
            color: #666;
            cursor: not-allowed;
            pointer-events: none;
        }

        .alert-success {
            background-color: #d4edda;
            padding: 10px;
            color: #155724;
            margin-bottom: 15px;
            border-radius: 4px;
        }
        .add-button {
            display: inline-block; margin-bottom: 20px; padding: 10px 20px;
            background-color: #8d6e63; color: white;
            text-decoration: none; border-radius: 5px;
            font-weight: bold; transition: background-color 0.3s ease;
        }

        .alert-error {
            background-color: #f8d7da;
            padding: 10px;
            color: #721c24;
            margin-bottom: 15px;
            border-radius: 4px;
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

    {{-- Botón habilitado solo si el usuario es admin --}}
    @if (session('user_role') === 'admin')
        <a class="btn-create" href="{{ route('sales.createsales') }}">+ Crear Nueva Venta</a>
    @else
        <button class="btn-create btn-disabled" disabled title="Solo administradores pueden crear ventas">+ Crear Nueva Venta</button>
    @endif
    
    <a href="{{ route('dashboard.redirect') }}" class="add-button" style="margin-right: auto;">🏠📚 Menu principal</a>
    
    <table>
        <thead>
            <tr>
                <th>ID Venta</th>
                <th>ID Producto</th>
                <th>Cantidad</th>
                <th>Precio Unitario</th>
                <th>Fecha</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($sales as $sale)
                @foreach ($sale['details'] as $detail)
                    <tr>
                        <td>{{ $sale['_id'] ?? 'N/A' }}</td>
                        <td>{{ $detail['productId'] }}</td>
                        <td>{{ $detail['quantity'] }}</td>
                        <td>${{ number_format($detail['unitPrice'], 2) }}</td>
                        <td>{{ \Carbon\Carbon::parse($sale['date'])->format('Y-m-d') }}</td>
                    </tr>
                @endforeach
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

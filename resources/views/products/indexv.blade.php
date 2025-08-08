<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Listado de Productos</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f4e3d7;
            padding: 40px;
            margin: 0;
        }

        h1 {
            text-align: center;
            color: #5a3e36;
            margin-bottom: 30px;
        }
        .add-button {
            display: inline-block; margin-bottom: 20px; padding: 10px 20px;
            background-color: #8d6e63; color: white;
            text-decoration: none; border-radius: 5px;
            font-weight: bold; transition: background-color 0.3s ease;
        }

        .container {
            max-width: 1000px;
            margin: auto;
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(90, 62, 54, 0.2);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th, td {
            padding: 14px;
            border-bottom: 1px solid #ddd;
            text-align: center;
        }

        th {
            background-color: #d7ccc8;
            color: #4e342e;
            font-size: 16px;
        }

        tr:nth-child(even) {
            background-color: #f8f5f3;
        }

        tr:hover {
            background-color: #efe5dd;
        }

        .no-products {
            text-align: center;
            color: #6d4c41;
            padding: 20px;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Listado de Productos</h1>
        <div class="top-bar">
        <a href="{{ route('dashboard.redirect') }}" class="add-button" style="margin-right: auto;">🏠📚 Menu principal</a>
    <div>
        <table>
            <thead>
                <tr>
                    <th>Título</th>
                    <th>Autor</th>
                    <th>Editorial</th>
                    <th>Stock</th>
                    <th>Precio</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($products as $product)
                    <tr>
                        <td>{{ $product['title'] }}</td>
                        <td>{{ $product['author'] }}</td>
                        <td>{{ $product['publisher'] }}</td>
                        <td>{{ $product['stock'] }}</td>
                        <td>${{ number_format($product['price'], 2) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="no-products">No hay productos registrados o la API no está disponible.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</body>
</html>

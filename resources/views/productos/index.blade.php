<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Listado de Productos</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4e3d7; /* color café claro */
            padding: 40px;
        }

        h1 {
            text-align: center;
            color: #5a3e36;
        }

        .container {
            max-width: 1000px;
            margin: auto;
            background: #fff;
            padding: 20px 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(90, 62, 54, 0.2);
        }

        .add-button {
            display: inline-block;
            margin-bottom: 20px;
            padding: 10px 20px;
            background-color: #8d6e63;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }

        .add-button:hover {
            background-color: #795548;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: center;
        }

        th {
            background-color: #d7ccc8;
            color: #4e342e;
        }

        tr:nth-child(even) {
            background-color: #f8f5f3;
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>Listado de Productos</h1>

        <a href="{{ url('/productos/crear') }}" class="add-button">+ Agregar Producto</a>

        <table>
            <thead>
                <tr>
                    <th>Título</th>
                    <th>Autor</th>
                    <th>Editorial</th>
                    <th>Stock</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($productos as $producto)
                    <tr>
                        <td>{{ $producto['titulo'] }}</td>
                        <td>{{ $producto['autor'] }}</td>
                        <td>{{ $producto['editorial'] }}</td>
                        <td>{{ $producto['stock'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</body>
</html>

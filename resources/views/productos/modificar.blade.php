<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Producto</title>
    <style>
        body {
            background-color: #f4e3d7;
            font-family: Arial;
            padding: 40px;
        }

        .container {
            max-width: 600px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(90, 62, 54, 0.2);
        }

        h1 {
            text-align: center;
            color: #5a3e36;
        }

        label {
            font-weight: bold;
            color: #5a3e36;
        }

        input[type="text"], input[type="number"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 16px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        button {
            background-color: #8d6e63;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-weight: bold;
            width: 100%;
        }

        button:hover {
            background-color: #795548;
        }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            text-decoration: none;
            color: #5a3e36;
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>Editar Producto</h1>

        <form action="{{ url('/productos/' . $producto['id']) }}" method="POST">
            @csrf
            @method('PUT')

            <label for="titulo">Título:</label>
            <input type="text" name="titulo" value="{{ old('titulo', $producto['titulo']) }}" required>

            <label for="autor">Autor:</label>
            <input type="text" name="autor" value="{{ old('autor', $producto['autor']) }}" required>

            <label for="editorial">Editorial:</label>
            <input type="text" name="editorial" value="{{ old('editorial', $producto['editorial']) }}" required>

            <label for="stock">Stock:</label>
            <input type="number" name="stock" value="{{ old('stock', $producto['stock']) }}" min="0" required>

            <button type="submit">Actualizar Producto</button>
        </form>

        <a href="{{ url('/productos') }}" class="back-link">← Volver al listado</a>
    </div>

</body>
</html>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agregar Producto</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4e3d7; /* color café claro */
            padding: 40px;
        }

        .container {
            max-width: 600px;
            margin: auto;
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(90, 62, 54, 0.2);
        }

        h1 {
            text-align: center;
            color: #5a3e36;
            margin-bottom: 20px;
        }

        label {
            font-weight: bold;
            color: #5a3e36;
        }

        input[type="text"],
        input[type="number"] {
            width: 100%;
            padding: 10px;
            margin: 8px 0 16px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .btn {
            background-color: #8d6e63;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s ease;
            width: 100%;
        }

        .btn:hover {
            background-color: #795548;
        }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #5a3e36;
            text-decoration: none;
        }

        .error {
            color: red;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>Agregar Producto</h1>

        @if ($errors->any())
            <div class="error">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ url('/productos') }}">
            @csrf
            <label for="titulo">Título:</label>
            <input type="text" id="titulo" name="titulo" required>

            <label for="autor">Autor:</label>
            <input type="text" id="autor" name="autor" required>

            <label for="editorial">Editorial:</label>
            <input type="text" id="editorial" name="editorial" required>

            <label for="stock">Stock:</label>
            <input type="number" id="stock" name="stock" min="0" required>

            <button type="submit" class="btn">Guardar Producto</button>
        </form>

        <a href="{{ url('/productos') }}" class="back-link">← Volver al listado</a>
    </div>

</body>
</html>

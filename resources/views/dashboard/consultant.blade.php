<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Consultor</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4e3d7;
            padding: 20px;
        }

        .container {
            background: white;
            padding: 20px;
            max-width: 800px;
            margin: auto;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #333;
        }

        .menu {
            display: flex;
            flex-direction: column;
            gap: 15px;
            margin-top: 30px;
        }

        .menu a {
            background-color: #5a3e36;
            color: white;
            padding: 12px;
            text-align: center;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
        }

        .menu a:hover {
            background-color: #3d2a24;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Bienvenido, {{ Auth::user()->name ?? 'Consultor' }}</h1>
    <div class="menu">
        <a href="{{ route('sales.index') }}">📦 Ver Productos</a>
        <a href="{{ route('sales.index') }}">📊 Ver Compras</a>
        <a href="{{ route('logout') }}">🚪 Cerrar sesión</a>
    </div>
</div>
</body>
</html>

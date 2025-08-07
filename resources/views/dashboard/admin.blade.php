<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin</title>

    <!-- Fuente Jacquard 24 -->
    <link href="https://fonts.googleapis.com/css2?family=Jacquard+24&display=swap" rel="stylesheet">

    <style>
        * {
            box-sizing: border-box;
        }

        body {
    font-family: Arial, sans-serif;
    background-color: #f4e3d7;

    background-image: url('/shadowthre.png'),url('/shadowthre.png'),url('/shadow2.png');
    background-repeat: no-repeat;
    background-position: 100% center, 10% center, 3% 100%; /* 👈 puedes mover cada una */
    background-size: 600px auto, 500px, 400px; /* ajusta el tamaño como necesites */
}


        .container {
    max-width: 600px; /* ✅ limita el ancho */
    margin: 10px auto; /* ✅ centra horizontalmente y da espacio arriba/abajo */
    border: 10px solid #8d6e63;
    border-image: url('/border-frame.png') 10 stretch;
    padding: 15px;
    border-radius: 40px;
    background-color: white;
    box-shadow: 0 0 20px rgba(0, 0, 0, 0.2); /* opcional, mejora el contraste */
}


        h1 {
            font-family: 'Jacquard 24', cursive;
            font-size: 78px;
            color: #000;
            margin-bottom: 60px;
        }

        .menu {
            display: flex;
            flex-direction: column;
            gap: 30px;
            align-items: center;
        }

        .menu-item {
            display: flex;
            align-items: center;
            background-color: #f2f2f2;
            padding: 15px 30px;
            border-radius: 5px;
            width: 350px;
            text-decoration: none;
            color: #000;
            font-weight: bold;
            font-size: 22px;
            transition: background 0.3s;
            justify-content: center;
        }

        .menu-item img {
            width: 40px;
            margin-right: 15px;
        }

        .menu-item:hover {
            background-color: #ddd;
        }

        form {
            margin: 0;
        }

        button.menu-item {
            border: none;
            cursor: pointer;
            background-color: #f2f2f2;
            font: inherit;
        }

        button.menu-item:hover {
            background-color: #ddd;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Bienvenido, {{ session('user_name') ?? 'Administrador' }}</h1>

        <div class="menu">
            <a href="{{ route('products.index') }}" class="menu-item">
                <img src="/prodc.png" alt="Productos"> Gestión de productos
            </a>
            <a href="{{ route('users.index') }}" class="menu-item">
                <img src="/user.png" alt="Usuarios"> Gestión de usuarios
            </a>
            <a href="{{ route('sales.index') }}" class="menu-item">
                <img src="/car.png" alt="Compras"> Gestión de compras
            </a>

            {{-- Logout --}}
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="menu-item">
                    <img src="/door.png" alt="Cerrar sesión"> Cerrar sesión
                </button>
            </form>
        </div>
    </div>
</body>
</html>

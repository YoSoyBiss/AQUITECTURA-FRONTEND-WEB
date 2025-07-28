<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>

<body>
    <div class="login-container">
        <h2>Iniciar sesión</h2>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <label for="email">Correo Electrónico:</label>
            <input type="email" id="email" name="email" required>

            <label for="password">Contraseña:</label>
            <input type="password" id="password" name="password" required>

            <button type="submit" class="btn-submit">Iniciar sesión</button>
        </form>

        <div class="register-link">
            <a href="{{ route('register') }}">¿No tienes cuenta?</a>
        </div>
    </div>

    @if (session('error'))
        <div class="popup-error" id="popup-error">
            {{ session('error') }}
        </div>
    @endif

    @if (session('success'))
        <div class="popup-success" id="popup-success">
            {{ session('success') }}
        </div>
    @endif
</body>

</html>

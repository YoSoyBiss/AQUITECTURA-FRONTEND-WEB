<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Registro</title>
    <link rel="stylesheet" href="{{ asset('css/register.css') }}">
</head>

<body>
    <div class="register-container">
        <h2>Crear Cuenta</h2>
        <form method="POST" action="{{ route('register') }}">
            @csrf

            <label for="name">Nombre:</label>
            <input type="text" id="name" name="name" required>

            <label for="email">Correo electrónico:</label>
            <input type="email" id="email" name="email" required>

            <label for="password">Contraseña:</label>
            <input type="password" id="password" name="password" required>

            <label for="password_confirmation">Confirme contraseña:</label>
            <input type="password" id="password_confirmation" name="password_confirmation" required>

            <label for="role">Rol:</label>
            <input type="text" id="role" name="role" required>

            <div class="button-group">
                <a href="{{ route('login') }}">
                    <button type="button" class="btn-return">Volver</button>
                </a>
                <button type="submit" class="btn-submit">Crear Cuenta</button>
            </div>
        </form>
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

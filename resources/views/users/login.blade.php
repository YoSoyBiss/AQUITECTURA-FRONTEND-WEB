<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <title>Iniciar sesión</title>
  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: #f4e3d7;
      display: flex;
      align-items: center;
      justify-content: center;
      height: 100vh;
      margin: 0;
      padding: 20px;
    }

    .login-container {
      background: #ffffff;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 0 20px rgba(90, 62, 54, 0.2);
      width: 100%;
      max-width: 400px;
      box-sizing: border-box;
    }

    h2 {
      text-align: center;
      color: #5a3e36;
      margin-bottom: 25px;
    }

    label {
      font-weight: bold;
      color: #5a3e36;
      display: block;
      margin-bottom: 6px;
    }

    .input-group {
      margin-bottom: 16px;
    }

    .input-group input {
      width: 100%;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 5px;
      box-sizing: border-box;
    }

    button[type="submit"] {
      background-color: #8d6e63;
      color: white;
      padding: 12px;
      border: none;
      border-radius: 5px;
      font-weight: bold;
      cursor: pointer;
      width: 100%;
      margin-top: 5px;
      transition: background-color 0.3s ease;
    }

    button[type="submit"]:hover {
      background-color: #795548;
    }

    .footer {
      text-align: center;
      margin-top: 20px;
      font-size: 14px;
    }

    .footer a {
      color: #5a3e36;
      text-decoration: none;
      font-weight: bold;
    }

    .footer a:hover {
      text-decoration: underline;
    }

    .error-message {
      color: red;
      font-size: 14px;
      margin-bottom: 10px;
    }
  </style>
</head>
<body>
  <div class="login-container">
    <h2>Iniciar sesión</h2>

    {{-- Mostrar errores --}}
    @if ($errors->any())
      <div class="error-message">
        <ul>
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    {{-- Mensajes de sesión (por ejemplo éxito o error custom) --}}
    @if (session('error'))
      <div class="error-message">{{ session('error') }}</div>
    @endif

    <form method="POST" action="{{ route('users.login.submit') }}">
      @csrf

      <label for="email">Correo electrónico</label>
      <div class="input-group">
        <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus />
      </div>

      <label for="password">Contraseña</label>
      <div class="input-group">
        <input type="password" id="password" name="password" required />
      </div>

      <button type="submit">Iniciar sesión</button>
    </form>

    <div class="footer">
      ¿No tienes cuenta? <a href="{{ route('register') }}">Regístrate aquí</a>
    </div>
  </div>
</body>
</html>

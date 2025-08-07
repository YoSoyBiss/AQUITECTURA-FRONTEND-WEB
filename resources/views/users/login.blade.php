<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <title>Iniciar sesión</title>
  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: #f4e3d7;
      background: url('/books.png') no-repeat center center fixed;
      background-size: cover;
      display: flex;
      align-items: center;
      justify-content: center;
      height: 100vh;
      margin: 0;
      padding: 20px;
    }

    .login-container {
      background: url('/pergamino2.png') no-repeat center center fixed;
      background-size: 600px auto;
      padding: 80px 80px;
      border-radius: 15px;
      box-shadow: 0 10px 50px rgba(64, 50, 100, 0.3);
      width: 100%;
      max-width: 500px;
      box-sizing: border-box;
    }

    h2 {
      text-align: center;
      color: #5a3e36;
      font-size: 32px;
      margin-bottom: 30px;
    }

    label {
      font-weight: bold;
      color: #5a3e36;
      display: block;
      margin-bottom: 8px;
      font-size: 16px;
    }

    .input-group {
      margin-bottom: 20px;
    }

    .input-group input {
      width: 100%;
      padding: 14px;
      border: 1px solid #ccc;
      border-radius: 8px;
      font-size: 15px;
      box-sizing: border-box;
    }

    button[type="submit"] {
      background-color: #8d6e63;
      color: white;
      padding: 14px;
      border: none;
      border-radius: 8px;
      font-weight: bold;
      cursor: pointer;
      width: 100%;
      font-size: 16px;
      transition: background-color 0.3s ease;
    }

    button[type="submit"]:hover {
      background-color: #795548;
    }

    .footer {
      text-align: center;
      margin-top: 25px;
      font-size: 15px;
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
      margin-bottom: 15px;
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

    {{-- Mensajes de sesión --}}
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

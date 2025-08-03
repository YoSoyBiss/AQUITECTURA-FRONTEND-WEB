<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Crear cuenta</title>
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

    .register-container {
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
  <div class="register-container">
    <h2>Crear Cuenta</h2>

    @if(session('error'))
      <div class="error-message">{{ session('error') }}</div>
    @endif

    @if ($errors->any())
      <div class="error-message">
        <ul>
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form method="POST" action="{{ route('register.submit') }}">
      @csrf

      <label for="name">Nombre completo</label>
      <div class="input-group">
        <input type="text" name="name" id="name" value="{{ old('name') }}" required>
      </div>

      <label for="email">Correo electrónico</label>
      <div class="input-group">
        <input type="email" name="email" id="email" value="{{ old('email') }}" required>
      </div>

      <label for="password">Contraseña</label>
      <div class="input-group">
        <input type="password" name="password" id="password" required>
      </div>

      <label for="password_confirmation">Confirmar contraseña</label>
      <div class="input-group">
        <input type="password" name="password_confirmation" id="password_confirmation" required>
      </div>

      <button type="submit">Crear cuenta</button>
    </form>

    <div class="footer">
      ¿Ya tienes cuenta? <a href="{{ route('users.login') }}">Inicia sesión</a>
    </div>
  </div>
</body>
</html>

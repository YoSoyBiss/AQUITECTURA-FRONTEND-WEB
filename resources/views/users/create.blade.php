<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <title>Crear Usuario</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f4e3d7;
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
    input[type="password"],
    select {
      width: 100%;
      padding: 10px;
      margin: 8px 0 16px 0;
      border: 1px solid #ccc;
      border-radius: 5px;
      box-sizing: border-box;
    }

    /* Contenedor para input con botón ojito */
    .password-wrapper {
      position: relative;
    }

    .password-wrapper button {
      position: absolute;
      right: 10px;
      top: 50%;
      transform: translateY(-50%);
      background: none;
      border: none;
      cursor: pointer;
      font-size: 1.2rem;
      user-select: none;
      color: #5a3e36;
      padding: 0;
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

    #successModal {
      display: none;
      position: fixed;
      top: 30%;
      left: 50%;
      transform: translate(-50%, -30%);
      background-color: white;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.3);
      text-align: center;
      z-index: 1000;
    }

    #successModal h2 {
      color: green;
    }

    #successModal button {
      background-color: #28a745;
      color: white;
      border: none;
      padding: 10px 20px;
      font-size: 16px;
      border-radius: 5px;
      cursor: pointer;
    }
  </style>
</head>
<body>

  <div class="container">
    <h1>Crear Usuario</h1>

    @if ($errors->any())
      <div class="error">
        <ul>
          @foreach ($errors->all() as $error)
            <li>{{ is_array($error) ? implode(', ', $error) : $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form id="createForm" method="POST" action="{{ url('/users') }}">
      @csrf

      <label for="name">Nombre:</label>
      <input type="text" id="name" name="name" value="{{ old('name') }}" required>

      <label for="email">Correo electrónico:</label>
      <input type="text" id="email" name="email" value="{{ old('email') }}" required>

      <label for="password">Contraseña:</label>
      <div class="password-wrapper">
        <input type="password" id="password" name="password" required>
        <button type="button" id="togglePassword" aria-label="Mostrar contraseña">👁️</button>
      </div>

      <label for="password_confirmation">Confirmar Contraseña:</label>
      <div class="password-wrapper">
        <input type="password" id="password_confirmation" name="password_confirmation" required>
        <button type="button" id="togglePasswordConfirm" aria-label="Mostrar contraseña">👁️</button>
      </div>

      <label for="role">Rol:</label>
      <select id="role" name="role" required>
        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
        <option value="seller" {{ old('role') == 'seller' ? 'selected' : '' }}>Vendedor</option>
        <option value="consultant" {{ old('role') == 'consultant' ? 'selected' : '' }}>Consultor</option>
      </select>

      <button type="submit" class="btn">Guardar Usuario</button>
    </form>

    <a href="{{ url('/users') }}" class="back-link">← Volver a la lista de usuarios</a>
  </div>

  <!-- Modal de éxito -->
  <div id="successModal">
    <h2>✅ Usuario creado exitosamente</h2>
    <button onclick="goToUsers()">Continuar</button>
  </div>

  <script>
    // Toggle para contraseña
    const togglePassword = document.querySelector('#togglePassword');
    const password = document.querySelector('#password');
    togglePassword.addEventListener('click', () => {
      const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
      password.setAttribute('type', type);
      togglePassword.textContent = type === 'password' ? '👁️' : '🙈';
    });

    // Toggle para confirmar contraseña
    const togglePasswordConfirm = document.querySelector('#togglePasswordConfirm');
    const passwordConfirm = document.querySelector('#password_confirmation');
    togglePasswordConfirm.addEventListener('click', () => {
      const type = passwordConfirm.getAttribute('type') === 'password' ? 'text' : 'password';
      passwordConfirm.setAttribute('type', type);
      togglePasswordConfirm.textContent = type === 'password' ? '👁️' : '🙈';
    });

    function goToUsers() {
      window.location.href = '/users';
    }
  </script>
</body>
</html>

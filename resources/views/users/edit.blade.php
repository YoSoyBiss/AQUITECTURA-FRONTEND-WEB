<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <title>Editar Usuario</title>
  <style>
    body {
      background-color: #f4e3d7;
      font-family: Arial, sans-serif;
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
      display: block;
      margin-top: 15px;
    }

    input[type="text"],
    input[type="password"],
    select {
      width: 100%;
      padding: 10px;
      margin-top: 5px;
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
      cursor: pointer;
      margin-top: 25px;
      transition: background-color 0.3s ease;
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
  <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>

  <div class="container">
    <h1>Editar Usuario</h1>

    @if ($errors->any())
      <div class="error" style="color:red;">
        <ul>
          @foreach ($errors->all() as $error)
            <li>{{ is_array($error) ? implode(', ', $error) : $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    @php
      $userRoleId = old('role', data_get($user, 'role._id', $user['role'] ?? ''));
    @endphp

    <form id="editForm" method="POST" action="{{ url('/users/' . ($user['id'] ?? $user['_id'])) }}">
      @csrf
      @method('PUT')

      <label for="name">Nombre:</label>
      <input
        type="text"
        id="name"
        name="name"
        value="{{ old('name', $user['name']) }}"
        required
      />

      <label for="password">Nueva Contraseña (opcional):</label>
      <input
        type="password"
        id="password"
        name="password"
        autocomplete="new-password"
      />

      <label for="role">Rol:</label>
      <select id="role" name="role" required>
        <option value="">-- Selecciona un rol --</option>
        @foreach ($roles as $role)
          <option value="{{ $role['_id'] }}" {{ $userRoleId == $role['_id'] ? 'selected' : '' }}>
            {{ ucfirst($role['name']) }}
          </option>
        @endforeach
      </select>

      <button type="submit">Actualizar Usuario</button>
    </form>

    <a href="{{ url('/users') }}" class="back-link">← Volver al listado de usuarios</a>
  </div>

</body>
</html>

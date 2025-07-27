<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Editar Usuario</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f4e3d7;
      padding: 20px;
    }

    .container {
      background: white;
      padding: 20px;
      max-width: 600px;
      margin: auto;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }

    h1 {
      text-align: center;
      color: #333;
      margin-bottom: 20px;
    }

    form {
      display: flex;
      flex-direction: column;
      gap: 15px;
    }

    label {
      font-weight: bold;
      color: #5a3e36;
    }

    input, select {
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 4px;
      font-size: 16px;
    }

    button {
      background-color: #007bff;
      color: white;
      padding: 10px;
      border: none;
      border-radius: 4px;
      font-size: 16px;
      cursor: pointer;
    }

    button:hover {
      background-color: #0056b3;
    }
  </style>
</head>
<body>
  <div class="container">
    <h1>Editar Usuario</h1>

    <form action="{{ route('users.update', $user['_id']) }}" method="POST">
      @csrf
      @method('PUT')

      <label for="name">Nombre:</label>
      <input type="text" name="name" id="name" value="{{ $user['name'] }}" required>

      <label for="password">Nueva Contraseña:</label>
      <input type="password" name="password" id="password">

      <label for="role">Rol:</label>
      <select name="role" id="role" required>
        <option value="admin" {{ $user['role'] === 'admin' ? 'selected' : '' }}>Admin</option>
        <option value="seller" {{ $user['role'] === 'seller' ? 'selected' : '' }}>Vendedor</option>
        <option value="consultant" {{ $user['role'] === 'consultant' ? 'selected' : '' }}>Consultor</option>
      </select>

      <button type="submit">Actualizar</button>
    </form>
  </div>
</body>
</html>
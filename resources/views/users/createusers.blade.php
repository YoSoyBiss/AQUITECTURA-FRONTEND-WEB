<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Crear Usuario</title>
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
      background-color: #28a745;
      color: white;
      padding: 10px;
      border: none;
      border-radius: 4px;
      font-size: 16px;
      cursor: pointer;
    }

    button:hover {
      background-color: #218838;
    }
  </style>
</head>
<body>
  <div class="container">
    <h1>Crear Usuario</h1>

    <form action="{{ route('users.store') }}" method="POST">
      @csrf

      <label for="name">Nombre:</label>
      <input type="text" name="name" id="name" required>

      <label for="password">Contraseña:</label>
      <input type="password" name="password" id="password" required>

      <label for="role">Rol:</label>
      <select name="role" id="role" required>
        <option value="admin">Admin</option>
        <option value="seller">Vendedor</option>
        <option value="consultant">Consultor</option>
      </select>

      <button type="submit">Guardar</button>
    </form>
  </div>
</body>
</html>
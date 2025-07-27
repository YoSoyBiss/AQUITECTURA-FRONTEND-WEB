<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Lista de Usuarios</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f4e3d7;
      padding: 20px;
    }

    .container {
      background: white;
      padding: 20px;
      max-width: 900px;
      margin: auto;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    h1 {
      text-align: center;
      color: #333;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }

    th, td {
      padding: 10px;
      border: 1px solid #ccc;
      text-align: center;
    }

    th {
      background-color: #5a3e36;
      color: white;
    }

    tr:nth-child(even) {
      background-color: #d2e2e0;
    }

    .btn-create {
      display: inline-block;
      margin-bottom: 20px;
      background-color: #28a745;
      color: white;
      padding: 10px 15px;
      text-decoration: none;
      border-radius: 4px;
    }

    .btn-create:hover {
      background-color: #218838;
    }

    .btn-edit {
      background-color: #007bff;
      color: white;
      border: none;
      padding: 6px 10px;
      border-radius: 4px;
      cursor: pointer;
      text-decoration: none;
      margin-right: 5px;
    }

    .btn-edit:hover {
      background-color: #0056b3;
    }

    .btn-delete {
      background-color: #dc3545;
      color: white;
      border: none;
      padding: 6px 10px;
      border-radius: 4px;
      cursor: pointer;
    }

    .btn-delete:hover {
      background-color: #c82333;
    }
  </style>
</head>
<body>
  <div class="container">
    <h1>Lista de Usuarios</h1>

    <a class="btn-create" href="{{ route('users.createusers') }}">+ Agregar Usuario</a>

    <table>
      <thead>
        <tr>
          <th>Nombre</th>
          <th>Rol</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        @foreach($users as $user)
          <tr>
            <td>{{ $user['name'] }}</td>
            <td>{{ $user['role'] }}</td>
            <td>
              <a class="btn-edit" href="{{ route('users.editusers', $user['_id']) }}">Editar</a>
              <form action="{{ route('users.destroy', $user['_id']) }}" method="POST" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-delete" onclick="return confirm('¿Estás seguro de eliminar este usuario?')">Eliminar</button>
              </form>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</body>
</html>
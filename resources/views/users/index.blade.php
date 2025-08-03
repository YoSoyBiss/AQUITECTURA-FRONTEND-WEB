<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Lista de Usuarios</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f4e3d7;
      padding: 40px;
    }
    h1 { text-align: center; color: #5a3e36; }
    .container {
      max-width: 1000px;
      margin: auto;
      background: #fff;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(90, 62, 54, 0.2);
    }
    .add-button {
      display: inline-block;
      margin-bottom: 20px;
      padding: 10px 20px;
      background-color: #8d6e63;
      color: white;
      text-decoration: none;
      border-radius: 5px;
      font-weight: bold;
      transition: background-color 0.3s ease;
    }
    .add-button:hover {
      background-color: #795548;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 10px;
    }
    th, td {
      padding: 12px;
      border: 1px solid #ddd;
      text-align: center;
    }
    th {
      background-color: #d7ccc8;
      color: #4e342e;
    }
    tr:nth-child(even) {
      background-color: #f8f5f3;
    }
    .action-button {
      padding: 6px 12px;
      background-color: #6d4c41;
      color: white;
      border: none;
      border-radius: 4px;
      text-decoration: none;
      font-size: 14px;
      cursor: pointer;
    }
    .action-button:hover {
      background-color: #5d4037;
    }
    .delete-icon {
      cursor: pointer;
      color: #c62828;
      font-size: 18px;
    }
    .top-bar {
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    .modal {
      display: none;
      position: fixed;
      z-index: 999;
      left: 0; top: 0;
      width: 100%; height: 100%;
      background-color: rgba(0,0,0,0.5);
    }
    .modal-content {
      background-color: #fff;
      margin: 15% auto;
      padding: 20px;
      width: 400px;
      border-radius: 8px;
      text-align: center;
    }
    .modal-buttons {
      margin-top: 20px;
    }
    .modal-buttons button {
      padding: 10px 20px;
      margin: 0 10px;
      border: none;
      border-radius: 5px;
      font-weight: bold;
      cursor: pointer;
    }
    .btn-cancel {
      background-color: #ccc;
    }
    .btn-confirm {
      background-color: #c62828;
      color: white;
    }
    .alert {
      background-color: #fdd;
      border: 1px solid #d00;
      padding: 15px;
      margin-bottom: 20px;
      border-radius: 5px;
      color: #900;
    }
  </style>
  <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>

  <div class="container">
    <div class="top-bar">
      <h1>Lista de Usuarios</h1>
      <a href="{{ route('users.create') }}" class="add-button">+ Agregar Usuario</a>
    </div>

    {{-- BLOQUE DE ERRORES --}}
    @if ($errors->any())
      <div class="alert">
        <strong>¡Error!</strong>
        <ul style="margin-top: 10px;">
          @foreach ($errors->all() as $error)
            <li>{{ is_array($error) ? implode(', ', $error) : $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <table>
      <thead>
        <tr>
          <th>Nombre</th>
          <th>Correo</th>
          <th>Rol</th>
          <th>Acciones</th>
          <th>Eliminar</th>
        </tr>
      </thead>
      <tbody>
        @forelse ($users as $user)
          <tr id="row-{{ $user['id'] ?? $user['_id'] }}">
            <td>{{ $user['name'] }}</td>
            <td>{{ $user['email'] ?? '-' }}</td>
            <td>{{ $user['role'] }}</td>
            <td>
              <a href="{{ url('/users/edit/' . ($user['id'] ?? $user['_id'])) }}" class="action-button">Editar</a>
            </td>
            <td>
              <span class="delete-icon" onclick="confirmDelete('{{ $user['id'] ?? $user['_id'] }}')">&#128465;</span>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="5">No hay usuarios (o la API no responde).</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <!-- Modal de confirmación -->
  <div id="deleteModal" class="modal">
    <div class="modal-content">
      <p>¿Estás seguro que deseas eliminar este usuario?</p>
      <div class="modal-buttons">
        <button class="btn-cancel" onclick="closeModal()">Cancelar</button>
        <form id="deleteForm" method="POST" style="display:inline;">
          @csrf
          @method('DELETE')
          <button type="submit" class="btn-confirm">Eliminar</button>
        </form>
      </div>
    </div>
  </div>

  <script>
    let selectedId = null;

    function confirmDelete(id) {
      selectedId = id;
      document.getElementById('deleteModal').style.display = 'block';
      const form = document.getElementById('deleteForm');
      form.action = `/users/${selectedId}`;
    }

    function closeModal() {
      document.getElementById('deleteModal').style.display = 'none';
      selectedId = null;
    }
  </script>

</body>
</html>

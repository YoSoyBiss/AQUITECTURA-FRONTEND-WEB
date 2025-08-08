<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista de Roles</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f9f9f9; padding: 20px; }
        .container { background: #fff; padding: 20px; border-radius: 8px; max-width: 900px; margin: auto; }
        h1 { color: #333; }
        .btn { background: #4CAF50; color: white; padding: 8px 12px; text-decoration: none; border-radius: 4px; }
        .btn:hover { background: #45a049; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 10px; text-align: left; }
        .success { color: green; margin: 10px 0; }
        .error { color: red; margin: 10px 0; }
        form { display: inline; }
        button { background: #e74c3c; color: white; border: none; padding: 6px 10px; border-radius: 4px; cursor: pointer; }
        button:hover { background: #c0392b; }
    </style>
</head>
<body>
<div class="container">
    <h1>Lista de Roles</h1>

    <a href="{{ route('roles.create') }}" class="btn">➕ Crear nuevo rol</a>

    @if (session('success'))
        <div class="success">{{ session('success') }}</div>
    @endif

    @if ($errors->any())
        <div class="error">
            @foreach ($errors->all() as $e)
                <p>{{ $e }}</p>
            @endforeach
        </div>
    @endif

    <table>
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($roles as $role)
                <tr>
                    <td>{{ $role['name'] }}</td>
                    <td>{{ $role['description'] ?? '—' }}</td>
                    <td>
                        <a href="{{ route('roles.edit', $role['_id']) }}" class="btn">✏️ Editar</a>
                        <form action="{{ route('roles.destroy', $role['_id']) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('¿Eliminar este rol?')">🗑️ Eliminar</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
</body>
</html>

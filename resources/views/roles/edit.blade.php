<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Rol</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f9f9f9; padding: 20px; }
        .container { background: #fff; padding: 20px; border-radius: 8px; max-width: 600px; margin: auto; }
        h1 { color: #333; }
        label { display: block; margin-top: 15px; font-weight: bold; }
        input[type="text"] { width: 100%; padding: 8px; margin-top: 5px; border: 1px solid #ccc; border-radius: 4px; }
        .btn { margin-top: 20px; background: #2196F3; color: white; padding: 10px 16px; border: none; border-radius: 4px; cursor: pointer; }
        .btn:hover { background: #1976D2; }
        .back-link { display: block; margin-top: 20px; color: #555; text-decoration: none; }
        .back-link:hover { text-decoration: underline; }
    </style>
</head>
<body>
<div class="container">
    <h1>Editar Rol</h1>

    <form action="{{ route('roles.update', $role['_id']) }}" method="POST">
        @csrf
        @method('PUT')

        <label>Nombre del rol:</label>
        <input type="text" name="name" value="{{ $role['name'] }}" required>

        <label>Descripción:</label>
        <input type="text" name="description" value="{{ $role['description'] ?? '' }}">

        <button type="submit" class="btn">Actualizar Rol</button>
    </form>

    <a href="{{ route('roles.index') }}" class="back-link">← Volver a la lista</a>
</div>
</body>
</html>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Rol</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f9f9f9; padding: 20px; }
        .container { background: #fff; padding: 20px; border-radius: 8px; max-width: 600px; margin: auto; }
        h1 { color: #333; }
        label { display: block; margin-top: 15px; font-weight: bold; }
        input[type="text"] { width: 100%; padding: 8px; margin-top: 5px; border: 1px solid #ccc; border-radius: 4px; }
        .btn { margin-top: 20px; background: #4CAF50; color: white; padding: 10px 16px; border: none; border-radius: 4px; cursor: pointer; }
        .btn:hover { background: #45a049; }
        .back-link { display: block; margin-top: 20px; color: #555; text-decoration: none; }
        .back-link:hover { text-decoration: underline; }
    </style>
</head>
<body>
<div class="container">
    <h1>Crear Rol</h1>

    <form action="{{ route('roles.store') }}" method="POST">
        @csrf

        <label>Nombre del rol:</label>
        <input type="text" name="name" required>

        <label>Descripción:</label>
        <input type="text" name="description">

        <button type="submit" class="btn">Guardar Rol</button>
    </form>

    <a href="{{ route('roles.index') }}" class="back-link">← Volver a la lista</a>
</div>
</body>
</html>

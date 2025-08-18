<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Editar Rol</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <style>
    :root{
      --bg: #f4e3d7;
      --brand: #8d6e63;
      --card: #ffffff;
      --ink: #000000;
      --soft: #f2f2f2;
      --soft-2: #dddddd;
      --shadow: 0 10px 28px rgba(0,0,0,0.2);
      --radius: 14px;
      --radius-sm: 10px;
      --transition: .2s ease;
    }

    *{ box-sizing: border-box; }
    html,body{ margin:0; padding:0; }
    body{
      font-family: ui-sans-serif, system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial;
      background: radial-gradient(1000px 600px at 80% -10%, #fff6f0 0%, var(--bg) 60%) fixed;
      color: var(--ink);
      padding: 32px 16px;
    }

    .wrap{ max-width: 640px; margin: 0 auto; }

    .card{
      background: var(--card);
      border: 1px solid var(--soft-2);
      border-radius: var(--radius);
      box-shadow: var(--shadow);
      padding: 28px 32px;
    }

    .card h1{
      margin: 0 0 20px;
      font-size: 26px;
      font-weight: 700;
      color: var(--brand);
      text-align: center;
    }

    label{
      display: block;
      margin-top: 16px;
      font-weight: 600;
      font-size: 14px;
      color: var(--ink);
    }
    input[type="text"]{
      width: 100%;
      padding: 10px 12px;
      margin-top: 6px;
      border: 1px solid var(--soft-2);
      border-radius: var(--radius-sm);
      background: var(--soft);
      font-size: 15px;
      transition: border var(--transition), background var(--transition);
    }
    input[type="text"]:focus{
      border-color: var(--brand);
      outline: none;
      background: #fff;
    }

    .btn{
      margin-top: 24px;
      display: inline-block;
      background: var(--brand);
      color: #fff;
      font-weight: 600;
      border: none;
      border-radius: 999px;
      padding: 12px 20px;
      font-size: 15px;
      cursor: pointer;
      box-shadow: 0 6px 16px rgba(0,0,0,0.12);
      transition: background var(--transition), transform var(--transition), box-shadow var(--transition);
    }
    .btn:hover{ background: #7b5d52; transform: translateY(-1px); box-shadow: 0 8px 20px rgba(0,0,0,0.14); }
    .btn:active{ transform: translateY(0); box-shadow: 0 4px 10px rgba(0,0,0,0.1); }

    .back-link{
      display: inline-block;
      margin-top: 20px;
      color: var(--brand);
      text-decoration: none;
      font-size: 14px;
      font-weight: 600;
    }
    .back-link:hover{ text-decoration: underline; }
  </style>
</head>
<body>
  <div class="wrap">
    <div class="card">
      <h1>Editar Rol</h1>

      <form action="{{ route('roles.update', $role['_id']) }}" method="POST">
        @csrf
        @method('PUT')

        <label>Nombre del rol:</label>
        <input type="text" name="name" value="{{ $role['name'] }}" required>

        <label>Descripción:</label>
        <input type="text" name="description" value="{{ $role['description'] ?? '' }}">

        <button type="submit" class="btn">💾 Actualizar Rol</button>
      </form>

      <a href="{{ route('roles.index') }}" class="back-link">← Volver a la lista</a>
    </div>
  </div>
</body>
</html>

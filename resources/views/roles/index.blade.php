<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Lista de Roles</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <style>
    :root{
      --bg: #f4e3d7;          /* body */
      --brand: #8d6e63;       /* primary */
      --card: #ffffff;        /* container */
      --ink: #000000;         /* text */
      --muted: #6f6f6f;       /* secondary text (derivado) */
      --soft: #f2f2f2;        /* table alt / chips */
      --soft-2: #dddddd;      /* borders / hovers */
      --shadow: 0 12px 32px rgba(0,0,0,0.2);
      --radius: 14px;
      --radius-sm: 10px;
      --radius-xs: 8px;
      --transition: .2s ease;
    }

    *{ box-sizing: border-box; }
    html,body{ margin:0; padding:0; }
    body{
      font-family: ui-sans-serif, system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", "Apple Color Emoji", "Segoe UI Emoji";
      background:
        radial-gradient(1200px 700px at 85% -10%, #fff6f0 0%, var(--bg) 60%) fixed;
      color: var(--ink);
      padding: 24px;
    }

    .wrap{
      max-width: 1080px;
      margin: 0 auto;
    }

    .page{
      background: var(--card);
      border: 1px solid var(--soft-2);
      border-radius: var(--radius);
      box-shadow: var(--shadow);
      overflow: clip;
    }

    .page-header{
      display: flex; align-items: center; justify-content: space-between;
      padding: 22px 24px;
      background:
        linear-gradient(0deg, rgba(255,255,255,0.8), rgba(255,255,255,0.8)),
        linear-gradient(135deg, #9a7a6f, var(--brand));
      color: #fff;
    }
    .page-title{
      margin: 0;
      font-size: clamp(22px, 2.2vw, 28px);
      letter-spacing: 0.3px;
      font-weight: 700;
    }

    .page-actions{
      display: flex; gap: 10px; flex-wrap: wrap;
    }

    .btn{
      --_bg: var(--brand);
      --_ink: #fff;
      display: inline-flex; align-items: center; gap: 8px;
      background: var(--_bg); color: var(--_ink);
      border: 1px solid rgba(0,0,0,0.05);
      padding: 10px 14px;
      border-radius: 999px;
      text-decoration: none;
      font-weight: 600;
      font-size: 14px;
      transition: transform var(--transition), box-shadow var(--transition), background var(--transition);
      box-shadow: 0 6px 16px rgba(0,0,0,0.12);
      white-space: nowrap;
    }
    .btn:hover{ transform: translateY(-1px); box-shadow: 0 8px 22px rgba(0,0,0,0.14); }
    .btn:active{ transform: translateY(0); box-shadow: 0 4px 12px rgba(0,0,0,0.12); }

    .btn--secondary{
      --_bg: var(--soft);
      --_ink: var(--ink);
      border-color: var(--soft-2);
    }
    .btn--danger{
      --_bg: #e74c3c;
      --_ink: #fff;
    }
    .btn--ghost{
      background: transparent;
      color: var(--brand);
      border-color: var(--brand);
    }

    .page-body{ padding: 18px 24px 24px; }

    /* Mensajes */
    .alert{
      display: grid; grid-template-columns: 22px 1fr; gap: 10px;
      align-items: start;
      padding: 12px 14px;
      border-radius: var(--radius-xs);
      border: 1px solid var(--soft-2);
      background: var(--soft);
      color: var(--ink);
      margin: 12px 0;
    }
    .alert--success{
      border-color: rgba(46,125,50,.25);
      background: rgba(46,125,50,.08);
      color: #2e7d32;
    }
    .alert--error{
      border-color: rgba(198,40,40,.25);
      background: rgba(198,40,40,.08);
      color: #c62828;
    }

    /* Tabla */
    .table-wrap{ overflow-x: auto; border-radius: var(--radius-sm); border: 1px solid var(--soft-2); background: #fff; }
    table{
      width: 100%; border-collapse: separate; border-spacing: 0; min-width: 720px;
      font-size: 14px;
    }
    thead th{
      position: sticky; top: 0; z-index: 1;
      background: linear-gradient(0deg, #fff, #fff);
      border-bottom: 1px solid var(--soft-2);
      text-align: left; padding: 12px 14px; color: #3c3c3c; font-weight: 700;
    }
    tbody td{
      border-bottom: 1px solid var(--soft-2);
      padding: 12px 14px; color: #222;
    }
    tbody tr:nth-child(odd){ background: #fff; }
    tbody tr:nth-child(even){ background: var(--soft); }
    tbody tr:hover{ outline: 2px solid var(--brand); outline-offset: -2px; background: #fff; }

    .actions{
      display: flex; gap: 8px; align-items: center; flex-wrap: wrap;
    }

    /* Formularios */
    form{ display: inline; }
    button{
      all: unset;
      display: inline-flex; align-items: center; gap: 6px;
      background: #e74c3c; color: #fff;
      padding: 8px 12px; border-radius: 999px; cursor: pointer;
      border: 1px solid rgba(0,0,0,0.05);
      box-shadow: 0 6px 16px rgba(0,0,0,0.12);
      font-weight: 600; font-size: 14px;
      transition: transform var(--transition), box-shadow var(--transition), background var(--transition);
    }
    button:hover{ transform: translateY(-1px); box-shadow: 0 8px 22px rgba(0,0,0,0.14); }
    button:active{ transform: translateY(0); }

    /* Chips/labels */
    .chip{
      display: inline-flex; align-items: center; gap: 8px;
      background: var(--soft);
      border: 1px solid var(--soft-2);
      border-radius: 999px; padding: 6px 10px; color: #333; font-weight: 600;
    }

    /* Footer */
    .page-footer{
      padding: 16px 24px;
      border-top: 1px solid var(--soft-2);
      background: #fff;
      display: flex; justify-content: space-between; align-items: center; gap: 12px; flex-wrap: wrap;
      color: var(--muted); font-size: 13px;
    }

    /* Accesibilidad & pequeños toques */
    a:focus-visible, button:focus-visible{
      outline: 3px solid var(--brand);
      outline-offset: 2px;
      border-radius: 10px;
    }

    @media (max-width: 640px){
      .page-header{ padding: 18px; }
      .page-body{ padding: 14px 16px 18px; }
      .page-footer{ padding: 12px 16px; }
    }
  </style>
</head>
<body>
  <div class="wrap">
    <div class="page" role="region" aria-labelledby="page-title">
      <header class="page-header">
        <h1 id="page-title" class="page-title">Lista de Roles</h1>
        <div class="page-actions">
          <a href="{{ route('roles.create') }}" class="btn" title="Crear nuevo rol">➕ Crear nuevo rol</a>
          <a href="{{ route('dashboard.redirect') }}" class="add-button">🏠📚 Menú principal</a>
        </div>
      </header>

      <section class="page-body">
        @if (session('success'))
          <div class="alert alert--success" role="status">
            <span>✅</span>
            <div>{{ session('success') }}</div>
          </div>
        @endif

        @if ($errors->any())
          <div class="alert alert--error" role="alert">
            <span>⚠️</span>
            <div>
              @foreach ($errors->all() as $e)
                <p style="margin:6px 0">{{ $e }}</p>
              @endforeach
            </div>
          </div>
        @endif

        <div class="table-wrap">
          <table aria-label="Tabla de roles">
            <thead>
              <tr>
                <th>Nombre</th>
                <th>Descripción</th>
                <th style="width:260px">Acciones</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($roles as $role)
                <tr>
                  <td>
                    <span class="chip">🧩 {{ $role['name'] }}</span>
                  </td>
                  <td>{{ $role['description'] ?? '—' }}</td>
                  <td>
                    <div class="actions">
                      <a href="{{ route('roles.edit', $role['_id']) }}" class="btn btn--secondary" title="Editar rol">✏️ Editar</a>

                      <form action="{{ route('roles.destroy', $role['_id']) }}" method="POST" onsubmit="return confirm('¿Eliminar este rol?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" title="Eliminar rol">🗑️ Eliminar</button>
                      </form>
                    </div>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </section>

      <footer class="page-footer">
        <span>📚 Administración de roles</span>
        <span style="opacity:.8">UI con paleta crema & madera — {{ now()->format('d/m/Y H:i') }}</span>
      </footer>
    </div>
  </div>
</body>
</html>

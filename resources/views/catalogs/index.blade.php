<!-- resources/views/catalogs/index.blade.php -->
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Catálogos</title>
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
  :root{
    --bg:#f4e3d7; --card:#ffffff; --ink:#4e342e; --muted:#6d4c41;
    --line:#e0d6d2; --accent:#8d6e63; --accent-2:#795548; --ok:#2e7d32; --danger:#c62828;
    --chip:#d7ccc8; --chip-ink:#4e342e; --shadow:0 10px 24px rgba(90,62,54,.18);
  }
  *{box-sizing:border-box}
  body{font-family:Arial,Helvetica,sans-serif;background:var(--bg);padding:40px;color:var(--ink)}
  h1{margin:0 0 12px;text-align:center;color:#5a3e36}
  .container{max-width:1200px;margin:auto;background:var(--card);padding:22px;border-radius:14px;box-shadow:var(--shadow)}

  .topbar{display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap}
  .tabs{display:flex;gap:8px;margin:16px 0 10px;flex-wrap:wrap}
  .tab{padding:10px 14px;border:1px solid var(--chip);border-radius:999px;background:#f8f5f3;color:var(--ink);cursor:pointer}
  .tab.active{background:var(--accent);color:#fff;border-color:var(--accent)}
  .view-switch{display:flex;gap:6px;align-items:center}
  .pill{background:var(--chip);color:var(--chip-ink);border-radius:999px;padding:6px 10px;font-size:12px;border:1px solid #cdbfb9}
  .muted{color:var(--muted);font-size:12px;margin-top:6px}

  .btn{padding:9px 12px;background:var(--accent);color:#fff;border:none;border-radius:8px;cursor:pointer}
  .btn:hover{background:var(--accent-2)}
  .btn-light{background:var(--chip);color:var(--chip-ink)}
  .btn-danger{background:var(--danger);color:#fff}
  .btn:disabled{opacity:.6;cursor:not-allowed}

  .toolbar{display:flex;gap:8px;flex-wrap:wrap;align-items:center;justify-content:space-between;margin-bottom:6px}
  .left-actions{display:flex;gap:8px;align-items:center}

  .search{padding:10px;border:1px solid #d7ccc8;border-radius:8px;width:100%}
  .grid-two{display:grid;grid-template-columns:1fr 120px;gap:10px;margin:8px 0 12px}

  .cards{display:grid;grid-template-columns:repeat(3,1fr);gap:16px}
  @media (max-width:1024px){ .cards{grid-template-columns:1fr 1fr} }
  @media (max-width:640px){ .cards{grid-template-columns:1fr} }

  .card{border:1px solid var(--line);border-radius:12px;overflow:hidden;background:#fff;box-shadow:0 6px 14px rgba(0,0,0,.04)}
  .card-head{display:flex;align-items:center;justify-content:space-between;background:#fbf9f8;border-bottom:1px solid var(--line);padding:12px 14px}
  .card-title{display:flex;align-items:center;gap:8px}
  .card-body{padding:12px;overflow:auto}
  .count{font-size:12px;background:var(--chip);color:var(--chip-ink);border-radius:999px;padding:4px 8px;border:1px solid #cdbfb9}

  table{width:100%;border-collapse:separate;border-spacing:0;min-width:420px}
  th,td{padding:11px 10px;border-bottom:1px solid var(--line);text-align:left}
  th{background:#f4efec;color:var(--ink);position:sticky;top:0;z-index:1}
  tr:hover td{background:#faf6f4}
  .row-actions{display:flex;gap:6px;flex-wrap:wrap;align-items:center}

  dialog{border:none;border-radius:12px;max-width:460px;width:92%;padding:0;overflow:hidden;box-shadow:var(--shadow)}
  .modal-head{background:var(--accent);color:#fff;padding:12px 14px;display:flex;justify-content:space-between;align-items:center}
  .modal-body{padding:16px}
  .modal-actions{display:flex;justify-content:flex-end;gap:8px;padding:12px}
  .field{display:grid;gap:6px;margin-bottom:10px}
  .field input{padding:10px;border:1px solid #d7ccc8;border-radius:8px;width:100%}

  .hidden{display:none !important}
  .soft{opacity:.8}
</style>
</head>
<body>
<div class="container">
  <h1>Catálogos <span class="pill">Administración</span></h1>

  <div class="toolbar">
    <div class="left-actions">
      <a class="btn-light" href="{{ route('products.index') }}" title="Volver al listado de productos">← Volver a productos</a>
      <button class="btn-light" type="button" onclick="location.reload()">Refrescar</button>
    </div>
    <div class="view-switch">
      <span class="muted soft">Vista</span>
      <button class="btn-light" id="btnTabs" type="button">Pestañas</button>
      <button class="btn" id="btnMosaic" type="button">Mosaico (3 tablas)</button>
    </div>
  </div>

  @if ($errors->any())
    <div class="alert">@foreach ($errors->all() as $e)<div>{{ $e }}</div>@endforeach</div>
  @endif
  @if (session('ok'))
    <div class="ok">{{ session('ok') }}</div>
  @endif

  <div class="tabs" id="tabBar">
    <button class="tab active" data-tab="publishers">Editoriales</button>
    <button class="tab" data-tab="authors">Autores</button>
    <button class="tab" data-tab="genres">Géneros</button>
  </div>

  {{-- ========== VISTA: PESTAÑAS ========== --}}
  <div id="viewTabs">
    {{-- ===== Editoriales ===== --}}
    <section id="tab-publishers">
      <div class="card">
        <div class="card-head">
          <div class="card-title">
            <strong>Editoriales</strong>
            <span class="count" id="count-publishers">{{ is_countable($publishers ?? []) ? count($publishers) : 0 }}</span>
          </div>
          <div class="header-actions">
            <button class="btn" type="button" onclick="openModal('publisher')">+ Agregar</button>
          </div>
        </div>
        <div class="card-body">
          <table id="tbl-publishers">
            <thead><tr><th style="width:70px;">ID</th><th>Nombre</th><th style="width:260px;">Acciones</th></tr></thead>
            <tbody>
            @forelse(($publishers ?? []) as $p)
              <tr data-name="{{ strtolower($p['name']) }}">
                <td>{{ $p['id'] }}</td>
                <td>{{ $p['name'] }}</td>
                <td class="row-actions">
                  <button type="button" class="btn-light" onclick="event.stopPropagation(); openModal('publisher', {{ $p['id'] }}, '{{ addslashes($p['name']) }}')">Editar</button>
                  <form method="POST" action="{{ route('catalogs.publishers.destroy',$p['id']) }}" style="display:inline;">
                    @csrf @method('DELETE')
                    <button type="button" class="btn-danger delete-btn" onclick="event.stopPropagation(); return false;">Eliminar</button>
                  </form>
                </td>
              </tr>
            @empty
              <tr class="empty"><td colspan="3">Sin editoriales</td></tr>
            @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </section>

    {{-- ===== Autores ===== --}}
    <section id="tab-authors" class="hidden">
      <div class="card">
        <div class="card-head">
          <div class="card-title">
            <strong>Autores</strong>
            <span class="count" id="count-authors">{{ is_countable($authors ?? []) ? count($authors) : 0 }}</span>
          </div>
          <div class="header-actions">
            <button class="btn" type="button" onclick="openModal('author')">+ Agregar</button>
          </div>
        </div>
        <div class="card-body">
          <table id="tbl-authors">
            <thead><tr><th style="width:70px;">ID</th><th>Nombre</th><th style="width:260px;">Acciones</th></tr></thead>
            <tbody>
            @forelse(($authors ?? []) as $a)
              <tr data-name="{{ strtolower($a['name']) }}">
                <td>{{ $a['id'] }}</td>
                <td>{{ $a['name'] }}</td>
                <td class="row-actions">
                  <button type="button" class="btn-light" onclick="event.stopPropagation(); openModal('author', {{ $a['id'] }}, '{{ addslashes($a['name']) }}')">Editar</button>
                  <form method="POST" action="{{ route('catalogs.authors.destroy',$a['id']) }}" style="display:inline;">
                    @csrf @method('DELETE')
                    <button type="button" class="btn-danger delete-btn" onclick="event.stopPropagation(); return false;">Eliminar</button>
                  </form>
                </td>
              </tr>
            @empty
              <tr class="empty"><td colspan="3">Sin autores</td></tr>
            @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </section>

    {{-- ===== Géneros ===== --}}
    <section id="tab-genres" class="hidden">
      
      <div class="card">
        <div class="card-head">
          <div class="card-title">
            <strong>Géneros</strong>
            <span class="count" id="count-genres">{{ is_countable($genres ?? []) ? count($genres) : 0 }}</span>
          </div>
          <div class="header-actions">
            <button class="btn" type="button" onclick="openModal('genre')">+ Agregar</button>
          </div>
        </div>
        <div class="card-body">
          <table id="tbl-genres">
            <thead><tr><th style="width:70px;">ID</th><th>Nombre</th><th style="width:260px;">Acciones</th></tr></thead>
            <tbody>
            @forelse(($genres ?? []) as $g)
              <tr data-name="{{ strtolower($g['name']) }}">
                <td>{{ $g['id'] }}</td>
                <td>{{ $g['name'] }}</td>
                <td class="row-actions">
                  <button type="button" class="btn-light" onclick="event.stopPropagation(); openModal('genre', {{ $g['id'] }}, '{{ addslashes($g['name']) }}')">Editar</button>
                  <form method="POST" action="{{ route('catalogs.genres.destroy',$g['id']) }}" style="display:inline;">
                    @csrf @method('DELETE')
                    <button type="button" class="btn-danger delete-btn" onclick="event.stopPropagation(); return false;">Eliminar</button>
                  </form>
                </td>
              </tr>
            @empty
              <tr class="empty"><td colspan="3">Sin géneros</td></tr>
            @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </section>
  </div>

  {{-- ========== VISTA: MOSAICO ========== --}}
  <div id="viewMosaic" class="hidden">
    <div class="cards">
      {{-- Editoriales --}}
      <div class="card">
        <div class="card-head">
          <div class="card-title">
            <strong>Editoriales</strong>
            <span class="count" id="count-publishers-m">{{ is_countable($publishers ?? []) ? count($publishers) : 0 }}</span>
          </div>
          <div class="header-actions"><button class="btn" type="button" onclick="openModal('publisher')">+ Agregar</button></div>
        </div>
        <div class="card-body">
          <input class="search" type="text" id="q_pub_m" placeholder="Buscar editorial..." style="margin-bottom:10px">
          <table id="tbl-publishers-m">
            <thead><tr><th>ID</th><th>Nombre</th><th>Acciones</th></tr></thead>
            <tbody>
              @forelse(($publishers ?? []) as $p)
                <tr data-name="{{ strtolower($p['name']) }}">
                  <td>{{ $p['id'] }}</td>
                  <td>{{ $p['name'] }}</td>
                  <td class="row-actions">
                    <button type="button" class="btn-light" onclick="event.stopPropagation(); openModal('publisher', {{ $p['id'] }}, '{{ addslashes($p['name']) }}')">Editar</button>
                    <form method="POST" action="{{ route('catalogs.publishers.destroy',$p['id']) }}" style="display:inline;">
                      @csrf @method('DELETE')
                      <button type="button" class="btn-danger delete-btn" onclick="event.stopPropagation(); return false;">Eliminar</button>
                    </form>
                  </td>
                </tr>
              @empty
                <tr class="empty"><td colspan="3">Sin editoriales</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>

      {{-- Autores --}}
      <div class="card">
        <div class="card-head">
          <div class="card-title">
            <strong>Autores</strong>
            <span class="count" id="count-authors-m">{{ is_countable($authors ?? []) ? count($authors) : 0 }}</span>
          </div>
          <div class="header-actions"><button class="btn" type="button" onclick="openModal('author')">+ Agregar</button></div>
        </div>
        <div class="card-body">
          <input class="search" type="text" id="q_auth_m" placeholder="Buscar autor..." style="margin-bottom:10px">
          <table id="tbl-authors-m">
            <thead><tr><th>ID</th><th>Nombre</th><th>Acciones</th></tr></thead>
            <tbody>
              @forelse(($authors ?? []) as $a)
                <tr data-name="{{ strtolower($a['name']) }}">
                  <td>{{ $a['id'] }}</td>
                  <td>{{ $a['name'] }}</td>
                  <td class="row-actions">
                    <button type="button" class="btn-light" onclick="event.stopPropagation(); openModal('author', {{ $a['id'] }}, '{{ addslashes($a['name']) }}')">Editar</button>
                    <form method="POST" action="{{ route('catalogs.authors.destroy',$a['id']) }}" style="display:inline;">
                      @csrf @method('DELETE')
                      <button type="button" class="btn-danger delete-btn" onclick="event.stopPropagation(); return false;">Eliminar</button>
                    </form>
                  </td>
                </tr>
              @empty
                <tr class="empty"><td colspan="3">Sin autores</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>

      {{-- Géneros --}}
      <div class="card">
        <div class="card-head">
          <div class="card-title">
            <strong>Géneros</strong>
            <span class="count" id="count-genres-m">{{ is_countable($genres ?? []) ? count($genres) : 0 }}</span>
          </div>
          <div class="header-actions"><button class="btn" type="button" onclick="openModal('genre')">+ Agregar</button></div>
        </div>
        <div class="card-body">
          <input class="search" type="text" id="q_gen_m" placeholder="Buscar género..." style="margin-bottom:10px">
          <table id="tbl-genres-m">
            <thead><tr><th>ID</th><th>Nombre</th><th>Acciones</th></tr></thead>
            <tbody>
              @forelse(($genres ?? []) as $g)
                <tr data-name="{{ strtolower($g['name']) }}">
                  <td>{{ $g['id'] }}</td>
                  <td>{{ $g['name'] }}</td>
                  <td class="row-actions">
                    <button type="button" class="btn-light" onclick="event.stopPropagation(); openModal('genre', {{ $g['id'] }}, '{{ addslashes($g['name']) }}')">Editar</button>
                    <form method="POST" action="{{ route('catalogs.genres.destroy',$g['id']) }}" style="display:inline;">
                      @csrf @method('DELETE')
                      <button type="button" class="btn-danger delete-btn" onclick="event.stopPropagation(); return false;">Eliminar</button>
                    </form>
                  </td>
                </tr>
              @empty
                <tr class="empty"><td colspan="3">Sin géneros</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>

    </div>
  </div>
</div>

<!-- Modal reutilizable -->
<dialog id="modalEntity">
  <div class="modal-head">
    <strong id="m-title">Nuevo</strong>
    <button class="btn-light" type="button" onclick="closeAnyModal()">✕</button>
  </div>
  <form id="m-form" method="POST" class="modal-body" action="#">
    @csrf
    <input type="hidden" name="_method" id="m-method" value="POST">
    <div class="field">
      <label for="m-name">Nombre</label>
      <input id="m-name" type="text" name="name" required>
    </div>
    <div class="modal-actions">
      <button type="button" class="btn-light" onclick="closeAnyModal()">Cancelar</button>
      <button class="btn" type="submit">Guardar</button>
    </div>
  </form>
</dialog>

<script>
  /* ===== Rutas reales generadas por Blade ===== */
  const ROUTES = {
    publisher: {
      store:  "{{ route('catalogs.publishers.store') }}",
      update: "{{ route('catalogs.publishers.update', ['id' => '___ID___']) }}".replace('___ID___', '{id}')
    },
    author: {
      store:  "{{ route('catalogs.authors.store') }}",
      update: "{{ route('catalogs.authors.update', ['id' => '___ID___']) }}".replace('___ID___', '{id}')
    },
    genre: {
      store:  "{{ route('catalogs.genres.store') }}",
      update: "{{ route('catalogs.genres.update', ['id' => '___ID___']) }}".replace('___ID___', '{id}')
    }
  };

  /* ===== Cambio de vista (tabs/mosaico) ===== */
  const viewTabs   = document.getElementById('viewTabs');
  const viewMosaic = document.getElementById('viewMosaic');
  document.getElementById('btnTabs').addEventListener('click', ()=>{
    viewTabs.classList.remove('hidden'); viewMosaic.classList.add('hidden');
  });
  document.getElementById('btnMosaic').addEventListener('click', ()=>{
    viewMosaic.classList.remove('hidden'); viewTabs.classList.add('hidden');
  });

  /* ===== Tabs ===== */
  const tabs = document.querySelectorAll('.tab');
  const sections = {
    publishers: document.getElementById('tab-publishers'),
    authors:    document.getElementById('tab-authors'),
    genres:     document.getElementById('tab-genres'),
  };
  tabs.forEach(t => t.addEventListener('click', () => {
    tabs.forEach(x => x.classList.remove('active'));
    t.classList.add('active');
    Object.values(sections).forEach(s => s.classList.add('hidden'));
    sections[t.dataset.tab].classList.remove('hidden');
  }));

  /* ===== Modal reutilizable ===== */
  const dlg  = document.getElementById('modalEntity');
  const form = document.getElementById('m-form');
  const mt   = document.getElementById('m-title');
  const mm   = document.getElementById('m-method');
  const mn   = document.getElementById('m-name');

  function openModal(entity, id = null, name = '') {
    if (!ROUTES[entity]) return console.error('Entidad desconocida:', entity);

    if (id) {
      mt.textContent = `Editar ${labelOf(entity)}`;
      form.action = ROUTES[entity].update.replace('{id}', id);
      mm.value = 'PUT';
      mn.value = name || '';
    } else {
      mt.textContent = `Nuevo ${labelOf(entity)}`;
      form.action = ROUTES[entity].store;
      mm.value = 'POST';
      mn.value = '';
    }
    dlg.showModal();
    setTimeout(()=>mn.focus(), 50);
  }
  function labelOf(entity){
    return entity === 'publisher' ? 'editorial' : entity === 'author' ? 'autor' : 'género';
  }
  function closeAnyModal(){ dlg.close(); }

  /* ===== Búsqueda en cliente ===== */
  function addClientSearch(inputId, tableId, countId){
    const input = document.getElementById(inputId);
    const table = document.getElementById(tableId);
    const count = document.getElementById(countId);
    if (!input || !table) return;

    const tbody = table.querySelector('tbody');
    const rows  = Array.from(tbody.querySelectorAll('tr')).filter(r => !r.classList.contains('empty'));
    const emptyRow = tbody.querySelector('.empty');

    function apply(){
      const q = (input.value || '').toLowerCase().trim();
      let visible = 0;
      rows.forEach(r=>{
        const name = (r.getAttribute('data-name') || '').toLowerCase();
        const show = !q || name.includes(q);
        r.style.display = show ? '' : 'none';
        if (show) visible++;
      });
      if (count) count.textContent = visible;
      if (emptyRow) emptyRow.style.display = visible === 0 ? '' : 'none';
    }
    input.addEventListener('input', apply);
    apply();
  }
  addClientSearch('q_pub','tbl-publishers','count-publishers');
  addClientSearch('q_auth','tbl-authors','count-authors');
  addClientSearch('q_gen','tbl-genres','count-genres');
  addClientSearch('q_pub_m','tbl-publishers-m','count-publishers-m');
  addClientSearch('q_auth_m','tbl-authors-m','count-authors-m');
  addClientSearch('q_gen_m','tbl-genres-m','count-genres-m');

  /* ===== Eliminar con confirm (evita submits accidentales) ===== */
  document.addEventListener('click', function(e){
    const btn = e.target.closest('.delete-btn');
    if (!btn) return;
    const form = btn.closest('form');
    if (!form) return;
    const ok = confirm('¿Seguro que deseas eliminar este registro?');
    if (ok) form.submit();
  });
</script>
</body>
</html>

<!-- resources/views/products/index.blade.php -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Listado de Productos</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4e3d7; padding: 40px; }
        h1 { text-align: center; color: #5a3e36; margin-bottom: 10px; }
        .container { max-width: 1100px; margin: auto; background: #fff; padding: 30px; border-radius: 10px; box-shadow: 0 0 10px rgba(90, 62, 54, 0.2); }
        .top-bar { display: flex; justify-content: space-between; align-items: center; }
        .add-button, .btn { display: inline-block; padding: 10px 16px; background: #8d6e63; color: #fff; text-decoration: none; border-radius: 6px; font-weight: bold; border: none; cursor: pointer; }
        .add-button:hover, .btn:hover { background: #795548; }
        .btn-light { background: #d7ccc8; color: #4e342e; }
        .btn-light:hover { background: #c5b4ad; }
        .filters { display: grid; grid-template-columns: repeat(4, 1fr); gap: 12px; background: #f8f5f3; padding: 14px; border-radius: 8px; margin: 12px 0 18px; border: 1px solid #e0d6d2; }
        .filters label { display: block; color: #4e342e; font-weight: bold; margin-bottom: 6px; }
        .filters input, .filters select { width: 100%; padding: 9px; border: 1px solid #d7ccc8; border-radius: 6px; }
        
        /* === Tabla con scroll === */
        .table-wrapper {
            max-height: 400px;   /* Ajusta la altura según tu gusto */
            overflow-y: auto;
            border: 1px solid #ddd;
            border-radius: 6px;
        }
        table { width: 100%; border-collapse: collapse; min-width: 900px; }
        th, td { padding: 12px; border: 1px solid #ddd; text-align: center; vertical-align: middle; }
        th { background-color: #d7ccc8; color: #4e342e; position: sticky; top: 0; z-index: 2; }
        tr:nth-child(even) { background-color: #f8f5f3; }
        .action-button { padding: 6px 12px; background-color: #6d4c41; color: white; border: none; border-radius: 4px; text-decoration: none; font-size: 14px; cursor: pointer; }
        .action-button:hover { background-color: #5d4037; }
        .delete-icon { cursor: pointer; color: #c62828; font-size: 14px; background: #ffebee; border: 1px solid #ffcdd2; padding: 6px 10px; border-radius: 4px; }
        .alert { background: #ffebee; border: 1px solid #ffcdd2; color: #c62828; padding: 10px; border-radius: 6px; margin-bottom: 15px; }
        .ok { background: #e8f5e9; border: 1px solid #c8e6c9; color: #2e7d32; padding: 10px; border-radius: 6px; margin-bottom: 15px; }
        .buttons { display: flex; gap: 8px; align-items: center; justify-content: flex-end; }
        .muted { color: #6d4c41; font-size: 12px; margin-top: 4px; grid-column: 1 / -1; }

        /* ===== Preview único con lupa ===== */
        .thumb-preview { position: relative; width: 96px; height: 96px; margin: 0 auto; border-radius: 8px; overflow: hidden; border: 1px solid #ddd; cursor: pointer; outline: none; }
        .thumb-preview img { width: 100%; height: 100%; object-fit: cover; display: block; }
        .zoom-overlay { position: absolute; inset: 0; background: rgba(0,0,0,.35); display: flex; align-items: center; justify-content: center; opacity: 0; transition: opacity .18s ease-in-out; }
        .thumb-preview:hover .zoom-overlay, .thumb-preview:focus .zoom-overlay, .thumb-preview:focus-visible .zoom-overlay { opacity: 1; }
        .zoom-overlay svg { width: 32px; height: 32px; filter: drop-shadow(0 2px 2px rgba(0,0,0,.25)); }

        @media (max-width: 1024px) { .filters { grid-template-columns: repeat(2, 1fr); } }
        @media (max-width: 600px) { .filters { grid-template-columns: 1fr; } }

        /* ===== Lightbox ===== */
        .lb-backdrop { position:fixed; inset:0; background:rgba(0,0,0,.7); display:none; z-index:9999; }
        .lb-dialog { position:absolute; inset:0; display:flex; align-items:center; justify-content:center; padding:24px; }
        .lb-card { background:#fff; border-radius:12px; max-width:900px; width:95%; box-shadow:0 10px 30px rgba(0,0,0,.3); overflow:hidden; }
        .lb-head { display:flex; justify-content:space-between; align-items:center; padding:12px 16px; background:#8d6e63; color:#fff; }
        .lb-body { padding:12px 16px; display:grid; gap:10px; }
        .lb-imgwrap { display:flex; align-items:center; justify-content:center; background:#f6f2ef; border:1px solid #e6ddd9; border-radius:8px; padding:10px; }
        .lb-imgwrap img { max-width:100%; max-height:60vh; border-radius:8px; }
        .lb-caption { color:#4e342e; font-size:14px; }
        .lb-nav { display:flex; justify-content:space-between; align-items:center; gap:8px; }
        .lb-btn { padding:8px 12px; border:none; border-radius:6px; cursor:pointer; background:#d7ccc8; color:#4e342e; }
        .lb-btn:hover { background:#c5b4ad; }
        .lb-close { background:#c62828; color:#fff; }
        .lb-index { color:#f5f5f5; font-size:13px; }
    </style>
</head>
<body>
<div class="container">
    <h1>Listado de Productos</h1>

    {{-- Alertas --}}
    @if ($errors->any())
        <div class="alert">
            @foreach ($errors->all() as $e) <div>{{ $e }}</div> @endforeach
        </div>
    @endif
    @if (session('ok'))
        <div class="ok">{{ session('ok') }}</div>
    @endif

    {{-- Filtros --}}
    <div class="top-bar">
        <form id="filter-form" method="GET" action="{{ route('products.index') }}" style="width:100%;">
            <div class="filters">
                {{-- Nombre --}}
                <div><label>Nombre</label>
                    <input type="text" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="Buscar por título...">
                </div>
                {{-- Autor --}}
                @php $selectedAuthor = $filters['author_ids'] ?? null; if (is_array($selectedAuthor)) { $selectedAuthor = $selectedAuthor[0] ?? null; } @endphp
                <div><label>Autor</label>
                    <select name="author_ids" id="author_select">
                        <option value="">-- Todos --</option>
                        @foreach(($authors ?? []) as $a)
                            <option value="{{ $a['id'] }}" {{ (string)$selectedAuthor === (string)$a['id'] ? 'selected' : '' }}>{{ $a['name'] }}</option>
                        @endforeach
                    </select>
                </div>
                {{-- Género --}}
                @php $selectedGenre = $filters['genre_ids'] ?? null; if (is_array($selectedGenre)) { $selectedGenre = $selectedGenre[0] ?? null; } @endphp
                <div><label>Género</label>
                    <select name="genre_ids" id="genre_select">
                        <option value="">-- Todos --</option>
                        @foreach(($genres ?? []) as $g)
                            <option value="{{ $g['id'] }}" {{ (string)$selectedGenre === (string)$g['id'] ? 'selected' : '' }}>{{ $g['name'] }}</option>
                        @endforeach
                    </select>
                </div>
                {{-- Editorial --}}
                @php $selectedPublisher = $filters['publisher_id'] ?? ''; @endphp
                <div><label>Editorial</label>
                    <select name="publisher_id" id="publisher_select">
                        <option value="">-- Todas --</option>
                        @foreach(($publishers ?? []) as $pub)
                            <option value="{{ $pub['id'] }}" {{ (string)$selectedPublisher === (string)$pub['id'] ? 'selected' : '' }}>{{ $pub['name'] }}</option>
                        @endforeach
                    </select>
                </div>
                {{-- Botones --}}
                <div class="buttons" style="grid-column: 1 / -1;">
                    <button class="btn" type="submit">Buscar</button>
                    <a class="btn btn-light" href="{{ route('products.index') }}">Limpiar</a>
                    <a class="add-button" href="{{ route('products.create') }}">+ Nuevo producto</a>
                    <a class="add-button" href="{{ route('catalogs.index') }}">Catalogo</a>
                    <a href="{{ route('dashboard.redirect') }}" class="add-button">🏠📚 Menu principal</a>
                </div>
            </div>
        </form>
    </div>

    {{-- Tabla con scroll --}}
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>ID</th><th>Imagen</th><th>Título</th><th>Editorial</th><th>Autores</th>
                    <th>Géneros</th><th>Stock</th><th>Precio</th><th>Precio proveedor</th><th>Acciones</th>
                </tr>
            </thead>
            <tbody>
            @forelse($products as $p)
                @php
                    $pprov = $p['preciodeproveedor'] ?? null;
                    $publisherOut = is_array($p['publisher'] ?? null) ? ($p['publisher']['name'] ?? '') : ($p['publisher'] ?? '');
                    $images = collect($p['images'] ?? [])->values()->all();
                    $mainIndex = collect($images)->search(fn($x) => !empty($x['is_main'])); if ($mainIndex === false) { $mainIndex = 0; }
                    $mainItem = $images[$mainIndex] ?? null; $mainUrl  = $mainItem['url'] ?? null; $mainAlt  = $mainItem['alt'] ?? 'Imagen';
                @endphp
                <tr>
                    <td>{{ $p['id'] }}</td>
                    <td>
                        <div class="thumb-preview" tabindex="0"
                             data-images='@json($images)' data-start="{{ $mainIndex }}"
                             onclick="openGalleryFromPreview(this)" onkeydown="previewKeyOpen(event,this)">
                            <img src="{{ $mainUrl ?? 'https://via.placeholder.com/96x96?text=Sin+Imagen' }}" alt="{{ $mainAlt }}"
                                 onerror="this.src='https://via.placeholder.com/96x96?text=Img'">
                            <div class="zoom-overlay"><svg viewBox="0 0 24 24" fill="#fff"><path d="M15.5 14h-.79l-.28-.27A6.471 6.471 0 0016 9.5 6.5 6.5 0 109.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 5 1.5-1.5-5-5zM10 14a4 4 0 110-8 4 4 0 010 8z"/></svg></div>
                        </div>
                    </td>
                    <td>{{ $p['title'] }}</td>
                    <td>{{ $publisherOut }}</td>
                    <td>{{ collect($p['authors'] ?? [])->pluck('name')->join(', ') }}</td>
                    <td>{{ collect($p['genres'] ?? [])->pluck('name')->join(', ') }}</td>
                    <td>{{ $p['stock'] }}</td>
                    <td>${{ number_format((float)($p['price'] ?? 0), 2) }}</td>
                    <td>@if(!is_null($pprov)) ${{ number_format((float)$pprov, 2) }} @else — @endif</td>
                    <td>
                        <a class="action-button" href="{{ route('products.edit',$p['id']) }}">Editar</a>
                        <button class="delete-icon" onclick="openDelete({{ $p['id'] }})">Eliminar</button>
                    </td>
                </tr>
            @empty
                <tr><td colspan="10">Sin datos</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Formulario de borrado --}}
<form id="delete-form" method="POST" style="display:none">@csrf @method('DELETE')</form>

{{-- Modal borrar --}}
<div id="modal" style="display:none; position:fixed; z-index:999; inset:0; background:rgba(0,0,0,.5);">
  <div style="background:#fff; margin:15% auto; padding:20px; width:400px; border-radius:8px; text-align:center;">
    <div>¿Seguro que deseas eliminar este producto?</div>
    <div style="margin-top:20px;">
      <button type="button" class="btn-light" onclick="closeModal()">Cancelar</button>
      <button type="button" class="btn" style="background:#c62828;" onclick="confirmDelete()">Eliminar</button>
    </div>
  </div>
</div>

{{-- Lightbox --}}
<div id="lb" class="lb-backdrop" onclick="backdropClose(event)">
  <div class="lb-dialog"><div class="lb-card">
    <div class="lb-head"><strong>Imágenes del producto</strong><div class="lb-index" id="lbIndex">1 / 1</div></div>
    <div class="lb-body">
      <div class="lb-imgwrap"><img id="lbImg" src="" alt=""></div>
      <div class="lb-caption" id="lbCaption"></div>
      <div class="lb-nav">
        <button class="lb-btn" onclick="prevImg()">&larr; Anterior</button>
        <div style="flex:1"></div>
        <button class="lb-btn" onclick="nextImg()">Siguiente &rarr;</button>
        <button class="lb-btn lb-close" onclick="closeGallery()">Cerrar</button>
      </div>
    </div>
  </div></div>
</div>

<script>
  let deleteId=null;
  function openDelete(id){deleteId=id;document.getElementById('modal').style.display='block';}
  function closeModal(){document.getElementById('modal').style.display='none';deleteId=null;}
  function confirmDelete(){if(!deleteId)return;const form=document.getElementById('delete-form');form.action="{{ url('/products') }}/"+deleteId;form.submit();}
  let LB={images:[],index:0};
  function openGalleryFromPreview(el){const images=JSON.parse(el.dataset.images||'[]');if(!images.length)return;const start=parseInt(el.dataset.start||'0',10)||0;LB.images=images;LB.index=Math.max(0,Math.min(start,images.length-1));renderLB();document.getElementById('lb').style.display='block';document.addEventListener('keydown',lbKeyHandler);}
  function previewKeyOpen(evt,el){if(evt.key==='Enter'||evt.key===' '){evt.preventDefault();openGalleryFromPreview(el);}}
  function closeGallery(){document.getElementById('lb').style.display='none';document.removeEventListener('keydown',lbKeyHandler);}
  function backdropClose(e){if(e.target.id==='lb')closeGallery();}
  function lbKeyHandler(e){if(e.key==='Escape')return closeGallery();if(e.key==='ArrowRight')return nextImg();if(e.key==='ArrowLeft')return prevImg();}
  function nextImg(){if(!LB.images.length)return;LB.index=(LB.index+1)%LB.images.length;renderLB();}
  function prevImg(){if(!LB.images.length)return;LB.index=(LB.index-1+LB.images.length)%LB.images.length;renderLB();}
  function renderLB(){const imgEl=document.getElementById('lbImg');const capEl=document.getElementById('lbCaption');const idxEl=document.getElementById('lbIndex');const it=LB.images[LB.index]||{};imgEl.src=it.url||'https://via.placeholder.com/800x600?text=Sin+Imagen';imgEl.alt=it.alt||'Imagen';capEl.textContent=it.alt||'Sin descripción';idxEl.textContent

<!-- resources/views/products/indexcon.blade.php -->
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8" />
<title>Catálogo de Productos — Consultores</title>
<meta name="csrf-token" content="{{ csrf_token() }}">

<style>
  :root{
    --bg:#efeae6;
    --ink:#3c2f2b;
    --muted:#6d4c41;
    --card:#ffffff;
    --brand:#8d6e63;
    --brand-2:#795548;
    --line:#e6dcd7;
    --ok:#2e7d32;
    --accent:#b08968;
  }

  *{ box-sizing: border-box }
  html,body{ margin:0; padding:0; background: radial-gradient(1200px 800px at 80% -10%, #f6f1ee 0%, var(--bg) 60%) fixed; font-family: ui-sans-serif, system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial; color: var(--ink) }

  /* Header */
  .hero{
    position: relative;
    padding: 48px 20px 24px;
    text-align: center;
  }
  .hero h1{
    margin:0;
    font-weight: 800;
    letter-spacing:.3px;
    color: var(--ink);
    font-size: clamp(26px, 3vw, 36px);
  }
  .hero p{
    margin:10px auto 0;
    max-width: 720px;
    color:#5b463f;
    opacity:.85;
  }
  .spark{
    position:absolute; inset: 0 0 auto; height: 140px; pointer-events:none;
    background:
      radial-gradient(250px 80px at 20% 60%, rgba(141,110,99,.15), transparent 60%),
      radial-gradient(260px 80px at 80% 40%, rgba(176,137,104,.18), transparent 60%);
    filter: blur(12px);
  }

  /* Contenedor general */
  .container{ max-width: 1200px; margin: 0 auto; padding: 0 20px 40px }

  /* Filtros */
  .filters-wrap{
    position: sticky; top: 0; z-index: 10;
    margin: 16px auto 24px;
    background: rgba(255,255,255,.7);
    -webkit-backdrop-filter: saturate(160%) blur(6px);
    backdrop-filter: saturate(160%) blur(6px);
    border: 1px solid var(--line);
    border-radius: 14px;
    padding: 14px;
    box-shadow: 0 10px 30px rgba(60,47,43,.06);
    animation: drop .45s ease both;
  }
  @keyframes drop{ from{ transform: translateY(-8px); opacity:0 } to{ transform: translateY(0); opacity:1 } }

  .filters{ display: grid; grid-template-columns: 2fr 1.2fr 1.2fr 1.2fr auto; gap: 10px; align-items: end }
  .filters label{ display:block; font-size: 12px; font-weight:700; color:#6a4f47; margin: 0 0 6px }
  .filters input,.filters select{
    width:100%; padding: 10px 12px; border:1px solid var(--line); border-radius:10px; background:#fff;
    transition: box-shadow .18s, transform .05s;
    outline: none;
  }
  .filters input:focus,.filters select:focus{ box-shadow: 0 0 0 3px rgba(141,110,99,.15) }
  .filters .buttons{ display:flex; gap:8px; align-items:center; justify-content:flex-end }

  .btn{ display:inline-flex; gap:8px; align-items:center; justify-content:center; border:0; cursor:pointer; font-weight:700; border-radius:10px; padding:10px 14px; background: var(--brand); color:#fff; transition: transform .05s ease, box-shadow .2s ease, background .2s }
  .btn:hover{ background: var(--brand-2); box-shadow: 0 10px 20px rgba(121,85,72,.25) }
  .btn:active{ transform: translateY(1px) }
  .btn-light{ background:#efe2dc; color:#4e342e }
  .btn-light:hover{ background:#e7d5cd }

  /* Grid de tarjetas */
  .grid{
    display: grid;
    grid-template-columns: repeat(12, 1fr);
    gap: 16px;
  }
  @media (max-width: 1100px){ .grid{ grid-template-columns: repeat(8, 1fr) } }
  @media (max-width: 720px){ .grid{ grid-template-columns: repeat(4, 1fr) } }

  .card{
    grid-column: span 3;
    background: linear-gradient(180deg, #ffffff 0%, #fff 60%, #faf7f5 100%);
    border: 1px solid var(--line);
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 8px 24px rgba(60,47,43,.05);
    transition: transform .18s ease, box-shadow .25s ease, border-color .25s ease;
    position: relative;
  }
  .card:hover{
    transform: translateY(-2px);
    box-shadow: 0 16px 40px rgba(60,47,43,.12);
    border-color:#e0d0c8;
  }
  @media (max-width: 1100px){ .card{ grid-column: span 4 } }
  @media (max-width: 720px){ .card{ grid-column: span 4 } }

  .thumb{
    position: relative; aspect-ratio: 4/3; background:#f4efec; overflow:hidden;
  }
  .thumb img{
    width:100%; height:100%; object-fit:cover; display:block; transform: scale(1.02);
    transition: transform .5s ease;
  }
  .card:hover .thumb img{ transform: scale(1.07) }
  .thumb .zoom{
    position:absolute; inset:auto 10px 10px auto; display:flex; align-items:center; gap:6px;
    background: rgba(0,0,0,.55); color:#fff; padding:6px 10px; border-radius:999px; font-size:12px;
  }
  .thumb .zoom svg{ width:16px; height:16px }

  .body{ padding: 12px 14px 14px }
  .title{
    font-weight: 800; font-size: 15px; line-height: 1.25; margin: 4px 0 6px; color:#3a2d29;
    display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;
  }
  .meta{ display:flex; flex-wrap: wrap; gap:6px; margin-bottom:8px }
  .chip{
    font-size: 11px; border:1px solid var(--line); background:#fff; color:#5c463f;
    padding:4px 8px; border-radius:999px;
  }
  .row{ display:flex; justify-content:space-between; align-items:center; gap:10px; margin-top:8px }
  .price{ font-weight:800; color:#2f2a28 }
  .stock{ font-size:12px; color:#4f3e38; background:#efe2dc; padding:4px 8px; border-radius:8px }

  .empty{
    text-align:center; padding: 40px 20px; border: 2px dashed #dccdc5; border-radius: 16px; background: #fff;
    color:#6b524a;
  }

  /* Lightbox */
  .lb-backdrop { position: fixed; inset:0; display:none; z-index: 9999; background: rgba(0,0,0,.76) }
  .lb-dialog   { position:absolute; inset:0; display:flex; align-items:center; justify-content:center; padding:24px }
  .lb-card     { width:min(100%, 980px); background:#111; border-radius:14px; overflow:hidden; box-shadow: 0 20px 60px rgba(0,0,0,.45); }
  .lb-head     { display:flex; align-items:center; justify-content:space-between; padding: 10px 14px; color:#eee; background: linear-gradient(180deg, #222, #111) }
  .lb-body     { padding: 12px 14px 16px; background: #111 }
  .lb-imgwrap  { display:flex; align-items:center; justify-content:center; background:#000; border-radius: 10px; border: 1px solid #222; padding: 8px; min-height: 60vh }
  .lb-imgwrap img{ max-width:100%; max-height:75vh; border-radius:8px }
  .lb-caption  { color:#cbb; font-size:13px; margin: 8px 2px 0 }
  .lb-nav{ display:flex; gap:8px; align-items:center; justify-content:space-between; margin-top: 12px }
  .lb-btn{ padding:10px 12px; border:1px solid #333; border-radius:10px; background:#1a1a1a; color:#eee; cursor:pointer }
  .lb-btn:hover{ background:#232323 }
  .lb-close{ background:#7a2b2b; border-color:#833; }
  .lb-index{ color:#d7ccc8; font-size:12px }

  /* Accesibilidad: enfoque */
  .focusable:focus{ outline: 3px solid rgba(141,110,99,.35); outline-offset: 2px; border-radius: 12px }

  /* ===== Modal de Información ===== */
  .info-backdrop{ position:fixed; inset:0; background:rgba(0,0,0,.6); display:none; z-index: 10000; }
  .info-dialog{ position:absolute; inset:0; display:flex; align-items:center; justify-content:center; padding:20px; }
  .info-card{
    width:min(92%, 520px);
    background:#fff; border-radius:16px; overflow:hidden; border:1px solid var(--line);
    box-shadow:0 20px 60px rgba(0,0,0,.35); animation: infoPop .18s ease-out both;
  }
  @keyframes infoPop{ from{ transform:scale(.98); opacity:.0 } to{ transform:scale(1); opacity:1 } }
  .info-head{ display:flex; align-items:center; justify-content:space-between; gap:10px; padding:12px 16px; background:var(--brand); color:#fff }
  .info-body{ padding:16px; color:var(--ink); }
  .info-row{ display:flex; align-items:center; gap:10px; padding:10px 12px; border:1px solid var(--line); border-radius:12px; background:#fff; }
  .info-row + .info-row{ margin-top:10px }
  .info-actions{ display:flex; justify-content:flex-end; gap:8px; padding:0 16px 16px; }
  .info-btn{ padding:10px 14px; border:0; border-radius:10px; cursor:pointer; font-weight:700; }
  .info-btn.close{ background:#7a2b2b; color:#fff }
  .info-btn.copy{ background:#efe2dc; color:#4e342e }
  .info-index{ font-size:12px; opacity:.85 }
</style>
</head>
<body>

<div class="hero">
  <div class="spark" aria-hidden="true"></div>
  <h1>Catálogo — Vista para Consultores</h1>
  <p>Explora el inventario con filtros dinámicos y una galería de imágenes. No hay acciones de edición en esta vista.</p>
</div>

<div class="container">
  {{-- Alertas --}}
  @if ($errors->any())
    <div class="empty" style="border-style: solid; border-color:#f7d7d7; background:#fff7f7; color:#7a2b2b">
      @foreach ($errors->all() as $e)
        <div>{{ $e }}</div>
      @endforeach
    </div>
  @endif
  @if (session('ok'))
    <div class="empty" style="border-color:#cce5cc; background:#f4fbf4; color:#205b20">{{ session('ok') }}</div>
  @endif

  {{-- Filtros --}}
  <div class="filters-wrap">
    <form id="filter-form" method="GET" action="{{ route('products.index') }}">
      <div class="filters">
        {{-- Nombre --}}
        <div>
          <label for="q">Nombre / Título</label>
          <input id="q" type="text" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="Buscar por título..." />
        </div>

        {{-- Autor --}}
        @php
          $selectedAuthor = $filters['author_ids'] ?? null;
          if (is_array($selectedAuthor)) { $selectedAuthor = $selectedAuthor[0] ?? null; }
        @endphp
        <div>
          <label for="author_select">Autor</label>
          <select name="author_ids" id="author_select">
            <option value="">— Todos —</option>
            @foreach(($authors ?? []) as $a)
              <option value="{{ $a['id'] }}" {{ (string)$selectedAuthor === (string)$a['id'] ? 'selected' : '' }}>
                {{ $a['name'] }}
              </option>
            @endforeach
          </select>
        </div>

        {{-- Género --}}
        @php
          $selectedGenre = $filters['genre_ids'] ?? null;
          if (is_array($selectedGenre)) { $selectedGenre = $selectedGenre[0] ?? null; }
        @endphp
        <div>
          <label for="genre_select">Género</label>
          <select name="genre_ids" id="genre_select">
            <option value="">— Todos —</option>
            @foreach(($genres ?? []) as $g)
              <option value="{{ $g['id'] }}" {{ (string)$selectedGenre === (string)$g['id'] ? 'selected' : '' }}>
                {{ $g['name'] }}
              </option>
            @endforeach
          </select>
        </div>

        {{-- Editorial --}}
        @php $selectedPublisher = $filters['publisher_id'] ?? ''; @endphp
        <div>
          <label for="publisher_select">Editorial</label>
          <select name="publisher_id" id="publisher_select">
            <option value="">— Todas —</option>
            @foreach(($publishers ?? []) as $pub)
              <option value="{{ $pub['id'] }}" {{ (string)$selectedPublisher === (string)$pub['id'] ? 'selected' : '' }}>
                {{ $pub['name'] }}
              </option>
            @endforeach
          </select>
        </div>

        {{-- Botones --}}
        <div class="buttons">
          <button class="btn" type="submit" title="Aplicar filtros">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="#fff" aria-hidden="true"><path d="M3 5h18v2H3zm4 6h10v2H7zm2 6h6v2H9z"/></svg>
            Buscar
          </button>
          <a class="btn btn-light" href="{{ route('products.index') }}" title="Limpiar filtros">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="#4e342e" aria-hidden="true"><path d="M3 5h18v2H3zm0 6h12v2H3zm0 6h6v2H3z"/></svg>
            Limpiar
          </a>
          <button type="button" class="btn btn-light" title="Ver información" onclick="openInfoModal()">
            +Informacion
          </button>
        </div>
      </div>
    </form>
  </div>

  {{-- Grid de tarjetas --}}
  @php
    $items = collect($products ?? []);
  @endphp

  @if($items->isEmpty())
    <div class="empty">
      No se encontraron productos con los filtros seleccionados.
    </div>
  @else
    <div class="grid">
      @foreach($items as $p)
        @php
          $publisherOut = is_array($p['publisher'] ?? null) ? ($p['publisher']['name'] ?? '') : ($p['publisher'] ?? '');
          $authorsOut   = collect($p['authors'] ?? [])->pluck('name')->filter()->values()->all();
          $genresOut    = collect($p['genres'] ?? [])->pluck('name')->filter()->values()->all();

          $images   = collect($p['images'] ?? [])->values()->all();
          $mainIdx  = collect($images)->search(fn($x)=>!empty($x['is_main']));
          if($mainIdx === false){ $mainIdx = 0; }
          $main     = $images[$mainIdx] ?? null;
          $mainUrl  = $main['url'] ?? null;
          $mainAlt  = $main['alt'] ?? 'Imagen';
        @endphp

        <article class="card">
          <button class="thumb focusable" onclick="openGallery(@json($images), {{ (int)$mainIdx }})" title="Ver imágenes" aria-label="Abrir galería">
            <img src="{{ $mainUrl ?: 'https://via.placeholder.com/600x450?text=Sin+Imagen' }}"
                 alt="{{ $mainAlt }}"
                 onerror="this.src='https://via.placeholder.com/600x450?text=Imagen'">
            <div class="zoom">
              <svg viewBox="0 0 24 24" fill="#fff" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path d="M15.5 14h-.79l-.28-.27A6.47 6.47 0 0016 9.5 6.5 6.5 0 109.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 5 1.5-1.5-5-5zM10 14a4 4 0 110-8 4 4 0 010 8z"/></svg>
              Galería
            </div>
          </button>

          <div class="body">
            <div class="title" title="{{ $p['title'] }}">{{ $p['title'] }}</div>

            <div class="meta">
              @if($publisherOut)<span class="chip">Editorial: {{ $publisherOut }}</span>@endif
              @if(!empty($authorsOut))<span class="chip">Autor: {{ implode(', ', $authorsOut) }}</span>@endif
              @if(!empty($genresOut))<span class="chip">Género: {{ implode(', ', $genresOut) }}</span>@endif
              <span class="chip">ID: {{ $p['id'] }}</span>
            </div>

            <div class="row">
              <div class="price">${{ number_format((float)($p['price'] ?? 0), 2) }}</div>
              <div class="stock" title="Stock disponible">Stock: {{ $p['stock'] ?? 0 }}</div>
            </div>
          </div>
        </article>
      @endforeach
    </div>
  @endif
</div>

<!-- Lightbox -->
<div id="lb" class="lb-backdrop" onclick="lbBackdropClose(event)" aria-modal="true" role="dialog" aria-labelledby="lbIndex">
  <div class="lb-dialog">
    <div class="lb-card">
      <div class="lb-head">
        <strong>Imágenes del producto</strong>
        <div class="lb-index" id="lbIndex">1 / 1</div>
      </div>
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
    </div>
  </div>
</div>

<!-- Modal de Información -->
<div id="infoModal" class="info-backdrop" onclick="infoBackdropClose(event)" aria-modal="true" role="dialog" aria-labelledby="infoTitle">
  <div class="info-dialog">
    <div class="info-card">
      <div class="info-head">
        <strong id="infoTitle">Información de contacto</strong>
        <div class="info-index">Soporte y consultas</div>
      </div>
      <div class="info-body">
        <div class="info-row">
          <!-- Ícono WhatsApp -->
          <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M20.52 3.48A11.91 11.91 0 0012.06 0C5.66 0 .48 5.17.48 11.57c0 2.04.53 4.03 1.54 5.79L0 24l6.86-1.97a11.5 11.5 0 005.2 1.26h.01c6.39 0 11.57-5.18 11.57-11.57a11.5 11.5 0 00-3.12-8.24zM12.06 21.2h-.01a9.64 9.64 0 01-4.91-1.35l-.35-.21-4.07 1.17 1.17-3.97-.23-.41a9.65 9.65 0 01-1.46-5.05c0-5.33 4.34-9.67 9.67-9.67 2.58 0 5 1 6.83 2.82a9.61 9.61 0 012.84 6.86c0 5.33-4.34 9.67-9.68 9.67zm5.56-7.21c-.3-.15-1.76-.86-2.03-.95-.27-.1-.46-.15-.66.15-.19.3-.76.95-.94 1.14-.17.2-.35.22-.65.08-.3-.15-1.27-.47-2.42-1.5a9.1 9.1 0 01-1.69-2.1c-.18-.3 0-.46.13-.61.13-.13.3-.35.45-.53.15-.17.2-.3.3-.5.1-.2.05-.38-.03-.53-.08-.15-.66-1.6-.9-2.2-.24-.58-.48-.5-.66-.5h-.56c-.2 0-.53.08-.8.38-.27.3-1.05 1.02-1.05 2.5s1.08 2.9 1.23 3.1c.15.2 2.13 3.25 5.15 4.55.72.31 1.29.5 1.73.64.72.23 1.38.2 1.9.12.58-.09 1.76-.72 2.01-1.42.25-.7.25-1.29.17-1.42-.08-.13-.27-.2-.56-.35z"/></svg>
          <div>
            <div style="font-weight:700">WhatsApp</div>
            <div>7711790029</div>
          </div>
        </div>
        <div class="info-row">
          <!-- Ícono Correo -->
          <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 13L2 6.76V18a2 2 0 002 2h16a2 2 0 002-2V6.76L12 13z"/><path d="M12 11l10-6H2l10 6z"/></svg>
          <div>
            <div style="font-weight:700">Correo</div>
            <div>arturo@gmail.com</div>
          </div>
        </div>
      </div>
      <div class="info-actions">
        <button class="info-btn copy" type="button" onclick="copyContact()">Copiar datos</button>
        <button class="info-btn close" type="button" onclick="closeInfoModal()">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<script>
  // ===== Lightbox state =====
  const LB = { images: [], index: 0 };

  function openGallery(images, startIndex){
    const list = Array.isArray(images) ? images : [];
    if(!list.length) return;
    LB.images = list;
    LB.index  = Math.max(0, Math.min(parseInt(startIndex || 0,10), list.length-1));
    renderLB();
    const lb = document.getElementById('lb');
    lb.style.display = 'block';
    document.addEventListener('keydown', lbKeys);
  }
  function closeGallery(){
    document.getElementById('lb').style.display = 'none';
    document.removeEventListener('keydown', lbKeys);
  }
  function lbBackdropClose(e){ if(e.target.id === 'lb') closeGallery(); }
  function lbKeys(e){
    if(e.key === 'Escape') return closeGallery();
    if(e.key === 'ArrowRight') return nextImg();
    if(e.key === 'ArrowLeft')  return prevImg();
  }
  function nextImg(){
    if(!LB.images.length) return;
    LB.index = (LB.index + 1) % LB.images.length;
    renderLB();
  }
  function prevImg(){
    if(!LB.images.length) return;
    LB.index = (LB.index - 1 + LB.images.length) % LB.images.length;
    renderLB();
  }
  function renderLB(){
    const imgEl = document.getElementById('lbImg');
    const capEl = document.getElementById('lbCaption');
    const idxEl = document.getElementById('lbIndex');
    const it    = LB.images[LB.index] || {};
    imgEl.src   = it.url || 'https://via.placeholder.com/1000x700?text=Sin+Imagen';
    imgEl.alt   = it.alt || 'Imagen';
    capEl.textContent = it.alt || 'Sin descripción';
    idxEl.textContent = (LB.index + 1) + ' / ' + LB.images.length;
  }

  // Accesibilidad: permitir Enter en chips/focos futuros si se requiere
  document.querySelectorAll('.focusable').forEach(el=>{
    el.addEventListener('keydown', (e)=>{
      if(e.key === 'Enter' || e.key === ' ') { e.preventDefault(); el.click(); }
    });
  });

  // ===== Modal de Información =====
  function openInfoModal(){
    const m = document.getElementById('infoModal');
    m.style.display = 'block';
    document.addEventListener('keydown', infoKeys);
  }
  function closeInfoModal(){
    const m = document.getElementById('infoModal');
    m.style.display = 'none';
    document.removeEventListener('keydown', infoKeys);
  }
  function infoBackdropClose(e){ if(e.target.id === 'infoModal') closeInfoModal(); }
  function infoKeys(e){
    if(e.key === 'Escape') return closeInfoModal();
  }
  function copyContact(){
    const text = `WhatsApp: 7711790029\nCorreo: arturo@gmail.com`;
    if (navigator.clipboard && navigator.clipboard.writeText) {
      navigator.clipboard.writeText(text).then(()=> alert('Información copiada al portapapeles'))
      .catch(fallbackCopy);
    } else {
      fallbackCopy();
    }
    function fallbackCopy() {
      const ta = document.createElement('textarea');
      ta.value = text; document.body.appendChild(ta);
      ta.select(); document.execCommand('copy'); document.body.removeChild(ta);
      alert('Información copiada al portapapeles');
    }
  }
</script>

</body>
</html>

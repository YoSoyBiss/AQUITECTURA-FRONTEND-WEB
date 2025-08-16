<!-- resources/views/products/consultants-catalog.blade.php -->
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <title>Catálogo — Añadir al carrito</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <style>
    :root{
      --bg:#e0cdba;
      --card:#ffffff;
      --ink:#2e2a27;
      --muted:#6b7280;
      --ring:#e5e7eb;
      --brand:#5a3e36;
      --brand-600:#4d342d;
      --accent:#2ea69a;
      --accent-600:#23867b;
      --warn:#f59e0b;
      --ok:#10b981;
      --danger:#c0392b;
    }
    *{box-sizing:border-box}
    html,body{margin:0}
    body{font-family: ui-sans-serif, system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial; background:var(--bg); color:var(--ink)}
    .shell{max-width:1200px; margin:40px auto; padding:0 20px}
    .header{display:flex; align-items:flex-start; justify-content:space-between; gap:16px; margin-bottom:22px}
    .title{margin:0; font-size:28px; color:var(--brand)}
    .sub{margin:6px 0 0; color:var(--muted)}
    .filters{display:flex; flex-wrap:wrap; gap:10px; align-items:center}
    .search{flex:1; min-width:200px}
    input[type="text"], select{
      width:100%; padding:10px 12px; border:1px solid var(--ring); border-radius:12px; outline:none; background:#fff;
    }
    .btn{appearance:none; border:none; border-radius:12px; padding:10px 14px; font-weight:700; cursor:pointer}
    .btn-primary{background:var(--accent); color:#fff}
    .btn-primary:hover{background:var(--accent-600)}
    .btn-ghost{border:1px solid var(--ring); background:#fff}
    .btn-dark{background:var(--brand); color:#fff}
    .btn-dark:hover{background:var(--brand-600)}
    .card{background:var(--card); border:1px solid var(--ring); border-radius:16px; box-shadow:0 10px 26px rgba(0,0,0,.06)}
    .toolbar{display:flex; gap:12px; justify-content:space-between; align-items:center; padding:14px 16px}
    .grid{display:grid; gap:16px; grid-template-columns: repeat(4, 1fr)}
    @media (max-width:1050px){.grid{grid-template-columns: repeat(3, 1fr)}}
    @media (max-width:800px){.grid{grid-template-columns: repeat(2, 1fr)}}
    @media (max-width:560px){.grid{grid-template-columns: 1fr}}

    /* Product Card */
    .p-card{display:flex; flex-direction:column; overflow:hidden}
    .p-media{position:relative; aspect-ratio:4/3; background:#f7f7f7; overflow:hidden}
    .p-media img{width:100%; height:100%; object-fit:cover; display:block; transition:transform .35s ease}
    .p-media:hover img{transform:scale(1.03)}
    .thumbs{position:absolute; right:8px; bottom:8px; display:flex; gap:6px; background:rgba(255,255,255,.8); padding:6px; border-radius:999px; border:1px solid var(--ring)}
    .thumbs img{width:28px; height:28px; object-fit:cover; border-radius:6px; border:1px solid var(--ring); cursor:pointer; opacity:.8}
    .thumbs img.active{outline:2px solid var(--accent); opacity:1}
    .p-body{padding:14px}
    .p-title{margin:0; font-size:16px}
    .p-meta{color:var(--muted); font-size:12px; margin:6px 0 10px}
    .p-price{font-weight:800; font-size:18px}
    .p-stock{font-size:12px; color:var(--muted)}
    .p-actions{display:flex; gap:8px; align-items:center; margin-top:12px}
    .qty{display:flex; align-items:center; gap:8px}
    .qty button{width:34px; height:34px; border-radius:10px; border:1px solid var(--ring); background:#fff; font-weight:800; cursor:pointer}
    .qty input{width:60px; text-align:center; padding:8px; border:1px solid var(--ring); border-radius:10px}

    /* Badges */
    .badge{display:inline-flex; gap:6px; align-items:center; padding:4px 10px; border-radius:999px; font-size:12px; background:#eef2ff; color:#1f2937; border:1px solid var(--ring)}
    .tags{display:flex; flex-wrap:wrap; gap:6px; margin-top:8px}

    /* Modal Quick View */
    .modal{position:fixed; inset:0; backdrop-filter: blur(2px); display:none; align-items:center; justify-content:center; padding:20px; z-index:50}
    .modal.open{display:flex}
    .modal-card{width:min(980px, 100%); max-height:90vh; overflow:auto}
    .modal-body{display:grid; gap:20px; grid-template-columns: 1.2fr 1fr; padding:16px}
    @media (max-width:900px){.modal-body{grid-template-columns:1fr}}
    .close-x{position:absolute; right:16px; top:12px; background:#fff; border:1px solid var(--ring); border-radius:999px; width:36px; height:36px; display:grid; place-items:center; cursor:pointer}

    /* Toast */
    .toast{position:fixed; right:16px; bottom:16px; background:#111; color:#fff; padding:12px 14px; border-radius:12px; box-shadow:0 10px 30px rgba(0,0,0,.2); opacity:0; transform:translateY(16px); transition:all .25s ease; z-index:60}
    .toast.show{opacity:1; transform:translateY(0)}
    .toast.ok{background:var(--brand)}
    .toast.warn{background:var(--warn)}
    .toast.err{background:var(--danger)}

    .muted{color:var(--muted)}
    .link{color:var(--brand); text-decoration:none}
    .link:hover{text-decoration:underline}
  </style>
</head>
<body>
<div class="shell">
  <div class="header">
    <div>
      <h1 class="title">Catálogo para consultantes</h1>
      <p class="sub">Explora, elige cantidades y añade al carrito con un clic. Visual optimizada y veloz.</p>
    </div>
    <a href="{{ route('dashboard.redirect') }}" class="btn btn-dark">🏠 Volver</a>
  </div>

  <div class="card">
    <div class="toolbar">
      <div class="filters" style="flex:1">
        <div class="search">
          <input type="text" id="q" placeholder="Buscar por título, autor, SKU, etiqueta…">
        </div>
        <div style="width:180px">
          <select id="genre">
            <option value="">Género (todos)</option>
            @php
              // Si pasas $genres desde el controlador, se listan; si no, queda vacío
              $genres = $genres ?? [];
            @endphp
            @foreach($genres as $g)
              <option value="{{ is_array($g)?($g['name'] ?? $g['id'] ?? ''): $g }}">{{ is_array($g)?($g['name'] ?? $g): $g }}</option>
            @endforeach
          </select>
        </div>
        <div style="width:180px">
          <select id="stock">
            <option value="">Stock</option>
            <option value="in">Disponible</option>
            <option value="out">Agotado</option>
          </select>
        </div>
        <button class="btn btn-ghost" id="clear">Limpiar</button>
      </div>
      @if (Route::has('cart.index'))
        <a class="btn btn-primary" href="{{ route('cart.index') }}">🛒 Ver carrito</a>
      @endif
    </div>

    <div style="padding:16px">
      <div class="grid" id="grid">
        @forelse (($products ?? []) as $p)
          @php
            $pid   = $p['id']   ?? $p['product_id'] ?? $p['_id']['$oid'] ?? $p['_id'] ?? null;
            $title = $p['title']?? $p['name'] ?? 'Producto';
            $price = (float)($p['price'] ?? 0);
            $stock = (int)($p['stock'] ?? 0);
            $sku   = $p['sku']   ?? null;
            $author= $p['author']?? null;
            $genre = $p['genre'] ?? null;
            $tags  = $p['tags']  ?? [];
            // Normaliza imágenes: [['url'=>...], ...]
            $imagesRaw = $p['images'] ?? [];
            if (is_array($imagesRaw)) {
                $images = array_values(array_filter($imagesRaw, fn($img) =>
                    is_array($img) && !empty($img['url'] ?? null)
                ));
            } else { $images = []; }
            $cover = $images[0]['url'] ?? 'https://placehold.co/800x600?text=Sin+Imagen';
          @endphp

          <div class="p-card card"
               data-title="{{ strtolower($title) }}"
               data-genre="{{ strtolower(is_array($genre)?($genre['name'] ?? ''):$genre) }}"
               data-stock="{{ $stock > 0 ? 'in' : 'out' }}"
               data-sku="{{ strtolower($sku ?? '') }}"
               data-author="{{ strtolower(is_array($author)?($author['name'] ?? ''):$author) }}"
          >
            <div class="p-media">
              <img src="{{ $cover }}" alt="{{ $title }}" loading="lazy" />
              @if (count($images) > 1)
                <div class="thumbs">
                  @foreach ($images as $idx => $img)
                    <img
                      src="{{ $img['url'] }}"
                      alt="thumb"
                      class="{{ $idx===0 ? 'active':'' }}"
                      data-role="thumb">
                  @endforeach
                </div>
              @endif
            </div>
            <div class="p-body">
              <h3 class="p-title">{{ $title }}</h3>
              <div class="p-meta">
                @if($author) Autor: <strong>{{ is_array($author)?($author['name'] ?? '—'):$author }}</strong> · @endif
                @if($genre) Género: <strong>{{ is_array($genre)?($genre['name'] ?? '—'):$genre }}</strong>@endif
              </div>

              <div style="display:flex; align-items:baseline; gap:10px; justify-content:space-between">
                <div>
                  <div class="p-price">{{ '$'.number_format($price,2) }}</div>
                  <div class="p-stock">{{ $stock>0 ? 'Disponible' : 'Agotado' }} · SKU: {{ $sku ?? '—' }}</div>
                </div>
                @if(!empty($tags))
                  <div class="tags">
                    @foreach((array)$tags as $t)
                      <span class="badge">#{{ is_array($t)?($t['name'] ?? $t['id'] ?? 'tag'): $t }}</span>
                    @endforeach
                  </div>
                @endif
              </div>

              <div class="p-actions">
                <div class="qty" data-role="qty">
                  <button data-role="minus">−</button>
                  <input type="number" min="1" value="1" inputmode="numeric" />
                  <button data-role="plus">+</button>
                </div>

                <button class="btn btn-primary"
                        data-role="add"
                        data-id="{{ $pid }}"
                        data-title="{{ $title }}"
                        data-price="{{ $price }}"
                        data-endpoint="{{ Route::has('cart.add') ? route('cart.add') : url('/cart/add') }}"
                        {{ $stock>0 ? '' : 'disabled' }}>
                  🛒 Agregar
                </button>

                <button class="btn btn-ghost" data-role="quick" data-id="{{ $pid }}">👁️ Vista rápida</button>
              </div>
            </div>
          </div>
        @empty
          <div class="card" style="padding:16px">No hay productos por mostrar.</div>
        @endforelse
      </div>
    </div>
  </div>
</div>

<!-- Modal Quick View -->
<div class="modal" id="modal">
  <div class="modal-card card">
    <div style="position:relative">
      <button class="close-x" id="closeModal">✕</button>
      <div class="toolbar" style="border-bottom:1px solid var(--ring)">
        <div>
          <div id="mTitle" style="font-weight:800"></div>
          <div id="mMeta" class="muted" style="font-size:12px"></div>
        </div>
        <div id="mPrice" class="badge"></div>
      </div>

      <div class="modal-body">
        <div>
          <div class="p-media" id="mMedia" style="border-radius:12px; overflow:hidden"></div>
          <div id="mThumbs" class="thumbs" style="position:static; margin-top:8px; background:transparent; border:none; padding:0"></div>
        </div>
        <div>
          <div id="mDesc" class="muted" style="line-height:1.5"></div>

          <div style="margin-top:12px; display:flex; gap:8px; align-items:center">
            <div class="qty" id="mQty">
              <button data-role="minus">−</button>
              <input type="number" min="1" value="1" inputmode="numeric" />
              <button data-role="plus">+</button>
            </div>
            <button class="btn btn-primary" id="mAdd">🛒 Agregar</button>
          </div>

          <div id="mStock" class="p-stock" style="margin-top:8px"></div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Toast -->
<div class="toast" id="toast">Listo</div>

<script>
(function(){
  const $$ = (sel, root=document) => Array.from(root.querySelectorAll(sel));
  const $  = (sel, root=document) => root.querySelector(sel);

  // Debounce helper
  const debounce = (fn, ms=250) => {
    let t; return (...args)=>{ clearTimeout(t); t=setTimeout(()=>fn(...args), ms); };
  };

  // Thumbnail -> swap main image
  $$('.p-card').forEach(card=>{
    const media = $('.p-media img', card);
    $$('.thumbs img', card).forEach((th)=>{
      th.addEventListener('click', ()=>{
        $$('.thumbs img', card).forEach(i=>i.classList.remove('active'));
        th.classList.add('active');
        media.src = th.src;
      });
    });
  });

  // Qty steppers
  function bindQty(root){
    $$('.qty', root).forEach(box=>{
      const input = $('input', box);
      $('[data-role="minus"]', box)?.addEventListener('click', ()=>{
        const v = Math.max(1, parseInt(input.value||'1')-1);
        input.value = v;
      });
      $('[data-role="plus"]', box)?.addEventListener('click', ()=>{
        const v = Math.max(1, parseInt(input.value||'1')+1);
        input.value = v;
      });
    });
  }
  bindQty(document);

  // Filters
  const grid  = $('#grid');
  const q     = $('#q');
  const genre = $('#genre');
  const stock = $('#stock');
  const clear = $('#clear');

  const applyFilters = ()=>{
    const qv = (q.value||'').trim().toLowerCase();
    const gv = (genre.value||'').toLowerCase();
    const sv = (stock.value||'').toLowerCase();

    $$('.p-card', grid).forEach(card=>{
      const matchQ = !qv || (card.dataset.title?.includes(qv) || card.dataset.sku?.includes(qv) || card.dataset.author?.includes(qv));
      const matchG = !gv || (card.dataset.genre===gv);
      const matchS = !sv || (card.dataset.stock===sv);
      card.style.display = (matchQ && matchG && matchS) ? '' : 'none';
    });
  };
  q.addEventListener('input', debounce(applyFilters, 180));
  genre.addEventListener('change', applyFilters);
  stock.addEventListener('change', applyFilters);
  clear.addEventListener('click', ()=>{
    q.value=''; genre.value=''; stock.value=''; applyFilters();
  });

  // Toast
  const toast = $('#toast');
  function showToast(msg, kind='ok'){
    toast.textContent = msg;
    toast.className = 'toast show ' + (kind==='ok'?'ok':kind);
    setTimeout(()=> toast.classList.remove('show'), 2200);
  }

  // Add to cart
  function getCsrf(){ return document.querySelector('meta[name="csrf-token"]')?.content || ''; }

  async function addToCart(endpoint, payload){
    const res = await fetch(endpoint, {
      method:'POST',
      headers: {
        'Content-Type':'application/json',
        'X-CSRF-TOKEN': getCsrf(),
        'Accept': 'application/json'
      },
      body: JSON.stringify(payload)
    });
    if(!res.ok) throw new Error('HTTP '+res.status);
    return await res.json().catch(()=> ({}));
  }

  // Button handlers on cards
  $$('.p-card').forEach(card=>{
    const addBtn = $('[data-role="add"]', card);
    const qtyBox = $('[data-role="qty"] input', card);

    addBtn?.addEventListener('click', async ()=>{
      const endpoint = addBtn.dataset.endpoint;
      const pid = addBtn.dataset.id;
      const qty = Math.max(1, parseInt(qtyBox?.value || '1'));
      try{
        await addToCart(endpoint, { product_id: pid, quantity: qty });
        showToast('Añadido al carrito ✔');
      }catch(e){
        showToast('No se pudo añadir', 'err');
        console.error(e);
      }
    });
  });

  // Quick View modal
  const modal = $('#modal');
  const closeModal = $('#closeModal');
  const mTitle = $('#mTitle');
  const mMeta  = $('#mMeta');
  const mPrice = $('#mPrice');
  const mMedia = $('#mMedia');
  const mThumbs= $('#mThumbs');
  const mDesc  = $('#mDesc');
  const mQty   = $('#mQty input');
  const mAdd   = $('#mAdd');
  const mStock = $('#mStock');

  let currentProduct = null;

  $$('.p-card .btn[data-role="quick"]').forEach(btn=>{
    btn.addEventListener('click', ()=>{
      const card = btn.closest('.p-card');
      if(!card) return;

      // Build product object from card DOM
      const title = $('.p-title', card)?.textContent?.trim() || 'Producto';
      const price = parseFloat($('[data-role="add"]', card)?.dataset.price || '0');
      const pid   = $('[data-role="add"]', card)?.dataset.id;
      const author= card.dataset.author || '';
      const genre = card.dataset.genre || '';
      const stock = card.dataset.stock === 'in' ? 'Disponible' : 'Agotado';

      const mainImg = $('.p-media img', card)?.src;
      const thumbs  = $$('.thumbs img', card).map(i=>i.src);

      currentProduct = {pid, title, price, author, genre, stock, mainImg, thumbs};

      // Fill modal
      mTitle.textContent = title;
      mMeta.textContent  = (author?`Autor: ${author}`:'') + (author&&genre?' · ':'') + (genre?`Género: ${genre}`:'');
      mPrice.textContent = 'Precio: $' + (price.toFixed(2));
      mStock.textContent = stock;

      mMedia.innerHTML = '';
      const img = document.createElement('img');
      img.src = mainImg; img.alt = title; img.loading='lazy'; img.style.width='100%'; img.style.height='100%'; img.style.objectFit='cover';
      mMedia.appendChild(img);

      mThumbs.innerHTML = '';
      const allThumbs = thumbs.length? thumbs : [mainImg];
      allThumbs.forEach((src, i)=>{
        const t = document.createElement('img');
        t.src = src; t.alt = 'thumb'; t.style.width='52px'; t.style.height='52px'; t.style.objectFit='cover'; t.style.borderRadius='8px'; t.style.border='1px solid var(--ring)'; t.style.cursor='pointer';
        if(i===0) t.style.outline='2px solid var(--accent)';
        t.addEventListener('click', ()=>{
          $$('#mThumbs img').forEach(k=>k.style.outline='none');
          t.style.outline='2px solid var(--accent)';
          $('#mMedia img').src = src;
        });
        mThumbs.appendChild(t);
      });

      // (Opcional) descripción: si la tienes en dataset puedes inyectarla aquí.
      mDesc.textContent = $('.p-stock', card)?.textContent || '';

      mQty.value = 1;
      modal.classList.add('open');
    });
  });

  closeModal.addEventListener('click', ()=> modal.classList.remove('open'));
  modal.addEventListener('click', (e)=>{ if(e.target===modal) modal.classList.remove('open'); });
  bindQty($('#modal'));

  mAdd.addEventListener('click', async ()=>{
    if(!currentProduct) return;
    const endpoint = $('[data-endpoint]', document)?.dataset?.endpoint || "{{ Route::has('cart.add') ? route('cart.add') : url('/cart/add') }}";
    const qty = Math.max(1, parseInt(mQty.value||'1'));
    try{
      await addToCart(endpoint, { product_id: currentProduct.pid, quantity: qty });
      showToast('Añadido al carrito ✔');
      modal.classList.remove('open');
    }catch(e){
      showToast('No se pudo añadir', 'err');
      console.error(e);
    }
  });

})();
</script>
</body>
</html>

<!-- resources/views/products/edit.blade.php -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar producto</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4e3d7; padding: 40px; }
        h1 { text-align: center; color: #5a3e36; margin-bottom: 10px; }
        .container { max-width: 900px; margin: auto; background: #fff; padding: 30px; border-radius: 10px; box-shadow: 0 0 10px rgba(90,62,54,.2); }
        .alert { background:#ffebee; border:1px solid #ffcdd2; color:#c62828; padding:10px; border-radius:6px; margin-bottom:15px; }
        .ok { background:#e8f5e9; border:1px solid #c8e6c9; color:#2e7d32; padding:10px; border-radius:6px; margin-bottom:15px; }
        .form-grid { display:grid; grid-template-columns:1fr 1fr; gap:14px; }
        .full { grid-column:1 / -1; }
        label { display:block; color:#4e342e; font-weight:bold; margin-bottom:6px; }
        input[type=text], input[type=number], input[type=url], select, textarea {
            width:100%; padding:9px; border:1px solid #d7ccc8; border-radius:6px; background:#fff;
        }
        .card { border:1px solid #e0d6d2; border-radius:8px; background:#f8f5f3; padding:14px; }
        .btn { display:inline-block; padding:10px 16px; background:#8d6e63; color:#fff; text-decoration:none; border-radius:6px; font-weight:bold; border:none; cursor:pointer; }
        .btn:hover { background:#795548; }
        .btn-light { background:#d7ccc8; color:#4e342e; }
        .btn-light:hover { background:#c5b4ad; }
        .btn-danger { background:#c62828; color:#fff; }
        .image-row { display:grid; grid-template-columns:2.2fr 1.5fr .9fr .5fr .4fr; gap:10px; margin-bottom:10px; align-items:center; }
        .muted { color:#6d4c41; font-size:12px; }
        .thumb { width:60px; height:60px; object-fit:cover; border:1px solid #ddd; border-radius:6px; background:#fff; }
        @media (max-width:800px){ .form-grid{grid-template-columns:1fr;} .image-row{grid-template-columns:1fr;} }
    </style>
</head>
<body>
<div class="container">
    <h1>Editar producto</h1>

    @if ($errors->any())
        <div class="alert">
            @foreach ($errors->all() as $e)
                <div>{{ $e }}</div>
            @endforeach
        </div>
    @endif
    @if (session('ok'))
        <div class="ok">{{ session('ok') }}</div>
    @endif

    <form method="POST" action="{{ route('products.update', $product['id']) }}">
        @csrf
        @method('PUT')

        <div class="form-grid">
            {{-- Datos del producto --}}
            <div class="full">
                <label for="title">Título</label>
                <input type="text" id="title" name="title" value="{{ old('title', $product['title'] ?? '') }}" required>
            </div>

            <div>
                <label for="publisher_id">Editorial</label>
                <select id="publisher_id" name="publisher_id" required>
                    <option value="">-- Selecciona --</option>
                    @foreach(($publishers ?? []) as $pub)
                        @php
                            $pid = is_array($pub) ? ($pub['id'] ?? null) : ($pub->id ?? null);
                            $pname = is_array($pub) ? ($pub['name'] ?? '') : ($pub->name ?? '');
                        @endphp
                        @if($pid)
                        <option value="{{ $pid }}" {{ (string)old('publisher_id', $product['publisher_id'] ?? '') === (string)$pid ? 'selected' : '' }}>
                            {{ $pname }}
                        </option>
                        @endif
                    @endforeach
                </select>
            </div>

            <div>
                <label for="stock">Stock</label>
                <input type="number" id="stock" name="stock" min="0" value="{{ old('stock', $product['stock'] ?? 0) }}" required>
            </div>

            <div>
                <label for="price">Precio de venta ($)</label>
                <input type="number" step="0.01" min="0" id="price" name="price" value="{{ old('price', $product['price'] ?? 0) }}" required>
            </div>

            <div>
                <label for="preciodeproveedor">Precio de proveedor ($)</label>
                <input type="number" step="0.01" min="0" id="preciodeproveedor" name="preciodeproveedor" value="{{ old('preciodeproveedor', $product['preciodeproveedor'] ?? '') }}" placeholder="0.00">
                <div class="muted">Opcional. Se guarda como relación 1–1.</div>
            </div>

            <div>
                <label for="author_ids">Autores</label>
                @php
                    $selectedAuthors = collect(old('author_ids', collect($product['authors'] ?? [])->pluck('id')->all()));
                @endphp
                <select id="author_ids" name="author_ids[]" multiple>
                    @foreach(($authors ?? []) as $a)
                        @php
                            $aid = is_array($a) ? ($a['id'] ?? null) : ($a->id ?? null);
                            $aname = is_array($a) ? ($a['name'] ?? '') : ($a->name ?? '');
                        @endphp
                        @if($aid)
                        <option value="{{ $aid }}" {{ $selectedAuthors->contains($aid) ? 'selected' : '' }}>
                            {{ $aname }}
                        </option>
                        @endif
                    @endforeach
                </select>
                <div class="muted">Mantén Ctrl/⌘ para seleccionar múltiples.</div>
            </div>

            <div>
                <label for="genre_ids">Géneros</label>
                @php
                    $selectedGenres = collect(old('genre_ids', collect($product['genres'] ?? [])->pluck('id')->all()));
                @endphp
                <select id="genre_ids" name="genre_ids[]" multiple>
                    @foreach(($genres ?? []) as $g)
                        @php
                            $gid = is_array($g) ? ($g['id'] ?? null) : ($g->id ?? null);
                            $gname = is_array($g) ? ($g['name'] ?? '') : ($g->name ?? '');
                        @endphp
                        @if($gid)
                        <option value="{{ $gid }}" {{ $selectedGenres->contains($gid) ? 'selected' : '' }}>
                            {{ $gname }}
                        </option>
                        @endif
                    @endforeach
                </select>
                <div class="muted">Mantén Ctrl/⌘ para seleccionar múltiples.</div>
            </div>

            {{-- Imágenes --}}
            <div class="full card">
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px;">
                    <label style="margin:0;">Imágenes</label>
                    <button class="btn btn-light" type="button" onclick="addImageRow()">+ Agregar imagen</button>
                </div>

                <div id="images-container">
                    <!-- Se llena desde JS con imágenes viejas o del producto -->
                </div>

                <div class="muted">Consejo: solo una “Principal”. Al cambiarla, las demás se marcarán como “Secundaria”.</div>
            </div>

            <div class="full" style="display:flex; gap:10px; justify-content:flex-end; margin-top:8px;">
                <a class="btn btn-light" href="{{ route('products.index') }}">Cancelar</a>
                <button class="btn" type="submit">Guardar cambios</button>
            </div>
        </div>
    </form>
</div>

<script>
    // ====== IMÁGENES ======
    let imgIndex = 0;

    function addImageRow(from = null) {
        const container = document.getElementById('images-container');
        const i = imgIndex++;
        const url = from?.url ?? '';
        const alt = from?.alt ?? '';
        const isMain = Number(from?.is_main ?? 0);

        const row = document.createElement('div');
        row.className = 'image-row';
        row.innerHTML = `
            <input type="url" name="images[${i}][url]" placeholder="URL de la imagen" value="${escapeHtml(url)}" oninput="preview(this)">
            <input type="text" name="images[${i}][alt]" placeholder="Texto alternativo" value="${escapeHtml(alt)}">
            <select name="images[${i}][is_main]" onchange="ensureSingleMain(this)">
                <option value="0" ${isMain ? '' : 'selected'}>Secundaria</option>
                <option value="1" ${isMain ? 'selected' : ''}>Principal</option>
            </select>
            <img class="thumb" src="${url || 'https://via.placeholder.com/60?text=Img'}" alt="preview" onerror="this.src='https://via.placeholder.com/60?text=Img'">
            <button type="button" class="btn-light" onclick="removeRow(this)">×</button>
        `;
        container.appendChild(row);
    }

    function removeRow(btn) {
        const row = btn.closest('.image-row');
        if (row) row.remove();
    }

    function preview(input) {
        const row = input.closest('.image-row');
        const img = row.querySelector('img.thumb');
        img.src = input.value || 'https://via.placeholder.com/60?text=Img';
    }

    // Asegurar solo una "Principal"
    function ensureSingleMain(sel) {
        if (sel.value !== '1') return;
        document.querySelectorAll('#images-container select[name$="[is_main]"]').forEach(s => {
            if (s !== sel) s.value = '0';
        });
    }

    function escapeHtml(str) {
        return String(str ?? '').replaceAll('&','&amp;').replaceAll('<','&lt;').replaceAll('>','&gt;').replaceAll('"','&quot;').replaceAll("'",'&#039;');
    }

    // Cargar imágenes iniciales: primero old(), si no hay, las del producto
    @php
        $oldImages = collect(old('images', []))->values()->all();
        $prodImages = collect($product['images'] ?? [])->map(function($i){
            return [
                'url' => $i['url'] ?? '',
                'alt' => $i['alt'] ?? '',
                'is_main' => !empty($i['is_main']) ? 1 : 0,
            ];
        })->values()->all();
    @endphp
    const initialImages = @json(!empty($oldImages) ? $oldImages : $prodImages);

    (function initImages(){
        const container = document.getElementById('images-container');
        container.innerHTML = '';
        imgIndex = 0;

        if (initialImages.length) {
            initialImages.forEach(img => addImageRow(img));
        } else {
            addImageRow(); // al menos una fila vacía
        }
    })();
</script>
</body>
</html>

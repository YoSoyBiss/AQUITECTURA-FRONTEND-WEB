<!-- resources/views/products/create.blade.php -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear producto</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4e3d7; padding: 40px; }
        h1 { text-align: center; color: #5a3e36; margin-bottom: 10px; }
        .container { max-width: 900px; margin: auto; background: #fff; padding: 30px; border-radius: 10px; box-shadow: 0 0 10px rgba(90,62,54,.2); }
        .alert { background:#ffebee; border:1px solid #ffcdd2; color:#c62828; padding:10px; border-radius:6px; margin-bottom:15px; }
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
        .image-row { display:grid; grid-template-columns:2.2fr 1.5fr .7fr .4fr; gap:10px; margin-bottom:10px; align-items:center; }
        .muted { color:#6d4c41; font-size:12px; }
        @media (max-width:800px){ .form-grid{grid-template-columns:1fr;} .image-row{grid-template-columns:1fr;} }
    </style>
</head>
<body>
<div class="container">
    <h1>Crear producto</h1>

    @if ($errors->any())
        <div class="alert">
            @foreach ($errors->all() as $e)
                <div>{{ $e }}</div>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('products.store') }}">
        @csrf

        <div class="form-grid">
            {{-- Datos del producto --}}
            <div class="full">
                <label for="title">Título</label>
                <input type="text" id="title" name="title" value="{{ old('title') }}" required>
            </div>

            <div>
                <label for="publisher_id">Editorial</label>
                <select id="publisher_id" name="publisher_id" required>
                    <option value="">-- Selecciona --</option>
                    @foreach(($publishers ?? []) as $pub)
                        <option value="{{ $pub['id'] }}" {{ old('publisher_id') == $pub['id'] ? 'selected' : '' }}>
                            {{ $pub['name'] }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="stock">Stock</label>
                <input type="number" id="stock" name="stock" min="0" value="{{ old('stock', 0) }}" required>
            </div>

            <div>
                <label for="price">Precio de venta ($)</label>
                <input type="number" step="0.01" min="0" id="price" name="price" value="{{ old('price', 0) }}" required>
            </div>

            <div>
                <label for="preciodeproveedor">Precio de proveedor ($)</label>
                <input type="number" step="0.01" min="0" id="preciodeproveedor" name="preciodeproveedor" value="{{ old('preciodeproveedor') }}" placeholder="0.00">
                <div class="muted">Opcional: si lo capturas, se guarda la relación 1–1.</div>
            </div>

            <div>
                <label for="author_ids">Autores</label>
                <select id="author_ids" name="author_ids[]" multiple>
                    @foreach(($authors ?? []) as $a)
                        <option value="{{ $a['id'] }}" @if(collect(old('author_ids', []))->contains($a['id'])) selected @endif>
                            {{ $a['name'] }}
                        </option>
                    @endforeach
                </select>
                <div class="muted">Mantén Ctrl/⌘ para seleccionar múltiples.</div>
            </div>

            <div>
                <label for="genre_ids">Géneros</label>
                <select id="genre_ids" name="genre_ids[]" multiple>
                    @foreach(($genres ?? []) as $g)
                        <option value="{{ $g['id'] }}" @if(collect(old('genre_ids', []))->contains($g['id'])) selected @endif>
                            {{ $g['name'] }}
                        </option>
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
                    <div class="image-row">
                        <input type="url" name="images[0][url]" placeholder="URL de la imagen">
                        <input type="text" name="images[0][alt]" placeholder="Texto alternativo">
                        <select name="images[0][is_main]">
                            <option value="0">Secundaria</option>
                            <option value="1">Principal</option>
                        </select>
                        <button type="button" class="btn-light" onclick="removeRow(this)">×</button>
                    </div>
                </div>

                <div class="muted">Consejo: marca solo una como “Principal”.</div>
            </div>

            <div class="full" style="display:flex; gap:10px; justify-content:flex-end; margin-top:8px;">
                <a class="btn btn-light" href="{{ route('products.index') }}">Cancelar</a>
                <button class="btn" type="submit">Guardar</button>
            </div>
        </div>
    </form>
</div>

<script>
    let imgIndex = 1;

    function addImageRow(fromOld = null) {
        const container = document.getElementById('images-container');
        const i = imgIndex++;
        const row = document.createElement('div');
        row.className = 'image-row';
        row.innerHTML = `
            <input type="url" name="images[${i}][url]" placeholder="URL de la imagen" value="${fromOld?.url ?? ''}">
            <input type="text" name="images[${i}][alt]" placeholder="Texto alternativo" value="${fromOld?.alt ?? ''}">
            <select name="images[${i}][is_main]">
                <option value="0" ${fromOld && fromOld.is_main == 0 ? 'selected' : ''}>Secundaria</option>
                <option value="1" ${fromOld && fromOld.is_main == 1 ? 'selected' : ''}>Principal</option>
            </select>
            <button type="button" class="btn-light" onclick="removeRow(this)">×</button>
        `;
        container.appendChild(row);
    }

    function removeRow(btn) {
        const row = btn.closest('.image-row');
        if (row) row.remove();
    }

    @php $oldImages = collect(old('images', []))->values()->all(); @endphp
    const oldImages = @json($oldImages);
    if (oldImages.length > 0) {
        document.getElementById('images-container').innerHTML = '';
        imgIndex = 0;
        oldImages.forEach(img => addImageRow(img));
    }
</script>
</body>
</html>

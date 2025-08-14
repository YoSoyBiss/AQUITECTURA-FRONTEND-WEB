<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Pantalla de Inicio</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Bootstrap para estilos rápidos --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f8f9fa;
        }
        .role-btn {
            padding: 1rem;
            font-size: 1.2rem;
            border-radius: 10px;
            margin-bottom: 1rem;
            width: 100%;
        }
        .note {
            font-size: 0.9rem;
            color: #6c757d;
        }
    </style>
</head>
<body>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">

            <h3 class="mb-4 text-center">Elige cómo quieres entrar</h3>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger">{{ $errors->first() }}</div>
            @endif

            @php
                $roles = [
                    ['key'=>'admin', 'label'=>'Admin', 'class'=>'btn-dark'],
                    ['key'=>'seller', 'label'=>'Seller', 'class'=>'btn-primary'],
                    ['key'=>'consultant', 'label'=>'Consultant', 'class'=>'btn-info'],
                    ['key'=>'otros', 'label'=>'Otros', 'class'=>'btn-outline-secondary'],
                ];
            @endphp

            @foreach($roles as $r)
                <form method="POST" action="{{ route('start.select') }}">
                    @csrf
                    <input type="hidden" name="role" value="{{ $r['key'] }}">
                    <button type="submit" class="btn {{ $r['class'] }} role-btn">
                        {{ $r['label'] }}
                    </button>
                </form>
            @endforeach

            <p class="note mt-3 text-center">
                Si eliges <strong>Admin</strong> pero tu cuenta no es admin, el sistema no te dejará pasar.
            </p>
        </div>
    </div>
</div>
</body>
</html>

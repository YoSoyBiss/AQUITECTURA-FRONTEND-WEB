<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Pantalla de Inicio</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        /* Paleta crema */
        body {
            background: linear-gradient(135deg, #fdf6e3 0%, #fae9d4 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            position: relative;
            overflow-x: hidden;
        }

        h3 {
            color: #5b4636;
            font-weight: 700;
        }

        /* Botones con animación */
        .role-btn {
            padding: 1rem;
            font-size: 1.2rem;
            border-radius: 12px;
            margin-bottom: 1rem;
            width: 100%;
            border: none;
            transition: all 0.3s ease;
            box-shadow: 0 4px 8px rgba(91, 70, 54, 0.15);
        }
        .role-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 16px rgba(91, 70, 54, 0.25);
        }

        /* Colores personalizados */
        .btn-admin {
            background-color: #8B4513;
            color: #fff;
        }
        .btn-seller {
            background-color: #D2691E;
            color: #fff;
        }
        .btn-consultant {
            background-color: #CD853F;
            color: #fff;
        }
        .btn-otros {
            background-color: transparent;
            color: #5b4636;
            border: 2px solid #5b4636;
        }
        .btn-otros:hover {
            background-color: #5b4636;
            color: #fff;
        }

        /* Nota */
        .note {
            font-size: 0.9rem;
            color: #6c757d;
        }

        /* Detalles decorativos de libros */
        .book-decor {
            position: absolute;
            opacity: 0.15;
            pointer-events: none;
        }
        .book-decor.top-left {
            top: -20px;
            left: -30px;
            width: 150px;
            transform: rotate(-15deg);
        }
        .book-decor.bottom-right {
            bottom: -20px;
            right: -30px;
            width: 180px;
            transform: rotate(15deg);
        }

        /* Animación sutil de aparición */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .fade-in-up {
            animation: fadeInUp 0.8s ease forwards;
        }
    </style>
</head>
<body>
    <!-- Decoraciones -->
    <img src="https://cdn-icons-png.flaticon.com/512/29/29302.png" alt="Libro" class="book-decor top-left">
    <img src="https://cdn-icons-png.flaticon.com/512/29/29302.png" alt="Libro" class="book-decor bottom-right">

    <div class="container py-5 fade-in-up">
        <div class="row justify-content-center">
            <div class="col-md-6 text-center">

                <h3 class="mb-4">Elige cómo quieres entrar</h3>

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if($errors->any())
                    <div class="alert alert-danger">{{ $errors->first() }}</div>
                @endif

                @php
                    $roles = [
                        ['key'=>'admin', 'label'=>'Admin', 'class'=>'btn-admin'],
                        ['key'=>'seller', 'label'=>'Seller', 'class'=>'btn-seller'],
                        ['key'=>'consultant', 'label'=>'Consultant', 'class'=>'btn-consultant'],
                        ['key'=>'otros', 'label'=>'Otros', 'class'=>'btn-otros'],
                    ];
                @endphp

                @foreach($roles as $r)
                    <form method="POST" action="{{ route('start.select') }}">
                        @csrf
                        <input type="hidden" name="role" value="{{ $r['key'] }}">
                        <button type="submit" class="role-btn {{ $r['class'] }}">
                            {{ $r['label'] }}
                        </button>
                    </form>
                @endforeach

                <p class="note mt-3">
                    Si eliges <strong>Admin</strong> pero tu cuenta no es admin, el sistema no te dejará pasar.
                </p>
            </div>
        </div>
    </div>
</body>
</html>
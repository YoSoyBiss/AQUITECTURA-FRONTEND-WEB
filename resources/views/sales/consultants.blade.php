<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Contáctanos — Librería</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;800&display=swap" rel="stylesheet">
    <style>
        :root{
            /* Paleta de colores claros que proporcionaste */
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

        /* Estilos generales */
        *{box-sizing:border-box;margin:0;padding:0}
        body{
            font-family: 'Poppins', 'Segoe UI', Roboto, Arial, sans-serif;
            background: radial-gradient(1200px 800px at 80% -10%, #f6f1ee 0%, var(--bg) 60%) fixed;
            color: var(--ink);
            min-height:100vh;
            overflow-x:hidden;
        }
        a{color:inherit; text-decoration:none}

        /* Nav */
        .nav{
            display:flex;
            align-items:center;
            justify-content:space-between;
            gap:12px;
            padding: 1.5rem 1rem;
            max-width: 1100px;
            margin: auto;
        }
        .brand{display:flex; gap:12px; align-items:center}
        .logo{
            width:44px; height:44px; border-radius:12px; display:grid; place-items:center;
            background:linear-gradient(135deg, rgba(255,255,255,.85), rgba(255,255,255,.65));
            color:#111; font-weight:800; box-shadow:0 10px 30px rgba(0,0,0,.25);
        }
        .nav a.btn{
            padding:10px 14px; border-radius:12px;
            border:1px solid var(--line);
            background:var(--card);
            box-shadow: 0 4px 12px rgba(0,0,0,.1);
            transition: transform .2s ease;
        }
        .nav a.btn:hover{ transform: translateY(-2px); box-shadow: 0 6px 16px rgba(0,0,0,.15); }
        .divider{
            height:1px;
            background:linear-gradient(90deg, transparent, var(--line), transparent);
            margin:18px auto;
            max-width: 1100px;
        }

        /* Header */
        header{
            text-align:center;
            padding:3rem 1rem;
            background:linear-gradient(135deg,var(--brand),var(--brand-2));
            color:#fff;
            animation: fadeInDown 1s ease-out;
        }
        header h1{font-size:2.5rem; margin-bottom:.5rem}
        header p{font-size:1.2rem; opacity:.9}

        main{
            max-width:1100px;
            margin:auto;
            padding:2rem 1rem;
            display:grid;
            gap:2rem;
        }

        /* Contacto */
        .contact-box{
            background:var(--card);
            padding:2rem;
            border-radius:16px;
            box-shadow:0 8px 20px rgba(0,0,0,.1);
            display:grid;
            grid-template-columns:1fr 1fr;
            gap:2rem;
            animation: fadeInUp 1.2s ease;
        }
        @media (max-width: 768px) {
            .contact-box {
                grid-template-columns: 1fr;
            }
        }

        .contact-info{
            display:flex;flex-direction:column;gap:1rem;justify-content:center
        }
        .contact-info h2{color:var(--brand-2);margin-bottom:1rem}
        .contact-info a{
            text-decoration:none;color:var(--brand);
            font-weight:600;transition:.3s
        }
        .contact-info a:hover{color:var(--accent)}

        .contact-form input,.contact-form textarea{
            width:100%;padding:.8rem 1rem;margin-bottom:1rem;
            border:1px solid var(--line);border-radius:8px;
            font-size:1rem
        }
        .contact-form button{
            background:var(--brand-2);color:#fff;
            padding:.9rem 1.4rem;border:none;border-radius:8px;
            font-size:1rem;cursor:pointer;
            transition:.3s;box-shadow:0 4px 12px rgba(0,0,0,.15)
        }
        .contact-form button:hover{background:var(--brand)}

        .gallery{
            margin-top:2rem;
            display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));
            gap:1rem;
        }
        .gallery img{
            width:100%;border-radius:12px;
            transition:transform .4s, box-shadow .4s
        }
        .gallery img:hover{
            transform:scale(1.05);
            box-shadow:0 10px 20px rgba(0,0,0,.2)
        }

        /* Footer */
        footer{
            text-align:center;padding:1.5rem;margin-top:2rem;
            background:var(--brand-2);color:#fff;
            animation: fadeInUp 1.2s ease;
        }

        /* FAB WhatsApp */
        .fab{
            position:fixed; right:18px; bottom:18px; z-index:10;
            width:60px; height:60px; border-radius:50%; background:#25D366; color:#fff; display:grid; place-items:center;
            box-shadow:0 12px 30px rgba(0,0,0,.35); cursor:pointer; border:none; font-size:26px; font-weight:900;
            transform:translateZ(0); transition:transform .2s ease;
        }
        .fab:hover{ transform:scale(1.05) }

        /* Animaciones */
        @keyframes fadeInDown{from{opacity:0;transform:translateY(-30px)}to{opacity:1;transform:translateY(0)}}
        @keyframes fadeInUp{from{opacity:0;transform:translateY(30px)}to{opacity:1;transform:translateY(0)}}
    </style>
</head>
<body>
    <div class="nav">
        <div class="brand">
            <div class="logo">📚</div>
            <div>
                <div style="font-weight:800">Librería</div>
                <div style="font-size:12px; color:var(--muted)">Historias que se quedan</div>
            </div>
        </div>
        <div style="display:flex; gap:8px">
            <a class="btn" href="{{ route('dashboard.redirect') ?? '#' }}">Menú</a>
        </div>
    </div>
    <div class="divider"></div>

    <header>
        <h1>Comunícate con Nosotros</h1>
        <p>Estamos listos para ayudarte 📚</p>
    </header>

    <main>
        <div class="contact-box">
            <div class="contact-info">
                <h2>Datos de contacto</h2>
                <p>📞 WhatsApp: <a href="https://wa.me/5217711790029" target="_blank">771 179 0029</a></p>
                <p>📧 Correo: <a href="mailto:arturo@gmail.com">arturo@gmail.com</a></p>
                <p>📍 Dirección: Av. Principal #123, Centro</p>
            </div>

            <form class="contact-form">
                <h2>Envíanos un mensaje</h2>
                <input type="text" placeholder="Tu nombre" required>
                <input type="email" placeholder="Tu correo" required>
                <textarea rows="4" placeholder="Escribe tu mensaje..."></textarea>
                <button type="submit">Enviar</button>
            </form>
        </div>

        <section class="gallery">
            <img src="https://images.unsplash.com/photo-1524995997946-a1c2e315a42f" alt="Libros 1">
            <img src="https://images.unsplash.com/photo-1512820790803-83ca734da794" alt="Libros 2">
            <img src="https://static01.nyt.com/images/2018/07/12/universal/es/LIBRERIASESPA1206/LIBRERIASESPA1206-superJumbo.jpg" alt="Libros 3">
            <img src="https://images.unsplash.com/photo-1516979187457-637abb4f9353" alt="Libros 4">
        </section>
    </main>

    <footer>
        <p>&copy; {{ date('Y') }} Librería | Todos los derechos reservados</p>
    </footer>

    <a id="fab-wa" class="fab" href="https://wa.me/5217711790029" target="_blank" aria-label="Chatear por WhatsApp">💬</a>
</body>
</html>
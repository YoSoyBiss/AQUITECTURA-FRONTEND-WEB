<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <title>Editar Usuario</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <style>
    :root{
      --bg:#f4e3d7;
      --primary:#8d6e63;
      --primary-dark:#795548;
      --text:#5a3e36;
      --shadow:rgba(90,62,54,.2);
      --ok:#2e7d32;
      --err:#c62828;
    }

    /* Base */
    body {
      background-color: var(--bg);
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      padding: 40px;
    }
    .container {
      max-width: 680px;
      margin: auto;
      background: #fff;
      padding: 30px;
      border-radius: 16px;
      box-shadow: 0 10px 24px var(--shadow);
      animation: fadeIn .5s ease;
    }
    h1 { text-align: center; color: var(--text); margin-top: 0; }

    label { font-weight: 700; color: var(--text); display: block; margin-top: 16px; }
    input[type="text"], input[type="password"], select {
      width: 100%; padding: 12px; margin-top: 6px;
      border-radius: 10px; border: 1px solid #ddd; box-sizing: border-box;
      transition: box-shadow .2s ease, transform .05s ease;
    }
    input:focus, select:focus {
      outline: none; box-shadow: 0 0 0 3px rgba(141,110,99,.2);
      transform: translateY(-1px);
    }

    .btn {
      background-color: var(--primary);
      color: #fff; padding: 12px 16px; border: none; border-radius: 12px;
      font-weight: 700; cursor: pointer; transition: transform .12s ease, box-shadow .2s ease, background-color .2s ease;
      box-shadow: 0 6px 14px var(--shadow);
    }
    .btn:hover { background-color: var(--primary-dark); transform: translateY(-1px); }
    .btn:active { transform: translateY(0); }
    .btn.full { width: 100%; }
    .btn.ghost {
      background: transparent; color: var(--text); border: 2px solid var(--text);
    }
    .row { display: flex; gap: 12px; flex-wrap: wrap; }
    .row > * { flex: 1 1 240px; }

    .back-link {
      display: inline-block; margin-top: 16px; color: var(--text);
      text-decoration: none; font-weight: 700;
    }
    .back-link:hover { text-decoration: underline; }

    .error { color: var(--err); margin: 12px 0; }
    .success { color: var(--ok); margin: 12px 0; }

    /* Modal */
    .overlay {
      position: fixed; inset: 0; background: rgba(0,0,0,.35);
      display: none; align-items: center; justify-content: center;
      animation: fadeIn .2s ease;
      z-index: 50;
    }
    .overlay.show { display: flex; }

    .modal {
      width: 100%; max-width: 520px; background: #fff; border-radius: 16px;
      box-shadow: 0 14px 34px rgba(0,0,0,.18);
      transform: translateY(10px) scale(.98);
      animation: slideUp .25s ease forwards;
      overflow: hidden;
    }
    .modal-header {
      padding: 16px 20px; background: linear-gradient(135deg, #fdf6e3 0%, #fae9d4 100%);
      color: var(--text); font-weight: 800; display: flex; align-items: center; justify-content: space-between;
    }
    .modal-body { padding: 20px; }
    .modal-actions { display: flex; gap: 8px; justify-content: flex-end; padding: 0 20px 20px; }

    .chip {
      display: inline-flex; align-items: center; gap: 6px; padding: 8px 10px;
      border-radius: 999px; font-size: 13px; font-weight: 700;
    }
    .chip.ok { background: #e8f5e9; color: var(--ok); }
    .chip.err { background: #ffebee; color: var(--err); }

    .hint { font-size: 12px; color: #6b6b6b; margin-top: 6px; }
    .hidden { display: none; }

    @keyframes fadeIn { from { opacity: 0 } to { opacity: 1 } }
    @keyframes slideUp { from { transform: translateY(10px) scale(.98); opacity:.95 } to { transform: translateY(0) scale(1); opacity:1 } }
    @keyframes pulse { 0%{box-shadow:0 0 0 0 rgba(46,125,50,.4)} 70%{box-shadow:0 0 0 10px rgba(46,125,50,0)} 100%{box-shadow:0 0 0 0 rgba(46,125,50,0)} }
    .pulse { animation: pulse 1.2s ease 1; border-radius: 12px; }
  </style>
</head>
<body>

  <div class="container">
    <h1>Editar Usuario</h1>

    @if ($errors->any())
      <div class="error">
        <ul>
          @foreach ($errors->all() as $error)
            <li>{{ is_array($error) ? implode(', ', $error) : $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    @php
      $userRoleId = old('role', data_get($user, 'role._id', $user['role'] ?? ''));
      $userId = $user['id'] ?? $user['_id'] ?? '';
      $userEmail = $user['email'] ?? '';
    @endphp

    <form id="editForm" method="POST" action="{{ url('/users/' . $userId) }}">
      @csrf
      @method('PUT')

      <div class="row">
        <div>
          <label for="name">Nombre:</label>
          <input type="text" id="name" name="name" value="{{ old('name', $user['name']) }}" required />
        </div>

        <div>
          <label for="role">Rol:</label>
          <select id="role" name="role" required>
            <option value="">-- Selecciona un rol --</option>
            @foreach ($roles as $role)
              <option value="{{ $role['_id'] }}" {{ $userRoleId == $role['_id'] ? 'selected' : '' }}>
                {{ ucfirst($role['name']) }}
              </option>
            @endforeach
          </select>
        </div>
      </div>

      {{-- Inputs ocultos: los llena el modal cuando se valida y guarda --}}
      <input type="hidden" name="password" id="hidden_password">
      <input type="hidden" name="password_confirmation" id="hidden_password_confirmation">

      <div class="row" style="margin-top:12px;">
        <div>
          <label>Contraseña:</label>
          <div class="hint">Usa “Administrar contraseña” para cambiarla de forma segura.</div>
        </div>
        <div style="align-self:flex-end;">
          <button type="button" id="managePwdBtn" class="btn">Administrar contraseña</button>
        </div>
      </div>

      <button type="submit" class="btn full" style="margin-top:18px;">Actualizar Usuario</button>
    </form>

    <a href="{{ url('/users') }}" class="back-link">← Volver al listado de usuarios</a>
  </div>

  <!-- MODAL: Administrar contraseña -->
  <div class="overlay" id="pwdOverlay" aria-hidden="true">
    <div class="modal" role="dialog" aria-modal="true" aria-labelledby="modalTitle">
      <div class="modal-header">
        <span id="modalTitle">Administrar contraseña</span>
        <button class="btn ghost" id="closeModalBtn" aria-label="Cerrar">✕</button>
      </div>

      <div class="modal-body">
        <div id="statusChip" class="chip hidden"></div>

        <label for="current_password">Contraseña actual</label>
        <input type="password" id="current_password" autocomplete="current-password" placeholder="Tu contraseña actual">

        <div class="hint">Primero verifica tu contraseña actual para habilitar el cambio.</div>

        <div id="newPwdGroup" class="hidden">
          <label for="new_password" style="margin-top:14px;">Nueva contraseña</label>
          <input type="password" id="new_password" minlength="6" autocomplete="new-password" placeholder="Mínimo 6 caracteres">

          <label for="new_password_confirmation">Confirmar nueva contraseña</label>
          <input type="password" id="new_password_confirmation" minlength="6" autocomplete="new-password" placeholder="Repite la nueva contraseña">

          <div class="hint" id="matchHint"></div>
        </div>
      </div>

      <div class="modal-actions">
        <button type="button" class="btn ghost" id="cancelModalBtn">Cancelar</button>
        <button type="button" class="btn" id="verifyBtn">Verificar</button>
        <button type="button" class="btn" id="savePwdBtn" disabled>Guardar</button>
      </div>
    </div>
  </div>

  <script>
    (function(){
      const btnOpen = document.getElementById('managePwdBtn');
      const overlay = document.getElementById('pwdOverlay');
      const btnClose = document.getElementById('closeModalBtn');
      const btnCancel = document.getElementById('cancelModalBtn');
      const btnVerify = document.getElementById('verifyBtn');
      const btnSave = document.getElementById('savePwdBtn');
      const chip = document.getElementById('statusChip');
      const newGroup = document.getElementById('newPwdGroup');
      const matchHint = document.getElementById('matchHint');

      const inpCurrent = document.getElementById('current_password');
      const inpNew = document.getElementById('new_password');
      const inpNew2 = document.getElementById('new_password_confirmation');

      const hiddenPwd = document.getElementById('hidden_password');
      const hiddenPwd2 = document.getElementById('hidden_password_confirmation');

      const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

      const verifyUrl = "{{ route('users.verifyPassword') }}"; // <-- necesitas crear esta ruta
      const userEmail = @json($userEmail);

      function openModal(){
        overlay.classList.add('show');
        inpCurrent.value = '';
        inpNew.value = '';
        inpNew2.value = '';
        newGroup.classList.add('hidden');
        btnSave.disabled = true;
        setChip(); // limpia estado
        inpCurrent.focus();
      }
      function closeModal(){
        overlay.classList.remove('show');
      }
      function setChip(kind, text){
        if(!kind){ chip.className = 'chip hidden'; chip.textContent = ''; return; }
        chip.className = 'chip ' + (kind === 'ok' ? 'ok' : 'err');
        chip.textContent = text || '';
      }

      function validateNewMatch(){
        const a = inpNew.value.trim();
        const b = inpNew2.value.trim();
        const okLen = a.length >= 6 && b.length >= 6;
        const match = a && b && a === b;
        if(!okLen){
          matchHint.textContent = 'La nueva contraseña debe tener al menos 6 caracteres.';
          matchHint.style.color = '#6b6b6b';
          btnSave.disabled = true;
          return false;
        }
        if(!match){
          matchHint.textContent = 'Las contraseñas no coinciden.';
          matchHint.style.color = 'var(--err)';
          btnSave.disabled = true;
          return false;
        }
        matchHint.textContent = 'Todo listo. Guarda para aplicar el cambio.';
        matchHint.style.color = 'var(--ok)';
        btnSave.disabled = false;
        return true;
      }

      // Eventos
      btnOpen.addEventListener('click', openModal);
      btnClose.addEventListener('click', closeModal);
      btnCancel.addEventListener('click', closeModal);
      overlay.addEventListener('click', (e)=>{ if(e.target === overlay) closeModal(); });

      inpNew.addEventListener('input', validateNewMatch);
      inpNew2.addEventListener('input', validateNewMatch);

      btnVerify.addEventListener('click', async ()=>{
        setChip();
        const current = inpCurrent.value;
        if(!current){
          setChip('err', 'Escribe tu contraseña actual.');
          return;
        }
        try{
          const res = await fetch(verifyUrl, {
            method: 'POST',
            headers: {
              'Content-Type':'application/json',
              'X-CSRF-TOKEN': csrf,
            },
            body: JSON.stringify({ email: userEmail, password: current })
          });
          const data = await res.json();

          if(res.ok && data.valid){
            setChip('ok', 'Contraseña verificada ✔');
            newGroup.classList.remove('hidden');
            inpNew.focus();
            document.querySelector('.modal').classList.add('pulse');
            setTimeout(()=>document.querySelector('.modal').classList.remove('pulse'), 1200);
          }else{
            setChip('err', data.message || 'Contraseña incorrecta.');
            newGroup.classList.add('hidden');
            btnSave.disabled = true;
          }
        }catch(err){
          setChip('err', 'No se pudo verificar. Intenta de nuevo.');
          console.error(err);
        }
      });

      btnSave.addEventListener('click', ()=>{
        if(!validateNewMatch()) return;
        // Pasar valores al form principal y cerrar
        hiddenPwd.value = inpNew.value.trim();
        hiddenPwd2.value = inpNew2.value.trim();
        closeModal();
      });
    })();
  </script>
</body>
</html>

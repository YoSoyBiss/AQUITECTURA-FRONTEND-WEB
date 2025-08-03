<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Registro</title>
  <style>/* estilos previos... */</style>
</head>
<body>
  <div class="register-container">
    <h2>Crear Cuenta</h2>
    <form id="register-form">
      <label for="name">Nombre:</label>
      <input type="text" id="name" required>

      <label for="email">Correo electrónico:</label>
      <input type="email" id="email" required>

      <label for="password">Contraseña:</label>
      <input type="password" id="password" required>

      <label for="password_confirmation">Confirme contraseña:</label>
      <input type="password" id="password_confirmation" required>

      <button type="submit">Crear Cuenta</button>
    </form>
    <a href="/login"><button>Volver</button></a>
  </div>
  <script>
    document.getElementById('register-form').addEventListener('submit', async (e) => {
      e.preventDefault();
      if (password.value !== password_confirmation.value) return alert('Las contraseñas no coinciden');
      const res = await fetch('http://localhost:3000/api/auth/register', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
          name: name.value,
          email: email.value,
          password: password.value
        })
      });
      const data = await res.json();
      if (res.ok) {
        alert('Registro exitoso. Ahora puedes iniciar sesión.');
        window.location.href = '/login';
      } else {
        alert(data.message || 'Error al registrarse');
      }
    });
  </script>
</body>
</html>
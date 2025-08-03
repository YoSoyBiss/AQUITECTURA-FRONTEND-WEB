<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Login</title>
  <style>/* estilos previos... */</style>
</head>
<body>
  <div class="login-container">
    <h2>Iniciar sesión</h2>
    <form id="login-form">
      <label for="email">Correo Electrónico:</label>
      <input type="email" id="email" required>

      <label for="password">Contraseña:</label>
      <input type="password" id="password" required>

      <button type="submit">Iniciar sesión</button>
    </form>
    <div class="register-link">
      <a href="/register">¿No tienes cuenta?</a>
    </div>
  </div>
  <script>
    document.getElementById('login-form').addEventListener('submit', async (e) => {
      e.preventDefault();
      const res = await fetch('http://localhost:3000/api/auth/login', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ email: email.value, password: password.value })
      });
      const data = await res.json();
      if (res.ok) {
        localStorage.setItem('token', data.token);
        window.location.href = '/users';
      } else {
        alert(data.message || 'Login fallido');
      }
    });
  </script>
</body>
</html>
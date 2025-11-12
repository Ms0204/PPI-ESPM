<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Inicio de Sesión</title>
  <!-- Incluir Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Incluir Font Awesome para los íconos -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
  <!-- Incluir Google Fonts (Poppins) -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <!-- Incluir style -->
<link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>
<body>
  <div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="login-container p-4 shadow rounded-3">
      <!-- Logo de la empresa arriba -->
      <div class="logo-container text-center mb-4">
        <img src="{{ asset('static/Img/logo_espomalia.png') }}" alt="Logo ESPOMALIA">
      </div>
      <h2 class="text-center mb-4">
        Iniciar Sesión
      </h2>
      <form action="{{ route('login.procesar') }}" method="POST" id="loginForm">
          @csrf
        @if (session('error'))
        <div class="alert alert-danger text-center">
          {{ session('error') }}
        </div>
  @endif
        <div class="mb-3">
          <input type="email" name="usuario" class="form-control" placeholder="Correo Electronico" required>
        </div>
        <div class="mb-3 position-relative">
          <input type="password" name="contraseña" id="contraseña" class="form-control" placeholder="Contraseña" required>
          <span id="toggle-password" class="position-absolute top-50 end-0 translate-middle-y pe-3" style="cursor: pointer;">
            <i class="fas fa-eye-slash"></i>
          </span>
        </div>
        <button type="submit" class="btn btn-danger w-100">Iniciar sesión</button>
      </form>
      <div class="footer text-center mt-3">
        <p><a href="#" data-bs-toggle="modal" data-bs-target="#recoverPasswordModal">¿Olvidaste tu contraseña?</a></p>
      </div>
    </div>
  </div>
<!-- Modal de recuperación de contraseña -->
<div class="modal fade" id="recoverPasswordModal" tabindex="-1" aria-labelledby="recoverPasswordModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title" id="recoverPasswordModalLabel">Recuperar Contraseña</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>

      <div class="modal-body">

        {{-- Mensaje de éxito centrado dentro del modal --}}
        @if(session('success'))
          <div class="alert alert-success alert-dismissible fade show text-center" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
          </div>
        @endif

        {{-- Formulario --}}
        <form action="{{ route('password.recover') }}" method="POST">
          @csrf
          <div class="mb-3">
            <label for="email" class="form-label">Correo electrónico</label>
            <input type="email" name="email" class="form-control" id="email" placeholder="Ingresa tu correo" required>
          </div>
          <button type="submit" class="btn btn-danger w-100">Recuperar contraseña</button>
        </form>
      </div>
    </div>
  </div>
</div>

  <!-- Bootstrap Bundle -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
  
  <!-- Script para mostrar/ocultar la contraseña -->
<script>
    const togglePassword = document.getElementById('toggle-password');
    const passwordField = document.getElementById('contraseña');

    togglePassword.addEventListener('click', function() {
      const type = passwordField.type === 'password' ? 'text' : 'password';
      passwordField.type = type;

      this.innerHTML = type === 'password'
        ? '<i class="fas fa-eye-slash"></i>'
        : '<i class="fas fa-eye"></i>';
    });
  </script>
  @if(session('success'))
<script>
  const recoverModal = new bootstrap.Modal(document.getElementById('recoverPasswordModal'));
  recoverModal.show();
</script>
@endif
</body>
</html>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sistema de Gestión</title>
  <!-- Fuente Poppins -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
  <!-- FontAwesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
  <!-- Estilo CSS local -->
  <link href="{{ asset('css/home.css') }}" rel="stylesheet">
</head>
<body>
  <!-- Botón para abrir la barra lateral en móviles -->
  <button class="menu-toggle" onclick="toggleMenu()"><i class="fas fa-bars"></i></button>

  <!-- Barra lateral -->
  <div class="sidebar">
    <div class="logo">
      <img src="{{ asset('static/Img/logo_espomalia.png') }}" alt="Logo del Sistema">
    </div>
    <ul>
      <li><a href="{{ url('usuarios') }}">Gestión de Usuarios</a></li>
      <li><a href="{{ url('inventarios') }}">Gestión de Inventarios</a></li>
      <li><a href="{{ url('pagos') }}">Gestión de Pagos</a></li>
      <li><a href="{{ url('reportes') }}">Gestión de Reportes</a></li>
      <li><a href="{{ url('ingresos') }}">Gestión de Ingresos</a></li>
      <li><a href="{{ url('egresos') }}">Gestión de Egresos</a></li>
      <li><a href="{{ url('productos') }}">Gestión de Productos</a></li>
      <li><a href="{{ url('categorias') }}">Gestión de Categorías</a></li>
      <li><a href="{{ url('roles') }}">Gestión de Roles</a></li>
      <li><a href="{{ url('permisos') }}">Gestión de Permisos</a></li>
      <li>
        <a href="{{ route('login') }}" class="btn-danger" onclick="return confirmarCerrarSesion()">
          <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
        </a>
      </li>
    </ul>
  </div>

  <!-- Contenido principal -->
  <div class="main-content">
    <header>
      <h1>Bienvenido al Sistema</h1>
    </header>

    <main>
      <div class="grid-container">
        @php
          $gestiones = [
            ['img' => 'agregar-usuario.png', 'titulo' => 'Usuarios', 'desc' => 'Administra la información.', 'url' => 'usuarios', 'btn' => 'blue'],
            ['img' => 'inventario.png', 'titulo' => 'Inventarios', 'desc' => 'Organiza los productos.', 'url' => 'inventarios', 'btn' => 'green'],
            ['img' => 'metodo-de-pago.png', 'titulo' => 'Pagos', 'desc' => 'Controla los pagos realizados.', 'url' => 'pagos', 'btn' => 'yellow'],
            ['img' => 'reporte.png', 'titulo' => 'Reportes', 'desc' => 'Consulta reportes del sistema.', 'url' => 'reportes', 'btn' => 'red'],
            ['img' => 'ingresos.png', 'titulo' => 'Ingresos', 'desc' => 'Supervisa los ingresos.', 'url' => 'ingresos', 'btn' => 'purple'],
            ['img' => 'egresos.png', 'titulo' => 'Egresos', 'desc' => 'Monitorea los egresos.', 'url' => 'egresos', 'btn' => 'orange'],
            ['img' => 'agregar-producto.png', 'titulo' => 'Productos', 'desc' => 'Administra productos.', 'url' => 'productos', 'btn' => 'blue'],
            ['img' => 'categorias.png', 'titulo' => 'Categorías', 'desc' => 'Organiza categorías.', 'url' => 'categorias', 'btn' => 'green'],
            ['img' => 'roles.png', 'titulo' => 'Roles', 'desc' => 'Gestiona roles de usuarios.', 'url' => 'roles', 'btn' => 'red'],
            ['img' => 'permisos.png', 'titulo' => 'Permisos', 'desc' => 'Gestiona permisos del sistema.', 'url' => 'permisos', 'btn' => 'red'],
          ];
        @endphp

        @foreach($gestiones as $gestion)
        <div class="card">
          <div class="icon">
            <img src="{{ asset('static/Icons/' . $gestion['img']) }}" alt="Icono {{ $gestion['titulo'] }}">
          </div>
          <h2>Gestión de {{ $gestion['titulo'] }}</h2>
          <p>{{ $gestion['desc'] }}</p>
          <a href="{{ url($gestion['url']) }}" class="btn {{ $gestion['btn'] }}">Ir a {{ $gestion['titulo'] }}</a>
        </div>
        @endforeach
      </div>
    </main>

    <!-- FOOTER -->
    <footer>
      &copy; 2025 Sistema de Gestión | PPI-ESPOMALIA
    </footer>
  </div>

  <script src="{{ asset('js/home.js') }}"></script>
</body>
</html>

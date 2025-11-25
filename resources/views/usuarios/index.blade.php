<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Gestión de Usuarios</title>

  <!-- Fuente Poppins -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet" />

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" />

  <!-- FontAwesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />

  <!-- Estilos globales (el mismo del home) -->
  <link href="{{ asset('css/styles.css') }}" rel="stylesheet" />
</head>

<body>
  <!-- ===== BOTÓN MENÚ RESPONSIVO ===== -->
  <button class="menu-toggle" onclick="toggleMenu()">
    <i class="fas fa-bars"></i>
  </button>

  <!-- ===== SIDEBAR ===== -->
  <div class="sidebar">
    <div class="logo">
      <img src="{{ asset('static/Img/logo_espomalia.png') }}" alt="Logo del Sistema" />
    </div>

    <ul>
      <li><a href="{{ url('usuarios') }}" class="active">Gestión de Usuarios</a></li>
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
        <a href="{{ route('login') }}" class="btn-rojo" onclick="return confirmarCerrarSesion()">
          <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
        </a>
      </li>
    </ul>
  </div>

  <!-- ===== CONTENIDO PRINCIPAL ===== -->
  <div class="main-content">
    <header>
      <h1>Gestión de Usuarios</h1>
      <a href="{{ route('home') }}" class="btn btn-primary d-flex align-items-center">
        <i class="fas fa-home me-2"></i> Inicio
      </a>
    </header>

    <main class="container">
      @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
      @endif

      <div class="d-flex justify-content-between align-items-center mb-4">
        <input type="text" id="search" class="form-control" placeholder="Buscar Usuarios" value="{{ request('search') }}" style="max-width: 400px;" />
        <div class="d-flex gap-2">
          <a href="{{ route('usuarios.pdf') }}" class="btn btn-danger">
            <i class="fas fa-file-pdf"></i> Generar Reporte
          </a>
          <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addUserModal">
            <i class="fas fa-user-plus"></i> Agregar Usuario
          </button>
        </div>
      </div>

      <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="d-flex align-items-center">
          <label class="me-2">Mostrar:</label>
          <select class="form-select form-select-sm" style="width: auto;" onchange="window.location.href='?per_page='+this.value+'&search={{ request('search') }}'">
            <option value="5" {{ request('per_page', 5) == 5 ? 'selected' : '' }}>5</option>
            <option value="10" {{ request('per_page', 5) == 10 ? 'selected' : '' }}>10</option>
            <option value="15" {{ request('per_page', 5) == 15 ? 'selected' : '' }}>15</option>
          </select>
          <span class="ms-2">registros</span>
        </div>
      </div>

      <div class="table-responsive">
        <table class="table table-bordered table-striped">
          <thead>
            <tr>
              <th>Cédula</th>
              <th>Nombres</th>
              <th>Apellidos</th>
              <th>Correo</th>
              <th>Dirección</th>
              <th>Teléfono</th>
              <th>Estado</th>
              <th>Acciones</th>
            </tr>
          </thead>
        <tbody>
          @forelse ($usuarios as $index => $usuario)
          <tr>
            <td>{{ $usuario->cedula }}</td>
            <td>{{ $usuario->nombres }}</td>
            <td>{{ $usuario->apellidos }}</td>
            <td>{{ $usuario->correo }}</td>
            <td>{{ $usuario->direccion }}</td>
            <td>{{ $usuario->telefono }}</td>
            <td>
              @if($usuario->activo)
                <span class="badge bg-success">Activo</span>
              @else
                <span class="badge bg-danger">Inactivo</span>
              @endif
            </td>
            <td>
              <button type="button" class="btn btn-warning btn-sm edit-btn"
                data-id="{{ $usuario->id }}"
                data-cedula="{{ $usuario->cedula }}"
                data-nombres="{{ $usuario->nombres }}"
                data-apellidos="{{ $usuario->apellidos }}"
                data-correo="{{ $usuario->correo }}"
                data-direccion="{{ $usuario->direccion }}"
                data-telefono="{{ $usuario->telefono }}"
                data-bs-toggle="modal" data-bs-target="#editUserModal">
                <i class="fas fa-edit"></i>
              </button>
              <form action="{{ route('usuarios.destroy', $usuario->id) }}" method="POST" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit"
                  onclick="return confirm('¿{{ $usuario->activo ? 'Desactivar' : 'Activar' }} este usuario?')"
                  class="btn btn-{{ $usuario->activo ? 'danger' : 'success' }} btn-sm">
                  <i class="fas fa-{{ $usuario->activo ? 'user-slash' : 'user-check' }}"></i>
                </button>
              </form>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="8" class="text-center">No hay usuarios registrados.</td>
          </tr>
          @endforelse
        </tbody>
      </table>
      </div>

      <div class="d-flex justify-content-center mt-4">
        {{ $usuarios->links() }}
      </div>
    </main>
        <!-- ===== FOOTER ===== -->
    <footer>
      &copy; 2025 Sistema de Gestión | PPI-ESPOMALIA
    </footer>
  </div>

  <!-- ===== MODAL: AGREGAR USUARIO ===== -->
  <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <form action="{{ route('usuarios.store') }}" method="POST" class="modal-content">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title" id="addUserModalLabel">Agregar Usuario</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
          @if ($errors->any())
            <div class="alert alert-danger">
              <ul class="mb-0">
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif
          <div class="mb-3">
            <label for="addCedula" class="form-label">Cédula</label>
            <input type="text" name="cedula" id="addCedula" class="form-control @error('cedula') is-invalid @enderror" pattern="[0-9]{10}" minlength="10" maxlength="10" value="{{ old('cedula') }}" required />
            @error('cedula')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
          <div class="mb-3">
            <label for="addNombres" class="form-label">Nombres</label>
            <input type="text" name="nombres" id="addNombres" class="form-control @error('nombres') is-invalid @enderror" value="{{ old('nombres') }}" required />
            @error('nombres')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
          <div class="mb-3">
            <label for="addApellidos" class="form-label">Apellidos</label>
            <input type="text" name="apellidos" id="addApellidos" class="form-control @error('apellidos') is-invalid @enderror" value="{{ old('apellidos') }}" required />
            @error('apellidos')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
          <div class="mb-3">
            <label for="addCorreo" class="form-label">Correo Electrónico</label>
            <input type="email" name="correo" id="addCorreo" class="form-control @error('correo') is-invalid @enderror" value="{{ old('correo') }}" required />
            @error('correo')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
          <div class="mb-3">
            <label for="addDireccion" class="form-label">Dirección</label>
            <input type="text" name="direccion" id="addDireccion" class="form-control @error('direccion') is-invalid @enderror" value="{{ old('direccion') }}" required />
            @error('direccion')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
          <div class="mb-3">
            <label for="addTelefono" class="form-label">Teléfono</label>
            <input type="text" name="telefono" id="addTelefono" class="form-control @error('telefono') is-invalid @enderror" maxlength="15" value="{{ old('telefono') }}" required />
            @error('telefono')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-success">Guardar</button>
        </div>
      </form>
    </div>
  </div>

  <!-- ===== MODAL: EDITAR USUARIO ===== -->
  <div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <form id="editUserForm" method="POST" class="modal-content">
        @csrf
        @method('PUT')
        <input type="hidden" name="id" id="editId" value="{{ old('id') }}" />
        <div class="modal-header">
          <h5 class="modal-title" id="editUserModalLabel">Editar Usuario</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
          @if ($errors->any() && old('id'))
            <div class="alert alert-danger">
              <ul class="mb-0">
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif
          <div class="mb-3">
            <label for="editCedula" class="form-label">Cédula</label>
            <input type="text" name="cedula" id="editCedula" class="form-control @error('cedula') is-invalid @enderror" pattern="[0-9]{10}" minlength="10" maxlength="10" required />
            @error('cedula')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
          <div class="mb-3">
            <label for="editNombres" class="form-label">Nombres</label>
            <input type="text" name="nombres" id="editNombres" class="form-control @error('nombres') is-invalid @enderror" required />
            @error('nombres')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
          <div class="mb-3">
            <label for="editApellidos" class="form-label">Apellidos</label>
            <input type="text" name="apellidos" id="editApellidos" class="form-control @error('apellidos') is-invalid @enderror" required />
            @error('apellidos')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
          <div class="mb-3">
            <label for="editCorreo" class="form-label">Correo</label>
            <input type="email" name="correo" id="editCorreo" class="form-control @error('correo') is-invalid @enderror" required />
            @error('correo')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
          <div class="mb-3">
            <label for="editDireccion" class="form-label">Dirección</label>
            <input type="text" name="direccion" id="editDireccion" class="form-control @error('direccion') is-invalid @enderror" required />
            @error('direccion')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
          <div class="mb-3">
            <label for="editTelefono" class="form-label">Teléfono</label>
            <input type="text" name="telefono" id="editTelefono" class="form-control @error('telefono') is-invalid @enderror" maxlength="15" required />
            @error('telefono')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
          <div class="form-check">
            <input type="checkbox" name="activo" id="editActivo" class="form-check-input" />
            <label for="editActivo" class="form-check-label">Usuario Activo</label>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary">Actualizar</button>
        </div>
      </form>
    </div>
  </div>

  <!-- ===== SCRIPTS ===== -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Abrir modal si hay errores de validación
    @if ($errors->any())
      @if(old('id'))
        // Si hay un ID, es del formulario de edición
        var editUserModal = new bootstrap.Modal(document.getElementById('editUserModal'));
        // Cargar los datos del usuario que se estaba editando
        document.getElementById('editUserForm').action = `/usuarios/{{ old('id') }}`;
        document.getElementById('editId').value = '{{ old('id') }}';
        document.getElementById('editCedula').value = '{{ old('cedula') }}';
        document.getElementById('editNombres').value = '{{ old('nombres') }}';
        document.getElementById('editApellidos').value = '{{ old('apellidos') }}';
        document.getElementById('editCorreo').value = '{{ old('correo') }}';
        document.getElementById('editDireccion').value = '{{ old('direccion') }}';
        document.getElementById('editTelefono').value = '{{ old('telefono') }}';
        document.getElementById('editActivo').checked = {{ old('activo') ? 'true' : 'false' }};
        editUserModal.show();
      @else
        // Si no hay ID, es del formulario de agregar
        var addUserModal = new bootstrap.Modal(document.getElementById('addUserModal'));
        addUserModal.show();
      @endif
    @endif

    function toggleMenu() {
      document.querySelector('.sidebar').classList.toggle('active');
    }

    // Cerrar menú al hacer clic fuera de él
    document.addEventListener('click', function(event) {
        const sidebar = document.querySelector('.sidebar');
        const menuToggle = document.querySelector('.menu-toggle');
        
        if (!sidebar.contains(event.target) && !menuToggle.contains(event.target)) {
            sidebar.classList.remove('active');
        }
    });

    document.querySelectorAll('.edit-btn').forEach(button => {
      button.addEventListener('click', () => {
        const id = button.dataset.id;
        document.getElementById('editUserForm').action = `/usuarios/${id}`;
        document.getElementById('editId').value = id;
        document.getElementById('editCedula').value = button.dataset.cedula;
        document.getElementById('editNombres').value = button.dataset.nombres;
        document.getElementById('editApellidos').value = button.dataset.apellidos;
        document.getElementById('editCorreo').value = button.dataset.correo;
        document.getElementById('editDireccion').value = button.dataset.direccion;
        document.getElementById('editTelefono').value = button.dataset.telefono;
        document.getElementById('editActivo').checked = button.closest('tr').querySelector('.badge').classList.contains('bg-success');
      });
    });

    function confirmarCerrarSesion() {
      return confirm('¿Está seguro que desea cerrar sesión?');
    }

    // Búsqueda en tiempo real
    const searchInput = document.getElementById('search');
    let searchTimeout;
    
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            const perPage = '{{ request("per_page", 5) }}';
            const searchValue = this.value;
            window.location.href = `?per_page=${perPage}&search=${searchValue}`;
        }, 500);
    });
  </script>
</body>
</html>

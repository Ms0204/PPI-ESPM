<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Gestión de Permisos</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet" />
</head>
<body>
    <button class="menu-toggle" onclick="toggleMenu()"><i class="fas fa-bars"></i></button>
    <div class="sidebar">
    <div class="logo">
        <img src="{{ asset('static/Img/logo_espomalia.png') }}" alt="Logo del Sistema" />
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
            <li><a href="{{ route('login') }}" class="btn-rojo" onclick="return confirmarCerrarSesion()">
                <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
            </a></li>
        </ul>
    </div>
    <div class="main-content">
        <header>
            <h1>Gestión de Permisos</h1>
            <a href="{{route('home')}}" class="btn btn-primary d-flex align-items-center">
                <i class="fas fa-home me-2"></i> Inicio
            </a>
        </header>
        <main class="container">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            <div class="d-flex justify-content-between align-items-center mb-4">
                <input type="text" id="search" class="form-control" placeholder="Buscar Permisos" />
                <div class="d-flex gap-2">
                    <a href="{{ route('permisos.pdf') }}" class="btn btn-danger">
                        <i class="fas fa-file-pdf"></i> Generar Reporte
                    </a>
                    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addPermisoModal">
                        <i class="fas fa-user-plus"></i> Agregar Permiso
                    </button>
                </div>
            </div>
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="d-flex align-items-center">
                    <label class="me-2">Mostrar:</label>
                    <select class="form-select form-select-sm" style="width: auto;" onchange="window.location.href='?per_page='+this.value">
                        <option value="5" {{ request('per_page', 5) == 5 ? 'selected' : '' }}>5</option>
                        <option value="10" {{ request('per_page', 5) == 10 ? 'selected' : '' }}>10</option>
                        <option value="15" {{ request('per_page', 5) == 15 ? 'selected' : '' }}>15</option>
                    </select>
                    <span class="ms-2">registros</span>
                </div>
            </div>
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Id</th>
                        <th>Fecha Asignación</th>
                        <th>Cédula Usuario</th>
                        <th>Id Rol</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($permisos as $index => $permiso)
                        <tr>
                            <td>{{ $permisos->total() - ($permisos->firstItem() + $index) + 1 }}</td>
                            <td>{{ 'PR-' . str_pad($permiso->id, 3, '0', STR_PAD_LEFT) }}</td>
                            <td>{{ date('Y-m-d', strtotime($permiso->fechaAsignacion)) }}</td>
                            <td>{{ $permiso->cedulaUsuario }}</td>
                            <td>{{ 'RL-' . str_pad($permiso->idRol, 3, '0', STR_PAD_LEFT) }}</td>
                            <td>
                                @if($permiso->estado == 'Activo')
                                    <span class="badge bg-success">Activo</span>
                                @else
                                    <span class="badge bg-danger">Inactivo</span>
                                @endif
                            </td>
                            <td>
                                <button type="button" class="btn btn-warning btn-sm edit-btn"
                                    data-id="{{ $permiso->id }}"
                                    data-fecha="{{ date('Y-m-d', strtotime($permiso->fechaAsignacion)) }}"
                                    data-estado="{{ $permiso->estado }}"
                                    data-cedula="{{ $permiso->cedulaUsuario }}"
                                    data-rol="{{ $permiso->idRol }}"
                                    data-bs-toggle="modal"
                                    data-bs-target="#editPermisoModal">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="{{ route('permisos.destroy', $permiso->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        onclick="return confirm('¿{{ $permiso->estado == 'Activo' ? 'Desactivar' : 'Activar' }} este permiso?')"
                                        class="btn btn-{{ $permiso->estado == 'Activo' ? 'danger' : 'success' }} btn-sm">
                                        <i class="fas fa-{{ $permiso->estado == 'Activo' ? 'user-slash' : 'user-check' }}"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">No hay permisos registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="d-flex justify-content-center mt-4">
                {{ $permisos->links() }}
            </div>
        </main>
                <!-- ===== FOOTER ===== -->
    <footer>
        &copy; 2025 Sistema de Gestión | PPI-ESPOMALIA
    </footer>
    </div>
    <!-- Modal Agregar Permiso -->
    <div class="modal fade" id="addPermisoModal" tabindex="-1" aria-labelledby="addPermisoModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('permisos.store') }}" method="POST" class="modal-content">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Agregar Permiso</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    {{-- ID se genera automáticamente y se muestra como PR-001, PR-002, ... --}}
                    <div class="mb-3">
                        <label for="fechaAsignacion" class="form-label">Fecha Asignación</label>
                        <input type="date" name="fechaAsignacion" class="form-control" required />
                    </div>
                    <div class="mb-3">
                        <label for="estado" class="form-label">Estado</label>
                        <input type="text" name="estado" class="form-control" required />
                    </div>
                    <div class="mb-3">
                        <label for="cedulaUsuario" class="form-label">Cédula Usuario</label>
                        <select name="cedulaUsuario" id="cedulaUsuario" class="form-control" required>
                            <option value="">Seleccione usuario</option>
                            @foreach($usuarios as $u)
                                <option value="{{ $u->cedula }}">{{ $u->cedula }} - {{ $u->nombres ?? '' }} {{ $u->apellidos ?? '' }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="idRol" class="form-label">Rol</label>
                        <select name="idRol" id="idRol" class="form-control" required>
                            <option value="">Seleccione rol</option>
                            @foreach($roles as $r)
                                <option value="{{ $r->id }}">RL-{{ str_pad($r->id,3,'0',STR_PAD_LEFT) }} - {{ $r->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">Guardar</button>
                </div>
            </form>
        </div>
    </div>
    <!-- Modal Editar Permiso -->
    <div class="modal fade" id="editPermisoModal" tabindex="-1" aria-labelledby="editPermisoModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="editPermisoForm" method="POST" class="modal-content">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Editar Permiso</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="editIdDisplay" class="form-label">ID</label>
                        <input type="text" name="id_display" id="editIdDisplay" class="form-control" readonly />
                        <div class="form-text">El ID se genera automáticamente y no se puede editar.</div>
                    </div>
                    <div class="mb-3">
                        <label for="editFechaAsignacion" class="form-label">Fecha Asignación</label>
                        <input type="date" name="fechaAsignacion" id="editFechaAsignacion" class="form-control" required />
                    </div>
                    <div class="mb-3">
                        <label for="editEstado" class="form-label">Estado</label>
                        <input type="text" name="estado" id="editEstado" class="form-control" required />
                    </div>
                    <div class="mb-3">
                        <label for="editCedulaUsuario" class="form-label">Cédula Usuario</label>
                        <select name="cedulaUsuario" id="editCedulaUsuario" class="form-control" required>
                            <option value="">Seleccione usuario</option>
                            @foreach($usuarios as $u)
                                <option value="{{ $u->cedula }}">{{ $u->cedula }} - {{ $u->nombres ?? '' }} {{ $u->apellidos ?? '' }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="editIdRol" class="form-label">Rol</label>
                        <select name="idRol" id="editIdRol" class="form-control" required>
                            <option value="">Seleccione rol</option>
                            @foreach($roles as $r)
                                <option value="{{ $r->id }}">RL-{{ str_pad($r->id,3,'0',STR_PAD_LEFT) }} - {{ $r->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Actualizar</button>
                </div>
            </form>
        </div>
    </div>
    <script>
        document.querySelectorAll('.edit-btn').forEach(button => {
            button.addEventListener('click', () => {
                const id = button.dataset.id;
                const fecha = button.dataset.fecha;
                const estado = button.dataset.estado;
                const cedula = button.dataset.cedula;
                const rol = button.dataset.rol;

                document.getElementById('editIdDisplay').value = 'PR-' + String(id).padStart(3,'0');
                document.getElementById('editFechaAsignacion').value = fecha;
                document.getElementById('editEstado').value = estado;
                // preselect user
                const userSelect = document.getElementById('editCedulaUsuario');
                Array.from(userSelect.options).forEach(opt => { opt.selected = (opt.value === cedula); });
                // preselect role
                const rolSelect = document.getElementById('editIdRol');
                Array.from(rolSelect.options).forEach(opt => { opt.selected = (opt.value === rol); });

                document.getElementById('editPermisoForm').action = `/permisos/${id}`;
            });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Gestión de Pagos</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet" />
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet" />
</head>
<body>
  <!-- ===== BOTÓN MENÚ RESPONSIVO ===== -->
  <button class="menu-toggle" onclick="toggleMenu()">
    <i class="fas fa-bars"></i>
  </button>
    <!-- Barra lateral -->
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
            <li>
                <a href="{{ route('login') }}" class="btn-rojo" onclick="return confirmarCerrarSesion()">
                    <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                </a>
            </li>
        </ul>
    </div>

    <div class="main-content">
        <header>
            <h1>Gestión de Pagos</h1>
            <a href="{{ route('home') }}" class="btn btn-primary d-flex align-items-center">
                <i class="fas fa-home me-2"></i> Inicio
            </a>
        </header>

        <main class="container">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="d-flex justify-content-between align-items-center mb-4">
                <input type="text" id="search" class="form-control" placeholder="Buscar Pagos" value="{{ request('search') }}" style="max-width: 400px;" />
                <div class="d-flex gap-2">
                    <a href="{{ route('pagos.pdf') }}" class="btn btn-danger">
                        <i class="fas fa-file-pdf"></i> Generar Reporte
                    </a>
                    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addPagoModal">
                        <i class="fas fa-user-plus"></i> Agregar Pago
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
                            <th>ID</th>
                            <th>Número Pago</th>
                            <th>Método</th>
                            <th>Cantidad</th>
                            <th>Fecha</th>
                            <th>Usuario</th>
                            <th>Observación</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                <tbody>
                    @forelse ($pagos as $index => $pago)
                        <tr>
                            <td>{{ str_pad($pago->id, 3, '0', STR_PAD_LEFT) }}</td>
                            <td>{{ $pago->numeroPago }}</td>
                            <td>{{ $pago->metodoPago }}</td>
                            <td>{{ $pago->cantidad }}</td>
                            <td>{{ date('Y-m-d', strtotime($pago->fechaPago)) }}</td>
                            <td>{{ $pago->usuario->nombres ?? 'N/A' }} {{ $pago->usuario->apellidos ?? '' }} - {{ $pago->cedulaUsuario }}</td>
                            <td>{{ $pago->observaciones ?? '-' }}</td>
                            <td>
                                <button type="button" class="btn btn-warning btn-sm edit-btn"
                                    data-id="{{ $pago->id }}"
                                    data-numeropago="{{ $pago->numeroPago }}"
                                    data-metodopago="{{ $pago->metodoPago }}"
                                    data-cantidad="{{ $pago->cantidad }}"
                                    data-fechapago="{{ $pago->fechaPago }}"
                                    data-cedulausuario="{{ $pago->cedulaUsuario }}"
                                    data-observaciones="{{ $pago->observaciones ?? '' }}"
                                    data-bs-toggle="modal"
                                    data-bs-target="#editPagoModal">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="{{ route('pagos.destroy', $pago->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('¿Eliminar este pago?')" class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">No hay pagos registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            </div>

            <div class="d-flex justify-content-center mt-4">
                {{ $pagos->links() }}
            </div>
        </main>
            <!-- ===== FOOTER ===== -->
    <footer>
        &copy; 2025 Sistema de Gestión | PPI-ESPOMALIA
    </footer>
    </div>

    <!-- Modal Agregar Pago -->
    <div class="modal fade" id="addPagoModal" tabindex="-1" aria-labelledby="addPagoModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('pagos.store') }}" method="POST" class="modal-content">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="addPagoModalLabel">Agregar Pago</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    {{-- ID se genera automáticamente, no pedir al usuario --}}
                    <div class="mb-3">
                        <label for="numeroPago" class="form-label">Número Pago</label>
                        <input type="text" name="numeroPago" class="form-control" required />
                    </div>
                    <div class="mb-3">
                        <label for="metodoPago" class="form-label">Método de Pago</label>
                        <select name="metodoPago" id="metodoPago" class="form-control" required>
                            <option value="">Seleccione método</option>
                            <option value="efectivo">Efectivo</option>
                            <option value="transferencia">Transferencia</option>
                            <option value="cheque">Cheque</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="cantidad" class="form-label">Cantidad</label>
                        <input type="number" step="0.01" name="cantidad" class="form-control" required />
                    </div>
                    <div class="mb-3">
                        <label for="fechaPago" class="form-label">Fecha de Pago</label>
                        <input type="date" name="fechaPago" class="form-control" required />
                    </div>
                    <div class="mb-3">
                        <label for="cedulaUsuario" class="form-label">Usuario</label>
                        <select name="cedulaUsuario" id="cedulaUsuario" class="form-control" required>
                            <option value="">Seleccione un usuario</option>
                            @foreach($usuarios as $usuario)
                                <option value="{{ $usuario->cedula }}">{{ $usuario->nombres }} {{ $usuario->apellidos }} - {{ $usuario->cedula }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="observaciones" class="form-label">Observaciones</label>
                        <textarea name="observaciones" id="observaciones" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">Guardar</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Editar Pago -->
    <div class="modal fade" id="editPagoModal" tabindex="-1" aria-labelledby="editPagoModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" class="modal-content" id="editPagoForm">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title" id="editPagoModalLabel">Editar Pago</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="editId" class="form-label">ID</label>
                        <input type="text" name="id" id="editId" class="form-control" readonly />
                        <div class="form-text">El ID se genera automáticamente y no se puede editar.</div>
                    </div>
                    <div class="mb-3">
                        <label for="editNumeroPago" class="form-label">Número Pago</label>
                        <input type="text" name="numeroPago" id="editNumeroPago" class="form-control" required />
                    </div>
                    <div class="mb-3">
                        <label for="editMetodoPago" class="form-label">Método de Pago</label>
                        <select name="metodoPago" id="editMetodoPago" class="form-control" required>
                            <option value="">Seleccione método</option>
                            <option value="efectivo">Efectivo</option>
                            <option value="transferencia">Transferencia</option>
                            <option value="cheque">Cheque</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="editCantidad" class="form-label">Cantidad</label>
                        <input type="number" step="0.01" name="cantidad" id="editCantidad" class="form-control" required />
                    </div>
                    <div class="mb-3">
                        <label for="editFechaPago" class="form-label">Fecha de Pago</label>
                        <input type="date" name="fechaPago" id="editFechaPago" class="form-control" required />
                    </div>
                    <div class="mb-3">
                        <label for="editCedulaUsuario" class="form-label">Usuario</label>
                        <select name="cedulaUsuario" id="editCedulaUsuario" class="form-control" required>
                            <option value="">Seleccione un usuario</option>
                            @foreach($usuarios as $usuario)
                                <option value="{{ $usuario->cedula }}">{{ $usuario->nombres }} {{ $usuario->apellidos }} - {{ $usuario->cedula }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="editObservaciones" class="form-label">Observaciones</label>
                        <textarea name="observaciones" id="editObservaciones" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Actualizar</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.querySelectorAll('.edit-btn').forEach(button => {
            button.addEventListener('click', () => {
                const id = button.dataset.id;
                document.getElementById('editPagoForm').action = `/pagos/${id}`;
                document.getElementById('editId').value = id;
                document.getElementById('editNumeroPago').value = button.dataset.numeropago;

                // set metodoPago select
                const metodo = button.dataset.metodopago ? button.dataset.metodopago.toLowerCase() : '';
                const metodoSelect = document.getElementById('editMetodoPago');
                Array.from(metodoSelect.options).forEach(option => {
                    option.selected = (option.value === metodo);
                });

                document.getElementById('editCantidad').value = button.dataset.cantidad;
                document.getElementById('editFechaPago').value = button.dataset.fechapago;

                // set usuario select
                const cedula = button.dataset.cedulausuario || '';
                const usuarioSelect = document.getElementById('editCedulaUsuario');
                Array.from(usuarioSelect.options).forEach(option => {
                    option.selected = (option.value === cedula);
                });

                // observaciones (if dataset has it)
                if (button.dataset.observaciones !== undefined) {
                    document.getElementById('editObservaciones').value = button.dataset.observaciones;
                } else {
                    document.getElementById('editObservaciones').value = '';
                }
            });
        });

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

        // Función para abrir/cerrar menú en móviles
        function toggleMenu() {
            const sidebar = document.querySelector('.sidebar');
            sidebar.classList.toggle('active');
        }

        // Cerrar menú al hacer clic fuera de él
        document.addEventListener('click', function(event) {
            const sidebar = document.querySelector('.sidebar');
            const menuToggle = document.querySelector('.menu-toggle');
            
            if (!sidebar.contains(event.target) && !menuToggle.contains(event.target)) {
                sidebar.classList.remove('active');
            }
        });
    </script>
</body>
</html>
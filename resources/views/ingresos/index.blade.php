<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Gestión de Ingresos</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet" />
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
            <li>
                <a href="{{ route('login') }}" class="btn-rojo" onclick="return confirmarCerrarSesion()">
                    <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                </a>
            </li>
        </ul>
    </div>

    <div class="main-content">
        <header>
            <h1>Gestión de Ingresos</h1>
            <a href="{{ route('home') }}" class="btn btn-primary d-flex align-items-center">
                <i class="fas fa-home me-2"></i> Inicio
            </a>
        </header>

        <main class="container">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="d-flex justify-content-between align-items-center mb-4">
                <input type="text" id="search" class="form-control" placeholder="Buscar Ingresos" value="{{ request('search') }}" style="max-width: 400px;" />
                <div class="d-flex gap-2">
                    <a href="{{ route('ingresos.pdf') }}" class="btn btn-danger">
                        <i class="fas fa-file-pdf"></i> Generar Reporte
                    </a>
                    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addIngresoModal">
                        <i class="fas fa-user-plus"></i> Agregar Ingresos
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
                            <th>#</th>
                            <th>Id</th>
                            <th>Cantidad</th>
                            <th>Fecha Ingreso</th>
                            <th>Producto</th>
                            <th>Código Inventario</th>
                            <th>Observación</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                <tbody>
                    @forelse ($ingresos as $index => $ingreso)
                        <tr>
                            <td>{{ $ingresos->total() - ($ingresos->firstItem() + $index) + 1 }}</td>
                            <td>{{ $ingreso->id }}</td>
                            <td>{{ $ingreso->cantidad }}</td>
                            <td>{{ date('Y-m-d', strtotime($ingreso->fechaIngreso)) }}</td>
                            <td>{{ $ingreso->producto ? $ingreso->producto->nombre : 'N/A' }}</td>
                            <td>{{ $ingreso->codigoInventario }}</td>
                            <td>{{ $ingreso->observacion ?? '-' }}</td>
                            <td>
                                <button
                                    type="button"
                                    class="btn btn-warning btn-sm edit-btn"
                                    data-id="{{ $ingreso->id }}"
                                    data-cantidad="{{ $ingreso->cantidad }}"
                                    data-fechaingreso="{{ $ingreso->fechaIngreso }}"
                                    data-idproducto="{{ $ingreso->idProducto }}"
                                    data-codigoinventario="{{ $ingreso->codigoInventario }}"
                                    data-observacion="{{ $ingreso->observacion }}"
                                    data-bs-toggle="modal"
                                    data-bs-target="#editIngresoModal"
                                >
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="{{ route('ingresos.destroy', $ingreso->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('¿Eliminar este ingreso?')" class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">No hay ingresos registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            </div>

            <div class="d-flex justify-content-center mt-4">
                {{ $ingresos->links() }}
            </div>
        </main>
            <!-- ===== FOOTER ===== -->
    <footer>
        &copy; 2025 Sistema de Gestión | PPI-ESPOMALIA
    </footer>
    </div>

    <!-- Modal Agregar Ingreso -->
    <div class="modal fade" id="addIngresoModal" tabindex="-1" aria-labelledby="addIngresoModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('ingresos.store') }}" method="POST" class="modal-content">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="addIngresoModalLabel">Agregar Ingreso</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    {{-- ID se genera automáticamente y se muestra como IG-01, IG-02, ... --}}
                    <div class="mb-3">
                        <label for="cantidad" class="form-label">Cantidad</label>
                        <input type="number" name="cantidad" class="form-control" required />
                    </div>
                    <div class="mb-3">
                        <label for="fechaIngreso" class="form-label">Fecha Ingreso</label>
                        <input type="date" name="fechaIngreso" class="form-control" required />
                    </div>
                    <div class="mb-3">
                        <label for="idProducto" class="form-label">Producto</label>
                        <select name="idProducto" id="idProducto" class="form-control" required>
                            <option value="">Seleccione un producto</option>
                            @foreach($productos as $producto)
                                <option value="{{ $producto->id }}">{{ str_pad($producto->id,3,'0',STR_PAD_LEFT) }} - {{ $producto->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="codigoInventario" class="form-label">Código Inventario</label>
                        <select name="codigoInventario" id="codigoInventario" class="form-control" required>
                            <option value="">Seleccione código de inventario</option>
                            @foreach($inventarios as $inv)
                                <option value="{{ $inv->codigo }}">{{ $inv->codigo }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="observacion" class="form-label">Observación</label>
                        <textarea name="observacion" id="observacion" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">Guardar</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Editar Ingreso -->
    <div class="modal fade" id="editIngresoModal" tabindex="-1" aria-labelledby="editIngresoModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="editIngresoForm" method="POST" class="modal-content">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" id="editId" />
                <div class="modal-header">
                    <h5 class="modal-title" id="editIngresoModalLabel">Editar Ingreso</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="editId" class="form-label">ID</label>
                        <input type="text" name="id_display" id="editIdDisplay" class="form-control" readonly />
                        <div class="form-text">El ID se genera automáticamente y no se puede editar.</div>
                    </div>
                    <div class="mb-3">
                        <label for="editCantidad" class="form-label">Cantidad</label>
                        <input type="number" name="cantidad" id="editCantidad" class="form-control" required />
                    </div>
                    <div class="mb-3">
                        <label for="editFechaIngreso" class="form-label">Fecha Ingreso</label>
                        <input type="date" name="fechaIngreso" id="editFechaIngreso" class="form-control" required />
                    </div>
                    <div class="mb-3">
                        <label for="editIdProducto" class="form-label">Producto</label>
                        <select name="idProducto" id="editIdProducto" class="form-control" required>
                            <option value="">Seleccione un producto</option>
                            @foreach($productos as $producto)
                                <option value="{{ $producto->id }}">{{ str_pad($producto->id,3,'0',STR_PAD_LEFT) }} - {{ $producto->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="editCodigoInventario" class="form-label">Código Inventario</label>
                        <select name="codigoInventario" id="editCodigoInventario" class="form-control" required>
                            <option value="">Seleccione código de inventario</option>
                            @foreach($inventarios as $inv)
                                <option value="{{ $inv->codigo }}">{{ $inv->codigo }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="editObservacion" class="form-label">Observación</label>
                        <textarea name="observacion" id="editObservacion" class="form-control" rows="3"></textarea>
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
                const cantidad = button.dataset.cantidad;
                const fecha = button.dataset.fechaingreso;
                const idProducto = button.dataset.idproducto;
                const codigo = button.dataset.codigoinventario;
                const observacion = button.dataset.observacion;

                // display IG- prefix with padded id
                document.getElementById('editId').value = id;
                document.getElementById('editIdDisplay').value = 'IG-' + String(id).padStart(2,'0');
                document.getElementById('editCantidad').value = cantidad;
                document.getElementById('editFechaIngreso').value = fecha;
                document.getElementById('editObservacion').value = observacion || '';

                // set producto select
                const productoSelect = document.getElementById('editIdProducto');
                Array.from(productoSelect.options).forEach(option => {
                    option.selected = (option.value === idProducto);
                });

                // set inventario select
                const invSelect = document.getElementById('editCodigoInventario');
                Array.from(invSelect.options).forEach(option => {
                    option.selected = (option.value === codigo);
                });

                document.getElementById('editIngresoForm').action = `/ingresos/${id}`;
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
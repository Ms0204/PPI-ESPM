<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Gestión de Reportes</title>
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
            <li>
                <a href="{{ route('login') }}" class="btn-rojo" onclick="return confirmarCerrarSesion()">
                    <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                </a>
            </li>
        </ul>
    </div>

    <div class="main-content">
        <header>
            <h1>Gestión de Reportes</h1>
            <a href="{{ route('home') }}" class="btn btn-primary d-flex align-items-center">
                <i class="fas fa-home me-2"></i> Inicio
            </a>
        </header>

        <main class="container">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="d-flex justify-content-between align-items-center mb-4">
                <input type="text" id="search" class="form-control" placeholder="Buscar Reportes" />
                <div class="d-flex gap-2">
                    <a href="{{ route('reportes.pdf') }}" class="btn btn-danger">
                        <i class="fas fa-file-pdf"></i> Generar Reporte
                    </a>
                    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addReporteModal">
                        <i class="fas fa-user-plus"></i> Agregar Reporte
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
                        <th>ID</th>
                        <th>Título</th>
                        <th>Descripción</th>
                        <th>Fecha Emisión</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($reportes as $index => $reporte)
                        <tr>
                            <td>{{ $reportes->total() - ($reportes->firstItem() + $index) + 1 }}</td>
                            <td>{{ str_pad($reporte->id, 4, '0', STR_PAD_LEFT) }}</td>
                            <td>{{ $reporte->tituloReporte }}</td>
                            <td>{{ $reporte->descripcion }}</td>
                            <td>{{ date('Y-m-d', strtotime($reporte->fechaEmision)) }}</td>
                            <td>
                                <button
                                    type="button"
                                    class="btn btn-warning btn-sm edit-btn"
                                    data-id="{{ $reporte->id }}"
                                    data-titulo="{{ $reporte->tituloReporte }}"
                                    data-descripcion="{{ $reporte->descripcion }}"
                                    data-fecha="{{ $reporte->fechaEmision }}"
                                    data-bs-toggle="modal"
                                    data-bs-target="#editReporteModal"
                                >
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="{{ route('reportes.destroy', $reporte->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('¿Eliminar este reporte?')" class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">No hay reportes registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="d-flex justify-content-center mt-4">
                {{ $reportes->links() }}
            </div>
        </main>
            <!-- ===== FOOTER ===== -->
    <footer>
        &copy; 2025 Sistema de Gestión | PPI-ESPOMALIA
    </footer>
    </div>

    <!-- Modal Agregar Reporte -->
    <div class="modal fade" id="addReporteModal" tabindex="-1" aria-labelledby="addReporteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('reportes.store') }}" method="POST" class="modal-content">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="addReporteModalLabel">Agregar Reporte</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    {{-- El ID se genera automáticamente; no pedirlo al usuario --}}
                    <div class="mb-3">
                        <label for="addTitulo" class="form-label">Título</label>
                        <input type="text" name="tituloReporte" id="addTitulo" class="form-control" required />
                    </div>
                    <div class="mb-3">
                        <label for="addDescripcion" class="form-label">Descripción</label>
                        <input type="text" name="descripcion" id="addDescripcion" class="form-control" required />
                    </div>
                    <div class="mb-3">
                        <label for="addFechaEmision" class="form-label">Fecha Emisión</label>
                        <input type="date" name="fechaEmision" id="addFechaEmision" class="form-control" required />
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">Guardar</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Editar Reporte -->
    <div class="modal fade" id="editReporteModal" tabindex="-1" aria-labelledby="editReporteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="editReporteForm" method="POST" class="modal-content">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title" id="editReporteModalLabel">Editar Reporte</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="editId" class="form-label">ID</label>
                        <input type="text" name="id" id="editId" class="form-control" readonly />
                        <div class="form-text">El ID se genera automáticamente y no se puede editar.</div>
                    </div>
                    <div class="mb-3">
                        <label for="editTitulo" class="form-label">Título</label>
                        <input type="text" name="tituloReporte" id="editTitulo" class="form-control" required />
                    </div>
                    <div class="mb-3">
                        <label for="editDescripcion" class="form-label">Descripción</label>
                        <input type="text" name="descripcion" id="editDescripcion" class="form-control" required />
                    </div>
                    <div class="mb-3">
                        <label for="editFechaEmision" class="form-label">Fecha Emisión</label>
                        <input type="date" name="fechaEmision" id="editFechaEmision" class="form-control" required />
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
                const id = button.getAttribute('data-id');
                const titulo = button.getAttribute('data-titulo');
                const descripcion = button.getAttribute('data-descripcion');
                const fecha = button.getAttribute('data-fecha');

                document.getElementById('editId').value = id;
                document.getElementById('editTitulo').value = titulo;
                document.getElementById('editDescripcion').value = descripcion;
                document.getElementById('editFechaEmision').value = fecha;

                document.getElementById('editReporteForm').action = `/reportes/${id}`;
            });
        });
    </script>
</body>
</html>
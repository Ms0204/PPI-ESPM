<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Categoria</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
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
            <h1>Gestión de Categoria</h1>
            <a href="{{route('home')}}" class="btn btn-primary d-flex align-items-center">
                <i class="fas fa-home me-2"></i> Inicio
            </a>
        </header>
        <main class="container">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            <div class="d-flex justify-content-between align-items-center mb-4">
                <input type="text" id="search" class="form-control" placeholder="Buscar Categoria" aria-label="Buscar Categoria">
                <div class="d-flex gap-2">
                    <a href="{{ route('categorias.pdf') }}" class="btn btn-danger">
                        <i class="fas fa-file-pdf"></i> Generar Reporte
                    </a>
                    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addCategoriaModal">
                        <i class="fas fa-user-plus"></i> Agregar Categoria
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
                        <th>Nombre</th>
                        <th>Descripcion</th>
                        <th>Id Producto</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($categorias as $index => $categoria)
                        <tr>
                            <td>{{ $categoria->id }}</td>
                            <td>{{ 'CTG-' . str_pad($categoria->id, 2, '0', STR_PAD_LEFT) }}</td>
                            <td>{{ $categoria->nombre }}</td>
                            <td>{{ $categoria->descripcion }}</td>
                            <td>{{ $categoria->idProducto }}</td>
                            <td>
                                <button
                                    type="button"
                                    class="btn btn-warning btn-sm edit-btn"
                                    data-id="{{ $categoria->id }}"
                                    data-nombre="{{ $categoria->nombre }}"
                                    data-descripcion="{{ $categoria->descripcion }}"
                                    data-idproducto="{{ $categoria->idProducto }}"
                                    data-bs-toggle="modal"
                                    data-bs-target="#editCategoriaModal"
                                >
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="{{ route('categorias.destroy', $categoria->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('¿Eliminar esta categoría?')" class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">No hay categorías registradas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="d-flex justify-content-center mt-4">
                {{ $categorias->links() }}
            </div>
        </main>
                    <!-- ===== FOOTER ===== -->
    <footer>
        &copy; 2025 Sistema de Gestión | PPI-ESPOMALIA
    </footer>
    </div>
    <!-- Modal Agregar Categoria -->
    <div class="modal fade" id="addCategoriaModal" tabindex="-1" aria-labelledby="addCategoriaModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('categorias.store') }}" method="POST" class="modal-content">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="addCategoriaModalLabel">Agregar Categoria</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    {{-- ID se genera automáticamente y se muestra como CTG-01, CTG-02, ... --}}
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre</label>
                        <input type="text" name="nombre" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripcion</label>
                        <input type="text" name="descripcion" class="form-control" required>
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
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">Guardar</button>
                </div>
            </form>
        </div>
    </div>
    <!-- Modal Editar Categoria -->
    <div class="modal fade" id="editCategoriaModal" tabindex="-1" aria-labelledby="editCategoriaModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="editCategoriaForm" method="POST" class="modal-content">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" id="editId">
                <div class="modal-header">
                    <h5 class="modal-title" id="editCategoriaModalLabel">Editar Categoria</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="editIdDisplay" class="form-label">ID</label>
                        <input type="text" name="id_display" id="editIdDisplay" class="form-control" readonly>
                        <div class="form-text">El ID se genera automáticamente y no se puede editar.</div>
                    </div>
                    <div class="mb-3">
                        <label for="editNombre" class="form-label">Nombre</label>
                        <input type="text" name="nombre" id="editNombre" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="editDescripcion" class="form-label">Descripcion</label>
                        <input type="text" name="descripcion" id="editDescripcion" class="form-control" required>
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
                const nombre = button.dataset.nombre;
                const descripcion = button.dataset.descripcion;
                const idProducto = button.dataset.idproducto;

                // display CTG- prefix with padded id
                document.getElementById('editId').value = id;
                document.getElementById('editIdDisplay').value = 'CTG-' + String(id).padStart(2,'0');
                document.getElementById('editNombre').value = nombre;
                document.getElementById('editDescripcion').value = descripcion;

                // set producto select
                const productoSelect = document.getElementById('editIdProducto');
                Array.from(productoSelect.options).forEach(option => {
                    option.selected = (option.value === idProducto);
                });

                document.getElementById('editCategoriaForm').action = `/categorias/${id}`;
            });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
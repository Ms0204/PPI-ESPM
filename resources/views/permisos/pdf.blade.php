<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Permisos</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; margin: 20px; }
        .header { text-align: center; margin-bottom: 30px; }
        .header h1 { color: #004d80; margin: 0; font-size: 24px; }
        .header p { margin: 5px 0; color: #666; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th { background-color: #004d80; color: white; padding: 10px; text-align: left; font-weight: bold; }
        td { padding: 8px; border-bottom: 1px solid #ddd; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        .badge { padding: 3px 8px; border-radius: 3px; font-size: 10px; font-weight: bold; }
        .badge-success { background-color: #28a745; color: white; }
        .badge-danger { background-color: #dc3545; color: white; }
        .footer { margin-top: 30px; text-align: center; font-size: 10px; color: #666; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Reporte de Permisos</h1>
        <p>Sistema de Gestión | PPI-ESPOMALIA</p>
        <p>Fecha de generación: {{ date('d/m/Y') }}</p>
    </div>
    <table>
        <thead>
            <tr><th>ID</th><th>Fecha Asignación</th><th>Usuario</th><th>Rol</th><th>Estado</th></tr>
        </thead>
        <tbody>
            @foreach($permisos as $index => $permiso)
            <tr>
                <td>{{ 'PR-' . str_pad($permiso->id, 3, '0', STR_PAD_LEFT) }}</td>
                <td>{{ \Carbon\Carbon::parse($permiso->fechaAsignacion)->format('d/m/Y') }}</td>
                <td>{{ $permiso->cedulaUsuario }} - {{ $permiso->usuario->nombres ?? 'N/A' }} {{ $permiso->usuario->apellidos ?? '' }}</td>
                <td>{{ 'RL-' . str_pad($permiso->idRol, 3, '0', STR_PAD_LEFT) }} - {{ $permiso->rol->nombre ?? 'N/A' }}</td>
                <td><span class="badge badge-{{ $permiso->estado == 'Activo' ? 'success' : 'danger' }}">{{ $permiso->estado }}</span></td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="footer">
        <p>&copy; 2025 Sistema de Gestión | PPI-ESPOMALIA</p>
        <p>Total de permisos: {{ count($permisos) }}</p>
    </div>
</body>
</html>

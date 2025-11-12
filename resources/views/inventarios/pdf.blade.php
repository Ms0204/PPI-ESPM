<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Inventarios</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; margin: 20px; }
        .header { text-align: center; margin-bottom: 30px; }
        .header h1 { color: #004d80; margin: 0; font-size: 24px; }
        .header p { margin: 5px 0; color: #666; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th { background-color: #004d80; color: white; padding: 10px; text-align: left; font-weight: bold; }
        td { padding: 8px; border-bottom: 1px solid #ddd; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        .footer { margin-top: 30px; text-align: center; font-size: 10px; color: #666; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Reporte de Inventarios</h1>
        <p>Sistema de Gestión | PPI-ESPOMALIA</p>
        <p>Fecha de generación: {{ date('d/m/Y H:i:s') }}</p>
    </div>
    <table>
        <thead>
            <tr><th>#</th><th>Código</th><th>Tipo</th><th>Cantidad</th><th>Fecha</th><th>Usuario</th></tr>
        </thead>
        <tbody>
            @foreach($inventarios as $index => $inventario)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $inventario->codigo }}</td>
                <td>{{ ucfirst($inventario->tipoMovimiento) }}</td>
                <td>{{ $inventario->cantidadProductos }}</td>
                <td>{{ \Carbon\Carbon::parse($inventario->fechaRegistro)->format('d/m/Y') }}</td>
                <td>{{ $inventario->usuario->nombres ?? 'N/A' }} {{ $inventario->usuario->apellidos ?? '' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="footer">
        <p>&copy; 2025 Sistema de Gestión | PPI-ESPOMALIA</p>
        <p>Total de inventarios: {{ count($inventarios) }}</p>
    </div>
</body>
</html>

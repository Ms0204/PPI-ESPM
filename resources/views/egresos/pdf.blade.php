<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Egresos</title>
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
        <h1>Reporte de Egresos</h1>
        <p>Sistema de Gestión | PPI-ESPOMALIA</p>
        <p>Fecha de generación: {{ \Carbon\Carbon::now('America/Guayaquil')->format('d/m/Y') }}</p>
    </div>
    <table>
        <thead>
            <tr><th>ID</th><th>Producto</th><th>Cantidad</th><th>Fecha</th><th>Inventario</th><th>Observación</th></tr>
        </thead>
        <tbody>
            @foreach($egresos as $index => $egreso)
            <tr>
                <td>{{ 'EG-' . str_pad($egreso->id, 3, '0', STR_PAD_LEFT) }}</td>
                <td>{{ str_pad($egreso->idProducto, 3, '0', STR_PAD_LEFT) }} - {{ $egreso->producto->nombre ?? 'N/A' }}</td>
                <td>{{ $egreso->cantidad }}</td>
                <td>{{ \Carbon\Carbon::parse($egreso->fechaEgreso)->format('d/m/Y') }}</td>
                <td>{{ $egreso->inventario->codigo ?? 'N/A' }}</td>
                <td>{{ $egreso->observacion ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="footer">
        <p>&copy; 2025 Sistema de Gestión | PPI-ESPOMALIA</p>
        <p>Total de egresos: {{ count($egresos) }} | Cantidad total: {{ $egresos->sum('cantidad') }}</p>
    </div>
</body>
</html>

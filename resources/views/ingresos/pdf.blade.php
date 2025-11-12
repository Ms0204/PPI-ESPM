<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Ingresos</title>
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
        <h1>Reporte de Ingresos</h1>
        <p>Sistema de Gestión | PPI-ESPOMALIA</p>
        <p>Fecha de generación: {{ date('d/m/Y H:i:s') }}</p>
    </div>
    <table>
        <thead>
            <tr><th>#</th><th>ID</th><th>Producto</th><th>Cantidad</th><th>Fecha</th><th>Inventario</th></tr>
        </thead>
        <tbody>
            @foreach($ingresos as $index => $ingreso)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ 'IG-' . str_pad($ingreso->id, 2, '0', STR_PAD_LEFT) }}</td>
                <td>{{ $ingreso->producto->nombre ?? 'N/A' }}</td>
                <td>{{ $ingreso->cantidad }}</td>
                <td>{{ \Carbon\Carbon::parse($ingreso->fechaIngreso)->format('d/m/Y') }}</td>
                <td>{{ $ingreso->inventario->codigo ?? 'N/A' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="footer">
        <p>&copy; 2025 Sistema de Gestión | PPI-ESPOMALIA</p>
        <p>Total de ingresos: {{ count($ingresos) }} | Cantidad total: {{ $ingresos->sum('cantidad') }}</p>
    </div>
</body>
</html>

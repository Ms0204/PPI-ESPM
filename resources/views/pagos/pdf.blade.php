<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Pagos</title>
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
        <h1>Reporte de Pagos</h1>
        <p>Sistema de Gestión | PPI-ESPOMALIA</p>
        <p>Fecha de generación: {{ date('d/m/Y') }}</p>
    </div>
    <table>
        <thead>
            <tr><th>ID</th><th>Número</th><th>Método</th><th>Cantidad</th><th>Fecha</th><th>Usuario</th></tr>
        </thead>
        <tbody>
            @foreach($pagos as $index => $pago)
            <tr>
                <td>{{ str_pad($pago->id, 3, '0', STR_PAD_LEFT) }}</td>
                <td>{{ $pago->numeroPago }}</td>
                <td>{{ ucfirst($pago->metodoPago) }}</td>
                <td>${{ number_format($pago->cantidad, 2) }}</td>
                <td>{{ \Carbon\Carbon::parse($pago->fechaPago)->format('d/m/Y') }}</td>
                <td>{{ $pago->usuario->nombres ?? 'N/A' }} {{ $pago->usuario->apellidos ?? '' }} - {{ $pago->cedulaUsuario }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="footer">
        <p>&copy; 2025 Sistema de Gestión | PPI-ESPOMALIA</p>
        <p>Total de pagos: {{ count($pagos) }} | Total: ${{ number_format($pagos->sum('cantidad'), 2) }}</p>
    </div>
</body>
</html>

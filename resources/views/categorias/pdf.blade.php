<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Categorías</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #004d80;
            margin: 0;
            font-size: 24px;
        }
        .header p {
            margin: 5px 0;
            color: #666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th {
            background-color: #004d80;
            color: white;
            padding: 10px;
            text-align: left;
            font-weight: bold;
        }
        td {
            padding: 8px;
            border-bottom: 1px solid #ddd;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Reporte de Categorías</h1>
        <p>Sistema de Gestión | PPI-ESPOMALIA</p>
        <p>Fecha de generación: {{ date('d/m/Y H:i:s') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>ID</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Producto</th>
                <th>Fecha de Creación</th>
            </tr>
        </thead>
        <tbody>
            @foreach($categorias as $index => $categoria)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ 'CTG-' . str_pad($categoria->id, 2, '0', STR_PAD_LEFT) }}</td>
                <td>{{ $categoria->nombre }}</td>
                <td>{{ $categoria->descripcion }}</td>
                <td>{{ $categoria->producto->nombre ?? 'N/A' }}</td>
                <td>{{ $categoria->created_at->format('d/m/Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>&copy; 2025 Sistema de Gestión | PPI-ESPOMALIA</p>
        <p>Total de categorías: {{ count($categorias) }}</p>
    </div>
</body>
</html>

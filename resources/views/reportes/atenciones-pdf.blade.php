<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Atenciones</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            font-size: 12px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .header h1 {
            color: #333;
            font-size: 24px;
            margin-bottom: 5px;
        }
        .header p {
            color: #666;
            font-size: 14px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th {
            background-color: #4a5568;
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
            background-color: #f8f9fa;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
        @media print {
            body {
                print-color-adjust: exact;
                -webkit-print-color-adjust: exact;
            }
            .no-print {
                display: none;
            }
        }
        .print-button {
            margin-bottom: 20px;
            padding: 10px 20px;
            background-color: #4a5568;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
        }
        .print-button:hover {
            background-color: #2d3748;
        }
    </style>
</head>
<body>
    <button class="print-button no-print" onclick="window.print()">Imprimir / Guardar como PDF</button>
    
    <div class="header">
        <h1>Reporte de Atenciones Médicas</h1>
        <p>Clínica Veterinaria Huellitas</p>
        <p>Generado el: {{ date('d/m/Y H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Mascota</th>
                <th>Propietario</th>
                <th>Veterinario</th>
                <th>Motivo</th>
                <th>Diagnóstico</th>
                <th>Tratamiento</th>
            </tr>
        </thead>
        <tbody>
            @forelse($fichas as $ficha)
            <tr>
                <td>{{ \Carbon\Carbon::parse($ficha->fecha_visita)->format('d/m/Y') }}</td>
                <td>{{ $ficha->mascota->nombre ?? 'N/A' }}</td>
                <td>{{ $ficha->mascota->propietario->nombre_completo ?? 'N/A' }}</td>
                <td>{{ $ficha->veterinario->nombre ?? 'N/A' }}</td>
                <td>{{ Str::limit($ficha->motivo, 50) }}</td>
                <td>{{ Str::limit($ficha->diagnostico, 50) }}</td>
                <td>{{ Str::limit($ficha->tratamiento, 50) }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align: center;">No hay registros para mostrar</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Total de registros: {{ count($fichas) }}</p>
        <p>Clínica Veterinaria Huellitas - Todos los derechos reservados</p>
    </div>
</body>
</html>


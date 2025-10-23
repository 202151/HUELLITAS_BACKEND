<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Citas</title>
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
        .estado {
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 11px;
            font-weight: bold;
        }
        .estado-programada { background-color: #fbbf24; color: #78350f; }
        .estado-confirmada { background-color: #60a5fa; color: #1e3a8a; }
        .estado-completada { background-color: #34d399; color: #064e3b; }
        .estado-cancelada { background-color: #f87171; color: #7f1d1d; }
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
        <h1>Reporte de Citas</h1>
        <p>Clínica Veterinaria Huellitas</p>
        <p>Generado el: {{ date('d/m/Y H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Fecha/Hora</th>
                <th>Mascota</th>
                <th>Propietario</th>
                <th>Servicio</th>
                <th>Veterinario</th>
                <th>Estado</th>
                <th>Monto</th>
            </tr>
        </thead>
        <tbody>
            @forelse($citas as $cita)
            <tr>
                <td>{{ \Carbon\Carbon::parse($cita->fecha_cita)->format('d/m/Y H:i') }}</td>
                <td>{{ $cita->mascota->nombre ?? 'N/A' }}</td>
                <td>{{ $cita->mascota->propietario->nombre_completo ?? 'N/A' }}</td>
                <td>{{ $cita->servicio->nombre_servicio ?? 'N/A' }}</td>
                <td>{{ $cita->veterinario->nombre ?? 'N/A' }}</td>
                <td>
                    <span class="estado estado-{{ $cita->estado }}">
                        {{ ucfirst($cita->estado) }}
                    </span>
                </td>
                <td>${{ number_format($cita->monto_total ?? 0, 2) }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align: center;">No hay registros para mostrar</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Total de registros: {{ count($citas) }}</p>
        <p>Monto total: ${{ number_format($citas->sum('monto_total'), 2) }}</p>
        <p>Clínica Veterinaria Huellitas - Todos los derechos reservados</p>
    </div>
</body>
</html>


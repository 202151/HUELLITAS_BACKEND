<!-- resources/views/citas/vista_previa.blade.php -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vista Previa - Cita #{{ str_pad($cita->id_citas, 5, '0', STR_PAD_LEFT) }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            padding: 20px;
        }
        
        .toolbar {
            background: white;
            padding: 15px 20px;
            margin-bottom: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .toolbar h2 {
            color: #333;
            font-size: 18px;
        }
        
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            text-decoration: none;
            display: inline-block;
            margin-left: 10px;
            transition: all 0.3s;
        }
        
        .btn-primary {
            background: #2E75B6;
            color: white;
        }
        
        .btn-primary:hover {
            background: #1d5a8f;
        }
        
        .btn-secondary {
            background: #6c757d;
            color: white;
        }
        
        .btn-secondary:hover {
            background: #545b62;
        }
        
        .btn-success {
            background: #28a745;
            color: white;
        }
        
        .btn-success:hover {
            background: #218838;
        }
        
        .documento {
            background: white;
            max-width: 210mm;
            margin: 0 auto;
            padding: 40px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            min-height: 297mm;
        }
        
        .header {
            text-align: center;
            border-bottom: 3px solid #2E75B6;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        
        .header h1 {
            color: #2E75B6;
            font-size: 24px;
            margin-bottom: 10px;
        }
        
        .header p {
            color: #666;
            font-size: 14px;
        }
        
        .seccion-titulo {
            background: #2E75B6;
            color: white;
            padding: 10px 15px;
            margin: 30px 0 15px 0;
            font-size: 16px;
            font-weight: bold;
            border-radius: 4px;
        }
        
        .tabla-info {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        .tabla-info tr {
            border-bottom: 1px solid #ddd;
        }
        
        .tabla-info td {
            padding: 12px;
            vertical-align: top;
        }
        
        .tabla-info td:first-child {
            background: #f8f9fa;
            font-weight: bold;
            width: 35%;
            color: #333;
        }
        
        .tabla-info td:last-child {
            color: #555;
        }
        
        .estado {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 14px;
        }
        
        .estado-programada {
            background: #d4edda;
            color: #155724;
        }
        
        .estado-reprogramada {
            background: #fff3cd;
            color: #856404;
        }
        
        .estado-cancelada {
            background: #f8d7da;
            color: #721c24;
        }
        
        .notas-box {
            background: #f8f9fa;
            border-left: 4px solid #2E75B6;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        
        .importante-box {
            background: #fff3cd;
            border: 2px solid #ffc107;
            padding: 20px;
            margin: 30px 0;
            border-radius: 8px;
        }
        
        .importante-box h3 {
            color: #856404;
            margin-bottom: 15px;
            font-size: 16px;
        }
        
        .importante-box ul {
            margin-left: 20px;
            color: #333;
        }
        
        .importante-box li {
            margin-bottom: 8px;
            line-height: 1.6;
        }
        
        .footer {
            text-align: center;
            color: #999;
            font-size: 12px;
            margin-top: 50px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
        }
        
        .id-badge {
            background: #e3f2fd;
            color: #1976d2;
            padding: 5px 15px;
            border-radius: 20px;
            font-weight: bold;
            display: inline-block;
        }
        
        @media print {
            body {
                background: white;
                padding: 0;
            }
            
            .toolbar {
                display: none;
            }
            
            .documento {
                box-shadow: none;
                margin: 0;
                padding: 20mm;
            }
        }
        
        @media (max-width: 768px) {
            .documento {
                padding: 20px;
            }
            
            .toolbar {
                flex-direction: column;
                gap: 10px;
            }
            
            .toolbar h2 {
                margin-bottom: 10px;
            }
        }
    </style>
</head>
<body>
    <!-- Barra de herramientas -->
    <div class="toolbar">
        <h2>Vista Previa del Reporte</h2>
        <div>
            <a href="{{ route('citas.index') }}" class="btn btn-secondary">
                ← Volver
            </a>
            <button onclick="window.print()" class="btn btn-success">
                🖨️ Imprimir
            </button>
            <a href="{{ route('citas.reporte.individual', $cita->id_citas) }}" class="btn btn-primary">
                📄 Descargar Word
            </a>
        </div>
    </div>
    
    <!-- Documento -->
    <div class="documento">
        <!-- Encabezado -->
        <div class="header">
            <h1>REPORTE DE CITA</h1>
            <p style="font-size: 18px; font-weight: bold; margin-top: 10px;">
                Sistema de Gestión de Citas
            </p>
            <p>Reporte Generado Automáticamente</p>
        </div>
        
        <!-- Información de la cita -->
        <div class="seccion-titulo">INFORMACIÓN DE LA CITA</div>
        
        <table class="tabla-info">
            <tr>
                <td>ID de Cita:</td>
                <td>
                    <span class="id-badge">CITA-{{ str_pad($cita->id_citas, 5, '0', STR_PAD_LEFT) }}</span>
                </td>
            </tr>
            <tr>
                <td>Nombre del Cliente:</td>
                <td><strong>{{ $cita->nombre_cliente }}</strong></td>
            </tr>
            <tr>
                <td>Servicio:</td>
                <td>{{ $cita->servicio ?? 'No especificado' }}</td>
            </tr>
            <tr>
                <td>Fecha y Hora:</td>
                <td>
                    <strong style="font-size: 16px; color: #2E75B6;">
                        {{ \Carbon\Carbon::parse($cita->fecha)->format('d/m/Y h:i A') }}
                    </strong>
                </td>
            </tr>
            <tr>
                <td>Estado:</td>
                <td>
                    <span class="estado estado-{{ $cita->estado }}">
                        {{ ucfirst($cita->estado) }}
                    </span>
                </td>
            </tr>
            <tr>
                <td>Registrada el:</td>
                <td>{{ \Carbon\Carbon::parse($cita->creado_en)->format('d/m/Y h:i A') }}</td>
            </tr>
        </table>
        
        <!-- Notas -->
        @if($cita->notas)
        <div class="seccion-titulo">NOTAS ADICIONALES</div>
        <div class="notas-box">
            {{ $cita->notas }}
        </div>
        @endif
        
        <!-- Información importante -->
        <div class="importante-box">
            <h3>⚠️ IMPORTANTE</h3>
            <ul>
                <li>Por favor, llegue 10 minutos antes de su cita.</li>
                <li>Si necesita cancelar o reprogramar, comuníquese con anticipación.</li>
                <li>Traiga este documento el día de su cita.</li>
                <li>Para cualquier consulta, contáctenos al teléfono indicado.</li>
            </ul>
        </div>
        
        <!-- Pie de página -->
        <div class="footer">
            <p>Documento generado el {{ now()->format('d/m/Y H:i:s') }}</p>
            <p>Este documento es válido como comprobante de cita</p>
        </div>
    </div>
</body>
</html>
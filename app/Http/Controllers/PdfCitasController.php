<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use TCPDF;

class PdfCitasController extends Controller
{
    public function reporteCitaVeterinaria($id)
    {
        // Datos de ejemplo - en producción vendrían de la base de datos
        // $cita = Cita::with(['mascota', 'propietario', 'veterinario'])->findOrFail($id);
        
        $cita = [
            'id' => 1,
            'fecha' => '2025-11-05',
            'hora' => '10:30',
            'mascota' => [
                'nombre' => 'Luna',
                'especie' => 'Perro',
                'raza' => 'Golden Retriever',
                'edad' => '3 años',
                'peso' => '28 kg',
                'sexo' => 'Hembra',
                'color' => 'Dorado'
            ],
            'propietario' => [
                'nombre' => 'María González Pérez',
                'telefono' => '987-654-321',
                'email' => 'maria.gonzalez@email.com',
                'direccion' => 'Av. Principal #123, Lima'
            ],
            'veterinario' => [
                'nombre' => 'Dr. Carlos Ramírez Torres',
                'especialidad' => 'Medicina General Veterinaria',
                'colegiatura' => 'CMV-12345'
            ],
            'motivo' => 'Control de vacunación y chequeo general',
            'sintomas' => 'Mascota en buen estado general, presenta apetito normal y actividad regular.',
            'diagnostico' => 'Estado de salud óptimo. Se procede con vacunación antirrábica de refuerzo.',
            'tratamiento' => 'Vacuna antirrábica aplicada. Se recomienda desparasitación en 15 días.',
            'observaciones' => 'Mantener dieta balanceada. Próximo control en 6 meses.',
            'proxima_cita' => '2025-05-05',
            'estado' => 'Completada'
        ];

        $fechaReporte = date('d/m/Y H:i');
        
        $html = '
        <style>
            h1 { 
                color: #2c3e50; 
                text-align: center; 
                margin-bottom: 5px; 
                font-size: 20px;
            }
            h2 { 
                color: #3498db; 
                font-size: 14px; 
                margin-top: 15px; 
                margin-bottom: 8px;
                border-bottom: 2px solid #3498db;
                padding-bottom: 3px;
            }
            .header-info { 
                text-align: center; 
                color: #7f8c8d; 
                margin-bottom: 20px; 
                font-size: 10px;
            }
            .section {
                margin-bottom: 15px;
            }
            .info-box {
                background-color: #f8f9fa;
                border-left: 4px solid #3498db;
                padding: 10px;
                margin-bottom: 10px;
            }
            .label {
                color: #7f8c8d;
                font-weight: bold;
                display: inline-block;
                width: 120px;
            }
            .value {
                color: #2c3e50;
            }
            .mascota-header {
                background: linear-gradient(to right, #3498db, #2ecc71);
                color: white;
                padding: 10px;
                border-radius: 5px;
                margin-bottom: 10px;
                text-align: center;
            }
            table {
                width: 100%;
                margin-top: 10px;
            }
            .footer {
                margin-top: 30px;
                text-align: center;
                padding-top: 15px;
                border-top: 2px solid #ecf0f1;
                font-size: 9px;
                color: #95a5a6;
            }
            .firma {
                margin-top: 40px;
                text-align: center;
            }
            .linea-firma {
                border-top: 1px solid #000;
                width: 200px;
                margin: 0 auto;
                margin-top: 50px;
            }
        </style>
        
        <h1>🐾 REPORTE DE CITA VETERINARIA 🐾</h1>
        <p class="header-info">
            <strong>N° Cita:</strong> ' . str_pad($cita['id'], 6, '0', STR_PAD_LEFT) . ' | 
            <strong>Fecha:</strong> ' . date('d/m/Y', strtotime($cita['fecha'])) . ' | 
            <strong>Hora:</strong> ' . $cita['hora'] . '<br>
            <strong>Estado:</strong> ' . $cita['estado'] . ' | 
            <strong>Generado:</strong> ' . $fechaReporte . '
        </p>
        
        <div class="mascota-header">
            <h2 style="margin:0; color:white; border:none;">INFORMACIÓN DE LA MASCOTA</h2>
        </div>
        
        <div class="info-box">
            <table cellpadding="3">
                <tr>
                    <td><span class="label">Nombre:</span> <span class="value">' . htmlspecialchars($cita['mascota']['nombre']) . '</span></td>
                    <td><span class="label">Especie:</span> <span class="value">' . htmlspecialchars($cita['mascota']['especie']) . '</span></td>
                </tr>
                <tr>
                    <td><span class="label">Raza:</span> <span class="value">' . htmlspecialchars($cita['mascota']['raza']) . '</span></td>
                    <td><span class="label">Edad:</span> <span class="value">' . htmlspecialchars($cita['mascota']['edad']) . '</span></td>
                </tr>
                <tr>
                    <td><span class="label">Sexo:</span> <span class="value">' . htmlspecialchars($cita['mascota']['sexo']) . '</span></td>
                    <td><span class="label">Peso:</span> <span class="value">' . htmlspecialchars($cita['mascota']['peso']) . '</span></td>
                </tr>
                <tr>
                    <td colspan="2"><span class="label">Color:</span> <span class="value">' . htmlspecialchars($cita['mascota']['color']) . '</span></td>
                </tr>
            </table>
        </div>
        
        <h2>DATOS DEL PROPIETARIO</h2>
        <div class="info-box">
            <p><span class="label">Nombre:</span> <span class="value">' . htmlspecialchars($cita['propietario']['nombre']) . '</span></p>
            <p><span class="label">Teléfono:</span> <span class="value">' . htmlspecialchars($cita['propietario']['telefono']) . '</span></p>
            <p><span class="label">Email:</span> <span class="value">' . htmlspecialchars($cita['propietario']['email']) . '</span></p>
            <p><span class="label">Dirección:</span> <span class="value">' . htmlspecialchars($cita['propietario']['direccion']) . '</span></p>
        </div>
        
        <h2>VETERINARIO TRATANTE</h2>
        <div class="info-box">
            <p><span class="label">Nombre:</span> <span class="value">' . htmlspecialchars($cita['veterinario']['nombre']) . '</span></p>
            <p><span class="label">Especialidad:</span> <span class="value">' . htmlspecialchars($cita['veterinario']['especialidad']) . '</span></p>
            <p><span class="label">Colegiatura:</span> <span class="value">' . htmlspecialchars($cita['veterinario']['colegiatura']) . '</span></p>
        </div>
        
        <h2>MOTIVO DE CONSULTA</h2>
        <div class="info-box">
            <p>' . nl2br(htmlspecialchars($cita['motivo'])) . '</p>
        </div>
        
        <h2>SÍNTOMAS Y EXAMEN FÍSICO</h2>
        <div class="info-box">
            <p>' . nl2br(htmlspecialchars($cita['sintomas'])) . '</p>
        </div>
        
        <h2>DIAGNÓSTICO</h2>
        <div class="info-box">
            <p>' . nl2br(htmlspecialchars($cita['diagnostico'])) . '</p>
        </div>
        
        <h2>TRATAMIENTO Y MEDICACIÓN</h2>
        <div class="info-box">
            <p>' . nl2br(htmlspecialchars($cita['tratamiento'])) . '</p>
        </div>
        
        <h2>OBSERVACIONES Y RECOMENDACIONES</h2>
        <div class="info-box">
            <p>' . nl2br(htmlspecialchars($cita['observaciones'])) . '</p>
        </div>
        
        <div class="info-box" style="background-color:#e8f5e9; border-left-color:#2ecc71;">
            <p><span class="label">Próxima Cita:</span> <span class="value" style="color:#27ae60; font-weight:bold;">' . date('d/m/Y', strtotime($cita['proxima_cita'])) . '</span></p>
        </div>
        
        <div class="firma">
            <div class="linea-firma"></div>
            <p style="margin-top:5px; font-size:10px;">
                <strong>' . htmlspecialchars($cita['veterinario']['nombre']) . '</strong><br>
                ' . htmlspecialchars($cita['veterinario']['colegiatura']) . '
            </p>
        </div>
        
        <div class="footer">
            <p>Este documento es un registro médico veterinario oficial</p>
            <p>Clínica Veterinaria - Cuidamos a tu mejor amigo</p>
        </div>';

        // Crear PDF
        $pdf = new TCPDF();
        $pdf->SetCreator('Sistema Veterinario');
        $pdf->SetAuthor('Clínica Veterinaria');
        $pdf->SetTitle('Reporte Cita - ' . $cita['mascota']['nombre']);
        $pdf->SetSubject('Historial Clínico Veterinario');
        
        // Configuración de página
        $pdf->SetMargins(15, 15, 15);
        $pdf->SetAutoPageBreak(true, 15);
        $pdf->SetFont('dejavusans', '', 9);
        
        // Agregar página
        $pdf->AddPage();
        
        // Escribir contenido HTML
        $pdf->writeHTML($html, true, false, true, false, '');

        // Generar PDF
        $nombreArchivo = 'cita-veterinaria-' . $cita['mascota']['nombre'] . '-' . date('Ymd') . '.pdf';
        $pdfContent = $pdf->Output($nombreArchivo, 'S');
        
        return response($pdfContent, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $nombreArchivo . '"',
        ]);
    }
}
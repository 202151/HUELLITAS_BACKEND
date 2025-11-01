<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use TCPDF;

class PdfCitasController extends Controller
{
    public function reporteCitas()
    {
        $veterinaria = 'Huellitas Clínica Veterinaria';
        $fechaReporte = date('d/m/Y');
        $periodo = 'Octubre 2025';
        
        // Datos de ejemplo - reemplaza con tu consulta a la BD
        $citas = [
            [
                'fecha' => '2025-10-28',
                'hora' => '09:00',
                'mascota' => 'Max',
                'especie' => 'Perro',
                'propietario' => 'Juan Pérez',
                'servicio' => 'Consulta general',
                'veterinario' => 'Dr. García',
                'estado' => 'Completada'
            ],
            
                   
            [
                'fecha' => '2025-10-31',
                'hora' => '09:30',
                'mascota' => 'Toby',
                'especie' => 'Perro',
                'propietario' => 'Luis Mendoza',
                'servicio' => 'Desparasitación',
                'veterinario' => 'Dr. García',
                'estado' => 'Confirmada'
            ],
        ];

        // Calcular estadísticas
        $totalCitas = count($citas);
        $completadas = 0;
        $pendientes = 0;
        $confirmadas = 0;
        
        foreach ($citas as $c) {
            if ($c['estado'] === 'Completada') $completadas++;
            if ($c['estado'] === 'Pendiente') $pendientes++;
            if ($c['estado'] === 'Confirmada') $confirmadas++;
        }

        // Generar filas de la tabla
        $rows = '';
        foreach ($citas as $cita) {
            $fechaFormato = date('d/m/Y', strtotime($cita['fecha']));
            
            // Color según estado
            $colorEstado = '#ffffff';
            if ($cita['estado'] === 'Completada') $colorEstado = '#d4edda';
            if ($cita['estado'] === 'Confirmada') $colorEstado = '#d1ecf1';
            if ($cita['estado'] === 'Pendiente') $colorEstado = '#fff3cd';
            if ($cita['estado'] === 'Cancelada') $colorEstado = '#f8d7da';
            
            $rows .= '<tr>
                <td style="text-align:center;">' . htmlspecialchars($fechaFormato) . '</td>
                <td style="text-align:center;">' . htmlspecialchars($cita['hora']) . '</td>
                <td>' . htmlspecialchars($cita['mascota']) . '</td>
                <td style="text-align:center;">' . htmlspecialchars($cita['especie']) . '</td>
                <td>' . htmlspecialchars($cita['propietario']) . '</td>
                <td>' . htmlspecialchars($cita['servicio']) . '</td>
                <td>' . htmlspecialchars($cita['veterinario']) . '</td>
                <td style="text-align:center; background-color:' . $colorEstado . ';">
                    <strong>' . htmlspecialchars($cita['estado']) . '</strong>
                </td>
            </tr>';
        }

        $html = '
        <style>
            h1 { color: #2c5f7c; margin: 0 0 5px 0; text-align: center; }
            h2 { color: #4a90a4; font-size: 14px; margin: 5px 0; text-align: center; }
            .info-box { background-color: #f8f9fa; padding: 10px; margin: 15px 0; }
            th { background-color: #2c5f7c; color: white; font-weight: bold; }
        </style>
        
        <h1>' . htmlspecialchars($veterinaria) . '</h1>
        <h2>Reporte de Citas - ' . htmlspecialchars($periodo) . '</h2>
        <p style="text-align:center; margin: 5px 0; font-size: 10px; color: #666;">
            Generado el ' . htmlspecialchars($fechaReporte) . '
        </p>

        <div class="info-box">
            <strong>Resumen:</strong> 
            Total: <strong>' . $totalCitas . '</strong> | 
            Completadas: <strong>' . $completadas . '</strong> | 
            Confirmadas: <strong>' . $confirmadas . '</strong> | 
            Pendientes: <strong>' . $pendientes . '</strong>
        </div>

        <table border="1" cellpadding="5" cellspacing="0" style="width:100%;">
            <thead>
                <tr>
                    <th style="text-align:center;">Fecha</th>
                    <th style="text-align:center;">Hora</th>
                    <th>Mascota</th>
                    <th style="text-align:center;">Especie</th>
                    <th>Propietario</th>
                    <th>Servicio</th>
                    <th>Veterinario</th>
                    <th style="text-align:center;">Estado</th>
                </tr>
            </thead>
            <tbody>' . $rows . '</tbody>
        </table>

        <p style="margin-top: 20px; font-size: 9px; color: #666; text-align: center;">
            Este reporte contiene información confidencial de ' . htmlspecialchars($veterinaria) . '
        </p>';

        // Crear PDF con TCPDF
        $pdf = new \TCPDF('L', 'mm', 'A4', true, 'UTF-8');
        $pdf->SetCreator('Laravel');
        $pdf->SetAuthor($veterinaria);
        $pdf->SetTitle('Reporte de Citas - ' . $periodo);
        $pdf->SetMargins(15, 20, 15);
        $pdf->SetAutoPageBreak(true, 20);
        $pdf->AddPage();
        $pdf->SetFont('dejavusans', '', 9);
        $pdf->writeHTML($html, true, false, true, false, '');
        $pdfContent = $pdf->Output('reporte-citas-' . date('Y-m-d') . '.pdf', 'S');
        
        return response($pdfContent, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="reporte-citas-' . date('Y-m-d') . '.pdf"',
        ]);
    }
}
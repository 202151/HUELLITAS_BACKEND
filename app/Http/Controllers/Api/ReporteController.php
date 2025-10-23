<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cita;
use App\Models\Propietario;
use App\Models\Mascota;
use App\Models\FichaClinica;
use App\Models\Vacuna;
use App\Models\Servicio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class ReporteController extends Controller
{
    public function reporteCitas(Request $request)
    {
        $query = Cita::with(['mascota.propietario', 'servicio', 'veterinario']);
        
        if ($request->has('fecha_inicio')) {
            $query->where('fecha_cita', '>=', $request->fecha_inicio);
        }
        
        if ($request->has('fecha_fin')) {
            $query->where('fecha_cita', '<=', $request->fecha_fin);
        }
        
        if ($request->has('estado')) {
            $query->where('estado', $request->estado);
        }
        
        $citas = $query->orderBy('fecha_cita', 'desc')->get();
        
        if ($request->has('formato') && $request->formato === 'pdf') {
            return $this->generarPDFCitas($citas);
        }
        
        return $this->generarCSV($this->prepararDatosCitas($citas), 'reporte_citas_' . date('Y-m-d') . '.csv');
    }

    public function reporteAtenciones(Request $request)
    {
        $query = FichaClinica::with(['mascota.propietario', 'veterinario']);
        
        if ($request->has('fecha_inicio')) {
            $query->where('fecha_visita', '>=', $request->fecha_inicio);
        }
        
        if ($request->has('fecha_fin')) {
            $query->where('fecha_visita', '<=', $request->fecha_fin);
        }
        
        if ($request->has('id_mascota')) {
            $query->where('id_mascota', $request->id_mascota);
        }
        
        $fichas = $query->orderBy('fecha_visita', 'desc')->get();
        
        if ($request->has('formato') && $request->formato === 'pdf') {
            return $this->generarPDFAtenciones($fichas);
        }
        
        return $this->generarCSV($this->prepararDatosAtenciones($fichas), 'reporte_atenciones_' . date('Y-m-d') . '.csv');
    }
    
    public function reportePropietarios()
    {
        $propietarios = Propietario::with('mascotas')->where('activo', true)->get();
        
        $datosCSV = [
            ['Nombre Completo', 'Tipo Documento', 'Número Documento', 'Teléfono', 'Correo', 'Dirección', 'Ciudad', 'Fecha Nacimiento', 'Sexo', 'Número de Mascotas', 'Notas']
        ];
        
        foreach ($propietarios as $propietario) {
            $datosCSV[] = [
                $propietario->nombre_completo,
                $propietario->tipo_documento,
                $propietario->numero_documento,
                $propietario->numero_cell,
                $propietario->correo ?? '',
                $propietario->direccion ?? '',
                $propietario->ciudad ?? '',
                $propietario->fecha_nacimiento ?? '',
                $propietario->sexo ?? '',
                $propietario->mascotas->count(),
                $propietario->notas ?? ''
            ];
        }
        
        return $this->generarCSV($datosCSV, 'reporte_propietarios_' . date('Y-m-d') . '.csv');
    }
    
    public function reporteMascotas()
    {
        $mascotas = Mascota::with('propietario')->where('activo', true)->get();
        
        $datosCSV = [
            ['Nombre', 'Especie', 'Raza', 'Sexo', 'Fecha Nacimiento', 'Peso (kg)', 'Color', 'Microchip', 'Esterilizado', 'Propietario', 'Teléfono Propietario', 'Alergias', 'Condiciones Médicas']
        ];
        
        foreach ($mascotas as $mascota) {
            $datosCSV[] = [
                $mascota->nombre,
                $mascota->especie,
                $mascota->raza ?? '',
                $mascota->sexo,
                $mascota->fecha_nacimiento ?? '',
                $mascota->peso ?? '',
                $mascota->color ?? '',
                $mascota->numero_microchip ?? '',
                $mascota->esterilizado ? 'Sí' : 'No',
                $mascota->propietario->nombre_completo ?? '',
                $mascota->propietario->numero_cell ?? '',
                $mascota->alergias ?? '',
                $mascota->condiciones_medicas ?? ''
            ];
        }
        
        return $this->generarCSV($datosCSV, 'reporte_mascotas_' . date('Y-m-d') . '.csv');
    }
    
    public function reporteVacunas(Request $request)
    {
        $query = Vacuna::with(['mascota.propietario', 'veterinario']);
        
        if ($request->has('id_mascota')) {
            $query->where('id_mascota', $request->id_mascota);
        }
        
        if ($request->has('fecha_inicio')) {
            $query->where('fecha_aplicacion', '>=', $request->fecha_inicio);
        }
        
        if ($request->has('fecha_fin')) {
            $query->where('fecha_aplicacion', '<=', $request->fecha_fin);
        }
        
        $vacunas = $query->orderBy('fecha_aplicacion', 'desc')->get();
        
        $datosCSV = [
            ['Fecha Aplicación', 'Mascota', 'Propietario', 'Veterinario', 'Tipo', 'Nombre Vacuna', 'Marca', 'Lote', 'Próxima Dosis', 'Observaciones']
        ];
        
        foreach ($vacunas as $vacuna) {
            $datosCSV[] = [
                $vacuna->fecha_aplicacion,
                $vacuna->mascota->nombre ?? '',
                $vacuna->mascota->propietario->nombre_completo ?? '',
                $vacuna->veterinario->nombre ?? '',
                $vacuna->tipo,
                $vacuna->nombre_vacuna,
                $vacuna->marca ?? '',
                $vacuna->numero_lote ?? '',
                $vacuna->fecha_proxima_dosis ?? '',
                $vacuna->observaciones ?? ''
            ];
        }
        
        return $this->generarCSV($datosCSV, 'reporte_vacunas_' . date('Y-m-d') . '.csv');
    }
    
    public function reporteServicios()
    {
        $servicios = Servicio::with(['citas' => function($query) {
            $query->where('estado', 'completada');
        }])->where('activo', true)->get();
        
        $datosCSV = [
            ['Servicio', 'Descripción', 'Precio', 'Duración (min)', 'Categoría', 'Citas Completadas', 'Ingresos Totales']
        ];
        
        foreach ($servicios as $servicio) {
            $citasCompletadas = $servicio->citas->count();
            $ingresoTotal = $servicio->citas->sum('monto_total');
            
            $datosCSV[] = [
                $servicio->nombre_servicio,
                $servicio->descripcion ?? '',
                $servicio->precio,
                $servicio->duracion_estimada,
                $servicio->categoria,
                $citasCompletadas,
                $ingresoTotal
            ];
        }
        
        return $this->generarCSV($datosCSV, 'reporte_servicios_' . date('Y-m-d') . '.csv');
    }

    private function generarPDFAtenciones($fichas)
    {
        $html = view('reportes.atenciones-pdf', compact('fichas'))->render();
        
        return Response::make($html, 200, [
            'Content-Type' => 'text/html; charset=UTF-8',
        ]);
    }

    private function generarPDFCitas($citas)
    {
        $html = view('reportes.citas-pdf', compact('citas'))->render();
        
        return Response::make($html, 200, [
            'Content-Type' => 'text/html; charset=UTF-8',
        ]);
    }

    private function prepararDatosCitas($citas)
    {
        $datos = [
            ['Fecha', 'Hora', 'Mascota', 'Propietario', 'Servicio', 'Veterinario', 'Estado', 'Duración (min)', 'Monto Total', 'Motivo', 'Notas']
        ];
        
        foreach ($citas as $cita) {
            $datos[] = [
                $cita->fecha_cita ? date('Y-m-d', strtotime($cita->fecha_cita)) : '',
                $cita->fecha_cita ? date('H:i', strtotime($cita->fecha_cita)) : '',
                $cita->mascota->nombre ?? '',
                $cita->mascota->propietario->nombre_completo ?? '',
                $cita->servicio->nombre_servicio ?? '',
                $cita->veterinario->nombre ?? '',
                $cita->estado,
                $cita->duracion_minutos,
                $cita->monto_total ?? 0,
                $cita->motivo ?? '',
                $cita->notas ?? ''
            ];
        }
        
        return $datos;
    }

    private function prepararDatosAtenciones($fichas)
    {
        $datos = [
            ['Fecha Visita', 'Mascota', 'Propietario', 'Veterinario', 'Motivo', 'Síntomas', 'Diagnóstico', 'Tratamiento', 'Medicamentos', 'Peso (kg)', 'Temperatura', 'Próxima Visita', 'Notas']
        ];
        
        foreach ($fichas as $ficha) {
            $datos[] = [
                $ficha->fecha_visita,
                $ficha->mascota->nombre ?? '',
                $ficha->mascota->propietario->nombre_completo ?? '',
                $ficha->veterinario->nombre ?? '',
                $ficha->motivo,
                $ficha->sintomas ?? '',
                $ficha->diagnostico,
                $ficha->tratamiento,
                $ficha->medicamentos ?? '',
                $ficha->peso ?? '',
                $ficha->temperatura ?? '',
                $ficha->fecha_proxima_visita ?? '',
                $ficha->notas ?? ''
            ];
        }
        
        return $datos;
    }
    
    private function generarCSV($datos, $nombreArchivo)
    {
        $output = fopen('php://temp', 'w');
        
        fwrite($output, "\xEF\xBB\xBF");
        
        foreach ($datos as $fila) {
            fputcsv($output, $fila, ';');
        }
        
        rewind($output);
        $csv = stream_get_contents($output);
        fclose($output);
        
        return Response::make($csv, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $nombreArchivo . '"',
        ]);
    }
}


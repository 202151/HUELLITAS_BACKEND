<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Owner;
use App\Models\Pet;
use App\Models\MedicalRecord;
use App\Models\Vaccination;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class ReportController extends Controller
{
    public function appointmentsReport(Request $request)
    {
        $query = Appointment::with(['pet.owner', 'service', 'veterinarian']);
        
        // Filtros opcionales
        if ($request->has('start_date')) {
            $query->where('appointment_date', '>=', $request->start_date);
        }
        
        if ($request->has('end_date')) {
            $query->where('appointment_date', '<=', $request->end_date);
        }
        
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        
        $appointments = $query->orderBy('appointment_date', 'desc')->get();
        
        $csvData = [];
        $csvData[] = [
            'Fecha',
            'Hora',
            'Mascota',
            'Propietario',
            'Servicio',
            'Veterinario',
            'Estado',
            'Duración (min)',
            'Monto Total',
            'Motivo',
            'Notas'
        ];
        
        foreach ($appointments as $appointment) {
            $csvData[] = [
                $appointment->appointment_date ? date('Y-m-d', strtotime($appointment->appointment_date)) : '',
                $appointment->appointment_date ? date('H:i', strtotime($appointment->appointment_date)) : '',
                $appointment->pet->name ?? '',
                ($appointment->pet->owner->first_name ?? '') . ' ' . ($appointment->pet->owner->last_name ?? ''),
                $appointment->service->name ?? '',
                $appointment->veterinarian->name ?? '',
                $appointment->status,
                $appointment->duration_minutes,
                $appointment->total_amount ?? 0,
                $appointment->reason ?? '',
                $appointment->notes ?? ''
            ];
        }
        
        return $this->generateCsvResponse($csvData, 'reporte_citas_' . date('Y-m-d') . '.csv');
    }
    
    public function ownersReport()
    {
        $owners = Owner::with('pets')->where('is_active', true)->get();
        
        $csvData = [];
        $csvData[] = [
            'Nombre',
            'Apellido',
            'Tipo Documento',
            'Número Documento',
            'Teléfono',
            'Email',
            'Dirección',
            'Ciudad',
            'Fecha Nacimiento',
            'Género',
            'Número de Mascotas',
            'Notas'
        ];
        
        foreach ($owners as $owner) {
            $csvData[] = [
                $owner->first_name,
                $owner->last_name,
                $owner->document_type,
                $owner->document_number,
                $owner->phone,
                $owner->email ?? '',
                $owner->address ?? '',
                $owner->city ?? '',
                $owner->birth_date ?? '',
                $owner->gender ?? '',
                $owner->pets->count(),
                $owner->notes ?? ''
            ];
        }
        
        return $this->generateCsvResponse($csvData, 'reporte_propietarios_' . date('Y-m-d') . '.csv');
    }
    
    public function petsReport()
    {
        $pets = Pet::with('owner')->where('is_active', true)->get();
        
        $csvData = [];
        $csvData[] = [
            'Nombre',
            'Especie',
            'Raza',
            'Género',
            'Fecha Nacimiento',
            'Peso (kg)',
            'Color',
            'Microchip',
            'Esterilizado',
            'Propietario',
            'Teléfono Propietario',
            'Alergias',
            'Condiciones Médicas'
        ];
        
        foreach ($pets as $pet) {
            $csvData[] = [
                $pet->name,
                $pet->species,
                $pet->breed ?? '',
                $pet->gender,
                $pet->birth_date ?? '',
                $pet->weight ?? '',
                $pet->color ?? '',
                $pet->microchip_number ?? '',
                $pet->is_sterilized ? 'Sí' : 'No',
                ($pet->owner->first_name ?? '') . ' ' . ($pet->owner->last_name ?? ''),
                $pet->owner->phone ?? '',
                $pet->allergies ?? '',
                $pet->medical_conditions ?? ''
            ];
        }
        
        return $this->generateCsvResponse($csvData, 'reporte_mascotas_' . date('Y-m-d') . '.csv');
    }
    
    public function medicalRecordsReport(Request $request)
    {
        $query = MedicalRecord::with(['pet.owner', 'veterinarian']);
        
        if ($request->has('pet_id')) {
            $query->where('pet_id', $request->pet_id);
        }
        
        if ($request->has('start_date')) {
            $query->where('visit_date', '>=', $request->start_date);
        }
        
        if ($request->has('end_date')) {
            $query->where('visit_date', '<=', $request->end_date);
        }
        
        $records = $query->orderBy('visit_date', 'desc')->get();
        
        $csvData = [];
        $csvData[] = [
            'Fecha Visita',
            'Mascota',
            'Propietario',
            'Veterinario',
            'Motivo Visita',
            'Síntomas',
            'Diagnóstico',
            'Tratamiento',
            'Medicamentos',
            'Peso (kg)',
            'Temperatura',
            'Frecuencia Cardíaca',
            'Frecuencia Respiratoria',
            'Presión Arterial',
            'Próxima Visita',
            'Notas'
        ];
        
        foreach ($records as $record) {
            $csvData[] = [
                $record->visit_date,
                $record->pet->name ?? '',
                ($record->pet->owner->first_name ?? '') . ' ' . ($record->pet->owner->last_name ?? ''),
                $record->veterinarian->name ?? '',
                $record->reason_for_visit,
                $record->symptoms ?? '',
                $record->diagnosis ?? '',
                $record->treatment ?? '',
                $record->medications ?? '',
                $record->weight ?? '',
                $record->temperature ?? '',
                $record->heart_rate ?? '',
                $record->respiratory_rate ?? '',
                $record->blood_pressure ?? '',
                $record->next_visit_date ?? '',
                $record->notes ?? ''
            ];
        }
        
        return $this->generateCsvResponse($csvData, 'reporte_registros_medicos_' . date('Y-m-d') . '.csv');
    }
    
    public function vaccinationsReport(Request $request)
    {
        $query = Vaccination::with(['pet.owner', 'veterinarian']);
        
        if ($request->has('pet_id')) {
            $query->where('pet_id', $request->pet_id);
        }
        
        if ($request->has('start_date')) {
            $query->where('vaccination_date', '>=', $request->start_date);
        }
        
        if ($request->has('end_date')) {
            $query->where('vaccination_date', '<=', $request->end_date);
        }
        
        $vaccinations = $query->orderBy('vaccination_date', 'desc')->get();
        
        $csvData = [];
        $csvData[] = [
            'Fecha Vacunación',
            'Mascota',
            'Propietario',
            'Veterinario',
            'Nombre Vacuna',
            'Tipo Vacuna',
            'Lote',
            'Fabricante',
            'Próxima Vacunación',
            'Notas'
        ];
        
        foreach ($vaccinations as $vaccination) {
            $csvData[] = [
                $vaccination->vaccination_date,
                $vaccination->pet->name ?? '',
                ($vaccination->pet->owner->first_name ?? '') . ' ' . ($vaccination->pet->owner->last_name ?? ''),
                $vaccination->veterinarian->name ?? '',
                $vaccination->vaccine_name,
                $vaccination->vaccine_type,
                $vaccination->batch_number ?? '',
                $vaccination->manufacturer ?? '',
                $vaccination->next_vaccination_date ?? '',
                $vaccination->notes ?? ''
            ];
        }
        
        return $this->generateCsvResponse($csvData, 'reporte_vacunaciones_' . date('Y-m-d') . '.csv');
    }
    
    public function servicesReport()
    {
        $services = Service::with(['appointments' => function($query) {
            $query->where('status', 'completada');
        }])->where('is_active', true)->get();
        
        $csvData = [];
        $csvData[] = [
            'Servicio',
            'Descripción',
            'Precio',
            'Duración (min)',
            'Categoría',
            'Citas Completadas',
            'Ingresos Totales'
        ];
        
        foreach ($services as $service) {
            $completedAppointments = $service->appointments->count();
            $totalRevenue = $service->appointments->sum('total_amount');
            
            $csvData[] = [
                $service->name,
                $service->description ?? '',
                $service->price,
                $service->duration_minutes,
                $service->category,
                $completedAppointments,
                $totalRevenue
            ];
        }
        
        return $this->generateCsvResponse($csvData, 'reporte_servicios_' . date('Y-m-d') . '.csv');
    }
    
    private function generateCsvResponse($data, $filename)
    {
        $output = fopen('php://temp', 'w');
        
        // Agregar BOM para UTF-8
        fwrite($output, "\xEF\xBB\xBF");
        
        foreach ($data as $row) {
            fputcsv($output, $row, ';'); // Usar punto y coma como separador
        }
        
        rewind($output);
        $csv = stream_get_contents($output);
        fclose($output);
        
        return Response::make($csv, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }
}
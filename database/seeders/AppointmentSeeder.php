<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Appointment;
use App\Models\Pet;
use App\Models\Service;
use App\Models\User;
use Carbon\Carbon;

class AppointmentSeeder extends Seeder
{
    public function run(): void
    {
        $pets = Pet::all();
        $services = Service::all();
        $veterinarians = User::whereHas('role', function($query) {
            $query->where('name', 'veterinarian');
        })->get();

        if ($pets->isEmpty() || $services->isEmpty() || $veterinarians->isEmpty()) {
            return;
        }

        $appointments = [
            [
                'pet_id' => $pets->get(0)->id,
                'service_id' => $services->where('name', 'Consulta General')->first()->id,
                'veterinarian_id' => $veterinarians->first()->id,
                'appointment_date' => Carbon::today()->addDays(1)->setTime(9, 0),
                'duration_minutes' => 30,
                'status' => 'programada',
                'reason' => 'Revisión general de salud',
                'notes' => 'Primera consulta del año',
                'total_amount' => 50.00,
            ],
            [
                'pet_id' => $pets->get(1)->id,
                'service_id' => $services->where('name', 'Vacunación')->first()->id,
                'veterinarian_id' => $veterinarians->first()->id,
                'appointment_date' => Carbon::today()->addDays(2)->setTime(10, 30),
                'duration_minutes' => 15,
                'status' => 'confirmada',
                'reason' => 'Vacuna anual',
                'notes' => 'Aplicar vacuna triple felina',
                'total_amount' => 25.00,
            ],
            [
                'pet_id' => $pets->get(2)->id,
                'service_id' => $services->where('name', 'Baño y Corte')->first()->id,
                'veterinarian_id' => $veterinarians->first()->id,
                'appointment_date' => Carbon::today()->addDays(3)->setTime(14, 0),
                'duration_minutes' => 60,
                'status' => 'programada',
                'reason' => 'Grooming mensual',
                'notes' => 'Corte especial para la raza',
                'total_amount' => 35.00,
            ],
            [
                'pet_id' => $pets->get(3)->id,
                'service_id' => $services->where('name', 'Consulta General')->first()->id,
                'veterinarian_id' => $veterinarians->first()->id,
                'appointment_date' => Carbon::yesterday()->setTime(11, 0),
                'duration_minutes' => 30,
                'status' => 'completada',
                'reason' => 'Chequeo post-esterilización',
                'notes' => 'Revisión de cicatrización',
                'total_amount' => 50.00,
                'completed_at' => Carbon::yesterday()->setTime(11, 30),
            ],
            [
                'pet_id' => $pets->get(4)->id,
                'service_id' => $services->where('name', 'Limpieza Dental')->first()->id,
                'veterinarian_id' => $veterinarians->first()->id,
                'appointment_date' => Carbon::today()->subDays(3)->setTime(15, 30),
                'duration_minutes' => 45,
                'status' => 'completada',
                'reason' => 'Limpieza dental anual',
                'notes' => 'Sarro moderado removido exitosamente',
                'total_amount' => 120.00,
                'completed_at' => Carbon::today()->subDays(3)->setTime(16, 15),
            ],
            [
                'pet_id' => $pets->get(5)->id,
                'service_id' => $services->where('name', 'Desparasitación')->first()->id,
                'veterinarian_id' => $veterinarians->first()->id,
                'appointment_date' => Carbon::today()->addDays(5)->setTime(16, 0),
                'duration_minutes' => 10,
                'status' => 'programada',
                'reason' => 'Desparasitación trimestral',
                'notes' => 'Aplicar tratamiento interno y externo',
                'total_amount' => 20.00,
            ],
            [
                'pet_id' => $pets->get(6)->id,
                'service_id' => $services->where('name', 'Vacunación')->first()->id,
                'veterinarian_id' => $veterinarians->first()->id,
                'appointment_date' => Carbon::today()->addDays(7)->setTime(9, 30),
                'duration_minutes' => 15,
                'status' => 'programada',
                'reason' => 'Primera vacuna',
                'notes' => 'Gatita joven, primera serie de vacunas',
                'total_amount' => 25.00,
            ]
        ];

        foreach ($appointments as $appointment) {
            Appointment::create($appointment);
        }
    }
}
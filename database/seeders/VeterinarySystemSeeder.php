<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Role;
use App\Models\User;
use App\Models\Service;
use App\Models\Owner;
use App\Models\Pet;
use App\Models\Appointment;
use App\Models\MedicalRecord;
use App\Models\Vaccination;

class VeterinarySystemSeeder extends Seeder
{
    public function run(): void
    {
        // Crear roles del sistema
        $adminRole = Role::firstOrCreate(
            ['name' => 'admin'],
            [
                'display_name' => 'Administrador',
                'description' => 'Acceso completo al sistema',
                'permissions' => json_encode(['all'])
            ]
        );

        $veterinarianRole = Role::firstOrCreate(
            ['name' => 'veterinarian'],
            [
                'display_name' => 'Veterinario',
                'description' => 'Acceso a funciones médicas',
                'permissions' => json_encode(['medical_records', 'vaccinations', 'appointments'])
            ]
        );

        $receptionistRole = Role::firstOrCreate(
            ['name' => 'receptionist'],
            [
                'display_name' => 'Recepcionista',
                'description' => 'Gestión de citas y propietarios',
                'permissions' => json_encode(['appointments', 'owners', 'pets'])
            ]
        );

        // Crear usuarios del sistema
        $admin = User::firstOrCreate(
            ['email' => 'admin@huellitas.com'],
            [
                'name' => 'Administrador Sistema',
                'password' => Hash::make('password'),
                'role_id' => $adminRole->id
            ]
        );

        $veterinarian = User::firstOrCreate(
            ['email' => 'veterinario@huellitas.com'],
            [
                'name' => 'Dr. María González',
                'password' => Hash::make('password'),
                'role_id' => $veterinarianRole->id
            ]
        );

        $receptionist = User::firstOrCreate(
            ['email' => 'recepcion@huellitas.com'],
            [
                'name' => 'Ana Recepcionista',
                'password' => Hash::make('password'),
                'role_id' => $receptionistRole->id
            ]
        );

        // Crear servicios
        $services = [
            [
                'name' => 'Consulta General',
                'description' => 'Examen médico general de la mascota',
                'category' => 'consulta',
                'price' => 50000.00,
                'duration_minutes' => 30
            ],
            [
                'name' => 'Vacunación',
                'description' => 'Aplicación de vacunas preventivas',
                'category' => 'vacuna',
                'price' => 35000.00,
                'duration_minutes' => 15
            ],
            [
                'name' => 'Baño y Peluquería',
                'description' => 'Servicio completo de aseo y grooming',
                'category' => 'grooming',
                'price' => 40000.00,
                'duration_minutes' => 60
            ],
            [
                'name' => 'Cirugía Menor',
                'description' => 'Procedimientos quirúrgicos menores',
                'category' => 'cirugia',
                'price' => 150000.00,
                'duration_minutes' => 90
            ],
            [
                'name' => 'Emergencia',
                'description' => 'Atención de emergencias veterinarias',
                'category' => 'emergencia',
                'price' => 80000.00,
                'duration_minutes' => 45
            ]
        ];

        foreach ($services as $serviceData) {
            Service::firstOrCreate(
                ['name' => $serviceData['name']],
                $serviceData
            );
        }

        // Crear propietarios
        $owners = [
            [
                'first_name' => 'Juan',
                'last_name' => 'Pérez',
                'document_type' => 'DNI',
                'document_number' => '12345678',
                'email' => 'juan.perez@email.com',
                'phone' => '3001234567',
                'address' => 'Calle 123 #45-67',
                'city' => 'Bogotá'
            ],
            [
                'first_name' => 'María',
                'last_name' => 'García',
                'document_type' => 'DNI',
                'document_number' => '87654321',
                'email' => 'maria.garcia@email.com',
                'phone' => '3009876543',
                'address' => 'Carrera 45 #12-34',
                'city' => 'Medellín'
            ],
            [
                'first_name' => 'Carlos',
                'last_name' => 'López',
                'document_type' => 'DNI',
                'document_number' => '11223344',
                'email' => 'carlos.lopez@email.com',
                'phone' => '3005555555',
                'address' => 'Avenida 80 #23-45',
                'city' => 'Cali'
            ]
        ];

        $createdOwners = [];
        foreach ($owners as $ownerData) {
            $createdOwners[] = Owner::firstOrCreate(
                ['document_number' => $ownerData['document_number']],
                $ownerData
            );
        }

        // Crear mascotas
        $pets = [
            [
                'name' => 'Max',
                'species' => 'Perro',
                'breed' => 'Golden Retriever',
                'gender' => 'M',
                'birth_date' => now()->subYears(3)->format('Y-m-d'),
                'weight' => 25.5,
                'color' => 'Dorado',
                'owner_id' => $createdOwners[0]->id
            ],
            [
                'name' => 'Luna',
                'species' => 'Gato',
                'breed' => 'Siamés',
                'gender' => 'F',
                'birth_date' => now()->subYears(2)->format('Y-m-d'),
                'weight' => 4.2,
                'color' => 'Blanco y negro',
                'owner_id' => $createdOwners[0]->id
            ],
            [
                'name' => 'Rocky',
                'species' => 'Perro',
                'breed' => 'Pastor Alemán',
                'gender' => 'M',
                'birth_date' => now()->subYears(5)->format('Y-m-d'),
                'weight' => 30.0,
                'color' => 'Negro y café',
                'owner_id' => $createdOwners[1]->id
            ]
        ];

        $createdPets = [];
        foreach ($pets as $petData) {
            $createdPets[] = Pet::firstOrCreate(
                ['name' => $petData['name'], 'owner_id' => $petData['owner_id']],
                $petData
            );
        }

        // Crear citas
        $appointments = [
            [
                'pet_id' => $createdPets[0]->id,
                'service_id' => 1,
                'veterinarian_id' => $veterinarian->id,
                'receptionist_id' => $receptionist->id,
                'appointment_date' => now()->addDays(1),
                'duration_minutes' => 30,
                'status' => 'programada',
                'reason' => 'Consulta de rutina',
                'notes' => 'Revisión general'
            ],
            [
                'pet_id' => $createdPets[1]->id,
                'service_id' => 2,
                'veterinarian_id' => $veterinarian->id,
                'receptionist_id' => $receptionist->id,
                'appointment_date' => now()->addDays(2),
                'duration_minutes' => 15,
                'status' => 'confirmada',
                'reason' => 'Vacunación',
                'notes' => 'Vacuna anual'
            ]
        ];

        foreach ($appointments as $appointmentData) {
            Appointment::firstOrCreate(
                [
                    'pet_id' => $appointmentData['pet_id'],
                    'appointment_date' => $appointmentData['appointment_date']
                ],
                $appointmentData
            );
        }

        // Crear registros médicos
        $medicalRecords = [
            [
                'pet_id' => $createdPets[0]->id,
                'veterinarian_id' => $veterinarian->id,
                'visit_date' => now()->subDays(30)->format('Y-m-d'),
                'reason' => 'Consulta general',
                'diagnosis' => 'Animal sano',
                'treatment' => 'Ninguno requerido'
            ]
        ];

        foreach ($medicalRecords as $recordData) {
            MedicalRecord::firstOrCreate(
                [
                    'pet_id' => $recordData['pet_id'],
                    'visit_date' => $recordData['visit_date']
                ],
                $recordData
            );
        }

        // Crear vacunaciones
        $vaccinations = [
            [
                'pet_id' => $createdPets[0]->id,
                'veterinarian_id' => $veterinarian->id,
                'type' => 'vacuna',
                'name' => 'Parvovirus',
                'application_date' => now()->subDays(60)->format('Y-m-d'),
                'next_dose_date' => now()->addYear()->format('Y-m-d')
            ]
        ];

        foreach ($vaccinations as $vaccinationData) {
            Vaccination::firstOrCreate(
                [
                    'pet_id' => $vaccinationData['pet_id'],
                    'name' => $vaccinationData['name'],
                    'application_date' => $vaccinationData['application_date']
                ],
                $vaccinationData
            );
        }
    }
}
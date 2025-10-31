<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\User;
use App\Models\Owner;
use App\Models\Pet;
use App\Models\Service;
use App\Models\Appointment;
use App\Models\MedicalRecord;
use App\Models\Vaccination;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatosPruebaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Roles
        $this->crearRoles();

        // 2. Usuarios
        $usuarios = $this->crearUsuarios();

        // 3. Propietarios
        $propietarios = $this->crearPropietarios();

        // 4. Mascotas
        $mascotas = $this->crearMascotas($propietarios);

        // 5. Servicios
        $servicios = $this->crearServicios();

        // 6. Citas
        $this->crearCitas($mascotas, $servicios, $usuarios);

        // 7. Vacunas
        $this->crearVacunas($mascotas, $usuarios);

        // 8. Registros médicos
        $this->crearRegistrosMedicos($mascotas, $usuarios);
    }

    private function crearRoles(): void
    {
        $roles = [
            [
                'name' => 'admin',
                'display_name' => 'Administrador',
                'description' => 'Administrador del sistema con acceso completo',
                'permissions' => json_encode(['gestionar_usuarios', 'ver_reportes', 'gestionar_servicios', 'gestionar_sistema'])
            ],
            [
                'name' => 'veterinarian',
                'display_name' => 'Veterinario',
                'description' => 'Veterinario con acceso a fichas médicas y citas',
                'permissions' => json_encode(['ver_citas', 'gestionar_fichas_medicas', 'gestionar_vacunas', 'ver_mascotas'])
            ],
            [
                'name' => 'receptionist',
                'display_name' => 'Recepcionista',
                'description' => 'Recepcionista con acceso a citas y propietarios',
                'permissions' => json_encode(['gestionar_citas', 'gestionar_propietarios', 'gestionar_mascotas', 'ver_servicios'])
            ]
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(
                ['name' => $role['name']],
                $role
            );
        }
    }

    private function crearUsuarios(): array
    {
        $adminRole = Role::where('name', 'admin')->first();
        $veterinarioRole = Role::where('name', 'veterinarian')->first();
        $recepcionistaRole = Role::where('name', 'receptionist')->first();

        $usuarios = [
            [
                'name' => 'Carlos Administrador',
                'email' => 'admin@huellitas.com',
                'password' => Hash::make('admin123'),
                'role_id' => $adminRole->id,
                'is_active' => true,
            ],
            [
                'name' => 'Dra. María González',
                'email' => 'maria.gonzalez@huellitas.com',
                'password' => Hash::make('vet123'),
                'role_id' => $veterinarioRole->id,
                'is_active' => true,
            ],
            [
                'name' => 'Dr. Roberto Martínez',
                'email' => 'roberto.martinez@huellitas.com',
                'password' => Hash::make('vet123'),
                'role_id' => $veterinarioRole->id,
                'is_active' => true,
            ],
            [
                'name' => 'Ana Sofía López',
                'email' => 'ana.lopez@huellitas.com',
                'password' => Hash::make('recep123'),
                'role_id' => $recepcionistaRole->id,
                'is_active' => true,
            ],
            [
                'name' => 'Luis Fernando Pérez',
                'email' => 'luis.perez@huellitas.com',
                'password' => Hash::make('recep123'),
                'role_id' => $recepcionistaRole->id,
                'is_active' => true,
            ]
        ];

        $usuariosCreados = [];
        foreach ($usuarios as $usuario) {
            $usuariosCreados[] = User::updateOrCreate(
                ['email' => $usuario['email']],
                $usuario
            );
        }

        return $usuariosCreados;
    }

    private function crearPropietarios(): array
    {
        $propietarios = [
            [
                'first_name' => 'Juan',
                'last_name' => 'Pérez García',
                'document_type' => 'DNI',
                'document_number' => '12345678',
                'phone' => '+51987654321',
                'email' => 'juan.perez@email.com',
                'address' => 'Av. Principal 123, San Isidro',
                'city' => 'Lima',
                'birth_date' => '1985-03-15',
                'gender' => 'M',
                'notes' => 'Cliente frecuente, muy responsable con las citas',
                'is_active' => true,
            ],
            [
                'first_name' => 'Ana',
                'last_name' => 'García Rodríguez',
                'document_type' => 'DNI',
                'document_number' => '87654321',
                'phone' => '+51987654322',
                'email' => 'ana.garcia@email.com',
                'address' => 'Jr. Central 456, Miraflores',
                'city' => 'Lima',
                'birth_date' => '1990-07-22',
                'gender' => 'F',
                'notes' => null,
                'is_active' => true,
            ],
            [
                'first_name' => 'Luis',
                'last_name' => 'Rodríguez Torres',
                'document_type' => 'DNI',
                'document_number' => '11223344',
                'phone' => '+51987654323',
                'email' => 'luis.rodriguez@email.com',
                'address' => 'Plaza Mayor 789, Centro Histórico',
                'city' => 'Lima',
                'birth_date' => '1978-11-08',
                'gender' => 'M',
                'notes' => 'Tiene múltiples mascotas',
                'is_active' => true,
            ],
            [
                'first_name' => 'Carmen',
                'last_name' => 'López Sánchez',
                'document_type' => 'DNI',
                'document_number' => '44332211',
                'phone' => '+51987654324',
                'email' => 'carmen.lopez@email.com',
                'address' => 'Calle Los Olivos 321',
                'city' => 'Lima',
                'birth_date' => '1982-01-12',
                'gender' => 'F',
                'notes' => null,
                'is_active' => true,
            ],
            [
                'first_name' => 'Miguel',
                'last_name' => 'Torres Vargas',
                'document_type' => 'DNI',
                'document_number' => '55667788',
                'phone' => '+51987654325',
                'email' => 'miguel.torres@email.com',
                'address' => 'Av. Sur 654, San Borja',
                'city' => 'Lima',
                'birth_date' => '1975-09-30',
                'gender' => 'M',
                'notes' => null,
                'is_active' => true,
            ],
            [
                'first_name' => 'Patricia',
                'last_name' => 'Mendoza Flores',
                'document_type' => 'DNI',
                'document_number' => '99887766',
                'phone' => '+51987654326',
                'email' => 'patricia.mendoza@email.com',
                'address' => 'Jr. Libertad 987, Surco',
                'city' => 'Lima',
                'birth_date' => '1988-05-20',
                'gender' => 'F',
                'notes' => null,
                'is_active' => true,
            ]
        ];

        $propietariosCreados = [];
        foreach ($propietarios as $propietario) {
            $propietariosCreados[] = Owner::updateOrCreate(
                ['document_number' => $propietario['document_number']],
                $propietario
            );
        }

        return $propietariosCreados;
    }

    private function crearMascotas(array $propietarios): array
    {
        $mascotas = [
            [
                'name' => 'Max',
                'species' => 'Perro',
                'breed' => 'Golden Retriever',
                'birth_date' => '2020-03-15',
                'gender' => 'M',
                'weight' => 28.5,
                'color' => 'Dorado',
                'distinctive_marks' => 'Mancha blanca en el pecho',
                'microchip_number' => 'MC001234567890',
                'is_sterilized' => true,
                'allergies' => 'Ninguna conocida',
                'medical_conditions' => 'Perro muy activo y saludable',
                'owner_id' => $propietarios[0]->id,
                'is_active' => true,
            ],
            [
                'name' => 'Luna',
                'species' => 'Gato',
                'breed' => 'Siamés',
                'birth_date' => '2021-07-22',
                'gender' => 'F',
                'weight' => 4.2,
                'color' => 'Crema con puntos oscuros',
                'distinctive_marks' => 'Ojos azules brillantes',
                'microchip_number' => 'MC001234567891',
                'is_sterilized' => true,
                'allergies' => 'Alérgica al pollo',
                'medical_conditions' => 'Gata muy tranquila',
                'owner_id' => $propietarios[1]->id,
                'is_active' => true,
            ],
            [
                'name' => 'Rocky',
                'species' => 'Perro',
                'breed' => 'Bulldog Francés',
                'birth_date' => '2019-11-08',
                'gender' => 'M',
                'weight' => 12.8,
                'color' => 'Atigrado',
                'distinctive_marks' => 'Oreja izquierda caída',
                'microchip_number' => 'MC001234567892',
                'is_sterilized' => false,
                'allergies' => 'Sensible a productos lácteos',
                'medical_conditions' => 'Problemas respiratorios leves típicos de la raza',
                'owner_id' => $propietarios[2]->id,
                'is_active' => true,
            ],
            [
                'name' => 'Bella',
                'species' => 'Perro',
                'breed' => 'Labrador',
                'birth_date' => '2022-01-12',
                'gender' => 'F',
                'weight' => 22.3,
                'color' => 'Chocolate',
                'distinctive_marks' => null,
                'microchip_number' => 'MC001234567893',
                'is_sterilized' => true,
                'allergies' => 'Ninguna conocida',
                'medical_conditions' => 'Muy enérgica, necesita ejercicio regular',
                'owner_id' => $propietarios[3]->id,
                'is_active' => true,
            ],
            [
                'name' => 'Mimi',
                'species' => 'Gato',
                'breed' => 'Persa',
                'birth_date' => '2020-09-30',
                'gender' => 'F',
                'weight' => 5.1,
                'color' => 'Blanco',
                'distinctive_marks' => 'Pelaje muy largo y sedoso',
                'microchip_number' => 'MC001234567894',
                'is_sterilized' => true,
                'allergies' => 'Ninguna conocida',
                'medical_conditions' => 'Requiere cepillado diario por su pelaje largo',
                'owner_id' => $propietarios[4]->id,
                'is_active' => true,
            ],
            [
                'name' => 'Charlie',
                'species' => 'Perro',
                'breed' => 'Beagle',
                'birth_date' => '2021-05-18',
                'gender' => 'M',
                'weight' => 15.7,
                'color' => 'Tricolor',
                'distinctive_marks' => 'Cola siempre en movimiento',
                'microchip_number' => 'MC001234567895',
                'is_sterilized' => false,
                'allergies' => 'Ninguna conocida',
                'medical_conditions' => 'Muy curioso y explorador',
                'owner_id' => $propietarios[0]->id,
                'is_active' => true,
            ],
            [
                'name' => 'Coco',
                'species' => 'Gato',
                'breed' => 'Mestizo',
                'birth_date' => '2023-02-14',
                'gender' => 'F',
                'weight' => 3.2,
                'color' => 'Negro',
                'distinctive_marks' => 'Gatita joven muy juguetona',
                'microchip_number' => 'MC001234567896',
                'is_sterilized' => false,
                'allergies' => 'Ninguna conocida',
                'medical_conditions' => null,
                'owner_id' => $propietarios[1]->id,
                'is_active' => true,
            ],
            [
                'name' => 'Thor',
                'species' => 'Perro',
                'breed' => 'Husky Siberiano',
                'birth_date' => '2020-06-10',
                'gender' => 'M',
                'weight' => 24.0,
                'color' => 'Gris y blanco',
                'distinctive_marks' => 'Ojos heterocromáticos (uno azul, uno marrón)',
                'microchip_number' => 'MC001234567897',
                'is_sterilized' => true,
                'allergies' => 'Ninguna conocida',
                'medical_conditions' => 'Muy activo, necesita ejercicio intenso diario',
                'owner_id' => $propietarios[5]->id,
                'is_active' => true,
            ]
        ];

        $mascotasCreadas = [];
        foreach ($mascotas as $mascota) {
            $mascotasCreadas[] = Pet::updateOrCreate(
                ['microchip_number' => $mascota['microchip_number']],
                $mascota
            );
        }

        return $mascotasCreadas;
    }

    private function crearServicios(): array
    {
        $servicios = [
            [
                'name' => 'Consulta General',
                'description' => 'Consulta veterinaria general para revisión de salud y diagnóstico',
                'price' => 50.00,
                'duration_minutes' => 30,
                'category' => 'consulta',
                'is_active' => true,
            ],
            [
                'name' => 'Vacunación',
                'description' => 'Aplicación de vacunas según calendario de vacunación',
                'price' => 25.00,
                'duration_minutes' => 15,
                'category' => 'vacuna',
                'is_active' => true,
            ],
            [
                'name' => 'Desparasitación',
                'description' => 'Tratamiento antiparasitario interno y externo',
                'price' => 20.00,
                'duration_minutes' => 10,
                'category' => 'otros',
                'is_active' => true,
            ],
            [
                'name' => 'Baño y Corte',
                'description' => 'Servicio de grooming completo con corte de pelo',
                'price' => 35.00,
                'duration_minutes' => 60,
                'category' => 'grooming',
                'is_active' => true,
            ],
            [
                'name' => 'Cirugía Menor',
                'description' => 'Procedimientos quirúrgicos menores y esterilización',
                'price' => 150.00,
                'duration_minutes' => 90,
                'category' => 'cirugia',
                'is_active' => true,
            ],
            [
                'name' => 'Radiografía',
                'description' => 'Estudio radiográfico para diagnóstico',
                'price' => 80.00,
                'duration_minutes' => 20,
                'category' => 'otros',
                'is_active' => true,
            ],
            [
                'name' => 'Análisis de Sangre',
                'description' => 'Exámenes de laboratorio básicos y completos',
                'price' => 60.00,
                'duration_minutes' => 15,
                'category' => 'otros',
                'is_active' => true,
            ],
            [
                'name' => 'Limpieza Dental',
                'description' => 'Profilaxis dental veterinaria completa',
                'price' => 120.00,
                'duration_minutes' => 45,
                'category' => 'otros',
                'is_active' => true,
            ],
            [
                'name' => 'Baño Simple',
                'description' => 'Baño básico para mascotas',
                'price' => 20.00,
                'duration_minutes' => 30,
                'category' => 'baño',
                'is_active' => true,
            ],
            [
                'name' => 'Emergencia',
                'description' => 'Atención de emergencia veterinaria 24/7',
                'price' => 100.00,
                'duration_minutes' => 60,
                'category' => 'emergencia',
                'is_active' => true,
            ]
        ];

        $serviciosCreados = [];
        foreach ($servicios as $servicio) {
            $serviciosCreados[] = Service::updateOrCreate(
                ['name' => $servicio['name']],
                $servicio
            );
        }

        return $serviciosCreados;
    }

    private function crearCitas(array $mascotas, array $servicios, array $usuarios): void
    {
        // Cargar la relación role para todos los usuarios
        $usuariosConRole = User::with('role')->whereIn('id', array_map(fn($u) => $u->id, $usuarios))->get();
        
        $veterinarios = $usuariosConRole->filter(function($usuario) {
            return $usuario->role && $usuario->role->name === 'veterinarian';
        })->values();
        
        $recepcionistas = $usuariosConRole->filter(function($usuario) {
            return $usuario->role && $usuario->role->name === 'receptionist';
        })->values();

        $veterinario = $veterinarios->first();
        $recepcionista = $recepcionistas->first();
        
        if (!$veterinario || !$recepcionista) {
            return; // No se pueden crear citas sin veterinario o recepcionista
        }

        $citas = [
            [
                'pet_id' => $mascotas[0]->id,
                'service_id' => $servicios[0]->id, // Consulta General
                'veterinarian_id' => $veterinario->id,
                'receptionist_id' => $recepcionista->id,
                'appointment_date' => Carbon::today()->addDays(1)->setTime(9, 0),
                'duration_minutes' => 30,
                'status' => 'programada',
                'reason' => 'Revisión general de salud anual',
                'notes' => 'Primera consulta del año, revisar estado general',
                'total_amount' => 50.00,
            ],
            [
                'pet_id' => $mascotas[1]->id,
                'service_id' => $servicios[1]->id, // Vacunación
                'veterinarian_id' => $veterinario->id,
                'receptionist_id' => $recepcionista->id,
                'appointment_date' => Carbon::today()->addDays(2)->setTime(10, 30),
                'duration_minutes' => 15,
                'status' => 'confirmada',
                'reason' => 'Vacuna anual obligatoria',
                'notes' => 'Aplicar vacuna triple felina',
                'total_amount' => 25.00,
                'confirmed_at' => Carbon::now(),
            ],
            [
                'pet_id' => $mascotas[2]->id,
                'service_id' => $servicios[3]->id, // Baño y Corte
                'veterinarian_id' => $veterinario->id,
                'receptionist_id' => $recepcionista->id,
                'appointment_date' => Carbon::today()->addDays(3)->setTime(14, 0),
                'duration_minutes' => 60,
                'status' => 'programada',
                'reason' => 'Grooming mensual',
                'notes' => 'Corte especial para la raza Bulldog Francés',
                'total_amount' => 35.00,
            ],
            [
                'pet_id' => $mascotas[3]->id,
                'service_id' => $servicios[0]->id, // Consulta General
                'veterinarian_id' => $veterinario->id,
                'receptionist_id' => $recepcionista->id,
                'appointment_date' => Carbon::yesterday()->setTime(11, 0),
                'duration_minutes' => 30,
                'status' => 'completada',
                'reason' => 'Chequeo post-esterilización',
                'notes' => 'Revisión de cicatrización, todo normal',
                'total_amount' => 50.00,
                'completed_at' => Carbon::yesterday()->setTime(11, 30),
            ],
            [
                'pet_id' => $mascotas[4]->id,
                'service_id' => $servicios[7]->id, // Limpieza Dental
                'veterinarian_id' => $veterinario->id,
                'receptionist_id' => $recepcionista->id,
                'appointment_date' => Carbon::today()->subDays(3)->setTime(15, 30),
                'duration_minutes' => 45,
                'status' => 'completada',
                'reason' => 'Limpieza dental anual',
                'notes' => 'Sarro moderado removido exitosamente, sin complicaciones',
                'total_amount' => 120.00,
                'completed_at' => Carbon::today()->subDays(3)->setTime(16, 15),
            ],
            [
                'pet_id' => $mascotas[5]->id,
                'service_id' => $servicios[2]->id, // Desparasitación
                'veterinarian_id' => $veterinario->id,
                'receptionist_id' => $recepcionista->id,
                'appointment_date' => Carbon::today()->addDays(5)->setTime(16, 0),
                'duration_minutes' => 10,
                'status' => 'programada',
                'reason' => 'Desparasitación trimestral',
                'notes' => 'Aplicar tratamiento interno y externo',
                'total_amount' => 20.00,
            ],
            [
                'pet_id' => $mascotas[6]->id,
                'service_id' => $servicios[1]->id, // Vacunación
                'veterinarian_id' => $veterinario->id,
                'receptionist_id' => $recepcionista->id,
                'appointment_date' => Carbon::today()->addDays(7)->setTime(9, 30),
                'duration_minutes' => 15,
                'status' => 'programada',
                'reason' => 'Primera vacuna',
                'notes' => 'Gatita joven, primera serie de vacunas',
                'total_amount' => 25.00,
            ],
            [
                'pet_id' => $mascotas[7]->id,
                'service_id' => $servicios[0]->id, // Consulta General
                'veterinarian_id' => $veterinario->id,
                'receptionist_id' => $recepcionista->id,
                'appointment_date' => Carbon::today()->addDays(2)->setTime(16, 0),
                'duration_minutes' => 30,
                'status' => 'confirmada',
                'reason' => 'Chequeo de rutina',
                'notes' => 'Revisión general después del verano',
                'total_amount' => 50.00,
                'confirmed_at' => Carbon::now(),
            ]
        ];

        foreach ($citas as $cita) {
            Appointment::create($cita);
        }
    }

    private function crearVacunas(array $mascotas, array $usuarios): void
    {
        // Obtener veterinario usando whereHas
        $veterinario = User::whereHas('role', function($query) {
            $query->where('name', 'veterinarian');
        })->first();

        if (!$veterinario) {
            return; // No se pueden crear vacunas sin veterinario
        }

        $vacunas = [
            [
                'pet_id' => $mascotas[0]->id,
                'veterinarian_id' => $veterinario->id,
                'type' => 'Anual',
                'name' => 'Vacuna Triple Canina',
                'brand' => 'Nobivac',
                'batch_number' => 'LOTE-2024-001',
                'application_date' => Carbon::today()->subMonths(2),
                'expiration_date' => Carbon::today()->addMonths(10),
                'next_dose_date' => Carbon::today()->addMonths(10),
                'weight_at_application' => 28.0,
                'adverse_reactions' => null,
                'notes' => 'Aplicación correcta, sin reacciones adversas',
            ],
            [
                'pet_id' => $mascotas[1]->id,
                'veterinarian_id' => $veterinario->id,
                'type' => 'Anual',
                'name' => 'Vacuna Triple Felina',
                'brand' => 'Felocell',
                'batch_number' => 'LOTE-2024-002',
                'application_date' => Carbon::today()->subMonths(1),
                'expiration_date' => Carbon::today()->addMonths(11),
                'next_dose_date' => Carbon::today()->addMonths(11),
                'weight_at_application' => 4.0,
                'adverse_reactions' => null,
                'notes' => 'Primera dosis aplicada correctamente',
            ],
            [
                'pet_id' => $mascotas[2]->id,
                'veterinarian_id' => $veterinario->id,
                'type' => 'Anual',
                'name' => 'Vacuna Antirrábica',
                'brand' => 'Rabisin',
                'batch_number' => 'LOTE-2024-003',
                'application_date' => Carbon::today()->subMonths(6),
                'expiration_date' => Carbon::today()->addMonths(6),
                'next_dose_date' => Carbon::today()->addMonths(6),
                'weight_at_application' => 12.5,
                'adverse_reactions' => null,
                'notes' => 'Vacuna antirrábica anual',
            ],
            [
                'pet_id' => $mascotas[3]->id,
                'veterinarian_id' => $veterinario->id,
                'type' => 'Refuerzo',
                'name' => 'Vacuna Triple Canina',
                'brand' => 'Nobivac',
                'batch_number' => 'LOTE-2024-004',
                'application_date' => Carbon::today()->subWeeks(2),
                'expiration_date' => Carbon::today()->addMonths(11),
                'next_dose_date' => Carbon::today()->addMonths(11),
                'weight_at_application' => 22.0,
                'adverse_reactions' => null,
                'notes' => 'Refuerzo anual aplicado',
            ],
            [
                'pet_id' => $mascotas[4]->id,
                'veterinarian_id' => $veterinario->id,
                'type' => 'Anual',
                'name' => 'Vacuna Triple Felina',
                'brand' => 'Felocell',
                'batch_number' => 'LOTE-2024-005',
                'application_date' => Carbon::today()->subMonths(3),
                'expiration_date' => Carbon::today()->addMonths(9),
                'next_dose_date' => Carbon::today()->addMonths(9),
                'weight_at_application' => 5.0,
                'adverse_reactions' => null,
                'notes' => 'Vacuna aplicada sin complicaciones',
            ],
            [
                'pet_id' => $mascotas[5]->id,
                'veterinarian_id' => $veterinario->id,
                'type' => 'Anual',
                'name' => 'Vacuna Antirrábica',
                'brand' => 'Rabisin',
                'batch_number' => 'LOTE-2024-006',
                'application_date' => Carbon::today()->subMonths(4),
                'expiration_date' => Carbon::today()->addMonths(8),
                'next_dose_date' => Carbon::today()->addMonths(8),
                'weight_at_application' => 15.5,
                'adverse_reactions' => null,
                'notes' => 'Primera vacuna antirrábica del año',
            ]
        ];

        foreach ($vacunas as $vacuna) {
            Vaccination::create($vacuna);
        }
    }

    private function crearRegistrosMedicos(array $mascotas, array $usuarios): void
    {
        // Obtener veterinario usando whereHas
        $veterinario = User::whereHas('role', function($query) {
            $query->where('name', 'veterinarian');
        })->first();

        if (!$veterinario) {
            return; // No se pueden crear registros médicos sin veterinario
        }

        $registros = [
            [
                'pet_id' => $mascotas[0]->id,
                'veterinarian_id' => $veterinario->id,
                'visit_date' => Carbon::today()->subDays(30),
                'reason' => 'Consulta de rutina',
                'symptoms' => 'Ninguno, chequeo preventivo',
                'diagnosis' => 'Animal en buen estado de salud',
                'treatment' => 'Ninguno requerido',
                'medications' => null,
                'recommendations' => 'Continuar con ejercicio regular y dieta balanceada',
                'next_visit_date' => Carbon::today()->addMonths(6),
                'weight' => 28.5,
                'temperature' => 38.5,
                'heart_rate' => 80,
                'respiratory_rate' => 20,
                'notes' => 'Perro en excelente estado físico y mental',
            ],
            [
                'pet_id' => $mascotas[1]->id,
                'veterinarian_id' => $veterinario->id,
                'visit_date' => Carbon::today()->subDays(15),
                'reason' => 'Vacunación anual',
                'symptoms' => 'Ninguno',
                'diagnosis' => 'Animal saludable, apto para vacunación',
                'treatment' => 'Aplicación de vacuna triple felina',
                'medications' => null,
                'recommendations' => 'Observar posibles reacciones en las próximas 24 horas',
                'next_visit_date' => Carbon::today()->addMonths(12),
                'weight' => 4.2,
                'temperature' => 38.8,
                'heart_rate' => 140,
                'respiratory_rate' => 30,
                'notes' => 'Gata muy tranquila durante la consulta',
            ],
            [
                'pet_id' => $mascotas[2]->id,
                'veterinarian_id' => $veterinario->id,
                'visit_date' => Carbon::today()->subDays(60),
                'reason' => 'Problemas respiratorios',
                'symptoms' => 'Respiraciones cortas y ronquidos leves',
                'diagnosis' => 'Síndrome braquicefálico leve, típico de la raza',
                'treatment' => 'Monitoreo y evitar ejercicio intenso en días calurosos',
                'medications' => 'Ninguno',
                'recommendations' => 'Evitar ejercicio extenuante en clima cálido, mantener peso adecuado',
                'next_visit_date' => Carbon::today()->addMonths(3),
                'weight' => 12.8,
                'temperature' => 38.7,
                'heart_rate' => 100,
                'respiratory_rate' => 35,
                'notes' => 'Síntomas dentro de lo normal para la raza, continuar monitoreo',
            ],
            [
                'pet_id' => $mascotas[3]->id,
                'veterinarian_id' => $veterinario->id,
                'visit_date' => Carbon::today()->subDays(7),
                'reason' => 'Post-esterilización',
                'symptoms' => 'Ninguno',
                'diagnosis' => 'Cicatrización normal post-quirúrgica',
                'treatment' => 'Limpieza de herida y aplicación de antibiótico tópico',
                'medications' => 'Antibiótico tópico una vez al día por 5 días',
                'recommendations' => 'Evitar lamer la herida, usar collar isabelino si es necesario',
                'next_visit_date' => Carbon::today()->addDays(7),
                'weight' => 22.3,
                'temperature' => 38.6,
                'heart_rate' => 90,
                'respiratory_rate' => 22,
                'notes' => 'Recuperación excelente, herida limpia sin signos de infección',
            ],
            [
                'pet_id' => $mascotas[4]->id,
                'veterinarian_id' => $veterinario->id,
                'visit_date' => Carbon::today()->subDays(90),
                'reason' => 'Limpieza dental',
                'symptoms' => 'Acumulación de sarro moderada',
                'diagnosis' => 'Sarro dental moderado, sin enfermedad periodontal',
                'treatment' => 'Limpieza dental profesional bajo anestesia',
                'medications' => 'Enjuague bucal diario',
                'recommendations' => 'Cepillado dental diario con pasta especial para gatos',
                'next_visit_date' => Carbon::today()->addMonths(6),
                'weight' => 5.1,
                'temperature' => 38.9,
                'heart_rate' => 150,
                'respiratory_rate' => 28,
                'notes' => 'Procedimiento exitoso, sarro removido completamente',
            ]
        ];

        foreach ($registros as $registro) {
            MedicalRecord::create($registro);
        }
    }
}


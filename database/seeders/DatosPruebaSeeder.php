<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Rol;
use App\Models\Usuario;
use App\Models\Propietario;
use App\Models\Mascota;
use App\Models\Servicio;
use App\Models\Cita;
use App\Models\FichaClinica;
use App\Models\Vacuna;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatosPruebaSeeder extends Seeder
{
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

        // 8. Fichas Clínicas
        $this->crearFichasClinicas($mascotas, $usuarios);
    }

    private function crearRoles(): void
    {
        $roles = [
            [
                'nombre' => 'admin',
                'nombre_mostrar' => 'Administrador',
                'descripcion' => 'Administrador del sistema con acceso completo',
                'permisos' => json_encode(['gestionar_usuarios', 'ver_reportes', 'gestionar_servicios', 'gestionar_sistema'])
            ],
            [
                'nombre' => 'veterinarian',
                'nombre_mostrar' => 'Veterinario',
                'descripcion' => 'Veterinario con acceso a fichas médicas y citas',
                'permisos' => json_encode(['ver_citas', 'gestionar_fichas_medicas', 'gestionar_vacunas', 'ver_mascotas'])
            ],
            [
                'nombre' => 'receptionist',
                'nombre_mostrar' => 'Recepcionista',
                'descripcion' => 'Recepcionista con acceso a citas y propietarios',
                'permisos' => json_encode(['gestionar_citas', 'gestionar_propietarios', 'gestionar_mascotas', 'ver_servicios'])
            ]
        ];

        foreach ($roles as $role) {
            Rol::updateOrCreate(['nombre' => $role['nombre']], $role);
        }
    }

    private function crearUsuarios(): array
    {
        $adminRole = Rol::where('nombre', 'admin')->first();
        $veterinarioRole = Rol::where('nombre', 'veterinarian')->first();
        $recepcionistaRole = Rol::where('nombre', 'receptionist')->first();

        $usuarios = [
            [
                'nombre' => 'Carlos Administrador',
                'correo' => 'admin@huellitas.com',
                'contrasenia' => Hash::make('admin123'),
                'rol_id' => $adminRole->id,
                'activo' => true,
            ],
            [
                'nombre' => 'Dra. María González',
                'correo' => 'maria.gonzalez@huellitas.com',
                'contrasenia' => Hash::make('vet123'),
                'rol_id' => $veterinarioRole->id,
                'activo' => true,
            ],
            [
                'nombre' => 'Ana Sofía López',
                'correo' => 'ana.martinez@huellitas.com',
                'contrasenia' => Hash::make('recep123'),
                'rol_id' => $recepcionistaRole->id,
                'activo' => true,
            ],
        ];

        $usuariosCreados = [];
        foreach ($usuarios as $usuario) {
            $usuariosCreados[] = Usuario::updateOrCreate(['correo' => $usuario['correo']], $usuario);
        }

        return $usuariosCreados;
    }

    private function crearPropietarios(): array
    {
        $propietarios = [
            [
                'nombre_completo' => 'Juan Pérez García',
                'tipo_documento' => 'DNI',
                'numero_documento' => '12345678',
                'numero_cell' => '+51987654321',
                'correo' => 'juan.perez@email.com',
                'direccion' => 'Av. Principal 123, San Isidro',
                'ciudad' => 'Lima',
                'fecha_nacimiento' => '1985-03-15',
                'sexo' => 'M',
                'notas' => 'Cliente frecuente',
                'activo' => true,
            ],
            [
                'nombre_completo' => 'Ana García Rodríguez',
                'tipo_documento' => 'DNI',
                'numero_documento' => '87654321',
                'numero_cell' => '+51987654322',
                'correo' => 'ana.garcia@email.com',
                'direccion' => 'Jr. Central 456, Miraflores',
                'ciudad' => 'Lima',
                'fecha_nacimiento' => '1990-07-22',
                'sexo' => 'F',
                'notas' => null,
                'activo' => true,
            ],
            [
                'nombre_completo' => 'Ricardo Quispe',
                'tipo_documento' => 'DNI',
                'numero_documento' => '11223344',
                'numero_cell' => '+51987654323',
                'correo' => 'rquispe@novaly.com.pe',
                'direccion' => 'Calle Lima 789',
                'ciudad' => 'Lima',
                'fecha_nacimiento' => '1988-05-20',
                'sexo' => 'M',
                'notas' => null,
                'activo' => true,
            ],
        ];

        $propietariosCreados = [];
        foreach ($propietarios as $propietario) {
            $propietariosCreados[] = Propietario::updateOrCreate(['numero_documento' => $propietario['numero_documento']], $propietario);
        }

        return $propietariosCreados;
    }

    private function crearMascotas(array $propietarios): array
    {
        $mascotas = [
            [
                'nombre' => 'Max',
                'especie' => 'Perro',
                'raza' => 'Golden Retriever',
                'fecha_nacimiento' => '2020-03-15',
                'sexo' => 'M',
                'peso' => 28.5,
                'color' => 'Dorado',
                'marcas_distintivas' => 'Mancha blanca en el pecho',
                'numero_microchip' => 'MC001234567890',
                'esterilizado' => true,
                'alergias' => 'Ninguna conocida',
                'condiciones_medicas' => 'Perro muy activo y saludable',
                'id_propietario' => $propietarios[0]->id,
                'activo' => true,
            ],
            [
                'nombre' => 'Luna',
                'especie' => 'Gato',
                'raza' => 'Siamés',
                'fecha_nacimiento' => '2021-07-22',
                'sexo' => 'F',
                'peso' => 4.2,
                'color' => 'Crema con puntos oscuros',
                'marcas_distintivas' => 'Ojos azules brillantes',
                'numero_microchip' => 'MC001234567891',
                'esterilizado' => true,
                'alergias' => 'Alérgica al pollo',
                'condiciones_medicas' => 'Gata muy tranquila',
                'id_propietario' => $propietarios[1]->id,
                'activo' => true,
            ],
            [
                'nombre' => 'Rocky',
                'especie' => 'Perro',
                'raza' => 'Bulldog Francés',
                'fecha_nacimiento' => '2019-11-08',
                'sexo' => 'M',
                'peso' => 12.8,
                'color' => 'Atigrado',
                'marcas_distintivas' => 'Oreja izquierda caída',
                'numero_microchip' => 'MC001234567892',
                'esterilizado' => false,
                'alergias' => 'Sensible a productos lácteos',
                'condiciones_medicas' => 'Problemas respiratorios leves',
                'id_propietario' => $propietarios[2]->id,
                'activo' => true,
            ],
        ];

        $mascotasCreadas = [];
        foreach ($mascotas as $mascota) {
            $mascotasCreadas[] = Mascota::updateOrCreate(['numero_microchip' => $mascota['numero_microchip']], $mascota);
        }

        return $mascotasCreadas;
    }

    private function crearServicios(): array
    {
        $servicios = [
            [
                'nombre_servicio' => 'Consulta General',
                'descripcion' => 'Consulta veterinaria general',
                'precio' => 50.00,
                'duracion_estimada' => 30,
                'categoria' => 'consulta',
                'activo' => true,
            ],
            [
                'nombre_servicio' => 'Vacunación',
                'descripcion' => 'Aplicación de vacunas',
                'precio' => 25.00,
                'duracion_estimada' => 15,
                'categoria' => 'vacuna',
                'activo' => true,
            ],
            [
                'nombre_servicio' => 'Desparasitación',
                'descripcion' => 'Tratamiento antiparasitario',
                'precio' => 20.00,
                'duracion_estimada' => 10,
                'categoria' => 'otros',
                'activo' => true,
            ],
        ];

        $serviciosCreados = [];
        foreach ($servicios as $servicio) {
            $serviciosCreados[] = Servicio::updateOrCreate(['nombre_servicio' => $servicio['nombre_servicio']], $servicio);
        }

        return $serviciosCreados;
    }

    private function crearCitas(array $mascotas, array $servicios, array $usuarios): void
    {
        $veterinario = Usuario::whereHas('rol', function($query) {
            $query->where('nombre', 'veterinarian');
        })->first();

        $recepcionista = Usuario::whereHas('rol', function($query) {
            $query->where('nombre', 'receptionist');
        })->first();

        if (!$veterinario || !$recepcionista) {
            return;
        }

        $citas = [
            [
                'id_mascota' => $mascotas[0]->id,
                'id_servicio' => $servicios[0]->id,
                'id_veterinario' => $veterinario->id,
                'id_recepcionista' => $recepcionista->id,
                'fecha_cita' => Carbon::today()->addDays(1)->setTime(9, 0),
                'duracion_minutos' => 30,
                'estado' => 'programada',
                'motivo' => 'Revisión general',
                'notas' => 'Primera consulta del año',
                'monto_total' => 50.00,
            ],
        ];

        foreach ($citas as $cita) {
            Cita::create($cita);
        }
    }

    private function crearVacunas(array $mascotas, array $usuarios): void
    {
        $veterinario = Usuario::whereHas('rol', function($query) {
            $query->where('nombre', 'veterinarian');
        })->first();

        if (!$veterinario) {
            return;
        }

        $vacunas = [
            [
                'id_mascota' => $mascotas[0]->id,
                'id_veterinario' => $veterinario->id,
                'tipo' => 'vacuna',
                'nombre_vacuna' => 'Vacuna Triple Canina',
                'marca' => 'Nobivac',
                'numero_lote' => 'LOTE-2024-001',
                'fecha_aplicacion' => Carbon::today()->subMonths(2),
                'fecha_expiracion' => Carbon::today()->addMonths(10),
                'fecha_proxima_dosis' => Carbon::today()->addMonths(10),
                'peso_aplicacion' => 28.0,
                'reacciones_adversas' => null,
                'observaciones' => 'Aplicación correcta',
            ],
        ];

        foreach ($vacunas as $vacuna) {
            Vacuna::create($vacuna);
        }
    }

    private function crearFichasClinicas(array $mascotas, array $usuarios): void
    {
        $veterinario = Usuario::whereHas('rol', function($query) {
            $query->where('nombre', 'veterinarian');
        })->first();

        if (!$veterinario) {
            return;
        }

        $fichas = [
            [
                'id_mascota' => $mascotas[0]->id,
                'id_veterinario' => $veterinario->id,
                'fecha_visita' => Carbon::today()->subDays(30),
                'motivo' => 'Consulta de rutina',
                'sintomas' => 'Ninguno',
                'examen_fisico' => 'Frecuencia cardiaca: 80 lpm, Frecuencia respiratoria: 20 rpm',
                'peso' => 28.5,
                'temperatura' => 38.5,
                'diagnostico' => 'Animal en buen estado de salud',
                'tratamiento' => 'Ninguno requerido',
                'medicamentos' => null,
                'recomendaciones' => 'Continuar con ejercicio regular',
                'fecha_proxima_visita' => Carbon::today()->addMonths(6),
                'notas' => 'Perro en excelente estado',
            ],
        ];

        foreach ($fichas as $ficha) {
            FichaClinica::create($ficha);
        }
    }
}

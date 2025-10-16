<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Service;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            [
                'name' => 'Consulta General',
                'description' => 'Consulta veterinaria general para revisión de salud',
                'price' => 50.00,
                'duration_minutes' => 30,
                'category' => 'consulta',
                'is_active' => true,
            ],
            [
                'name' => 'Vacunación',
                'description' => 'Aplicación de vacunas según calendario',
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
                'description' => 'Servicio de grooming completo',
                'price' => 35.00,
                'duration_minutes' => 60,
                'category' => 'grooming',
                'is_active' => true,
            ],
            [
                'name' => 'Cirugía Menor',
                'description' => 'Procedimientos quirúrgicos menores',
                'price' => 150.00,
                'duration_minutes' => 90,
                'category' => 'cirugia',
                'is_active' => true,
            ],
            [
                'name' => 'Radiografía',
                'description' => 'Estudio radiográfico',
                'price' => 80.00,
                'duration_minutes' => 20,
                'category' => 'otros',
                'is_active' => true,
            ],
            [
                'name' => 'Análisis de Sangre',
                'description' => 'Exámenes de laboratorio básicos',
                'price' => 60.00,
                'duration_minutes' => 15,
                'category' => 'otros',
                'is_active' => true,
            ],
            [
                'name' => 'Limpieza Dental',
                'description' => 'Profilaxis dental veterinaria',
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
                'description' => 'Atención de emergencia veterinaria',
                'price' => 100.00,
                'duration_minutes' => 60,
                'category' => 'emergencia',
                'is_active' => true,
            ]
        ];

        foreach ($services as $service) {
            Service::create($service);
        }
    }
}
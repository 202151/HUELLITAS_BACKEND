<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            [
                'name' => 'admin',
                'display_name' => 'Administrador',
                'description' => 'Administrador del sistema con acceso completo',
                'permissions' => ['manage_users', 'view_reports', 'manage_services', 'manage_system']
            ],
            [
                'name' => 'veterinarian',
                'display_name' => 'Veterinario',
                'description' => 'Veterinario con acceso a fichas médicas y citas',
                'permissions' => ['view_appointments', 'manage_medical_records', 'manage_vaccinations', 'view_pets']
            ],
            [
                'name' => 'receptionist',
                'display_name' => 'Recepcionista',
                'description' => 'Recepcionista con acceso a citas y propietarios',
                'permissions' => ['manage_appointments', 'manage_owners', 'manage_pets', 'view_services']
            ]
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}

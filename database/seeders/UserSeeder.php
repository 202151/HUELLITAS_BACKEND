<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::where('name', 'admin')->first();
        $veterinarianRole = Role::where('name', 'veterinarian')->first();
        $receptionistRole = Role::where('name', 'receptionist')->first();

        $users = [
            [
                'name' => 'Administrador Sistema',
                'email' => 'admin@huellitas.com',
                'password' => Hash::make('admin123'),
                'role_id' => $adminRole->id,
                'is_active' => true,
            ],
            [
                'name' => 'Dr. María González',
                'email' => 'maria.gonzalez@huellitas.com',
                'password' => Hash::make('vet123'),
                'role_id' => $veterinarianRole->id,
                'is_active' => true,
            ],
            [
                'name' => 'Dr. Carlos Rodríguez',
                'email' => 'carlos.rodriguez@huellitas.com',
                'password' => Hash::make('vet123'),
                'role_id' => $veterinarianRole->id,
                'is_active' => true,
            ],
            [
                'name' => 'Ana Martínez',
                'email' => 'ana.martinez@huellitas.com',
                'password' => Hash::make('recep123'),
                'role_id' => $receptionistRole->id,
                'is_active' => true,
            ],
            [
                'name' => 'Luis Pérez',
                'email' => 'luis.perez@huellitas.com',
                'password' => Hash::make('recep123'),
                'role_id' => $receptionistRole->id,
                'is_active' => true,
            ]
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}

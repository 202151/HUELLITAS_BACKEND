<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Owner;

class OwnerSeeder extends Seeder
{
    public function run(): void
    {
        $owners = [
            [
                'first_name' => 'Juan',
                'last_name' => 'Pérez',
                'document_type' => 'DNI',
                'document_number' => '12345678',
                'phone' => '+1234567890',
                'email' => 'juan.perez@email.com',
                'address' => 'Calle Principal 123',
                'city' => 'Lima',
                'birth_date' => '1985-03-15',
                'gender' => 'M',
                'notes' => 'Cliente frecuente',
                'is_active' => true,
            ],
            [
                'first_name' => 'Ana',
                'last_name' => 'García',
                'document_type' => 'DNI',
                'document_number' => '87654321',
                'phone' => '+1234567892',
                'email' => 'ana.garcia@email.com',
                'address' => 'Avenida Central 456',
                'city' => 'Lima',
                'birth_date' => '1990-07-22',
                'gender' => 'F',
                'notes' => null,
                'is_active' => true,
            ],
            [
                'first_name' => 'Luis',
                'last_name' => 'Rodríguez',
                'document_type' => 'DNI',
                'document_number' => '11223344',
                'phone' => '+1234567894',
                'email' => 'luis.rodriguez@email.com',
                'address' => 'Plaza Mayor 789',
                'city' => 'Lima',
                'birth_date' => '1978-11-08',
                'gender' => 'M',
                'notes' => null,
                'is_active' => true,
            ],
            [
                'first_name' => 'Carmen',
                'last_name' => 'López',
                'document_type' => 'DNI',
                'document_number' => '44332211',
                'phone' => '+1234567896',
                'email' => 'carmen.lopez@email.com',
                'address' => 'Barrio Norte 321',
                'city' => 'Lima',
                'birth_date' => '1982-01-12',
                'gender' => 'F',
                'notes' => null,
                'is_active' => true,
            ],
            [
                'first_name' => 'Miguel',
                'last_name' => 'Torres',
                'document_type' => 'DNI',
                'document_number' => '55667788',
                'phone' => '+1234567898',
                'email' => 'miguel.torres@email.com',
                'address' => 'Zona Sur 654',
                'city' => 'Lima',
                'birth_date' => '1975-09-30',
                'gender' => 'M',
                'notes' => null,
                'is_active' => true,
            ]
        ];

        foreach ($owners as $owner) {
            Owner::create($owner);
        }
    }
}
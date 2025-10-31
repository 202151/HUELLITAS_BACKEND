<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seeder unificado con todos los datos de prueba en español
        $this->call([
            DatosPruebaSeeder::class,
        ]);

        // Seeders individuales anteriores (comentados - usar solo DatosPruebaSeeder)
        // $this->call([
        //     RoleSeeder::class,
        //     UserSeeder::class,
        //     ServiceSeeder::class,
        //     OwnerSeeder::class,
        //     PetSeeder::class,
        //     AppointmentSeeder::class,
        // ]);
    }
}

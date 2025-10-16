<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Pet;
use App\Models\Owner;

class PetSeeder extends Seeder
{
    public function run(): void
    {
        $owners = Owner::all();
        
        $pets = [
            [
                'name' => 'Max',
                'species' => 'Perro',
                'breed' => 'Golden Retriever',
                'birth_date' => '2020-03-15',
                'gender' => 'M',
                'weight' => 25.5,
                'color' => 'Dorado',
                'microchip_number' => 'MC001234567890',
                'is_sterilized' => true,
                'allergies' => 'Ninguna conocida',
                'medical_conditions' => 'Perro muy activo y saludable',
                'owner_id' => $owners->get(0)->id ?? 1,
            ],
            [
                'name' => 'Luna',
                'species' => 'Gato',
                'breed' => 'Siamés',
                'birth_date' => '2021-07-22',
                'gender' => 'F',
                'weight' => 4.2,
                'color' => 'Crema con puntos oscuros',
                'microchip_number' => 'MC001234567891',
                'is_sterilized' => true,
                'allergies' => 'Alérgica al pollo',
                'medical_conditions' => 'Gata muy tranquila',
                'owner_id' => $owners->get(1)->id ?? 2,
            ],
            [
                'name' => 'Rocky',
                'species' => 'Perro',
                'breed' => 'Bulldog Francés',
                'birth_date' => '2019-11-08',
                'gender' => 'M',
                'weight' => 12.8,
                'color' => 'Atigrado',
                'microchip_number' => 'MC001234567892',
                'is_sterilized' => false,
                'allergies' => 'Sensible a productos lácteos',
                'medical_conditions' => 'Problemas respiratorios leves típicos de la raza',
                'owner_id' => $owners->get(2)->id ?? 3,
            ],
            [
                'name' => 'Bella',
                'species' => 'Perro',
                'breed' => 'Labrador',
                'birth_date' => '2022-01-12',
                'gender' => 'F',
                'weight' => 18.3,
                'color' => 'Chocolate',
                'microchip_number' => 'MC001234567893',
                'is_sterilized' => true,
                'allergies' => 'Ninguna conocida',
                'medical_conditions' => 'Muy enérgica, necesita ejercicio regular',
                'owner_id' => $owners->get(3)->id ?? 4,
            ],
            [
                'name' => 'Mimi',
                'species' => 'Gato',
                'breed' => 'Persa',
                'birth_date' => '2020-09-30',
                'gender' => 'F',
                'weight' => 5.1,
                'color' => 'Blanco',
                'microchip_number' => 'MC001234567894',
                'is_sterilized' => true,
                'allergies' => 'Ninguna conocida',
                'medical_conditions' => 'Requiere cepillado diario por su pelaje largo',
                'owner_id' => $owners->get(4)->id ?? 5,
            ],
            [
                'name' => 'Charlie',
                'species' => 'Perro',
                'breed' => 'Beagle',
                'birth_date' => '2021-05-18',
                'gender' => 'M',
                'weight' => 15.7,
                'color' => 'Tricolor',
                'microchip_number' => 'MC001234567895',
                'is_sterilized' => false,
                'allergies' => 'Ninguna conocida',
                'medical_conditions' => 'Muy curioso y explorador',
                'owner_id' => $owners->get(0)->id ?? 1,
            ],
            [
                'name' => 'Coco',
                'species' => 'Gato',
                'breed' => 'Mestizo',
                'birth_date' => '2023-02-14',
                'gender' => 'F',
                'weight' => 2.8,
                'color' => 'Negro',
                'microchip_number' => 'MC001234567896',
                'is_sterilized' => false,
                'allergies' => 'Ninguna conocida',
                'medical_conditions' => 'Gatita joven muy juguetona',
                'owner_id' => $owners->get(1)->id ?? 2,
            ]
        ];

        foreach ($pets as $pet) {
            Pet::create($pet);
        }
    }
}
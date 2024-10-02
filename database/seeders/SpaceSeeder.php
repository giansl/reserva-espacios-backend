<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SpaceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $spaces = [
            [
                'name' => 'Sala de Conferencias A',
                'description' => 'Amplia sala equipada para conferencias y presentaciones',
                'capacity' => 50,
                'type' => 'Conferencia',
            ],
            [
                'name' => 'Oficina Compartida B',
                'description' => 'Espacio de trabajo compartido con escritorios individuales',
                'capacity' => 20,
                'type' => 'Coworking',
            ],
            [
                'name' => 'Sala de Reuniones C',
                'description' => 'Sala pequeña ideal para reuniones de equipo',
                'capacity' => 10,
                'type' => 'Reunión',
            ],
        ];

        foreach ($spaces as $space) {
            DB::table('spaces')->insert($space);
        }
    }
}

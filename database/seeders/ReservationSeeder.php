<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Reservation;
use App\Models\User;
use App\Models\Space;
use Carbon\Carbon;

class ReservationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Asegúrate de que tienes usuarios y espacios en la base de datos
        $users = User::all();
        $spaces = Space::all();

        if ($users->isEmpty() || $spaces->isEmpty()) {
            throw new \Exception('Asegúrate de tener usuarios y espacios en la base de datos antes de ejecutar este seeder.');
        }

        // Crea 20 reservas de ejemplo
        for ($i = 0; $i < 20; $i++) {
            $start = Carbon::now()->addDays(rand(1, 30))->setTime(rand(8, 18), 0, 0);
            $end = (clone $start)->addHours(rand(1, 4));

            Reservation::create([
                'user_id' => $users->random()->id,
                'space_id' => $spaces->random()->id,
                'event_name' => 'Evento ' . ($i + 1),
                'start' => $start,
                'end' => $end,
            ]);
        }
    }
}

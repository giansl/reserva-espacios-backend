<?php

namespace Database\Factories;

use App\Models\Reservation;
use App\Models\User;
use App\Models\Space;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReservationFactory extends Factory
{
    protected $model = Reservation::class;

    public function definition()
    {
        $startDate = $this->faker->dateTimeBetween('now', '+1 month');
        $endDate = clone $startDate;
        $endDate->modify('+' . rand(1, 4) . ' hours');

        return [
            'user_id' => User::factory(),
            'space_id' => Space::factory(),
            'event_name' => $this->faker->sentence(3),
            'start' => $startDate,
            'end' => $endDate,
        ];
    }
}

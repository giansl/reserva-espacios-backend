<?php

namespace Database\Factories;

use App\Models\Space;
use Illuminate\Database\Eloquent\Factories\Factory;

class SpaceFactory extends Factory
{
    protected $model = Space::class;

    public function definition()
    {
        return [
            'name' => $this->faker->unique()->words(3, true),
            'description' => $this->faker->paragraph,
            'capacity' => $this->faker->numberBetween(1, 100),
            'type' => $this->faker->randomElement(['sala de reuniones', 'auditorio', 'oficina', 'aula']),
        ];
    }
}

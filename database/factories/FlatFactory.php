<?php

namespace Database\Factories;

use App\Models\Floor;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Flat>
 */
class FlatFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'floorId' => Floor::factory(),
            'flatNo' => $this->faker->bothify('??##'),
            'roomQty' => $this->faker->numberBetween(1, 5),
            'washroomQty' => $this->faker->numberBetween(1, 3),
            'hasVeranda' => $this->faker->boolean(50),
            'hasKitchen' => $this->faker->boolean(80),
            'rent' => $this->faker->randomFloat(2, 5000, 20000),
            'status' => $this->faker->randomElement(['available', 'occupied', 'maintenance']),
        ];
    }
}

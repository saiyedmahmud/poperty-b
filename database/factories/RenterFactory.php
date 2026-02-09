<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Renter>
 */
class RenterFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'fullName' => $this->faker->name(),
            'phone' => $this->faker->phoneNumber(),
            'nidNumber' => $this->faker->unique()->numerify('##########'),
            'address' => $this->faker->address(),
        ];
    }
}

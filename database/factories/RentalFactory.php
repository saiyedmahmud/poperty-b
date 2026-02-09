<?php

namespace Database\Factories;

use App\Models\Flat;
use App\Models\Renter;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Rental>
 */
class RentalFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startDate = $this->faker->dateTimeBetween('-6 months', 'now');

        return [
            'flatId' => Flat::factory(),
            'renterId' => Renter::factory(),
            'startDate' => $startDate,
            'endDate' => $this->faker->dateTimeBetween($startDate, '+12 months'),
            'securityDeposit' => $this->faker->randomFloat(2, 5000, 50000),
            'isActive' => $this->faker->boolean(75),
        ];
    }
}

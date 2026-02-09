<?php

namespace Database\Factories;

use App\Models\Invoice;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payment>
 */
class PaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'invoiceId' => Invoice::factory(),
            'amount' => $this->faker->randomFloat(2, 1000, 50000),
            'paymentDate' => $this->faker->dateTimeBetween('-30 days', 'now'),
            'paymentMethod' => $this->faker->randomElement(['cash', 'check', 'bank_transfer', 'online']),
        ];
    }
}

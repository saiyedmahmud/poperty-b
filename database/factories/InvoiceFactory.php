<?php

namespace Database\Factories;

use App\Models\Rental;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Invoice>
 */
class InvoiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $rentAmount = $this->faker->randomFloat(2, 10000, 50000);
        $otherBill = $this->faker->randomFloat(2, 500, 5000);
        $totalAmount = $rentAmount + $otherBill;
        $dueAmount = $this->faker->randomFloat(2, 0, $totalAmount);

        return [
            'rentalId' => Rental::factory(),
            'otherBill' => $otherBill,
            'rentAmount' => $rentAmount,
            'totalAmount' => $totalAmount,
            'dueAmount' => $dueAmount,
            'invoiceMonth' => $this->faker->dateTime()->format('Y-m'),
            'status' => $this->faker->randomElement(['pending', 'paid', 'overdue', 'partially_paid']),
        ];
    }
}

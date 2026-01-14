<?php

namespace Database\Factories;

use App\Models\Payment;
use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payment>
 */
class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'project_id' => Project::factory(),
            'amount' => fake()->numberBetween(50000, 500000),
            'payment_method' => fake()->randomElement(['transfer', 'cash', 'ewallet']),
            'notes' => fake()->optional(0.3)->sentence(),
            'payment_date' => fake()->dateTimeBetween('-30 days', 'now'),
        ];
    }

    /**
     * Payment via bank transfer.
     */
    public function transfer(): static
    {
        return $this->state(fn(array $attributes) => [
            'payment_method' => 'transfer',
        ]);
    }

    /**
     * Payment via cash.
     */
    public function cash(): static
    {
        return $this->state(fn(array $attributes) => [
            'payment_method' => 'cash',
        ]);
    }
}

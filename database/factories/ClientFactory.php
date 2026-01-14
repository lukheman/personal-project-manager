<?php

namespace Database\Factories;

use App\Models\Client;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Client>
 */
class ClientFactory extends Factory
{
    protected $model = Client::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake('id_ID')->name(),
            'phone' => fake('id_ID')->phoneNumber(),
            'referred_by_client_id' => null,
            'referral_credit_used' => 0,
        ];
    }

    /**
     * Indicate that the client was referred by another client.
     */
    public function referredBy(Client $referrer): static
    {
        return $this->state(fn(array $attributes) => [
            'referred_by_client_id' => $referrer->id,
        ]);
    }
}

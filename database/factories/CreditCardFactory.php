<?php

namespace Database\Factories;

use App\Models\CreditCard;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CreditCard>
 */
class CreditCardFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'bank' => $this->faker->company,
            'number' => $this->faker->creditCardNumber,
            'expiration' => $this->faker->creditCardExpirationDateString,
            'cvv' => $this->faker->numberBetween(100, 999),
            'limit' => $this->faker->numberBetween(100, 10000),
        ];
    }
}

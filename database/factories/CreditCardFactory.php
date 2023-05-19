<?php

namespace Database\Factories;

use App\Models\{CreditCard, User};
use Carbon\Carbon;
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
            'user_id'         => User::factory(),
            'bank'            => $this->faker->sentence(2),
            'number'          => str_repeat('' . $this->faker->randomNumber(1), 16),
            'expiration'      => Carbon::createFromFormat('m/y', $this->faker->creditCardExpirationDateString)->format('m/Y'),
            'cvv'             => $this->faker->numberBetween(100, 999),
            'limit'           => $limit = $this->faker->numberBetween(100, 10000),
            'remaining_limit' => $limit,
        ];
    }
}

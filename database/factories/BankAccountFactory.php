<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BankAccount>
 */
class BankAccountFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'bank_name'     => $this->faker->sentence,
            'type'          => $this->faker->randomElement(['Conta Corrente', 'Conta Poupança']),
            'number'        => $this->faker->randomNumber(8),
            'agency_number' => $this->faker->randomNumber(4),
            'digit'         => $this->faker->randomNumber(2),
            'balance'       => $this->faker->randomFloat(2, 0, 100000),
        ];
    }
}

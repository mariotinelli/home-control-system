<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BankAccountWithdraw>
 */
class BankAccountWithdrawFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'value'           => $this->faker->randomFloat(2, 1, 1000),
            'bank_account_id' => \App\Models\BankAccount::factory(),
            'description'     => $this->faker->sentence(1),
            'date'            => $this->faker->date(),
        ];
    }
}

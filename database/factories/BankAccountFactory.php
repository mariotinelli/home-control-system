<?php

namespace Database\Factories;

use App\Enums\BankAccountTypeEnum;
use App\Models\User;
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
            'user_id'       => User::factory(),
            'bank_name'     => fake()->sentence(1),
            'type'          => fake()->randomElement(BankAccountTypeEnum::toArray()),
            'number'        => rand(10000, 99999),
            'digit'         => rand(0, 9),
            'agency_number' => rand(1000, 9999),
            'agency_digit'  => rand(0, 9),
            'balance'       => fake()->randomFloat(2, 0, 15000),
        ];
    }
}

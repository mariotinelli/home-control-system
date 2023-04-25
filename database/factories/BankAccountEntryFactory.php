<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BankAccountEntry>
 */
class BankAccountEntryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'value'       => $this->faker->randomFloat(2, 1, 1000),
            'description' => $this->faker->sentence,
            'date'        => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }
}

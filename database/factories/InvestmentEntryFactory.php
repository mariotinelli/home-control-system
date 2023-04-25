<?php

namespace Database\Factories;

use App\Models\{Investment, InvestmentEntry};
use Illuminate\Database\Eloquent\Factories\Factory;

class InvestmentEntryFactory extends Factory
{
    protected $model = InvestmentEntry::class;

    public function definition(): array
    {
        return [
            'investment_id' => Investment::factory(),
            'amount'        => $this->faker->randomFloat(2, 1, 1000),
            'date'          => $this->faker->date(),
        ];
    }
}

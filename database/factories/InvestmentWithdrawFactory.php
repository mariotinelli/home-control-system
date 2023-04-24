<?php

namespace Database\Factories;

use App\Models\{Investment, InvestmentWithdraw};
use Illuminate\Database\Eloquent\Factories\Factory;

class InvestmentWithdrawFactory extends Factory
{
    protected $model = InvestmentWithdraw::class;

    public function definition(): array
    {
        return [
            'investment_id' => Investment::factory(),
            'amount'        => $this->faker->numberBetween(1, 1000),
            'date'          => $this->faker->date(),
        ];
    }
}

<?php

namespace Database\Factories;

use App\Models\{CoupleSpending, CoupleSpendingCategory, User};
use Illuminate\Database\Eloquent\Factories\Factory;

class CoupleSpendingFactory extends Factory
{
    protected $model = CoupleSpending::class;

    public function definition(): array
    {
        return [
            'user_id'                     => User::factory(),
            'couple_spending_category_id' => CoupleSpendingCategory::factory(),
            'description'                 => $this->faker->text(50),
            'amount'                      => $this->faker->numberBetween(1, 1000),
            'date'                        => $this->faker->date(),
        ];
    }
}

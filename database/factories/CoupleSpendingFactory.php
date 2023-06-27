<?php

namespace Database\Factories;

use App\Models\{CoupleSpending, CoupleSpendingCategory, CoupleSpendingPlace, User};
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class CoupleSpendingFactory extends Factory
{
    protected $model = CoupleSpending::class;

    public function definition(): array
    {
        return [
            'user_id'                     => User::factory(),
            'couple_spending_category_id' => CoupleSpendingCategory::factory(),
            'couple_spending_place_id'    => CoupleSpendingPlace::factory(),
            'description'                 => $this->faker->text(50),
            'amount'                      => $this->faker->numberBetween(1, 1000),
            'date'                        => Carbon::create(year: now()->year, month: rand(1, 12), day: rand(1, 28))->format('Y-m-d'),
        ];
    }
}

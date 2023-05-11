<?php

namespace Database\Factories;

use App\Models\{CoupleSpendingCategory, User};
use Illuminate\Database\Eloquent\Factories\Factory;

class CoupleSpendingCategoryFactory extends Factory
{
    protected $model = CoupleSpendingCategory::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'name'    => $this->faker->sentence(5),
        ];
    }
}
